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
