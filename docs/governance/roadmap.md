# Roadmap - Portfolio

## Horizon

- Version cible : v1.1 Stabilisation qualite et gouvernance.
- Depot de reference : GitLab (`origin`).

## Priorites

1. Corriger les incoherences fonctionnelles et tests critiques.
2. Stabiliser le flux contact e-mail (dev + prod).
3. Fiabiliser le parcours CV (ouverture, telechargement, preview).
4. Livrer les ajustements UX/UI a forte valeur immediate (hero, boutons, lisibilite desktop).
5. Structurer le storytelling projets (CRM Admiral + PWA Budget) avec medias.
6. Garantir un affichage fiable sur navigateurs/appareils principaux.
7. Consolider la documentation de gouvernance et d'exploitation.

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
- [ ] Formaliser et appliquer le modele bilingue FR/EN pour tous les textes a l'ecran et les ALT text.

## Phase 5 - Evolutions produit et UX (Semaine 2+)

- [ ] Inverser le texte FR/EN du bouton de changement de langue.
- [ ] Deplacer les boutons langue + theme au-dessus des boutons Contact et CV.
- [ ] Augmenter l'espace entre les titres des sections et les boutons en mode desktop.
- [ ] Changer le titre en : `Chef de projet IT | Developpeur Full Stack oriente Produit & UX`.
- [ ] Ajouter React, PostgreSQL et MySQL dans les technologies recentes.
- [ ] Mettre a jour la description profil pour inclure la recherche de roles chef de projet / product owner et enrichir le volet formation (ANSSI, LinkedIn Learning, etc.).
- [ ] Changer l'arriere-plan clair avec un degrade plus agreable.
- [ ] Ajouter une section Projets pour l'experience CRM Admiral (ADM Assurances) sans lien Git, avec carousel de screenshots.
- [ ] Evaluer l'ajout d'un enregistrement ecran CRM Admiral en fonction du poids final deploye (Alwaysdata).
- [ ] Ajouter une section Projets pour la PWA Budget (Lovable) avec lien public.
- [ ] Ajouter un selecteur de CV au clic sur le bouton CV :
    - [ ] version Chef de projet IT,
    - [ ] version Developpeur Full Stack oriente Produit & UX.

## Plan de priorisation - Reste de journee (execution rapide)

### Bloc A - Must have aujourd'hui (faible risque, fort impact)

- [ ] Inverser texte FR/EN du bouton langue.
- [ ] Deplacer boutons langue + theme au-dessus de Contact/CV.
- [ ] Augmenter espacement titres/zone boutons (desktop).
- [ ] Changer le titre principal (hero) vers la formulation cible.
- [ ] Ajouter React, PostgreSQL, MySQL dans la stack/technologies.

### Bloc B - Si temps restant aujourd'hui (impact produit)

- [ ] Mettre a jour description profil (roles vises + experience chef de projet + formations).
- [ ] Appliquer un nouveau degrade de fond clair.

### Bloc C - A planifier sur prochain lot (plus volumineux)

- [ ] Section CRM Admiral avec carousel screenshots.
- [ ] Option enregistrement ecran (decision apres estimation poids/perf).
- [ ] Section PWA Budget avec lien.
- [ ] Selecteur de CV (2 versions).

## Definition de pret pour demain

- Bloc A termine + verifie en desktop/mobile.
- Aucune regression navigation/contact/CV.
- Screenshots de validation conserves pour comparer avant/apres.

## Definition of done globale

- Comportements critiques valides par tests et checks manuels.
- Documentation de gouvernance a jour.
- Aucune exposition de secret dans le repository.
