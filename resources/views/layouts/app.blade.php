<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Portfolio')</title>
    <!-- Prefer raster PNG (explicit sizes) to ensure correct tab rendering; keep SVG as fallback. -->
    <!-- Ensure public/favicon-32.png and public/favicon-16.png exist and contain the artwork filling the canvas. -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon-32.png') }}">
    <!-- Apple touch icon (recommended 180x180) -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <!-- SVG fallback: kept for browsers that support scalable favicons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen">
    @yield('content')
    @stack('scripts')
</body>

</html>