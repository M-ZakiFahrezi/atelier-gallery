@extends('layouts.kolektor-dashboard')

@section('title', 'Profil Kolektor')

@push('page-styles')
<style>

:root {
    --gold-strong: #d4af37;
    --gold-soft: #e9d9a3;
    --gold-matte: #c2a86d;

    --shadow-deep: rgba(0,0,0,0.45);
    --shadow-soft: rgba(0,0,0,0.2);

    --bg-main-light: #faf7ef;
    --bg-card-light: rgba(255,255,255,0.78);
    --text-main-light: #3a2f21;

    --bg-main-dark: #0f0f0f;
    --bg-card-dark: rgba(22,22,22,0.78);
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

.profile-wrapper {
    color: var(--text-main);
    animation: fadeIn 0.6s ease-forward;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ==============================================
   Layout diperbaiki, card kiri diperbesar
================================================= */
.profile-layout {
    display: grid;
    grid-template-columns: 320px 1fr !important;
    gap: 30px;
    margin-top: 20px;
}

.profile-card {
    background: var(--bg-card);
    border: 1px solid rgba(212,175,55,0.28);
    border-radius: 18px;
    padding: 25px;
    text-align: center;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 16px var(--shadow-soft);
    transition: 0.3s;
}

.profile-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px var(--shadow-deep);
}

.profile-avatar {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--gold-strong);
    margin-bottom: 15px;
}

.profile-name {
    font-family: 'Cinzel', serif;
    font-size: 1.4rem;
    color: var(--gold-matte);
    margin-bottom: 5px;
}

.profile-type {
    font-size: 0.95rem;
    opacity: 0.8;
    margin-bottom: 15px;
}

/* ==============================================
   Statistik - dijamin tidak overflow
================================================= */
.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    margin-top: 20px;
    gap: 10px;
}

.stat-box {
    background: rgba(255,255,255,0.1);
    padding: 12px 8px;
    border-radius: 12px;
    border: 1px solid rgba(212,175,55,0.25);
}

.stat-box strong {
    display: block;
    font-size: 1.1rem;
    color: var(--gold-strong);
}

.stat-box small {
    display: block;
    margin-top: 2px;
    font-size: 0.8rem;
}

/* ==============================================
   Info Panel kanan - diperkecil agar tidak kosong
================================================= */
.info-panel {
    background: var(--bg-card);
    border: 1px solid rgba(212,175,55,0.25);
    border-radius: 18px;
    padding: 30px;
    backdrop-filter: blur(10px);
    box-shadow: 0 3px 12px var(--shadow-soft);
    max-width: 700px; /* << Panel kanan lebih kecil */
}

.info-title {
    font-size: 1.4rem;
    font-family: 'Cinzel', serif;
    color: var(--gold-matte);
    margin-bottom: 12px;
}

.info-list {
    margin-top: 15px;
    line-height: 1.8;
}

.info-list p {
    margin-bottom: 5px;
}

.info-list strong {
    color: var(--gold-strong);
}

</style>
@endpush



@section('dashboard-content')
<div class="profile-wrapper">

    <h1 class="gallery-title">Profil Kolektor</h1>
    <p class="gallery-subtitle">Informasi pribadi Anda sebagai bagian dari Atelier Gallery.</p>

    <div class="profile-layout">

        <!-- CARD FOTO + STATISTIK -->
        <div class="profile-card">

            <img src="/images/user.png" class="profile-avatar">

            <div class="profile-name">{{ $kolektor->nama_kolektor }}</div>
            <div class="profile-type">{{ ucfirst($kolektor->jenis_kolektor) }}</div>

            <div class="profile-stats">
                <div class="stat-box">
                    <strong>{{ $karyaCount }}</strong>
                    <small>Karya Dimiliki</small>
                </div>
                <div class="stat-box">
                    <strong>{{ $favoritCount }}</strong>
                    <small>Favorit</small>
                </div>
                <div class="stat-box">
                    <strong>{{ $transaksiCount }}</strong>
                    <small>Transaksi</small>
                </div>
            </div>

        </div>

        <!-- PANEL INFORMASI -->
        <div class="info-panel">

            <div class="info-title">Informasi Pribadi</div>

            <div class="info-list">
                <p><strong>Nama             :</strong> {{ $kolektor->nama_kolektor }}</p>
                <p><strong>Jenis Kolektor   :</strong> {{ ucfirst($kolektor->jenis_kolektor) }}</p>
                <p><strong>Alamat           :</strong> {{ $kolektor->alamat }}</p>
                <p><strong>Kontak           :</strong> {{ $kolektor->kontak }}</p>
                <p><strong>Username         :</strong> {{ $kolektor->username }}</p>
            </div>

        </div>

    </div>

</div>
@endsection
