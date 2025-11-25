@extends('layouts.public')

@section('title', 'Contact - Atelier Gallery')

@push('page-styles')
    <style>
        /* ---------- BODY ---------- */
        body {
            background: linear-gradient(145deg, #f8f5ef 0%, #e8dcc3 100%);
            font-family: 'Cinzel', serif;
            color: #3a2e1f;
            transition: background 0.6s ease, color 0.6s ease;
        }

        /* Shimmer & pattern */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("{{ asset('images/bg-pola.png') }}") center/cover repeat;
            opacity: 0.25;
            pointer-events: none;
            z-index: -2;
            filter: brightness(1.05) saturate(0.9);
            transition: opacity 0.6s ease, filter 0.6s ease;
        }

        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.25) 40%, rgba(255, 255, 255, 0) 80%);
            opacity: 0.15;
            pointer-events: none;
            z-index: -1;
            animation: shimmerMove 14s ease-in-out infinite alternate;
            mix-blend-mode: soft-light;
            transition: opacity 0.6s ease;
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
        .contact-header {
            text-align: center;
            margin: 40px 20px 20px 20px;
            text-shadow: 0 0 6px rgba(212, 175, 55, 0.4);
        }

        .contact-header h1 {
            font-family: 'Cinzel', serif;
            font-size: 2.8rem;
            color: #3a2e1f;
            transition: color 0.6s ease, text-shadow 0.6s ease;
        }

        .contact-header p {
            font-size: 1rem;
            color: #4a4032;
            margin-top: 10px;
            transition: color 0.6s ease;
        }

        /* ---------- GRID ---------- */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        /* ---------- SUCCESS MESSAGE ---------- */
        .success-message {
            background: linear-gradient(135deg, #d4af37, #b9952a);
            color: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ---------- FORM CARD ---------- */
        .contact-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            box-shadow: 0 12px 30px rgba(212, 175, 55, 0.35);
            transform: translateY(-3px);
        }

        .contact-card label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
            color: #3a2e1f;
        }

        .contact-card input,
        .contact-card textarea {
            width: 100%;
            padding: 10px 15px;
            margin-top: 5px;
            border-radius: 10px;
            border: 1px solid #d4af37;
            background: #fffbe8;
            resize: none;
            transition: all 0.3s ease;
        }

        .contact-card input:focus,
        .contact-card textarea:focus {
            outline: none;
            border-color: #b9952a;
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.3);
        }

        .contact-card button {
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #d4af37, #b9952a);
            color: #fff;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .contact-card button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(212, 175, 55, 0.4);
        }

        /* ---------- INFO CARD ---------- */
        .info-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .info-card h3 {
            color: #3a2e1f;
            margin-bottom: 15px;
        }

        .info-card p {
            color: #4a4032;
            margin: 8px 0;
        }

        .info-card a {
            color: #d4af37;
            text-decoration: none;
            font-weight: 500;
        }

        .info-card a:hover {
            text-decoration: underline;
        }

        .success-message {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            font-weight: 600;
            color: #3a2e1f;
            margin-bottom: 20px;
            font-family: 'Cinzel', serif;

            /* added */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 120px;
            /* atur tinggi sesuai kebutuhan */
        }



        @media(max-width:600px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ---------- DARK MODE ---------- */
        body.dark-mode {
            background: radial-gradient(circle at top left, rgba(10, 30, 50, 0.45) 0%, rgba(6, 20, 35, 0.55) 40%, rgba(3, 10, 20, 0.65) 100%), url("{{ asset('images/dark-bg-pola.png') }}") center/cover no-repeat fixed;
            color: #e2ebf3;
        }

        body.dark-mode::before {
            opacity: 0.18;
            filter: brightness(0.8);
        }

        body.dark-mode::after {
            background: linear-gradient(120deg, rgba(0, 200, 255, 0.25), rgba(255, 255, 255, 0.02), rgba(0, 200, 255, 0.25));
            mix-blend-mode: screen;
            opacity: 0.2;
        }

        body.dark-mode .contact-header h1 {
            color: #00e1ff;
            text-shadow: 0 0 10px rgba(0, 230, 255, 0.5);
        }

        body.dark-mode .contact-header p {
            color: #a4bfd2;
        }

        body.dark-mode .contact-card,
        body.dark-mode .info-card {
            background: rgba(10, 25, 45, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 18px rgba(0, 210, 255, 0.12);
        }

        body.dark-mode .contact-card label,
        body.dark-mode .contact-card input,
        body.dark-mode .contact-card textarea,
        body.dark-mode .contact-card button,
        body.dark-mode .info-card h3,
        body.dark-mode .info-card p,
        body.dark-mode .info-card a {
            color: #e2ebf3;
        }

        body.dark-mode .contact-card input,
        body.dark-mode .contact-card textarea {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(0, 210, 255, 0.35);
        }

        body.dark-mode .contact-card input:focus,
        body.dark-mode .contact-card textarea:focus {
            box-shadow: 0 0 8px rgba(0, 210, 255, 0.3);
            border-color: rgba(0, 210, 255, 0.5);
        }

        body.dark-mode .contact-card button {
            background: linear-gradient(135deg, #00e1ff, #0080a3);
            box-shadow: 0 4px 10px rgba(0, 210, 255, 0.3);
            color: #fff;
        }

        body.dark-mode .success-message {
            background: rgba(33, 33, 33, 0.85);
            color: #f7e6b5;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(212, 175, 55, 0.15);
        }
    </style>
@endpush

@section('content')
    <div class="contact-header">
        <h1>Get in Touch</h1>
        <p>We would love to hear from you. Send us a message or find our contact information below.</p>
    </div>

    <div class="contact-grid">
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="contact-card">
            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <label for="name">Your Name</label>
                <input type="text" name="name" id="name" required>

                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>

                <label for="subject">Subject</label>
                <input type="text" name="subject" id="subject" required>

                <label for="message">Message</label>
                <textarea name="message" id="message" rows="6" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <h3>Contact Information</h3>
            <p><strong>Address:</strong> 123 Atelier Street, Art City</p>
            <p><strong>Phone:</strong> +62 821 7541 1361</p>
            <p><strong>Email:</strong> <a href="mailto:info@ateliergallery.com">galleryatelier53@gmail.com</a></p>
            <p><strong>Website:</strong> <a href="https://ateliergallery.com" target="_blank">ateliergallery.com</a></p>
            <p><strong>Open Hours:</strong> Mon-Fri 10:00 - 18:00</p>
        </div>
    </div>
@endsection
