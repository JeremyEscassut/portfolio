# README_Solutions

Ce document récapitule, de façon concise et exploitable, les problèmes rencontrés durant le développement et le déploiement du projet Portfolio, ainsi que la solution minimale pour les résoudre ou les éviter selon le template suivant :

-   Titre : court et explicite
-   Date : (omit si non connu)
-   Contexte / reproduction : comment reproduire
-   Cause racine : diagnostic bref
-   Solution : commandes, patches, fichiers modifiés, justification
-   Référence : commit SHA, PR, fichier(s) modifié(s) si disponibles
-   Tags : bug | infra | tests | perf | doc | build | sécurité | autre

==================================================

1. Liens CV pointant sur `/cv/...` au lieu de `/portfolio/cv/...`

-   Titre : Liens CV sans préfixe `/portfolio`
-   Contexte / reproduction : en production, certains liens vers le CV génèrent des URLs sans le préfixe `/portfolio`. Reproduction : ouvrir la page d'accueil en production et inspecter les liens CV.
-   Cause racine : `APP_URL` serveur mal configuré et/ou occurrences de chemins codés en dur dans les vues.
-   Solution : mettre à jour `APP_URL` sur le serveur (ex. `APP_URL="https://jeremyescassut.alwaysdata.net/portfolio"`), vider les caches (`php artisan config:clear && php artisan view:clear`) et corriger les occurrences codées en dur (préférer `asset('cv/...')`).
-   Référence : vues modifiées, configuration serveur `.env`.
-   Tags : bug | infra | doc

2. Fichiers publics manquants (ex. `public/png/JE_portfolio.png`) → 404

-   Titre : Ressources publiques manquantes
-   Contexte / reproduction : ressources référencées depuis `public/` renvoient 404 en production (ex. images). Reproduction : accéder à l'URL directe d'une image manquante.
-   Cause racine : fichiers restés dans `resources/` et non copiés dans `public/` avant le déploiement.
-   Solution : copier les fichiers nécessaires dans `public/` ou inclure `public/` lors du rsync. Exemple : `cp resources/png/JE_portfolio.png public/png/JE_portfolio.png`.
-   Référence : chemins de fichiers (ex. `public/png/JE_portfolio.png`).
-   Tags : bug | infra

3. CSS inline dans Blade (layouts) — maintenance et duplication

-   Titre : Styles inline dans Blade
-   Contexte / reproduction : styles (dark mode, dialogues) présents inline dans les templates rendent la maintenance plus difficile.
-   Cause racine : styles contextualisés non centralisés, duplication.
-   Solution : déplacer les styles vers `resources/css/app.css` et utiliser `@stack('styles')` pour overrides minimes ; rebuild assets.
-   Référence : templates Blade (ex. `resources/views/layouts/app.blade.php`) et `resources/css/app.css`.
-   Tags : maintenance | css | build

4. Avertissements Vite / PostCSS sur sélecteurs littéraux

-   Titre : Warnings PostCSS/Vite pour sélecteurs littéraux Tailwind
-   Contexte / reproduction : warnings lors du build Vite/PostCSS pour des sélecteurs littéraux (ex. `.dark\:bg-\[\#111\]`). Reproduction : lancer `npm run build` et observer la sortie.
-   Cause racine : utilisation de sélecteurs utilitaires littéraux dans des sélecteurs complexes que le parseur signale.
-   Solution : ces warnings ne sont pas bloquants ; si souhaité, simplifier ou échapper les sélecteurs problématiques.
-   Référence : logs de build Vite/PostCSS.
-   Tags : build | tooling

5. Remplacement/écrasement de la clé SSH par défaut — perte d'accès Git

-   Titre : Clé SSH écrasée — accès Git perdu
-   Contexte / reproduction : `git push` échoue après la génération d'une nouvelle clé SSH locale. Reproduction : tenter un push depuis la machine affectée.
-   Cause racine : génération d'une nouvelle clé SSH qui a écrasé la clé utilisée pour GitLab.
-   Solution : générer une clé dédiée pour GitLab (ex. `~/.ssh/id_ed25519_gitlab`) et ajouter la clé publique dans le compte GitLab, ou utiliser HTTPS pour Git.
-   Référence : configuration SSH locale (`~/.ssh/`).
-   Tags : infra | security

6. Rsync lancé depuis le serveur (copie serveur→serveur) au lieu de la machine locale

-   Titre : Rsync exécuté depuis la mauvaise machine
-   Contexte / reproduction : synchronisation effectuée depuis le serveur au lieu de la machine de dev, causant transferts inversés.
-   Cause racine : confusion entre shell local et distant lors de l'exécution de la commande.
-   Solution : lancer rsync depuis la machine contenant la source (dev), vérifier `source` et `destination` avant exécution.
-   Référence : commandes rsync exécutées dans les sessions shell.
-   Tags : infra | deploy

7. Écrasement des caches en `bootstrap/cache` → erreurs (providers manquants)

