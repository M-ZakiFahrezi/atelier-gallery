@extends('layouts.kolektor')

@section('title', 'Leaderboard - Atelier Gallery')

@push('page-styles')
<style>
    /* ---------- BODY ---------- */
    body {
        background: linear-gradient(145deg, #f8f5ef 0%, #e8dcc3 100%);
        font-family: 'Cinzel', serif;
        color: #3a2e1f;
        overflow-x: hidden;
        transition: background 0.6s ease, color 0.6s ease;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("{{ asset('images/bg-pola.png') }}") center/cover repeat;
        opacity: 0.25;
        pointer-events: none;
        z-index: -2;
        filter: brightness(1.05) saturate(0.9);
        transition: opacity 0.6s ease, filter 0.6s ease;
    }

    body::after {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.25) 40%, rgba(255,255,255,0) 80%);
        opacity: 0.15;
        pointer-events: none;
        z-index: -1;
        animation: shimmerMove 14s ease-in-out infinite alternate;
        mix-blend-mode: soft-light;
        transition: opacity 0.6s ease;
    }

    @keyframes shimmerMove {
        0% { transform: translateX(-25%) translateY(-10%) rotate(0deg); }
        100% { transform: translateX(25%) translateY(10%) rotate(3deg); }
    }

    /* ---------- HEADER ---------- */
    .leaderboard-header {
        text-align: center;
        margin: 40px 0 10px;
    }

    .leaderboard-header h1 {
        font-size: 2.8rem;
        color: #3a2e1f;
        text-shadow: 0 0 6px rgba(212,175,55,0.4);
        transition: color 0.6s ease, text-shadow 0.6s ease;
    }

    .leaderboard-header p {
        font-size: 1rem;
        color: #4a4032;
        margin-top: 10px;
        transition: color 0.6s ease;
    }

    /* ---------- PODIUM ---------- */
    .podium {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 40px;
        margin: 60px auto 40px;
        flex-wrap: wrap;
    }

    .podium-card {
        text-align: center;
        background: rgba(255,255,255,0.85);
        border: 2px solid #d4af37;
        border-radius: 20px;
        padding: 20px;
        width: 200px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.15);
        transition: all 0.4s ease, box-shadow 0.4s ease, border 0.4s ease, background 0.6s ease;
        position: relative;
        cursor: pointer;
    }

    .podium-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(212,175,55,0.4);
    }

    .podium-card img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #d4af37;
        margin-bottom: 12px;
        box-shadow: 0 0 12px rgba(212,175,55,0.5);
        position: relative;
        z-index: 2;
        animation: glowPulse 3.5s ease-in-out infinite;
        transition: box-shadow 0.6s ease;
    }

    @keyframes glowPulse {
        0% { box-shadow: 0 0 8px rgba(212,175,55,0.3); }
        50% { box-shadow: 0 0 18px rgba(212,175,55,0.6); }
        100% { box-shadow: 0 0 8px rgba(212,175,55,0.3); }
    }

    .podium-card h3 {
        color: #3a2e1f;
        font-size: 1.2rem;
        margin-bottom: 5px;
        transition: color 0.6s ease;
    }

    .podium-card p {
        font-size: 0.95rem;
        color: #4a4032;
        transition: color 0.6s ease;
    }

    .podium-rank {
        font-size: 1.5rem;
        font-weight: bold;
        color: #d4af37;
        margin-bottom: 8px;
        transition: color 0.6s ease;
    }

    /* Podium positions */
    .podium-card.first { transform: translateY(-40px); order:2; }
    .podium-card.second { transform: translateY(-20px); order:1; }
    .podium-card.third { transform: translateY(0); order:3; }

    /* ---------- TABLE ---------- */
    .leaderboard-table {
        max-width: 850px;
        margin: 0 auto;
        border-radius: 15px;
        overflow: hidden;
        background: rgba(255,255,255,0.9);
        border: 2px solid #e6d8b5;
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        transition: background 0.6s ease, border 0.6s ease;
    }

    .leaderboard-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .leaderboard-table th,
    .leaderboard-table td {
        padding: 14px 18px;
        text-align: left;
    }

    .leaderboard-table th {
        background: #f2e7c8;
        color: #3a2e1f;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        transition: background 0.6s ease, color 0.6s ease;
    }

    .leaderboard-table tr:nth-child(even) { background: rgba(255,255,255,0.7); }
    .leaderboard-table tr:hover { background: rgba(212,175,55,0.15); }

    .rank-number { color: #d4af37; font-weight:bold; font-family: 'Cinzel', serif; }
    .show-more {
        display:block;
        margin:25px auto 60px;
        padding:10px 24px;
        background: linear-gradient(90deg,#d4af37,#c6a135);
        border:none;
        border-radius:30px;
        color:#fff;
        font-weight:600;
        cursor:pointer;
        transition: all 0.3s ease;
        font-family:'Cinzel', serif;
        box-shadow:0 4px 10px rgba(212,175,55,0.3);
    }
    .show-more:hover { transform: translateY(-2px); box-shadow:0 6px 14px rgba(212,175,55,0.5); }

    .hidden-row { display:none; }

    /* ---------- MEDIA ---------- */
    @media(max-width:768px) {
        .podium { gap: 20px; }
        .podium-card { width: 160px; }
    }

    /* ---------- DARK MODE ---------- */
    body.dark-mode {
        background: radial-gradient(circle at top left, rgba(10,30,50,0.45) 0%, rgba(6,20,35,0.55) 40%, rgba(3,10,20,0.65) 100%), url("{{ asset('images/dark-bg-pola.png') }}") center/cover no-repeat fixed;
        color: #e2ebf3;
    }

    body.dark-mode::before { opacity: 0.18; filter: brightness(0.8); }
    body.dark-mode::after { 
        background: linear-gradient(120deg, rgba(0,200,255,0.25), rgba(255,255,255,0.02), rgba(0,200,255,0.25));
        mix-blend-mode: screen;
        opacity:0.2;
    }

    body.dark-mode .leaderboard-header h1 { color:#00e1ff; text-shadow:0 0 10px rgba(0,230,255,0.5); }
    body.dark-mode .leaderboard-header p { color:#a4bfd2; }

    body.dark-mode .podium-card { background: rgba(10,25,45,0.3); border: 1px solid rgba(255,255,255,0.08); box-shadow:0 0 18px rgba(0,210,255,0.12); }
    body.dark-mode .podium-card:hover { box-shadow:0 0 25px rgba(0,210,255,0.4); border:1px solid rgba(0,210,255,0.35); }
    body.dark-mode .podium-card h3 { color:#eaf7ff; }
    body.dark-mode .podium-card p { color:#a4bfd2; }
    body.dark-mode .podium-rank { color:#00e1ff; }
    body.dark-mode .leaderboard-table { background: rgba(10,25,45,0.3); border: 1px solid rgba(255,255,255,0.08); }
    body.dark-mode .leaderboard-table th { background: rgba(0,0,0,0.25); color:#00e1ff; }
    body.dark-mode .leaderboard-table tr:nth-child(even) { background: rgba(255,255,255,0.08); }
    body.dark-mode .leaderboard-table tr:hover { background: rgba(0,210,255,0.15); }
    body.dark-mode .rank-number { color:#00e1ff; }
    body.dark-mode .show-more { background: linear-gradient(90deg,#00e1ff,#0080a3); box-shadow:0 4px 10px rgba(0,210,255,0.3); }

</style>
@endpush

@section('content')
<div class="leaderboard-header">
    <h1>Collector Leaderboard</h1>
    <p>Top collectors with the highest total art purchases</p>
</div>

@php
    $top3 = $leaderboard->take(3);
    $others = $leaderboard->slice(3)->values();
    $avatars = [
        asset('images/leaderboard/satu.png'),
        asset('images/leaderboard/dua.png'),
        asset('images/leaderboard/tiga.png'),
    ];
@endphp

<div class="podium">
    @foreach ($top3 as $index => $k)
        <div class="podium-card 
            @if ($index==0) first 
            @elseif($index==1) second 
            @else third @endif">
            <div class="podium-rank">#{{ $index+1 }}</div>
            <img src="{{ $avatars[$index] }}" alt="{{ $k->nama_kolektor }}">
            <h3>{{ $k->nama_kolektor }}</h3>
            <p>{{ ucfirst($k->jenis_kolektor) }}</p>
            <p><strong>Rp {{ number_format($k->total_pengeluaran,0,',','.') }}</strong></p>
            <p>{{ $k->jumlah_transaksi }} transaksi</p>
        </div>
    @endforeach
</div>

<div class="leaderboard-table">
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Nama Kolektor</th>
                <th>Jenis</th>
                <th>Jumlah Transaksi</th>
                <th>Total Pembelian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($others as $k)
                @php $rank = $loop->iteration + 3; @endphp
                <tr class="{{ $loop->iteration > 7 ? 'hidden-row' : '' }}">
                    <td class="rank-number">{{ $rank }}</td>
                    <td>{{ $k->nama_kolektor }}</td>
                    <td>{{ ucfirst($k->jenis_kolektor) }}</td>
                    <td>{{ $k->jumlah_transaksi }}</td>
                    <td>Rp {{ number_format($k->total_pengeluaran,0,',','.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if ($others->count() > 7)
    <button class="show-more" onclick="toggleRows()">▼ Lihat Semua Kolektor</button>
@endif

<script>
function toggleRows() {
    const hiddenRows = document.querySelectorAll('.hidden-row');
    const button = document.querySelector('.show-more');
    if (!hiddenRows.length) return;
    const isHidden = hiddenRows[0].style.display === 'none' || hiddenRows[0].style.display === '';
    hiddenRows.forEach(r => r.style.display = isHidden ? 'table-row' : 'none');
    button.textContent = isHidden ? '▲ Sembunyikan' : '▼ Lihat Semua Kolektor';
}
/* 🌟 Efek Tilt & Zoom Interaktif */
        document.querySelectorAll('.podium').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const rotateY = ((x / rect.width) - 0.5) * 20;
                const rotateX = ((y / rect.height) - 0.5) * -20;
                card.style.transform =
                    `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.03)`;
                card.querySelector('img').style.transform = 'scale(1.08)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) scale(1)';
                card.querySelector('img').style.transform = 'scale(1)';
            });
        });
</script>
@endsection
