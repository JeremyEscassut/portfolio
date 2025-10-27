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

### Accessibilité — contraste en mode sombre

Pour garantir la lisibilité en mode sombre, une règle CSS globale a été ajoutée au layout afin d'assurer un contraste minimum pour le texte, les liens et les éléments interactifs. Objectifs et actions :

-   Forcer des couleurs de texte lisibles en `prefers-color-scheme: dark` (variables `--accent-text`, `--muted-text`).
-   Ajuster les fonds très sombres pour éviter des cas de « pure black » qui peuvent réduire le contraste apparent.
-   Rendre les fonds de cartes/panneaux légèrement translucides pour faire ressortir le texte.

Comment tester :

1. Activer le mode sombre au niveau du système (ou dans le navigateur via les outils DevTools).
2. Vérifier visuellement que les titres, paragraphes et liens sont lisibles (contraste suffisant).
3. Utiliser un validateur de contraste (ex : Lighthouse, axe, or the WebAIM Contrast Checker) sur les pages principales (`/`, `/cv`, etc.).

Si certains composants héritent de styles (ex: `transform`, `filter`) qui empêchent la prise d'effet, ouvrir la console et inspecter les ancêtres pour déceler des règles CSS particulières. Si besoin, on pourra appliquer un fallback plus strict (clonage/positionnement en dehors du flux) pour les éléments fixes en mobile.

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

### Mail en développement — MailHog (recommandé)

Pour capter facilement les e-mails en local sans config SMTP externe, utilisez MailHog. Voici deux méthodes courantes pour le lancer et la config `.env` associée.

-   Installation via Homebrew (macOS) :

```bash
# Installer (une seule fois)
brew update && brew install mailhog

# Lancer MailHog (méthode recommandée via Homebrew)
# Sur macOS (Homebrew), la commande pour lancer MailHog en tant que service est :
mailhog start

# Alternative : lancer MailHog en arrière-plan dans le shell
mailhog &
```

-   Ou via Docker :

```bash
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog
```

Configuration `.env` pour utiliser MailHog :

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
```

Tester :

1. Lancer l'app Laravel (php artisan serve) et Vite (npm run dev).
2. Ouvrir MailHog UI à l'URL http://127.0.0.1:8025 pour voir les e-mails reçus.
3. Soumettre le formulaire de contact depuis la page d'accueil et vérifier que le message apparaît dans MailHog.

Remarque : en développement, la valeur par défaut du projet est `MAIL_MAILER=log`. Si vous activez MailHog, mettez à jour `.env` comme ci-dessus puis redémarrez le serveur Laravel si nécessaire.

### En production — choix du provider et bonnes pratiques (Symfony Mailer)

Pour la mise en ligne du portfolio, Symfony Mailer (utilisé par Laravel) est un choix pérenne et sécurisé. Je recommande d'utiliser un provider transactionnel (Postmark recommandé pour les petits sites/portfolios) afin d'améliorer la délivrabilité, faciliter la configuration et recevoir des webhooks sur les bounces/complaints.

Exemples `.env` pour différents scénarios :

-   Postmark (recommandé — API, pratique pour Vercel / serverless) :

```env
MAIL_MAILER=postmark
POSTMARK_TOKEN=your-postmark-server-token
MAIL_FROM_ADDRESS=hello@yourdomain.com
MAIL_FROM_NAME="Your Name"
```

-   Mailgun (SMTP ou HTTP API) :

```env
# via smtp
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@mg.yourdomain.com
MAIL_PASSWORD=secret
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@yourdomain.com
MAIL_FROM_NAME="Your Name"

# ou via le driver mailgun (HTTP) :
# MAIL_MAILER=mailgun
# MAILGUN_DOMAIN=mg.yourdomain.com
# MAILGUN_SECRET=your-mailgun-api-key
```

-   AWS SES (via `ses` / `ses-v2`) :

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=eu-west-1
MAIL_FROM_ADDRESS=hello@yourdomain.com
```

Notes opérationnelles :

