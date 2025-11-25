<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\KaryaSeni;
use App\Models\TransaksiPenjualan;
use App\Helpers\MidtransConfig;
use Midtrans\CoreApi;
use Midtrans\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf; // if installed


class TransactionController extends Controller
{
    /* ============================================================
       HALAMAN CHECKOUT
    ============================================================ */
    public function checkout($id_karya)
    {
        if (!session()->has('kolektor')) {
            return redirect()->route('login')->withErrors(['loginError' => 'Silakan login terlebih dahulu.']);
        }

        $kolektor = session('kolektor');

        $karya = DB::table('karya_seni')->where('id_karya', $id_karya)->first();
        if (!$karya) {
            return redirect()->back()->with('error', 'Karya tidak ditemukan.');
        }

        // jika sudah terjual
        $isSold = DB::table('penjualan')->where('id_karya', $id_karya)->exists();

        // jika ada pending transaksi
        $hasPending = DB::table('transaksi_penjualan')
            ->where('id_karya', $id_karya)
            ->where('status_transaksi', 'pending')
            ->exists();

        if ($isSold || $hasPending) {
            return redirect()->back()->with('error', 'Maaf, karya ini sudah tidak tersedia.');
        }

        return view('kolektor.transaksi.checkout', compact('kolektor', 'karya'));
    }

    /* ============================================================
       PROSES CHECKOUT → INSERT KE TABEL transaksi_penjualan
    ============================================================ */
public function processCheckout(Request $request)
{
    // Pastikan kolektor login
    if (!session()->has('kolektor')) {
        return redirect()->route('login');
    }
    $kolektor = session('kolektor');

    // Validasi input minimal (sesuaikan nama field di form)
    $validated = $request->validate([
        'id_karya' => 'required|integer|exists:karya_seni,id_karya',
        'alamat_pengiriman' => 'required|string|max:500',
        'catatan_transaksi' => 'nullable|string|max:1000',
    ]);

    $id_karya = $validated['id_karya'];

    // Ambil data karya
    $karya = DB::table('karya_seni')->where('id_karya', $id_karya)->first();
    if (!$karya) {
        return back()->with('error', 'Karya tidak ditemukan.');
    }

    // Cek apakah sudah terjual atau ada pending (opsional, jaga konsistensi)
    $isSold = DB::table('penjualan')->where('id_karya', $id_karya)->exists();
    $hasPending = DB::table('transaksi_penjualan')
        ->where('id_karya', $id_karya)
        ->where('status_transaksi', 'pending')
        ->exists();

    if ($isSold || $hasPending) {
        return back()->with('error', 'Maaf, karya ini sudah tidak tersedia.');
    }

    // Waktu sekarang & expire 15 menit
    $now = Carbon::now();
    $expireAt = $now->copy()->addMinutes(15);

    // INSERT transaksi awal (mencerminkan struktur DB-mu sebelumnya)
    try {
        $trxId = DB::table('transaksi_penjualan')->insertGetId([
            'kode_transaksi'    => '', // akan diupdate setelah insert
            'tanggal_transaksi' => $now,
            'harga_terjual'     => $karya->harga,
            'metode_pembayaran' => 'qris',
            'status_bayar'      => 'belum lunas',
            'id_karya'          => $id_karya,
            'id_kolektor'       => $kolektor->id_kolektor,
            'status_transaksi'  => 'pending',
            'catatan_transaksi' => $request->input('catatan_transaksi'),
            'alamat_pengiriman' => $request->input('alamat_pengiriman'),
            'expire_at'         => $expireAt,
        ]);
    } catch (\Exception $e) {
        // Gagal insert → log & tampilkan pesan
        \Log::error('Gagal insert transaksi_penjualan: '.$e->getMessage());
        return back()->with('error', 'Gagal membuat transaksi. Silakan coba lagi.');
    }

    // Generate kode_transaksi (<= 20 char sesuai catatan sebelumnya)
    $kodeTransaksi = 'TRX-' . $trxId . '-' . date('Hi');
    DB::table('transaksi_penjualan')
        ->where('id_transaksi', $trxId)
        ->update(['kode_transaksi' => $kodeTransaksi]);

    // Generate orderId Midtrans (unik)
    $orderId = "ORDER-{$trxId}-" . time();

    // MIDTRANS CONFIG (tetap seperti sebelumnya, sandbox false/true sesuai config)
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    $params = [
        "payment_type" => "qris",
        "transaction_details" => [
            "order_id" => $orderId,
            "gross_amount" => (int)$karya->harga,
        ],
        // Pakai object kosong agar Midtrans memilih acquirer otomatis (lebih stabil di sandbox)
        "qris" => new \stdClass()
    ];

    // Panggil Midtrans: try/catch untuk mencegah 502 mem-bom aplikasi
    try {
        $charge = \Midtrans\CoreApi::charge($params);
    } catch (\Exception $e) {
        // Log error dari Midtrans agar bisa dicek nanti
        \Log::error('Midtrans charge error: '.$e->getMessage(), ['params' => $params]);

        // Update transaksi: beri catatan bahwa pembuatan QR gagal (opsional)
        DB::table('transaksi_penjualan')
            ->where('id_transaksi', $trxId)
            ->update([
                'order_id' => $orderId,
                'transaction_status' => 'error',
                // biarkan status_transaksi = pending agar admin bisa cek
            ]);

        // Jangan hapus transaksi agar jejak tercatat — beri tahu user
        return back()->with('error', 'Gagal membuat QRIS (gateway sedang bermasalah). Silakan coba lagi beberapa menit.');
    }

    // Ambil URL QRIS dari response (cek struktur object Midtrans)
    $qrisUrl = null;
    $transactionStatus = null;
    try {
        // beberapa pengembalian midtrans bisa memiliki actions array atau qr_string
        if (!empty($charge->actions) && is_array($charge->actions)) {
            // cari action yang punya url
            foreach ($charge->actions as $act) {
                if (!empty($act->url)) {
                    $qrisUrl = $act->url;
                    break;
                }
            }
        }
        // fallback: ada property qris_string atau next_url (bergantung implementasi)
        if (!$qrisUrl && !empty($charge->qr_string)) {
            $qrisUrl = $charge->qr_string;
        }
        if (!$qrisUrl && !empty($charge->actions[0]->url)) {
            $qrisUrl = $charge->actions[0]->url;
        }

        $transactionStatus = $charge->transaction_status ?? null;
    } catch (\Exception $e) {
        \Log::warning('Gagal parse response midtrans: '.$e->getMessage(), ['response' => $charge]);
    }

    if (!$qrisUrl) {
        // Jika QR tidak tersedia, update status lalu beri tahu user
        DB::table('transaksi_penjualan')
            ->where('id_transaksi', $trxId)
            ->update([
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus ?? 'no_qr'
            ]);

        \Log::error('Midtrans tidak mengembalikan qris URL', ['charge' => $charge]);

        return back()->with('error', 'QRIS tidak tersedia dari Midtrans. Silakan coba lagi.');
    }

    // Simpan qris_url dan order_id ke transaksi
    DB::table('transaksi_penjualan')
        ->where('id_transaksi', $trxId)
        ->update([
            'order_id' => $orderId,
            'qris_url' => $qrisUrl,
            'transaction_status' => $transactionStatus ?? 'pending'
        ]);

    // Redirect ke halaman qris (sesuai route yang kamu gunakan)
    return redirect()->route('kolektor.transaksi.qris.show', $trxId);
}


