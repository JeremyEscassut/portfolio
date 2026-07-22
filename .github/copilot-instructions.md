# Portfolio - AI Development Instructions

> Derniere mise a jour : 2026-07-22
> Ce fichier est la source de verite de gouvernance IA du projet.
> Chemin : `.github/copilot-instructions.md`

---

## Regle de conformite des reponses IA

Chaque reponse commence immediatement par un marqueur de conformite (aucun texte avant).

Marqueurs autorises :

- `🤖📘` : conformite conjointe instructions IA + guidelines (cas par defaut)
- `🌿` : verification discipline de travail locale effectuee avant modification
- `🪲` : consultation du registre incident effectuee avant correction de bug
- `🧪` : consultation gouvernance des tests effectuee avant reponse sur tests

Format : `<marqueur> contenu`
Exemple : `🤖📘 🌿 Reponse...`

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
- Validation locale minimale avant cloture : tests cibles + verification manuelle utile.
- Tout incident significatif est trace dans `docs/reports/INCIDENT_LOG.md`.
- Convention de commits : `feat:`, `fix:`, `docs:`, `refactor:`, `test:`, `chore:`, `security:`.

---

## Fichiers a surveiller

- `README.md`
- `docs/governance/ARCHITECTURE.md`
- `docs/governance/Guidelines.md`
- `docs/governance/roadmap.md`
- `docs/governance/TECHNICAL_DEBT.md`
- `docs/reports/INCIDENT_LOG.md`
- `docs/reports/SOLUTIONS_LOG.md`
