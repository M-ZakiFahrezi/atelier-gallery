lanjut halaman artists, apakah ini sudah responsive? dan atau ada yang perlu diperbaiki/ditambahkan

@extends('layouts.kolektor')

@section('title', 'Artists - Atelier Gallery')

@push('page-styles')
    <style>
        /* ---------- LIGHT MODE ---------- */
        body {
            background: linear-gradient(145deg, #f8f5ef 0%, #e8dcc3 100%);
            color: #3a2e1f;
            transition: background 0.6s ease, color 0.6s ease;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: url("{{ asset('images/bg-pola.png') }}") center/cover repeat;
            opacity: 0.25;
            pointer-events: none;
            z-index: -2;
            filter: brightness(1.05) saturate(0.9);
            transition: opacity 0.6s ease;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(120deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.25) 40%,
                    rgba(255, 255, 255, 0) 80%);
            opacity: 0.15;
            pointer-events: none;
            z-index: -1;
            animation: shimmerMove 14s ease-in-out infinite alternate;
            mix-blend-mode: soft-light;
        }

        @keyframes shimmerMove {
            0% {
                transform: translateX(-25%) translateY(-10%) rotate(0deg);
            }

            100% {
                transform: translateX(25%) translateY(10%) rotate(3deg);
            }
        }

        /* ---------- HEADER ---------- */
        .artists-header {
            text-align: center;
            margin: 40px 20px 20px 20px;
            color: #d4af37;
            text-shadow: 0 0 6px rgba(212, 175, 55, 0.4);
        }

        .artists-header h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.8rem;
            transition: color 0.6s ease, text-shadow 0.6s ease;
        }

        .artists-header p {
            font-size: 1rem;
            color: #4a4032;
            margin-top: 10px;
            transition: color 0.6s ease;
        }

        /* ---------- GRID ---------- */
        .artists-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 35px;
            padding: 30px 25px 80px 25px;
        }

        /* ---------- CARD ---------- */
        .artist-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            transition: transform 0.4s ease, box-shadow 0.4s ease, border 0.4s ease, background 0.6s ease;
            cursor: pointer;
            text-align: center;
            border: 2px solid transparent;
            position: relative;
            transform-style: preserve-3d;
        }

        .artist-card:hover {
            transform: translateY(-5px) rotateX(6deg) rotateY(-6deg) scale(1.03);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.35);
            border: 2px solid #d4af37;
        }

        .artist-card img {
            width: 100%;
            height: 230px;
            object-fit: cover;
            border-bottom: 1px solid #e0d8c5;
            transition: transform 0.6s ease;
        }

        .artist-card:hover img {
            transform: scale(1.08);
        }

        .artist-card h3 {
            margin: 12px 0 5px 0;
            font-size: 1.1rem;
            color: #3a2e1f;
            font-weight: 600;
            transition: color 0.6s ease;
        }

        .artist-card p {
            font-size: 0.9rem;
            color: #4a4032;
            margin-bottom: 12px;
            transition: color 0.6s ease;
        }

        /* ---------- EXPANDED CARD ---------- */
        .artist-card.expanded {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(1);
            width: 80%;
            max-width: 700px;
            z-index: 1001;
            border-radius: 20px;
            background: linear-gradient(145deg, #fffbe8, #f9eebd);
            border: 3px solid #d4af37;
            box-shadow: 0 20px 50px rgba(212, 175, 55, 0.6), 0 0 40px rgba(255, 215, 0, 0.3);
            animation: expandCard 0.4s ease forwards;
        }

        .artist-card.expanded img {
            height: 350px;
            border-bottom: 2px solid #d4af37;
            animation: fadeIn 0.6s ease-in-out;
        }

        .artist-card.expanded::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 20px;
            background: linear-gradient(130deg,
                    rgba(212, 175, 55, 0.4),
                    rgba(255, 255, 255, 0.1),
                    rgba(212, 175, 55, 0.3));
            animation: goldShine 5s linear infinite;
            z-index: -1;
        }

        @keyframes goldShine {
            0% {
                transform: translateX(-100%) rotate(0deg);
                opacity: 0.3;
            }

            50% {
                transform: translateX(100%) rotate(2deg);
                opacity: 0.6;
            }

            100% {
                transform: translateX(-100%) rotate(0deg);
                opacity: 0.3;
            }
        }

        .artist-details {
            display: none;
            padding: 18px 25px;
            font-size: 0.96rem;
            color: #3f3b2f;
            line-height: 1.5;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .artist-card.expanded .artist-details {
            display: block;
            opacity: 1;
        }

        /* ---------- OVERLAY ---------- */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease;
            backdrop-filter: blur(4px);
        }

        .overlay.show {
            display: block;
            opacity: 1;
        }

        @keyframes expandCard {
            from {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0;
            }

            to {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.98);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ---------- DARK MODE (selaras dengan arts) ---------- */
        body.dark-mode {
            background: radial-gradient(circle at top left,
                    rgba(10, 30, 50, 0.45) 0%,
                    rgba(6, 20, 35, 0.55) 40%,
                    rgba(3, 10, 20, 0.65) 100%),
                url("{{ asset('images/dark-bg-pola.png') }}") center/cover no-repeat fixed;
            color: #e2ebf3;
        }

        body.dark-mode::before {
            opacity: 0.18;
            filter: brightness(0.8);
        }

        body.dark-mode::after {
            background: linear-gradient(120deg,
                    rgba(0, 200, 255, 0.25),
                    rgba(255, 255, 255, 0.02),
                    rgba(0, 200, 255, 0.25));
            mix-blend-mode: screen;
            opacity: 0.2;
        }

        body.dark-mode .artists-header h1 {
            color: #00e1ff;
            text-shadow: 0 0 10px rgba(0, 230, 255, 0.5);
        }

        body.dark-mode .artists-header p {
            color: #a4bfd2;
        }

        body.dark-mode .artist-card {
            background: rgba(10, 25, 45, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 18px rgba(0, 210, 255, 0.12);
        }

        body.dark-mode .artist-card:hover {
            box-shadow: 0 0 25px rgba(0, 210, 255, 0.4);
            border: 1px solid rgba(0, 210, 255, 0.35);
        }

        body.dark-mode .artist-card h3 {
            color: #eaf7ff;
        }

        body.dark-mode .artist-card p {
            color: #a4bfd2;
        }

        body.dark-mode .artist-card.expanded {
            background: linear-gradient(145deg, #0d1b2a, #112d44);
            border: 2px solid rgba(0, 210, 255, 0.5);
            box-shadow: 0 0 40px rgba(0, 210, 255, 0.3);
        }

        body.dark-mode .artist-card.expanded::after {
            background: linear-gradient(130deg,
                    rgba(0, 200, 255, 0.3),
                    rgba(255, 255, 255, 0.05),
                    rgba(0, 200, 255, 0.3));
            animation: none;
        }

        body.dark-mode .artist-details {
            color: #d0e9ff;
        }

        @media(max-width:600px) {
            .artist-card.expanded {
                width: 90%;
            }

            .artist-card.expanded img {
                height: 300px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="artists-header">
        <h1>Meet the Visionary Artists</h1>
        <p>Discover the brilliant minds and creative souls behind each timeless masterpiece.</p>
    </div>

    <div class="artists-grid">
        @foreach ($seniman as $index => $s)
            <div class="artist-card" data-index="{{ $index }}">
                <img src="{{ asset('images/asset/seniman/' . ($s->gambar ?? 'default.jpg')) }}"
                    alt="{{ $s->nama_seniman }}">
                <h3>{{ $s->nama_seniman }}</h3>
                <p><em>{{ $s->asal }}</em></p>
                <div class="artist-details">
                    <p><strong>Born:</strong> {{ \Carbon\Carbon::parse($s->tanggal_lahir)->format('F j, Y') }}</p>
                    <p>{{ $s->bio }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="overlay" onclick="closeExpanded()"></div>

    <script>
        // Expand / Collapse
        function toggleCard(card) {
            const expanded = document.querySelector('.artist-card.expanded');
            const overlay = document.querySelector('.overlay');

            if (expanded && expanded !== card) expanded.classList.remove('expanded');

            if (card.classList.contains('expanded')) {
                card.classList.remove('expanded');
                overlay.classList.remove('show');
            } else {
                card.classList.add('expanded');
                overlay.classList.add('show');
            }
        }

        document.querySelectorAll('.artist-card').forEach(card => {
            card.addEventListener('click', e => {
                e.stopPropagation();
                toggleCard(card);
            });
        });

        function closeExpanded() {
            document.querySelectorAll('.artist-card.expanded').forEach(c => c.classList.remove('expanded'));
            document.querySelector('.overlay').classList.remove('show');
        }

        /* 🌟 Efek Tilt & Zoom Interaktif */
        document.querySelectorAll('.artist-card').forEach(card => {
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
