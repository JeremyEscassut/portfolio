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

    (ou synchroniser `vendor/` si l'on choisit de déployer dépendances).

-   Référence : logs et présence du dossier `vendor/`.
-   Tags : infra | composer

9. `SQLSTATE[HY000]: General error: 1 no such table: cache` lors de php artisan cache:clear

-   Titre : Table cache manquante pour le store `database`
-   Contexte / reproduction : `php artisan cache:clear` renvoie une erreur SQL liée à la table `cache`. Reproduction : exécuter la commande sur l'environnement affecté.
-   Cause racine : `CACHE_STORE` configuré sur `database` alors que la table `cache` n'existe pas ou config mise en cache.
-   Solution :

    -   Option 1 (rapide) : forcer `CACHE_STORE=file` dans `.env` :

        sed -i.bak 's/^CACHE_STORE=.\*/CACHE_STORE=file/' .env || echo 'CACHE_STORE=file' >> .env
        php artisan config:clear
        php artisan view:clear

    -   Option 2 : créer la table cache :

        php artisan cache:table
        php artisan migrate --force

-   Référence : `.env` et commandes artisan associées.
-   Tags : infra | db

10. `.env` ou caches committés / écrasés par erreur

-   Titre : `.env` ou caches inclus/écrasés par erreur
-   Contexte / reproduction : `.env` ou fichiers de cache transférés/committés provoquent des problèmes de configuration. Reproduction : vérifier l'historique git/rsync qui inclut `.env`.
-   Cause racine : inclusion accidentelle de `.env` dans le dépôt ou lors d'un rsync.
-   Solution :

    -   Ajouter `.env` à `.gitignore` et retirer tout `.env` du dépôt (`git rm --cached .env`).
    -   Avant opérations sensibles, sauvegarder : `cp .env .env.bak-$(date +%Y%m%d-%H%M)`.

-   Référence : dépôt git et procédures de déploiement.
-   Tags : infra | security

11. Composer / installation des dépendances côté serveur

-   Titre : Dépendances `vendor/` absentes côté serveur
-   Contexte / reproduction : après rsync sans `vendor`, l'application manque de dépendances. Reproduction : vérifier l'absence du dossier `vendor/` sur le serveur et l'erreur retournée.
-   Cause racine : `vendor/` non synchronisé et `composer install` non exécuté sur le serveur.
-   Solution : exécuter `composer install --no-dev --optimize-autoloader` côté serveur après déploiement, ou inclure `vendor/` dans le déploiement si choisi.
-   Référence : dossier `vendor/` et logs d'erreur.
-   Tags : infra | composer

12. Permissions de `.env` et des fichiers de cache

-   Titre : Permissions incorrectes sur `.env` / `storage`
-   Contexte / reproduction : erreurs d'accès ou risques de sécurité suite à permissions incorrectes. Reproduction : `ls -la` et tests d'écriture sur `storage`.
-   Cause racine : permissions trop ouvertes ou mauvais propriétaire sur les fichiers sensibles.
-   Solution :

    chmod 600 .env
    chmod -R 775 storage bootstrap/cache
    vérifier le propriétaire (chown) selon l'utilisateur du serveur web.

-   Référence : vérification `ls -la`.
-   Tags : infra | security

13. Processus de rollback

-   Titre : Absence de procédure de rollback
-   Contexte / reproduction : nécessité de restaurer rapidement l'état précédent après une opération destructive.
-   Cause racine : aucune sauvegarde systématique avant opérations lourdes.
-   Solution : toujours créer des sauvegardes avant opérations critiques, exemple :

    cp .env .env.bak-$(date +%Y%m%d-%H%M)
  cp -r public/build public/build.bak-$(date +%Y%m%d-%H%M)

    et documenter la procédure de rollback.

-   Référence : scripts de sauvegarde.
-   Tags : infra | ops
