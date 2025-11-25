<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_transaksi' => 'required|integer'
        ]);

        $id_transaksi = $request->id_transaksi;

        // Ambil data transaksi
        $transaksi = DB::table('transaksi_penjualan')->where('id_transaksi', $id_transaksi)->first();
        if (!$transaksi) {
            return back()->with('error', 'Transaksi tidak ditemukan');
        }

        // Buat order_id baru (WAJIB UNIK)
        $orderId = "ORDER-" . $id_transaksi . "-" . time();

        // Setup Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Data yang dikirim ke Midtrans API
        $payload = [
            "payment_type" => "qris",
            "transaction_details" => [
                "order_id" => $orderId,
                "gross_amount" => (int) $transaksi->harga_terjual,
            ],
            "qris" => [
                "acquirer" => "qris"
            ]

        ];

        try {
            // Panggil Midtrans Charge API
            $response = \Midtrans\CoreApi::charge($payload);

            // Simpan data QR & order_id
            DB::table('transaksi_penjualan')
                ->where('id_transaksi', $id_transaksi)
                ->update([
                    'order_id' => $orderId,
                    'qris_url' => $response->actions[0]->url ?? null,
                    'transaction_status' => $response->transaction_status ?? 'pending'
                ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat QRIS: ' . $e->getMessage());
        }

        // Redirect ke halaman QRIS
        return redirect()->route('transaksi.qris.show', $id_transaksi);
    }


    // ================================================================
    // CALLBACK MIDTRANS - NOTIFIKASI
    // ================================================================
public function callback(Request $request)
{
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = false;

    $notif = new \Midtrans\Notification();

    $orderId = $notif->order_id;
    $transactionStatus = $notif->transaction_status;

    $parts = explode("-", $orderId);
    $id_transaksi = $parts[1] ?? null;

    if (!$id_transaksi) return response()->json(['error' => 'Invalid order_id'], 400);

    if ($transactionStatus === 'settlement') {
        DB::table('transaksi_penjualan')
            ->where('id_transaksi', $id_transaksi)
            ->update([
                'status_transaksi' => 'berhasil',
                'status_bayar' => 'lunas',
                'tanggal_pelunasan' => now(),
                'transaction_status' => 'settlement'
            ]);
    } elseif (in_array($transactionStatus, ['expire', 'cancel', 'failure'])) {
        DB::table('transaksi_penjualan')
            ->where('id_transaksi', $id_transaksi)
            ->update([
                'status_transaksi' => 'dibatalkan',
                'transaction_status' => $transactionStatus
            ]);
    }

    return response()->json(['message' => 'Callback processed']);
}

/**
* Cek status transaksi berdasarkan database
*/
public function checkStatus($id_transaksi)
{
    $trx = DB::table('transaksi_penjualan')->where('id_transaksi', $id_transaksi)->first();

    if (!$trx || !$trx->order_id) {
        return response()->json(['status' => 'not_found']);
    }

    // Setup Midtrans
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = false;

    try {
        $status = \Midtrans\Transaction::status($trx->order_id);
        $transactionStatus = $status->transaction_status;

        // ======= Update database sesuai status =======
        if ($transactionStatus === 'settlement') {
            DB::table('transaksi_penjualan')
                ->where('id_transaksi', $id_transaksi)
                ->update([
                    'status_bayar' => 'lunas',
                    'status_transaksi' => 'berhasil',
                    'tanggal_pelunasan' => now(),
                    'transaction_status' => $transactionStatus
                ]);
        }

        if (in_array($transactionStatus, ['cancel', 'expire'])) {
            DB::table('transaksi_penjualan')
                ->where('id_transaksi', $id_transaksi)
                ->update([
                    'status_bayar' => 'belum lunas',
                    'status_transaksi' => 'dibatalkan',
                    'transaction_status' => $transactionStatus
                ]);
        }

        return response()->json([
            'status' => $transactionStatus
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}


}
