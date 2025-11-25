@extends('layouts.kolektor')

@section('title', 'Arts - Atelier Gallery')

@push('page-styles')
    <style>
        /* === ANIMASI SHIMMER === */
        @keyframes shimmerMove {
            0% { transform: translateX(-25%) translateY(-10%) rotate(0deg); }
            100% { transform: translateX(25%) translateY(10%) rotate(3deg); }
        }

        @keyframes glossyMove {
            from { background-position: -200% center; }
            to { background-position: 200% center; }
        }

        /* === BODY & BACKGROUND === */
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

        /* === HEADER === */
        .arts-header {
            text-align: center;
            margin: 40px 20px 20px 20px;
            color: #d4af37;
            text-shadow: 0 0 6px rgba(212,175,55,0.4);
            transition: color 0.8s ease, text-shadow 0.8s ease;
        }

        .arts-header h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.8rem;
        }

        .arts-header p {
            font-size: 1rem;
            margin-top: 10px;
        }

        /* === GRID === */
        .arts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
            padding: 20px 20px 60px 20px;
        }

        /* === CARD === */
        .art-card {
            position: relative;
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            text-align: center;
            border: 1px solid rgba(212,175,55,0.15);
            box-shadow: 0 6px 15px rgba(212,175,55,0.2);
            transition: all 0.35s ease;
            transform-style: preserve-3d;
        }

        .art-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid #e0d8c5;
            transition: transform 0.4s ease;
        }

        .art-card h3 {
            margin: 12px 0;
            font-size: 1.2rem;
            color: #3a2e1f;
            transition: color 0.3s ease;
        }

        .art-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg,
                rgba(255,255,255,0.35),
                rgba(255,255,255,0.05),
                rgba(255,255,255,0.35));
            opacity: 0.25;
            background-size: 400% 400%;
            animation: glossyMove 10s linear infinite;
            pointer-events: none;
            border-radius: inherit;
            mix-blend-mode: soft-light;
        }

        .art-card:hover {
            transform: rotate3d(1,1,0,6deg) scale(1.03);
            box-shadow: 0 12px 25px rgba(212,175,55,0.35);
        }

        .art-card:hover img {
            transform: scale(1.05);
        }

        /* === DETAILS & PURCHASE BUTTON === */
        .art-details {
            display: none;
            padding: 12px;
            font-size: 0.95rem;
            color: #444;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .purchase-btn {
            display: none;
            margin: 15px auto 20px auto;
            padding: 10px 30px;
            background: linear-gradient(135deg, #d4af37, #b9952a);
            color: #fff;
            border-radius: 25px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* === EXPANDED CARD MODAL === */
        .art-card.expanded {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) scale(1);
            width: 80%;
            max-width: 700px;
            z-index: 1001;
            box-shadow: 0 20px 50px rgba(212,175,55,0.6), 0 0 40px rgba(255,215,0,0.3);
            border-radius: 20px;
            border: 3px solid #d4af37;
            background: linear-gradient(145deg, #fffbe8, #f9eebd);
            animation: expandCard 0.4s ease forwards;
        }

        .art-card.expanded img {
            height: 350px;
            object-fit: cover;
            border-bottom: 2px solid #d4af37;
            transform: scale(1.05);
        }

        .art-card.expanded .art-details,
        .art-card.expanded .purchase-btn {
            display: block;
            opacity: 1;
        }

        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .overlay.show {
            display: block;
            opacity: 1;
        }

        @keyframes expandCard {
            from { transform: translate(-50%, -50%) scale(0.8); opacity:0; }
            to { transform: translate(-50%, -50%) scale(1); opacity:1; }
        }

        /* === DARK MODE === */
        body.dark-mode {
            background: radial-gradient(circle at top left,
                    rgba(10,30,50,0.45) 0%,
                    rgba(6,20,35,0.55) 40%,
                    rgba(3,10,20,0.65) 100%),
                url("{{ asset('images/dark-bg-pola.png') }}") center/cover no-repeat fixed;
            color: #e2ebf3;
        }

        body.dark-mode::before {
            opacity: 0.18;
            filter: brightness(0.8);
        }

        body.dark-mode::after {
            background: linear-gradient(120deg,
                    rgba(0,200,255,0.25),
                    rgba(255,255,255,0.02),
                    rgba(0,200,255,0.25));
            mix-blend-mode: screen;
            opacity: 0.2;
        }

        body.dark-mode .arts-header {
            color: #00e1ff;
            text-shadow: 0 0 10px rgba(0,230,255,0.5);
        }

        body.dark-mode .arts-header p {
            color: #a4bfd2;
        }

        body.dark-mode .art-card {
            background: rgba(10,25,45,0.3);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 0 18px rgba(0,210,255,0.12);
        }

        body.dark-mode .art-card:hover {
            box-shadow: 0 0 25px rgba(0,210,255,0.4);
            border: 1px solid rgba(0,210,255,0.35);
        }

        body.dark-mode .art-card h3 {
            color: #eaf7ff;
        }

        body.dark-mode .art-details {
            color: #a4bfd2;
        }

        body.dark-mode .purchase-btn {
            background: linear-gradient(135deg, #00bcd4, #007c9e);
            box-shadow: 0 0 15px rgba(0,212,255,0.3);
        }

        body.dark-mode .purchase-btn:hover {
            background: linear-gradient(135deg, #00eaff, #009ac2);
            box-shadow: 0 0 25px rgba(0,212,255,0.5);
        }

        /* === RESPONSIVE === */
        @media(max-width:600px){
            .art-card.expanded { width: 90%; }
            .art-card img { height: 200px; }
            .art-card.expanded img { height: 300px; }
        }

.save-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 22px;
}
.save-btn i {
    transition: .2s;
}
.save-btn:hover i {
    transform: scale(1.2);
}


    </style>
@endpush

@section('content')
    <div class="arts-header">
        <h1>Discover Timeless Masterpieces</h1>
        <p>Explore unique artworks curated with passion and elegance. Click to see the magic unfold.</p>
    </div>

    <div class="arts-grid">
        @foreach ($karyaSeni as $index => $karya)
            <div class="art-card" data-index="{{ $index }}">
                <img src="{{ asset('images/asset/karyaSeni/' . ($karya->gambar ?? 'default.jpg')) }}"
                 alt="{{ $karya->judul_karya }}">
                <h3>{{ $karya->judul_karya }}</h3>
                    {{-- TOMBOL SIMPAN FAVORIT --}}
                    <form action="{{ route('kolektor.favorit.toggle', $karya->id_karya) }}" method="POST">
                        @csrf
                        <button type="submit" class="save-btn">
                            @if($karya->favorit->where('id_kolektor', session('kolektor')->id_kolektor)->count())
                                <i class="fa-solid fa-bookmark text-gold"></i> 
                            @else
                                <i class="fa-regular fa-bookmark"></i>
                            @endif
                        </button>
                    </form>

                <div class="art-details">
                    <p>{{ $karya->deskripsi }}</p>
                    <p><strong>Year:</strong> {{ $karya->tahun_pembuatan }}</p>
                    <p><strong>Price:</strong> ${{ $karya->harga }}</p>
                    <p><strong>Status:</strong> {{ $karya->status_karya }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="overlay" onclick="closeExpanded()"></div>

    <script>
        /* Toggle Expanded Card */
        function toggleCard(card){
            const expanded = document.querySelector('.art-card.expanded');
            const overlay = document.querySelector('.overlay');
            if(expanded && expanded!==card) expanded.classList.remove('expanded');
            if(card.classList.contains('expanded')){
                card.classList.remove('expanded');
                overlay.classList.remove('show');
            } else {
                card.classList.add('expanded');
                overlay.classList.add('show');
            }
        }

        document.querySelectorAll('.art-card').forEach(card=>{
            card.addEventListener('click', e=>{
                e.stopPropagation();
                toggleCard(card);
            });
        });

        function closeExpanded(){
            document.querySelectorAll('.art-card.expanded').forEach(c=>c.classList.remove('expanded'));
            document.querySelector('.overlay').classList.remove('show');
        }

        /* 🌟 Tilt & Zoom Interaktif */
        document.querySelectorAll('.art-card').forEach(card=>{
            card.addEventListener('mousemove', e=>{
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const rotateY = ((x/rect.width)-0.5)*20;
                const rotateX = ((y/rect.height)-0.5)*-20;
                card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.03)`;
                card.querySelector('img').style.transform = 'scale(1.08)';
            });
            card.addEventListener('mouseleave', ()=>{
                card.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) scale(1)';
                card.querySelector('img').style.transform = 'scale(1)';
            });
        });
        // cegah klik tombol membuka modal
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function(e){
                e.stopPropagation(); // ❗ mencegah card expand
            });
        });

        document.querySelectorAll('.favorite-form').forEach(form => {
            form.addEventListener('click', function(e){
                e.stopPropagation(); // ❗ aman
            });
        });
    </script>
@endsection

