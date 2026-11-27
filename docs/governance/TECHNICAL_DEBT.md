# TECHNICAL_DEBT - Portfolio

## Regles

- Ce fichier recense la dette technique connue et priorisee.
- Chaque item doit avoir un impact, une priorite et un plan de reduction.

## Backlog priorise

## D-001 - Deprecations PHP 8.5 pendant les tests

- Impact : bruit dans les runs de tests, signal faible degrade.
- Cause : deprecations upstream Laravel/vendor (`PDO::MYSQL_ATTR_SSL_CA`, `ReflectionMethod::setAccessible`).
- Priorite : medium.
- Plan : suivre versions Laravel/collision compatibles PHP 8.5, valider apres upgrade.
- Statut : **clos le 2026-11-27** : apres la mise a jour des dependances (lot securite), les tests ne remontent plus de deprecations.

## D-002 - ContactRequest non utilise

- Impact : duplication potentielle des regles de validation, risque de divergence.
- Cause : validation inline dans `ContactController`.
- Priorite : medium.
- Plan : migrer la validation vers `ContactRequest` et couvrir par tests.

## D-003 - Routage localise complexe

- Impact : risque de regression 404 sur routes localisees.
- Cause : combinaison prefix optional + fallback + redirects compatibilite.
- Priorite : high.
- Plan : documenter clairement les scenarios et renforcer les tests Feature des routes critiques.

## D-004 - Couverture tests front cross-browser insuffisante

- Impact : regressions UI possibles selon navigateur/appareil.
- Cause : absence de suite automatisee cross-browser.
- Priorite : high.
- Plan : definir une matrice de verification manuelle puis etudier Playwright.

> Mise a jour du 2026-11-27 : D-003 avance (carte des URL commentee en tete de `routes/web.php`), D-004 avance (verification Chrome manuelle), nouveaux items D-005 a D-009.

## D-005 - Feuille de style historique tres chargee

- Impact : chaque nouvelle regle doit surencherir (selecteurs a identifiant, `!important`) ; risque de regressions visuelles, surtout en mode sombre.
- Cause : ~2800 lignes de regles anciennes avec `!important` et selecteurs larges (`.dark body > div { position: static !important }`, `html:not(.dark) main#content * { color: inherit !important }`, `.dark [role="dialog"] button { color: ... !important }`).
- Priorite : high.
- Plan : migrer section par section vers les jetons `--pf-*` du bloc « UX quick wins », supprimer les regles anciennes une fois remplacees, verifier clair/sombre a chaque etape.

## D-006 - Fichiers lourds ou inutilises versionnes

- Impact : depot et deploiement alourdis (plus de 130 Mo inutiles).
- Cause : sources remplacees le 2026-11-27 mais conservees : `public/mov/Demo Admiral.mov` (62 Mo), `resources/mov/Demo Admiral.mov`, `resources/png/` (captures et icones CV d'origine), `resources/je_profile.jpeg`, `resources/je_ia_profile.jpg`, `public/images/je_profile.jpeg` (2 Mo), `public/images/je_ia_profile.jpg`, vue `welcome.blade.php` par defaut de Laravel.
- Priorite : medium.
- Plan : supprimer apres validation de Jeremy ; verifier qu'aucune vue ni test ne les reference.

## D-007 - Google Analytics charge sans consentement

- Impact : non-conformite RGPD potentielle ; les mentions legales affirment qu'aucun cookie ni outil d'analyse n'est utilise.
- Cause : balise `gtag.js` dans `resources/views/layouts/app.blade.php`.
- Priorite : high.
- Plan : soit retirer Analytics (ou passer a une mesure sans cookie), soit ajouter un bandeau de consentement et corriger les mentions legales.
- Avancement 2026-11-27 : mentions legales corrigees (Google Analytics 4 declare, FR/EN, commit `fix(legal)` du lot UX). Reste : recueillir le consentement avant de charger `gtag.js` (bandeau), ou remplacer GA par une mesure sans cookie.

## D-008 - Ouverture des fenetres par mecanismes heterogenes

- Impact : comportements fragiles a maintenir (fenetre CV pilotee par le hash d'URL, fenetre contact par evenement + secours via `__x`, contournements de survol par clonage de noeud).
- Cause : correctifs successifs pour contourner des problemes Alpine / CSS.
- Priorite : medium.
- Plan : unifier sur des composants Alpine standards avec gestion du focus, puis supprimer les secours devenus inutiles.

## D-009 - Comportements JS sans tests automatises

- Impact : `sectionFab.js` (eventail, defilement), `contactFeedback.js` (toasts, `mailto:`, copie) et `photoToggle.js` ne sont verifies que manuellement.
- Cause : pas de suite de tests navigateur.
- Priorite : medium.
- Plan : couvrir ces parcours avec Playwright en meme temps que D-004. En attendant, `tools/qa-contrast-audit.js` couvre les contrastes (lancement manuel).

