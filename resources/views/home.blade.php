{{--
	Home page (the whole site is this one page).

	Structure
	  <aside #home-aside>   identity (name, role, pitch), photo, and #home-right-col:
	                        section nav (desktop) + one group of icons (LinkedIn, GitLab,
	                        theme, language, contact, CV). Sticky on desktop.
	  <main #content>       sections: about, projects, experience, studies,
	                        recommendations, stack, then the footer.
	  #section-fab          mobile/tablet "jump to section" fan
	  contactModal()        contact form (Alpine), opened by the 'open-contact' event
	  #cv-selector-modal    choice of CV, opened through the URL hash

	Where the behaviour lives
	  resources/js/app.js          modals, section highlight, pinned contact/CV icons
	  resources/js/photoToggle.js  photo <-> AI version swap
	  resources/js/sectionFab.js   the mobile section fan
	  resources/css/app.css        styles (see the "UX quick wins" block at the end)

	Texts: every visible string comes from resources/lang/{fr,en}.json via __().
	Alpine attributes are written data-x-* (valid HTML) and copied to x-* by app.js.
--}}
@extends('layouts.app')

@section('content')
@php
// Inline SVG icons (small files) — returns '' when the file is missing.
$icon = function (string $name): string {
	$path = resource_path('svg' . DIRECTORY_SEPARATOR . $name . '.svg');
	return is_file($path) ? (string) file_get_contents($path) : '';
};
@endphp
<div class="lg:flex lg:justify-between lg:gap-4">
	<!-- LEFT: identity, actions and section navigation (sticky on large screens) -->
	<aside id="home-aside" class="order-1 lg:order-1 lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-[48%] lg:flex-col lg:justify-between lg:py-12">
		<div id="home" data-x-data="{ theme: localStorage.getItem('theme') || 'light', hoverCV: false, init(){ if(this.theme==='dark'){ document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') } }, set(t){ this.theme=t; if(t==='dark'){ localStorage.setItem('theme','dark'); document.documentElement.classList.add('dark') } else { localStorage.setItem('theme','light'); document.documentElement.classList.remove('dark') } }, cycle(){ const order=['light','dark']; let i=order.indexOf(this.theme); this.set(order[(i+1)%order.length]); } }"
			data-x-init="init()"
			class="w-full bg-transparent dark:bg-transparent rounded-lg px-6 pt-6 pb-2 sm:px-8 lg:p-12 flex flex-col justify-between">

			<div id="home-title">
				<h1 class="home-name">{{ __('home.hero.name') }}</h1>
				<p class="home-role">{{ __('home.job_title') }}</p>
				<p class="home-pitch">{{ __('home.pitch') }}</p>
			</div>

			<div id="home-interactions" class="flex gap-4 mt-8 items-start lg:gap-6 lg:mt-0">
				<!-- Photo: click to switch between the original and the AI-generated version -->
				<div id="home-left-col" class="left-col flex-1 min-w-0 flex items-center justify-center">
					<div id="home-photo" class="w-full min-w-0">
						<div id="profilePhotoContainer" class="relative cursor-pointer group w-full lg:mx-auto max-w-full text-center flex flex-col items-center" role="button" tabindex="0" aria-pressed="false"
							aria-label="{{ __('photo.caption') }}"
							data-original="{{ asset('images/profile.jpg') }}"
							data-ai="{{ asset('images/profile-ia.jpg') }}"
							data-caption-original="{{ __('photo.caption') }}"
							data-caption-ai="{{ __('photo.caption_ai') }}">
							<div id="photoCanvasWrapper" class="photo-frame" style="max-width:430px; display:flex; align-items:center; justify-content:center;">
								<!-- Two equal gaps (30 units), each starting 18 units from the lower-right corner -->
								<svg class="photo-frame__wireframe" viewBox="0 0 430 430" aria-hidden="true" focusable="false">
									<path d="M 51 322 V 108 L 215 13 L 379 108 V 274" />
									<path class="photo-frame__notch" d="M 379 304 V 322 L 363.42 331.02" />
									<path d="M 337.46 346.06 L 215 417 L 51 322" />
								</svg>
								<canvas id="photoCanvas" width="400" height="400"
									style="display:block; width:100%; height:auto; max-width:400px;"></canvas>
							</div>
							<p id="photoCaption" class="photo-caption" aria-live="polite">{{ __('photo.caption') }}</p>
						</div>
					</div>
				</div>

				<!-- Section navigation (desktop) + every clickable icon in one place -->
				<div id="home-right-col" class="right-col flex-none w-auto max-w-[140px] sm:max-w-[180px] flex flex-col gap-4 lg:gap-7">
					<nav class="mt-0 mb-0 hidden lg:block lg:mb-4" aria-label="{{ __('nav.sections') }}">
						<ul id="home-sections-list" class="space-y-3 text-sm lg:text-right">
							<li><a href="#about" class="font-medium hover:underline">{{ __('nav.about') }}</a></li>
							<li><a href="#projects" class="font-medium hover:underline">{{ __('nav.projects') }}</a></li>
							<li><a href="#experience" class="font-medium hover:underline">{{ __('nav.experience') }}</a></li>
							<li><a href="#studies" class="font-medium hover:underline">{{ __('nav.studies') }}</a></li>
							<li><a href="#recommendations" class="font-medium hover:underline">{{ __('nav.recommendations') }}</a></li>
							<li><a href="#stack" class="font-medium hover:underline">{{ __('nav.stack') }}</a></li>
						</ul>
					</nav>

					<div id="home-social-icons" class="flex gap-3 items-center text-sm text-[#706f6c] justify-end">
						<a href="https://www.linkedin.com/in/jérémy-escassut-57773427" target="_blank" rel="noopener noreferrer" aria-label="{{ __('social.linkedin') }}" title="LinkedIn" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--linkedin">
							{!! $icon('icon-linkedin') !!}
						</a>
						<a href="https://gitlab.com/JeremyEscassut" id="gitlab-link" target="_blank" rel="noopener noreferrer" aria-label="{{ __('social.gitlab') }}" title="GitLab" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--gitlab">
							{!! $icon('icon-gitlab') !!}
						</a>
					</div>

					<div id="theme-toggle-wrapper" class="mt-1 flex justify-end items-center gap-2">
						<button id="themeToggleButton" type="button" @click="cycle()" title="{{ __('ui.theme.selector') }}" class="p-3 rounded theme-lang-button mobile-offset lg:transform lg:-translate-x-2 hover:bg-gray-100 dark:hover:bg-neutral-800" aria-label="{{ __('ui.theme.selector') }}">
							<template x-if="theme==='light'">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
									<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								</svg>
							</template>
							<template x-if="theme==='dark'">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
									<circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5"></circle>
									<path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"></path>
								</svg>
							</template>
						</button>

						<!-- Language toggle: shows the target locale (inverse of current) -->
						<button id="langToggleButton" type="button" data-locale="{{ app()->getLocale() }}" class="p-3 rounded theme-lang-button mobile-offset hover:bg-gray-100 dark:hover:bg-neutral-800" aria-label="{{ __('ui.lang.change') }}" title="{{ __('ui.lang.change') }}">
							<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.2" fill="none" />
								<path d="M12 3v18" stroke="currentColor" stroke-width="1" stroke-linecap="round" />
								<path d="M9 3 C7.5 8 7.5 16 9 21" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" />
								<path d="M15 3 C16.5 8 16.5 16 15 21" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" />
								<path d="M3 12h18" stroke="currentColor" stroke-width="1" stroke-linecap="round" />
								<path d="M4.5 7.8 C8 9.2 16 9.2 19.5 7.8" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" />
								<path d="M4.5 16.2 C8 14.8 16 14.8 19.5 16.2" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" />
							</svg>
							<span class="lang-badge text-xs px-1 rounded ml-1" aria-hidden="true">{{ strtoupper((app()->getLocale() ?? 'fr') === 'fr' ? 'EN' : 'FR') }}</span>
						</button>
					</div>

					<!-- Contact / CV: pinned to the top of the screen on mobile once scrolled past (resources/js/app.js) -->
					<div id="home-contact-cv-icons" class="contact-cv-actions mt-0 flex items-center gap-3 justify-end">
						<button type="button" onclick="setTimeout(()=>window.dispatchEvent(new CustomEvent('open-contact')), 0)" title="{{ __('home.cta.contact') }}" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--contact" aria-label="{{ __('home.cta.contact') }}" style="margin-top:2px;">
							{!! $icon('icon-contact') !!}
						</button>

						<a href="#cv-selector-modal"
							data-cv-open="true"
							data-x-on-mouseenter="hoverCV = true"
							data-x-on-mouseleave="hoverCV = false"
							data-x-on-focus="hoverCV = true"
							data-x-on-blur="hoverCV = false"
							onclick="window.openCvSelectorModal(); return false;"
							title="{{ __('home.cta.cv') }}"
							class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--cv"
							aria-label="{{ __('home.cta.cv') }}" style="margin-top:2px;">
							{{-- light mode: black icon; dark mode: white icon (the hover hexagon stays dark) --}}
							<img src="{{ asset('images/icons/cv-black.png') }}" alt="" class="w-8 h-8" aria-hidden="true" data-x-show="theme==='light'" data-x-cloak>
							<img src="{{ asset('images/icons/cv-white.png') }}" alt="" class="w-8 h-8" aria-hidden="true" data-x-show="theme==='dark'" data-x-cloak>
						</a>
					</div>
				</div>
			</div>
		</div>
	</aside>

	<!-- RIGHT: page scroll handles content; main not an independent scroller -->
	<main id="content" class="order-2 lg:order-2 pt-6 lg:py-24 lg:w-[52%]">

		<section id="about" class="home-section home-section--first scroll-mt-24" aria-labelledby="about-title">
			<div class="section-banner sticky top-0 z-20 -mx-6 mb-2 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only">
				<h2 id="about-title" class="section-title">{{ __('home.section.about') }}</h2>
			</div>
			<div class="section-body">
				<p>{{ __('about.p1') }}</p>
				<p>{{ __('about.p2') }}</p>
				<p>{{ __('about.p3') }}</p>
				<p>{{ __('about.p4') }}</p>
			</div>
		</section>

		<section id="projects" class="home-section scroll-mt-24" aria-labelledby="projects-title">
			<div class="section-banner sticky top-0 z-20 -mx-6 mb-2 w-screen bg-transparent px-6 py-3 backdrop-blur">
				<h2 id="projects-title" class="section-title">{{ __('home.section.projects') }}</h2>
			</div>
			@php
			$crmSlides = [
				['src' => asset('images/projects/admiral-detail-attendu.webp'), 'alt' => __('projects.crm.carousel.slide1.alt')],
				['src' => asset('images/projects/admiral-tdb-admin.webp'), 'alt' => __('projects.crm.carousel.slide2.alt')],
				['src' => asset('images/projects/admiral-tdb-client.webp'), 'alt' => __('projects.crm.carousel.slide3.alt')],
			];
			@endphp
			<div class="section-body projects-list">

				<article class="project">
					<h3 class="project__title">{{ __('projects.crm.title') }}</h3>
					<p>{{ __('projects.crm.description') }}</p>
					<p class="project__role"><strong>{{ __('projects.role.label') }}</strong> {{ __('projects.crm.role') }}</p>
					<div class="project-tags">
						<span class="project-tag">React</span>
						<span class="project-tag">Spring Boot</span>
						<span class="project-tag">TypeScript</span>
						<span class="project-tag">PostgreSQL</span>
						<span class="project-tag">API REST</span>
					</div>
					<div class="project__media" x-data="{ videoOpen: false, slides: {{ json_encode($crmSlides) }}, currentIndex: 0, startX: null, dragDelta: 0, goToNext() { this.currentIndex = (this.currentIndex + 1) % this.slides.length; }, goToPrev() { this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length; }, handleTouchStart(event) { this.startX = event.touches[0].clientX; this.dragDelta = 0; }, handleTouchMove(event) { if (this.startX === null) return; this.dragDelta = this.startX - event.touches[0].clientX; }, handleTouchEnd() { if (this.dragDelta > 50) { this.goToNext(); } else if (this.dragDelta < -50) { this.goToPrev(); } this.startX = null; this.dragDelta = 0; }, handlePointerDown(event) { this.startX = event.clientX; this.dragDelta = 0; }, handlePointerMove(event) { if (this.startX === null) return; this.dragDelta = this.startX - event.clientX; }, handlePointerUp() { if (this.dragDelta > 50) { this.goToNext(); } else if (this.dragDelta < -50) { this.goToPrev(); } this.startX = null; this.dragDelta = 0; }, closeVideo() { this.videoOpen = false; if (this.$refs.demo) this.$refs.demo.pause(); } }" @keydown.escape.window="closeVideo()">
						<div class="carousel relative w-full overflow-hidden" role="group" aria-roledescription="carousel" aria-label="{{ __('projects.crm.screenshots') }}" @touchstart="handleTouchStart($event)" @touchmove="handleTouchMove($event)" @touchend="handleTouchEnd()" @pointerdown="handlePointerDown($event)" @pointermove="handlePointerMove($event)" @pointerup="handlePointerUp()" @pointerleave="handlePointerUp()">
							<template x-for="(slide, index) in slides" :key="index">
								<div x-show="index === currentIndex" class="w-full">
									<img loading="lazy" decoding="async" width="1600" height="824" :src="slide.src" :alt="slide.alt" class="h-auto w-full object-contain">
								</div>
							</template>
							<div class="carousel__dots">
								<template x-for="(slide, index) in slides" :key="index">
									<button type="button" class="carousel__dot" :class="index === currentIndex ? 'is-active' : ''" @click="currentIndex = index" :aria-label="@js(__('projects.crm.screenshots')) + ' ' + (index + 1)" :aria-current="index === currentIndex ? 'true' : 'false'"></button>
								</template>
							</div>
							<button type="button" class="carousel__nav carousel__nav--prev" @click="goToPrev()" aria-label="{{ __('projects.crm.carousel.previous') }}">←</button>
							<button type="button" class="carousel__nav carousel__nav--next" @click="goToNext()" aria-label="{{ __('projects.crm.carousel.next') }}">→</button>
						</div>
						<div class="project__links">
							<button type="button" @click="videoOpen = true" class="pf-btn pf-btn--secondary">
								{{ __('projects.crm.video.launch') }}
							</button>
						</div>
						<div x-show="videoOpen" x-cloak class="fixed inset-0 z-[10000] flex items-center justify-center bg-black/70 p-4" @click.self="closeVideo()">
							<div class="video-dialog w-full max-w-4xl rounded-lg p-3 shadow-xl" role="dialog" aria-modal="true" aria-label="{{ __('projects.crm.video.title') }}">
								<div class="mb-2 flex items-center justify-between">
									<p class="video-dialog__title">{{ __('projects.crm.video.title') }}</p>
									<button type="button" @click="closeVideo()" class="video-dialog__close" aria-label="{{ __('projects.crm.video.close') }}">✕</button>
								</div>
								<video x-ref="demo" controls playsinline preload="none" poster="{{ asset('images/projects/demo-admiral-poster.webp') }}" class="w-full rounded-lg">
									<source src="{{ asset('mov/demo-admiral.mp4') }}" type="video/mp4">
									{{ __('projects.crm.video.unsupported') }}
								</video>
							</div>
						</div>
					</div>
				</article>

				<article class="project">
					<h3 class="project__title">{{ __('projects.pwa.title') }}</h3>
					<p>{{ __('projects.pwa.description') }}</p>
					<p class="project__role"><strong>{{ __('projects.role.label') }}</strong> {{ __('projects.pwa.role') }}</p>
					<div class="project-tags">
						<span class="project-tag">Lovable</span>
						<span class="project-tag">React</span>
						<span class="project-tag">TypeScript</span>
						<span class="project-tag">Postgres</span>
					</div>
					<div class="project__links">
						<a href="https://a2balles.com" target="_blank" rel="noopener noreferrer" class="pf-btn pf-btn--secondary project-link project-link--a2balles">
							{!! $icon('icon-a2balles') !!}
							<span>{{ __('projects.link.site') }}</span>
						</a>
					</div>
				</article>

				<article class="project">
					<h3 class="project__title">{{ __('projects.livret.title') }}</h3>
					<p>{{ __('projects.livret.description') }}</p>
					<p class="project__role"><strong>{{ __('projects.role.label') }}</strong> {{ __('projects.livret.role') }}</p>
					<div class="project-tags">
						<span class="project-tag">PHP</span>
						<span class="project-tag">Symfony</span>
						<span class="project-tag">EasyAdmin</span>
						<span class="project-tag">PHPUnit</span>
					</div>
					<img loading="lazy" decoding="async" width="1600" height="878" src="{{ asset('images/projects/livret-cda.webp') }}" alt="{{ __('projects.preview.livret') }}" class="project__image">
					<div class="project__links">
						<a href="https://gitlab.com/JeremyEscassut/livret-cda" target="_blank" rel="noopener noreferrer" class="pf-btn pf-btn--secondary project-link">
							{!! $icon('icon-gitlab-repo-link') !!}
							<span>{{ __('projects.link.code') }}</span>
						</a>
					</div>
				</article>

				<article class="project">
					<h3 class="project__title">{{ __('projects.breakingcat.title') }}</h3>
					<p>{{ __('projects.breakingcat.description') }}</p>
					<p class="project__role"><strong>{{ __('projects.role.label') }}</strong> {{ __('projects.breakingcat.role') }}</p>
					<div class="project-tags">
						<span class="project-tag">PHP</span>
						<span class="project-tag">MySQL</span>
						<span class="project-tag">PDO</span>
						<span class="project-tag">JavaScript</span>
					</div>
					<img loading="lazy" decoding="async" width="1291" height="970" src="{{ asset('images/projects/breaking-cat.webp') }}" alt="{{ __('projects.preview.breakingcat') }}" class="project__image">
					<div class="project__links">
						<a href="https://gitlab.com/JeremyEscassut/breakingcat" target="_blank" rel="noopener noreferrer" class="pf-btn pf-btn--secondary project-link">
							{!! $icon('icon-gitlab-repo-link') !!}
							<span>{{ __('projects.link.code') }}</span>
						</a>
					</div>
				</article>
			</div>
		</section>

		<section id="experience" class="home-section scroll-mt-24" aria-labelledby="experience-title">
			<div class="section-banner sticky top-0 z-20 -mx-6 mb-2 w-screen bg-transparent px-6 py-3 backdrop-blur">
				<h2 id="experience-title" class="section-title">{{ __('home.section.experience') }}</h2>
			</div>
			<div class="section-body timeline">
				<div class="timeline__item">
					<p class="timeline__date">{{ __('experience.current.dates') }}</p>
					<div>
						<h3 class="timeline__title">{{ __('experience.current.title') }}</h3>
						<p class="timeline__subtitle">{{ __('experience.current.subtitle') }}</p>
						<p>{{ __('experience.current.description') }}</p>
						<p>{{ __('experience.current.internship') }}</p>
					</div>
				</div>

				<div class="timeline__item">
					<p class="timeline__date">{{ __('experience.previous.dates') }}</p>
					<div>
						<h3 class="timeline__title">{{ __('experience.previous.title') }}</h3>
						<p class="timeline__subtitle">{{ __('experience.previous.subtitle') }}</p>
						<p>{{ __('experience.previous.description') }}</p>
					</div>
				</div>
			</div>
		</section>

		<section id="studies" class="home-section scroll-mt-24" aria-labelledby="studies-title">
			<div class="section-banner sticky top-0 z-20 -mx-6 mb-2 w-screen bg-transparent px-6 py-3 backdrop-blur">
				<h2 id="studies-title" class="section-title">{{ __('home.section.studies') }}</h2>
			</div>
			<div class="section-body timeline">
				<div class="timeline__item">
					<p class="timeline__date">{{ __('studies.cda.dates') }}</p>
					<div>
						<h3 class="timeline__title">{{ __('studies.cda.title') }}</h3>
						<p>{{ __('studies.cda.description') }}</p>
					</div>
				</div>
				<div class="timeline__item">
					<p class="timeline__date">{{ __('studies.master.dates') }}</p>
					<div>
						<h3 class="timeline__title">{{ __('studies.master.title') }}</h3>
						<p>{{ __('studies.master.description') }}</p>
					</div>
				</div>
			</div>
		</section>

		<section id="recommendations" class="home-section scroll-mt-24" aria-labelledby="recommendations-title">
			<div class="section-banner sticky top-0 z-20 -mx-6 mb-2 w-screen bg-transparent px-6 py-3 backdrop-blur">
				<h2 id="recommendations-title" class="section-title">{{ __('home.section.recommendations') }}</h2>
			</div>
			<div class="section-body recommendations">
				<figure class="reco">
					<blockquote class="reco__quote">
						<p>{{ __('recommendations.linkedin.quote') }}</p>
					</blockquote>
					<figcaption class="reco__author">
						<span class="reco__name">{{ __('recommendations.linkedin.author') }}</span>
						<span class="reco__role">{{ __('recommendations.linkedin.role') }}</span>
						<span class="reco__context">{{ __('recommendations.linkedin.context') }}</span>
						<a href="https://www.linkedin.com/in/jérémy-escassut-57773427/" target="_blank" rel="noopener noreferrer" class="reco__link">{{ __('recommendations.linkedin.link') }}</a>
					</figcaption>
				</figure>

				<div class="reco reco--letter">
					<p class="reco__name">{{ __('recommendations.pdf.title') }}</p>
					<p class="reco__context">{{ __('recommendations.pdf.context') }}</p>
					<p>{{ __('recommendations.pdf.body') }}</p>
					<a href="{{ asset('recommandations/Jérémy Escassut - Lettre de recommandation par Pierre Souillé.pdf') }}" target="_blank" rel="noopener noreferrer" class="pf-btn pf-btn--secondary pf-btn--small">
						{{ __('recommendations.pdf.download') }}
					</a>
				</div>
			</div>
		</section>

		<section id="stack" class="home-section scroll-mt-24" aria-labelledby="stack-title">
			<div class="section-banner sticky top-0 z-20 -mx-6 mb-2 w-screen bg-transparent px-6 py-3 backdrop-blur">
				<h2 id="stack-title" class="section-title">{{ __('home.section.stack') }}</h2>
			</div>

			@php
			$stack = [
				'front' => ['angular' => 'Angular', 'react' => 'React', 'html5' => 'HTML5', 'ionic' => 'Ionic'],
				'back' => ['laravel' => 'Laravel', 'nodejs' => 'Node.js', 'python' => 'Python', 'django' => 'Django', 'java' => 'Java', 'spring-boot' => 'Spring Boot', 'symfony' => 'Symfony', 'postgresql' => 'PostgreSQL', 'mysql' => 'MySQL'],
				'other' => ['jira' => 'Jira', 'postman' => 'Postman', 'php' => 'PHP'],
				'ai' => ['claude-code' => 'Claude Code', 'codex' => 'Codex', 'lovable' => 'Lovable'],
			];
			@endphp
			<div id="stack-icons" class="p-0 stack-grid">
				<div class="space-y-4 text-sm">
					@foreach($stack as $group => $tools)
					<div @if($group === 'other') id="other" @endif class="stack-group stack-group--{{ $group }} pl-4 sm:pl-5 lg:pl-6">
						<h3 class="text-sm font-semibold mb-2">{{ __('stack.' . $group) }}</h3>
						<div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-6 gap-2">
							@foreach($tools as $slug => $label)
							<div class="bg-white rounded flex items-center justify-center skill-{{ $slug }} skill-tile" role="img" aria-label="{{ $label }}" data-name="{{ $label }}">
								@if($slug === 'postman')
								<span class="postman-light">{!! $icon('icon-postman') !!}</span>
								<span class="postman-dark" aria-hidden="true">{!! $icon('icon-postman-dark') !!}</span>
								@else
								{!! $icon('icon-' . $slug) !!}
								@endif
							</div>
							@endforeach
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</section>

		<!-- Footer within right column -->
		<footer id="footer" class="right-footer site-footer">
			<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2">
				<div class="site-footer__links">
					<span>&copy; {{ date('Y') }} Jérémy Escassut</span>
					<button type="button" class="site-footer__link" onclick="setTimeout(()=>window.dispatchEvent(new CustomEvent('open-contact')), 0)">{{ __('footer.contact') }}</button>
					<a href="{{ route('mentions', ['locale' => app()->getLocale()]) }}" class="site-footer__link">{{ __('legal.title') }}</a>
				</div>
				<div class="text-center lg:text-right site-footer__meta">
					{{ __('footer.built_with') }}
				</div>
			</div>
		</footer>

	</main>
