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

- Merge Request : lint + build + tests requis, bloquants.
- Un test critique en echec bloque la livraison.
- Campagne de regression etendue en differé (nightly) quand CI activee.

### Definition de termine

Une evolution n'est terminee que si le comportement cible est implemente et les tests impactes sont realignes.

## Maintenabilite

- Favoriser des modules a responsabilite claire.
- Decouper progressivement les fichiers trop volumineux.
- Eviter l'abstraction prematuree.

## Documentation du code

- Priorite a un code auto-explicatif.
- Documenter surtout les zones critiques (securite, routage, mail, deploy).
- Une information, une source principale.

## Guidelines UI - Section Stack

### Categorisation obligatoire

- Chaque technologie doit etre classee dans une categorie existante : `Front`, `Back` ou `Autres`.
- Ne pas introduire de sous-bloc ad hoc (exemple : "Recently used") si les technologies peuvent etre classees dans les categories standards.

### Presentation visuelle

- Chaque technologie est affichee dans une card `skill-tile` avec :
    - une icone,
    - un label via `data-name`.
- Interdiction d'utiliser des tags texte seuls pour une technologie dans cette section.

### Style des icones

- Les icones de la section Stack doivent etre monochromes (noir/blanc selon le theme) en mode clair et en mode sombre.
- Les SVG doivent utiliser `currentColor` (pas de couleur de marque hardcodee) sauf exception explicitement validee.
- Gabarit recommande pour les nouveaux SVG : `40x40` avec contenu centre.
- La lisibilite doit rester coherente entre toutes les cards (pas d'icone visuellement plus grande que les autres).

## Langue et nomenclature

- Code et commentaires techniques : anglais par defaut.
- Interface utilisateur : francais (avec support EN quand active).
- Documentation gouvernance : francais.

## Workflow Git et livraison

- Remote officiel : GitLab (`origin`).
- Convention de branches : `feature/*`, `fix/*`, `chore/*`, `docs/*`.
- Convention de commits : `feat`, `fix`, `docs`, `refactor`, `test`, `chore`, `security`.

### Objectif

Maintenir un historique clair, des branches lisibles et des integrations sures.

### Regles de base

- Verifier systematiquement la branche active avant toute implementation.
- Si la branche ne correspond pas au sujet en cours, creer une branche dediee avant de coder.
- Preferer des commits atomiques et thematiques.
- Pousser les changements quand la fonctionnalite est integree et validee (build/tests adaptes).
- Ne pas lancer de merge sans revue minimale et sans accord explicite.
- Les documents de reference doivent rester a jour sur `main` en continu ; ils ne doivent jamais vivre uniquement dans une branche de fonctionnalite.
- Toute mise a jour transverse de `.github/copilot-instructions.md`, `docs/governance/Guidelines.md`, `docs/governance/ARCHITECTURE.md`, `docs/governance/roadmap.md`, `docs/reports/INCIDENT_LOG.md` ou `docs/daily_plan/` doit etre isolee du code metier et integree d'abord sur `main`, puis propagee via `git merge main` dans les branches actives.

### Convention de branches recommandee

- `feature/<scope-court>` pour une fonctionnalite.
- `fix/<scope-court>` pour un correctif.
- `docs/<scope-court>` pour la documentation.

### Strategie de merge GitLab

- Cible par defaut : merge requests vers `main`.
- Methode recommandee : `Squash and merge` pour conserver un historique de `main` lisible.
- Option autorisee : `Merge commit` uniquement pour les lots multi-commits dont la granularite apporte une valeur de lecture.
- `Rebase and merge` est reserve aux branches courtes avec historique propre et sans conflit fonctionnel.
- La branche source doit etre synchronisee avec `main` juste avant merge (`git fetch` puis `git merge main` sur la branche de travail).
- En cas de conflit, la resolution doit etre testee localement avant mise a jour de la MR.

### Conditions obligatoires avant merge

- Pipeline MR en vert sur les checks obligatoires (au minimum build + tests cibles).
- Au moins une revue explicite si la modification touche routage, formulaire contact, mail ou securite.
- Aucun secret, token ou donnee sensible dans les fichiers modifies.
- Roadmap et logs documentaires synchronises quand le lot modifie le perimetre produit.

### Synchronisation de la roadmap

- La version la plus recente de `docs/governance/roadmap.md` fait foi et doit refleter l'etat reel du projet.
- Des qu'une tache, un item de DoD ou un angle mort est cloture, `docs/governance/roadmap.md` doit etre mis a jour dans le meme cycle de travail, avant annonce de fin de lot.
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
