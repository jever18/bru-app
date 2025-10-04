<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Brun-app') }} | Pelacak Lari</title>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <style>
            /* Pastikan body mengisi seluruh viewport */
            body {
                margin: 0;
                padding: 0;
            }

            /* 1. BACKGROUND DENGAN !IMPORTANT */
            .welcome-background {
                /* Menggunakan Blade asset() dan !important */
                background-image: url({{ asset('images/running_bg.jpg') }}) !important; 
                background-size: cover !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
                
                /* Layout */
                position: relative;
                min-height: 100vh; /* Menggunakan 100vh agar memenuhi layar */
                display: flex;
                flex-direction: column;
            }
            
            /* 2. OVERLAY HITAM TRANSPARAN */
            .welcome-background::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.65); /* Sedikit lebih gelap agar teks putih kontras */
                z-index: 1;
            }
            
            /* 3. KONTEN (Teks, Tombol) */
            .welcome-content {
                position: relative;
                z-index: 2; /* Di atas overlay */
            }
            .text-shadow-light {
                text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8); /* Shadow yang lebih kuat untuk kontras */
            }
            
            /* 4. RESPONSIF JUDUL */
            .welcome-title {
                font-size: 7vw !important;
            }
            @media (max-width: 768px) {
                .welcome-title {
                    font-size: 15vw !important;
                }
            }
        </style>
    </head>
    
    <body class="antialiased">
        <div class="welcome-background text-white"> 
            
            <div class="position-absolute top-0 end-0 p-3 p-lg-4 welcome-content">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/runs') }}" class="btn btn-outline-light btn-sm shadow-sm me-2">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm shadow-sm me-2">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-warning btn-sm shadow text-dark fw-bold">
                                Daftar
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="container d-flex flex-column align-items-center justify-content-center flex-grow-1 pt-5 pb-5 welcome-content" style="flex-grow: 1;">
                
                <h1 class="welcome-title display-1 fw-bolder text-white text-uppercase text-center mb-2 text-shadow-light">
                    Brun-app
                </h1>
                
                <p class="h4 text-light text-center max-w-500px text-shadow-light">
                    Rekam setiap langkah. Lihat statistik. Kalahkan rekor terbaik Anda. <span class="fw-bold text-warning">Motivasi</span> Anda, di setiap lari.
                </p>

                <div class="mt-4 mt-lg-5 d-flex flex-column flex-sm-row justify-content-center" style="gap: 15px;">
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg rounded-pill px-5 shadow-lg text-dark fw-bold">
                        Mulai Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 border shadow-sm fw-bold">
                        Sudah Punya Akun?
                    </a>
                </div>

                <p class="mt-5 text-secondary small text-shadow-light">
                    Dibuat dengan Laravel, Bootstrap, dan Chart.js
                </p>
            </div>
            
        </div>
    </body>
</html>
