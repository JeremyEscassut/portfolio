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

## 7) Lancement de l'environnement de dev Laravel bloque par OPcache

- Date: 2026-07-30
- Contexte: tentative de lancer `php artisan serve` depuis l'environnement de travail ; PHP a retourne `Fatal Error Unable to create opcache lock file in /tmp: Operation not permitted`.
- Cause: OPcache CLI bloque la creation de lockfile dans l'environnement de conteneur/sandbox utilise pour l'execution.
- Solution: relancer le serveur avec `php -d opcache.enable=0 -d opcache.enable_cli=0 -d opcache.jit=0 artisan serve --host=127.0.0.1 --port=8000` ; si besoin, utiliser une execution non sandboxee pour rendre localhost accessible.
- Procedure a retenir pour la prochaine fois: 1) verifier la branche et le scope demande, 2) tenter `php artisan serve`, 3) si erreur OPcache, relancer avec OPcache desactive, 4) documenter le cas ici.
- References: terminal local, `php artisan serve`, `php -i`.
- Tags: infra | dev | php | ops

## 8) Erreur JavaScript du carrousel CRM (apostrophe dans une expression Alpine)

- Date: 2026-11-27
- Contexte: la console affichait `Uncaught SyntaxError: Unexpected identifier 'écran'` au chargement de la page d'accueil.
- Cause: le libellé traduit « captures d'écran » etait injecte tel quel dans une expression Alpine (`:aria-label="'…' + …"`) ; l'apostrophe fermait la chaine JavaScript.
- Solution: passer les textes traduits injectes dans du JavaScript par `@js(__('cle'))`, qui echappe l'apostrophe.
- References: `resources/views/home.blade.php` (points du carrousel), `tests/Feature/HomePageContentTest.php` (test `carousel_labels_with_apostrophes_are_escaped_for_alpine`).
- Tags: ui | bug | i18n | js

## 9) Fenetre de choix du CV affichee en bas de page en mode sombre

- Date: 2026-11-27
- Contexte: en mode sombre, la fenetre « Choisissez un CV » s'ouvrait en bas de la page au lieu d'etre centree a l'ecran.
- Cause: la regle `.dark body > div { position: static !important }` s'appliquait aussi a `#cv-selector-modal`, enfant direct de `<body>`, et annulait son `position: fixed`.
- Solution: `html.dark #cv-selector-modal { position: fixed !important; }` dans le bloc « UX quick wins » de `app.css`. Le bouton de sections mobile, egalement enfant de `<body>`, positionne ses elements individuellement pour la meme raison.
- Non-regression: pas de test automatise possible (rendu CSS) ; verification manuelle en mode sombre, desktop et mobile.
- References: `resources/css/app.css`.
- Tags: ui | bug | css | dark-mode

## 10) Tester l'echec d'envoi du formulaire sans rien envoyer

- Date: 2026-11-27
- Contexte: verifier le repli de contact (toast, fenetre rouverte, `mailto:`) sans envoyer de vrai message.
- Cause: le repli ne s'affiche que si l'envoi SMTP echoue.
- Solution: lancer le profil `portfolio-mail-fail` de `.claude/launch.json` (port 8010). Il passe `MAIL_MAILER=smtp`, `MAIL_HOST=127.0.0.1`, `MAIL_PORT=9` en variables d'environnement, qui priment sur `.env` ; l'envoi echoue toujours.
- References: `.claude/launch.json`, `app/Http/Controllers/ContactController.php`.
- Tags: tests | mail | dev

## 11) Couleur d'un bouton ecrasee par une regle sombre generique

- Date: 2026-11-27
- Contexte: libelle de bouton illisible en mode sombre dans une fenetre.
- Cause: `.dark [role="dialog"] button { color: ... !important }` (deux classes) l'emporte sur un selecteur a une classe.
- Solution: ecrire les couleurs des composants avec au moins deux classes (`.pf-btn.pf-btn--primary`) ou un identifiant, avec `!important` si la regle concurrente en a un.
- References: `resources/css/app.css` (bloc « UX quick wins »).
- Tags: ui | css | dark-mode

## 12) Classes Tailwind `dark:` qui ne suivent pas le bouton theme

- Date: 2026-11-27
- Contexte: le site bascule le theme avec la classe `.dark` sur `<html>`, mais Tailwind v4 lie `dark:` a `prefers-color-scheme` par defaut.
- Cause: variante `dark` par defaut de Tailwind v4.
- Solution: `@custom-variant dark (&:where(.dark, .dark *));` juste apres `@import "tailwindcss";` dans `resources/css/app.css`.
- References: `resources/css/app.css`.
- Tags: ui | css | dark-mode | tailwind

## 13) Auditer les contrastes au survol et au focus

- Date: 2026-11-27
- Contexte: les etats survol/focus ne sont pas lisibles par `getComputedStyle` sans interaction reelle.
- Cause: les pseudo-classes `:hover` / `:focus-visible` ne peuvent pas etre forcees par script.
- Solution: `tools/qa-contrast-audit.js` copie les regles `:hover` / `:focus-visible` sur des classes `.qa-hover` / `.qa-focus`, desactive les transitions et calcule le contraste texte/icone contre le fond reel (y compris `::before` / `::after`). A lancer en clair et en sombre, fenetres ouvertes.
- References: `tools/qa-contrast-audit.js`.
- Tags: ui | accessibilite | tests
