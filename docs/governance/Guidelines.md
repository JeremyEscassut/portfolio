# Guidelines - Portfolio

Chemin de reference : `docs/governance/Guidelines.md`

## Perimetre de ce document

Ce fichier definit les regles de production du code.

Inclus : conventions de code, qualite, securite applicative, tests, maintenabilite.
Exclu : arbitrages techniques (`ARCHITECTURE.md`), priorites et phases (`roadmap.md`), suivi de dette (`TECHNICAL_DEBT.md`).

## Anti-recurrence bugs

- Tout incident significatif corrige est trace dans `docs/reports/INCIDENT_LOG.md`.
- Chaque correctif inclut un test de non-regression ou une justification explicite.
- Avant correction d'un incident recurrent, relire l'entree existante.

## Securite applicative

### Controle d'acces

- Le backend est l'arbitre final des autorisations.
- Regle de base : deny by default en cas d'ambiguite.

### Authentification

- Valider le type et l'usage des tokens, pas seulement leur validite cryptographique.
- Separation stricte des politiques par environnement (dev, staging, prod).
- Aucun secret en dur dans le code ni dans une documentation versionnee.

### Defense operationnelle minimale

- Validation stricte des entrees.
- Journalisation exploitable sans fuite de donnees sensibles.
- Erreurs techniques masquees en production.

## Gouvernance des tests metier

### Declencheurs obligatoires

- Toute evolution metier declenche une revue d'impact tests.
- Toute correction d'incident declenche une non-regression.

### Strategie minimale recommandee

- Identifier les parcours critiques avant d'ecrire les tests.
- Couvrir a minima :
    - logique metier,
    - API/integration,
    - smoke E2E manuelle.
- Prioriser par impact et frequence.

### Quality gates

- Pull request : lint + build + tests requis, bloquants.
- Un test critique en echec bloque la livraison.
- Campagne de regression etendue en differé (nightly) quand CI activee.

### Definition de termine

Une evolution n'est terminee que si le comportement cible est implemente, les tests impactes sont realignes, `CHANGELOG.md` est mis a jour (rubrique « Non publie ») et la roadmap reflete l'etat reel.

## Maintenabilite

- Favoriser des modules a responsabilite claire.
- Decouper progressivement les fichiers trop volumineux.
- Eviter l'abstraction prematuree.

## Documentation du code

- Priorite a un code auto-explicatif.
- Documenter surtout les zones critiques (securite, routage, mail, deploy) et les methodes non evidentes (calculs, contournements de cascade CSS, comportements JS).
- Chaque fichier JS/Blade principal commence par un commentaire qui resume son role.
- Une information, une source principale.
- `docs/PARCOURS_DE_LECTURE.md` est tenu a jour quand un fichier ou un flux important est ajoute ou deplace.
- `CHANGELOG.md` recoit chaque changement notable (Ajoute, Modifie, Corrige, Supprime, Securite, Performance, Documentation).

## Guidelines UI - Section Stack

### Categorisation obligatoire

