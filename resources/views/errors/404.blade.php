{{-- Page not found (unknown address). No route matched, so SetLocale did not run:
     the language comes from the first URL segment (/en/...), French otherwise. --}}
@php
app()->setLocale(request()->segment(1) === 'en' ? 'en' : 'fr');
@endphp
@extends('layouts.app')

@section('title', __('errors.404.title'))
@section('noindex', '1')

@section('content')
<main class="mx-auto max-w-xl px-6 py-24 text-center">
    <p class="text-sm font-semibold tracking-wide" style="color: var(--pf-action)">404</p>
    <h1 class="mt-2 text-2xl lg:text-3xl font-bold tracking-tight">{{ __('errors.404.title') }}</h1>
    <p class="mt-4">{{ __('errors.404.text') }}</p>
    <p class="mt-8">
        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="pf-btn pf-btn--primary">{{ __('errors.404.home') }}</a>
    </p>
</main>
@endsection
