<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Atelier Gallery')</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'cinzel', sans-serif;
            color: #222;
            overflow-x: hidden;
            position: relative;
            background: transparent;
            transition: background 0.6s ease, color 0.6s ease;
        }

        /* Tema Gelap */
        body.dark-mode {
            background: url("{{ asset('images/dark-bg-pola.png') }}") center/cover no-repeat fixed;
            color: #f5e9c3;
        }

        /* NAVBAR */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 15px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 215, 0, 0.25);
            transition: background 0.4s ease;
        }

        body.dark-mode nav {
            background: rgba(0, 0, 0, 0.4);
            border-bottom-color: rgba(255, 215, 0, 0.4);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .brand span {
            font-family: 'Cinzel', serif;
            font-size: 1.4rem;
            color: #d4af37;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 28px;
            align-items: center;
        }

        nav ul li a {
            color: #2f2a1e;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav ul li a:hover,
        nav ul li a.active {
            color: #d4af37;
        }

        body.dark-mode nav ul li a {
            color: #f5e9c3;
        }

        body.dark-mode nav ul li a:hover {
            color: #ffd700;
        }

        /* === Dropdown User === */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 35px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            list-style: none;
            padding: 10px 0;
            border-radius: 10px;
            min-width: 150px;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .dropdown-menu li {
            padding: 8px 16px;
        }

        .dropdown-menu li a,
        .dropdown-menu li button {
            color: #2f2a1e;
            text-decoration: none;
            display: block;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
        }

        .dropdown-menu li a:hover,
        .dropdown-menu li button:hover {
            color: #d4af37;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        body.dark-mode .dropdown-menu {
            background: rgba(0, 0, 0, 0.9);
            border-color: rgba(255, 215, 0, 0.4);
        }

        body.dark-mode .dropdown-menu li a,
        body.dark-mode .dropdown-menu li button {
            color: #f5e9c3;
        }

        body.dark-mode .dropdown-menu li a:hover,
        body.dark-mode .dropdown-menu li button:hover {
            color: #ffd700;
        }

        /* Tombol toggle tema */
        .theme-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: #d4af37;
            transition: transform 0.3s ease, color 0.3s;
        }

        .theme-toggle:hover {
            transform: scale(1.2);
        }

        body.dark-mode .theme-toggle {
            color: #ffd700;
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
            z-index: 1100;
        }

        .hamburger span {
            width: 26px;
            height: 3px;
            background-color: #2f2a1e;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        body.dark-mode .hamburger span {
            background-color: #f5e9c3;
        }

        .hamburger.active span:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        /* Menu Responsif */
        @media (max-width: 900px) {
            nav {
                padding: 15px 30px;
            }

            nav ul {
                position: fixed;
                top: 70px;
                right: -100%;
                width: 220px;
                height: calc(100vh - 70px);
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                flex-direction: column;
                justify-content: flex-start;
                align-items: start;
                gap: 20px;
                padding: 30px 25px;
                transition: right 0.4s ease;
            }

            body.dark-mode nav ul {
                background: rgba(0, 0, 0, 0.85);
            }

            nav ul.show {     
                right: 0;
            }

            .hamburger {
                display: flex;
            }
        }

        main {
            padding-top: 90px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* FOOTER */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 20px 10px;
            z-index: 999;
            /* Transisi untuk perpindahan antar mode yang mulus */
            transition: background-color 0.4s ease, border-top-color 0.4s ease, color 0.4s ease;
        }

        footer p {
            font-size: 0.85rem;
            transition: color 0.4s ease;
        }

        /* --- LIGHT MODE FOOTER (Default) --- */
        /* Menggunakan semi-transparan putih/terang, serasi dengan navbar & sidebar */
        footer {
            /* Latar belakang yang sangat terang dan semi-transparan */
            background: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(12px); /* Menggunakan blur yang sama dengan navbar/sidebar */
            
            border-top: 1px solid rgba(212, 175, 55, 0.25); /* Garis emas muda */
            color: #2f2a1e; /* Teks gelap */
        }

        footer p {
            color: #8a7a58; /* Warna teks kalem di mode terang */
        }


        /* --- DARK MODE FOOTER --- */
        /* Dipicu ketika body memiliki class .dark-mode */
        body.dark-mode footer {
            /* Latar belakang gelap semi-transparan, serasi dengan navbar/sidebar dark mode */
            background: rgba(0, 0, 0, 0.45); 
            
            border-top: 1px solid rgba(255, 215, 0, 0.4); /* Garis emas yang lebih menonjol */
            color: #f5e9c3; /* Teks terang */
        }

        body.dark-mode footer p {
            color: #cfc08a; /* Warna teks kalem di mode gelap */
        }

        .fade-up {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 1.3s, transform 1.3s;
        }

        .fade-up.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    @stack('page-styles')
</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Atelier Logo">
            <span>ATELIER</span>
        </div>

        <!-- Tombol hamburger -->
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <ul id="navMenu">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('arts') }}" class="{{ request()->routeIs('arts') ? 'active' : '' }}">Arts</a></li>
            <li><a href="{{ route('artists') }}" class="{{ request()->routeIs('artists') ? 'active' : '' }}">Artists</a>
            </li>
            <li><a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
            </li>
            <li><a href="{{ route('leaderboard') }}"
                    class="{{ request()->routeIs('leaderboard') ? 'active' : '' }}">Leaderboard</a></li>
            <li><a href="{{ route('contact') }}"
                    class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
            @if (session('kolektor'))
                <li class="dropdown">
                    <button class="theme-toggle">
                        <img src="{{ asset('images/user.png') }}"
                            style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('kolektor.profil') }}">Profil Saya</a></li>
                        <li><a href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </li>
            @elseif (session('admin'))
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
            @endif
            <li>
                <button class="theme-toggle" id="themeToggle">
                    <i class="fa-solid fa-moon"></i>
                </button>
            </li>


        </ul>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <p>© {{ date('Y') }} Atelier Gallery. Crafted with passion and elegance by Jake.company</p>
    </footer>

    <script>
        // Animasi scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('show');
            });
        }, {
            threshold: 0.15
        });
        document.querySelectorAll('section, .hero').forEach(el => {
            el.classList.add('fade-up');
            observer.observe(el);
        });

        // Toggle Tema
        const body = document.body;
        const toggleBtn = document.getElementById('themeToggle');
        const icon = toggleBtn.querySelector('i');
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            icon.classList.replace('fa-moon', 'fa-sun');
        }
        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            icon.classList.toggle('fa-sun', isDark);
            icon.classList.toggle('fa-moon', !isDark);
        });

        // Navbar Responsif
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('navMenu');
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('show');
        });
        document.querySelectorAll('#navMenu a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('show');
            });
        });
    </script>
</body>

</html>
