# Portfolio - AI Development Instructions

> Derniere mise a jour : 2026-11-27
> Ce fichier est la source de verite de gouvernance IA du projet.
> Chemin : `.github/copilot-instructions.md`
> S'applique a tout assistant IA travaillant sur le depot (GitHub Copilot, Claude Code, etc.). `CLAUDE.md` a la racine renvoie ici.

---

## Regle de conformite des reponses IA

Chaque reponse commence immediatement par un marqueur de conformite (aucun texte avant).
Cette regle est obligatoire, pas facultative. Elle s'applique a toutes les reponses, y compris les messages courts, confirmations, corrections et mises a jour rapides.

Marqueurs autorises :

- `🤖📘` : conformite conjointe instructions IA + guidelines (cas par defaut)
- `🌿` : verification discipline de travail locale effectuee avant modification
- `🪲` : consultation du registre incident effectuee avant correction de bug
- `🧪` : consultation gouvernance des tests effectuee avant reponse sur tests

Format : `<marqueur> contenu`
Exemple : `🤖📘 🌿 Reponse...`

Regles d'application :

- Avant d'envoyer une reponse, verifier qu'elle commence bien par un marqueur autorise et qu'aucun texte ne precede ce marqueur.
- Si un marqueur manque, corriger la reponse avant de la transmettre.
- En cas de reponse relative a un bug, a des tests ou a une verification de travail local, utiliser le marqueur correspondant selon le contexte.
- En cas de reponse sur l'etat d'un lot de travail, confirmer d'abord le scope reel, la branche active et la roadmap avant de decrire la tache en cours.
- Toute reponse traitant de la roadmap, des guidelines, de la gouvernance ou du workflow Git doit commencer immediatement par un marqueur autorise ; le premier token du message doit etre visible sans ambiguite.
- Lorsque la reponse suit une consultation explicite des fichiers de gouvernance, le marqueur doit etre `🤖📘` sauf si le contexte est un bug, une verification locale ou un sujet de test, auquel cas il faut utiliser `🌿`, `🪲` ou `🧪` selon le cas.

---

## Anti-sycophancy (non negociable)

L'assistant ne doit pas :

- Inventer des signatures de methodes ou APIs non verifiees dans le code.
- Confirmer une supposition sans verification prealable dans les fichiers.
- Pretendre qu'une correction est complete si des cas limites restent ouverts.
- Produire du code placeholder presente comme complet.

L'assistant doit :

- Signaler explicitement les limites et incertitudes.
- Refuser une demande non securisee.
- Lire le code reel avant toute modification.

---

## Pre-verifications obligatoires

- Consulter `docs/governance/roadmap.md`.
- Consulter `docs/governance/ARCHITECTURE.md`.
- Avant correction de bug : consulter `docs/reports/INCIDENT_LOG.md`.
- Avant sujet tests/non-regression : consulter `docs/governance/Guidelines.md` (section tests).

---

## Regle de conformite bilingue du portfolio

- Tout texte affiche a l'ecran doit respecter le modele bilingue du site : version anglaise et francaise obligatoire.
- Les messages renvoyes par le serveur (confirmations, erreurs, validation) sont concernes au meme titre que les vues.
- Verification obligatoire avant cloture : `node tools/check-missing-translations.cjs`.
- Les ALT text, labels, titres, boutons, messages d'erreur, tooltips, placeholders et textes de navigation doivent etre fournis dans les deux langues.
- Aucune nouvelle interface ou modification de texte UI ne doit etre introduite sans version FR + EN conforme au modele du site.
- En cas d'ecart avec le modele existant, l'assistant doit prioriser la version bilingue et ne pas laisser de contenu monolingue.

---

## Juridiction documentaire

Perimetres exclusifs :

- `copilot-instructions.md` : comportement IA.
- `docs/governance/ARCHITECTURE.md` : decisions techniques verrouillees.
- `docs/governance/Guidelines.md` : regles de code quotidiennes.
- `docs/governance/roadmap.md` : pilotage produit et jalons.
- `docs/governance/TECHNICAL_DEBT.md` : dette technique priorisee.
- `CHANGELOG.md` : historique des changements (livres et non publies).
- `docs/PARCOURS_DE_LECTURE.md` : guide de lecture du code (non normatif).

