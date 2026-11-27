# Roadmap - Portfolio

## Horizon

- Version cible : v1.1 Stabilisation qualite et gouvernance.
- Depot de reference : GitLab (`origin`).

> Note de fonctionnement : la section Plan par phases est la source de verite des taches cochees ou non cochees. Les sections Priorites, Plan de priorisation, Definition de pret pour demain et Definition of done globale sont des vues dynamiques a re-evaluer a chaque changement de scope et a chaque nouvelle journee de travail.

## Priorites

1. Fiabiliser le parcours CV et ses tests de base. [En cours: tests de base ajoutes]
2. Finaliser le storytelling produit et la structure narrative du portfolio. [En cours]
3. Garantir un affichage fiable sur navigateurs/appareils principaux. [A venir]
4. Consolider la gouvernance et l'exploitation. [Continu]

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

Criteres de sortie :

- 0 bug bloquant sur matrice cible.
- Bugs mineurs traces dans `TECHNICAL_DEBT.md`.

## Phase 4 - Gouvernance et hygiene continue

- [ ] Ajouter pipeline CI GitLab minimale : tests Laravel + build Vite.
- [ ] Mettre en place une cadence de revue des incidents/solutions.
- [ ] Formaliser checklist pre-deploiement dans docs (sans runbook DRP), avec obligation de faire tourner les tests et le build avant tout push/merge de prod.
- [ ] Formaliser et appliquer le modele bilingue FR/EN pour tous les textes a l'ecran et les ALT text.

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

## Plan du jour - execution rapide

### Priorites du jour

- [x] Finaliser l'ajustement de la photo de profil (toggle original/IA + microcopy accessible).
- [ ] Finaliser la structure narrative du portfolio : profil, etudes, experience, projets, recommandations.
- [x] Ajouter les deux projets a la section Projets (CRM Admiral + PWA Budget) avec un niveau de detail suffisant pour une lecture claire.
- [x] Ajouter et reformuler l'experience de Chef de projet / Concepteur d'application metier - Stage pour qu'elle s'inscrive naturellement dans la ligne du portfolio.
- [x] Reorganiser la partie formation/experience en introduisant une section Etudes et en clarifiant le positionnement des experiences.
- [x] Ajouter la section Recommandations et preparer le dossier `public/recommendations/` pour le PDF a telecharger.
- [x] Verifier la coherence editoriale, le ton et la hierarchie sur desktop et mobile.
- [x] Si le temps le permet, preparer les visuels et elements de preuve associes aux projets.

### Plan de travail aujourd'hui

- [x] Implementer le toggle de photo de profil original/IA avec transition visuelle.
- [x] Ajouter l'accessibilite clavier et la microcopy associee au toggle photo.
- [x] Integrer le volet de demonstration CRM Admiral : CTA de demo, carousel, modal video et labels FR/EN.
- [x] Rediger le contenu du projet CRM Admiral : contexte, role, enjeux, solution, resultats, preuve de valeur.
- [x] Rediger le contenu du projet PWA Budget : contexte, role, solution, impact, lien public.
- [x] Rediger le contenu de l'experience ADM Agen en version coherente avec les autres rubriques.
- [x] Creer la structure Etudes et deplacer/adapter les elements de formation associes.
- [x] Creer la structure Recommandations et preparer l'entree de telechargement PDF.
- [x] Valider la coherence globale du contenu avant integration dans la page.
- [x] Preparer un lot de contenus plus complet si le temps le permet (temoignages, preuves, liens complementaires).

### A remettre a demain si besoin

- [ ] Affiner les visuels/carrousel du projet CRM Admiral.
- [ ] Finaliser le PDF de recommandations et le point d'entree de telechargement.
- [ ] Valider la version mobile de la nouvelle structuration.
- [ ] Ajuster finement le storytelling produit et les microcopies.

## Definition de pret pour aujourd'hui

- La priorite du jour est claire et le bloc restant est explicite.
- Le prochain bloc prioritaire est identifie (contenu, structure editoriale ou storytelling produit).
- Le plan du jour est explicite et priorise, avec la coherence du portfolio comme condition de passage.
- La roadmap est synchronisee avec l'etat reel du travail avance sur le lot courant.
- Les validations ou limites bloquees sont documentees si elles ne peuvent pas etre traitees aujourd'hui.

## Definition of done globale

- Comportements critiques valides par tests et checks manuels.
- Documentation de gouvernance a jour.
- Roadmap synchronisee avec l'etat reel du projet a chaque lot et a chaque journee de travail.
- Les sections dynamiques de priorisation ont ete re-evaluees selon l'avancement reel.
- Aucune exposition de secret dans le repository.
