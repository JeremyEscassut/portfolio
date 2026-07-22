# Roadmap - Portfolio

## Horizon

- Version cible : v1.1 Stabilisation qualite et gouvernance.
- Depot de reference : GitLab (`origin`).

## Priorites

1. Corriger les incoherences fonctionnelles et tests critiques.
2. Stabiliser le flux contact e-mail (dev + prod).
3. Fiabiliser le parcours CV (ouverture, telechargement, preview).
4. Garantir un affichage fiable sur navigateurs/appareils principaux.
5. Consolider la documentation de gouvernance et d'exploitation.

## Plan par phases

## Phase 1 - Stabilisation immediate (Semaine 1)

- [x] Realigner tests contact avec le comportement reel.
- [x] Corriger le message de succes du formulaire (espacement).
- [ ] Ajouter test non-regression du honeypot (`hp` rempli => pas d'envoi mail).
- [ ] Ajouter test de validation (`email` invalide, `message` trop long).
- [ ] Ajouter test de route locale `fr/en` pour endpoint contact.

Criteres de sortie :

- `php artisan test --filter=ContactFormTest` passe.
- Flux contact nominal couvert par tests.

## Phase 2 - Fiabilisation parcours utilisateur (Semaine 1-2)

- [ ] Ajouter tests Feature pour page CV :
    - statut 200,
    - lien PDF present,
    - lien download present,
    - iframe de preview presente.
- [ ] Ajouter test d'accessibilite minimale (presence labels/contact controls).

Criteres de sortie :

- Tests CV automatises en vert.
- Verification manuelle locale validee.

## Phase 3 - Matrice navigateurs et appareils (Semaine 2)

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

Criteres de sortie :

- 0 bug bloquant sur matrice cible.
- Bugs mineurs traces dans `TECHNICAL_DEBT.md`.

## Phase 4 - Gouvernance et hygiene continue (Semaine 2+)

- [ ] Ajouter pipeline CI GitLab minimale : tests Laravel + build Vite.
- [ ] Mettre en place une cadence de revue des incidents/solutions.
- [ ] Formaliser checklist pre-deploiement dans docs (sans runbook DRP).

## Definition of done globale

- Comportements critiques valides par tests et checks manuels.
- Documentation de gouvernance a jour.
- Aucune exposition de secret dans le repository.
