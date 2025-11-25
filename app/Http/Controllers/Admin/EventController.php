<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pameran;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // ambil semua pameran, order by tanggal_mulai desc
        $events = DB::table('pameran')
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(12); // pagination, bisa diubah

        // hitung karya dipajang per pameran (optimalkan dengan one query)
        $karyaCounts = DB::table('karya_pameran')
            ->select('id_pameran', DB::raw('COUNT(*) as total'))
            ->groupBy('id_pameran')
            ->pluck('total', 'id_pameran'); // collection [id_pameran => total]

        return view('admin.event.index', compact('events', 'karyaCounts'));
    }
    
public function create()
{
    // ambil daftar galeri (untuk dropdown)
    $galeri = DB::table('galeri')->get();

    return view('admin.event.create', compact('galeri'));
}

public function store(Request $request)
{
    // validasi input
    $request->validate([
        'nama_pameran' => 'required|max:100',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'lokasi_pameran' => 'required|max:255',
        'id_galeri' => 'required|integer'
    ]);

    // Insert ke database
    DB::table('pameran')->insert([
        'nama_pameran' => $request->nama_pameran,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'lokasi_pameran' => $request->lokasi_pameran,
        'id_galeri' => $request->id_galeri
    ]);

    return redirect()->route('admin.event.index')->with('success', 'Event berhasil ditambahkan!');
}

public function edit($id)
    {
        $event = DB::table('pameran')->where('id_pameran', $id)->first();
        $galeri = DB::table('galeri')->get();

        if (! $event) {
            return redirect()->route('admin.event.index')->with('error', 'Pameran tidak ditemukan.');
        }

        return view('admin.event.edit', compact('event', 'galeri'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pameran' => 'required|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi_pameran' => 'required|max:255',
            'id_galeri' => 'required|integer'
        ]);

        $updated = DB::table('pameran')->where('id_pameran', $id)->update([
            'nama_pameran' => $request->nama_pameran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi_pameran' => $request->lokasi_pameran,
            'id_galeri' => $request->id_galeri
        ]);

        return redirect()->route('admin.event.index')->with('success', 'Event berhasil diperbarui.');
    }


public function destroy($id)
{
    $event = Pameran::findOrFail($id);

    // Jika ingin sekaligus menghapus relasi karya_pameran
    \DB::table('karya_pameran')->where('id_pameran', $id)->delete();

    $event->delete();

    return redirect()->route('admin.event.index')->with('success', 'Event berhasil dihapus.');
}


public function artworks($id)
{
    $event = DB::table('pameran')->where('id_pameran', $id)->first();

    // Ambil karya yang sudah terhubung ke event
    $karyaEvent = DB::table('karya_pameran')
        ->join('karya_seni', 'karya_pameran.id_karya', '=', 'karya_seni.id_karya')
        ->join('seniman', 'karya_seni.id_seniman', '=', 'seniman.id_seniman')
        ->where('karya_pameran.id_pameran', $id)
        ->select(
            'karya_pameran.*',
            'karya_seni.judul_karya',
            'seniman.nama_seniman'
        )
        ->get();

    // Daftar karya yang masih tersedia untuk dipilih
    $karya = DB::table('karya_seni')->get();

    return view('admin.event.artworks', compact('event', 'karyaEvent', 'karya'));
}

public function addArtwork(Request $request, $id)
{
    $request->validate([
        'id_karya' => 'required|integer',
        'status_display' => 'required|in:dipajang,disimpan',
        'posisi_pajang' => 'nullable|max:100',
        'catatan_kuratorial' => 'nullable'
    ]);

    DB::table('karya_pameran')->insert([
        'id_karya' => $request->id_karya,
        'id_pameran' => $id,
        'status_display' => $request->status_display,
        'posisi_pajang' => $request->posisi_pajang,
        'catatan_kuratorial' => $request->catatan_kuratorial
    ]);

    return back()->with('success', 'Karya berhasil ditambahkan ke event.');
}

public function removeArtwork($id)
{
    DB::table('karya_pameran')->where('id_karya_pameran', $id)->delete();

    return back()->with('success', 'Karya berhasil dihapus dari event.');
}


}