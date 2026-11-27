# Parcours de lecture du code

Ce guide sert à comprendre le site en lisant les fichiers dans le bon ordre.
Pour les décisions d'architecture (stack, hébergement, règles), voir `docs/governance/ARCHITECTURE.md`.

## Le site en bref

- **Une seule page** (`home.blade.php`), rendue côté serveur par Laravel, en français ou en anglais.
- **Pas de framework front** : Blade génère le HTML, Alpine.js et quelques modules JS ajoutent le comportement (fenêtres, photo, menus).
- **Vite** compile `resources/css/app.css` et `resources/js/app.js` dans `public/build/`.
- **Seule partie dynamique côté serveur** : le formulaire de contact, envoyé par mail.

## Ordre de lecture

Chaque fichier commence par un commentaire qui résume son rôle.

| # | Fichier | Ce qu'on y apprend |
|---|---------|--------------------|
| 1 | `routes/web.php` | La carte des URL (`/`, `/fr`, `/en`, `/cv`, `/mentions`, `POST /contact`) et l'ordre dans lequel Laravel les teste. |
| 2 | `app/Http/Middleware/SetLocale.php` | Comment la langue est choisie : segment d'URL, puis cookie `site_locale`, puis valeur par défaut. |
| 3 | `resources/views/layouts/app.blade.php` | Le `<head>` commun : favicons, police (Bunny Fonts), Google Analytics, chargement Vite. |
| 4 | `resources/views/home.blade.php` | La structure de la page : colonne de gauche (identité, photo, icônes), sections, bouton de sections mobile, fenêtres contact et CV. |
| 5 | `resources/lang/fr.json` et `en.json` | Tous les textes affichés. Une clé = une phrase, dans les deux langues. Les messages de validation français sont dans `resources/lang/fr/validation.php`. Vérifier qu'aucune clé ne manque : `node tools/check-missing-translations.cjs`. |
| 6 | `resources/js/app.js` | Le sommaire en tête du fichier liste les blocs : fenêtres, surlignage de la section en cours, icônes Contact/CV épinglées sur mobile, conversion `data-x-*` vers `x-*`. |
| 7 | `resources/js/photoToggle.js` | La bascule photo / version IA, dessinée dans un `<canvas>`. |
| 8 | `resources/js/sectionFab.js` | Le bouton hexagonal mobile et le calcul de l'éventail en quart de cercle (`layout()`). |
| 9 | `resources/js/halo.js` | Le halo lumineux qui suit la souris (desktop uniquement). |
| 9 bis | `resources/js/contactFeedback.js` | Les toasts après l'envoi du formulaire et le repli « ouvrir dans ma messagerie / copier le message » en cas d'échec. |
| 10 | `resources/css/app.css` | Lire d'abord l'en-tête, puis le bloc **« UX quick wins »** à la fin du fichier : c'est la couche de design actuelle. |
| 11 | `app/Http/Controllers/ContactController.php`, `app/Mail/ContactMessage.php`, `resources/views/emails/contact.blade.php` | Le formulaire de contact, de la validation jusqu'au mail. |
| 12 | `tests/Feature/` | Ce qui est vérifié automatiquement : contenu de la page en FR/EN, carrousel et vidéo, formulaire de contact, page CV. |

## Trois parcours à suivre de bout en bout

**Changer de langue**
1. Clic sur le globe (`#langToggleButton`, `home.blade.php`).
2. Le script en bas de `home.blade.php` écrit le cookie `site_locale` et redirige vers `/en/...` ou `/fr/...`.
3. `SetLocale` lit le segment d'URL et règle la langue ; `__('clé')` va chercher le texte dans `en.json` ou `fr.json`.

**Envoyer un message**
1. Toute commande « contact » (icône enveloppe, lien du pied de page) déclenche l'événement `open-contact`.
2. Le composant Alpine `contactModal()` (défini dans `app.js`) affiche la fenêtre.
3. Le formulaire est envoyé en `POST /{locale}/contact` vers `ContactController@send`.
4. Après validation et contrôle du champ piège `hp`, `ContactMessage` est envoyé, puis un toast confirme l'envoi.
5. Si l'envoi échoue : toast d'erreur, la fenêtre se rouvre avec le texte conservé (`old()`), et propose d'ouvrir un e-mail déjà rédigé dans la messagerie du visiteur ou de copier le message (`contactFeedback.js`). En cas d'erreur de validation, la fenêtre se rouvre aussi, avec les erreurs.

