@extends('layouts.public')

@section('title', 'Gallery - Atelier Gallery')

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
            background: linear-gradient(120deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.25) 40%, rgba(255, 255, 255, 0) 80%);
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
        .gallery-header {
            text-align: center;
            margin: 30px 20px 10px 20px;
            font-size: 1.3rem;
            font-weight: 500;
            color: #d4af37;
            text-shadow: 0 0 6px rgba(212, 175, 55, 0.4);
        }

        .gallery-header h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.8rem;
            transition: color 0.6s ease, text-shadow 0.6s ease;
        }

        .gallery-header p {
            font-size: 1rem;
            color: #4a4032;
            margin-top: 10px;
            transition: color 0.6s ease;
        }

        /* ---------- GRID ---------- */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
            padding: 20px 20px 60px 20px;
        }

        /* ---------- CARD ---------- */
        .gallery-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            transition: transform 0.4s ease, box-shadow 0.4s ease, border 0.4s ease, background 0.6s ease;
            cursor: pointer;
            text-align: center;
            border: 2px solid transparent;
            position: relative;
            transform-style: preserve-3d;
        }

        .gallery-card:hover {
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.35);
            transform: translateY(-3px);
            border: 2px solid #d4af37;
        }

        .gallery-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid #e0d8c5;
            transition: transform 0.4s ease;
        }

        .gallery-card:hover img {
            transform: scale(1.05);
        }

        .gallery-card h3 {
            margin: 12px 0;
            font-size: 1.1rem;
            color: #3a2e1f;
            transition: color 0.6s ease;
        }

        .gallery-details {
            display: none;
            padding: 12px;
            font-size: 0.95rem;
            color: #444;
            opacity: 0;
            transition: opacity 0.5s ease, color 0.6s ease;
        }

        /* ---------- EXPANDED CARD ---------- */
        .gallery-card.expanded {
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

        .gallery-card.expanded img {
            height: 350px;
            border-bottom: 2px solid #d4af37;
            transform: scale(1.05);
        }

        .gallery-card.expanded .gallery-details {
            display: block;
            opacity: 1;
        }

        /* ---------- OVERLAY ---------- */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1000;
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease;
            backdrop-filter: blur(4px);
        }

        .overlay.show {
            display: flex;
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

        @media(max-width:600px) {
            .gallery-card.expanded {
                width: 90%;
            }

            .gallery-card img {
                height: 200px;
            }

            .gallery-card.expanded img {
                height: 300px;
            }
        }

        /* ---------- DARK MODE ---------- */
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

        body.dark-mode .gallery-header h1 {
            color: #00e1ff;
            text-shadow: 0 0 10px rgba(0, 230, 255, 0.5);
        }

        body.dark-mode .gallery-header p {
            color: #a4bfd2;
        }

        body.dark-mode .gallery-card {
            background: rgba(10, 25, 45, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 18px rgba(0, 210, 255, 0.12);
        }

        body.dark-mode .gallery-card:hover {
            box-shadow: 0 0 25px rgba(0, 210, 255, 0.4);
            border: 1px solid rgba(0, 210, 255, 0.35);
        }

        body.dark-mode .gallery-card h3 {
            color: #eaf7ff;
        }

        body.dark-mode .gallery-details {
            color: #d0e9ff;
        }

        body.dark-mode .gallery-card.expanded {
            background: linear-gradient(145deg, #0d1b2a, #112d44);
            border: 2px solid rgba(0, 210, 255, 0.5);
            box-shadow: 0 0 40px rgba(0, 210, 255, 0.3);
        }

        body.dark-mode .gallery-card.expanded::after {
            background: linear-gradient(130deg,
                    rgba(0, 200, 255, 0.3),
                    rgba(255, 255, 255, 0.05),
                    rgba(0, 200, 255, 0.3));
            animation: none;
        }
    </style>
@endpush

@section('content')
    <div class="gallery-header">
        <h1 style="font-family: 'Cinzel', serif;">Explore Our Galleries</h1>
        <p>Discover curated galleries with elegance and style. Click to see more details.</p>
    </div>

    <div class="gallery-grid">
        @foreach ($galeri as $index => $g)
            <div class="gallery-card" data-index="{{ $index }}">
                <img src="{{ asset('images/asset/gallery/' . ($g->gambar ?? 'default.jpg')) }}" alt="{{ $g->nama_galeri }}">
                <h3>{{ $g->nama_galeri }}</h3>
                <div class="gallery-details">
                    <p><strong>Alamat:</strong> {{ $g->alamat }}</p>
                    <p><strong>Kontak:</strong> {{ $g->kontak }}</p>
                    <p><strong>Website:</strong> <a href="{{ $g->website }}" target="_blank">{{ $g->website }}</a></p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="overlay" onclick="closeExpanded()"></div>

    <script>
        function toggleCard(card) {
            const expanded = document.querySelector('.gallery-card.expanded');
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

        document.querySelectorAll('.gallery-card').forEach(card => {
            card.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleCard(card);
            });
        });

        function closeExpanded() {
            document.querySelectorAll('.gallery-card.expanded').forEach(c => c.classList.remove('expanded'));
            const overlay = document.querySelector('.overlay');
            overlay.classList.remove('show');
        }

        /* 🌟 Efek Tilt & Zoom Interaktif */
        document.querySelectorAll('.gallery-card').forEach(card => {
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
