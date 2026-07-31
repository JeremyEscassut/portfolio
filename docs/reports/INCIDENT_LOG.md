# INCIDENT_LOG - Portfolio

## Regles

- Toute anomalie significative en production, recette ou developpement est tracee ici.
- Chaque entree doit etre exploitable et orientee non-regression.

## Template

- ID:
- Date:
- Environnement: dev | test | prod
- Symptomes:
- Impact:
- Cause racine:
- Correctif applique:
- Tests de non-regression:
- Statut: open | monitoring | closed
- References:

---

## INC-2026-07-22-001

- ID: INC-2026-07-22-001
- Date: 2026-07-22
- Environnement: dev
- Symptomes: tests contact en echec (404) sur endpoint d'envoi.
- Impact: impossible de valider automatiquement le formulaire de contact.
- Cause racine: tests appelaient `route('contact.send')` sans `locale`, alors que la route est localisee.
- Correctif applique: tests modifies pour appeler `route('contact.send', ['locale' => 'fr'])`.
- Tests de non-regression: `php artisan test --filter=ContactFormTest`.
- Statut: closed
- References: `tests/Feature/ContactFormTest.php`.

---

## INC-2026-07-30-001

- ID: INC-2026-07-30-001
- Date: 2026-07-30
- Environnement: dev
- Symptomes: le bouton CV sur la page d'accueil ne provoquait aucune ouverture de modale dans le navigateur, malgré la présence du markup.
- Impact: l'utilisateur ne pouvait pas choisir de version de CV depuis l'accueil, ce qui bloquait l'accès au téléchargement.
- Cause racine: le déclencheur initial reposait sur un flux d'événements trop fragile entre le markup Blade, Alpine et le DOM, ce qui ne garantissait pas l'ouverture de la modale à l'exécution.
- Correctif applique: bascule vers un déclencheur robuste via un lien ciblant `#cv-selector-modal` et un fallback JavaScript `window.openCvSelectorModal()` pour synchroniser l'état de la modale et l'URL. Ajout d'une option CV Product Owner dans la modale avec le PDF associé.
- Tests de non-regression: `npm run build`, `php artisan test --filter=CvPageTest`.
- Statut: closed
- References: `resources/views/home.blade.php`, `resources/js/app.js`, `tests/Feature/CvPageTest.php`.
