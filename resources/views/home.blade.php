@extends('layouts.app')

@section('content')
<div class="lg:flex lg:justify-between lg:gap-4">
    <!-- LEFT: navigation / identity (centré verticalement sur grand écran) -->
    <!-- LEFT: like Brittany, sticky on large screens within page scroll -->
    <aside class="order-1 lg:order-1 lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-[48%] lg:flex-col lg:justify-between lg:py-24">
        <div class="w-full bg-transparent dark:bg-transparent rounded-lg p-8 lg:p-12 shadow flex flex-col justify-between">
            <!-- Name -->
            <div class="mb-8">
                <!-- Single-cycle theme icon (click to cycle Auto → Clair → Sombre) -->
                <div class="mt-3 flex justify-end" x-data="{ theme: localStorage.getItem('theme') || 'auto', init(){ if(this.theme==='dark'){ document.documentElement.classList.add('dark') } else if(this.theme==='light'){ document.documentElement.classList.remove('dark') } else { document.documentElement.classList.toggle('dark', window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) } }, set(t){ this.theme=t; if(t==='auto'){ localStorage.removeItem('theme'); document.documentElement.classList.toggle('dark', window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) } else if(t==='dark'){ localStorage.setItem('theme','dark'); document.documentElement.classList.add('dark') } else { localStorage.setItem('theme','light'); document.documentElement.classList.remove('dark') } }, cycle(){ const order=['auto','light','dark']; let i=order.indexOf(this.theme); this.set(order[(i+1)%order.length]); } }" x-init="init()">
                    <button type="button" @click="cycle()" :title="(theme==='auto' ? 'Auto — respecte les réglages système' : (theme==='light' ? 'Mode clair' : 'Mode sombre')) + ' — cliquez pour changer'" class="p-2 rounded" aria-label="Sélecteur de thème">
                        <template x-if="theme==='auto'">
                            <!-- system icon -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.5"></rect>
                                <path d="M8 20h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </template>
                        <template x-if="theme==='light'">
                            <!-- sun icon -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5"></circle>
                                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"></path>
                            </svg>
                        </template>
                        <template x-if="theme==='dark'">
                            <!-- moon icon -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </template>
                    </button>
                </div>

                <h1 class="text-3xl font-semibold">Jérémy Escassut</h1>
                <h2 class="text-xl font-semibold">Développeur Full Stack</h2>
                <p class="text-[#706f6c] mt-3">Bienvenue sur mon portfolio. Voici un aperçu de mon travail et de mon expérience.</p>
            </div>

            <!-- Section links (hidden on small screens, visible on desktop) -->
            <nav class="mt-8 mb-6 hidden lg:block">
                <ul class="space-y-3 text-sm text-end">
                    <li><a href="#about" class="font-medium hover:underline">À propos</a></li>
                    <li><a href="#projects" class="font-medium hover:underline">Projets</a></li>
                    <li><a href="#experience" class="font-medium hover:underline">Expérience</a></li>
                    <li><a href="#skills" class="font-medium hover:underline">Compétences</a></li>
                </ul>
            </nav>


            <!-- Social icon buttons (Tabler icons, larger, no border) -->
            <div class="mt-6 flex gap-3 items-center text-sm text-[#706f6c] justify-end">
                <!-- LinkedIn (inline from resources/svg/icon-linkedin.svg) -->
                <a href="https://www.linkedin.com/in/jérémy-escassut-57773427" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn (ouvre dans un nouvel onglet)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--linkedin">
                    @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-linkedin.svg')); } catch (Exception $e) { } @endphp
                    @if($svg) {!! $svg !!} @endif
                </a>

                <!-- GitLab (inline from resources/svg/icon-gitlab.svg) -->
                <a href="https://gitlab.com/JeremyEscassut" id="gitlab-link" target="_blank" rel="noopener noreferrer" aria-label="GitLab (ouvre dans un nouvel onglet)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--gitlab">
                    @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-gitlab.svg')); } catch (Exception $e) { } @endphp
                    @if($svg) {!! $svg !!} @endif
                </a>
            </div>


            <!-- Actions: icons -->
            <div id="contact-cv-actions" class="mt-6 flex items-center gap-3 justify-end">
                <!-- Contact (inline from resources/svg/icon-contact.svg) -->
                <button onclick="setTimeout(()=>window.dispatchEvent(new CustomEvent('open-contact')), 0)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--contact" aria-label="Ouvrir le contact" style="margin-top:2px;">
                    @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-contact.svg')); } catch (Exception $e) { } @endphp
                    @if($svg) {!! $svg !!} @endif
                </button>

                <!-- CV (inline from resources/svg/icon-cv.svg) -->
                <a href="/cv/CV_Dev-FS_2025-10-24_Jeremy_Escassut.pdf" target="_blank" rel="noopener noreferrer" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--cv" aria-label="Voir le CV (ouvre dans un nouvel onglet)" style="margin-top:2px;">
                    @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-cv.svg')); } catch (Exception $e) { } @endphp
                    @if($svg) {!! $svg !!} @endif
                </a>
            </div>
        </div>
    </aside>

    <!-- RIGHT: page scroll handles content; main not an independent scroller -->
    <main id="content" class="order-2 lg:order-2 pt-24 lg:py-24 lg:w-[52%]">



        <section id="about" class="mb-8 scroll-mt-24" aria-label="À propos">
            <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                <h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">À propos</h2>
            </div>
            <div class="bg-[#fff2f2] rounded-md p-6">
                <p class="text-sm text-[#706f6c]">Après plusieurs années passées dans la traduction et la gestion de projets IT, j’ai choisi de me tourner vers le développement pour concrétiser une passion que je nourrissais depuis longtemps. Cette transition s’est faite naturellement : j’y ai retrouvé la même rigueur, le même sens du détail et le même plaisir à transformer une idée en solution concrète et utile.</p>
                <p class="text-sm text-[#706f6c] mt-3">Ce que j’aime dans le code, c’est l’équilibre entre logique et créativité. Je cherche toujours à optimiser, à rendre les choses plus fluides, plus claires, plus efficaces — sans jamais perdre de vue l’expérience de l’utilisateur final. La satisfaction vient pour moi d’un système qui fonctionne bien, proprement, et qui simplifie réellement la vie de ceux qui l’utilisent.</p>
                <p class="text-sm text-[#706f6c] mt-2">J’accorde une grande importance à la bienveillance et au respect dans les relations de travail. J’aime collaborer, partager mes connaissances et apprendre des autres. Curieux, honnête et déterminé, je crois en une amélioration continue, individuelle comme collective — celle qui permet à une équipe de progresser ensemble et de construire des projets durables.</p>
                <p class="text-sm text-[#706f6c] mt-2">En dehors du code, je trouve mon équilibre dans le sport et la musique : badminton, course à pied, salsa et bachata rythment mon temps libre et nourrissent mon énergie au quotidien.</p>
            </div>
        </section>

        <section id="projects" class="mb-8 scroll-mt-24" aria-label="Projets">
            <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                <h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">Projets</h2>
            </div>
            <!-- Remove gray side panel so projects use full width; images are placed below text and sized to 3:2 aspect ratio -->
            <div class="p-0">
                <div class="flex flex-col gap-4">
                    <div class="w-full p-4 bg-white rounded flex flex-col gap-4 project-card">
                        <div class="flex-1 text-sm text-[#706f6c]">
                            <div class="flex items-center">
                                <span class="text-base font-semibold">Livret CDA</span>
                                @php $repoSvg = ''; try { $repoSvg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-gitlab-repo-link.svg')); } catch (Exception $e) { } @endphp
                                <a href="https://gitlab.com/JeremyEscassut/livret-cda" target="_blank" rel="noopener noreferrer" aria-label="Voir le dépôt GitLab - Livret CDA" class="ml-5 repo-link p-1 rounded hover:bg-gray-100 dark:hover:bg-neutral-800">
                                    @if($repoSvg) {!! $repoSvg !!} @endif
                                </a>
                            </div>
                            <p class="mt-2 text-sm text-[#706f6c]">Application web pour gérer sessions de formation, utilisateurs (profils), compétences et espaces admin. Elle inclut des fixtures pour peupler les données, des contrôleurs et templates Twig pour l’IU, ainsi qu’une suite de tests PHPUnit avec rapports de couverture. Le code est organisé avec Doctrine pour la persistance et des outils de génération/CI.</p>

                            <div class="project-tags mt-3">
                                <span class="project-tag">PHP</span>
                                <span class="project-tag">Symfony</span>
                                <span class="project-tag">EasyAdmin</span>
                                <span class="project-tag">PHPUnit</span>
                            </div>


                        </div>
                        <img loading="lazy" width="600" height="400" src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'Livret_CDA.png'))) }}" alt="Aperçu du projet Livret CDA" class="rounded project-image">
                    </div>

                    <div class="w-full p-4 bg-white rounded flex flex-col gap-4 project-card">
                        <div class="flex-1 text-sm text-[#706f6c]">
                            <div class="flex items-center">
                                <span class="text-base font-semibold">Breaking Cat</span>
                                @php $repoSvg = ''; try { $repoSvg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-gitlab-repo-link.svg')); } catch (Exception $e) { } @endphp
                                <a href="https://gitlab.com/JeremyEscassut/breakingcat" target="_blank" rel="noopener noreferrer" aria-label="Voir le dépôt GitLab - Breaking Cat" class="ml-5 repo-link p-1 rounded hover:bg-gray-100 dark:hover:bg-neutral-800">
                                    @if($repoSvg) {!! $repoSvg !!} @endif
                                </a>
                            </div>
                            <p class="mt-2 text-sm text-[#706f6c]">Mini‑jeu web accessible après authentification, avec deux modes de jeu (Normal et Hardcore, ce dernier indiqué comme inactif). Le joueur lance une partie et enregistre son score, qui alimente un tableau des meilleurs scores affiché sur l’accueil. L’interface est responsive (Bootstrap) avec un mode thème clair/sombre, et les données (utilisateurs/scores) sont persistées en base MySQL via PDO.</p>

                            <div class="project-tags mt-3">
                                <span class="project-tag">PHP</span>
                                <span class="project-tag">MySQL</span>
                                <span class="project-tag">PDO</span>
                                <span class="project-tag">JavaScript</span>
                            </div>
                        </div>
                        <img loading="lazy" width="600" height="400" src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'Breaking_Cat.png'))) }}" alt="Breaking Cat - aperçu" class="rounded project-image">
                    </div>
                </div>
            </div>
        </section>

        <section id="experience" class="mb-8 scroll-mt-24" aria-label="Expérience">
            <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                <h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">Expérience</h2>
            </div>
            <div class="bg-[#fff7db] rounded-md p-6">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="text-sm text-[#706f6c] font-medium">Juin 2025–PRÉSENT</div>
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight">Formation CDA - C2RT</h2>
                            <h3 class="text-sm font-medium text-[#5f615f] mt-1">Titre RNCP Concepteur Développeur d'Applications</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="text-sm text-[#706f6c] font-medium">2012–2024</div>
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight">Traducteur/Chef de projets - e2f</h2>
                            <h3 class="text-sm font-medium text-[#5f615f] mt-1">Gestion d'un parc informatique, dépannage</h3>
                            <p class="text-sm text-[#706f6c] mt-2">Titulaire d’un Master en traduction (CETIM), j’ai assuré la traduction, la relecture et le contrôle qualité de contenus IT. J’ai géré des projets multilingues, coordonné des traducteurs et assuré le support technique local : maintenance du parc informatique, installation et dépannage logiciels, rédaction de procédures internes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="skills" class="mb-8 scroll-mt-24" aria-label="Compétences">
            <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                <h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">Compétences</h2>
            </div>

            <!-- Simplified skills container: no green background, tighter gaps, centered icon tiles -->
            <div class="p-0 skills-grid">
                <div class="space-y-4 text-sm">
                    <!-- Front group -->
                    <div>
                        <h3 class="text-sm font-semibold mb-2">Front</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-6 gap-2">
                            <div class="bg-white rounded flex items-center justify-center skill-angular skill-tile" title="Angular" aria-label="Angular">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-angular.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-html5 skill-tile" title="HTML5" aria-label="HTML5">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-html5.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-ionic skill-tile" title="Ionic" aria-label="Ionic">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-ionic.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                        </div>
                    </div>

                    <!-- Back group -->
                    <div>
                        <h3 class="text-sm font-semibold mb-2">Back</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-6 gap-2">
                            <div class="bg-white rounded flex items-center justify-center skill-laravel skill-tile" title="Laravel" aria-label="Laravel">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-laravel.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-nodejs skill-tile" title="Node.js" aria-label="Node.js">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-nodejs.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-python skill-tile" title="Python" aria-label="Python">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-python.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-django skill-tile" title="Django" aria-label="Django">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-django.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-java skill-tile" title="Java" aria-label="Java">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-java.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-spring-boot skill-tile" title="Spring Boot" aria-label="Spring Boot">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-spring-boot.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-symfony skill-tile" title="Symfony" aria-label="Symfony">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-symfony.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                        </div>
                    </div>

                    <!-- Others group -->
                    <div>
                        <h3 class="text-sm font-semibold mb-2">Autres</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-6 gap-2">
                            <div class="bg-white rounded flex items-center justify-center skill-jira skill-tile" title="Jira" aria-label="Jira">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-jira.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-postman skill-tile" title="Postman" aria-label="Postman">
                                @php
                                $postmanSvg = '';
                                try { $postmanSvg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-postman.svg')); } catch (Exception $e) { }
                                $postmanDarkSvg = '';
                                try { $postmanDarkSvg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-postman-dark.svg')); } catch (Exception $e) { }
                                @endphp
                                @if($postmanSvg)
                                <span class="postman-light">{!! $postmanSvg !!}</span>
                                @endif
                                @if($postmanDarkSvg)
                                <span class="postman-dark" aria-hidden="true">{!! $postmanDarkSvg !!}</span>
                                @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-php skill-tile" title="PHP" aria-label="PHP">
                                {{-- keep PHP inline as before --}}
                                @php $phpSvg = ''; try { $phpSvg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-php.svg')); } catch (Exception $e) { } @endphp
                                @if($phpSvg) {!! $phpSvg !!} @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer within right column: becomes sticky only when scrolled to bottom -->
        <footer id="right-footer" class="mt-6 p-6 text-sm text-[#706f6c] bg-transparent right-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }} — Conçu avec Laravel, Tailwind et Alpine.
        </footer>

    </main>
</div>

@push('styles')
<style>
    /* enable smooth scrolling for in-page anchors */
    html {
        scroll-behavior: smooth;
    }

    /* when main is scrollable (lg), ensure focus outline is visible */
    main:focus {
        outline: none;
    }

    /* page scroll on body/html by default; keep smooth scrolling */
    @media (min-width: 500px) {
        /* no special overflow on body; aside will be sticky */
    }

    /* right-footer: default is in-flow; when .stuck is added, it becomes fixed at bottom-right of the viewport aligned with the main area */
    #right-footer {
        position: relative;
    }

    #right-footer.stuck {
        position: fixed;
        bottom: 0;
        right: 0;
        left: 0;
        /* keep visual appearance identical to page: transparent, no border/shadow */
        background: transparent !important;
        box-shadow: none !important;
        z-index: 40;
    }

    /* Ensure dark-mode mirrors the same fixed/footer geometry as light mode so the
       sticky-to-fixed transition behaves identically on desktop. This prevents the
       footer from being shifted or resized unexpectedly when `.dark` is active. */
    .dark #right-footer.stuck {
        position: fixed;
        bottom: 0;
        right: 0;
        left: 0;
        background: transparent !important;
        box-shadow: none !important;
        z-index: 40;
    }

    /* On large screens, constrain the fixed footer to the right column
       (aside uses lg:w-[48%], main uses lg:w-[52%] — keep footer aligned with main) */
    @media (min-width: 1024px) {
        #right-footer.stuck {
            left: 48%;
            /* start where the left column ends */
            right: 0;
            /* stretch to the viewport right edge (fills main column) */
            width: 52%;
            /* explicit width matching main's fraction to be robust */
            max-width: calc(100% - 48%);
            /* remain visually identical when stuck */
            background: transparent !important;
            box-shadow: none !important;
        }

        /* Dark-mode mirror for desktop widths */
        .dark #right-footer.stuck {
            left: 48%;
            right: 0;
            width: 52%;
            max-width: calc(100% - 48%);
            background: transparent !important;
            box-shadow: none !important;
        }

        /* Prevent footer text wrapping in dark mode when fixed on desktop.
           Mirror the light-mode layout and keep content on a single line. */
        .dark #right-footer.stuck {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            white-space: nowrap !important;
            overflow: visible !important;
        }
    }

    /* Contact/CV actions when fixed on small screens */
    #contact-cv-actions.fixed {
        position: fixed !important;
        margin-top: 0px;
        top: 0px !important;
        right: 55px !important;
        left: auto !important;
        z-index: 60 !important;
        background: transparent !important;
        box-shadow: none !important;
        display: flex !important;
        align-items: center !important;
    }

    /* Enlarge PHP skill icon without forcing strokes/fills */
    .skill-php svg {
        width: 2rem;
        height: 2rem;
        display: inline-block;
        vertical-align: middle;
    }

    /* Harmonize skill icons and force equal square tiles inside #skills */
    .skills-grid .skill-tile {
        /* make tile a square using aspect-ratio and a fixed min size */
        aspect-ratio: 1 / 1;
        min-width: 3.5rem;
        /* ensures a reasonable tappable size on mobile */
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        box-sizing: border-box;
    }

    /* The actual grid cells (.skill-tile) will size according to their grid column width; to avoid variable heights due to surrounding content, ensure grid items don't stretch vertically */
    .skills-grid .grid>.skill-tile {
        height: auto;
    }

    .skills-grid svg {
        width: 1.9rem;
        height: 1.9rem;
        display: block;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Slightly larger tiles on desktop (bigger min-size and larger icons) */
    @media (min-width: 1024px) {
        .skills-grid .skill-tile {
            min-width: 5rem;
            /* larger tappable area on desktop */
        }

        .skills-grid svg {
            width: 2.4rem;
            height: 2.4rem;
        }
    }

    /* Restore outline (stroke) icons for social links.
       Apply stroke only on the <svg> element so children with explicit stroke="none" are respected. */
    .social-link svg {
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Color values: brand colors for linkedin/gitlab; black for contact/cv in light mode */
    .social-link--linkedin {
        color: #0A66C2 !important;
    }

    .social-link--gitlab {
        color: #FC6D26 !important;
    }

    .social-link--contact {
        color: #0b1b20;
        /* richer black for better legibility in light mode */
    }

    .social-link--cv {
        color: #0b1b20;
        /* richer black for better legibility in light mode */
    }

    /* In dark mode, keep contact/cv visible (white) */
    .dark .social-link--contact {
        color: #fff;
    }

    .dark .social-link--cv {
        color: #fff;
    }

    /* Explicitly set the SVG stroke color for contact and cv according to mode.
       Target the svg element (not children) and avoid !important so cascade works predictably. */
    .social-link--contact svg {
        stroke: #000;
    }

    /* Only apply stroke override to SVG child elements that already have a stroke attribute
       (keeps outer SVG wrapper and decorative borders untouched). This ensures only
       internal stroke paths become white in dark mode. */
    .dark .social-link--contact svg *[stroke],
    .dark a.social-link.social-link--cv svg *[stroke] {
        stroke: #fff !important;
    }

    .dark .social-link--cv svg {
        stroke: #fff;
    }

    /* More specific rules and prefers-color-scheme fallback to ensure Contact/CV svg strokes are readable
       without using !important. These target the svg element directly and increase specificity by
       qualifying with the social-link class. */
    a.social-link.social-link--cv svg,
    button.social-link.social-link--contact svg {
        stroke: #000;
    }

    @media (prefers-color-scheme: dark) {

        a.social-link.social-link--cv svg,
        button.social-link.social-link--contact svg {
            stroke: #fff;
        }
    }

    /* Stronger override: ensure contact & CV svgs (including inner paths) render white in dark mode.
       This covers cases where SVG children have inline fills/strokes. We keep hover backgrounds intact. */
    .dark .social-link--contact svg,
    .dark .social-link--contact svg *,
    .dark a.social-link.social-link--cv svg,
    .dark a.social-link.social-link--cv svg * {
        stroke: #fff !important;
        fill: none;
    }

    /* When hovering or focusing the Contact/CV buttons in dark mode, prefer a dark stroke
       for better legibility against the button background. This rule is intentionally
       specific and placed after the white-stroke block so it can override that declaration. */
    .dark a.social-link.social-link--cv:hover svg,
    .dark a.social-link.social-link--cv:hover svg *,
    .dark button.social-link.social-link--contact:hover svg,
    .dark button.social-link.social-link--contact:hover svg *,
    /* Use :focus-visible for keyboard focus indicators only so mouse clicks
       that focus the element do not permanently change the icon colour. */
    .dark a.social-link.social-link--cv:focus-visible svg,
    .dark a.social-link.social-link--cv:focus-visible svg *,
    .dark button.social-link.social-link--contact:focus-visible svg,
    .dark button.social-link.social-link--contact:focus-visible svg * {
        stroke: #000 !important;
        color: #000 !important;
    }

    /* Ensure certain skill icons use white stroke in dark mode for better contrast */
    .dark .skill-ionic svg,
    .dark .skill-java svg,
    .dark .skill-spring-boot svg,
    .dark .skill-jira svg {
        stroke: #000;

    }

    /* Postman: show dark variant in dark mode, light variant in light mode */
    .postman-dark {
        display: none;
    }

    .postman-light {
        display: inline-block;
    }

    @media (prefers-color-scheme: dark) {
        .postman-dark {
            display: inline-block;
        }

        .postman-light {
            display: none;
        }
    }
</style>
@endpush


<style>
    /* Contact modal — lighter surface in light mode, green-tinted but soft.
       Dark mode keeps the stronger green (defined later). */
    div[role="dialog"] {
        /* softer, lighter surface in light mode (soft green) */
        background-color: #eef7ef !important;
        /* use the page text color in light mode for consistency */
        color: var(--text) !important;
        border-radius: 0.5rem !important;
        border: none !important;
    }

    /* Inner content color overrides to ensure strong contrast on labels and help text */
    div[role="dialog"] .p-6,
    div[role="dialog"] .p-6 *,
    div[role="dialog"] label {
        color: var(--text) !important;
    }

    /* Inputs: lightly transparent on top of the tinted surface so they read as fields */
    div[role="dialog"] input,
    div[role="dialog"] textarea,
    div[role="dialog"] select {
        background-color: rgba(255, 255, 255, 0.88) !important;
        color: var(--text) !important;
        border: 1px solid rgba(11, 23, 26, 0.06) !important;
    }

    div[role="dialog"] input::placeholder,
    div[role="dialog"] textarea::placeholder {
        color: rgba(11, 23, 26, 0.45) !important;
    }

    /* Button styles scoped to the dialog */
    div[role="dialog"] .modal-cancel {
        background: transparent !important;
        /* use the normal text color so cancel reads like a neutral action */
        color: var(--text) !important;
        border: 1px solid color-mix(in srgb, var(--accent-secondary) 18%, rgba(11, 23, 26, 0.06)) !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0.375rem !important;
    }

    div[role="dialog"] .modal-send {
        /* keep a clear primary affordance: use accent-secondary with white text */
        background: var(--accent-secondary) !important;
        color: #fff !important;
        border: none !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 6px 18px rgba(42, 143, 183, 0.08) !important;
    }

    /* Hover states for affordance */
    div[role="dialog"] .modal-cancel:hover {
        background: color-mix(in srgb, var(--accent-secondary) 6%, transparent) !important;
    }

    div[role="dialog"] .modal-send:hover {
        filter: brightness(0.96) !important;
    }

    /* Dark mode: keep the stronger requested green tint for the dialog */
    .dark div[role="dialog"] {
        background-color: #40553f !important;
        color: #ffffff !important;
    }

    /* Very-specific selector to override any utility classes (bg-white, etc.)
       that may still apply to the dialog container in the DOM. Matches the
       element you provided precisely and forces the requested color. */
    body>div>main>div:nth-child(4)>div>div.fixed.inset-0.flex.items-center.justify-center.p-4>div[role="dialog"] {
        background-color: #cbdfca !important;
        color: #ffffff !important;
    }

    .dark body>div>main>div:nth-child(4)>div>div.fixed.inset-0.flex.items-center.justify-center.p-4>div[role="dialog"] {
        background-color: #425941ff !important;
    }

    .dark div[role="dialog"] input,
    .dark div[role="dialog"] textarea,
    .dark div[role="dialog"] select {
        background-color: rgba(255, 255, 255, 0.04) !important;
        color: var(--text) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
    }

    .dark div[role="dialog"] input::placeholder,
    .dark div[role="dialog"] textarea::placeholder {
        color: rgba(255, 255, 255, 0.68) !important;
    }

    .dark div[role="dialog"] .modal-cancel {
        color: color-mix(in srgb, var(--accent-secondary) 38%, #fff 62%) !important;
        border: 1px solid color-mix(in srgb, var(--accent-secondary) 18%, rgba(255, 255, 255, 0.06)) !important;
        background: transparent !important;
    }

    .dark div[role="dialog"] .modal-send {
        background: color-mix(in srgb, var(--accent-secondary) 62%, #0b1b20 38%) !important;
        color: #fff !important;
    }
</style>


<!-- Contact modal (Alpine) -->
<script>
    // Ensure contactModal factory exists before Alpine initialises.
    window.contactModal = function() {
        return {
            isOpen: false,
            lastActive: null,
            openModal() {
                this.lastActive = document.activeElement;
                this.isOpen = true;
                this.$nextTick(() => {
                    if (this.$refs && this.$refs.email) this.$refs.email.focus();
                });
                document.addEventListener('keydown', this._escHandler = (e) => {
                    if (e.key === 'Escape') this.closeModal();
                });
            },
            closeModal() {
                this.isOpen = false;
                document.removeEventListener('keydown', this._escHandler);
                this.$nextTick(() => {
                    if (this.lastActive && typeof this.lastActive.focus === 'function') this.lastActive.focus();
                });
            }
        }
    }
</script>

<div x-data="contactModal()" @open-contact.window="isOpen = true; $nextTick(() => { if ($refs.email) $refs.email.focus(); })" :aria-hidden="!isOpen">
    <template x-if="isOpen">
        <div>
            <div x-show="isOpen" x-cloak class="fixed inset-0 bg-black/50" aria-hidden="true" style="z-index: 9998;"></div>

            <div x-show="isOpen" x-cloak class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 9999;">
                <div @click.away="closeModal()" class="w-full max-w-lg bg-white dark:bg-[#111] rounded-lg shadow-lg overflow-hidden" role="dialog" aria-modal="true" aria-label="Contact form" style="z-index: 10000; position: relative;">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Contact</h3>
                            <button @click="closeModal()" class="text-gray-500 hover:text-gray-700" aria-label="Fermer">✕</button>
                        </div>

                        <form method="POST" action="{{ route('contact.send') }}" class="space-y-4">
                            @csrf
                            @if(session('status'))
                            <div class="p-3 rounded bg-green-50 text-green-800">{{ session('status') }}</div>
                            @endif
                            @if(session('error'))
                            <div class="p-3 rounded bg-red-50 text-red-800">{{ session('error') }}</div>
                            @endif
                            @if($errors->any())
                            <div class="p-3 rounded bg-red-50 text-red-800">
                                <ul class="list-disc pl-5">
                                    @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <!-- honeypot -->
                            <input type="text" name="hp" tabindex="-1" autocomplete="off" style="display:none">

                            <div>
                                <label class="block text-sm">Nom (optionnel)</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded border px-3 py-2" placeholder="Jean Dupont">
                            </div>

                            <div>
                                <label class="block text-sm">Votre email</label>
                                <input x-ref="email" type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded border px-3 py-2" placeholder="prenom@exemple.com" required>
                            </div>

                            <div>
                                <label class="block text-sm">Message</label>
                                <textarea x-ref="message" name="message" rows="4" class="mt-1 block w-full rounded border px-3 py-2" placeholder="Bonjour…" required>{{ old('message') }}</textarea>
                            </div>

                            <div class="flex justify-end items-center gap-3">
                                <button type="button" @click="closeModal()" class="mr-2 px-4 py-2 border rounded modal-cancel">Annuler</button>
                                <button type="submit" class="px-4 py-2 bg-[#1b1b18] text-white rounded modal-send">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<!-- Mobile sticky buttons removed; mobile behavior relies on desktop action buttons and the global open-contact event -->

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Contact modal factory is defined earlier (before the HTML) to avoid ReferenceError and name collisions.

        // highlight active section in left nav using IntersectionObserver
        const navLinks = document.querySelectorAll('aside nav a');
        const mainEl = document.querySelector('main#content') || document.querySelector('main');
        const sections = document.querySelectorAll('main#content > section[id]');

        // helper: remove active classes
        function clearActive() {
            navLinks.forEach(a => a.classList.remove('text-[#1b1b18]', 'font-semibold'));
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id;
                    clearActive();
                    const active = Array.from(navLinks).find(a => a.getAttribute('href') === '#' + id);
                    if (active) active.classList.add('text-[#1b1b18]', 'font-semibold');
                }
            });
        }, {
            root: null, // observe in viewport (page scroll)
            rootMargin: '0px',
            threshold: 0.6
        });

        sections.forEach(s => observer.observe(s));

        // smooth-scroll behavior for nav links: when main is the scroll container, scroll it; otherwise let the browser handle it
        navLinks.forEach(a => {
            a.addEventListener('click', (e) => {
                const href = a.getAttribute('href');
                if (!href || !href.startsWith('#')) return;
                const target = document.querySelector(href);
                if (!target) return;

                // With page scroll, rely on default behavior (smooth via CSS)
                // Keep e.preventDefault() if you want to customize offset.
            });
        });

        // Toggle footer 'stuck' class when main is scrolled to bottom
        const rightFooter = document.getElementById('right-footer');
        if (rightFooter) {
            function checkFooterStuckWindow() {
                const atBottom = Math.abs((window.innerHeight + window.scrollY) - document.body.scrollHeight) < 3;
                if (atBottom) rightFooter.classList.add('stuck');
                else rightFooter.classList.remove('stuck');
            }
            window.addEventListener('scroll', checkFooterStuckWindow, {
                passive: true
            });
            window.addEventListener('resize', checkFooterStuckWindow);
            checkFooterStuckWindow();
        }
    });
