@extends('layouts.admin')

@section('title', 'Profil Admin')

@push('page-styles')
<style>
.profil-container {
    max-width: 700px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 30px 40px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

body.dark-mode .profil-container {
    background: rgba(0, 0, 0, 0.85);
    color: #f5e9c3;
}

.profil-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.profil-header img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #d4af37;
}

.profil-header h2 {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    color: #d4af37;
    margin: 0;
}

.profil-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.profil-details div {
    display: flex;
    justify-content: space-between;
    padding: 10px 15px;
    border-radius: 10px;
    background: rgba(0,0,0,0.03);
}

body.dark-mode .profil-details div {
    background: rgba(255,255,255,0.05);
}

.profil-details div span:first-child {
    font-weight: 600;
    color: #555;
}

body.dark-mode .profil-details div span:first-child {
    color: #f5e9c3;
}

.profil-details div span:last-child {
    font-weight: 500;
    color: #222;
}

body.dark-mode .profil-details div span:last-child {
    color: #f5e9c3;
}

.edit-btn {
    display: inline-block;
    margin-top: 20px;
    background: linear-gradient(135deg,#d4af37,#b89127);
    color: #fff;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    text-decoration: none;
    transition: transform 0.2s;
}

.edit-btn:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@section('content')
<section class="fade-up">
    <div class="profil-container">
        <div class="profil-header">
            <img src="{{ asset('images/user.png') }}" alt="Admin Photo">
            <h2>{{ session('admin')->nama ?? 'Admin' }}</h2>
        </div>

        <div class="profil-details">
            <div>
                <span>Username</span>
                <span>{{ session('admin')->username ?? '-' }}</span>
            </div>
            <div>
                <span>Email</span>
                <span>{{ session('admin')->email ?? '-' }}</span>
            </div>
            <div>
                <span>Role</span>
                <span>Administrator</span>
            </div>
        </div>

        <a href="{{ route('admin.editProfil') }}" class="edit-btn">✏️ Edit Profil</a>
    </div>
</section>
@endsection
