@extends('layouts.kolektor-dashboard')

@section('title', 'My Favorites')

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
   FAVORITES WRAPPER
------------------------------------------------------*/
.fav-wrapper {
    color: var(--text-main);
    animation: fadeIn 0.6s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

.fav-title {
    font-family: 'Cinzel', serif;
    font-size: 2rem;
    color: var(--gold-matte);
    margin-bottom: 10px;
}

.fav-subtitle {
    opacity: 0.75;
    margin-bottom: 25px;
}

/* GRID */
.fav-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 22px;
}

/* CARD */
.fav-card {
    background: var(--bg-card);
    border-radius: 14px;
    padding: 14px;
    border: 1px solid rgba(212,175,55,0.25);
    box-shadow: 0 3px 12px var(--shadow-soft);
    backdrop-filter: blur(10px);
    transition: 0.25s ease;
    position: relative;
}

.fav-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px var(--shadow-deep);
}

/* IMAGE */
.fav-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
}

/* INFO */
.fav-info {
    margin-top: 12px;
}

.fav-info h4 {
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--text-main);
}

.fav-info .kategori {
    color: var(--gold-matte);
    font-size: 0.9rem;
}

.fav-info .tahun {
    opacity: 0.7;
    font-size: 0.85rem;
}

.fav-info .harga {
    margin-top: 6px;
    font-weight: bold;
    color: var(--gold-strong);
}

/* REMOVE BUTTON */
.fav-remove-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(0,0,0,0.55);
    color: white;
    border: none;
    padding: 6px 10px;
    font-size: 0.8rem;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s ease;
}

.fav-remove-btn:hover {
    background: var(--gold-strong);
    color: black;
}
</style>
@endpush

@section('dashboard-content')

<div class="fav-wrapper">

    <h1 class="fav-title">My Favorites</h1>
    <p class="fav-subtitle">Karya seni yang Anda simpan dalam daftar favorit.</p>

    <div class="fav-grid">

        @forelse ($favorites as $fav)
            <div class="fav-card">

                {{-- REMOVE BUTTON --}}
                <form action="{{ route('kolektor.favorit.remove', $fav->id_favorit) }}" 
                    method="POST" 
                    onsubmit="return confirm('Hapus dari favorit?')">

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="fav-remove-btn">✕</button>
                </form>

                {{-- IMAGE --}}
                <img src="/images/asset/karyaSeni/{{ $fav->karya->gambar ?? 'default.jpg' }}" alt="Karya Favorit">

                <div class="fav-info">
                    <h4>{{ $fav->karya->judul_karya }}</h4>
                    <div class="kategori">{{ $fav->karya->kategori ?? 'Tidak diketahui' }}</div>
                    <div class="tahun">Tahun: {{ $fav->karya->tahun_pembuatan }}</div>
                    <div class="harga">Rp {{ number_format($fav->karya->harga,0,',','.') }}</div>
                </div>
            </div>

        @empty

            <p style="opacity:0.7;">Anda belum memiliki favorit.</p>

        @endforelse

    </div>

</div>

@endsection
