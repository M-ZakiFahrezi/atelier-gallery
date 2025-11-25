@extends('layouts.kolektor')

@section('dashboard-style')
<style>

/* WRAPPER GRID */
.dashboard-wrapper {
    min-height: calc(100vh - 90px);
    width: 100%;
    display: block;
}


/* Default ukuran sidebar */
:root {
    --sidebar-width: 220px;
    --sidebar-collapsed: 70px;
}

/* SIDEBAR */
.dashboard-sidebar {
    position: fixed;
    top: 70px;
    bottom: 0;
    left: 0;

    width: var(--sidebar-width);
    background: rgba(245, 239, 220, 0.22);
    border-right: 1px solid rgba(212,175,55,0.22);
    backdrop-filter: blur(12px);

    padding: 25px 10px;
    padding-bottom: 90px;
    overflow-y: auto;

    transition: width 0.35s ease, padding 0.35s ease;
}

/* DARK MODE */
body.dark-mode .dashboard-sidebar {
    background: rgba(30, 30, 30, 0.45);
    border-right: 1px solid rgba(255,215,0,0.3);
}

/* COLLAPSED STATE */
.dashboard-sidebar.collapsed {
    width: var(--sidebar-collapsed);
    padding-left: 10px;
    padding-right: 10px;
}

/* TITLE */
.dashboard-sidebar h3 {
    text-align: center;
    color: #d4af37;
    margin-bottom: 20px;
    font-size: 1.2rem;
    transition: opacity 0.3s ease;
}

/* Hide title when collapsed */
.dashboard-sidebar.collapsed h3 {
    opacity: 0;
    pointer-events: none;
}

/* MENU LIST */
.dashboard-menu {
    list-style: none;
    margin: 0;
    padding: 0;
}

.dashboard-menu li {
    margin-bottom: 12px;
}

/* MENU LINK */
.dashboard-menu a {
    display: flex;
    align-items: center;
    gap: 12px;

    padding: 10px 12px;
    text-decoration: none;
    color: #e7dfc8;
    font-family: 'Poppins', sans-serif;
    border-radius: 6px;
    transition: 0.25s ease;
}

/* ICON SIZE */
.dashboard-menu a i {
    font-size: 1.2rem;
    width: 24px;
    text-align: center;
}

/* TEXT INSIDE MENU */
.menu-text {
    transition: opacity 0.25s ease;
}

/* Hide text when collapsed */
.dashboard-sidebar.collapsed .menu-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

/* HOVER + ACTIVE */
.dashboard-menu a:hover,
.dashboard-menu a.active {
    background: rgba(212,175,55,0.32);
    color: #d4af37;
}

/* CONTENT */
.dashboard-content {
    margin-left: var(--sidebar-width);
    margin-top: 90px;
    margin-bottom: 60px;
    padding: 40px;
    transition: margin-left .35s ease;
}

/* Adjust when collapsed */
.dashboard-content.expanded {
    margin-left: var(--sidebar-collapsed);
}

/* TOGGLE BUTTON */
#sidebarToggle {
    position: absolute;
    top: 10px;
    right: -15px;

    background: #efdfa8e4;
    color: #766969ff;
    padding: 6px 8px;
    border-radius: 6px;

    cursor: pointer;
    transition: 0.3s;
    font-size: 1rem;
}

.dashboard-sidebar.collapsed #sidebarToggle {
    right: -20px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .dashboard-sidebar {
        display: none;
    }

    .dashboard-content {
        margin-left: 0 !important;
        width: 100%;
        padding: 20px;
    }
}

</style>
@endsection

@section('sidebar')
<div class="dashboard-wrapper">

    <!-- Sidebar -->
    <aside class="dashboard-sidebar" id="dashboardSidebar">

        <!-- Toggle -->
        <button id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>

        <h3>Collector</h3>

        <ul class="dashboard-menu">

            <li>
                <a href="{{ route('kolektor.dashboard') }}"
                class="{{ request()->routeIs('kolektor.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('kolektor.myGallery') }}"
                class="{{ request()->routeIs('kolektor.myGallery') ? 'active' : '' }}">
                    <i class="fa-solid fa-image"></i>
                    <span class="menu-text">My Gallery</span>
                </a>
            </li>

            <li>
                <a href="{{ route('kolektor.favorites') }}"
                class="{{ request()->is('kolektor/favorites*') ? 'active' : '' }}">
                    <i class="fa-regular fa-heart"></i>
                    <span class="menu-text">Favorites</span>
                </a>
            </li>

            <li>
                <a href="{{ route('kolektor.transaksi.index') }}"
                class="{{ request()->is('kolektor/transaksi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span class="menu-text">Transactions</span>
                </a>
            </li>

            <li>
                <a href="{{ route('kolektor.orderTracking') }}"
                class="{{ request()->is('kolektor/order-tracking*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-fast"></i>
                    <span class="menu-text">Order Tracking</span>
                </a>
            </li>

            <li>
                <a href="{{ route('kolektor.profil') }}"
                class="{{ request()->routeIs('kolektor.profil') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i>
                    <span class="menu-text">Profile</span>
                </a>
            </li>

            <li>
                <a href="{{ route('kolektor.profil.edit') }}"
                class="{{ request()->routeIs('kolektor.profil.edit') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i>
                    <span class="menu-text">Account Settings</span>
                </a>
            </li>

            <li>
                <a href="{{ route('logout') }}">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </li>

        </ul>
    </aside>

    <!-- Content -->
    <main class="dashboard-content" id="dashboardContent">
        @yield('dashboard-content')
    </main>

</div>

<script>
document.getElementById("sidebarToggle").addEventListener("click", function () {
    const sidebar = document.getElementById("dashboardSidebar");
    const content = document.getElementById("dashboardContent");

    sidebar.classList.toggle("collapsed");
    content.classList.toggle("expanded");
});
</script>

@stack('page-scripts')   <!-- TAMBAHKAN BARIS INI -->
@endsection
