@extends('layouts.app')

@section('title', 'Téléchargez le CV ci-dessous')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow p-6">
        <h1 class="text-2xl font-semibold mb-2">Curriculum Vitae</h1>
        <p class="text-sm text-[#706f6c] mb-4">Vous pouvez consulter ou télécharger mon CV au format PDF.</p>

        <div class="flex gap-3 mb-6">
            <a href="{{ asset('cv/CV_Dev-FS_2025-10-24_Jeremy_Escassut.pdf') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 bg-[#1b1b18] text-white rounded">Ouvrir le CV (nouvel onglet)</a>
            <a href="{{ asset('cv/CV_Dev-FS_2025-10-24_Jeremy_Escassut.pdf') }}" download class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded">Télécharger le CV</a>
        </div>

        <div class="border rounded overflow-hidden" style="height:70vh;">
            <iframe src="{{ asset('cv/CV_Dev-FS_2025-10-24_Jeremy_Escassut.pdf') }}" title="Aperçu du CV" class="w-full h-full" aria-label="Aperçu du CV en PDF"></iframe>
        </div>

        <p class="text-xs text-[#706f6c] mt-3">Si le PDF ne s'affiche pas dans l'aperçu, utilisez le bouton "Télécharger le CV" ou ouvrez-le dans un nouvel onglet.</p>
    </div>
</div>

@endsection