@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('page-styles')
<style>
.admin-container {
    min-height: calc(100vh - 90px);
    padding: 60px 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: transparent;
}

.admin-card {
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(14px);
    border-radius: 18px;
    padding: 40px 50px;
    max-width: 1000px;
    width: 100%;
    border: 1px solid rgba(212,175,55,0.3);
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    text-align: center;
}

body.dark-mode .admin-card {
    background: rgba(0,0,0,0.7);
    color: #f5e9c3;
    border-color: rgba(255,215,0,0.3);
}

.admin-card h1 {
    font-family: 'Cinzel', serif;
    color: #b89e3f;
    margin-bottom: 20px;
}

.admin-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
}

.admin-actions a {
    background: linear-gradient(135deg, #d4af37, #b89127);
    color: #fff;
    padding: 15px 25px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: transform 0.2s ease;
}

.admin-actions a:hover {
    transform: translateY(-3px);
}

</style>
@endpush

@section('content')
<section class="fade-up">
    <div class="admin-container">
        <div class="admin-card">
            <h1>Selamat Datang, admin!! {{ $admin->nama_admin }}</h1>
            <p>Gunakan dashboard ini untuk mengelola data koleksi, seniman, dan galeri.</p>

            <div class="admin-actions">
                <a href="{{ route('admin.karya.index') }}">📄 Karya Seni</a>
                <a href="{{ route('admin.seniman.index') }}">🎨 Seniman</a>
                <a href="{{ route('admin.galeri.index') }}">🏛 Galeri</a>
                <a href="{{ route('logout') }}">🚪 Logout</a>
            </div>
        </div>
    </div>
</section>
@endsection
