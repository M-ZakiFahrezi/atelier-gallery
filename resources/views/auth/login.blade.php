@extends('layouts.public')

@section('title', 'Login - Atelier Gallery')

@push('page-styles')
    <style>
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
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        body.dark-mode .auth-box {
            background: rgba(0, 0, 0, 0.75);
            border-color: rgba(255, 215, 0, 0.3);
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

        .auth-box input,
        .auth-box select {
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid rgba(180, 180, 180, 0.5);
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

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <section class="fade-up">
        <div class="auth-container">
            <div class="auth-box">
                <h2>Welcome Back!</h2>

                @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <select name="role" id="roleSelect">
                        <option value="kolektor">Kolektor</option>
                        <option value="admin">Admin</option>
                    </select>

                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>


                    <button type="submit" class="auth-btn">Login</button>
                </form>


                <div class="auth-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </section>
@endsection
