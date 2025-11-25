@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
<section style="max-width:620px; margin:auto;">
    
    <h2 style="font-family:'Cinzel', serif; color:#d4af37; font-size:1.6rem; margin-bottom:18px;">
        Edit Event
    </h2>

    <form action="{{ route('admin.event.update', $event->id_pameran) }}" method="POST"
          style="background:#fff; padding:24px; border-radius:12px; box-shadow:0 6px 16px rgba(0,0,0,0.06);">
        @csrf

        {{-- Nama Pameran --}}
        <div style="margin-bottom:16px;">
            <label style="font-weight:600; color:#4a4a4a;">Nama Pameran</label>
            <input type="text" name="nama_pameran" value="{{ old('nama_pameran', $event->nama_pameran) }}"
                   required
                   style="width:100%; padding:10px 12px; border:1px solid #ccc; border-radius:8px;">
            @error('nama_pameran')
                <p style="color:red; font-size:0.85rem;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal Mulai --}}
        <div style="margin-bottom:16px;">
            <label style="font-weight:600; color:#4a4a4a;">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $event->tanggal_mulai) }}"
                   required
                   style="width:100%; padding:10px 12px; border:1px solid #ccc; border-radius:8px;">
            @error('tanggal_mulai')
                <p style="color:red; font-size:0.85rem;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal Selesai --}}
        <div style="margin-bottom:16px;">
            <label style="font-weight:600; color:#4a4a4a;">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $event->tanggal_selesai) }}"
                   required
                   style="width:100%; padding:10px 12px; border:1px solid #ccc; border-radius:8px;">
            @error('tanggal_selesai')
                <p style="color:red; font-size:0.85rem;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lokasi Pameran --}}
        <div style="margin-bottom:16px;">
            <label style="font-weight:600; color:#4a4a4a;">Lokasi Pameran</label>
            <input type="text" name="lokasi_pameran" value="{{ old('lokasi_pameran', $event->lokasi_pameran) }}"
                   required
                   style="width:100%; padding:10px 12px; border:1px solid #ccc; border-radius:8px;">
            @error('lokasi_pameran')
                <p style="color:red; font-size:0.85rem;">{{ $message }}</p>
            @enderror
        </div>

        {{-- ID Galeri --}}
        <div style="margin-bottom:18px;">
            <label style="font-weight:600; color:#4a4a4a;">Galeri</label>
            <select name="id_galeri" required
                    style="width:100%; padding:10px 12px; border:1px solid #ccc; border-radius:8px;">
                <option value="">-- Pilih Galeri --</option>
                @foreach($galeri as $g)
                    <option value="{{ $g->id_galeri }}" {{ (old('id_galeri', $event->id_galeri) == $g->id_galeri) ? 'selected' : '' }}>
                        {{ $g->nama_galeri }}
                    </option>
                @endforeach
            </select>
            @error('id_galeri')
                <p style="color:red; font-size:0.85rem;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div style="margin-top:20px; text-align:right;">
            <a href="{{ route('admin.event.index') }}"
               style="padding:10px 14px; margin-right:10px; text-decoration:none; border-radius:8px; border:1px solid #ccc; color:#666;">
                Batal
            </a>

            <button type="submit"
                    style="padding:10px 18px; border:none; border-radius:8px; background:#d4af37; color:#fff; font-weight:600;">
                Simpan Perubahan
            </button>
        </div>

    </form>
</section>
@endsection
