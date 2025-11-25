<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryaSeni;
use App\Models\Seniman;
use App\Models\Galeri;
use App\Models\TransaksiPenjualan;
use App\Models\Kolektor;
use App\Models\ActivityTimeline;
use App\Models\Favorit;
use Illuminate\Support\Facades\DB;

class KolektorController extends Controller
{
public function dashboard()
{
    $kolektor = session('kolektor');

    // ====================================================
    // QUICK STATS
    // ====================================================

    // Total karya yang sudah berhasil dibeli kolektor
    $totalKoleksi = DB::table('transaksi_penjualan')
        ->where('id_kolektor', $kolektor->id_kolektor)
        ->where('status_transaksi', 'berhasil')
        ->count();

    // Total nilai koleksi yang berhasil dibeli
    $nilaiKoleksi = DB::table('transaksi_penjualan')
        ->where('id_kolektor', $kolektor->id_kolektor)
        ->where('status_transaksi', 'berhasil')
        ->sum('harga_terjual');

    // Hitung peringkat kolektor berdasarkan total pembelian
    $peringkat = DB::table('transaksi_penjualan')
        ->select('id_kolektor', DB::raw('SUM(harga_terjual) as total'))
        ->where('status_transaksi', 'berhasil')
        ->groupBy('id_kolektor')
        ->orderByDesc('total')
        ->get()
        ->pluck('id_kolektor')
        ->search($kolektor->id_kolektor);

    $peringkat = $peringkat !== false ? $peringkat + 1 : '-';


    $statistik = [
        'koleksi'   => $totalKoleksi,
        'nilai'     => number_format($nilaiKoleksi, 0, ',', '.'),
        'peringkat' => $peringkat
    ];


    // ====================================================
    // ACTIVITY TIMELINE (5 transaksi terbaru)
    // ====================================================

    $activities = DB::table('transaksi_penjualan')
        ->join('karya_seni', 'transaksi_penjualan.id_karya', '=', 'karya_seni.id_karya')
        ->select(
            'transaksi_penjualan.*',
            'karya_seni.judul_karya',
            'karya_seni.gambar'
        )
        ->where('transaksi_penjualan.id_kolektor', $kolektor->id_kolektor)
        ->orderByDesc('tanggal_transaksi')
        ->limit(5)
        ->get();


    // ====================================================
    // RECOMMENDED ART (ambil karya yang masih tersedia)
    // ====================================================

    $recommended = DB::table('karya_seni')
    ->select(
        'karya_seni.id_karya',
        'karya_seni.judul_karya AS judul',
        'karya_seni.gambar',
        'tipe_karya.nama_tipe AS kategori',
        DB::raw("CONCAT('/images/asset/karyaSeni/', IFNULL(karya_seni.gambar, 'default.jpg')) AS image_url")
    )
    ->leftJoin('tipe_karya', 'karya_seni.id_tipe', '=', 'tipe_karya.id_tipe')
    ->limit(6)
    ->get();


    // ====================================================
    // UPCOMING EVENTS (pakai tabel pameran)
    // ====================================================

    $events = DB::table('pameran')
        ->where('tanggal_mulai', '>=', now())
        ->orderBy('tanggal_mulai')
        ->limit(6)
        ->get();


    return view('kolektor.dashboard', compact(
        'kolektor',
        'statistik',
        'activities',
        'recommended',
        'events'
    ));
}

    public function arts()
    {
        $karyaSeni = KaryaSeni::all();
        return view('kolektor.arts', compact('karyaSeni'));
    }

    public function artists()
    {
        $seniman = Seniman::all();
        return view('kolektor.artists', compact('seniman'));
    }

    public function gallery()
    {
        $galeri = Galeri::all();
        return view('kolektor.gallery', compact('galeri'));
    }

