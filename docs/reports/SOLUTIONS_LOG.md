# SOLUTIONS_LOG - Portfolio

Ce fichier centralise les solutions appliquees au projet, au format court et reutilisable.

## Template d'entree

- Titre:
- Date:
- Contexte:
- Cause:
- Solution:
- References:
- Tags:

---

## 1) Liens CV sans prefixe `/portfolio`

- Date: 2025-11-21
- Contexte: en production, certains liens CV pointaient vers `/cv/...` au lieu de `/portfolio/cv/...`.
- Cause: `APP_URL` serveur incorrect et chemins potentiellement codes en dur.
- Solution: corriger `APP_URL`, vider caches Laravel, forcer `asset('cv/...')` dans les vues.
- References: `resources/views/*`, configuration serveur `.env`.
- Tags: bug | infra | doc

## 2) Ressources publiques manquantes (404)

- Date: 2025-11-21
- Contexte: fichiers images references renvoyaient 404 en prod.
- Cause: assets presents dans `resources/` mais absents de `public/` au deploy.
- Solution: copier les assets necessaires dans `public/` et verifier la strategie rsync.
- References: `public/png/*`.
- Tags: bug | infra

## 3) Caches `bootstrap/cache` incoherents apres deploy

- Date: 2025-11-21
- Contexte: erreurs 500/autoload apres synchronisation.
- Cause: fichiers de cache generes localement deployes sur serveur.
- Solution: supprimer `bootstrap/cache/*.php`, regenerer autoload et caches cote serveur.
- References: `scripts/deploy.sh`, `bootstrap/cache/`.
- Tags: infra | cache | deploy

## 4) Provider manquant (`PailServiceProvider`) en production

- Date: 2025-11-21
- Contexte: erreur `Class ... not found` au runtime.
- Cause: `vendor`/autoload incomplet ou incoherent.
- Solution: `composer install --no-dev --optimize-autoloader --prefer-dist` puis `composer dump-autoload -o`.
- References: logs serveur, `composer.json`.
- Tags: infra | build

## 5) Transition photo et cadre decoratif

- Date: 2025-11-25
- Contexte: transition image IA non fluide et risques de debordement visuel.
- Cause: animation/overlay non alignes sur la taille effective du canvas.
- Solution: crossfade opacity+blur, sizing pixel-perfect overlay, cadre via pseudo-elements CSS.
- References: `resources/js/photoToggle.js`, `resources/css/app.css`, `resources/views/home.blade.php`.
- Tags: ui | perf | css

## 6) Incoherence tests contact vs implementation

- Date: 2026-07-22
- Contexte: tests contact echouaient (message attendu obsolete, cle payload incorrecte, 404 route).
- Cause: divergence entre tests historiques et implementation actuelle.
- Solution: realigner les assertions sur message courant et payload `body`, utiliser route localisee `fr`.
- References: `tests/Feature/ContactFormTest.php`, `app/Http/Controllers/ContactController.php`.
- Tags: tests | bug | quality
