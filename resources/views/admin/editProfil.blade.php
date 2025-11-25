@extends('layouts.admin')

@section('title', 'Edit Profil Admin')

@push('page-styles')
<style>
.edit-container {
    max-width: 700px;
    margin: 0 auto;
    background: rgba(255,255,255,0.95);
    padding: 30px 40px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
body.dark-mode .edit-container {
    background: rgba(0,0,0,0.85);
    color: #f5e9c3;
}
.edit-container h2 {
    font-family:'Cinzel', serif;
    font-size:1.8rem;
    color:#d4af37;
    margin-bottom:20px;
}
.edit-container form {
    display:flex;
    flex-direction:column;
    gap:15px;
}
.edit-container label { font-weight:600; }
.edit-container input {
    padding:10px 12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:1rem;
}
body.dark-mode .edit-container input { background: rgba(255,255,255,0.05); border-color: #d4af37; color:#f5e9c3; }
.save-btn {
    background: linear-gradient(135deg,#d4af37,#b89127);
    color:#fff;
    padding:10px 20px;
    border-radius:10px;
    font-weight:500;
    border:none;
    cursor:pointer;
    transition: transform 0.2s;
}
.save-btn:hover { transform: translateY(-2px); }
</style>
@endpush

@section('content')
<section class="fade-up">
    <div class="edit-container">
        <h2>✏️ Edit Profil Admin</h2>
        <form action="{{ route('admin.updateProfil') }}" method="POST">
            @csrf
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" value="{{ session('admin')->nama ?? '' }}">

            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="{{ session('admin')->username ?? '' }}">

            <label for="password">Password <small>(Kosongkan jika tidak diganti)</small></label>
            <input type="password" name="password" id="password">

            <button type="submit" class="save-btn">💾 Simpan Perubahan</button>
        </form>
    </div>
</section>
@endsection
