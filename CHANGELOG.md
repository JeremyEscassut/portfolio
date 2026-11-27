# Changelog

Toutes les évolutions notables du portfolio sont consignées ici.
Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/) : les changements en cours vont dans **Non publié**, puis passent sous un numéro de version au moment du déploiement.

Catégories : **Ajouté**, **Modifié**, **Corrigé**, **Supprimé**, **Sécurité**, **Documentation**.

## [Non publié]

### Ajouté
- Bouton de sections mobile et tablette : un hexagone en bas à droite qui ouvre les sections en éventail (quart de cercle), avec la section en cours surlignée.
- Défilement animé vers la section choisie dans l'éventail : on voit la page monter ou descendre jusqu'à la section. Saut direct si le téléphone demande de réduire les animations.
- Toasts de confirmation et d'erreur après l'envoi du formulaire de contact.
- Repli en cas d'échec d'envoi : la fenêtre de contact se rouvre avec le texte conservé, et propose « Ouvrir dans ma messagerie » (e-mail déjà rédigé et adressé à l'adresse pro `jeremyescassut@gmail.com`) ou « Copier le message » (message + nom ; l'adresse de l'expéditeur est ajoutée par sa messagerie). Si aucune messagerie ne s'ouvre, un message l'indique avec l'adresse à utiliser.
- Groupe « IA » dans la stack : Claude Code, Codex et Lovable, avec leurs logos officiels (LobeHub Icons, licence MIT).
- Accroche sous le nom et légendes de la photo (originale / générée par IA), en FR et en EN.
- Ligne « Mon rôle » et liens écrits en toutes lettres (« Voir le site », « Voir le code ») pour chaque projet.
- Messages de validation du formulaire en français.
- Balise `meta description` et langue de la page (`lang`) conforme à la langue affichée.

### Modifié
- Titre : « Product owner, chef de projet IT et développeur UX/UI ».
- Police Schibsted Grotesk, texte plus foncé et lignes plus courtes pour la lisibilité.
- Sections séparées par un trait avec un titre visible ; colonne des dates réduite dans Expérience et Études.
- Recommandations : la citation passe en premier, la lettre de recommandation ensuite.
- Système de formes : hexagone pour les actions, rectangle à angles de 6 px pour les surfaces et images, de 4 px pour les étiquettes.
- Fond uni en mode clair (le dégradé est supprimé) ; le halo qui suit la souris reste.
- Icônes de la colonne de gauche agrandies à 48 px sur mobile ; cadre de la photo avec deux coupures égales et symétriques.
- Icônes de la stack harmonisées (Jira, MySQL, PHP).
- Liens des projets (« Voir le site », « Voir le code ») en boutons hexagonaux avec un logo plus grand, comme « Voir la démo » ; les trois passent en taille normale.
- Colonne de gauche sur desktop : les six boutons-icônes ont la même taille (44 px) et sont plus espacés ; le survol des boutons thème et langue est un hexagone vertical comme les autres (il était carré). Espacements resserrés sur les écrans de faible hauteur pour garder toutes les icônes visibles.
- Tous les messages du formulaire de contact passent par les fichiers de traduction (FR/EN).
- Les toasts disparaissent seuls au bout de 10 secondes (succès comme erreur).
- Boutons secondaires (« Voir la démo », « Voir le site », « Annuler »…) en hexagone à contour, et étiquettes des projets et de la stack avec un filet fin, pour mieux ressortir en mode clair.
- Survol des icônes en mode sombre : hexagone discret, les icônes gardent leur couleur. Orange GitLab plus foncé en mode clair (contraste suffisant).
- Les classes Tailwind `dark:` suivent le bouton thème du site au lieu du réglage du système.

### Corrigé
- Erreur JavaScript du carrousel CRM causée par l'apostrophe de « captures d'écran ».
- Fenêtre de choix du CV affichée en bas de page en mode sombre.
- Pied de page sorti de sa colonne à cause d'une balise en trop.
- Formulaire de contact, fenêtre CV, page CV et mentions légales : textes restés en français sur la version anglaise.
- Bouton « Envoyer » de la fenêtre de contact illisible en mode sombre.
- Fenêtre de choix du CV masquée par le bandeau de section et les icônes épinglées sur mobile en mode sombre.
- Survol illisible en mode sombre : choix de CV, croix de fermeture, icônes thème, langue et contact.

### Conformité
- Mentions légales : déclaration de Google Analytics 4 (cookies `_ga`, finalité, transfert vers les États-Unis, moyens de refus). Reprise de la branche `claude/project-thread-v3ra6l` (commit 413f1ae). Le bandeau de consentement reste à faire.

### Sécurité
- Le message de confirmation, qui reprend le nom saisi par le visiteur, est désormais échappé à l'affichage (il était inséré en HTML brut).

### Performance
- Page d'accueil allégée de 8 Mo à environ 64 Ko : les images ne sont plus intégrées en base64 mais servies comme fichiers (WebP pour les captures).
- Vidéo de démo convertie de `.mov` (62 Mo) en `.mp4` (4,9 Mo), lisible dans tous les navigateurs et chargée seulement à l'ouverture.

### Documentation
- `docs/PARCOURS_DE_LECTURE.md` : ordre de lecture du code, parcours de bout en bout, où modifier quoi, pièges connus.
- Commentaires ajoutés sur le code non évident (routes, langue, contact, scripts JS, structure de la page et de la feuille de style).
- `docs/reports/SOLUTIONS_LOG.md` : entrées 8 et 9.
- `tools/check-missing-translations.cjs` : script de vérification des traductions renommé pour fonctionner avec la configuration ES modules du projet.
- Gouvernance synchronisée avec l'état du 2026-11-27 : roadmap (priorités, phase 6, plan du jour), Guidelines (catégorie IA, système de design, règles i18n), ARCHITECTURE (front, médias, contact), dette technique (D-005 à D-009), journaux d'incidents (INC-2026-11-27-001 à 005) et de solutions (10, 11), instructions IA.
- `CLAUDE.md` : renvoie les assistants IA vers les fichiers de gouvernance.
- `tools/qa-contrast-audit.js` : audit des contrastes au survol et au focus (mode d'emploi en tête du fichier).

## [1.0-stable] - 2025-10-30
- Première version complète mise en ligne : page d'accueil, projets, CV, formulaire de contact, modes clair et sombre.

## [0.3-projects-added] - 2025-10-24
- Ajout de la section Projets.

## [0.2-mail] - 2025-10-20
- Formulaire de contact avec envoi par e-mail.

## [0.1-init] - 2025-10-17
- Initialisation du projet Laravel.
