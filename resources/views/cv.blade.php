@extends('layouts.app')

@section('title', __('cv.page_title'))

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow p-6">
        <h1 class="text-2xl font-semibold mb-2">{{ __('cv.heading') }}</h1>
        <p class="text-sm text-[#706f6c] mb-4">{{ __('cv.intro') }}</p>

        <div class="flex gap-3 mb-6">
            <a href="{{ asset('cv/ESCASSUT_JEREMY_-_CV_Développeur_Full_Stack_orienté_Produit_&_UX_Juillet_2026.pdf') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 bg-[#1b1b18] text-white rounded">{{ __('cv.open_new_tab') }}</a>
            <a href="{{ asset('cv/ESCASSUT_JEREMY_-_CV_Développeur_Full_Stack_orienté_Produit_&_UX_Juillet_2026.pdf') }}" download class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded">{{ __('cv.download') }}</a>
        </div>

        <div class="border rounded overflow-hidden" style="height:70vh;">
            <iframe src="{{ asset('cv/ESCASSUT_JEREMY_-_CV_Développeur_Full_Stack_orienté_Produit_&_UX_Juillet_2026.pdf') }}" title="{{ __('cv.preview') }}" class="w-full h-full" aria-label="{{ __('cv.preview_pdf') }}"></iframe>
        </div>

    </div>
</div>

@endsection