</div>

{{-- Toasts: result of the contact form after the redirect. Behaviour: resources/js/contactFeedback.js --}}
<div id="toast-region" class="toast-region">
	@if(session('status'))
	<div class="toast toast--success" role="status" data-autohide="10000">
		<p class="toast__text">{{ session('status') }}</p>
		<button type="button" class="toast__close" aria-label="{{ __('toast.close') }}">✕</button>
	</div>
	@endif
	@if(session('error'))
	<div class="toast toast--error" role="alert" data-autohide="10000">
		<p class="toast__text">{{ session('error') }}</p>
		<button type="button" class="toast__close" aria-label="{{ __('toast.close') }}">✕</button>
	</div>
	@endif
</div>

{{-- Mobile/tablet "jump to section" fan. Behaviour: resources/js/sectionFab.js, styles: app.css ("Section fan"). --}}
@php
$fabSections = ['about', 'projects', 'experience', 'studies', 'recommendations', 'stack'];
@endphp
<div id="section-fab" class="section-fab">
	<div class="section-fab__backdrop" hidden></div>
	<nav id="section-fab-menu" class="section-fab__menu" aria-label="{{ __('nav.sections') }}">
		<ul>
			@foreach($fabSections as $i => $sectionId)
			<li class="section-fab__item" style="--i: {{ $i }}">
				<a href="#{{ $sectionId }}" class="pf-btn section-fab__link">{{ __('nav.' . $sectionId) }}</a>
			</li>
			@endforeach
		</ul>
	</nav>
	<button type="button" class="section-fab__toggle" aria-expanded="false" aria-controls="section-fab-menu"
		aria-label="{{ __('nav.sections_open') }}"
		data-label-open="{{ __('nav.sections_open') }}"
		data-label-close="{{ __('nav.sections_close') }}">
		<svg class="section-fab__icon section-fab__icon--open" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
			<path d="M5 7h14M5 12h14M5 17h14" />
		</svg>
		<svg class="section-fab__icon section-fab__icon--close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
			<path d="M7 7l10 10M17 7l-10 10" />
		</svg>
	</button>