- Chaque technologie doit etre classee dans une categorie existante : `Front`, `Back`, `Autres` ou `IA` (outils d'IA utilises pour concevoir et developper ; categorie validee le 2026-11-27).
- Ne pas introduire d'autre sous-bloc ad hoc (exemple : "Recently used") si les technologies peuvent etre classees dans les categories standards.
- La liste est definie dans le tableau `$stack` de `resources/views/home.blade.php`.

### Presentation visuelle

- Chaque technologie est affichee dans une card `skill-tile` avec :
    - une icone,
    - un label via `data-name`.
- Interdiction d'utiliser des tags texte seuls pour une technologie dans cette section.

### Style des icones

- Les icones de la section Stack doivent etre monochromes (noir/blanc selon le theme) en mode clair et en mode sombre.
- Les SVG doivent utiliser `currentColor` (pas de couleur de marque hardcodee) sauf exception explicitement validee.
- Gabarit recommande pour les nouveaux SVG : `viewBox` carre (24x24 de preference), contenu centre, epaisseur de trait equivalente a 2 px sur 24.
- Taille d'affichage commune fixee dans `app.css` (1.9rem mobile, 2.4rem desktop) : pas de surcharge de taille par icone.
- Un logo officiel recupere d'une bibliotheque externe indique sa source et sa licence en commentaire en tete du fichier SVG.
- La lisibilite doit rester coherente entre toutes les cards (pas d'icone visuellement plus grande que les autres).

## Guidelines UI - Systeme de design

### Couleurs et typographie

- Les couleurs passent par les jetons `--pf-*` definis en fin de `resources/css/app.css`, avec leur pendant `.dark`.
- Le mode sombre depend de la classe `.dark` sur `<html>`, pas de la preference systeme. Les classes Tailwind `dark:` suivent cette classe (`@custom-variant dark` dans `app.css`).
- Police unique : Schibsted Grotesk (Bunny Fonts). Texte courant 16 px, lignes de 68 caracteres maximum.
- Pas de texte tout en majuscules pour les titres et libelles.

### Formes

- Hexagone : elements d'action (boutons `.pf-btn`, bouton de sections, fleches du carrousel, fond des icones au survol). La forme est dessinee par `::before` pour ne pas couper le contour de focus.
- Rectangle a angles de 6 px : surfaces et medias (images, carrousel, fenetres, champs, toasts).
- Rectangle a angles de 4 px : etiquettes passives (tags de projet, noms de technos, badge de langue), avec un filet fin (`--pf-rule`), sans effet au survol.
- Boutons : principal = hexagone plein (couleur d'action) ; secondaire = hexagone en contour de 1,5 px (couleur d'action) rempli de la couleur de fond, teinte au survol.
- Cercles : uniquement les points de position du carrousel.

### Cascade CSS

- Les nouvelles regles vont dans le bloc « UX quick wins » en fin de `app.css`.
- Les anciennes regles utilisent souvent `!important` et des selecteurs larges : prefixer les nouvelles (`html body #content ...`, `#home-aside ...`) et documenter en commentaire la regle contournee.
- Un element place directement dans `<body>` subit `.dark body > div { position: static !important }` : le positionner avec un selecteur a identifiant ou positionner ses enfants.

### Accessibilite

- Focus clavier visible sur tout element interactif.
- Cibles tactiles de 44 px minimum (48 px pour les icones de la colonne de gauche sur mobile).
- Toute animation non declenchee par l'utilisateur, et tout defilement anime, respecte `prefers-reduced-motion`.
- Contraste minimal 4,5:1 pour le texte et 3:1 pour les icones, y compris au survol et au focus, en clair et en sombre. Controle avant livraison d'un changement visuel : `tools/qa-contrast-audit.js` (mode d'emploi en tete du fichier).

## Langue et nomenclature

- Code et commentaires techniques : anglais par defaut.
- Interface utilisateur : respecter le modele bilingue FR/EN du site ; tout texte a l'ecran doit etre fourni en anglais et en francais.
- ALT text, labels accessibles, titres, boutons, messages d'erreur, placeholders et textes de navigation : obligatoirement bilingues.
- Toute nouvelle chaine de texte visible ou accessible doit etre ajoutee dans les fichiers de traduction existants : `resources/lang/fr.json` et `resources/lang/en.json`.
- Les traductions doivent toujours etre utilisees via le mecanisme de traduction du projet (`__()` / `trans()`), et non pas en dur dans les vues ou composants.
- Un texte traduit injecte dans une expression Alpine/JavaScript passe par `@js(__('cle'))` (sinon une apostrophe casse le script).
- Les messages renvoyes par le serveur (flash, validation) sont traduits aussi : `resources/lang/fr/validation.php` pour la validation.
- Controle avant livraison : `node tools/check-missing-translations.cjs` doit indiquer « All keys are synchronized ».
- En cas de modification d'un texte existant, la version FR et EN doit etre mise a jour de maniere coherente et preserve la meme semantique dans les deux langues.
- Une modification UI ne doit jamais produire un rendu monolingue ou un texte hors traduction ; la coherence avec la base de code et le comportement actuel du site est obligatoire.
- Documentation gouvernance : francais.

## Workflow Git et livraison

- Remote officiel : GitHub (`origin`, depot prive `JeremyEscassut/portfolio`). GitLab est abandonne depuis le 2026-11-27.
- Convention de branches : `feature/*`, `fix/*`, `chore/*`, `docs/*`.
- Convention de commits : `feat`, `fix`, `docs`, `refactor`, `test`, `chore`, `security`.

### Conformite IA et enforcement des regles

- Les instructions de `copilot-instructions.md` et de ce document sont des obligations de travail, pas des recommandations facultatives.
- Toute reponse doit commencer par un marqueur de conformite autorise ; aucune reponse courte, confirmation ou correction ne doit enfreindre cette regle.
- Avant d'envoyer une reponse, l'assistant doit verifier qu'elle respecte ce format. Si le marqueur manque, la reponse doit etre corrigee avant transmission.
- Toute reponse traitant de la roadmap, des guidelines, de la gouvernance ou du workflow Git doit commencer immediatement par un marqueur autorise ; l'absence de marqueur est consideree comme une violation de procedure.
- Les fichiers de gouvernance ne doivent pas etre inclus dans un push de lot fonctionnel sans flux documentaire dedie et validation explicite ; en cas de doute, ils restent locaux et ne sont pas pousses.
- Les changements doivent rester atomiques et alignes sur un seul sujet fonctionnel ; on ne melange pas feature photo, UX/UI, docs et corrections dans un meme lot sans justification explicite.
- En cas d'ecart avec ces regles, l'assistant doit corriger le comportement avant de poursuivre la tache.

### Objectif

Maintenir un historique clair, des branches lisibles et des integrations sures.

### Regles de base

- Verifier systematiquement la branche active avant toute implementation (`git branch --show-current`) et confirmer qu'elle correspond au scope demande.
- Si la branche ne correspond pas au sujet en cours, creer une branche dediee avant de coder et l'annoncer explicitement.
- Si l'objectif est un lancement local de l'environnement de dev, valider d'abord les pre-requis runtime ; si PHP bloque sur OPcache (`Unable to create opcache lock file`), relancer avec OPcache desactive et documenter la procedure de secours dans `docs/reports/SOLUTIONS_LOG.md`.
- Preferer des commits atomiques et thematiques.
- Ne jamais mixer plusieurs sujets fonctionnels dans un meme lot de travail sans justification explicite ; la branche et le commit doivent rester lisibles et coherents.
- Pousser les changements quand la fonctionnalite est integree et validee (build/tests adaptes).
- Ne pas lancer de merge sans revue minimale et sans accord explicite.
- Les documents de reference doivent rester a jour sur `main` en continu ; ils ne doivent jamais vivre uniquement dans une branche de fonctionnalite.
- Toute mise a jour transverse de `.github/copilot-instructions.md`, `docs/governance/Guidelines.md`, `docs/governance/ARCHITECTURE.md`, `docs/governance/roadmap.md`, `docs/reports/INCIDENT_LOG.md` ou `docs/daily_plan/` doit etre isolee du code metier et integree d'abord sur `main`, puis propagee via `git merge main` dans les branches actives.

### Convention de branches recommandee

- `feature/<scope-court>` pour une fonctionnalite.
- `fix/<scope-court>` pour un correctif.
- `docs/<scope-court>` pour la documentation.

### Strategie de merge GitHub

- Cible par defaut : pull requests vers `main`.
- Methode recommandee : `Squash and merge` pour conserver un historique de `main` lisible.
- Option autorisee : `Merge commit` uniquement pour les lots multi-commits dont la granularite apporte une valeur de lecture.
- `Rebase and merge` est reserve aux branches courtes avec historique propre et sans conflit fonctionnel.
- La branche source doit etre synchronisee avec `main` juste avant merge (`git fetch` puis `git merge main` sur la branche de travail).
- En cas de conflit, la resolution doit etre testee localement avant mise a jour de la pull request.

### Conditions obligatoires avant merge

- Pipeline de la pull request en vert sur les checks obligatoires (au minimum build + tests cibles).
- Au moins une revue explicite si la modification touche routage, formulaire contact, mail ou securite.
- Aucun secret, token ou donnee sensible dans les fichiers modifies.
- Roadmap et logs documentaires synchronises quand le lot modifie le perimetre produit.

### Synchronisation de la roadmap

- La version la plus recente de `docs/governance/roadmap.md` fait foi et doit refleter l'etat reel du projet.
- Cette synchronisation est obligatoire et doit etre faite systematiquement au fur et a mesure de l'avancement, pas seulement a la fin d'un lot.
- Des qu'une tache, un item de DoD, une priorite, une phase ou un angle mort est termine, `docs/governance/roadmap.md` doit etre mis a jour dans le meme cycle de travail, avant annonce de fin de lot.
- La section `Plan par phases` est la source de verite des taches cochees ou non cochees ; elle doit rester la reference stable pour le suivi du travail.
- Dans `Plan par phases`, l'assistant ne doit jamais supprimer une tache existante, ni retirer une ligne d'un item de phase. Il ne peut qu'ajouter de nouvelles taches si le scope evolue, et cocher une tache existante lorsqu'elle est terminee.
- Les sections `Horizon`, `Priorites`, `Plan de priorisation - Reste de journee`, `Definition de pret pour demain` et `Definition of done globale` sont des vues dynamiques. Elles doivent etre re-evaluees et mises a jour a chaque changement de scope, a chaque fin de journee et au debut de chaque nouvelle session de travail.
- Lorsqu'une action listée dans `Plan de priorisation - Reste de journee` est executee, l'assistant doit la cocher immédiatement dans la roadmap, sans attendre la fin du lot ni une étape ultérieure.
- Lorsqu'une priorite de la section `Priorites` est terminee, elle doit etre retiree immédiatement de cette liste et remplacee par la priorite suivante si besoin ; aucune priorite terminee ne doit rester dans la liste sous forme de statut `En cours` ou `A faire`.
- Si une priorite haute dans la liste est terminee, elle doit etre retiree de la section `Priorites` et remplacee par la priorite suivante ; les priorites du jour doivent toujours etre re-calibrees selon l'avancement reel.
- Le `DoD pret pour demain` est un snapshot quotidien ; il ne doit pas rester fige d'un jour sur l'autre et doit etre rewrite a chaque nouvelle journee de travail.
- Cette mise a jour est une digression documentaire : elle doit etre committee separement du code metier et integree sur `main` sans attendre la fin de la branche fonctionnelle.
- Un lot dont la roadmap n'est pas synchronisee n'est pas considere comme termine.

### Cloture d'un lot

- Un lot ne peut etre declare termine que s'il est a la fois code, valide et fige dans Git.
- Concretement : validations ciblees passantes, commit atomique realise, et push effectue des que le remote est disponible et que le sujet n'est pas explicitement garde local.
- Si le commit ou le push ne peut pas etre fait immediatement, le lot ne doit pas etre annonce comme termine ; il doit etre annonce comme `pret a figer` ou `en attente de transfert Git`.
- En fin de journee, il ne doit pas rester de modifications locales ambigues sur un lot annonce comme referme. A defaut, un commit `wip:` explicite est obligatoire pour geler l'etat.

### Checklist avant push

- Le scope est coherent avec le nom de branche.
- Les fichiers modifies appartiennent au meme sujet fonctionnel.
- Les validations minimales passent (build/tests cibles).
- Le message de commit est explicite (`feat:`, `fix:`, `docs:`, etc.).
- Le lot annonce comme termine est integralement couvert par le commit prepare.
- Aucune modification hors sujet ne reste melangee au working tree du lot a pousser.
- Si des fichiers documentaires de reference ont change, verifier qu'ils ont bien ete figes sur `main` et que la branche metier courante a ete resynchronisee avant annonce de cloture.
