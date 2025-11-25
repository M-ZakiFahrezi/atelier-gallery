@extends('layouts.kolektor')

@section('content')
    <!-- Hero Section -->
    <div class="hero" id="hero">
        <div class="hero-content">
            <img src="{{ asset('images/logo.png') }}" alt="Atelier Logo" class="logo">
            <h1>Experience Timeless Art.</h1>
            <p>Discover a collection of great artists' touches with extraordinary elegance.</p>
            <p>Give enough space for art to grow in your life.</p>
            <a href="#featured-arts" class="btn-explore">Explore the Gallery</a>
        </div>
    </div>

    @php
        $karyaPath = public_path('data/karya.json');
        $senimanPath = public_path('data/seniman.json');
        $galleryPath = public_path('data/gallery.json');

        $featuredArts = file_exists($karyaPath) ? json_decode(file_get_contents($karyaPath), true) : [];
        $highlightArtists = file_exists($senimanPath) ? json_decode(file_get_contents($senimanPath), true) : [];
        $galleryPreview = file_exists($galleryPath) ? json_decode(file_get_contents($galleryPath), true) : [];

        shuffle($featuredArts);
        shuffle($highlightArtists);
        shuffle($galleryPreview);

        $featuredArts = array_slice($featuredArts, 0, 3);
        $highlightArtists = array_slice($highlightArtists, 0, 3);
        $galleryPreview = array_slice($galleryPreview, 0, 4);
    @endphp

    <!-- Featured Artworks Section -->
    <section class="featured-arts" id="featured-arts">
        <h2>Featured Artworks</h2>
        <div class="arts-grid">
            @foreach ($featuredArts as $art)
                <div class="art-card">
                    <img src="{{ asset($art['file']) }}" alt="{{ $art['judul_karya'] }}">
                    <h3>{{ $art['judul_karya'] }}</h3>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Highlight Artists Section -->
    <section class="highlight-artists" id="artists">
        <h2>Meet the Artists</h2>
        <div class="artists-grid">
            @foreach ($highlightArtists as $artist)
                <div class="artist-card">
                    <img src="{{ asset('images/asset/seniman/' . $artist['file']) }}" alt="{{ $artist['nama_seniman'] }}">
                    <h3>{{ $artist['nama_seniman'] }}</h3>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Gallery Preview Section -->
    <section class="gallery-preview" id="gallery">
        <h2>Gallery Preview</h2>
        <div class="gallery-grid">
            @foreach ($galleryPreview as $item)
                <div class="gallery-card">
                    <img src="{{ asset('images/asset/gallery/' . $item['file']) }}" alt="Gallery">
                </div>
            @endforeach
        </div>
    </section>

    <!-- Page-specific Style -->
    <style>
        body {
            background: linear-gradient(145deg, #f8f5ef 0%, #e8dcc3 100%);
            color: #222;
            font-family: 'cinzel', sans-serif;
            overflow-x: hidden;
            transition: background 0.8s ease, color 0.8s ease;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: url("{{ asset('images/bg-pola.png') }}") center/cover repeat;
            opacity: 0.25;
            pointer-events: none;
            z-index: -2;
            filter: brightness(1.05) saturate(0.9);
            transition: opacity 0.8s ease;
        }

        body::after {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(120deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.25) 40%, rgba(255,255,255,0) 80%);
            opacity: 0.15;
            pointer-events: none;
            z-index: -1;
            animation: shimmerMove 14s ease-in-out infinite alternate;
            mix-blend-mode: soft-light;
            transition: opacity 0.8s ease;
        }
        /* === Hero Section === */
        .hero {
            text-align: center;
            padding: 150px 20px 100px;
            background: transparent;
        }

        .hero .logo {
            width: 120px;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.8rem;
            color: #3a2e1f;
        }

        .hero p {
            color: #4a4032;
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .btn-explore {
            display: inline-block;
            padding: 12px 28px;
            background: linear-gradient(135deg, #d4af37, #b9952a);
            color: #fff;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-explore:hover {
            background: linear-gradient(135deg, #e0c14e, #c9a531);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.5);
            transform: translateY(-3px);
        }

        /* === Sections === */
        section {
            padding: 80px 20px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        section h2 {
            font-family: 'Cinzel', serif;
            font-size: 2.2rem;
            margin-bottom: 40px;
            color: #d4af37;
            text-shadow: 0 0 6px rgba(255, 215, 0, 0.2);
        }

        /* === Grids & Cards === */
        .arts-grid,
        .artists-grid {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .art-card,
        .artist-card {
            position: relative;
            width: 250px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(212, 175, 55, 0.15);
            backdrop-filter: blur(10px) saturate(1.3);
            box-shadow: 0 6px 15px rgba(212, 175, 55, 0.2);
            transition: all 0.35s ease;
            transform-style: preserve-3d;
        }

        .art-card img,
        .artist-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .artist-card img {
            border-radius: 50%;
            width: 200px;
            height: 200px;
            margin: 0 auto 10px;
        }

        .art-card h3,
        .artist-card h3 {
            margin: 15px 0;
            font-size: 1.2rem;
            color: #333;
        }

        .art-card:hover,
        .artist-card:hover {
            transform: rotate3d(1, 1, 0, 6deg) scale(1.03);
            box-shadow: 0 12px 25px rgba(212, 175, 55, 0.35);
        }

        .art-card:hover img,
        .artist-card:hover img {
            transform: scale(1.05);
        }

        /* Gallery */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .gallery-card {
            overflow: hidden;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .gallery-card img {
            width: 100%;
            display: block;
            transition: transform 0.4s ease;
        }

        .gallery-card:hover {
            transform: scale(1.05) rotate3d(1, 1, 0, 4deg);
            box-shadow: 0 8px 22px rgba(212, 175, 55, 0.3);
        }

        /* === Dark Mode === */
        body.dark-mode .hero h1 {
            color: #00e1ff;
            text-shadow: 0 0 10px rgba(0, 230, 255, 0.5);
        }

        body.dark-mode .hero p {
            color: #a4bfd2;
        }

        body.dark-mode section h2 {
            color: #00e1ff;
            text-shadow: 0 0 10px rgba(0, 230, 255, 0.5);
        }

        body.dark-mode .art-card,
        body.dark-mode .artist-card {
            background: rgba(10, 25, 45, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(18px) saturate(1.4);
            box-shadow: 0 0 18px rgba(0, 210, 255, 0.12);
        }

        body.dark-mode .art-card h3,
        body.dark-mode .artist-card h3 {
            color: #eaf7ff;
        }

        body.dark-mode .btn-explore {
            background: linear-gradient(135deg, #00bcd4, #007c9e);
            box-shadow: 0 4px 14px rgba(0, 212, 255, 0.3);
        }

        body.dark-mode .btn-explore:hover {
            background: linear-gradient(135deg, #00eaff, #009ac2);
            box-shadow: 0 6px 20px rgba(0, 212, 255, 0.5);
        }

        /* === RESPONSIVE === */
        @media (max-width: 900px) {
            .hero {
                padding: 120px 15px 80px;
            }

            .hero h1 {
                font-size: 2.4rem;
            }

            .hero p {
                font-size: 0.95rem;
            }

            .btn-explore {
                padding: 10px 22px;
            }

            .art-card,
            .artist-card {
                width: 45%;
            }

            .artist-card img {
                width: 150px;
                height: 150px;
            }
        }

        @media (max-width: 600px) {
            .hero {
                padding: 100px 15px 60px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 0.9rem;
            }

            .hero .logo {
                width: 80px;
            }

            .btn-explore {
                padding: 10px 18px;
            }

            .art-card,
            .artist-card {
                width: 90%;
                margin: 0 auto;
            }

            .artist-card img {
                width: 120px;
                height: 120px;
            }
        }
    </style>

    <!-- Tilt & Zoom Interaktif -->
    <script>
        document.querySelectorAll('.art-card, .artist-card').forEach(card => {
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
