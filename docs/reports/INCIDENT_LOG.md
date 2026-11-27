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

---

## INC-2026-07-30-001

- ID: INC-2026-07-30-001
- Date: 2026-07-30
- Environnement: dev
- Symptomes: le bouton CV sur la page d'accueil ne provoquait aucune ouverture de modale dans le navigateur, malgré la présence du markup.
- Impact: l'utilisateur ne pouvait pas choisir de version de CV depuis l'accueil, ce qui bloquait l'accès au téléchargement.
- Cause racine: le déclencheur initial reposait sur un flux d'événements trop fragile entre le markup Blade, Alpine et le DOM, ce qui ne garantissait pas l'ouverture de la modale à l'exécution.
- Correctif applique: bascule vers un déclencheur robuste via un lien ciblant `#cv-selector-modal` et un fallback JavaScript `window.openCvSelectorModal()` pour synchroniser l'état de la modale et l'URL. Ajout d'une option CV Product Owner dans la modale avec le PDF associé.
- Tests de non-regression: `npm run build`, `php artisan test --filter=CvPageTest`.
- Statut: closed
- References: `resources/views/home.blade.php`, `resources/js/app.js`, `tests/Feature/CvPageTest.php`.

---

## INC-2026-11-27-001

- ID: INC-2026-11-27-001
- Date: 2026-11-27
- Environnement: dev + prod
- Symptomes: erreur console `Uncaught SyntaxError: Unexpected identifier 'écran'` au chargement de l'accueil.
- Impact: libelles accessibles des points du carrousel CRM non generes.
- Cause racine: texte traduit contenant une apostrophe (« captures d'écran ») insere tel quel dans une expression Alpine.
- Correctif applique: `@js(__('cle'))` pour tout texte traduit injecte dans du JavaScript.
- Tests de non-regression: `HomePageContentTest::carousel_labels_with_apostrophes_are_escaped_for_alpine`.
- Statut: closed
- References: `resources/views/home.blade.php`, `docs/reports/SOLUTIONS_LOG.md` (8).

---

## INC-2026-11-27-002

- ID: INC-2026-11-27-002
- Date: 2026-11-27
- Environnement: dev + prod
- Symptomes: en mode sombre, la fenetre « Choisissez un CV » s'ouvrait en bas de la page au lieu d'etre centree.
- Impact: choix du CV difficile a trouver en mode sombre.
- Cause racine: `.dark body > div { position: static !important }` annulait le `position: fixed` de la fenetre.
- Correctif applique: `html.dark #cv-selector-modal { position: fixed !important; }`.
- Tests de non-regression: verification manuelle (rendu CSS), voir `TECHNICAL_DEBT.md` D-005.
- Statut: closed
- References: `resources/css/app.css`, `docs/reports/SOLUTIONS_LOG.md` (9).

---

## INC-2026-11-27-003

- ID: INC-2026-11-27-003
- Date: 2026-11-27
- Environnement: prod
- Symptomes: apres un echec d'envoi ou une erreur de validation, la fenetre de contact restait fermee ; l'erreur s'affichait en haut de la page (invisible sur mobile) ; messages en francais uniquement.
- Impact: le visiteur pouvait croire son message envoye ou devoir tout ressaisir.
- Cause racine: le drapeau `openContact` du controleur n'etait lu nulle part ; messages ecrits en dur dans `ContactController`.
- Correctif applique: toasts, fenetre rouverte avec la saisie conservee, repli `mailto:` / copie, messages et validation traduits.
- Tests de non-regression: `ContactFormTest` (message EN, echec d'envoi, repli affiche, validation FR).
- Statut: closed
- References: `app/Http/Controllers/ContactController.php`, `resources/js/contactFeedback.js`, `resources/views/home.blade.php`.

---

## INC-2026-11-27-004

- ID: INC-2026-11-27-004
- Date: 2026-11-27
- Environnement: prod
- Symptomes: le message de confirmation du formulaire etait insere en HTML brut (`{!! session('status') !!}`) alors qu'il contient le nom saisi par le visiteur.
- Impact: injection HTML/JS possible dans la page du visiteur lui-meme (XSS reflechi).
- Cause racine: affichage non echappe.
- Correctif applique: affichage echappe (`{{ }}`) dans le toast.
- Tests de non-regression: a ajouter (envoi d'un nom contenant du HTML, verification de l'echappement).
- Statut: monitoring
- References: `resources/views/home.blade.php`.

---

## INC-2026-11-27-005

- ID: INC-2026-11-27-005
- Date: 2026-11-27
- Environnement: dev
- Symptomes: en mode sombre, le libelle du bouton « Envoyer » de la fenetre de contact etait illisible (texte clair sur fond clair).
- Impact: action principale du formulaire illisible.
- Cause racine: `.dark [role="dialog"] button { color: var(--accent-text) !important }` plus specifique que la couleur du bouton.
- Correctif applique: selecteurs a deux classes (`.pf-btn.pf-btn--primary`) pour les couleurs des boutons.
- Tests de non-regression: verification manuelle clair/sombre de la fenetre de contact.
- Statut: closed
- References: `resources/css/app.css`.

---

## INC-2026-11-27-006

- ID: INC-2026-11-27-006
- Date: 2026-11-27
- Environnement: dev + prod
- Symptomes: sur mobile en mode sombre, la fenetre « Choisissez un CV » passait sous le bandeau de section collant et sous les icones Contact/CV epinglees.
- Impact: titre et debut de la fenetre masques.
- Cause racine: `.dark body > div` force aussi `z-index: auto !important` ; le correctif de position (INC-2026-11-27-002) ne retablissait pas l'ordre d'empilement.
- Correctif applique: `html.dark #cv-selector-modal { z-index: 10000 !important; }`.
- Tests de non-regression: verification manuelle mobile sombre ; audit de contraste `tools/qa-contrast-audit.js`.
- Statut: closed
- References: `resources/css/app.css`.

---

## INC-2026-11-27-007

- ID: INC-2026-11-27-007
- Date: 2026-11-27
- Environnement: dev + prod
- Symptomes: en mode sombre desktop, survol des choix de CV et de la croix de fermeture illisible ; icones theme/langue noires sur fond sombre au survol.
- Impact: textes et icones illisibles au survol pour les visiteurs en mode sombre.
- Cause racine: (1) les classes Tailwind `dark:` suivaient la preference du systeme et non le bouton theme du site : sur un systeme clair, seul `hover:bg-gray-50` s'appliquait sous un texte clair ; (2) d'anciennes regles forcaient les icones en noir au survol, prevues pour un fond blanc.
- Correctif applique: `@custom-variant dark (&:where(.dark, .dark *))` dans `app.css` ; styles dedies pour les choix de CV et les croix (`.pf-close`) ; survol sombre en hexagone discret avec icones de la couleur du texte ; orange GitLab plus fonce en mode clair.
- Tests de non-regression: `tools/qa-contrast-audit.js` sans resultat en clair et en sombre, mobile (375 px) et desktop (1440 px), page + fenetres CV et contact.
- Statut: closed
- References: `resources/css/app.css`, `resources/views/home.blade.php`, `tools/qa-contrast-audit.js`.