</div>

{{--
	Contact modal. It opens on the 'open-contact' event, and reopens by itself after a
	redirect when sending failed (session 'contactFailed') or validation failed ($errors):
	old() refills the fields, so the visitor never has to type their message again.
--}}
@php
$contactFailed = (bool) session('contactFailed');
$reopenContact = $contactFailed || $errors->any();
$contactAddress = config('mail.public_contact_address');
@endphp
<div x-data="contactModal()" x-init="if (@js($reopenContact)) { isOpen = true; $nextTick(() => { if ($refs.message) $refs.message.focus(); }) }" @open-contact.window="isOpen = true; $nextTick(() => { if ($refs.email) $refs.email.focus(); })" @keydown.escape.window="if (isOpen) closeModal()" :aria-hidden="!isOpen">
	<template x-if="isOpen">
		<div>
			<div x-show="isOpen" x-cloak class="fixed inset-0 bg-black/50" aria-hidden="true" style="z-index: 9998;"></div>

			<div x-show="isOpen" x-cloak class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 9999;">
				<div @click.away="closeModal()" class="w-full max-w-lg bg-white dark:bg-[#111] rounded-lg shadow-lg overflow-hidden" role="dialog" aria-modal="true" aria-labelledby="contact-title" style="z-index: 10000; position: relative;">
					<div class="p-6">
						<div class="flex justify-between items-center mb-4">
							<h3 id="contact-title" class="text-lg font-medium">{{ __('contact.form.title') }}</h3>
							<button type="button" @click="closeModal()" class="pf-close" aria-label="{{ __('ui.close') }}">✕</button>
						</div>

						<form method="POST" action="{{ route('contact.send', ['locale' => app()->getLocale()]) }}" class="space-y-4">
							@csrf
							<!-- honeypot -->
							<input type="text" name="hp" tabindex="-1" autocomplete="off" style="display:none" aria-hidden="true">

							@if($errors->any())
							<div class="contact-feedback contact-feedback--error" role="alert">
								<p>{{ __('contact.form.errors') }}</p>
								<ul>
									@foreach($errors->all() as $err)
									<li>{{ $err }}</li>
									@endforeach
								</ul>
							</div>
							@endif

							@if($contactFailed && $contactAddress)
							{{-- Fallback when the server could not send the mail: hand the message
							     over to the visitor's own email app (mailto:) or copy it.
							     Behaviour: resources/js/contactFeedback.js --}}
							<div class="contact-feedback contact-feedback--fallback" role="alert">
								<p><strong>{{ __('contact.failed') }}</strong> {{ __('contact.fallback.intro') }} <a href="mailto:{{ $contactAddress }}">{{ $contactAddress }}</a>.</p>
								<div class="contact-feedback__actions">
									<a href="mailto:{{ $contactAddress }}" class="pf-btn pf-btn--primary pf-btn--small" data-mailto-fallback data-to="{{ $contactAddress }}" data-subject="{{ __('contact.fallback.subject') }}">{{ __('contact.fallback.open_mail') }}</a>
									<button type="button" class="pf-btn pf-btn--secondary pf-btn--small" data-copy-message data-copied-label="{{ __('contact.fallback.copied') }}">{{ __('contact.fallback.copy') }}</button>
								</div>
								<p class="contact-feedback__hint">{{ __('contact.fallback.webmail_hint') }}</p>
								{{-- Shown by contactFeedback.js when the mailto: link opened nothing --}}
								<p class="contact-feedback__not-opened" data-mailto-not-opened hidden>{{ __('contact.fallback.not_opened') }} <strong>{{ $contactAddress }}</strong>.</p>
							</div>
							@endif

							<div>
								<label for="contact-name" class="block text-sm">{{ __('contact.form.name') }}</label>
								<input id="contact-name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" class="mt-1 block w-full rounded border px-3 py-2" placeholder="{{ __('contact.form.name_placeholder') }}">
							</div>

							<div>
								<label for="contact-email" class="block text-sm">{{ __('contact.form.email') }}</label>
								<input id="contact-email" x-ref="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" class="mt-1 block w-full rounded border px-3 py-2" placeholder="{{ __('contact.form.email_placeholder') }}" required>
							</div>

							<div>
								<label for="contact-message" class="block text-sm">{{ __('contact.form.message') }}</label>
								<textarea id="contact-message" x-ref="message" name="message" rows="4" class="mt-1 block w-full rounded border px-3 py-2" placeholder="{{ __('contact.form.message_placeholder') }}" required>{{ old('message') }}</textarea>
							</div>

							<div class="contact-actions">
								<button type="button" @click="closeModal()" class="pf-btn pf-btn--secondary pf-btn--small">{{ __('contact.form.cancel') }}</button>
								<button type="submit" class="pf-btn {{ $contactFailed ? 'pf-btn--secondary' : 'pf-btn--primary' }} pf-btn--small">{{ $contactFailed ? __('contact.form.retry') : __('contact.form.submit') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</template>
</div>

@php
$cvs = [
	['file' => 'ESCASSUT_JEREMY_-_CV_Développeur_Full_Stack_orienté_Produit_&_UX_Juillet_2026.pdf', 'title' => 'cv.modal.dev', 'desc' => 'cv.modal.dev_desc'],
	['file' => 'ESCASSUT_JEREMY_-_CV_Chef_de_projet_Coordination_internationale_Juillet_2026.pdf', 'title' => 'cv.modal.pm', 'desc' => 'cv.modal.pm_desc'],
	['file' => 'ESCASSUT_JEREMY_-_CV_Product_Owner_|_Transformation_digitale_Juillet_2026.pdf', 'title' => 'cv.modal.po', 'desc' => 'cv.modal.po_desc'],
];
@endphp
<div id="cv-selector-modal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4" aria-hidden="true" x-cloak style="display: none;">
	<a href="#" class="absolute inset-0 bg-black/50" onclick="window.closeCvSelectorModal(); return false;" aria-label="{{ __('ui.close') }}"></a>
	<div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-lg dark:bg-[#111]" role="dialog" aria-modal="true" aria-labelledby="cv-modal-title">
		<div class="flex items-start justify-between gap-4">
			<div>
				<h3 id="cv-modal-title" class="text-lg font-semibold">{{ __('cv.modal.title') }}</h3>
				<p class="mt-1 text-sm text-[#706f6c]">{{ __('cv.modal.hint') }}</p>
			</div>
			<a href="#" onclick="window.closeCvSelectorModal(); return false;" class="pf-close" aria-label="{{ __('ui.close') }}">✕</a>
		</div>
		<div class="mt-6 flex flex-col gap-3">
			@foreach($cvs as $cv)
			<a href="{{ asset('cv/' . $cv['file']) }}"
				target="_blank" rel="noopener noreferrer"
				onclick="window.closeCvSelectorModal();"
				class="cv-option px-4 py-3 text-left">
				<span class="block font-medium">{{ __($cv['title']) }}</span>
				<span class="mt-1 block text-sm text-[#706f6c]">{{ __($cv['desc']) }}</span>
			</a>
			@endforeach
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
	// Language toggle: swap between 'fr' and 'en', preserve path/query/hash, set cookie
	(function() {
		function setLocaleCookie(locale) {
			try {
				// Middleware reads `site_locale` so set that cookie name for consistency
				document.cookie = 'site_locale=' + encodeURIComponent(locale) + ';path=/;max-age=' + (60 * 60 * 24 * 365);
			} catch (e) {
				/* noop */
			}
		}

		function buildNewPath(newLocale) {
			var loc = window.location;
			var path = loc.pathname || '/';

			// If the app is mounted under a base path (example: '/portfolio'),
			// remove that base from the location pathname before rebuilding
			// the canonical locale-prefixed path. We rely on `window.APP_BASE`
			// (injected server-side) instead of hardcoding '/portfolio'.
			if (window.APP_BASE && window.APP_BASE !== '') {
				// escape for use in RegExp
				var base = window.APP_BASE.replace(/\/$/, '');
				if (base) {
					var esc = base.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
					var re = new RegExp('^' + esc, 'i');
					path = path.replace(re, '');
				}
			}

			// remove any leading locale from the rest ('/fr' or '/en')
			var rest = path.replace(/^\/(fr|en)(?:\/|$)/i, '/');
			if (rest === '/') rest = '';

			var newPath = '/' + newLocale + (rest === '' ? '' : rest);
			return newPath + (loc.search || '') + (loc.hash || '');
		}

		document.addEventListener('DOMContentLoaded', function() {
			var btn = document.getElementById('langToggleButton');
			if (!btn) return;
			var badge = btn.querySelector('.lang-badge');
			var current = (btn.getAttribute('data-locale') || document.documentElement.lang || 'fr').toLowerCase();
			if (badge) badge.textContent = (current === 'fr' ? 'EN' : 'FR');

			btn.addEventListener('click', function(e) {
				e.preventDefault();
				var next = (current === 'fr') ? 'en' : 'fr';
				// set cookie so middleware can fall back when route param missing
				setLocaleCookie(next);
				// redirect to same path prefixed with locale
				var target = buildNewPath(next);
				window.location.href = target;
			});
		});
	})();
</script>
@endpush
