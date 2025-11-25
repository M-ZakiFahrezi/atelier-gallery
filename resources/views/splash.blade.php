<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Screen Cinematic</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background:
                linear-gradient(180deg, rgba(15, 30, 60, 0.9), rgba(25, 40, 70, 0.92)),
                url('{{ asset('images/bg-pola.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            font-family: 'Cinzel', serif;
            overflow: hidden;
            transition: opacity 0.8s ease;
            position: relative;
            color: #fff;
        }

        body.fade-out {
            opacity: 0;
        }

        /* 🌊 Lapisan shimmer ocean-gold */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 25% 30%, rgba(0, 180, 255, 0.18), transparent 60%),
                radial-gradient(circle at 80% 70%, rgba(255, 220, 130, 0.12), transparent 65%);
            background-blend-mode: screen;
            animation: shimmerWave 10s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes shimmerWave {
            from {
                background-position: 0% 0%, 100% 100%;
            }

            to {
                background-position: 100% 100%, 0% 0%;
            }
        }

        /* ✨ Cahaya pusat lembut */
        body::after {
            content: "";
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 220, 120, 0.25), transparent 70%);
            filter: blur(130px);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            animation: glowPulse 6s ease-in-out infinite alternate;
        }

        @keyframes glowPulse {
            from {
                opacity: 0.5;
                transform: translate(-50%, -50%) scale(1);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.05);
            }
        }

        /* 🖼️ Logo */
        .logo {
            position: relative;
            z-index: 2;
        }

        .logo img {
            width: 150px;
            opacity: 0;
            transform: scale(0.85);
            animation: fadeInScale 2s ease-out forwards;
            filter: drop-shadow(0 0 25px rgba(255, 215, 0, 0.45));
        }

        .logo::after {
            content: "";
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: skewX(-25deg);
            animation: shineSweep 2s ease-in-out 1.5s forwards;
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.8);
                filter: blur(5px);
            }

            100% {
                opacity: 1;
                transform: scale(1);
                filter: blur(0);
            }
        }

        @keyframes shineSweep {
            from {
                left: -75%;
            }

            to {
                left: 125%;
            }
        }

        /* 🌟 Tagline cerah elegan */
        .tagline {
            font-size: 2.1rem;
            margin-top: 25px;
            font-weight: 700;
            background: linear-gradient(90deg, #ffd700, #fff5b0, #00cfff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 3px;
            opacity: 0;
            transform: translateY(50px);
            animation: slideUp 2s forwards ease 2s;
            text-shadow: 0 0 14px rgba(255, 255, 200, 0.3);
            position: relative;
            z-index: 2;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loader keemasan dengan nuansa biru */
        .loader {
            margin-top: 40px;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: conic-gradient(from 0deg,
                    rgba(255, 215, 0, 0.9),
                    rgba(0, 180, 255, 0.7),
                    rgba(255, 255, 255, 0.3),
                    transparent 90%);
            animation: spin 2.5s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
            box-shadow: 0 0 25px rgba(255, 220, 100, 0.3);
            position: relative;
            z-index: 2;
        }

        .loader::before {
            content: "";
            position: absolute;
            inset: 6px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #08131f, #0f2033);
            box-shadow: inset 0 0 10px rgba(255, 215, 0, 0.15);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>


</head>

<body>
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Atelier Logo">
    </div>
    <div class="tagline">Art is a Blast</div>
    <div class="loader"></div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const audio = new Audio("{{ asset('audio/intro1.mp3') }}");
            audio.volume = 0;
            audio.muted = true;
            audio.loop = false;

            const startAudio = () => {
                audio.play().then(() => {
                    setTimeout(() => {
                        audio.muted = false;
                        let vol = 0;
                        const fade = setInterval(() => {
                            if (vol < 0.6) {
                                vol += 0.02;
                                audio.volume = vol;
                            } else clearInterval(fade);
                        }, 120);
                    }, 500);
                }).catch(err => console.log("Gagal main:", err));
            };

            const enableAudio = () => {
                startAudio();
                document.removeEventListener("click", enableAudio);
                document.removeEventListener("touchstart", enableAudio);
            };

            // Tunggu klik/tap pertama user untuk autoplay aman
            document.addEventListener("click", enableAudio);
            document.addEventListener("touchstart", enableAudio);

            // Transisi keluar
            setTimeout(() => {
                document.body.classList.add("fade-out");
                setTimeout(() => window.location.href = "{{ route('home') }}", 800);
            }, 4500);
        });
    </script>
</body>

</html>
