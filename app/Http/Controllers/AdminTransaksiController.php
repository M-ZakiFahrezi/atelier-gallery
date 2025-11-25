<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransaksiController extends Controller
{
    // 1. Halaman daftar transaksi pending
    public function index()
    {
        $transaksi = DB::table('transaksi_penjualan as t')
            ->join('karya_seni as k', 't.id_karya', '=', 'k.id_karya_seni')
            ->join('kolektor as c', 't.id_kolektor', '=', 'c.id_kolektor')
            ->where('t.status_transaksi', 'pending')
            ->select('t.*', 'k.judul_karya', 'c.nama as nama_kolektor')
            ->orderBy('t.tanggal_transaksi', 'desc')
            ->get();

        return view('admin.transaksi.index', compact('transaksi'));
    }

    // 2. Detail transaksi
    public function show($id)
    {
        $t = DB::table('transaksi_penjualan as t')
            ->join('karya_seni as k', 't.id_karya', '=', 'k.id_karya_seni')
            ->join('kolektor as c', 't.id_kolektor', '=', 'c.id_kolektor')
            ->where('t.id_transaksi', $id)
            ->select('t.*', 'k.*', 'c.nama as nama_kolektor', 'c.email')
            ->first();

        if (!$t) abort(404);

        return view('admin.transaksi.show', compact('t'));
    }

    // 3. Admin melakukan konfirmasi
    public function confirm($id)
    {
        $trans = DB::table('transaksi_penjualan')->where('id_transaksi', $id)->first();
        if (!$trans) abort(404);

        // update status
        DB::table('transaksi_penjualan')
            ->where('id_transaksi', $id)
            ->update([
                'status_transaksi' => 'berhasil',
                'status_bayar'     => 'lunas',
                'tanggal_pelunasan'=> now(),
            ]);

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dikonfirmasi!');
    }
}
