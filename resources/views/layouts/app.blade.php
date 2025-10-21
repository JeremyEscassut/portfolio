<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', config('app.name', 'Portfolio'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Accessibility: improve contrast in dark mode for text and interactive elements */
        :root {
            --accent-text: #e6e6e6;
            /* light grey for general text */
            --muted-text: #cfcfcf;
            /* slightly muted */
        }

        @media (prefers-color-scheme: dark) {

            /* force readable colors on common elements */
            body,
            .prose,
            p,
            h1,
            h2,
            h3,
            h4,
            h5,
            a,
            li,
            span {
                color: var(--accent-text) !important;
            }

            /* ensure links and buttons have sufficient contrast */
            a,
            button,
            input,
            textarea,
            select {
                color: var(--accent-text) !important;
            }

            /* muted helper text */
            .muted,
            .text-muted {
                color: var(--muted-text) !important;
            }

            /* adjust very dark backgrounds to a near-black to avoid pure-black contrast traps */

            /* If an icon sets a brand color (like LinkedIn blue), ensure it's bright enough on dark bg */
            a.p-3.bg-white.border svg.text-\[\#0A66C2\] {
                color: #74a8e6 !important;
            }

            /* make white backgrounds slightly translucent in dark mode so text stands out */
            .bg-white {
                background-color: rgba(255, 255, 255, 0.03) !important;
            }

            /* Improve readability of main content cards/sections */
            main .rounded-md,
            main [class*="bg-[#"],
            main .bg-white {
                background-color: rgba(28, 28, 28, 0.66) !important;
                color: var(--accent-text) !important;
            }

            /* Ensure paragraph/muted text in main is slightly lighter but still muted */
            main p,
            main li,
            [class*="text-[#706f6c]"] {
                color: var(--muted-text) !important;
            }
        }

        /* Modal/dialog should stay solid and readable regardless of global card translucency */
        [role="dialog"] {
            background-color: #ffffff !important;
            color: #111111 !important;
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.35) !important;
        }

        @media (prefers-color-scheme: dark) {
            [role="dialog"] {
                background-color: #0f1113 !important;
                /* deep near-black but not pure */
                color: var(--accent-text) !important;
                box-shadow: 0 12px 36px rgba(0, 0, 0, 0.6) !important;
                border: 1px solid rgba(255, 255, 255, 0.04) !important;
            }

            /* Ensure headings/labels/controls inside dialog are legible */
            [role="dialog"] h1,
            [role="dialog"] h2,
            [role="dialog"] h3,
            [role="dialog"] label,
            [role="dialog"] p,
            [role="dialog"] input,
            [role="dialog"] textarea,
            [role="dialog"] button {
                color: var(--accent-text) !important;
            }

            [role="dialog"] input,
            [role="dialog"] textarea {
                background-color: rgba(255, 255, 255, 0.03) !important;
                border-color: rgba(255, 255, 255, 0.12) !important;
                color: var(--accent-text) !important;
            }

            /* Make primary dialog button distinct (send) */
            [role="dialog"] button.bg-\[\#1b1b18\],
            [role="dialog"] button.bg-\[\#1b1b18\] * {
                background-color: #1b1b18 !important;
                color: #fff !important;
            }
        }
    </style>

    <style>
        /* Strong overrides to ensure the contact modal/dialog is opaque and readable */
        div[role="dialog"],
        div[role="dialog"].bg-white,
        div[role="dialog"].dark\:bg-\[\#111\],
        .w-full.max-w-lg[role="dialog"] {
            background-color: #ffffff !important;
            background-image: none !important;
            opacity: 1 !important;
            -webkit-backdrop-filter: none !important;
            backdrop-filter: none !important;
            box-shadow: 0 14px 40px rgba(2, 6, 23, 0.35) !important;
            border: 0 !important;
        }

        @media (prefers-color-scheme: dark) {

            div[role="dialog"],
            div[role="dialog"].bg-white,
            div[role="dialog"].dark\:bg-\[\#111\],
            .w-full.max-w-lg[role="dialog"] {
                background-color: #0f1113 !important;
                background-image: none !important;
                opacity: 1 !important;
                -webkit-backdrop-filter: none !important;
                backdrop-filter: none !important;
                box-shadow: 0 14px 40px rgba(0, 0, 0, 0.65) !important;
                border: 1px solid rgba(255, 255, 255, 0.04) !important;
            }

            /* ensure the inner panel keeps solid background */
            div[role="dialog"]>.p-6 {
                background: transparent !important;
                /* inherit the dialog bg */
            }
        }
    </style>


    <style>
        /* Mobile-safety helpers (<=640px) - minimal and conservative */
        @media (max-width: 640px) {

            html,
            *,
            *::before,
            *::after {
                box-sizing: border-box;
            }

            img,
            video,
            iframe,
            embed,
            object,
            table,
            td,
            blockquote,
            code,
            pre,
            textarea,
            input {
                max-width: 100%;
                height: auto;
                word-wrap: break-word;
                overflow-wrap: anywhere;
            }

            p,
            li,
            td,
            th,
            blockquote,
            code,
            pre,
            samp,
            textarea {
                -webkit-hyphens: auto;
                -moz-hyphens: auto;
                hyphens: auto;
                word-break: break-word;
                white-space: normal;
            }

            /* Utility helpers - explicit mobile visibility helpers */
            .u-mobile {
                display: block !important;
            }

            .u-no-mobile {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen">
    <!-- moved radial decorative background onto the main container to avoid a fixed overlay -->
    <div class="mx-auto min-h-screen max-w-screen-xl px-6 py-12 font-sans md:px-12 md:py-16 lg:py-0" style="background-image: radial-gradient(600px at 75% 10%, rgba(0,0,0,0.04), transparent 60%); background-repeat: no-repeat; background-position: 75% 10%; background-size: 600px;">
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