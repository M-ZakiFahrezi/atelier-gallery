@extends('layouts.admin')

@section('title', 'Tambah Galeri')

@push('page-styles')
<style>
.galeri-page {
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}

.galeri-box {
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

body.dark-mode .galeri-box {
    background: rgba(0,0,0,0.8);
    border-color: rgba(255,215,0,0.3);
    color: #f5e9c3;
}

.galeri-box h2 {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    color: #b89e3f;
    margin-bottom: 20px;
    text-align: center;
}

.galeri-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
}

.galeri-form input[type="text"],
.galeri-form textarea,
.galeri-form input[type="file"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 1rem;
    transition: border 0.3s ease;
}

.galeri-form input:focus,
.galeri-form textarea:focus {
    border-color: #d4af37;
    outline: none;
}

.galeri-form button {
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

.galeri-form button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212,175,55,0.4);
}

body.dark-mode .galeri-form input,
body.dark-mode .galeri-form textarea {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,215,0,0.3);
    color: #f5e9c3;
}
</style>
@endpush

@section('content')
<div class="galeri-page">
    <div class="galeri-box">
        <h2>Tambah Galeri Baru</h2>

        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="galeri-form">
            @csrf

            <label>Nama Galeri:</label>
            <input type="text" name="nama_galeri" value="{{ old('nama_galeri') }}" required>

            <label>Alamat:</label>
            <textarea name="alamat" rows="3">{{ old('alamat') }}</textarea>

            <label>Kontak:</label>
            <input type="text" name="kontak" value="{{ old('kontak') }}">

            <label>Website:</label>
            <input type="text" name="website" value="{{ old('website') }}">

            <label>Gambar Galeri:</label>
            <input type="file" name="gambar" accept="image/*">

            <button type="submit">➕ Tambah Galeri</button>
        </form>
    </div>
</div>
@endsection
