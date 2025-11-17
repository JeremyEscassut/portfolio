@extends('layouts.app')

@section('title', __('legal.title'))

@section('content')
<!-- Back button for desktop (top-left) -->
<button type="button" onclick="history.back()" aria-label="Retour" class="hidden lg:flex items-center gap-2 fixed top-6 left-6 z-50 bg-white/90 dark:bg-[#0b0b0b]/90 border border-gray-200 dark:border-neutral-800 shadow-sm rounded-full px-3 py-2 text-sm text-[#1b1b18] hover:opacity-95">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="none" stroke="currentColor">
        <path d="M12 4L6 10l6 6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    <span class="sr-only">Retour</span>
</button>

<!-- Mobile bottom bar with return button (doesn't obstruct reading) -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 z-40">
    <div class="mx-auto max-w-4xl px-4 py-2 bg-white/85 dark:bg-black/70 backdrop-blur-sm border-t border-gray-200 dark:border-neutral-800">
        <div class="flex justify-center">
            <button type="button" onclick="history.back()" aria-label="Retour" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white dark:bg-[#0b0b0b] border border-gray-200 dark:border-neutral-800 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1b1b18] dark:text-white" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                    <path d="M12 4L6 10l6 6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>

<main class="prose prose-sm lg:prose-lg mx-auto p-6 pb-28 lg:pb-6 lg:max-w-4xl lg:px-8">
    <h1 class="mt-2 text-2xl lg:text-3xl font-bold tracking-tight text-[#111] mb-4">{{ __('legal.title') }}</h1>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">{{ __('mentions.editor.heading') }}</h2>
        <p class="text-base text-[#222] leading-relaxed">{!! nl2br(e(__('mentions.editor.content'))) !!}</p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">{{ __('mentions.publisher.heading') }}</h2>
        <p class="text-base text-[#222] leading-relaxed">{{ __('mentions.publisher.content') }}</p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">{{ __('mentions.host.heading') }}</h2>
        <p class="text-base text-[#222] leading-relaxed">{!! nl2br(e(__('mentions.host.content'))) !!}</p>
    </section>

    <hr class="my-6">

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">{{ __('mentions.ip.heading') }}</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">{!! nl2br(e(__('mentions.ip.content'))) !!}</p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">{{ __('mentions.data_protection.heading') }}</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">{!! nl2br(e(__('mentions.data_protection'))) !!}</p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">{{ __('mentions.cookies.heading') }}</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">{!! nl2br(e(__('mentions.cookies'))) !!}</p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">{{ __('mentions.responsibility.heading') }}</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">{!! nl2br(e(__('mentions.responsibility'))) !!}</p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">{{ __('mentions.law.heading') }}</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">{!! nl2br(e(__('mentions.law'))) !!}</p>
    </section>

    <p class="text-sm text-gray-500 mt-6">{{ __('mentions.last_updated') }} {{ date('d/m/Y') }}</p>
</main>
@endsection