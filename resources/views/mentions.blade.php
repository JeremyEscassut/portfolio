@extends('layouts.app')

@section('title', 'Mentions légales')

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
    <h1 class="mt-2 text-2xl lg:text-3xl font-bold tracking-tight text-[#111] mb-4">Mentions légales</h1>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">Éditeur du site</h2>
        <p class="text-base text-[#222] leading-relaxed">
            Nom : <strong>Jérémy Escassut</strong><br>
            Statut : <strong>Particulier</strong><br>
            Adresse : 1801, route de Ridounel, 47510 Foulayronnes<br>
            E‑mail : <a class="underline decoration-dotted" href="mailto:j.losaint@gmail.com">j.losaint@gmail.com</a>
        </p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">Directeur de la publication</h2>
        <p class="text-base text-[#222] leading-relaxed">Directeur de la publication : <strong>Jérémy Escassut</strong> (responsable du contenu publié sur ce site).</p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">Hébergeur</h2>
        <p class="text-base text-[#222] leading-relaxed">
            Nom : <strong>Alwaysdata</strong><br>
            Siège social : 91 rue du Faubourg Saint Honoré - 75008 Paris<br>
            Site web : <a class="underline decoration-dotted" href="https://www.alwaysdata.com/fr/" target="_blank" rel="noopener">https://www.alwaysdata.com/fr/</a>
        </p>
    </section>

    <hr class="my-6">

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">Propriété intellectuelle</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">
            L’ensemble des contenus (textes, images, logos, code, maquettes, etc.) publiés sur ce site est protégé par le droit d’auteur.
            Toute reproduction, représentation, modification ou exploitation totale ou partielle est interdite sans autorisation écrite préalable de l’éditeur.
            Pour toute demande d’utilisation ou de reproduction : <a class="underline decoration-dotted" href="mailto:j.losaint@gmail.com">j.losaint@gmail.com</a>.
        </p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">Données personnelles (RGPD)</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">
            Responsable du traitement : <strong>Jérémy Escassut</strong> — contact : <a class="underline decoration-dotted" href="mailto:j.losaint@gmail.com">j.losaint@gmail.com</a>.<br>
            Finalités : gestion des demandes de contact transmises via le formulaire de contact (réponse aux messages reçus).
            <br>
            Base légale : traitement nécessaire à l'exécution de la demande de l'utilisateur.
            <br>
            Droits : vous pouvez demander l'accès, la rectification ou la suppression des données vous concernant en contactant : <a class="underline decoration-dotted" href="mailto:j.losaint@gmail.com">j.losaint@gmail.com</a>.
        </p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">Cookies</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">Le site lui‑même ne place pas de cookies. Aucune solution analytique tierce n'est activée par le site.
            L'hébergeur (<strong>Alwaysdata</strong>) collecte toutefois des données d'usage agrégées pour l'exploitation du service (par exemple : nombre de visites uniques par mois). Ces données sont agrégées et ne permettent pas d'identifier personnellement les visiteurs.
        </p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">Responsabilité</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">Les informations publiées sur ce site sont fournies à titre informatif. L’éditeur met en œuvre ses meilleurs efforts pour assurer l’exactitude et la mise à jour des contenus, mais ne peut garantir l’exhaustivité ni l’absence d’erreurs. L’éditeur décline toute responsabilité pour les dommages directs ou indirects résultant de l’accès ou de l’utilisation du site.</p>
    </section>

    <section class="mb-6 lg:mb-8">
        <h2 class="text-lg lg:text-xl font-semibold mt-2 mb-2 text-[#111]">Loi applicable et juridiction</h2>
        <p class="text-base text-[#222] leading-relaxed text-justify w-full">Les présentes mentions sont régies par le droit français. En cas de litige, compétence des tribunaux français compétents, sauf disposition impérative contraire.</p>
    </section>

    <p class="text-sm text-gray-500 mt-6">Dernière mise à jour : {{ date('d/m/Y') }}</p>
</main>
@endsection