    public function leaderboard()
    {
        $leaderboard = DB::table('transaksi_penjualan')
            ->join('kolektor', 'transaksi_penjualan.id_kolektor', '=', 'kolektor.id_kolektor')
            ->select(
                'kolektor.id_kolektor',
                'kolektor.nama_kolektor',
                'kolektor.jenis_kolektor',
                DB::raw('COUNT(transaksi_penjualan.id_transaksi) as jumlah_transaksi'),
                DB::raw('SUM(transaksi_penjualan.harga_terjual) as total_pengeluaran')
            )
            ->where('status_transaksi', 'berhasil')
            ->groupBy('kolektor.id_kolektor', 'kolektor.nama_kolektor', 'kolektor.jenis_kolektor')
            ->orderByDesc('total_pengeluaran')
            ->get();

        return view('kolektor.leaderboard', compact('leaderboard'));
    }

public function myGallery()
{
    $kolektor = session('kolektor');

    $karya = DB::table('transaksi_penjualan')
        ->join('karya_seni', 'transaksi_penjualan.id_karya', '=', 'karya_seni.id_karya')
        ->leftJoin('tipe_karya', 'karya_seni.id_tipe', '=', 'tipe_karya.id_tipe')
        ->where('transaksi_penjualan.id_kolektor', $kolektor->id_kolektor)
        ->where('transaksi_penjualan.status_transaksi', 'berhasil') // FIXED
        ->select(
            'karya_seni.id_karya',
            'karya_seni.judul_karya',
            'karya_seni.gambar',
            'karya_seni.deskripsi',
            'karya_seni.tahun_pembuatan',
            'karya_seni.harga',
            'tipe_karya.nama_tipe as kategori'
        )
        ->get();

    return view('kolektor.my-gallery', [
        'kolektor' => $kolektor,
        'karya' => $karya
    ]);
}

public function favorites()
{
    $kolektor = session('kolektor');

    if (!$kolektor) {
        return redirect()->route('login');
    }

    $favorites = Favorit::where('id_kolektor', $kolektor->id_kolektor)
        ->with(['karya'])
        ->get();

    return view('kolektor.favorites', compact('favorites', 'kolektor'));
}

public function removeFavorit($id)
{
    $favorit = Favorit::find($id);

    if (!$favorit) {
        return redirect()->back()->with('error', 'Data favorit tidak ditemukan.');
    }

    // Ambil kolektor dari session
    $kolektor = session('kolektor');

    if (!$kolektor) {
        return redirect()->route('login')->with('error', 'Silakan login.');
    }

    // Pastikan kolektor hanya menghapus miliknya
    if ($favorit->id_kolektor != $kolektor->id_kolektor) {
        return redirect()->back()->with('error', 'Anda tidak memiliki izin.');
    }

    $favorit->delete();

    return redirect()->back()->with('success', 'Karya berhasil dihapus dari favorit.');
}

public function toggleFavorit($id_karya)
{
    $kolektor = session('kolektor');

    if (!$kolektor) {
        return redirect()->route('login');
    }

    // cek apakah sudah ada
    $favorit = Favorit::where('id_kolektor', $kolektor->id_kolektor)
                      ->where('id_karya', $id_karya)
                      ->first();

    if ($favorit) {
        // Jika sudah ada → hapus
        $favorit->delete();
        return back()->with('info', 'Dihapus dari favorit.');
    } else {
        // Jika belum ada → tambah
        Favorit::create([
            'id_kolektor' => $kolektor->id_kolektor,
            'id_karya' => $id_karya
        ]);
        return back()->with('success', 'Ditambahkan ke favorit.');
    }
}

public function profil()
{
    $kolektor = session('kolektor');

    if (!$kolektor) {
        return redirect()->route('login');
    }

    // Hitung karya dimiliki
    $karyaCount = DB::table('transaksi_penjualan')
        ->where('id_kolektor', $kolektor->id_kolektor)
        ->where('status_transaksi', 'berhasil')
        ->count();

    // Hitung favorit
    $favoritCount = Favorit::where('id_kolektor', $kolektor->id_kolektor)->count();

    // Hitung transaksi
    $transaksiCount = DB::table('transaksi_penjualan')
        ->where('id_kolektor', $kolektor->id_kolektor)
        ->count();

    return view('kolektor.profil', compact(
        'kolektor',
        'karyaCount',
        'favoritCount',
        'transaksiCount'
    ));
}

/**
 * List orders for logged-in kolektor (Order Tracking page)
 */
public function orderTracking()
{
    if (!session()->has('kolektor')) {
        return redirect()->route('login');
    }
    $kolektor = session('kolektor');

    $orders = DB::table('transaksi_penjualan as t')
        ->leftJoin('karya_seni as k', 't.id_karya', '=', 'k.id_karya')
        ->leftJoin('seniman as s', 'k.id_seniman', '=', 's.id_seniman')
        ->where('t.id_kolektor', $kolektor->id_kolektor)
        ->select(
            't.id_transaksi',
            't.kode_transaksi',
            't.tanggal_transaksi',
            't.harga_terjual',
            't.status_bayar',
            't.status_transaksi',
            't.status_pengiriman',
            't.tanggal_pengiriman',
            't.alamat_pengiriman',
            't.metode_pembayaran',
            'k.judul_karya',
            'k.gambar as gambar_karya',
            's.nama_seniman'
        )
        ->orderBy('t.tanggal_transaksi', 'desc')
        ->get();

    // summary boxes
    $totalOrders = $orders->count();
    $inTransit = $orders->where('status_pengiriman', 'dikirim')->count();
    $completed = $orders->where('status_pengiriman', 'diterima')->count();

    return view('kolektor.order-tracking', compact(
        'kolektor', 'orders', 'totalOrders', 'inTransit', 'completed'
    ));
}

/**
 * Return single order detail as JSON for modal (AJAX)
 */
public function orderTrackingDetail($id)
{
    if (!session()->has('kolektor')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $kolektor = session('kolektor');

    $order = DB::table('transaksi_penjualan as t')
        ->leftJoin('karya_seni as k', 't.id_karya', '=', 'k.id_karya')
        ->leftJoin('seniman as s', 'k.id_seniman', '=', 's.id_seniman')
        ->where('t.id_transaksi', $id)
        ->where('t.id_kolektor', $kolektor->id_kolektor)
        ->select(
            't.*',
            'k.judul_karya',
            'k.gambar as gambar_karya',
            'k.harga as harga_karya',
            's.nama_seniman'
        )
        ->first();

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    return response()->json($order);
}



}