</script>
<script>
    (function() {
        const target = document.getElementById('contact-cv-actions');
        if (!target) return;

        let origRect = null;

        function recalc() {
            origRect = target.getBoundingClientRect();
            target.__origTop = origRect.top + window.scrollY;
        }

        function applyFixed() {
            if (target.classList.contains('fixed')) return;
            const w = target.offsetWidth;
            const h = target.offsetHeight;
            target.style.width = w + 'px';
            target.style.height = h + 'px';
            target.classList.add('fixed');
        }

        function removeFixed() {
            if (!target.classList.contains('fixed')) return;
            target.classList.remove('fixed');
            target.style.width = '';
            target.style.height = '';
        }

        function onScroll() {
            if (window.innerWidth > 640) {
                removeFixed();
                return;
            }
            if (origRect === null) recalc();
            if (window.scrollY > target.__origTop) applyFixed();
            else removeFixed();
        }

        window.addEventListener('resize', () => {
            recalc();
            onScroll();
        });
        window.addEventListener('orientationchange', () => {
            recalc();
            onScroll();
        });
        window.addEventListener('load', () => {
            recalc();
            onScroll();
        });
        window.addEventListener('scroll', onScroll, {
            passive: true
        });
        // initial
        setTimeout(() => {
            recalc();
            onScroll();
        }, 120);
    })();
