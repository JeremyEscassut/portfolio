# ARCHITECTURE - Portfolio

## Objet

Ce document fixe les decisions techniques structurelles du projet Portfolio.

## Stack verrouillee

- Backend : Laravel 12, PHP 8.3.
- Front build : Vite 7.
- Styling : TailwindCSS v4 + CSS cible dans `resources/css/app.css`.
- Interactions front : Alpine.js.
- Rendering : Blade (pas de SPA framework dedie).

## Contraintes d'hebergement

- Cible principale : Alwaysdata.
- Application potentiellement servie sous sous-chemin (exemple : `/portfolio`).
- Les URLs publiques doivent etre generees via helpers Laravel (`route`, `asset`) pour rester robustes au sous-chemin.

## Decisions d'architecture

- Le formulaire de contact est traite cote serveur (validation Laravel + envoi SMTP).
- Les secrets d'infrastructure restent hors depot (fichier `.env` local/serveur uniquement).
- Le code metier du front reste concentre dans Blade + JS/CSS centralises.
- Les scripts d'exploitation sont conserves dans `scripts/`.

## Regles de non-regression architecture

- Toute modification de routage localise (`/fr`, `/en`) doit etre accompagnee de tests Feature.
- Toute modification du flux mail doit etre accompagnee de tests Feature (Mail::fake) et d'une verification manuelle SMTP.
- Toute evolution de build/deploiement doit mettre a jour la documentation de `docs/`.

## Hors perimetre

- Les details de planning sont portes par `docs/governance/roadmap.md`.
- Les regles quotidiennes de qualite sont dans `docs/governance/Guidelines.md`.
- Le suivi de dette est dans `docs/governance/TECHNICAL_DEBT.md`.
