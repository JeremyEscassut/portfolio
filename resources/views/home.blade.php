@extends('layouts.app')

@section('content')
<div class="lg:flex lg:justify-between lg:gap-4">
    <!-- LEFT: navigation / identity (centré verticalement sur grand écran) -->
    <!-- LEFT: like Brittany, sticky on large screens within page scroll -->
    <aside id="home-aside" class="order-1 lg:order-1 lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-[48%] lg:flex-col lg:justify-between lg:py-24">
        <div id="home" x-data="{ theme: localStorage.getItem('theme') || 'light', hoverCV: false, init(){ if(this.theme==='dark'){ document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') } }, set(t){ this.theme=t; if(t==='dark'){ localStorage.setItem('theme','dark'); document.documentElement.classList.add('dark') } else { localStorage.setItem('theme','light'); document.documentElement.classList.remove('dark') } }, cycle(){ const order=['light','dark']; let i=order.indexOf(this.theme); this.set(order[(i+1)%order.length]); } }" x-init="init()" class="w-full bg-transparent dark:bg-transparent rounded-lg p-8 lg:p-12 shadow flex flex-col justify-between">
            <!-- Name -->
            <div id="home-title" class="mb-8">
                <!-- Single-cycle theme icon (click to cycle Auto → Clair → Sombre) -->
                <div id="home-icons" class="mt-3 flex justify-end"></div>

                <h1 class="text-3xl ml-6 font-semibold">Jérémy Escassut</h1>
                <h2 class="text-xl ml-6 font-semibold">Développeur Full Stack</h2>
                <p class="text-[#706f6c] mt-3">Bienvenue sur mon portfolio. Voici un aperçu de mon travail et de mon expérience.</p>
            </div>

            <div id="home-interactions" class="flex gap-4 mt-4 items-start">
                <!-- LEFT column: photo (fixed width on mobile, centered on lg) -->
                <div id="home-left-col" class="left-col flex-1 min-w-0 flex items-center justify-center">
                    <div id="home-photo" class="w-full min-w-0">
                        @php
                        $origData = '';
                        $aiData = '';
                        try {
                        $origData = base64_encode(file_get_contents(resource_path('je_profile.jpeg')));
                        } catch (Exception $e) { }
                        try {
                        $aiData = base64_encode(file_get_contents(resource_path('je_ia_profile.jpg')));
                        } catch (Exception $e) { }
                        @endphp

                        <div id="profilePhotoContainer" class="relative cursor-pointer group w-full lg:mx-auto lg:w-full max-w-full text-center flex flex-col items-center" role="button" tabindex="0"
                            data-original="{{ $origData ? 'data:image/jpeg;base64,' . $origData : asset('images/je_profile.jpeg') }}"
                            data-ai="{{ $aiData ? 'data:image/jpeg;base64,' . $aiData : asset('images/je_ia_profile.jpg') }}">
                            <div id="photoCanvasWrapper" style="max-width:400px; display:flex; align-items:center; justify-content:center;">
                                <canvas id="photoCanvas" width="400" height="400" class="rounded-lg overflow-hidden border-2 border-[#e3e3e0] shadow-[0_6px_0_rgba(0,0,0,0.08)] bg-white dark:bg-[#161615]"
                                    style="display:block; width:100%; height:auto; max-width:400px;"></canvas>
                            </div>
                            <p id="photoCaption" class="mt-3 text-sm text-center text-[#706f6c] dark:text-[#A1A09A]">Original<br>Cliquez si vous aimez Arcane</p>
                        </div>
                    </div>
                </div>

                <!-- RIGHT column: stacked list, social icons, contact/CV (fills remaining space on mobile) -->
                <div id="home-right-col" class="right-col flex-none w-auto max-w-[140px] sm:max-w-[180px] flex flex-col gap-4">
                    <nav class="mt-0 mb-0 hidden lg:block">
                        <ul id="home-sections-list" class="space-y-3 text-sm lg:text-right">
                            <li><a href="#about" class="font-medium hover:underline">À propos</a></li>
                            <li><a href="#projects" class="font-medium hover:underline">Projets</a></li>
                            <li><a href="#experience" class="font-medium hover:underline">Expérience</a></li>
                            <li><a href="#skills" class="font-medium hover:underline">Compétences</a></li>
                        </ul>
                    </nav>

                    <div id="home-social-icons" class="flex gap-3 items-center text-sm text-[#706f6c] justify-end">
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

                    <div id="home-contact-cv-icons" class="contact-cv-actions mt-0 flex items-center gap-3 justify-end">
                        <!-- Contact (inline from resources/svg/icon-contact.svg) -->
                        <button onclick="setTimeout(()=>window.dispatchEvent(new CustomEvent('open-contact')), 0)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--contact" aria-label="Ouvrir le contact" style="margin-top:2px;">
                            @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-contact.svg')); } catch (Exception $e) { } @endphp
                            @if($svg) {!! $svg !!} @endif
                        </button>

                        <!-- CV (PNG light/dark variants placed in resources/png) -->
                        <a href="{{ asset('cv/CV_Dev-FS_2025-10-24_Jeremy_Escassut.pdf') }}" target="_blank" rel="noopener noreferrer"
                            @mouseenter="hoverCV = true"
                            @mouseleave="hoverCV = false"
                            @click="hoverCV = false"
                            @focus="hoverCV = true"
                            @blur="hoverCV = false"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--cv"
                            aria-label="Voir le CV (ouvre dans un nouvel onglet)" style="margin-top:2px;">
                            {{-- icône pour mode clair (noir sur fond clair) --}}
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'cv-black-100.png'))) }}" alt="Icône CV" class="w-8 h-8" aria-hidden="true" x-show="theme==='light'" x-cloak>
                            {{-- icône pour mode sombre par défaut (blanc sur fond sombre) --}}
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'cv-white-100.png'))) }}" alt="Icône CV" class="w-8 h-8" aria-hidden="true" x-show="theme==='dark' && !hoverCV" x-cloak>
                            {{-- icône pour mode sombre lors du hover (utilise la variante noire fournie) --}}
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'cv-black-100.png'))) }}" alt="Icône CV" class="w-8 h-8" aria-hidden="true" x-show="theme==='dark' && hoverCV" x-cloak>
                        </a>
                    </div>

                    <!-- Theme cycle button placed under contact/CV actions -->
                    <div id="theme-toggle-wrapper" class="mt-2 flex justify-end items-center gap-2">
                        <button id="themeToggleButton" type="button" @click="cycle()" :title="(theme==='light' ? 'Mode clair' : 'Mode sombre') + ' — cliquez pour changer'" class="p-3 rounded theme-lang-button transform -translate-x-2 hover:bg-gray-100 dark:hover:bg-neutral-800" aria-label="Sélecteur de thème">
                            <template x-if="theme==='auto'">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.5"></rect>
                                    <path d="M8 20h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </template>
                            <template x-if="theme==='light'">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5"></circle>
                                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"></path>
                                </svg>
                            </template>
                            <template x-if="theme==='dark'">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </template>
                        </button>

                        <!-- Language toggle button: small globe with FR/EN badge -->
                        <button id="langToggleButton" type="button" class="p-3 rounded theme-lang-button hover:bg-gray-100 dark:hover:bg-neutral-800" aria-label="Changer la langue" title="Changer la langue">
                            {{-- globe icon : standard globe with meridians/latitudes --}}
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.2" fill="none" />

                                <!-- Verticals: center straight + two curved meridians -->
                                <path d="M12 3v18" stroke="currentColor" stroke-width="1" stroke-linecap="round" />
                                <path d="M9 3 C7.5 8 7.5 16 9 21" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" />
                                <path d="M15 3 C16.5 8 16.5 16 15 21" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" />

                                <!-- Horizontals: center straight + two curved parallels -->
                                <path d="M3 12h18" stroke="currentColor" stroke-width="1" stroke-linecap="round" />
                                <path d="M4.5 7.8 C8 9.2 16 9.2 19.5 7.8" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" />
                                <path d="M4.5 16.2 C8 14.8 16 14.8 19.5 16.2" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" />
                            </svg>
                            <span class="lang-badge text-xs px-1 rounded ml-1" aria-hidden="true">FR</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- RIGHT: page scroll handles content; main not an independent scroller -->
    <main id="content" class="order-2 lg:order-2 pt-24 lg:py-24 lg:w-[52%]">

        {{-- Global flash messages (visible outside the modal) --}}
        <div x-data="{ show: true }" x-cloak class="mx-6 mb-4">
            @if(session('status'))
            <div x-show="show" role="status" class="flex items-start justify-between gap-4 p-4 rounded-lg border border-green-200 bg-green-50 text-green-800 dark:bg-green-900 dark:border-green-800 dark:text-green-100 shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-600 dark:text-green-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm">{!! session('status') !!}</div>
                </div>
                <button @click="show=false" class="text-sm text-green-700 dark:text-green-200 opacity-80 hover:opacity-100" aria-label="Fermer le message">✕</button>
            </div>
            @endif

            @if(session('error'))
            <div x-show="show" role="alert" class="flex items-start justify-between gap-4 p-4 rounded-lg border border-red-200 bg-red-50 text-red-800 dark:bg-red-900 dark:border-red-800 dark:text-red-100 shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-600 dark:text-red-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm-1 8a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm">{!! session('error') !!}</div>
                </div>
                <button @click="show=false" class="text-sm text-red-700 dark:text-red-200 opacity-80 hover:opacity-100" aria-label="Fermer le message">✕</button>
            </div>
            @endif

            @if($errors->any())
            <div x-show="show" role="alert" class="p-4 rounded-lg border border-red-200 bg-red-50 text-red-800 dark:bg-red-900 dark:border-red-800 dark:text-red-100 shadow-sm">
                <div class="flex items-start justify-between">
                    <div class="text-sm">Veuillez corriger les erreurs suivantes :</div>
                    <button @click="show=false" class="text-sm text-red-700 dark:text-red-200 opacity-80 hover:opacity-100" aria-label="Fermer le message">✕</button>
                </div>
                <ul class="mt-2 list-disc pl-5 text-sm">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>



        <section id="about" class="mb-8 scroll-mt-24" aria-label="À propos">
            <div id="section-banner" class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
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
            <div id="skills-icons" class="p-0 skills-grid">
                <div class="space-y-4 text-sm">
                    <!-- Front group -->
                    <div>
                        <h3 class="text-sm font-semibold mb-2">Front</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-6 gap-2">
                            <div class="bg-white rounded flex items-center justify-center skill-angular skill-tile" aria-label="Angular" data-name="Angular">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-angular.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-html5 skill-tile" aria-label="HTML5" data-name="HTML5">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-html5.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-ionic skill-tile" aria-label="Ionic" data-name="Ionic">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-ionic.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                        </div>
                    </div>

                    <!-- Back group -->
                    <div>
                        <h3 class="text-sm font-semibold mb-2">Back</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-6 gap-2">
                            <div class="bg-white rounded flex items-center justify-center skill-laravel skill-tile" aria-label="Laravel" data-name="Laravel">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-laravel.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-nodejs skill-tile" aria-label="Node.js" data-name="Node.js">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-nodejs.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-python skill-tile" aria-label="Python" data-name="Python">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-python.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-django skill-tile" aria-label="Django" data-name="Django">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-django.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-java skill-tile" aria-label="Java" data-name="Java">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-java.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-spring-boot skill-tile" aria-label="Spring Boot" data-name="Spring Boot">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-spring-boot.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-symfony skill-tile" aria-label="Symfony" data-name="Symfony">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-symfony.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                        </div>
                    </div>

                    <!-- Others group -->
                    <div id="other">
                        <h3 class="text-sm font-semibold mb-2">Autres</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-6 gap-2">
                            <div class="bg-white rounded flex items-center justify-center skill-jira skill-tile" aria-label="Jira" data-name="Jira">
                                @php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-jira.svg')); } catch (Exception $e) { } @endphp
                                @if($svg) {!! $svg !!} @endif
                            </div>
                            <div class="bg-white rounded flex items-center justify-center skill-postman skill-tile" aria-label="Postman" data-name="Postman">
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
                            <div class="bg-white rounded flex items-center justify-center skill-php skill-tile" aria-label="PHP" data-name="PHP">
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
        <footer id="footer" class="right-footer mt-6 p-6 text-sm text-[#706f6c] bg-transparent">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2">
                <div class="text-center lg:text-left">
                    &copy; {{ date('Y') }} Jérémy Escassut<br><a href="{{ url('/mentions') }}" class="underline" aria-label="Mentions légales">Mentions légales</a>
                </div>
                <div class="text-center lg:text-right text-xs text-[#8b8a88]">
                    Conçu avec Laravel, Tailwind et Alpine.
                </div>
            </div>
        </footer>

    </main>
</div>

@push('styles')
<!-- Hover lift styles moved to `resources/js/app.js` and injected at runtime -->
@endpush


<!-- Modal styles moved to resources/css/app.css -->


<!-- Contact modal behavior moved to resources/js/app.js -->

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
                            {{-- flash messages moved to top-level so they are visible outside the modal after redirect --}}
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
<!-- Page scripts moved to resources/js/app.js -->
@endpush