# Portfolio - AI Development Instructions

> Derniere mise a jour : 2026-07-30
> Ce fichier est la source de verite de gouvernance IA du projet.
> Chemin : `.github/copilot-instructions.md`

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
- Chaque lot doit rester atomique : un sujet, un objectif, un commit et une branche cohérents ; il ne faut pas melanger photo, UX/UI, docs et corrections de bug dans un meme lot sans justification expresse.
- Validation locale minimale avant cloture : tests cibles + verification manuelle utile.
- Avant toute implementation, verifier explicitement la branche active (`git branch --show-current`) et confirmer qu'elle correspond au scope demande.
- Si la branche ne correspond pas au sujet, creer une branche dediee avant de coder et le signaler clairement.
- Tout incident significatif est trace dans `docs/reports/INCIDENT_LOG.md`.
- Lorsqu'il faut lancer un environnement de dev local, essayer d'abord `php artisan serve` ; si l'execution echoue sur un blocage OPcache (`Unable to create opcache lock file`), relancer avec `php -d opcache.enable=0 -d opcache.enable_cli=0 -d opcache.jit=0 artisan serve ...` et documenter le cas dans `docs/reports/SOLUTIONS_LOG.md`.
- Convention de commits : `feat:`, `fix:`, `docs:`, `refactor:`, `test:`, `chore:`, `security:`.
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
