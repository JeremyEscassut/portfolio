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

## 14 — Correction déploiement production : canonicalisation des routes, .env, SMTP et .htaccess

**Titre** : Correction déploiement production & contact (locale, .env, SMTP, .htaccess)  
**Date** : 2025-11-21

### Contexte / reproduction

En production, plusieurs symptômes simultanés empêchaient le site de fonctionner correctement :

-   Pages renvoyant 500 (erreurs PHP / bootstrap cache incompatibles).
-   Boucle interne d'Apache déclenchant l'erreur "Request exceeded the limit of 10 internal redirects".
-   Formulaire de contact renvoyant une erreur 419 "Page Expired" lorsqu'un agent headless tentait un POST.
-   Tentatives d'envoi d'email échouant (erreurs SMTP 535 ou connexion vers 127.0.0.1:1025).
-   Logs Laravel mentionnant un parse error sur `.env` (ligne `MAIL_PASSWORD`) et une erreur SQL `no such table: cache` quand `CACHE_STORE=database`.

Reproduction rapide (diagnostic) :

-   `curl -I -L https://<votre-site>/fr` → vérifier redirections excessives.
-   Consulter `storage/logs/laravel.log` pour entrées Dotenv / SQL / Mailer.
-   GET `/fr/contact`, récupérer `_token` + cookies, POST headless → 419.

### Logs / preuves (extraits représentatifs)

-   Dotenv parse error :
    `"Failed to parse dotenv file. Encountered an invalid name at ["MAIL_PASSWORD"]"`
-   SQLite / cache :
    `"SQLSTATE[HY000]: General error: 1 no such table: cache"`
-   Apache :
    `"Request exceeded the limit of 10 internal redirects due to probable configuration error."`
-   Mailer :
    `"Connection could not be established with host '127.0.0.1:1025': Connection refused"`  
    `"535 Incorrect authentication data"`

### Diagnostic (cause racine)

1. Fichiers de cache (`bootstrap/cache/*.php`) générés localement synchronisés sur le serveur → incohérences d'autoload / providers.
2. `.env` mal formé (guillemets non échappés autour de `MAIL_PASSWORD`) → échec du parsing dotenv → config cassée.
3. `CACHE_STORE=database` sans table `cache` → erreurs SQL.
4. Règles Apache (`RewriteBase /portfolio/` ou redirection racine → `/portfolio`) + `.htaccess` imbriqués → boucle interne.
5. JS front fragile : `path.replace(/^\/portfolio/i, '')` (hardcode) → comportement incorrect selon base path d'hébergement.
6. CSRF 419 en headless : mismatch session/cookie entre GET (token) et POST.

### Actions appliquées (étapes & commandes)

> Sauvegardes (avant toute opération)

```bash
git bundle create portfolio-backup-$(date +%Y%m%d-%H%M).bundle --all
tar -czf Portfolio-backup-$(date +%Y%m%d-%H%M).tgz .
cp .env .env.bak-$(date +%Y%m%d-%H%M)
```

1. Corriger `.env` côté serveur

    - Vérifier et retirer guillemets non échappés, valider `MAIL_PASSWORD`.
    - Exemple SMTP :
        ```
        MAIL_MAILER=smtp
        MAIL_HOST=smtp-jeremyescassut.alwaysdata.net
        MAIL_PORT=587
        MAIL_USERNAME=contact@jeremyescassut.com
        MAIL_PASSWORD=***REMOVED***
        MAIL_ENCRYPTION=tls
        ```
    - Puis :
        ```bash
        php artisan config:clear
        php artisan view:clear
        ```

2. Forcer `CACHE_STORE=file` (solution rapide) ou créer la table `cache`

    - Rapidement :
        ```bash
        sed -i.bak 's/^CACHE_STORE=.*/CACHE_STORE=file/' .env || echo 'CACHE_STORE=file' >> .env
        php artisan config:clear
        php artisan view:clear
        ```
    - Si on garde `database` :
        ```bash
        php artisan cache:table
        php artisan migrate --force
        ```

3. Supprimer caches incompatibles et régénérer

    ```bash
    rm -f bootstrap/cache/*.php
    composer dump-autoload -o
    php artisan config:clear
    php artisan cache:clear || true
    php artisan view:clear
    php artisan config:cache
    php artisan route:cache
    ```

4. Corriger `.htaccess` Apache (backup avant)

    ```bash
    cp public/.htaccess public/.htaccess.bak-$(date +%Y%m%d-%H%M)
    # s'assurer que RewriteBase / et pas de redirection racine -> /portfolio
    # tester:
    curl -I -L https://<votre-site>/fr
    ```

5. Frontend — canonicaliser le changement de langue

    - Injecter base path côté serveur (layout Blade) :
        ```blade
        <script>window.APP_BASE = "{{ rtrim(request()->getBasePath(), '/') }}";</script>
        ```
    - Remplacer `path.replace(/^\/portfolio/i, '')` par une fonction `buildNewPath(newLocale)` qui retire `window.APP_BASE` en échappant correctement la chaîne pour RegExp.
    - Centraliser le JS dans `resources/js/app.js`.

6. Déployer assets correctement

    - Build local :
        ```bash
        npm run build
        ```
    - Rsync (exemple) :
        ```bash
        rsync -av --delete public/build/ user@server:/home/username/www/Portfolio/public/build/
        ```
    - Côté serveur : régénérer autoload & caches (voir étape 3).

7. Vérification mail et contact

    - Depuis navigateur : tester `/fr/contact` → remplir → vérifier boîte.
    - Test côté serveur (script) si nécessaire :
        ```php
        // scripts/send_test_mail.php
        <?php
        require __DIR__ . '/vendor/autoload.php';
        use Illuminate\Support\Facades\Mail;
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        Mail::raw('Test SMTP', function($message){
            $message->to('j.losaint@gmail.com')->subject('Test SMTP');
        });
        echo "Mail envoyé (vérifier logs)\n";
        ```
        ```bash
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
-   `.env` correctement parsé garantit APP*KEY, MAIL*_, DB\__ disponibles → sinon bootstrap casse.
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
