@extends('layouts.app')

@section('content')
<div class="lg:flex lg:justify-between lg:gap-4">
	<!-- LEFT: navigation / identity (centré verticalement sur grand écran) -->
	<!-- LEFT: like Brittany, sticky on large screens within page scroll -->
	<aside id="home-aside" class="order-1 lg:order-1 lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-[48%] lg:flex-col lg:justify-between lg:py-24">
		<div id="home" data-x-data="{ theme: localStorage.getItem('theme') || 'light', hoverCV: false, cvModalOpen: false, init(){ if(this.theme==='dark'){ document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') } }, set(t){ this.theme=t; if(t==='dark'){ localStorage.setItem('theme','dark'); document.documentElement.classList.add('dark') } else { localStorage.setItem('theme','light'); document.documentElement.classList.remove('dark') } }, cycle(){ const order=['light','dark']; let i=order.indexOf(this.theme); this.set(order[(i+1)%order.length]); }, openCvModal(){ this.cvModalOpen = true }, closeCvModal(){ this.cvModalOpen = false } }"
			data-x-init="init()"
			class="w-full bg-transparent dark:bg-transparent rounded-lg p-8 lg:p-12 shadow flex flex-col justify-between">
			<!-- Name -->
			<div id="home-title" class="mb-8 lg:mb-10">
				<!-- Single-cycle theme icon (click to cycle Auto → Clair → Sombre) -->
				<div id="home-icons" class="mt-3 flex justify-end"></div>

				<h1 class="text-3xl ml-6 font-semibold">{{ __('home.hero.name') }}</h1>
				<h2 class="text-xl ml-6 font-semibold">{{ __('home.job_title') }}</h2>
				<p class="text-[#706f6c] mt-3">{{ __('portfolio.welcome') }}</p>
			</div>

			<div id="home-interactions" class="flex gap-4 mt-4 items-start lg:gap-6 lg:mt-6">
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
							<div id="photoCanvasWrapper" class="photo-frame" style="max-width:430px; display:flex; align-items:center; justify-content:center;">
								<svg class="photo-frame__wireframe" viewBox="0 0 430 430" aria-hidden="true" focusable="false">
									<path d="M 51 322 V 108 L 215 13 L 379 108 V 285" />
									<path class="photo-frame__notch" d="M 379 316 V 322 L 350 339" />
									<path d="M 323 355 L 215 417 L 51 322" />
								</svg>
								<canvas id="photoCanvas" width="400" height="400"
									style="display:block; width:100%; height:auto; max-width:400px;"></canvas>
							</div>
							<p id="photoCaption" class="mt-3 text-sm text-center text-[#706f6c] dark:text-[#A1A09A]">{!! __('photo.caption') !!}</p>
						</div>
					</div>
				</div>

				<!-- RIGHT column: stacked list, social icons, contact/CV (fills remaining space on mobile) -->
				<div id="home-right-col" class="right-col flex-none w-auto max-w-[140px] sm:max-w-[180px] flex flex-col gap-4 lg:gap-7">
					<nav class="mt-0 mb-0 hidden lg:block lg:mb-4">
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
						<!-- LinkedIn (inline from resources/svg/icon-linkedin.svg) -->
						<a href="https://www.linkedin.com/in/jérémy-escassut-57773427" target="_blank" rel="noopener noreferrer" aria-label="{{ __('social.linkedin') }}" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--linkedin">
							@php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-linkedin.svg')); } catch (Exception $e) { } @endphp
							@if($svg) {!! $svg !!} @endif
						</a>

						<!-- GitLab (inline from resources/svg/icon-gitlab.svg) -->
						<a href="https://gitlab.com/JeremyEscassut" id="gitlab-link" target="_blank" rel="noopener noreferrer" aria-label="{{ __('social.gitlab') }}" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--gitlab">
							@php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-gitlab.svg')); } catch (Exception $e) { } @endphp
							@if($svg) {!! $svg !!} @endif
						</a>


					</div>

					<!-- Theme/language controls moved above contact/CV actions -->
					<div id="theme-toggle-wrapper" class="mt-1 flex justify-end items-center gap-2">
						<button id="themeToggleButton" type="button" @click="cycle()" title="{{ __('ui.theme.selector') }}" class="p-3 rounded theme-lang-button mobile-offset lg:transform lg:-translate-x-2 hover:bg-gray-100 dark:hover:bg-neutral-800" aria-label="{{ __('ui.theme.selector') }}">
							<template x-if="theme==='auto'">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
									<rect x="3" y="4" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.5"></rect>
									<path d="M8 20h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
								</svg>
							</template>
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

						<!-- Language toggle button: show target locale (inverse of current) -->
						<button id="langToggleButton" type="button" data-locale="{{ app()->getLocale() }}" class="p-3 rounded theme-lang-button mobile-offset hover:bg-gray-100 dark:hover:bg-neutral-800" aria-label="{{ __('ui.lang.change') }}" title="{{ __('ui.lang.change') }}">
							<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
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

					<div id="home-contact-cv-icons" class="contact-cv-actions mt-0 flex items-center gap-3 justify-end">
						<!-- Contact (inline from resources/svg/icon-contact.svg) -->
						<button onclick="setTimeout(()=>window.dispatchEvent(new CustomEvent('open-contact')), 0)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--contact" aria-label="{{ __('contact.open') }}" style="margin-top:2px;">
							@php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-contact.svg')); } catch (Exception $e) { } @endphp
							@if($svg) {!! $svg !!} @endif
						</button>

						<!-- CV (PNG light/dark variants placed in resources/png) -->
						<a href="#cv-selector-modal"
							data-cv-open="true"
							data-x-on-mouseenter="hoverCV = true"
							data-x-on-mouseleave="hoverCV = false"
							data-x-on-focus="hoverCV = true"
							data-x-on-blur="hoverCV = false"
							onclick="window.openCvSelectorModal(); return false;"
							class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 flex items-center justify-center social-link social-link--cv"
							aria-label="{{ __('cv.view') }} ({{ __('nav.about') }})" style="margin-top:2px;">
							{{-- icône pour mode clair (noir sur fond clair) --}}
							<img src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'cv-black-100.png'))) }}"
								alt="{{ __('cv.icon_alt') }}" class="w-8 h-8" aria-hidden="true" data-x-show="theme==='light'" data-x-cloak>
							{{-- icône pour mode sombre par défaut (blanc sur fond sombre) --}}
							<img src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'cv-white-100.png'))) }}"
								alt="{{ __('cv.icon_alt') }}" class="w-8 h-8" aria-hidden="true" data-x-show="theme==='dark' && !hoverCV" data-x-cloak>
							{{-- icône pour mode sombre lors du hover (utilise la variante noire fournie) --}}
							<img src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'cv-black-100.png'))) }}"
								alt="{{ __('cv.icon_alt') }}" class="w-8 h-8" aria-hidden="true" data-x-show="theme==='dark' && hoverCV" data-x-cloak>
						</a>
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
				<button @click="show=false" class="text-sm text-green-700 dark:text-green-200 opacity-80 hover:opacity-100" aria-label="{{ __('ui.message.close') }}">✕</button>
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
				<button @click="show=false" class="text-sm text-red-700 dark:text-red-200 opacity-80 hover:opacity-100" aria-label="{{ __('ui.message.close') }}">✕</button>
			</div>
			@endif

			@if($errors->any())
			<div x-show="show" role="alert" class="p-4 rounded-lg border border-red-200 bg-red-50 text-red-800 dark:bg-red-900 dark:border-red-800 dark:text-red-100 shadow-sm">
				<div class="flex items-start justify-between">
					<div class="text-sm">{{ __('contact.form.errors') }}</div>
					<button @click="show=false" class="text-sm text-red-700 dark:text-red-200 opacity-80 hover:opacity-100" aria-label="{{ __('ui.message.close') }}">✕</button>
				</div>
				<ul class="mt-2 list-disc pl-5 text-sm">
					@foreach($errors->all() as $err)
					<li>{{ $err }}</li>
					@endforeach
				</ul>
			</div>
			@endif
		</div>



		<section id="about" class="mb-8 scroll-mt-24" aria-label="{{ __('nav.about') }}">
			<div id="section-banner" class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
				<h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">{{ __('home.section.about') }}</h2>
			</div>
			<div class="p-6">
				<p class="text-sm text-[#706f6c]">{{ __('about.p1') }}</p>
				<p class="text-sm text-[#706f6c] mt-3">{{ __('about.p2') }}</p>
				<p class="text-sm text-[#706f6c] mt-2">{{ __('about.p3') }}</p>
				<p class="text-sm text-[#706f6c] mt-2">{{ __('about.p4') }}</p>
			</div>
		</section>

		<section id="projects" class="mb-8 scroll-mt-24" aria-label="{{ __('nav.projects') }}">
			<div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
				<h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">{{ __('home.section.projects') }}</h2>
			</div>
			<div class="p-0">
				<div class="flex flex-col gap-4">
					@php
					$crmSlide1 = '';
					$crmSlide2 = '';
					$crmSlide3 = '';
					$a2Svg = '';
					try { $crmSlide1 = base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'Admiral - Détails d’un attendu.png'))); } catch (Exception $e) {}
					try { $crmSlide2 = base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'Admiral - TdB Admin.png'))); } catch (Exception $e) {}
					try { $crmSlide3 = base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'Admiral - TdB Client.png'))); } catch (Exception $e) {}
					try { $a2Svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-a2balles.svg')); } catch (Exception $e) {}
					$crmSlides = [
					['image' => $crmSlide1, 'alt' => __('projects.crm.carousel.slide1.alt')],
					['image' => $crmSlide2, 'alt' => __('projects.crm.carousel.slide2.alt')],
					['image' => $crmSlide3, 'alt' => __('projects.crm.carousel.slide3.alt')],
					];
					@endphp
					<p class="px-1 text-sm text-[#706f6c]">{{ __('projects.intro') }}</p>

					<div class="w-full p-4 bg-white/70 rounded flex flex-col gap-4 project-card">
						<div class="flex-1 text-sm text-[#706f6c]">
							<div class="flex items-center">
								<h3 class="text-base font-semibold">{{ __('projects.crm.title') }}</h3>
							</div>
							<p class="mt-2 text-sm text-[#706f6c]">{{ __('projects.crm.description') }}</p>
							<div class="project-tags mt-3">
								<span class="project-tag">React</span>
								<span class="project-tag">Spring Boot</span>
								<span class="project-tag">TypeScript</span>
								<span class="project-tag">PostgreSQL</span>
								<span class="project-tag">API REST</span>
							</div>
						</div>
						<div class="mt-2" x-data="{ videoOpen: false, slides: {{ json_encode($crmSlides) }}, currentIndex: 0, startX: null, dragDelta: 0, goToNext() { this.currentIndex = (this.currentIndex + 1) % this.slides.length; }, goToPrev() { this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length; }, handleTouchStart(event) { this.startX = event.touches[0].clientX; this.dragDelta = 0; }, handleTouchMove(event) { if (this.startX === null) return; this.dragDelta = this.startX - event.touches[0].clientX; }, handleTouchEnd() { if (this.dragDelta > 50) { this.goToNext(); } else if (this.dragDelta < -50) { this.goToPrev(); } this.startX = null; this.dragDelta = 0; }, handlePointerDown(event) { this.startX = event.clientX; this.dragDelta = 0; }, handlePointerMove(event) { if (this.startX === null) return; this.dragDelta = this.startX - event.clientX; }, handlePointerUp() { if (this.dragDelta > 50) { this.goToNext(); } else if (this.dragDelta < -50) { this.goToPrev(); } this.startX = null; this.dragDelta = 0; } }">
							<div class="relative w-full overflow-hidden rounded border border-[#e6ece5] bg-white/70 p-2" @touchstart="handleTouchStart($event)" @touchmove="handleTouchMove($event)" @touchend="handleTouchEnd()" @pointerdown="handlePointerDown($event)" @pointermove="handlePointerMove($event)" @pointerup="handlePointerUp()" @pointerleave="handlePointerUp()">
								<template x-for="(slide, index) in slides" :key="index">
									<div x-show="index === currentIndex" class="w-full">
										<img loading="lazy" :src="'data:image/png;base64,' + slide.image" :alt="slide.alt" class="h-auto w-full rounded object-contain">
									</div>
								</template>
								<div class="absolute inset-x-0 bottom-3 flex items-center justify-center gap-2">
									<template x-for="(slide, index) in slides" :key="index">
										<button type="button" class="h-2.5 w-2.5 rounded-full transition" :class="index === currentIndex ? 'bg-[#1b1b18]' : 'bg-[#c7d5c7] hover:bg-[#8b9a87]'" @click="currentIndex = index" :aria-label="'{{ __('projects.crm.carousel.previous') }}' + ' ' + (index + 1)"></button>
									</template>
								</div>
								<button type="button" class="absolute inset-y-0 left-0 z-10 flex w-1/2 items-center justify-start px-3 text-xl text-[#1b1b18] opacity-80 transition hover:opacity-100" @click="goToPrev()" :aria-label="'{{ __('projects.crm.carousel.previous') }}'">←</button>
								<button type="button" class="absolute inset-y-0 right-0 z-10 flex w-1/2 items-center justify-end px-3 text-xl text-[#1b1b18] opacity-80 transition hover:opacity-100" @click="goToNext()" :aria-label="'{{ __('projects.crm.carousel.next') }}'">→</button>
							</div>
							<button type="button" @click="videoOpen = true" class="recommendation-pdf-button mt-3 inline-flex items-center gap-2 rounded-full border border-[#c9d7cb] bg-[#f3f7f1] px-4 py-2 text-sm font-medium text-[#314132] shadow-sm transition hover:bg-[#e8f1e8] dark:text-[#edf4eb]">
								{{ __('projects.crm.video.launch') }}
							</button>
							<div x-show="videoOpen" x-cloak class="fixed inset-0 z-[10000] flex items-center justify-center bg-black/70 p-4">
								<div class="w-full max-w-4xl rounded-lg bg-white p-3 shadow-xl" role="dialog" aria-modal="true" aria-label="{{ __('projects.crm.video.title') }}">
									<div class="mb-2 flex items-center justify-between">
										<p class="text-sm font-semibold text-[#1b1b18]">{{ __('projects.crm.video.title') }}</p>
										<button type="button" @click="videoOpen = false" class="text-sm text-[#706f6c] hover:text-[#1b1b18]" aria-label="{{ __('projects.crm.video.close') }}">✕</button>
									</div>
									<video controls playsinline preload="metadata" class="w-full rounded-lg" aria-label="{{ __('projects.crm.video.title') }}">
										<source src="{{ asset('mov/demo-admiral-720p.mp4') }}" type="video/mp4" media="(max-width: 768px)">
										<source src="{{ asset('mov/demo-admiral-1080p.mp4') }}" type="video/mp4">
										{{ __('projects.crm.video.unsupported') }}
									</video>
								</div>
							</div>
						</div>
					</div>

					<div class="w-full p-4 bg-white/70 rounded flex flex-col gap-4 project-card">
						<div class="flex-1 text-sm text-[#706f6c]">
							<div class="flex items-center">
								<h3 class="text-base font-semibold">{{ __('projects.pwa.title') }}</h3>
								@if($a2Svg)
								<a href="https://a2balles.com" target="_blank" rel="noopener noreferrer" aria-label="{{ __('projects.repo.pwa') }}" class="repo-link project-link--a2balles ml-5 inline-flex items-center justify-center rounded p-1 hover:bg-gray-100 dark:hover:bg-neutral-800">
									{!! $a2Svg !!}
								</a>
								@endif
							</div>
							<p class="mt-2 text-sm text-[#706f6c]">{{ __('projects.pwa.description') }}</p>
							<div class="project-tags mt-3">
								<span class="project-tag">Lovable</span>
								<span class="project-tag">React</span>
								<span class="project-tag">TypeScript</span>
								<span class="project-tag">Postgres</span>
							</div>
						</div>

					</div>

					<div class="w-full p-4 bg-white/70 rounded flex flex-col gap-4 project-card">
						<div class="flex-1 text-sm text-[#706f6c]">
							<div class="flex items-center">
								<h3 class="text-base font-semibold">{{ __('projects.livret.title') }}</h3>
								@php $repoSvg = ''; try { $repoSvg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-gitlab-repo-link.svg')); } catch (Exception $e) { } @endphp
								<a href="https://gitlab.com/JeremyEscassut/livret-cda" target="_blank" rel="noopener noreferrer" aria-label="{{ __('projects.repo.livret') }}" class="ml-5 repo-link p-1 rounded hover:bg-gray-100 dark:hover:bg-neutral-800">
									@if($repoSvg) {!! $repoSvg !!} @endif
								</a>
							</div>
							<p class="mt-2 text-sm text-[#706f6c]">{{ __('projects.livret.description') }}</p>
							<div class="project-tags mt-3">
								<span class="project-tag">PHP</span>
								<span class="project-tag">Symfony</span>
								<span class="project-tag">EasyAdmin</span>
								<span class="project-tag">PHPUnit</span>
							</div>
						</div>
						<img loading="lazy" width="600" height="400" src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'Livret_CDA.png'))) }}" alt="{{ __('projects.preview.livret') }}" class="rounded project-image">
					</div>

					<div class="w-full p-4 bg-white/70 rounded flex flex-col gap-4 project-card">
						<div class="flex-1 text-sm text-[#706f6c]">
							<div class="flex items-center">
								<h3 class="text-base font-semibold">{{ __('projects.breakingcat.title') }}</h3>
								@php $repoSvg = ''; try { $repoSvg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-gitlab-repo-link.svg')); } catch (Exception $e) { } @endphp
								<a href="https://gitlab.com/JeremyEscassut/breakingcat" target="_blank" rel="noopener noreferrer" aria-label="{{ __('projects.repo.breakingcat') }}" class="ml-5 repo-link p-1 rounded hover:bg-gray-100 dark:hover:bg-neutral-800">
									@if($repoSvg) {!! $repoSvg !!} @endif
								</a>
							</div>
							<p class="mt-2 text-sm text-[#706f6c]">{{ __('projects.breakingcat.description') }}</p>
							<div class="project-tags mt-3">
								<span class="project-tag">PHP</span>
								<span class="project-tag">MySQL</span>
								<span class="project-tag">PDO</span>
								<span class="project-tag">JavaScript</span>
							</div>
						</div>
						<img loading="lazy" width="600" height="400" src="data:image/png;base64,{{ base64_encode(file_get_contents(resource_path('png'.DIRECTORY_SEPARATOR.'Breaking_Cat.png'))) }}" alt="{{ __('projects.preview.breakingcat') }}" class="rounded project-image">
					</div>
				</div>
			</div>
		</section>

		<section id="experience" class="mb-8 scroll-mt-24" aria-label="{{ __('nav.experience') }}">
			<div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
				<h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">{{ __('home.section.experience') }}</h2>
			</div>
			<div class="p-6">
				<div class="space-y-6">
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
						<div class="text-sm text-[#706f6c] font-medium">{{ __('experience.current.dates') }}</div>
						<div>
							<h3 class="text-lg font-semibold tracking-tight">{{ __('experience.current.title') }}</h3>
							<p class="text-sm font-medium text-[#5f615f] mt-1">{{ __('experience.current.subtitle') }}</p>
							<p class="text-sm text-[#706f6c] mt-2">{{ __('experience.current.description') }}</p>
							<p class="text-sm text-[#706f6c] mt-2">{{ __('experience.current.internship') }}</p>
						</div>
					</div>

					<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
						<div class="text-sm text-[#706f6c] font-medium">{{ __('experience.previous.dates') }}</div>
						<div>
							<h3 class="text-lg font-semibold tracking-tight">{{ __('experience.previous.title') }}</h3>
							<p class="text-sm font-medium text-[#5f615f] mt-1">{{ __('experience.previous.subtitle') }}</p>
							<p class="text-sm text-[#706f6c] mt-2">{{ __('experience.previous.description') }}</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="studies" class="mb-8 scroll-mt-24" aria-label="{{ __('nav.studies') }}">
			<div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
				<h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">{{ __('home.section.studies') }}</h2>
			</div>
			<div class="p-6">
				<div class="space-y-6">
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
						<div class="text-sm text-[#706f6c] font-medium">{{ __('studies.cda.dates') }}</div>
						<div>
							<h3 class="text-lg font-semibold tracking-tight">{{ __('studies.cda.title') }}</h3>
							<p class="mt-2 text-sm text-[#706f6c]">{{ __('studies.cda.description') }}</p>
						</div>
					</div>
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
						<div class="text-sm text-[#706f6c] font-medium">{{ __('studies.master.dates') }}</div>
						<div>
							<h3 class="text-lg font-semibold tracking-tight">{{ __('studies.master.title') }}</h3>
							<p class="mt-2 text-sm text-[#706f6c]">{{ __('studies.master.description') }}</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="recommendations" class="mb-8 scroll-mt-24" aria-label="{{ __('nav.recommendations') }}">
			<div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
				<h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">{{ __('home.section.recommendations') }}</h2>
			</div>
			<div class="p-6">
				<p class="text-sm text-[#706f6c]">{{ __('recommendations.intro') }}</p>
				<div class="mt-4 space-y-4">
					<div class="rounded border-l-2 border-[#c7d8ca] bg-transparent py-4 pl-4 pr-2 text-sm text-[#706f6c]">
						<p class="text-sm font-semibold text-[#1b1b18] dark:text-[#f5f5f0]">{{ __('recommendations.pdf.title') }}</p>
						<p class="mt-2">{{ __('recommendations.pdf.body') }}</p>
						<a href="{{ asset('recommandations/Jérémy Escassut - Lettre de recommandation par Pierre Souillé.pdf') }}" target="_blank" rel="noopener noreferrer" class="recommendation-pdf-button mt-4 inline-flex rounded-full border border-[#c9d7cb] bg-[#f3f7f1] px-4 py-2 text-sm font-medium text-[#314132] shadow-sm transition hover:bg-[#e8f1e8] dark:text-[#edf4eb]">
							{{ __('recommendations.pdf.download') }}
						</a>
					</div>
					<div class="rounded border-l-2 border-[#c7d8ca] bg-transparent py-4 pl-4 pr-2 text-sm text-[#706f6c]">
						<p class="text-sm font-semibold text-[#1b1b18] dark:text-[#f5f5f0]">{{ __('recommendations.linkedin.title') }}</p>
						<p class="mt-1 text-sm font-medium">{{ __('recommendations.linkedin.author') }}</p>
						<p class="text-xs uppercase tracking-wide text-[#8b8a88]">{{ __('recommendations.linkedin.role') }}</p>
						<p class="mt-2 text-sm italic">{{ __('recommendations.linkedin.date') }}</p>
						<blockquote class="mt-3 border-l border-[#c9d7cb] pl-4 text-sm leading-6 text-[#4b504b] dark:border-[#3b4a3d] dark:text-[#d8e2d8]">
							{{ __('recommendations.linkedin.quote') }}
						</blockquote>
						<a href="https://www.linkedin.com/in/jérémy-escassut-57773427/" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex text-sm font-medium underline decoration-[#899f8a] underline-offset-4">{{ __('recommendations.linkedin.link') }}</a>
					</div>
				</div>
			</div>
		</section>

		<section id="stack" class="mb-8 scroll-mt-24" aria-label="{{ __('nav.stack') }}">
			<div class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-transparent px-6 py-3 backdrop-blur lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0">
				<h2 class="text-sm font-bold uppercase tracking-widest lg:sr-only">{{ __('nav.stack') }}</h2>
			</div>

			<!-- Simplified stack container: no green background, tighter gaps, centered icon tiles -->
			<div id="stack-icons" class="p-0 stack-grid">
				<div class="space-y-4 text-sm">
					<!-- Front group -->
					<div class="stack-group stack-group--front pl-4 sm:pl-5 lg:pl-6">
						<h3 class="text-sm font-semibold mb-2">{{ __('stack.front') }}</h3>
						<div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-6 gap-2">
							<div class="bg-white rounded flex items-center justify-center skill-angular skill-tile" aria-label="Angular" data-name="Angular">
								@php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-angular.svg')); } catch (Exception $e) { } @endphp
								@if($svg) {!! $svg !!} @endif
							</div>
							<div class="bg-white rounded flex items-center justify-center skill-react skill-tile" aria-label="React" data-name="React">
								@php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-react.svg')); } catch (Exception $e) { } @endphp
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
					<div class="stack-group stack-group--back pl-4 sm:pl-5 lg:pl-6">
						<h3 class="text-sm font-semibold mb-2">{{ __('stack.back') }}</h3>
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
							<div class="bg-white rounded flex items-center justify-center skill-postgresql skill-tile" aria-label="PostgreSQL" data-name="PostgreSQL">
								@php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-postgresql.svg')); } catch (Exception $e) { } @endphp
								@if($svg) {!! $svg !!} @endif
							</div>
							<div class="bg-white rounded flex items-center justify-center skill-mysql skill-tile" aria-label="MySQL" data-name="MySQL">
								@php $svg = ''; try { $svg = file_get_contents(resource_path('svg'.DIRECTORY_SEPARATOR.'icon-mysql.svg')); } catch (Exception $e) { } @endphp
								@if($svg) {!! $svg !!} @endif
							</div>
						</div>
					</div>

					<!-- Others group -->
					<div id="other" class="stack-group stack-group--other pl-4 sm:pl-5 lg:pl-6">
						<h3 class="text-sm font-semibold mb-2">{{ __('stack.other') }}</h3>
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
					&copy; {{ date('Y') }} Jérémy Escassut<br><a href="{{ route('mentions', ['locale' => app()->getLocale()]) }}" class="underline" aria-label="{{ __('legal.title') }}">{{ __('legal.title') }}</a><br><button type="button" class="underline" data-consent-open>{{ __('consent.manage') }}</button>
				</div>
				<div class="text-center lg:text-right text-xs text-[#8b8a88]">
					{{ __('footer.built_with') }}
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
				<div @click.away="closeModal()" class="w-full max-w-lg bg-white dark:bg-[#111] rounded-lg shadow-lg overflow-hidden" role="dialog" aria-modal="true" aria-label="{{ __('contact.form.title') }}" style="z-index: 10000; position: relative;">
					<div class="p-6">
						<div class="flex justify-between items-center mb-4">
							<h3 class="text-lg font-medium">{{ __('contact.form.title') }}</h3>
							<button @click="closeModal()" class="text-gray-500 hover:text-gray-700" aria-label="{{ __('ui.close') }}">✕</button>
						</div>

						<form method="POST" action="{{ route('contact.send', ['locale' => app()->getLocale()]) }}" class="space-y-4">
							@csrf
							{{-- flash messages moved to top-level so they are visible outside the modal after redirect --}}
							<!-- honeypot -->
							<input type="text" name="hp" tabindex="-1" autocomplete="off" style="display:none">

							<div>
								<label class="block text-sm">Nom (optionnel)</label>
								<input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded border px-3 py-2" placeholder="{{ __('contact.form.name_placeholder') }}">
							</div>

							<div>
								<label class="block text-sm">Votre email</label>
								<input x-ref="email" type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded border px-3 py-2" placeholder="{{ __('contact.form.email_placeholder') }}" required>
							</div>

							<div>
								<label class="block text-sm">Message</label>
								<textarea x-ref="message" name="message" rows="4" class="mt-1 block w-full rounded border px-3 py-2" placeholder="{{ __('contact.form.message_placeholder') }}" required>{{ old('message') }}</textarea>
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

<div id="cv-selector-modal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4" aria-hidden="true" x-cloak style="display: none;">
	<a href="#" class="absolute inset-0 bg-black/50" onclick="window.closeCvSelectorModal(); return false;" aria-label="{{ __('ui.close') }}"></a>
	<div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-lg dark:bg-[#111]" role="dialog" aria-modal="true" aria-label="{{ __('cv.view') }}">
		<div class="flex items-start justify-between gap-4">
			<div>
				<h3 class="text-lg font-semibold">{{ __('cv.view') }}</h3>
				<p class="mt-1 text-sm text-[#706f6c]">Choisissez une version du CV à ouvrir.</p>
			</div>
			<a href="#" onclick="window.closeCvSelectorModal(); return false;" class="text-gray-500 hover:text-gray-700" aria-label="{{ __('ui.close') }}">✕</a>
		</div>
		<div class="mt-6 flex flex-col gap-3">
			<a href="{{ asset('cv/ESCASSUT_JEREMY_-_CV_Développeur_Full_Stack_orienté_Produit_&_UX_Juillet_2026.pdf') }}"
				target="_blank" rel="noopener noreferrer"
				onclick="window.closeCvSelectorModal();"
				class="rounded border border-gray-200 px-4 py-3 text-left hover:bg-gray-50 dark:border-neutral-700 dark:hover:bg-neutral-800">
				<span class="block font-medium">CV Développeur Full Stack</span>
				<span class="mt-1 block text-sm text-[#706f6c]">Ouvre le PDF actuel dans un nouvel onglet</span>
			</a>
			<a href="{{ asset('cv/ESCASSUT_JEREMY_-_CV_Chef_de_projet_Coordination_internationale_Juillet_2026.pdf') }}"
				target="_blank" rel="noopener noreferrer"
				onclick="window.closeCvSelectorModal();"
				class="rounded border border-gray-200 px-4 py-3 text-left hover:bg-gray-50 dark:border-neutral-700 dark:hover:bg-neutral-800">
				<span class="block font-medium">CV Chef de projet</span>
				<span class="mt-1 block text-sm text-[#706f6c]">Ouvre une version coordination internationale</span>
			</a>
			<a href="{{ asset('cv/ESCASSUT_JEREMY_-_CV_Product_Owner_|_Transformation_digitale_Juillet_2026.pdf') }}"
				target="_blank" rel="noopener noreferrer"
				onclick="window.closeCvSelectorModal();"
				class="rounded border border-gray-200 px-4 py-3 text-left hover:bg-gray-50 dark:border-neutral-700 dark:hover:bg-neutral-800">
				<span class="block font-medium">CV Product Owner</span>
				<span class="mt-1 block text-sm text-[#706f6c]">Ouvre une version orientée transformation digitale et product</span>
			</a>
		</div>
	</div>
</div>

<!-- Mobile sticky buttons removed; mobile behavior relies on desktop action buttons and the global open-contact event -->

@endsection

@push('scripts')
<!-- Page scripts moved to resources/js/app.js -->
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
				// use location.replace to avoid creating extra history entry
				window.location.href = target;
			});
		});
	})();
</script>
@endpush