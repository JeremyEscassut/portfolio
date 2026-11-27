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

### Front (mise a jour 2026-11-27)

- Une seule page (`home.blade.php`) rendue cote serveur ; Alpine.js pour les fenetres et petits etats.
- Point d'entree JS unique `resources/js/app.js` (sommaire en tete), qui importe des modules a responsabilite unique : `photoToggle.js` (photo / version IA), `halo.js` (halo desktop), `sectionFab.js` (bouton de sections mobile et defilement anime), `contactFeedback.js` (toasts et repli de contact).
- Attributs Alpine ecrits `data-x-*` (HTML valide) et convertis en `x-*` au demarrage.
- CSS : Tailwind v4 + `resources/css/app.css`. La couche de design de reference est le bloc « UX quick wins » en fin de fichier (jetons `--pf-*`, formes, composants) ; les regles plus anciennes restent en place tant qu'elles ne sont pas migrees (voir `TECHNICAL_DEBT.md`).
- Police servie par Bunny Fonts (miroir des Google Fonts conforme RGPD).

### Medias

- Aucune image embarquee en base64 dans le HTML : images servies depuis `public/images/` (captures en WebP), avec dimensions et chargement differe.
- Video de demonstration en MP4 H.264 (`public/mov/`), `preload="none"`, avec image d'apercu.

### Contact

- Deux adresses distinctes dans `config/mail.php` : `contact_address` (reception du formulaire, `MAIL_CONTACT_ADDRESS`) et `public_contact_address` (adresse affichee aux visiteurs, `MAIL_PUBLIC_CONTACT_ADDRESS`).
- En cas d'echec d'envoi : redirection avec la saisie conservee, toast d'erreur, fenetre rouverte et repli cote client (`mailto:` prerempli ou copie du message). Aucun detail technique n'est expose au visiteur.
- Profil local `portfolio-mail-fail` (`.claude/launch.json`, port 8010) pour tester l'echec sans envoyer de message.

### Internationalisation

- Textes dans `resources/lang/fr.json` et `en.json` ; messages de validation francais dans `resources/lang/fr/validation.php`.
- Langue choisie par segment d'URL, puis cookie `site_locale`, puis valeur par defaut (`SetLocale`).

### Documentation

- `docs/PARCOURS_DE_LECTURE.md` : ordre de lecture du code et parcours de bout en bout.
- `CHANGELOG.md` : historique des changements.

## Regles de non-regression architecture

- Toute modification de routage localise (`/fr`, `/en`) doit etre accompagnee de tests Feature.
- Toute modification du flux mail doit etre accompagnee de tests Feature (Mail::fake) et d'une verification manuelle SMTP.
- Toute modification du repli de contact (echec d'envoi) doit garder les tests `ContactFormTest` correspondants en vert et etre verifiee avec le profil `portfolio-mail-fail`.
- Toute evolution de build/deploiement doit mettre a jour la documentation de `docs/`.

## Hors perimetre

- Les details de planning sont portes par `docs/governance/roadmap.md`.
- Les regles quotidiennes de qualite sont dans `docs/governance/Guidelines.md`.
- Le suivi de dette est dans `docs/governance/TECHNICAL_DEBT.md`.
