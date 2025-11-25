@extends('layouts.kolektor-dashboard')

@section('title', 'My Gallery')

@push('page-styles')
<style>
/* ----------------------------------------------------
   THEME VARIABLES (Light & Dark Mode)
------------------------------------------------------*/
:root {
    --gold-strong: #d4af37;
    --gold-soft: #e9d9a3;
    --gold-matte: #c2a86d;

    --shadow-deep: rgba(0,0,0,0.45);
    --shadow-soft: rgba(0,0,0,0.25);

    /* LIGHT MODE */
    --bg-main-light: #faf7ef;
    --bg-card-light: rgba(255,255,255,0.75);
    --text-main-light: #3a2f21;

    /* DARK MODE */
    --bg-main-dark: #0f0f0f;
    --bg-card-dark: rgba(25,25,25,0.75);
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
   GALLERY WRAPPER
------------------------------------------------------*/
.gallery-wrapper {
    color: var(--text-main);
    animation: fadeIn 0.6s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

/* HEADER */
.gallery-title {
    font-family: 'Cinzel', serif;
    font-size: 2rem;
    color: var(--gold-matte);
    margin-bottom: 10px;
}

.gallery-subtitle {
    opacity: 0.75;
    margin-bottom: 25px;
}

/* GRID */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 22px;
}

/* CARD */
.gallery-card {
    background: var(--bg-card);
    border-radius: 14px;
    padding: 14px;
    border: 1px solid rgba(212,175,55,0.25);
    box-shadow: 0 3px 12px var(--shadow-soft);
    backdrop-filter: blur(10px);
    transition: 0.25s ease;
    cursor: pointer;
}

.gallery-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px var(--shadow-deep);
}

/* IMAGE */
.gallery-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
}

/* TEXT */
.gallery-info {
    margin-top: 12px;
}

.gallery-info h4 {
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--text-main);
}

.gallery-info .kategori {
    color: var(--gold-matte);
    font-size: 0.9rem;
}

.gallery-info .tahun {
    opacity: 0.7;
    font-size: 0.85rem;
}

.gallery-info .harga {
    margin-top: 6px;
    font-weight: bold;
    color: var(--gold-strong);
}

</style>
@endpush

@section('dashboard-content')
<div class="gallery-wrapper">

    <h1 class="gallery-title">My Gallery</h1>
    <p class="gallery-subtitle">Koleksi karya seni yang telah Anda miliki.</p>

    <div class="gallery-grid">

        @forelse ($karya as $item)
            <div class="gallery-card">
                <img src="/images/asset/karyaSeni/{{ $item->gambar ?? 'default.jpg' }}" alt="Karya Seni">

                <div class="gallery-info">
                    <h4>{{ $item->judul_karya }}</h4>

                    <div class="kategori">{{ $item->kategori }}</div>

                    <div class="tahun">Tahun: {{ $item->tahun_pembuatan }}</div>

                    <div class="harga">Rp {{ number_format($item->harga,0,',','.') }}</div>
                </div>
            </div>
        @empty
            <p style="opacity:0.7;">Anda belum memiliki karya seni.</p>
        @endforelse

    </div>

</div>
@endsection
