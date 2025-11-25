@extends('layouts.admin')

@section('title', 'Tambah Seniman')

@push('page-styles')
<style>
.seniman-page {
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}

.seniman-box {
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

body.dark-mode .seniman-box {
    background: rgba(0,0,0,0.8);
    border-color: rgba(255,215,0,0.3);
    color: #f5e9c3;
}

.seniman-box h2 {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    color: #b89e3f;
    margin-bottom: 20px;
    text-align: center;
}

/* form style */
.seniman-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
}

.seniman-form input[type="text"],
.seniman-form input[type="date"],
.seniman-form textarea,
.seniman-form input[type="file"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 1rem;
    transition: border 0.3s ease;
}

.seniman-form input:focus,
.seniman-form textarea:focus {
    border-color: #d4af37;
    outline: none;
}

/* button */
.seniman-form button {
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

.seniman-form button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212,175,55,0.4);
}

/* dark mode input colors */
body.dark-mode .seniman-form input,
body.dark-mode .seniman-form textarea {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,215,0,0.3);
    color: #f5e9c3;
}

/* error box */
.error-box {
    background: rgba(255, 0, 0, 0.1);
    border: 1px solid rgba(255, 0, 0, 0.3);
    color: #b00000;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

body.dark-mode .error-box {
    background: rgba(255, 50, 50, 0.15);
    border-color: rgba(255, 80, 80, 0.4);
    color: #ffcccc;
}
</style>
@endpush

@section('content')
<div class="seniman-page">
    <div class="seniman-box">
        <h2>Tambah Seniman Baru</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.seniman.store') }}" method="POST" enctype="multipart/form-data" class="seniman-form">
            @csrf

            <label>Nama Seniman:</label>
            <input type="text" name="nama_seniman" value="{{ old('nama_seniman') }}" required>

            <label>Asal:</label>
            <input type="text" name="asal" value="{{ old('asal') }}">

            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">

            <label>Bio:</label>
            <textarea name="bio" rows="4">{{ old('bio') }}</textarea>

            <label>Foto Seniman:</label>
            <input type="file" name="gambar" accept="image/*">

            <button type="submit">💾 Simpan Seniman</button>
        </form>
    </div>
</div>
@endsection
