@extends('layouts.admin')

@section('title', 'Edit Karya Seni')

@push('page-styles')
<style>
.karya-page {
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}

.karya-box {
    width: 100%;
    max-width: 700px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(212,175,55,0.25);
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

body.dark-mode .karya-box {
    background: rgba(0,0,0,0.8);
    border-color: rgba(255,215,0,0.3);
    color: #f5e9c3;
}

.karya-box h2 {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    color: #b89e3f;
    margin-bottom: 20px;
    text-align: center;
}

.karya-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
}

.karya-form input[type="text"],
.karya-form input[type="number"],
.karya-form textarea,
.karya-form select,
.karya-form input[type="file"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 1rem;
    transition: border 0.3s ease;
}

.karya-form input:focus,
.karya-form textarea:focus,
.karya-form select:focus {
    border-color: #d4af37;
    outline: none;
}

.karya-form button {
    background: linear-gradient(135deg, #d4af37, #b89127);
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: block;
    width: 100%;
}

.karya-form button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212,175,55,0.4);
}

body.dark-mode .karya-form input,
body.dark-mode .karya-form textarea,
body.dark-mode .karya-form select {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,215,0,0.3);
    color: #f5e9c3;
}

.preview-img {
    display: block;
    margin: 10px 0 20px;
    border-radius: 8px;
    max-height: 160px;
    object-fit: cover;
}
</style>
@endpush

@section('content')
<div class="karya-page">
    <div class="karya-box">
        <h2>Edit Karya Seni</h2>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px; border-radius: 8px;">
                <ul style="margin-bottom: 0;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.karya.update', $karya->id_karya) }}" method="POST" enctype="multipart/form-data" class="karya-form">
            @csrf
            @method('PUT')

            <label>Judul Karya:</label>
            <input type="text" name="judul_karya" value="{{ old('judul_karya', $karya->judul_karya) }}" required>

            <label>Tahun Pembuatan:</label>
            <input type="number" name="tahun_pembuatan" value="{{ old('tahun_pembuatan', $karya->tahun_pembuatan) }}" required>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="4">{{ old('deskripsi', $karya->deskripsi) }}</textarea>

            <label>Harga:</label>
            <input type="number" step="0.01" name="harga" value="{{ old('harga', $karya->harga) }}" required>

            <label>Status:</label>
            <select name="status_karya" required>
                <option value="tersedia" {{ old('status_karya', $karya->status_karya) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="terjual" {{ old('status_karya', $karya->status_karya) == 'terjual' ? 'selected' : '' }}>Terjual</option>
                <option value="dipinjam" {{ old('status_karya', $karya->status_karya) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="disimpan" {{ old('status_karya', $karya->status_karya) == 'disimpan' ? 'selected' : '' }}>Disimpan</option>
            </select>

            <label>Seniman:</label>
            <select name="id_seniman" required>
                @foreach($seniman as $s)
                    <option value="{{ $s->id_seniman }}" {{ old('id_seniman', $karya->id_seniman) == $s->id_seniman ? 'selected' : '' }}>
                        {{ $s->nama_seniman }}
                    </option>
                @endforeach
            </select>

            <label>Gambar Saat Ini:</label>
            @if($karya->gambar)
                <img src="{{ asset('images/asset/karyaSeni/' . $karya->gambar) }}" alt="Gambar Karya" class="preview-img">
            @else
                <p><em>Tidak ada gambar</em></p>
            @endif

            <label>Ganti Gambar (Opsional):</label>
            <input type="file" name="gambar" accept="image/*">

            <button type="submit">💾 Perbarui Karya</button>
        </form>
    </div>
</div>
@endsection
