@extends('layouts.kolektor-dashboard')

@section('title', 'Collector Dashboard')

@push('page-styles')
<style>
/* ----------------------------------------------------
   GLOBAL VARIABLES — THEME SUPPORT
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

/* AUTO THEME HANDLING */
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
   GENERAL PAGE
------------------------------------------------------*/
.dashboard-section {
    padding: 40px 30px;
    color: var(--text-main);
    animation: fadeIn 0.6s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ----------------------------------------------------
   HERO BANNER
------------------------------------------------------*/
.hero-banner {
    width: 100%;
    background: url("{{ asset('images/banner-art.png') }}") center/cover;
    padding: 80px 40px;
    border-radius: 18px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 25px var(--shadow-deep);
}

.hero-banner::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(174, 167, 127, 0.35), rgba(70, 64, 64, 0.85));
}

/* Dark Mode */
body.dark-mode .hero-banner {
    background: url("{{ asset('images/dark-banner-art.png') }}") center/cover;
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
}

.hero-title {
    font-family: 'Cinzel', serif;
    font-size: 2.7rem;
    color: var(--gold-soft);
    text-shadow: 0 0 12px rgba(255,220,150,0.55);
    margin-bottom: 6px;
}

.hero-sub {
    opacity: 0.92;
}

/* ----------------------------------------------------
   QUICK STATS
------------------------------------------------------*/
.quick-stats {
    margin-top: 35px;
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(180px,1fr));
    gap: 22px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid rgba(212,175,55,0.25);
    border-radius: 14px;
    padding: 22px;
    backdrop-filter: blur(10px);
    text-align: center;
    transition: 0.25s ease;
    box-shadow: 0 3px 12px var(--shadow-soft);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px var(--shadow-deep);
}

.stat-title {
    font-family: 'Cinzel', serif;
    color: var(--gold-matte);
    margin-bottom: 6px;
}

.stat-value {
    font-size: 1.45rem;
    font-weight: 700;
    color: var(--text-main);
}

/* ----------------------------------------------------
   TIMELINE
------------------------------------------------------*/
.timeline-box {
    margin-top: 50px;
    background: var(--bg-card);
    padding: 25px;
    border-radius: 16px;
    border: 1px solid rgba(212,175,55,0.22);
    box-shadow: 0 3px 12px var(--shadow-soft);
}

.timeline-title {
    font-family: 'Cinzel', serif;
    font-size: 1.45rem;
    color: var(--gold-matte);
    margin-bottom: 14px;
}

.timeline-item {
    padding: 12px 0 12px 18px;
    border-left: 2px solid rgba(212,175,55,0.35);
    position: relative;
}

.timeline-item strong {
    color: var(--text-main);
}

.timeline-item::before {
    content: "";
    width: 10px;
    height: 10px;
    background: var(--gold-strong);
    border-radius: 50%;
    position: absolute;
    left: -6px;
    top: 18px;
}

/* ----------------------------------------------------
   SECTIONS (Recommended & Events)
------------------------------------------------------*/
.section-card {
    margin-top: 45px;
    background: var(--bg-card);
    border-radius: 16px;
    border: 1px solid rgba(212,175,55,0.22);
    padding: 25px;
    box-shadow: 0 3px 12px var(--shadow-soft);
}

.section-title {
    font-family: 'Cinzel', serif;
    font-size: 1.35rem;
    color: var(--gold-matte);
    margin-bottom: 18px;
}

.grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap: 20px;
}

.art-card,
.event-card {
    background: rgba(255,255,255,0.05);
    padding: 15px;
    border-radius: 14px;
    border: 1px solid rgba(212,175,55,0.22);
    backdrop-filter: blur(8px);
    transition: 0.25s ease;
    box-shadow: 0 2px 10px var(--shadow-soft);
}

body.light-mode .art-card,
body.light-mode .event-card {
    background: rgba(255,255,255,0.45);
}

.art-card:hover,
.event-card:hover {
    transform: translateY(-4px);
}

.art-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}

</style>
@endpush

@section('dashboard-content')

<div class="dashboard-section">

    <!-- ======================================================
         HERO SECTION
    ======================================================= -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">Welcome Back, {{ $kolektor->nama_kolektor }}</h1>
            <p class="hero-sub">An exclusive art world awaits you.</p>
        </div>
    </div>

    <!-- ======================================================
         QUICK STATS
    ======================================================= -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-title">Total Koleksi</div>
            <div class="stat-value">{{ $statistik['koleksi'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Peringkat Anda</div>
            <div class="stat-value">#{{ $statistik['peringkat'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Nilai Koleksi</div>
            <div class="stat-value">{{ $statistik['nilai'] }}</div>
        </div>
    </div>

    <!-- ======================================================
         ACTIVITY TIMELINE
    ======================================================= -->
    <div class="timeline-box">
        <div class="timeline-title">Jejak Aktivitas Kolektor</div>
        <ul class="timeline-list">
            @forelse($activities as $act)
                <li class="timeline-item">
                    <strong>{{ $act->judul_karya }}</strong>
                    <small>{{ \Carbon\Carbon::parse($act->tanggal_transaksi)->diffForHumans() }}</small>
                </li>
            @empty
                <p>Belum ada aktivitas.</p>
            @endforelse
        </ul>
    </div>

    <!-- ======================================================
         RECOMMENDED ART
    ======================================================= -->
    <div class="section-card">
        <div class="section-title">Recommended Art for You</div>

        <div class="grid-3">
            @foreach($recommended as $art)
                <div class="art-card">
                    <img src="{{ $art->image_url }}" alt="">
                    <p><strong>{{ $art->judul }}</strong></p>
                    <p style="opacity:0.8;">{{ $art->kategori }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ======================================================
         UPCOMING EVENTS & AUCTIONS
    ======================================================= -->
    <div class="section-card">
        <div class="section-title">Upcoming Events & Auctions</div>

        <div class="grid-3">
            @foreach($events as $event)
                <div class="event-card">
                    <p class="event-date">{{ $event->tanggal_mulai }}</p>
                    <p class="event-title">{{ $event->nama_pameran }}</p>
                    <p style="opacity:0.8;">{{ $event->lokasi_pameran }}</p>
                </div>
            @endforeach
        </div>
    </div>

</div>

@endsection
