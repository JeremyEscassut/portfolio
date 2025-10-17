# Portfolio CDA — Laravel 12 + TailwindCSS + Alpine.js

> Cible: réalisation en 5–7 jours. Interface en français, code en anglais. Mobile minimaliste, desktop complet avec option “Pro/Fun”.

## 1) Supports et UX

| Support | Comportement                                                                                                                                     |
| ------- | ------------------------------------------------------------------------------------------------------------------------------------------------ |
| Mobile  | Interface minimaliste: texte court, lien CV (PDF), lien mail (`mailto:`), LinkedIn, Indeed. Pas d’animation, pas de formulaire.                  |
| Desktop | Version complète avec choix “Pro/Fun”. “Pro” → accès direct. “Fun” → animation d’intro optionnelle (bouton “Ignorer”). Fallback si JS désactivé. |

## 2) Contenus

-   Accueil: présentation, valeurs, objectifs.
-   Projets: liens GitLab.
-   Contact (optionnel au début): envoi email vers Gmail via SMTP, validation front/back, confirmation utilisateur.
-   CV: lien vers PDF.
-   Réseaux: LinkedIn, Indeed.
-   Footer: mentions légales, politique de confidentialité (version minimaliste).

## 3) Stack technique et contraintes

| Élément         | Choix                                                                                           |
| --------------- | ----------------------------------------------------------------------------------------------- |
| Backend         | Laravel 12 (PHP 8.3 via MAMP)                                                                   |
| Front           | Vite 7, TailwindCSS v4 (@tailwindcss/vite), Alpine.js                                           |
| Animations      | Optionnelles: CSS/Alpine transitions, GSAP possible (pas de Framer Motion)                      |
| Base de données | Aucune initialement (SQLite dispo pour sessions/tests). Bascule MySQL MAMP possible (port 8889) |
| Mail            | SMTP Gmail via `.env` (en dev: `MAIL_MAILER=log`)                                               |
| SEO             | `<title>`, `<meta>` description, balises alt, structure sémantique                              |
| Accessibilité   | Contraste, navigation clavier, aria basiques, responsive                                        |
| Sécurité        | CSRF, validation serveur, honeypot, HTML/CSS valides W3C                                        |
| Analytics       | Optionnel, à la toute fin                                                                       |

Contraintes: pas de frameworks non prévus (ex: React/shadcn/framer-motion). Interface FR, code EN.

## 4) Démarrage local

Prérequis: MAMP (PHP 8.3), Composer 2, Node LTS (nvm), npm.

```bash
# Lancer le serveur Laravel et Vite (hot reload)
npm run dev:all

# Ou séparément
php artisan serve --host=127.0.0.1 --port=8000
npm run dev

# Build de production
npm run build
```

Configuration `.env` (extraits utiles):

```env
APP_NAME=Portfolio
APP_URL=http://127.0.0.1:8000

# Mails (dev)
MAIL_MAILER=log

# Option MySQL MAMP (quand nécessaire)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=8889
# DB_DATABASE=portfolio
# DB_USERNAME=<your_user>   # ex. root en local MAMP
# DB_PASSWORD=<your_password>   # ex. root en local MAMP
```

Note sécurité:

-   Ne pas committer le fichier `.env` (il est ignoré par défaut par Laravel).
-   Utiliser des variables d’environnement ou des secrets CI/CD pour la production.

## 5) Qualité et tests

-   Tests unitaires et fonctionnels: navigation et formulaire de contact.
-   Validation W3C (HTML/CSS), règles d’accessibilité de base.
-   Éviter tout commentaire laissant transparaître l’usage d’IA.

Commandes:

```bash
# Tests Laravel (recommandé)
php artisan test

# PHPUnit direct
vendor/bin/phpunit --coverage-html storage/coverage
open storage/coverage/index.html
```

## 6) Déploiement

-   Cibles possibles: hébergement PHP classique (Apache/Nginx) ou plateformes compatibles (Laravel Forge + VPS, Render, Railway, Fly.io).
-   Variables d’environnement: `APP_ENV`, `APP_KEY`, `APP_URL`, `MAIL_*`, éventuels `DB_*`.

## 7) Roadmap (ordre cible)

1. Contenu statique et layout de base (FR UI / EN code)
2. Version mobile minimaliste (pas de form ni animations)
3. Version desktop complète + switch “Pro/Fun” (+ animation optionnelle)
4. Formulaire de contact (SMTP Gmail, CSRF, validation, honeypot)
5. Tests (form + nav) et conformité (SEO, A11y, W3C)
6. Build, déploiement, analytics (optionnel)
