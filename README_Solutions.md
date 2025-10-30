# README_Solutions

Ce document récapitule, de façon concise et exploitable, les problèmes rencontrés durant le développement et le déploiement du projet Portfolio, ainsi que la solution minimale pour les résoudre ou les éviter.

But

-   Fournir une référence courte, lisible et actionnable pour se remettre rapidement sur pied en cas de régression.

Procédure de déploiement sûre (rappel rapide)

-   Builder localement, puis rsync depuis la machine de développement (Mac) vers le serveur :

```bash
# À lancer depuis votre Mac (racine du repo)
npm ci
npm run build
rsync -avz --delete \
  --exclude node_modules --exclude vendor --exclude .git \
  --exclude .env --exclude bootstrap/cache \
  ./ jeremyescassut@ssh1:/home/jeremyescassut/www/Portfolio/
ssh jeremyescassut@ssh1 'cd /home/jeremyescassut/www/Portfolio && php artisan view:clear && php artisan config:cache && php artisan route:cache && php artisan cache:clear'
```

-   Ne pas écraser `bootstrap/cache` ni `.env` via rsync. Faire des sauvegardes avant changements critiques.

Problèmes rencontrés (problème → solution concise)

1. Liens CV pointant sur `/cv/...` au lieu de `/portfolio/cv/...`

-   Cause : `asset()` générait des URLs sans le préfixe /portfolio car `APP_URL` serveur était incorrect; certaines vues contenaient aussi des chemins codés en dur.
-   Correction : définir `APP_URL="https://jeremyescassut.alwaysdata.net/portfolio"` dans le `.env` serveur, vider les caches (php artisan config:clear, view:clear) et corriger les occurrences codées en dur (remplacer `href="/cv/` par `href="/portfolio/cv/` ou mieux utiliser `asset('cv/...')`).

2. Fichiers publics manquants (ex. `public/png/JE_portfolio.png`) → 404

-   Cause : fichiers restés dans `resources/` et non copiés dans `public/` sur le serveur.
-   Correction : copier le fichier dans `public/png/` (ou inclure `public/` lors du rsync). Exemple : `cp resources/png/JE_portfolio.png public/png/JE_portfolio.png`.

3. CSS inline dans Blade (layouts) — maintenance et duplication

-   Cause : styles sensibles au contexte (dark mode, dialog) placés inline dans `app.blade.php` rendant le build et la maintenance plus difficiles.
-   Correction : centraliser dans `resources/css/app.css` (fin du fichier), conserver `@stack('styles')` pour overrides minimes ; rebuild assets.

4. Avertissements Vite / PostCSS sur sélecteurs littéraux (ex. `.dark\:bg-\[\#111\]`)

-   Cause : sélecteurs utilitaires Tailwind littéraux dans des sélecteurs complexes provoquent des warnings du parseur.
-   Solution simple : garder (ce sont des warnings, pas bloquants) ou simplifier/échapper les sélecteurs si nécessaire.

5. Remplacement/écrasement de la clé SSH par défaut — perte d'accès Git

-   Cause : génération d'une nouvelle `~/.ssh/id_ed25519` pour Alwaysdata a écrasé la clé utilisée pour GitLab.
-   Correction : créer une clé SSH dédiée pour GitLab (`~/.ssh/id_ed25519_gitlab`) et ajouter la clé publique dans GitLab, ou basculer à HTTPS pour `git push`.

6. Rsync lancé depuis le serveur (copie serveur→serveur) au lieu de la machine locale

-   Cause : confusion entre shells local et distant.
-   Correction : lancer rsync depuis la machine qui contient la source (local). Vérifier la source/destination avant de lancer.

7. Ecrasement des caches en `bootstrap/cache` → erreurs (providers manquants)

-   Cause : rsync a écrasé `bootstrap/cache/*.php` avec des fichiers générés localement qui référencent un état différent (ex. providers non installés côté serveur).
-   Solution : supprimer les fichiers `bootstrap/cache/*.php` sur le serveur, puis régénérer caches/autoload après s'être assuré que `vendor/` et `.env` sont cohérents. Eviter d'inclure `bootstrap/cache` dans le rsync.

8. `Class "Laravel\Pail\PailServiceProvider" not found` (provider manquant)

-   Cause : autoload/cache bootstrap incohérent par rapport à `vendor/` (dépendances manquantes ou autoload non généré).
-   Correction : exécuter `composer install --no-dev --optimize-autoloader --prefer-dist` et `composer dump-autoload -o` sur le serveur, ou synchroniser `vendor/` si l'on choisit de déployer avec toutes les dépendances.

9. `SQLSTATE[HY000]: General error: 1 no such table: cache` lors de php artisan cache:clear

-   Cause : config de cache pointant sur `database` (table `cache`) alors que la table n'existe pas, ou config mise en cache différente.
-   Correction rapide : définir `CACHE_STORE=file` dans `.env` (ou créer la table via `php artisan cache:table` + `php artisan migrate --force`). Toujours supprimer `bootstrap/cache/config.php` avant recacher la config si corrompu.

10. `.env` ou caches committés / écrasés par erreur

-   Cause : `.env` transféré par rsync ou committé par mégarde.
-   Solution : ajouter `.env` à `.gitignore`, ne jamais push `.env`. Sur serveur, faire une sauvegarde `.env.bak-YYYYMMDD-HHMM` avant écrasement.

