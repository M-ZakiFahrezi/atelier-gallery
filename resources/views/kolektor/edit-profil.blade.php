@extends('layouts.kolektor-dashboard')

@section('title', 'Edit Profil')

@push('page-styles')
<style>
/* ----------------------------------------------------
   THEME VARIABLES (mengikuti My Gallery)
------------------------------------------------------*/

:root {
    --gold-strong: #d4af37;
    --gold-soft: #e9d9a3;
    --gold-matte: #c2a86d;

    --shadow-deep: rgba(0,0,0,0.45);
    --shadow-soft: rgba(0,0,0,0.25);

    /* Light Mode */
    --bg-main-light: #faf7ef;
    --bg-card-light: rgba(255,255,255,0.70);
    --text-main-light: #3a2f21;

    /* Dark Mode */
    --bg-main-dark: #0f0f0f;
    --bg-card-dark: rgba(25,25,25,0.65);
    --text-main-dark: #e6d8b8;
}

body.light-mode {
    --bg-main: var(--bg-main-light);
    --bg-card: var(--bg-card-light);
    --text-main: var(--text-main-light);
}

body.dark-mode {
    --bg-main: var(--bg-main-dark);
    --bg-card: var(--bg-card-dark);
    --text-main: var(--text-main-dark);
}

/* ----------------------------------------------------
   WRAPPER
------------------------------------------------------*/

.edit-wrapper {
    color: var(--text-main);
    animation: fadeIn 0.6s ease forwards;
    max-width: 780px;
    margin: 0 auto;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ----------------------------------------------------
   CARD STYLING
------------------------------------------------------*/

.edit-card {
    background: var(--bg-card);
    border-radius: 18px;
    padding: 32px;
    border: 1px solid rgba(212,175,55,0.28);
    box-shadow: 0 4px 14px var(--shadow-soft);
    backdrop-filter: blur(14px);
    transition: 0.3s;
}

.edit-card:hover {
    box-shadow: 0 6px 20px var(--shadow-deep);
}

/* ----------------------------------------------------
   HEADER SECTION (Avatar + Title)
------------------------------------------------------*/

.profile-header {
    text-align: center;
    margin-bottom: 30px;
}

.profile-header img {
    width: 115px;
    height: 115px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--gold-strong);
    box-shadow: 0 4px 12px var(--shadow-soft);
}

.profile-header h1 {
    margin-top: 18px;
    font-family: 'Cinzel', serif;
    color: var(--gold-matte);
    font-size: 1.9rem;
    letter-spacing: 1px;
}

/* ----------------------------------------------------
   FORM FIELDS
------------------------------------------------------*/

.edit-form label {
    font-weight: 600;
    margin-top: 12px;
    display: block;
    color: var(--gold-matte);
}

.edit-form input {
    width: 100%;
    padding: 11px 15px;
    border-radius: 10px;
    border: 1px solid rgba(212,175,55,0.35);
    background: rgba(255,255,255,0.35);
    backdrop-filter: blur(8px);
    margin-top: 3px;
    margin-bottom: 14px;
    transition: 0.25s;
    color: var(--text-main);
}

body.dark-mode .edit-form input {
    background: rgba(35,35,35,0.5);
}

.edit-form input:focus {
    outline: none;
    border-color: var(--gold-strong);
    box-shadow: 0 0 7px rgba(212,175,55,0.55);
}

/* ----------------------------------------------------
   ACTION BUTTONS
------------------------------------------------------*/

.action-buttons {
    text-align: center;
    margin-top: 22px;
}

.action-buttons button,
.action-buttons a {
    background: linear-gradient(145deg, #d4af37, #b89127);
    color: white;
    border: none;
    padding: 11px 28px;
    border-radius: 12px;
    cursor: pointer;
    text-decoration: none;
    font-weight: 600;
    transition: 0.25s;
    box-shadow: 0 3px 10px var(--shadow-soft);
}

.action-buttons button:hover,
.action-buttons a:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px var(--shadow-deep);
}

.action-buttons a {
    background: rgba(170,60,60,0.85);
}

</style>
@endpush

@section('dashboard-content')
<div class="edit-wrapper">

    <div class="edit-card">

        {{-- Avatar + Title --}}
        <div class="profile-header">
            <img src="/images/user.png" alt="Avatar">
            <h1>Edit Profil Kolektor</h1>
        </div>

        {{-- Error Notification --}}
        @if ($errors->any())
            <div style="color:#ff6b6b; margin-bottom:18px; text-align:center;">
                @foreach ($errors->all() as $error)
                    <div>⚠ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form class="edit-form" method="POST" action="{{ route('kolektor.profil.update') }}">
            @csrf

            <label>Nama Kolektor</label>
            <input type="text" name="nama_kolektor" value="{{ $kolektor->nama_kolektor }}" required>

            <label>Alamat</label>
            <input type="text" name="alamat" value="{{ $kolektor->alamat }}" required>

            <label>Kontak</label>
            <input type="text" name="kontak" value="{{ $kolektor->kontak }}" required>

            <label>Username</label>
            <input type="text" name="username" value="{{ $kolektor->username }}" required>

            <label>Password (kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" placeholder="••••••••">

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="••••••••">


            <div class="action-buttons">
                <button type="submit">💾 Simpan</button>
                <a href="{{ route('kolektor.profil') }}">Kembali</a>
            </div>

        </form>

    </div>
</div>
@endsection
