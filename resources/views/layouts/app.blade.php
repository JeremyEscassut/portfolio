<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', config('app.name', 'Portfolio'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen">
    <!-- decorative radial background (pointer-events-none so it doesn't interfere) -->
    <div class="pointer-events-none fixed inset-0 z-10 transition duration-300" style="background: radial-gradient(600px at 75% 10%, rgba(0,0,0,0.04), transparent 60%);"></div>

    <!-- centered container (Brittany-like structure) with page scrolling -->
    <div class="mx-auto min-h-screen max-w-screen-xl px-6 py-12 font-sans md:px-12 md:py-16 lg:py-0">
        <header class="py-6 flex items-center justify-end">
            {{-- Le titre visible du site est volontairement omis (nom dans la page d'accueil). 
                 Les liens texte CV / Contact ont été retirés : les boutons icônes dans la page
                 gèrent l'accès au CV et au modal de contact (responsive). --}}
        </header>

        <!-- Skip link for keyboard users -->
        <a href="#content" class="absolute left-0 top-0 block -translate-x-full focus:translate-x-0 focus:outline-none rounded bg-gradient-to-br from-teal-400 via-blue-500 to-purple-600 px-4 py-3 text-sm font-bold uppercase tracking-widest text-white focus-visible:translate-x-0">Aller au contenu</a>

        <main>
            @yield('content')
        </main>

        {{-- Footer moved into the right column of pages like the home view --}}
    </div>
    @stack('scripts')

</body>

</html>