-   Vercel / serverless : privilégiez les providers API (Postmark, Mailgun HTTP, SendGrid) plutôt que SMTP sortant (les fonctions serverless ont des limitations réseau). Stockez les clés API dans les variables d'environnement de Vercel.
-   Alwaysdata / hébergement classique : SMTP est généralement autorisé — utilisez SMTP ou le transport natif du provider et configurez les ports (587/465) selon le provider.
-   Security : ne committez jamais vos clés dans le repo. Utilisez les variables d'environnement, et activez 2FA / App Passwords sur vos comptes si nécessaire.
-   Deliverability : configurez SPF/DKIM/DMARC pour le domaine d'envoi afin d'éviter que vos mails soient marqués comme spam.
-   Asynchrone : en production, placez les envois dans une queue (ex : database/redis queue). Lancer un worker (supervisor, systemd ou `php artisan queue:work`) pour traiter les envois de manière fiable.

Commandes utiles pour la mise en prod :

```bash
# Cacher la config et les routes
php artisan config:cache
php artisan route:cache

# Lancer un worker (exécution manuelle / debug)
php artisan queue:work --sleep=3 --tries=3

# Pour la supervision en production, utiliser supervisor / systemd et relancer le worker au boot
```

## Changements récents (rapide résumé)

-   Modal de contact : le fond (`overlay`) et le conteneur du modal ont maintenant des z-index très élevés pour garantir que le modal reste toujours au premier plan (corrige les titres de sections qui pouvaient apparaître devant le modal).
-   Gestion du formulaire de contact : le contrôleur capture maintenant les exceptions d'envoi de mail (try/catch), loggue l'erreur côté serveur et renvoie un message d'erreur flash côté client. Le formulaire conserve les valeurs saisies (`old(...)`) pour éviter de tout perdre en cas d'erreur.
-   Comportement UX : en cas d'erreur lors de l'envoi, la page renvoie avec une clé de session `openContact` pour réouvrir automatiquement le modal et permettre la correction; en cas de succès, un message de confirmation est affiché.

Comment tester rapidement ces changements :

1. Ouvrir l'application en local (depuis le dossier `Portfolio`) :

```bash
cd /Users/admin/Documents/VSCode\ Workspaces/C2RT/Laravel/Portfolio
php artisan serve --host=127.0.0.1 --port=8000
npm run dev
```

2. Ouvrir la page d'accueil (`/`) dans le navigateur, cliquer sur le bouton « Ouvrir le contact », remplir le formulaire et soumettre.

3. Pour simuler une erreur d'envoi mail : configurer un transport SMTP invalide dans `.env` (ex: mauvais hôte/identifiants) puis soumettre. Le site affichera un message d'erreur et rouvrira le modal pour corriger.

Notes de sécurité/opérations :

-   Les erreurs côté serveur sont logguées via le facade `Log` de Laravel. Ne pas exposer les messages d'exception techniques à l'utilisateur final.

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

## 7) Guidelines

1. Contenu statique et layout de base (FR UI / EN code)
2. Réponses dans le chat courtes et "to the point".

## 8) Roadmap (ordre cible)

1. Publier sur Alwaysdata
2. Version desktop complète + switch “Pro/Fun” (+ animation optionnelle)
3. Ajuster les blocs pour faire disparaître la barre de scroll latérale (problème uniquement présent sur Safari)
4. Build et déploiement
5. Tests (formulaire + navigation) et conformité (SEO, A11y, W3C)
6. Analytics (à la toute fin)
7. Code qui prévient en cas d'injection de script dans l'Admin dashboard.
8. Nettoyer le code pour ne mettre que des commentaires pertinents
   S'assurer que les 2 prochains points soient des bonnes pratiques de développement.
9. Nettoyer le code pour éventuellement mettre les règles de style dans le fichier CSS approprié.
10. Nettoyer le code pour placer le code javascript dans un fichier dédié.
11. Améliorer le contraste pour les personnes à visibilité réduite
12. Mode focus pour les boutons Contact et CV

Actuellement, quand on est en mode @media, les titres des sections, ex. :
#about > div.sticky.top-0.z-20.-mx-6.mb-4.w-screen.bg-transparent.px-6.py-3.backdrop-blur.lg\:sr-only.lg\:relative.lg\:top-auto.lg\:mx-auto.lg\:w-full.lg\:px-0.lg\:py-0.lg\:opacity-0

débordent du cadre body > div > main
Est-ce que tu peux corriger ça ?
