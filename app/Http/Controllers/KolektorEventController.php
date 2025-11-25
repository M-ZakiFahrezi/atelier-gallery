<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KolektorEventController extends Controller
{
    /**
     * 1. List semua pameran
     */
    public function index()
    {
        if (!session()->has('kolektor')) {
            return redirect()->route('login')->withErrors([
                'loginError' => 'Silakan login terlebih dahulu.'
            ]);
        }

        $kolektor = session('kolektor');

        $events = DB::table('pameran')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('kolektor.event', compact('kolektor', 'events'));
    }

    /**
     * 2. Detail pameran
     */
    public function show($id)
    {
        if (!session()->has('kolektor')) {
            return redirect()->route('login')->withErrors([
                'loginError' => 'Silakan login terlebih dahulu.'
            ]);
        }

        $kolektor = session('kolektor');

        $event = DB::table('pameran')->where('id_pameran', $id)->first();
        if (!$event) abort(404);

        // Hitung karya dipajang
        $countDipajang = DB::table('karya_pameran')
            ->where('id_pameran', $id)
            ->where('status_display', 'dipajang')
            ->count();

        return view('kolektor.event-show', compact('kolektor', 'event', 'countDipajang'));
    }

    /**
     * 3. List karya seni dalam pameran (fix: cek sold via transaksi_penjualan)
     */
    public function artworks($id)
    {
        if (!session()->has('kolektor')) {
            return redirect()->route('login')->withErrors([
                'loginError' => 'Silakan login terlebih dahulu.'
            ]);
        }

        $kolektor = session('kolektor');

        $artworks = DB::table('karya_pameran as kp')
            ->join('karya_seni as k', 'kp.id_karya', '=', 'k.id_karya') // FIX
            ->leftJoin('transaksi_penjualan as t', function($join) {
                $join->on('k.id_karya', '=', 't.id_karya')              // FIX
                    ->where('t.status_transaksi', '=', 'berhasil');
            })
            ->select(
                'kp.id_karya_pameran',
                'kp.posisi_pajang',
                'kp.catatan_kuratorial',
                'k.id_karya',                                          // FIX
                'k.judul_karya',
                'k.gambar',
                'k.harga',
                DB::raw('CASE WHEN t.id_transaksi IS NULL THEN 0 ELSE 1 END AS is_sold')
            )
            ->where('kp.id_pameran', $id)
            ->where('kp.status_display', 'dipajang')
            ->orderBy('kp.posisi_pajang', 'asc')
            ->get();

        $event = DB::table('pameran')->where('id_pameran', $id)->first();
        if (!$event) abort(404);

        return view('kolektor.event-artworks', compact('kolektor', 'event', 'artworks'));
    }
}