Ordre de priorite en cas de conflit :

1. `copilot-instructions.md`
2. `ARCHITECTURE.md`
3. `Guidelines.md`
4. `roadmap.md`

---

## Securite documentaire

- Aucun secret, token ou mot de passe dans le depot.
- Aucune fuite de `.env`, `.git` ou logs sensibles.
- Les donnees operationnelles sensibles restent hors depot.

---

## Discipline de travail

- Chaque modification est bornee a un perimetre annonce.
- Chaque lot doit rester atomique : un sujet, un objectif, un commit et une branche coherents ; il ne faut pas melanger photo, UX/UI, docs et corrections de bug dans un meme lot sans justification expresse.
- Validation locale minimale avant cloture : tests cibles + verification manuelle utile.
- Avant toute implementation, verifier explicitement la branche active (`git branch --show-current`) et confirmer qu'elle correspond au scope demande.
- Lorsqu'un lot touche a l'interface, au contenu visible ou a la UX du portfolio, l'assistant doit traiter la roadmap comme un artefact obligatoire de suivi :
    1. ajouter ou enrichir les taches correspondantes dans la section Plan par phases ;
    2. ajouter les taches pertinentes dans les sections Priorites, Priorites du jour et Plan de travail aujourd'hui ;
    3. realiser le travail puis marquer les taches accomplies avec `[x]` dans les sections concernées ;
    4. retirer de la priorisation generale les taches devenues accomplies si elles ne sont plus pertinentes comme priorites actives ;
    5. ne pas considerer le lot comme termine tant que la roadmap n'est pas synchronisee avec l'etat reel.
- L'absence de mise a jour de la roadmap dans le meme cycle de travail est consideree comme une erreur de gouvernance et doit etre corrigee immediatement.
- Si la branche ne correspond pas au sujet, creer une branche dediee avant de coder et le signaler clairement.
- Tout incident significatif est trace dans `docs/reports/INCIDENT_LOG.md`.
- Lorsqu'il faut lancer un environnement de dev local, essayer d'abord `php artisan serve` ; si l'execution echoue sur un blocage OPcache (`Unable to create opcache lock file`), relancer avec `php -d opcache.enable=0 -d opcache.enable_cli=0 -d opcache.jit=0 artisan serve ...` et documenter le cas dans `docs/reports/SOLUTIONS_LOG.md`.
- Convention de commits : `feat:`, `fix:`, `docs:`, `refactor:`, `test:`, `chore:`, `security:`.
- Chaque changement notable est ajoute a `CHANGELOG.md` (rubrique « Non publie ») dans le meme cycle de travail que le changement.
- Les mises a jour de gouvernance demandees explicitement par Jeremy peuvent etre preparees sur la branche en cours ; elles sont ensuite commitees a part (commit `docs:` dedie, integre d'abord sur `main`), jamais melangees a un commit de code.
- Les fichiers de gouvernance ne doivent pas etre pousses dans une branche fonctionnelle comme partie d'un lot metier sans flux documentaire dedie et validation explicite ; en cas de doute, ils restent locaux et ne sont pas inclus dans le push.
- Ces regles de travail sont des obligations de fonctionnement. Elles ne doivent pas etre contournees par des reponses rapides, des hypotheses non verifiees ou une interpretation laxiste du scope.

---

## Fichiers a surveiller

- `README.md`
- `docs/governance/ARCHITECTURE.md`
- `docs/governance/Guidelines.md`
- `docs/governance/roadmap.md`
- `docs/governance/TECHNICAL_DEBT.md`
- `docs/reports/INCIDENT_LOG.md`
- `docs/reports/SOLUTIONS_LOG.md`
- `CHANGELOG.md`
- `docs/PARCOURS_DE_LECTURE.md`
