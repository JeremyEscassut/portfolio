@extends('layouts.app')

@section('content')
<div class="prose lg:prose-lg p-6 bg-white dark:bg-[#161615] rounded-lg">
    <h1>Curriculum Vitae</h1>
    <p class="text-sm text-[#706f6c]">Version courte du CV — sections : Expériences, Formations, Compétences.</p>

    <section class="mt-6">
        <h2 class="font-medium">Expériences</h2>
        <ul class="mt-2 list-disc ml-6 text-sm text-[#706f6c]">
            <li>Entreprise A — Développeur (2020–2023)</li>
        </ul>
    </section>

    <section class="mt-4">
        <h2 class="font-medium">Formations</h2>
        <p class="text-sm text-[#706f6c]">Licence / Master en informatique (placeholder)</p>
    </section>
</div>

@endsection