@extends('layouts.app')

@section('content')
<div class="lg:flex lg:justify-between lg:gap-4">
    <!-- LEFT: navigation / identity (centré verticalement sur grand écran) -->
    <!-- LEFT: like Brittany, sticky on large screens within page scroll -->
    <aside class="order-1 lg:order-1 lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-[48%] lg:flex-col lg:justify-between lg:py-24">
        <div class="w-full bg-transparent dark:bg-transparent rounded-lg p-8 lg:p-12 shadow flex flex-col justify-between">
            <!-- Name -->
            <div class="mb-8">
                <h1 class="text-3xl font-semibold">Jérémy Escassut</h1>
                <h2 class="text-xl font-semibold">Développeur Full Stack</h2>
                <p class="text-[#706f6c] mt-3">Bienvenue sur mon portfolio. Voici un aperçu de mon travail et de mon expérience.</p>
            </div>

            <!-- Section links (hidden on small screens, visible on desktop) -->
            <nav class="mt-8 mb-6 hidden lg:block">
                <ul class="space-y-3 text-sm">
                    <li><a href="#about" class="font-medium hover:underline">À propos</a></li>
                    <li><a href="#projects" class="font-medium hover:underline">Projets</a></li>
                    <li><a href="#experience" class="font-medium hover:underline">Expérience</a></li>
                    <li><a href="#skills" class="font-medium hover:underline">Compétences</a></li>
                </ul>
            </nav>

            <!-- Social icon buttons (Tabler icons, larger, no border) -->
            <div class="mt-6 flex gap-3 items-center text-sm text-[#706f6c] justify-end">
                <!-- LinkedIn (from resources/svg/icon-linkedin.svg) -->
                <a href="https://www.linkedin.com/in/jérémy-escassut-57773427" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn (ouvre dans un nouvel onglet)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#0A66C2]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M0 0h24v24H0z" stroke="none" fill="none" />
                        <path d="M8 11v5" />
                        <path d="M8 8v.01" />
                        <path d="M12 16v-5" />
                        <path d="M16 16v-3a2 2 0 1 0 -4 0" />
                        <path d="M3 7a4 4 0 0 1 4 -4h10a4 4 0 0 1 4 4v10a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4z" />
                    </svg>
                </a>

                <!-- GitLab (from resources/svg/icon-gitlab.svg) -->
                <a href="https://gitlab.com/JeremyEscassut" id="gitlab-link" target="_blank" rel="noopener noreferrer" aria-label="GitLab (ouvre dans un nouvel onglet)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#FC6D27]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M0 0h24v24H0z" stroke="none" fill="none" />
                        <path d="M21 14l-9 7l-9 -7l3 -11l3 7h6l3 -7z" />
                    </svg>
                </a>
            </div>

            <!-- Actions: icons -->
            <div id="contact-cv-actions" class="mt-6 flex items-center gap-3 justify-end">
                <!-- Contact (from resources/svg/icon-contact.svg) -->
                <button onclick="setTimeout(()=>window.dispatchEvent(new CustomEvent('open-contact')), 0)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center" aria-label="Ouvrir le contact" style="margin-top:2px;">
                    <svg class="w-6 h-6 text-[#1b1b18] dark:text-[#e6eef6]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M0 0h24v24H0z" stroke="none" fill="none" />
                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                        <path d="M3 7l9 6l9 -6" />
                    </svg>
                </button>

                <!-- CV (from resources/svg/icon-cv.svg) -->
                <a href="/cv/jeremy-escassut-v0.1.pdf" target="_blank" rel="noopener noreferrer" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center" aria-label="Voir le CV (ouvre dans un nouvel onglet)" style="margin-top:2px;">
                    <svg class="w-6 h-6 text-[#1b1b18] dark:text-[#e6eef6]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M0 0h24v24H0z" stroke="none" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <path d="M12 17v-6" />
                        <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
                    </svg>
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
                <p class="text-sm text-[#706f6c]">Court paragraphe de présentation. (Content de la section À propos — sans titre ici selon vos consignes.)</p>
                <p class="text-sm text-[#706f6c] mt-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae mi sed arcu pulvinar aliquet. Integer vel ligula sit amet lorem dapibus luctus. Sed a nibh non nibh feugiat ultricies. Curabitur non dui in nunc volutpat aliquet. Vivamus eget mi vel arcu lacinia aliquet.</p>
                <p class="text-sm text-[#706f6c] mt-2">Nullam ut magna et sapien gravida pretium. Aliquam erat volutpat. Maecenas sed nisl sit amet purus dictum vulputate. Cras at arcu at sapien efficitur feugiat.</p>
            </div>
        </section>

        <section id="projects" class="mb-8 scroll-mt-24" aria-label="Projets">
            <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                <h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">Projets</h2>
            </div>
            <div class="bg-[#dbdbd7] rounded-md p-6">
                <div class="flex flex-col gap-4">
                    <div class="w-full p-4 bg-white rounded flex items-start gap-4">
                        <img loading="lazy" width="96" height="96" src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=400&auto=format&fit=crop&crop=faces" alt="Project image" class="w-24 h-24 object-cover rounded flex-shrink-0">
                        <div class="flex-1 text-sm text-[#706f6c]">
                            <div class="text-base font-semibold">Projet 1</div>
                            <p class="mt-1 text-sm text-[#706f6c]">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Description courte du projet (placeholder).</p>
                        </div>
                    </div>

                    <div class="w-full p-4 bg-white rounded flex items-start gap-4">
                        <img loading="lazy" width="96" height="96" src="https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?q=80&w=400&auto=format&fit=crop&crop=faces" alt="Project image 2" class="w-24 h-24 object-cover rounded flex-shrink-0">
                        <div class="flex-1 text-sm text-[#706f6c]">
                            <div class="text-base font-semibold">Projet 2</div>
                            <p class="mt-1 text-sm text-[#706f6c]">Lorem ipsum dolor sit amet. Brève description du projet en quelques mots (placeholder).</p>
                        </div>
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
                        <div class="text-sm text-[#706f6c] font-medium">2024–PRESENT</div>
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight">Senior Developer - Entreprise A</h2>
                            <h3 class="text-sm font-medium text-[#5f615f] mt-1">Autres postes occupés: Developer, Junior</h3>
                            <p class="text-sm text-[#706f6c] mt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ac lacus sed arcu fermentum posuere.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="text-sm text-[#706f6c] font-medium">2021–2024</div>
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight">Developer - Entreprise B</h2>
                            <p class="text-sm text-[#706f6c] mt-2">Descriptif en Lorem: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent et.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="skills" class="mb-8 scroll-mt-24" aria-label="Compétences">
            <div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
                <h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">Compétences</h2>
            </div>
            <div class="bg-[#dbf7e6] rounded-md p-6">
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="p-3 bg-white rounded flex items-center skill-php">
                        {{-- Inline PHP icon (local SVG) - no global CSS changes to avoid stroke/fill side-effects --}}
                        @php $phpSvg = '';
                        try { $phpSvg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-php.svg')); } catch (
                        Exception $e) { }
                        @endphp
                        @if($phpSvg)
                        {!! $phpSvg !!}
                        @endif
                    </div>
                    <div class="p-3 bg-white rounded">Tailwind CSS</div>
                    <div class="p-3 bg-white rounded">Alpine.js</div>
                    <div class="p-3 bg-white rounded">Git / CI</div>
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
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 -6px 18px rgba(0, 0, 0, 0.06);
        z-index: 40;
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
        width: 1.8rem;
        height: 1.8rem;
        display: inline-block;
        vertical-align: middle;
    }
