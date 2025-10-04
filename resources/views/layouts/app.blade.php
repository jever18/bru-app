<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        
        @stack('scripts')

        <style>
            .app-background {
                background-image: url({{ asset('images/running_bg.jpg') }}) !important;
                background-size: cover !important;
                background-position: center !important;
                background-attachment: fixed !important; /* Membuat gambar tetap saat scroll */
                position: relative;
            }
            .app-background::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.65); /* Overlay hitam transparan */
                z-index: 1;
            }
            .app-content {
                position: relative;
                z-index: 2; /* Konten di atas overlay */
            }
        </style>
    </head>
    
    <body class="font-sans antialiased app-background"> 
        <div id="app" class="min-vh-100 app-content"> 
            
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-transparent text-white border-bottom border-secondary" style="background-color: rgba(255, 255, 255, 0.15) !important; backdrop-filter: blur(5px);">
                    <div class="container py-3">
                        <div class="h2 mb-0 text-white text-shadow-light">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endif

            <main>
                <div class="container py-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
