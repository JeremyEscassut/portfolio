# Portfolio de Jérémy Escassut

Site en ligne : **https://www.jeremyescassut.com** (français et anglais)

Portfolio personnel de Jérémy Escassut, product owner, chef de projet IT et développeur UX/UI. Ce dépôt est public pour montrer deux choses : le code du site, et la **méthode de travail avec les assistants IA** qui a servi à le construire et à le maintenir.

*English summary: personal portfolio built with Laravel 12. The repository is public to show both the code and the way AI coding assistants are framed on this project: written rules, mandatory checks, incident and solution logs, tests.*

## Ce que fait le site

- Une page d'accueil en sections : à propos, projets, expérience, études, recommandations, stack.
- Trois CV en PDF, une page de mentions légales, une page 404.
- Un formulaire de contact qui envoie un e-mail. En cas d'échec, le texte est conservé et le visiteur peut l'envoyer depuis sa propre messagerie.
- Le français et l'anglais, les thèmes clair et sombre (qui suivent le système), et un bouton de sections sur mobile (testé sur iPhone 13 Pro).
- L'accessibilité comme exigence : dialogues natifs, focus géré, contrastes vérifiés, textes alternatifs, et respect de la préférence « réduire les animations ».
- Google Analytics, chargé uniquement après le consentement du visiteur, avec un bandeau où « Refuser » est aussi visible que « Accepter ».

## Travailler avec l'IA : le cadre

Le code a été écrit avec des assistants IA (Claude Code, Codex), **dans un cadre écrit** que chaque assistant doit lire et appliquer :

| Fichier | Rôle |
|---------|------|
| [`CLAUDE.md`](CLAUDE.md) et [`.github/copilot-instructions.md`](.github/copilot-instructions.md) | Règles de comportement des assistants : vérifications avant toute modification, interdiction de valider une hypothèse sans preuve, bilinguisme, marqueur de conformité en tête de réponse. |
| [`docs/governance/`](docs/governance) | Architecture, conventions, roadmap et dette technique priorisée. |
| [`docs/reports/INCIDENT_LOG.md`](docs/reports/INCIDENT_LOG.md) | Journal des incidents, à lire avant toute correction de bug. |
| [`docs/reports/SOLUTIONS_LOG.md`](docs/reports/SOLUTIONS_LOG.md) | Solutions retenues et procédures à réutiliser. |
| [`docs/reports/AUDIT_2026-11-27.md`](docs/reports/AUDIT_2026-11-27.md) | Audit sécurité, propreté et SEO réalisé avant l'ouverture du dépôt. |
| [`CHANGELOG.md`](CHANGELOG.md) | Historique des évolutions, mis à jour à chaque lot. |

Les principes : donner à l'IA le contexte dont elle a besoin, cadrer précisément ce qu'elle peut et doit faire, puis vérifier chaque résultat (tests, build, contrôle visuel) avant de le retenir. Les changements passent par des pull requests relues et validées.

## Stack

- **Back** : Laravel 12 (PHP 8.2 et plus), Blade, envoi SMTP.
- **Front** : Vite 7, Tailwind CSS 4, Alpine.js 3, JavaScript sans framework pour les comportements spécifiques.
- **Qualité** : tests PHPUnit (`tests/Feature`), vérification des traductions FR/EN, `composer audit` et `npm audit`.
- **Hébergement** : Alwaysdata, déploiement par rsync (`scripts/deploy.sh`).

## Lancer le projet en local

Prérequis : PHP 8.2 ou plus, Composer 2, Node LTS et npm.

```bash
composer install
npm install
cp .env.example .env && php artisan key:generate
npm run build
php artisan serve
```

Vérifications avant chaque lot :

```bash
php artisan test
node tools/check-missing-translations.cjs
npm run build
```

Pour découvrir le code dans l'ordre : [`docs/PARCOURS_DE_LECTURE.md`](docs/PARCOURS_DE_LECTURE.md).

## Sécurité

Aucun secret n'est versionné : la configuration de production vit uniquement dans le `.env` du serveur. Le site envoie des en-têtes de sécurité HTTP et limite le nombre d'envois du formulaire de contact. Pour signaler un problème, passez par le formulaire de contact du site.

## Droits

Le code est consultable à titre de démonstration. Les contenus (textes, photos, CV, lettres de recommandation) restent la propriété de Jérémy Escassut et ne peuvent pas être réutilisés sans son accord.