-   Titre : `bootstrap/cache` synchronisé — erreurs d'autoload
-   Contexte / reproduction : erreurs 500 et stacktraces pointant vers `bootstrap/cache/*.php` après déploiement. Reproduction : inspecter `bootstrap/cache` sur le serveur après rsync.
-   Cause racine : fichiers de cache générés localement synchronisés sur le serveur, incompatibles avec l'état du serveur (vendor, .env).
-   Solution : supprimer les fichiers `bootstrap/cache/*.php` sur le serveur puis régénérer l'autoload et caches :

    rm -f bootstrap/cache/\*.php
    composer dump-autoload -o
    php artisan config:clear
    php artisan cache:clear || true
    php artisan view:clear
    php artisan config:cache
    php artisan route:cache

-   Référence : dossier `bootstrap/cache` sur le serveur.
-   Tags : infra | cache

8. `Class "Laravel\\Pail\\PailServiceProvider" not found` (provider manquant)

-   Titre : Provider manquant après déploiement
-   Contexte / reproduction : erreur d'autoload en accédant à l'application (provider introuvable). Reproduction : charger l'app et consulter les logs.
-   Cause racine : `vendor/` ou autoload incomplet / incohérent.
-   Solution : exécuter sur le serveur :

    composer install --no-dev --optimize-autoloader --prefer-dist
    composer dump-autoload -o

    ## README_Solutions

    Ce fichier recense les changements et correctifs récents appliqués au projet Portfolio (front-end + environnement local). Le format suit : Titre / Contexte / Cause racine / Solution / Fichiers modifiés / Tests.

    ***

    ## 1) Transition photo : crossfade GPU (opacité + blur)

    -   Contexte : la transition entre photo originale et version IA utilisait soit un dissolve per-pixel coûteux, soit un scale visible qui agrandissait temporairement l'image.
    -   Cause racine : animation par `transform: scale()` et/ou opérations pixel lourdes.
    -   Solution :
        -   Remplacement par un overlay canvas crossfade basé sur `opacity` + `filter: blur()` (420ms, courbe cubic-bezier).
        -   L'overlay est dessiné au même buffer (attribut `width`/`height`) que le canvas principal et reçoit une taille CSS identique (px) pour garantir l'absence de débordement.
        -   Écoute `transitionend` sur `opacity` et `filter` côté JS.
    -   Fichiers modifiés :
        -   `resources/js/photoToggle.js`
        -   `resources/css/app.css`
    -   Test rapide : démarrer Vite (`npm run dev`) et cliquer sur la photo — la transition est fluide sans agrandissement.

    ***

    ## 2) Cadre décoratif non-intrusif + légende

    -   Contexte : ajout d'un cadre autour du canvas qui ne doit pas modifier la mise en page ni masquer la légende.
    -   Cause racine : premiers essais modifiaient le flow ou la légende était trop proche du rim.
    -   Solution :
        -   Cadre réalisé via `::before` et `::after` sur `.photo-frame` (inner rim & outer rim) pour ne pas changer les dimensions du contenu.
        -   `#photoCanvas` garde `z-index` inférieur ; overlay est placé entre canvas et rim.
        -   Décalage de la légende : `.photo-frame + #photoCaption { margin-top: calc(2.5vmin + 0.75rem); }`.
    -   Fichiers modifiés : `resources/css/app.css`, `resources/views/home.blade.php` (classe `photo-frame` appliquée au wrapper).

    ***

    ## 3) Suppression du reflow à l'ajout de la classe de transition

    -   Contexte : ajout dynamique de la classe `.photo-transition-wrap` provoquait précédemment un décalage parce que la règle forçait `display:inline-block`.
    -   Solution : suppression de `display:inline-block` pour conserver le `display` d'origine du conteneur (flex), évitant ainsi tout reflow au clic.
    -   Fichier modifié : `resources/css/app.css`.

    ***

    ## 4) Robustesse de l'overlay : pixel-perfect sizing

    -   Contexte : pendant la transition, les images pouvaient momentanément afficher au format d'origine (débordement) si le CSS du canvas différait du buffer.
    -   Solution :
        -   Append de l'overlay dans le même parent que le canvas.
        -   Lecture de `canvas.offsetWidth/offsetHeight` pour fixer la taille CSS de l'overlay en px.
        -   Alignement du buffer de l'overlay (`overlay.width/height = canvas.width/height`).
    -   Fichier modifié : `resources/js/photoToggle.js`.

    ***

    ## 5) Corrections environnement local (PATH, VS Code terminal)

    -   Contexte : `node`/`npm` non détectés dans certaines sessions zsh du terminal intégré.
    -   Solution :

        -   Ajout d'un export PATH local dans `~/.zshenv` (et synchronisation dans `~/.zshrc`/`~/.zprofile`) :

            export PATH="/usr/local/bin:$PATH"

        -   Ajout d'un fichier `.vscode/settings.json` au workspace pour lancer le terminal intégré en login shell (zsh -l) afin que ces variables soient prises en compte.

    Note : ces changements sont propres à la machine de dev (fichiers de profil) et non obligatoirement commités dans le repo.

    ***

    ## 6) Commandes utiles

    -   Lancer l'environnement de développement (HMR) :

    ```bash
    npm run dev
    # ou (si défini) : npm run dev:all
    ```

    -   Si `php artisan serve` échoue (port 8000 occupé) :

    ```bash
    php artisan serve --port=8001
    # ou tuer le PID : lsof -i :8000 && kill <PID>
    ```

    ***

    ## 7) Liste courte des fichiers touchés

    -   `resources/js/photoToggle.js` — overlay/dessin/transition handling
    -   `resources/css/app.css` — transition (opacity+blur), `.photo-frame` pseudo-el, caption offset, suppression du display override
    -   `resources/views/home.blade.php` — wrapper `photo-frame` (appliquer la classe au bon élément)
    -   `.vscode/settings.json` — réglage terminal login shell (workspace)

    ***

    ## 8) Tests recommandés

    1. Démarrer `npm run dev` puis ouvrir la page locale.
    2. Cliquer sur la photo : vérifier absence d'agrandissement, cadre visible, légende bien placée.
    3. Vérifier HMR en modifiant `resources/css/app.css`.

    ***

    Si tu veux, je peux :

    -   committer ces modifications sur une branche dédiée (par ex. `fix/photo-transition-frame`) et ouvrir une PR ;
    -   augmenter/diminuer l'intensité du flou (`filter: blur(2px)`) ou la durée de transition (actuelle : 420ms) ;
    -   générer un screenshot headless pour vérifier le rendu sur plusieurs largeurs.

    ***

    Dernière mise à jour : 2025-11-25
    require **DIR** . '/vendor/autoload.php';
    use Illuminate\Support\Facades\Mail;
    $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        Mail::raw('Test SMTP', function($message){
    $message->to('j.losaint@gmail.com')->subject('Test SMTP');
    });
    echo "Mail envoyé (vérifier logs)\n";
    `
    `bash
    php scripts/send_test_mail.php
    tail -n 200 storage/logs/laravel.log
    ```

8. CSRF 419 — headless (exemple curl correct)

    ```bash
    curl -c cookies.txt 'https://<votre-site>/fr/contact' -o contact.html
    TOKEN=$(grep -oP 'name=\"_token\" value=\"\K[^\"]+' contact.html)
    curl -b cookies.txt -c cookies.txt -X POST 'https://<votre-site>/fr/contact' \
      -F "_token=${TOKEN}" -F "name=Test" -F "email=test@example.com" -F "message=hello"
    ```

9. Nettoyage post-opération
    ```bash
    php artisan optimize:clear
    # documenter & commit changes on feature branch (non main)
    ```

### Résultats observés après corrections

-   Suppression de la boucle Apache → `curl -I -L https://.../fr` → HTTP 200.
-   Pages localisées `/fr/mentions` et `/en/mentions` accessibles.
-   SMTP accepté par l'hôte après correction du mot de passe ; vérifier boîte de réception.
-   Formulaire de contact : fonctionne depuis navigateur. Headless nécessite gestion cookies/session.

