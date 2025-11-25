<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryaSeni;
use App\Models\Seniman;
use App\Models\Galeri;

class AdminController extends Controller
{
    // === Dashboard Admin ===
    public function dashboard()
    {
        $jumlahKarya = KaryaSeni::count();
        $jumlahSeniman = Seniman::count();
        $jumlahGaleri = Galeri::count();
        $admin = session('admin'); // ambil data admin dari session

        return view('admin.dashboard', compact('jumlahKarya', 'jumlahSeniman', 'jumlahGaleri', 'admin'));
    }


    // =========================
    // ===== KARYA SENI ========
    // =========================
    public function karyaIndex()
    {
        $karyaSeni = KaryaSeni::all();
        return view('admin.karya.index', compact('karyaSeni'));
    }

    public function karyaCreate()
    {
        $seniman = Seniman::all();
        return view('admin.karya.create', compact('seniman'));
    }

    public function karyaStore(Request $request)
    {
        $request->validate([
            'judul_karya' => 'required|string|max:100',
            'tahun_pembuatan' => 'required|integer',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'status_karya' => 'required|in:tersedia,terjual,dipinjam,disimpan',
            'id_seniman' => 'required|exists:seniman,id_seniman',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ubah ke nullable
        ]);

        $fileName = null;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = 'karya-' . time() . '.' . $file->extension();
            $file->move(public_path('images/asset/karyaSeni'), $fileName);
        }

        KaryaSeni::create([
            'judul_karya' => $request->judul_karya,
            'tahun_pembuatan' => $request->tahun_pembuatan,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'status_karya' => $request->status_karya,
            'id_seniman' => $request->id_seniman,
            'id_tipe' => 1,
            'gambar' => $fileName,
        ]);

        return redirect()->route('admin.karya.index')->with('success', 'Karya seni berhasil ditambahkan.');
    }

    public function karyaEdit($id)
    {
        $karya = KaryaSeni::findOrFail($id);
        $seniman = Seniman::all();
        return view('admin.karya.edit', compact('karya', 'seniman'));
    }

    public function karyaUpdate(Request $request, $id)
    {
        $karya = KaryaSeni::findOrFail($id);

        $request->validate([
            'judul_karya' => 'required|string|max:100',
            'tahun_pembuatan' => 'required|integer',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'status_karya' => 'required|in:tersedia,terjual,dipinjam,disimpan',
            'id_seniman' => 'required|exists:seniman,id_seniman',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle gambar baru
        if ($request->hasFile('gambar')) {
            // hapus gambar lama jika ada
            if ($karya->gambar && file_exists(public_path('images/asset/karyaSeni/' . $karya->gambar))) {
                unlink(public_path('images/asset/karyaSeni/' . $karya->gambar));
            }

            $fileName = 'karya-' . time() . '.' . $request->file('gambar')->extension();
            $request->file('gambar')->move(public_path('images/asset/karyaSeni'), $fileName);
            $karya->gambar = $fileName;
        }

        // Update field lain
        $karya->update([
            'judul_karya' => $request->judul_karya,
            'tahun_pembuatan' => $request->tahun_pembuatan,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'status_karya' => $request->status_karya,
            'id_seniman' => $request->id_seniman,
            'gambar' => $karya->gambar, // pastikan tetap tersimpan
        ]);

        return redirect()->route('admin.karya.index')->with('success', 'Karya seni berhasil diperbarui.');
    }

    public function karyaDestroy($id)
    {
        $karya = KaryaSeni::findOrFail($id);

        if ($karya->gambar && file_exists(public_path('images/asset/karyaSeni/' . $karya->gambar))) {
            unlink(public_path('images/asset/karyaSeni/' . $karya->gambar));
        }

        $karya->delete();

        return redirect()->route('admin.karya.index')->with('success', 'Karya seni berhasil dihapus.');
    }


    // =========================
    // ===== SENIMAN ===========
    // =========================
    public function senimanIndex()
    {
        $seniman = Seniman::all();
        return view('admin.seniman.index', compact('seniman'));
    }

    public function senimanCreate()
    {
        return view('admin.seniman.create');
    }

    public function senimanStore(Request $request)
    {
        $request->validate([
            'nama_seniman' => 'required|string|max:100',
            'asal' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'bio' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fileName = null;
        if ($request->hasFile('gambar')) {
            $fileName = 'seniman-' . time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/asset/seniman'), $fileName);
        }

        Seniman::create($request->only(['nama_seniman', 'asal', 'tanggal_lahir', 'bio']) + ['gambar' => $fileName]);

        return redirect()->route('admin.seniman.index')->with('success', 'Seniman berhasil ditambahkan.');
    }

    public function senimanEdit($id)
    {
        $seniman = Seniman::findOrFail($id);
        return view('admin.seniman.edit', compact('seniman'));
    }

    public function senimanUpdate(Request $request, $id)
    {
        $seniman = Seniman::findOrFail($id);
        $request->validate([
            'nama_seniman' => 'required|string|max:100',
            'asal' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'bio' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($seniman->gambar) {
                @unlink(public_path('images/asset/seniman/' . $seniman->gambar));
            }
            $fileName = 'seniman-' . time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/asset/seniman'), $fileName);
            $seniman->gambar = $fileName;
        }

        $seniman->update($request->only(['nama_seniman', 'asal', 'tanggal_lahir', 'bio']));
        $seniman->save();

        return redirect()->route('admin.seniman.index')->with('success', 'Seniman berhasil diperbarui.');
    }

    public function senimanDestroy($id)
    {
        $seniman = Seniman::findOrFail($id);
        if ($seniman->gambar) {
            @unlink(public_path('images/asset/seniman/' . $seniman->gambar));
        }
        $seniman->delete();
        return redirect()->route('admin.seniman.index')->with('success', 'Seniman berhasil dihapus.');
    }

    // =========================
    // ===== GALERI ===========
    // =========================
    public function galeriIndex()
    {
        $galeri = Galeri::all();
        return view('admin.galeri.index', compact('galeri'));
    }

    public function galeriCreate()
    {
        return view('admin.galeri.create');
    }

    public function galeriStore(Request $request)
    {
        $validated = $request->validate([
            'nama_galeri' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $filename = time() . '.' . $request->gambar->getClientOriginalExtension();
            $request->gambar->move(public_path('images/asset/gallery'), $filename);
            $validated['gambar'] = $filename;
        }

        Galeri::create($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil ditambahkan!');
    }

    public function galeriEdit($id)
    {
        $galeri = Galeri::findOrFail($id);
        return view('admin.galeri.edit', compact('galeri'));
    }

    public function galeriUpdate(Request $request, $id)
    {
        $galeri = Galeri::findOrFail($id);

        $validated = $request->validate([
            'nama_galeri' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($galeri->gambar && file_exists(public_path('images/asset/gallery/' . $galeri->gambar))) {
                unlink(public_path('images/asset/gallery/' . $galeri->gambar));
            }

            $filename = time() . '.' . $request->gambar->getClientOriginalExtension();
            $request->gambar->move(public_path('images/asset/gallery'), $filename);
            $validated['gambar'] = $filename;
        }

        $galeri->update($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil diperbarui!');
    }

    public function galeriDestroy($id)
    {
        $galeri = Galeri::findOrFail($id);

        if ($galeri->gambar && file_exists(public_path('images/asset/gallery/' . $galeri->gambar))) {
            unlink(public_path('images/asset/gallery/' . $galeri->gambar));
        }

        $galeri->delete();
        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil dihapus!');
    }
}
