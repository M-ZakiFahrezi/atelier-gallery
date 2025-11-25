<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Seniman;
use App\Models\KaryaSeni;

use App\Mail\ContactMail;
use App\Mail\ContactConfirmation;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function home()
    {
        // Ambil karya seni secara acak (3 item)
        $featuredArts = \App\Models\KaryaSeni::inRandomOrder()->take(3)->get();

        // Ambil seniman secara acak (3 item)
        $highlightArtists = \App\Models\Seniman::inRandomOrder()->take(3)->get();

        // Ambil galeri secara acak (5 item)
        $galleryPreview = \App\Models\Galeri::inRandomOrder()->take(5)->get();

        return view('home', compact('featuredArts', 'highlightArtists', 'galleryPreview'));
    }

    public function arts()
    {
        $karyaSeni = KaryaSeni::all();
        return view('arts', compact('karyaSeni'));
    }

    public function artists()
    {
        $seniman = Seniman::all();
        return view('artists', compact('seniman'));
    }

    public function gallery()
    {
        $galeri = Galeri::all();
        return view('gallery', compact('galeri'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Kirim email ke galeri
        Mail::to('info@ateliergallery.com')->send(new ContactMail($validated));

        // Kirim konfirmasi ke user
        Mail::to($validated['email'])->send(new ContactConfirmation($validated));

        // Kembali dengan flash message
        return back()->with('success', 'Thank you! Your message has been sent.');
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

        return view('leaderboard', compact('leaderboard'));
    }
}