### Pourquoi ces changements résolvent le problème

-   `bootstrap/cache` contient du code dépendant de l'environnement → le supprimer et regénérer évite les incohérences.
-   `.env` correctement parsé garantit APP*KEY, MAIL*\_, DB\_\_ disponibles → sinon bootstrap casse.
-   Corriger `RewriteBase` / règles `.htaccess` stoppe la boucle Apache.
-   Centraliser `window.APP_BASE` évite hacks JS fragiles et rend le comportement indépendant du sous‑chemin d'hébergement.

### Recommandations opérationnelles / préventives

1. Ne jamais rsync / committer `bootstrap/cache/*.php` ou `vendor/` sans précautions. Préférer `composer install` côté serveur.
2. Checklist de déploiement : backup `.env`, `public/build`, git bundle ; build local ; rsync `public/build` ; `composer dump-autoload` ; `php artisan config:cache`.
3. Mettre `.env` dans `.gitignore`.
4. Documenter tests post‑deploy (vérifier `/fr`, `/fr/mentions`, tester contact via navigateur).
5. CI / script de déploiement : nettoyer `bootstrap/cache`, `composer install --no-dev --optimize-autoloader`, migrations, recréer caches.
6. Garder un script d'envoi de mail de test et accès rapide aux logs (`tail -f storage/logs/laravel.log`).

### Rollback rapide

```bash
git bundle unbundle portfolio-backup-YYYYMMDD-HHMM.bundle
tar -xzf Portfolio-backup-YYYYMMDD-HHMM.tgz -C /path/to/restore
cp .env.bak-YYYYMMDD-HHMM .env
composer dump-autoload
php artisan config:clear
```

### Références git & commits

-   Branche : `feature/deploy-alwaysdata-20251121_123632` (correctifs front & cache).
-   Exemple pour afficher commits :

```bash
git fetch origin
git checkout feature/deploy-alwaysdata-20251121_123632
git log --oneline -n 10
```

---

Si tu veux que je remplace la section 14 directement dans `README_Solutions.md` et que je committe sur la branche `feature/deploy-alwaysdata-20251121_123632`, confirme et je l'appliquerai.