</script>
<script>
    // Ensure footer reserved space on mobile when it becomes stuck
    (function() {
        const footer = document.getElementById('right-footer');
        const main = document.querySelector('main#content') || document.querySelector('main');
        if (!footer || !main) return;

        function applyFooterPadding() {
            // Only on small screens (mobile)
            if (window.innerWidth > 640) {
                main.style.paddingBottom = '';
                return;
            }
            if (footer.classList.contains('stuck')) {
                // set padding-bottom equal to footer's outer height
                const rect = footer.getBoundingClientRect();
                const height = Math.ceil(rect.height);
                main.style.paddingBottom = height + 'px';
            } else {
                main.style.paddingBottom = '';
            }
        }

        // Observe class attribute changes on the footer to detect 'stuck' toggles
        const observer = new MutationObserver(mutations => {
            for (const m of mutations) {
                if (m.type === 'attributes' && m.attributeName === 'class') {
                    applyFooterPadding();
                }
            }
        });

        observer.observe(footer, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Also run on resize/scroll/load to ensure initial state
        window.addEventListener('resize', applyFooterPadding, {
            passive: true
        });
        window.addEventListener('load', applyFooterPadding);
        window.addEventListener('orientationchange', applyFooterPadding);

        // initial check
        setTimeout(applyFooterPadding, 60);
    })();
</script>
<script>
    // Fallback global listener to ensure the contact modal opens even if Alpine scope issues occur
    window.addEventListener('open-contact', function _openContactFallback() {
        function tryOpen() {
            const el = document.querySelector('[x-data="contactModal()"]');
            if (!el) return false;
            // Alpine v3 attaches __x with $data
            try {
                if (el.__x && el.__x.$data) {
                    el.__x.$data.isOpen = true;
                    // Try to focus the email input inside the modal (best-effort)
                    setTimeout(() => {
                        try {
                            const email = el.querySelector('[x-ref="email"]');
                            if (email && typeof email.focus === 'function') email.focus();
                        } catch (e) {
                            // ignore
                        }
                    }, 40);
                    return true;
                }
            } catch (e) {
                // ignore
            }
            return false;
        }

        if (!tryOpen()) {
            // retry shortly in case Alpine hasn't initialised yet
            setTimeout(tryOpen, 50);
            setTimeout(tryOpen, 200);
        }
    });
</script>
<script>

</script>
@endpush