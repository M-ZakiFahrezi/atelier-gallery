<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Atelier Gallery - Admin')</title>
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
            font-family: 'Poppins', sans-serif;
            color: #222;
            overflow-x: hidden;
            position: relative;
            background: linear-gradient(145deg, #f8f5ef 0%, #e8dcc3 100%);
            transition: background 0.6s ease, color 0.6s ease;
        }

        body.dark-mode {
            background: url("{{ asset('images/dark-bg-pola.png') }}") center/cover no-repeat fixed;
            color: #f5e9c3;
        }

        /* NAVBAR ADMIN */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.25);
            z-index: 1000;
        }

        body.dark-mode nav {
            background: rgba(0, 0, 0, 0.4);
            border-bottom-color: rgba(255, 215, 0, 0.4);
        }

        .brand {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            color: #d4af37;
            font-weight: 700;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
            align-items: center;
        }

        nav ul li a {
            text-decoration: none;
            font-weight: 500;
            color: #2f2a1e;
            transition: color 0.3s;
        }

        nav ul li a.active,
        nav ul li a:hover {
            color: #d4af37;
        }

        body.dark-mode nav ul li a {
            color: #f5e9c3;
        }

        body.dark-mode nav ul li a:hover {
            color: #ffd700;
        }

        /* Dropdown */
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
            border-radius: 10px;
            min-width: 150px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            list-style: none;
            padding: 10px 0;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu li {
            padding: 8px 16px;
        }

        .dropdown-menu li a,
        .dropdown-menu li button {
            color: #2f2a1e;
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-menu li a:hover,
        .dropdown-menu li button:hover {
            color: #d4af37;
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

        /* Toggle Tema */
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

        /* Main Content */
        main {
            padding: 100px 40px 40px 40px;
            max-width: 1200px;
            margin: 0 auto;
            min-height: calc(100vh - 160px);
        }

        footer {
            width: 100%;
            text-align: center;
            padding: 25px 10px;
            color: #cfc08a;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            background: linear-gradient(to top, #1a1a1a 0%, #2b2b2b 100%);
        }

        /* Fade Up Animation */
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('page-styles')
</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="brand">ATELIER ADMIN</div>
        <ul>
            <li><a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('admin.karya.index') }}"
                    class="{{ request()->routeIs('admin.karya.*') ? 'active' : '' }}">Karya</a></li>
            <li><a href="{{ route('admin.seniman.index') }}"
                    class="{{ request()->routeIs('admin.seniman.*') ? 'active' : '' }}">Seniman</a></li>
            <li><a href="{{ route('admin.galeri.index') }}"
                    class="{{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}">Galeri</a></li>
            <li><a href="{{ route('admin.event.index') }}"
                class="{{ request()->routeIs('admin.event.*') ? 'active' : '' }}">Event</a></li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a>
            </li>



            <li class="dropdown">
                <button class="theme-toggle">
                    <img src="{{ asset('images/user.png') }}"
                        style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('admin.profil') }}">Profil Saya</a></li>
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </li>
            <li><button class="theme-toggle" id="themeToggle"><i class="fa-solid fa-moon"></i></button></li>
        </ul>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <p>© {{ date('Y') }} Atelier Gallery Admin</p>
    </footer>

    <script>
        // Fade-up scroll
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

        // Toggle Theme
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
