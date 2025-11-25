@extends('layouts.public')

@section('title', 'Register - Atelier Gallery')

@push('page-styles')
<style>
/* --- CSS tetap sama seperti sebelumnya --- */
.auth-container {
    min-height: calc(100vh - 90px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px;
}

.auth-box {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 16px;
    padding: 40px 50px;
    max-width: 500px;
    width: 100%;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

body.dark-mode .auth-box {
    background: rgba(0,0,0,0.75);
    border-color: rgba(255,215,0,0.3);
    color: #f5e9c3;
}

.auth-box h2 {
    font-family: 'Cinzel', serif;
    color: #b89e3f;
    margin-bottom: 25px;
    font-size: 1.8rem;
}

.auth-box form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.auth-box input, .auth-box select {
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid rgba(180,180,180,0.5);
    font-family: 'Poppins', sans-serif;
    font-size: 0.95rem;
}

.auth-btn {
    background: linear-gradient(135deg, #d4af37, #b89127);
    border: none;
    color: #fff;
    padding: 12px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.auth-btn:hover {
    transform: translateY(-2px);
}

.auth-link {
    margin-top: 20px;
    font-size: 0.9rem;
}

.auth-link a {
    color: #b89127;
    text-decoration: none;
}

body.dark-mode .auth-link a {
    color: #ffd700;
}

/* Error message untuk password tidak cocok */
#error-message {
    color: red;
    font-size: 0.85rem;
    text-align: left;
    display: none;
    padding-left: 5px;
}
</style>
@endpush

@section('content')
<section class="fade-up">
    <div class="auth-container">
        <div class="auth-box">
            <h2>Daftar Kolektor</h2>

            @if ($errors->any())
                <div style="color:red; margin-bottom:10px;">
                    <ul style="padding-left: 15px; text-align:left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="registerForm" action="{{ route('register.post') }}" method="POST">
                @csrf
                <input type="text" name="nama_kolektor" placeholder="Nama Lengkap" required>
                <input type="text" name="kontak" placeholder="Kontak (HP)" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="alamat" placeholder="Alamat" required>
                
                <select name="jenis_kolektor" required>
                    <option value="">Pilih Jenis Kolektor</option>
                    <option value="individu">Individu</option>
                    <option value="institusi">Institusi</option>
                </select>

                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" required>

                <div id="error-message">Password dan konfirmasi tidak cocok!</div>

                <button type="submit" class="auth-btn">Daftar</button>
            </form>

            <div class="auth-link">
                Sudah punya akun? <a href="{{ route('login') }}">Login Sekarang</a>
            </div>
        </div>
    </div>
</section>

<script>
// Validasi password & konfirmasi
const form = document.getElementById('registerForm');
const password = document.getElementById('password');
const passwordConfirmation = document.getElementById('password_confirmation');
const errorMessage = document.getElementById('error-message');

form.addEventListener('submit', function(e) {
    if(password.value !== passwordConfirmation.value) {
        e.preventDefault();
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
    }
});
</script>
@endsection
