# Roadmap - Portfolio

## Horizon

- Derniere synchronisation : 2026-11-27 (etat reel du lot `feature/portfolio-ux-quickwins`).
- Version cible : v3.0 Refonte UX, performance et bilinguisme (apres v1.1 Stabilisation qualite et gouvernance). Numero choisi par Jeremy le 2026-11-27 : les branches de sauvegarde `v1.1` a `v2.3` existent deja.
- Depot de reference : GitHub (`origin`). GitLab abandonne le 2026-11-27.
- Historique des changements livres et en cours : `CHANGELOG.md`.

> Note de fonctionnement : la section Plan par phases est la source de verite des taches cochees ou non cochees. Les sections Priorites, Plan de priorisation, Definition de pret pour demain et Definition of done globale sont des vues dynamiques a re-evaluer a chaque changement de scope et a chaque nouvelle journee de travail.

## Priorites

1. Mettre en production la v3.0 (lot UX merge dans `main` le 2026-11-27, PR #3 et #4) : prerequis de la phase 7. [En cours]
2. Basculer le contact sur l'adresse pro `jeremyescassut@gmail.com` (Alwaysdata, `.env` de production, mentions legales). [A venir]
3. Google Analytics : mentions legales corrigees ; reste le consentement avant chargement (bandeau) ou une mesure sans cookie. [En cours]
4. Garantir un affichage fiable sur navigateurs/appareils principaux : il reste Safari, Firefox, Edge et appareils reels. [En cours]
5. Consolider la gouvernance et l'exploitation. [Continu]

## Plan par phases

## Phase 1 - Stabilisation immediate

- [x] Realigner tests contact avec le comportement reel.
- [x] Corriger le message de succes du formulaire (espacement).
- [x] Ajouter test non-regression du honeypot (`hp` rempli => pas d'envoi mail).
- [x] Ajouter test de validation (`email` invalide, `message` trop long).
- [x] Ajouter test de route locale `fr/en` pour endpoint contact.

Criteres de sortie :

- `php artisan test --filter=ContactFormTest` passe.
- Flux contact nominal couvert par tests.

## Phase 2 - Fiabilisation parcours utilisateur

- [x] Ajouter tests Feature pour page CV :
    - statut 200,
    - lien PDF present,
    - lien download present,
    - iframe de preview presente.
- [x] Ajouter test d'accessibilite minimale (presence labels/contact controls).

Criteres de sortie :

- Tests CV automatises en vert.
- Verification manuelle locale validee.

## Phase 3 - Matrice navigateurs et appareils

- [ ] Definir matrice de compatibilite minimale :
    - Navigateurs desktop : Chrome, Firefox, Safari, Edge.
    - Mobile : iOS Safari, Chrome Android.
    - Tailles : 390x844, 768x1024, 1366x768, 1920x1080.
- [ ] Executer checklist manuelle sur parcours critiques :
    - navigation sections,
    - ouverture CV,
    - envoi formulaire contact,
    - rendu mode clair/sombre,
    - affichage des icones/reseaux.
- [ ] Archiver les resultats dans `docs/reports/`.
- [x] Verification Chrome (2026-11-27) : desktop 1440x900 et 1100x760, tablette 820x1000, mobile 390x844 ; navigation sections, CV, contact (succes, erreurs de validation, echec d'envoi simule), clair/sombre, FR/EN.

Criteres de sortie :

- 0 bug bloquant sur matrice cible.
- Bugs mineurs traces dans `TECHNICAL_DEBT.md`.

## Phase 4 - Gouvernance et hygiene continue

- [ ] Ajouter pipeline CI GitLab minimale : tests Laravel + build Vite.
- [ ] Ajouter la CI sur GitHub Actions (remplace la tache GitLab ci-dessus depuis l'abandon de GitLab) : tests Laravel + build Vite.
- [ ] Mettre en place une cadence de revue des incidents/solutions.
- [ ] Formaliser checklist pre-deploiement dans docs (sans runbook DRP), avec obligation de faire tourner les tests et le build avant tout push/merge de prod.
- [x] Formaliser et appliquer le modele bilingue FR/EN pour tous les textes a l'ecran et les ALT text.
- [x] Reparer le controle de synchronisation des traductions (`node tools/check-missing-translations.cjs`).
- [x] Traduire les messages de validation du formulaire (`resources/lang/fr/validation.php`).
- [x] Creer et tenir a jour `CHANGELOG.md`.
- [x] Ecrire le guide de lecture du code (`docs/PARCOURS_DE_LECTURE.md`) et commenter le code non evident.
- [ ] Ajouter `CHANGELOG.md` a la checklist pre-deploiement.
- [ ] Basculer le contact sur l'adresse pro `jeremyescassut@gmail.com` :
    - [ ] configurer la boite / l'envoi sur Alwaysdata,
    - [ ] mettre a jour `MAIL_CONTACT_ADDRESS` (reception du formulaire) dans le `.env` de production ; `MAIL_PUBLIC_CONTACT_ADDRESS` (adresse affichee aux visiteurs) vaut deja cette adresse par defaut,
    - [ ] mettre a jour l'adresse des mentions legales (`mentions.*` dans `resources/lang/fr.json` et `en.json`, actuellement `j.losaint@gmail.com`),
    - [ ] tester un envoi reel du formulaire apres bascule.

## Phase 5 - Evolutions produit et UX

- [x] Mettre a jour la description profil pour inclure la recherche de roles chef de projet / product owner et enrichir le volet formation (ANSSI, LinkedIn Learning, etc.).
- [x] Changer l'arriere-plan clair avec un degrade plus agreable.
- [x] Inverser le texte FR/EN du bouton de changement de langue.
- [x] Deplacer les boutons langue + theme au-dessus des boutons Contact et CV.
- [x] Augmenter l'espace entre les titres des sections et les boutons en mode desktop.
- [x] Changer le titre en : `Chef de projet IT | Developpeur Full Stack oriente Produit & UX`.
- [x] Ajouter React, PostgreSQL et MySQL dans les technologies recentes.
- [x] Ajouter un toggle de photo de profil original/IA avec transition visuelle, microcopy et comportement coherent sur le hero.
- [x] Assurer l'accessibilite du toggle photo de profil via clic et clavier.
- [x] Ajouter le volet de demonstration du projet CRM Admiral avec CTA de demo, carousel mono-slide, modal video et tests de non-regression.
- [x] Ajouter une section Projets pour l'experience CRM Admiral (ADM Assurances) sans lien Git, avec carousel de screenshots.
- [x] Ajouter une section Projets pour la PWA Budget (Lovable) avec lien public.
- [x] Ajouter l'experience "Chef de projet / Concepteur d'application metier - Stage" (fev. 2026 - avr. 2026, ADM Agen) en reformulant le contenu pour qu'il soit coherente avec le reste du portfolio.
- [x] Ajouter une section Etudes avec le Master en traduction et le titre de CDA, en separant clairement cette dimension des experiences professionnelles.
- [x] Reorganiser la section Experience pour y intégrer le parcours de traducteur, les experiences de chef de projets internationaux et l'experience de chef de projet / concepteur d'application metier.
- [x] Ajouter une section Recommandations avec une liste de recommandations et un point d'entree de telechargement PDF.
- [x] Appliquer une logique de storytelling plus coherente entre profil, etudes, experience, projets et recommandations.
- [x] Evaluer l'ajout d'un enregistrement ecran CRM Admiral en fonction du poids final deploye (Alwaysdata).
- [x] Ajouter un selecteur de CV au clic sur le bouton CV :
    - [x] version Chef de projet IT,
    - [x] version Developpeur Full Stack oriente Produit & UX,
    - [x] version Product Owner / Transformation digitale.
- [x] Remplacer le titre par `Product owner, chef de projet IT et developpeur UX/UI` et ajouter une phrase d'accroche (FR/EN).
- [x] Remplacer le degrade du fond clair par un fond uni (le halo qui suit la souris est conserve).

## Phase 6 - Refonte UX quick wins (lot du 2026-11-27)

Contexte : rendre le portfolio plus abouti pour des recruteurs PO / chef de projet IT / developpeur UX/UI oriente IA, sans changer l'identite (palette, photo hexagonale, halo).

Lisibilite et identite

- [x] Police Schibsted Grotesk (Bunny Fonts), texte plus fonce, lignes de 68 caracteres maximum.
- [x] Sections separees par un trait avec un titre visible ; colonne des dates reduite (Experience, Etudes).
- [x] Supprimer les textes au ton de brouillon (intros Projets et Recommandations).
- [x] Projets : ligne « Mon role », descriptions raccourcies, liens en boutons hexagonaux avec logo.
- [x] Recommandations : citation en premier, auteur et contexte en dessous.
- [x] Systeme de formes : hexagone = action, rectangle 6 px = surface, rectangle 4 px = etiquette.
- [x] Boutons secondaires en hexagone a contour et tags avec filet fin pour mieux ressortir en mode clair.
- [x] Audit des contrastes survol/focus en clair et en sombre (`tools/qa-contrast-audit.js`) et corrections (choix de CV, croix de fermeture, icones theme/langue/contact, GitLab).
- [x] Cadre photo : deux coupures egales et symetriques autour de l'angle inferieur droit.

Colonne de gauche et navigation

- [x] Garder un seul groupe d'icones (LinkedIn, GitLab, theme, langue, contact, CV) ; survol en hexagone vertical.
- [x] Mobile : icones a 48 px, Contact/CV epingles en haut apres defilement.
- [x] Desktop : six icones a 44 px, espacement augmente, adaptation aux ecrans de faible hauteur.
- [x] Mobile/tablette : bouton hexagonal de sections, liens en eventail (quart de cercle), section en cours surlignee.
- [x] Defilement anime vers la section choisie (desactive si « reduire les animations »).

Stack

- [x] Groupe « IA » (Claude Code, Codex, Lovable) avec logos officiels (LobeHub Icons, MIT).
- [x] Harmoniser les icones Jira, MySQL et PHP.

Contact

- [x] Toasts de resultat (10 s), fenetre rouverte en cas d'erreur de validation ou d'echec d'envoi, texte conserve.
- [x] Repli « Ouvrir dans ma messagerie » (adresse pro) et « Copier le message », avec message si aucune messagerie ne s'ouvre.
- [x] Echapper le message de confirmation (il contenait le nom saisi en HTML brut).

Performance et accessibilite

- [x] Images servies comme fichiers (page de 8 Mo a ~64 Ko), captures en WebP.
- [x] Video de demo en MP4 (62 Mo -> 4,9 Mo), chargee a l'ouverture.
- [x] Focus clavier visible partout ; animations reduites respectees.

Lot « web moderne » (2026-11-27, audit `rapports/audit-web-moderne.md`, valide par Jeremy)

- [x] Theme : suit le systeme par defaut (decision de Jeremy), bouton pour forcer l'autre theme, script anti-flash dans `<head>` (`resources/js/theme.js`).
- [x] Fenetres contact, CV et video en `<dialog>` natif avec `showModal()` (`resources/js/dialogs.js`).
- [x] Pas de toast d'erreur quand la fenetre de contact se rouvre ; toasts annonces, 20 s, pause au survol et au focus.
- [x] Carrousel Admiral en scroll snap, captures dans le HTML, position annoncee, points de 24 px (`resources/js/carousel.js`).
- [x] Prechargement de la photo de profil, `font-size-adjust` sur la police.
- [ ] Verifier sur un vrai iPhone (Safari) : fenetres, clic a cote pour fermer, glissement du carrousel.

Reste a faire

- [ ] Relecture par Jeremy des textes rediges (accroche, roles des projets, descriptions raccourcies).
- [ ] Ajouter un resultat concret et chiffre par projet (donnees reelles uniquement).
- [ ] Supprimer les fichiers devenus inutiles (voir `TECHNICAL_DEBT.md` D-006).
- [ ] Harmoniser la page Mentions legales avec le nouveau systeme de formes.
- [ ] Decouper le lot en commits atomiques (UX, bouton de sections, contact, docs, gouvernance) et ouvrir la merge request.
    - [x] Commits atomiques faits le 2026-11-27 (outillage, medias, icones, interface, documentation ; gouvernance sur `docs/governance-sync-2026-11-27`).
    - [x] Ouvrir les pull requests vers `main` (gouvernance d'abord, puis le lot UX) : PR #3 et #4.
- [x] Filet fin sur les libelles de la stack, comme les tags de projet.

## Phase 7 - Mise en production v3.0

Evaluation du 2026-11-27 : code pret (tests, build, audit de contraste au vert), mais mise en ligne conditionnee aux etapes ci-dessous.

- [ ] Smoke test Safari macOS et iPhone (seul Chrome a ete verifie) : accueil, bouton de sections, fenetres CV et contact, clair/sombre, FR/EN.
- [ ] Relecture rapide des textes rediges par Jeremy.
- [x] Merger les pull requests vers `main` (PR #3 en squash, PR #4 en merge commit, 2026-11-27).
- [ ] Rotation des secrets exposes sur l'ancien depot GitLab : cle Mailgun, mot de passe SMTP, `APP_KEY` (mettre a jour le `.env` du serveur).
- [x] Verifier que la racine web Alwaysdata pointe sur `public/` (confirme par Jeremy le 2026-11-27) (le deploiement rsync envoie tout le dossier du projet : `docs/`, `tests/`, `.claude/`, `CHANGELOG.md`...).
- [ ] `./scripts/deploy.sh --dry-run`, puis deploiement depuis `main` a jour, sans modification non commitee.
- [ ] Verification en production : envoi reel du formulaire, ouverture des CV, video de demo, mode sombre, version anglaise.
- [ ] Passer la rubrique « Non publie » de `CHANGELOG.md` en version 3.0 datee, et poser le tag `v3.0`.

## Plan du jour - execution rapide (2026-11-27)

### Priorites du jour

- [x] Auditer la page d'accueil (desktop, mobile) et definir les quick wins UX.
- [x] Appliquer les quick wins : premiere impression, lisibilite, contenus, performance.
- [x] Integrer les retours de Jeremy : icones regroupees, Contact/CV epingles sur mobile, formes, cadre photo, stack IA.
- [x] Ajouter le bouton de sections mobile et le defilement anime.
- [x] Fiabiliser le formulaire de contact (toasts, repli messagerie, bilinguisme).
- [x] Synchroniser les fichiers de gouvernance avec l'etat reel.
- [ ] Relire les textes rediges et decouper le lot en commits.

### Plan de travail aujourd'hui

- [x] Convertir images et video, supprimer le base64 inline.
- [x] Mettre en place la couche de design `--pf-*` en fin de `app.css`.
- [x] Corriger les bugs trouves en route (carrousel, fenetre CV en sombre, pied de page, bouton du formulaire en sombre, fenetre CV masquee sur mobile, survols illisibles en sombre).
- [x] Ajouter les tests de non-regression (carrousel, contact FR/EN, echec d'envoi, validation FR).
- [x] Creer `CHANGELOG.md` et `docs/PARCOURS_DE_LECTURE.md`.

### A remettre a demain si besoin

- [ ] Relecture des textes et ajout de resultats chiffres par projet.
- [x] Commits atomiques.
- [x] Pull requests vers `main` mergees (#3, #4).
- [ ] Mise en production (phase 7).
- [ ] Bascule de l'adresse de contact pro sur Alwaysdata.
- [x] Mentions legales : declaration de Google Analytics.
- [ ] Decision sur Google Analytics : bandeau de consentement ou mesure sans cookie.

## Definition de pret pour demain

- Le lot UX est relu par Jeremy et decoupe en commits par sujet.
- La prochaine priorite est identifiee : adresse pro ou conformite Analytics.
- La roadmap et `CHANGELOG.md` refletent l'etat reel du travail.
- Les limites non traitees sont documentees (`TECHNICAL_DEBT.md`).

## Definition of done globale

- Comportements critiques valides par tests et checks manuels.
- Documentation de gouvernance a jour.
- `CHANGELOG.md` mis a jour pour chaque changement notable.
- Roadmap synchronisee avec l'etat reel du projet a chaque lot et a chaque journee de travail.
- Les sections dynamiques de priorisation ont ete re-evaluees selon l'avancement reel.
- Aucune exposition de secret dans le repository.