</style>
@endpush

<!-- Contact modal (Alpine) -->
<script>
    // Ensure contactModal factory exists before Alpine initialises. Use `isOpen` to avoid collision with window.open.
    window.contactModal = function() {
        return {
            isOpen: false,
            lastActive: null,
            openModal() {
                console.log('contactModal.openModal called');
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
                console.log('contactModal.closeModal called');
                this.isOpen = false;
                document.removeEventListener('keydown', this._escHandler);
                this.$nextTick(() => {
                    if (this.lastActive && typeof this.lastActive.focus === 'function') this.lastActive.focus();
                });
            },
            onSubmit(e) {
                console.log('contactModal.onSubmit called');
                e.preventDefault();
                this.closeModal();
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
                                <button type="button" @click="closeModal()" class="mr-2 px-4 py-2 border rounded">Annuler</button>
                                <button type="submit" class="px-4 py-2 bg-[#1b1b18] text-white rounded">Envoyer</button>
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
        console.log('Alpine initialized in home.blade.php');
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
    // Fallback global listener to ensure the contact modal opens even if Alpine scope issues occur
    window.addEventListener('open-contact', function _openContactFallback() {
        console.log('open-contact event received (fallback listener)');

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
    // Delegated click logger: logs when a click reaches document and matches the contact button
    (function() {
        try {
            if (!window.__contactClickLoggerBound) {
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest && e.target.closest('button[aria-label="Ouvrir le contact"]');
                    if (btn) {
                        console.log('Delegated click detected on contact button', btn);
                    }
                }, {
                    capture: true
                });
                window.__contactClickLoggerBound = true;
            }
        } catch (e) {
            // ignore
        }
    })();
</script>
@endpush