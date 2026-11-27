<!doctype html>
<html lang="fr">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6JG2Q1TZT8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-6JG2Q1TZT8');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Portfolio - Jérémy Escassut')</title>
    <!-- Prefer raster PNG (explicit sizes) to ensure correct tab rendering; keep SVG as fallback. -->
    <!-- Ensure public/favicon-32.png and public/favicon-16.png exist and contain the artwork filling the canvas. -->
    @php
    // cache-busting based on file modification time to force browsers to reload favicons
    $f16 = file_exists(public_path('favicon-16.png')) ? filemtime(public_path('favicon-16.png')) : time();
    $f32 = file_exists(public_path('favicon-32.png')) ? filemtime(public_path('favicon-32.png')) : time();
    $fsvg = file_exists(public_path('favicon.svg')) ? filemtime(public_path('favicon.svg')) : time();
    $fapple = file_exists(public_path('apple-touch-icon.png')) ? filemtime(public_path('apple-touch-icon.png')) : time();
    @endphp
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v={{ $f32 }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}?v={{ $f16 }}">
    <link rel="shortcut icon" href="{{ asset('favicon-32.png') }}?v={{ $f32 }}">
    <!-- Apple touch icon (recommended 180x180) -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v={{ $fapple }}">
    <!-- SVG fallback: kept for browsers that support scalable favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v={{ $fsvg }}" sizes="any">
    <!-- Safari pinned tab (mask-icon) -->
    <link rel="mask-icon" href="{{ asset('favicon.svg') }}?v={{ $fsvg }}" color="#40916C">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen">
    @yield('content')
    <!-- Provide the application base path for client-side code. -->
    <script>
        // `getBasePath()` returns the path where the app is mounted ('' or '/portfolio').
        // Store it on `window.APP_BASE` for JS to canonicalize URLs without hardcoding.
        window.APP_BASE = "{{ rtrim(request()->getBasePath(), '/') }}";
    </script>
    @stack('scripts')
</body>

</html>