# Portfolio - Laravel 12 + Vite + TailwindCSS

Resume rapide

- Projet : site portfolio (UI FR, code EN).
- Stack : Laravel 12 (PHP 8.3), Vite 7, TailwindCSS v4, Alpine.js.
- Objectif : site a contenu majoritairement statique avec formulaire de contact SMTP.
- Remote officiel : GitLab (`origin`).

## 1) But et portee

Ce repository contient le code source du portfolio. Le produit couvre :

- une page d'accueil descriptive,
- une section Projets,
- un lien CV (PDF),
- un formulaire de contact simple,
- un design responsive et accessible.

## 2) Demarrage rapide

Prerequis : PHP 8.3, Composer 2, Node LTS, npm.

```bash
composer install
npm install
php artisan serve
npm run dev
```

## 3) Tests et verifications

```bash
php artisan test
php artisan test --filter=ContactFormTest
```

## 4) Conventions de code

- CSS centralise dans `resources/css/app.css`.
- JS centralise dans `resources/js/app.js`.
- Les vues Blade contiennent principalement HTML + directives Blade.
- Aucun secret en dur dans le depot.

## 5) Documentation de reference

### Gouvernance

- Instructions IA : `.github/copilot-instructions.md`
- Architecture : `docs/governance/ARCHITECTURE.md`
- Guidelines : `docs/governance/Guidelines.md`
- Roadmap : `docs/governance/roadmap.md`
- Dette technique : `docs/governance/TECHNICAL_DEBT.md`

### Reports et operations

- Incident log : `docs/reports/INCIDENT_LOG.md`
- Solutions log : `docs/reports/SOLUTIONS_LOG.md`
- Configuration mail : `docs/operations/MAIL_SETUP.md`

## 6) Regle de consignation

Ne pas fermer une anomalie sans avoir ajoute une entree dans `docs/reports/SOLUTIONS_LOG.md`.