    /* ============================================================
       LIST TRANSAKSI KOLEKTOR
    ============================================================ */
    public function index()
    {
        if (!session()->has('kolektor')) {
            return redirect()->route('login');
        }

        $kolektor = session('kolektor');

        $transaksis = DB::table('transaksi_penjualan')
            ->where('id_kolektor', $kolektor->id_kolektor)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        return view('kolektor.transaksi.index', compact('kolektor', 'transaksis'));
    }

    /* ============================================================
       UPLOAD BUKTI PEMBAYARAN
    ============================================================ */
    public function uploadProof(Request $request, $id_transaksi)
    {
        if (!session()->has('kolektor')) {
            return redirect()->route('login');
        }

        $kolektor = session('kolektor');

        $trx = DB::table('transaksi_penjualan')
            ->where('id_transaksi', $id_transaksi)
            ->where('id_kolektor', $kolektor->id_kolektor)
            ->first();

        if (!$trx) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $request->validate([
            'bukti' => 'required|image|max:4096'
        ]);

        $path = $request->file('bukti')->store('bukti_pembayaran', 'public');

        DB::table('transaksi_penjualan')
            ->where('id_transaksi', $id_transaksi)
            ->update([
                'bukti_pembayaran' => $path,
                'status_bayar' => 'belum lunas',
                'status_transaksi' => 'pending',
                'tanggal_pelunasan' => null
            ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    /* ============================================================
       FORM UPLOAD BUKTI QRIS
    ============================================================ */
    public function formKonfirmasiQris($id_transaksi)
    {
        if (!session()->has('kolektor')) return redirect()->route('login');

        $kolektor = session('kolektor');

        $trx = DB::table('transaksi_penjualan')
            ->where('id_transaksi', $id_transaksi)
            ->where('id_kolektor', $kolektor->id_kolektor)
            ->first();

        if (!$trx) abort(404);

        return view('kolektor.transaksi.qris_upload', compact('trx'));
    }

    /* ============================================================
       UPLOAD BUKTI QRIS
    ============================================================ */
    public function konfirmasiQris(Request $request, $id_transaksi)
    {
        if (!session()->has('kolektor')) return redirect()->route('login');

        $kolektor = session('kolektor');

        $trx = DB::table('transaksi_penjualan')
            ->where('id_transaksi', $id_transaksi)
            ->where('id_kolektor', $kolektor->id_kolektor)
            ->first();

        if (!$trx) abort(404);

        $request->validate([
            'bukti_pembayaran' => 'required|image|max:2048',
            'catatan' => 'nullable|string'
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti_bayar', 'public');

        DB::table('transaksi_penjualan')
            ->where('id_transaksi', $id_transaksi)
            ->update([
                'bukti_pembayaran' => $path,
                'status_transaksi' => 'pending',
                'catatan_transaksi' => $request->catatan,
            ]);

        return redirect()->route('kolektor.transaksi.sukses', $id_transaksi);
    }

    /* ============================================================
       HALAMAN SUKSES
    ============================================================ */
    public function sukses($id)
    {
        $transaksi = TransaksiPenjualan::findOrFail($id);
        return view('kolektor.transaksi.sukses', compact('transaksi'));
    }

/* ============================================================
    QRIS PAGE
============================================================ */
public function qris($id_transaksi)
{
    $transaksi = DB::table('transaksi_penjualan')->where('id_transaksi', $id_transaksi)->first();
    return view('kolektor.transaksi.qris', compact('transaksi'));
}

/* ================================================================
   BATALKAN TRANSAKSI
=============================================================== */
public function batalkan($id_transaksi)
{
    $transaksi = TransaksiPenjualan::findOrFail($id_transaksi);

    // Hanya boleh batalkan jika pending
    if ($transaksi->status_transaksi !== 'pending') {
        return redirect()->back()->with('error', 'Transaksi tidak dapat dibatalkan.');
    }

    $transaksi->status_transaksi = 'dibatalkan';
    $transaksi->save();

    return redirect()->route('kolektor.event')
        ->with('success', 'Transaksi berhasil dibatalkan.');

}

/**
 * DETAIL: tampilkan 1 transaksi + riwayat transaksi kolektor
 */
public function show($id_transaksi)
{
    if (!session()->has('kolektor')) {
        return redirect()->route('login');
    }
    $kolektor = session('kolektor');

    // Ambil transaksi lengkap
    $trx = $this->getTransaksiLengkap($id_transaksi, $kolektor->id_kolektor);
    if (!$trx) return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');

    // Ambil detail karya berdasarkan id_karya dari transaksi
    $karya = DB::table('karya_seni')
        ->where('id_karya', $trx->id_karya)
        ->first();

    // Riwayat transaksi
    $riwayat = DB::table('transaksi_penjualan')
        ->where('id_kolektor', $kolektor->id_kolektor)
        ->orderBy('tanggal_transaksi', 'desc')
        ->get();

    return view('kolektor.transaksi.detail', [
        'kolektor' => $kolektor,
        'trx' => $trx,
        'riwayat' => $riwayat,
        'karya' => $karya  // ⬅ WAJIB dikirim
    ]);
}


/**
 * VIEW INVOICE (HTML)
 */
public function invoice($id_transaksi)
{
    if (!session()->has('kolektor')) {
        return redirect()->route('login');
    }
    $kolektor = session('kolektor');

    $trx = $this->getTransaksiLengkap($id_transaksi, $kolektor->id_kolektor);
    if (!$trx) return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');

    return view('kolektor.transaksi.invoice', [
        'kolektor' => $kolektor,
        'trx' => $trx
    ]);
}

/**
 * DOWNLOAD PDF INVOICE
 */
public function invoicePdf($id_transaksi)
{
    if (!session()->has('kolektor')) {
        return redirect()->route('login');
    }
    $kolektor = session('kolektor');

    $trx = $this->getTransaksiLengkap($id_transaksi, $kolektor->id_kolektor);
    if (!$trx) return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kolektor.transaksi.invoice_pdf', [
        'kolektor' => $kolektor,
        'trx' => $trx
    ])->setPaper('A4', 'portrait');

    return $pdf->download('invoice-'.$trx->kode_transaksi.'.pdf');
}

private function getTransaksiLengkap($id_transaksi, $id_kolektor)
{
    return DB::table('transaksi_penjualan as t')
        ->join('karya_seni as k', 'k.id_karya', '=', 't.id_karya')
        ->leftJoin('seniman as s', 's.id_seniman', '=', 'k.id_seniman')
        ->LeftJoin('karya_pameran as kp', 'kp.id_karya', '=', 'k.id_karya')
        ->LeftJoin('pameran as p', 'p.id_pameran', '=', 'kp.id_pameran')
        ->where('t.id_transaksi', $id_transaksi)
        ->where('t.id_kolektor', $id_kolektor)
        ->select(
            't.*',
            'k.judul_karya',
            'k.harga as harga_karya',
            'k.tahun_pembuatan',
            'k.deskripsi',

            'k.gambar as gambar_karya',
            's.nama_seniman',

            // pameran
            'p.nama_pameran',
            'p.tanggal_mulai',
            'p.tanggal_selesai',
            'p.lokasi_pameran'
        )
        ->first();
}


}