**Ouvrir un CV**
1. L'icône CV ajoute `#cv-selector-modal` à l'URL.
2. `syncCvSelectorModalState()` (`app.js`) affiche la fenêtre tant que ce hash est présent.
3. Chaque choix ouvre un PDF de `public/cv/` dans un nouvel onglet.

## Où modifier quoi

| Je veux… | Fichier(s) |
|----------|------------|
| Changer un texte | `resources/lang/fr.json` **et** `resources/lang/en.json` |
| Ajouter ou modifier un projet | `home.blade.php` (section `#projects`), images dans `public/images/projects/`, textes dans les deux fichiers de langue |
| Ajouter une techno à la stack | Tableau `$stack` dans `home.blade.php`, icône SVG `resources/svg/icon-<slug>.svg` (`currentColor`, même gabarit que les autres) |
| Changer une couleur, une taille de texte | Jetons `--pf-*` en fin de `app.css` (mode clair et `.dark`) |
| Changer la forme des boutons | `.pf-btn` en fin de `app.css` ; la règle des formes (hexagone = action, 6 px = surface, 4 px = étiquette) est décrite dans le bloc « Shape system » |
| Noter un changement | `CHANGELOG.md`, rubrique « Non publié » |
| Mettre à jour un CV | Fichier PDF dans `public/cv/`, puis le tableau `$cvs` dans `home.blade.php` |
| Changer la vidéo de démo | `public/mov/demo-admiral.mp4` (MP4 H.264 : lisible dans tous les navigateurs) |

## Où sont les fichiers

- `public/images/` : photos de profil, captures des projets (`projects/`, en WebP), icônes CV (`icons/`).
- `public/cv/`, `public/recommandations/` : PDF téléchargeables.
- `public/mov/` : vidéo de démo.
- `resources/svg/` : icônes intégrées directement dans le HTML (stack, réseaux, contact). Les logos Claude Code, Codex et Lovable viennent de LobeHub Icons (licence MIT, source indiquée dans chaque fichier).

## Pièges connus

- **Cascade CSS chargée.** Beaucoup de règles anciennes utilisent `!important`. Une nouvelle règle doit souvent être préfixée (`html body #content …`, `#home-aside …`) pour s'appliquer. Exemple : `html:not(.dark) main#content * { color: inherit !important }` écrase toute couleur moins spécifique.
- **`.dark body > div` force `position: static`** sur tous les blocs placés directement dans `<body>`. C'est pour ça que la fenêtre CV et le bouton de sections mobile repositionnent eux-mêmes leurs éléments.
- **Apostrophes dans les expressions Alpine.** Un texte traduit injecté dans du JavaScript (`:aria-label="…"`) doit passer par `@js(__('clé'))`, sinon « d'écran » casse le script.
- **Attributs `data-x-*`.** Ils sont copiés en `x-*` par `applyDataXShim()` avant le démarrage d'Alpine. Un élément ajouté après coup doit être réinitialisé (voir le bloc « CV link clone » dans `app.js`).
- **Sous-chemin en production.** Le site peut être servi sous `/portfolio`. Toujours utiliser `route()` et `asset()`, jamais de chemin écrit en dur.

## Commandes utiles

```bash
php artisan serve            # serveur local (voir docs/reports/SOLUTIONS_LOG.md §7 si OPcache bloque)
npm run dev                  # Vite en mode développement (rechargement à chaud)
npm run build                # compile CSS et JS dans public/build/
php artisan test             # tests automatisés
node tools/check-missing-translations.cjs   # clés FR/EN synchronisées
```

Pour tester l'échec d'envoi du formulaire sans rien envoyer : le profil `portfolio-mail-fail` de `.claude/launch.json` lance le site sur le port 8010 avec un serveur SMTP injoignable.