11. Composer/installation des dépendances côté serveur

-   Cause : rsync sans `vendor` nécessite d'appeler `composer install` sur le serveur.
-   Correction : soit inclure `vendor` dans le déploiement (peu recommandé), soit exécuter `composer install` sur le serveur après rsync. Vérifier la mémoire/timeouts hébergeur.

12. Permissions de `.env` et des fichiers de cache

-   Cause : permissions trop ouvertes ou incorrectes sur `.env` (risque de sécurité) ou dossiers `storage`/`bootstrap/cache` incorrectes.
-   Correction : `chmod 600 .env`, `chmod -R 775 storage bootstrap/cache` (ajuster selon l'hôte), vérifier l'utilisateur propriétaire.

13. Processus de rollback

-   Chaque opération lourde (rsync, modification .env, suppression bootstrap/cache) doit être précédée d'une sauvegarde (`cp ... .bak-$(date ...)`) pour rollback rapide.

Fichier de référence (raccourci de réparation rapide)

-   Nettoyer caches corrompus :

```bash
cd /home/jeremyescassut/www/Portfolio
rm -f bootstrap/cache/*.php
php artisan config:clear
php artisan cache:clear || true
php artisan view:clear
# si vendor ok
composer dump-autoload -o
php artisan config:cache
php artisan route:cache
```

-   Forcer store file si table cache manquante :

```bash
# modifier .env
sed -i.bak 's/^CACHE_STORE=.*/CACHE_STORE=file/' .env || echo 'CACHE_STORE=file' >> .env
php artisan config:clear
php artisan view:clear
```

Notes & bonnes pratiques (rapide)

-   Toujours builder les assets localement (vite) puis déployer `public/build` et `resources/` via rsync depuis la machine de dev.
-   Ne pas écraser `.env` ni `bootstrap/cache` via rsync. Exclure dans la commande.
-   Garder une clé SSH dédiée par service (gitlab, alwaysdata) et configurer `~/.ssh/config` pour les alias.
-   Préparer un script `deploy.sh` (build + rsync + clear caches) et le conserver hors dépôt (ou dans `scripts/` en local).

---

Si tu veux, je peux :

-   créer le fichier `scripts/deploy.sh` dans le repo (local) avec la commande rsync sûre et les étapes de cache à lancer sur le serveur (il restera à l'exécuter depuis ta machine),
-   ou commiter `README_Solutions.md` et pousser la branche pour toi (indique la branche).

Fin de la synthèse.

---

-   Titre : Avast / extension Chrome bloquant l'envoi des mails (false positive)
-   Date : 2025-10-29
-   Contexte : tests de formulaire de contact en production. Les envois semblaient échouer côté client avec des erreurs console `MessageNotSentError` et `runtime.lastError: The message port closed before a response was received`.
-   Cause : extension Avast/secure browser extension sur le poste de test (Chrome) injectait un script de gestion des cookies/privacité qui bloquait ou interrompait les appels réseau nécessaires à l'upload des formulaires (fausse alerte côté client). Les logs serveur contenaient aussi des traces montrant des références à Mailgun — il s'agissait d'une configuration legacy/ancienne qui n'est plus utilisée en production.
-   Solution :
    -   Vérifier côté serveur que la configuration `.env` et la config Laravel sont correctes (APP_URL, MAIL_MAILER, MAIL_HOST...).
    -   Vider les caches Laravel (depuis le dossier du projet sur le serveur) :

```bash
php artisan config:clear
php artisan cache:clear || true
php artisan view:clear
php artisan config:cache
php artisan route:cache
```

-   Tester l'envoi depuis la machine cliente sans extensions (nouvel onglet incognito sans extension) ou désactiver temporairement l'extension Avast. Après désactivation, le formulaire s'est soumis correctement et les mails ont bien été redirigés vers `j.losaint@gmail.com`.
-   Mesure préventive : documenter dans ce fichier l'incident et ajouter un court guide de debug client/serveur (console, network, logs Laravel) — ci‑dessous.

-   Checklist debug (client) :

    -   Ouvrir DevTools (Console + Network) et surveiller les erreurs JS et les requêtes POST (status, location, cookies).
    -   Tester en mode Incognito sans extensions.
    -   Si erreurs liées à la communication avec une extension, désactiver l'extension et retester.

-   Checklist debug (serveur) :
    -   Vérifier `storage/logs/laravel.log` pour exceptions (ex: Mailer [mailgun] is not defined). Si des traces Mailgun apparaissent, c'est probablement un vestige de configuration précédente — vérifie `MAIL_MAILER` dans `.env` et la présence de `services.mailgun` dans `config/services.php` si tu comptes utiliser Mailgun à nouveau.
    -   Vérifier `bootstrap/cache/config.php` — supprimer si corrompu et régénérer la config si nécessaire.
    -   S'assurer que `MAIL_MAILER=smtp` (ou `log` pour debug) et que `MAIL_HOST`/`MAIL_PORT` correspondent au provider (Alwaysdata : `smtp-jeremyescassut.alwaysdata.net`, port 587 pour STARTTLS, 465 pour SSL direct).

Référence : ticket local, test manuel 2025-10-29. Redirection confirmée vers `j.losaint@gmail.com`.
