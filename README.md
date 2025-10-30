# Portfolio — Laravel 12 + Vite + TailwindCSS

Résumé rapide

-   Projet: site portfolio (FR UI, code EN).
-   Stack: Laravel 12 (PHP 8.3), Vite 7, TailwindCSS v4, Alpine.js.
-   Objectif: site statique côté contenu + formulaire de contact envoyé via SMTP (Alwaysdata recommandé) — Mailgun possible en option.

Table des matières

1. But et portée
2. Prérequis locaux
3. Installation & dev
4. Configuration des mails
5. Build et déploiement (Alwaysdata)
6. Tests & vérifications
7. Conventions de code
8. Roadmap & éléments ouverts

---

## 1) But et portée

Ce repository contient le code source du portfolio. L'objectif est de fournir:

-   une page d'accueil descriptive,
-   une section Projets,
-   un lien CV (PDF),
-   un formulaire de contact simple (envoi d'e‑mail vers une adresse de contact),
-   un design responsive et accessible (contraste, navigation clavier).

Le front est rendu via Blade + TailwindCSS et des interactions légères gérées par Alpine.js.

## 2) Prérequis locaux

-   PHP 8.3 (MAMP ou autre)
-   Composer 2
-   Node LTS (nvm recommandé)
-   npm
-   (Optionnel) MailHog pour capturer les e‑mails en dev
-   Utilisation du SMTP d'Alwaysdata (recommandé) pour l'envoi d'e‑mails via le formulaire de contact. Mailgun reste une option si tu souhaites un provider externe.
-   Déploiement en build via alwaysdata.net

## 5) Build et déploiement (Alwaysdata)

Recommandation: générer le build localement puis uploader `public/build` sur le serveur.

Build local

```bash
npm ci
npm run build
# vérifiez public/build/manifest.json
```

Uploader les assets

-   Par SFTP/FTP ou `rsync` :

```bash
rsync -avz --delete public/build/ user@ssh.alwaysdata.net:/home/<user>/www/<site>/public/build/
```

Actions à exécuter côté serveur (Alwaysdata)

```bash
cd /home/<user>/www/<site>
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan queue:restart
```

Test final: soumettre le formulaire et vérifier la réception sur `MAIL_CONTACT_ADDRESS`.

## 6) Tests & vérifications

-   Tests Laravel:

```bash
php artisan test
```

-   Vérifier les logs en cas d’erreur:

```bash
tail -n 200 storage/logs/laravel.log
```

    -   Pour vérifier la délivrabilité des e‑mails : vérifier la boîte de destination (ex. j.losaint@gmail.com).

    -   Note importante : ce projet n'utilise plus Mailgun en production — les messages de contact sont envoyés via le SMTP d'Alwaysdata vers une boîte hébergée (`jeremyescassut@alwaysdata.net`) configurée côté alwaysdata pour rediriger automatiquement vers `j.losaint@gmail.com`.

    -   Configuration recommandée pour la production (dans `.env`, valeurs d'exemple) :

    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=smtp-jeremyescassut.alwaysdata.net
    MAIL_PORT=587
    MAIL_USERNAME=jeremyescassut@alwaysdata.net
    MAIL_PASSWORD=VotreMotDePasseMailbox
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=jeremyescassut@alwaysdata.net
    MAIL_FROM_NAME="Portfolio"
    MAIL_CONTACT_ADDRESS=j.losaint@gmail.com
    ```

    -   Si tu veux bypasser l'envoi réel pour du debug ou en local, définis `MAIL_MAILER=log` ou `array` en dev.

## 7) Conventions de code

-   CSS: tout le CSS (y compris overrides page‑scoped) -> `resources/css/app.css`.
-   JS: tout le JS spécifique au site -> `resources/js/app.js`.
-   Blade views: ne garder que le HTML et les directives Blade. Centraliser CSS/JS dans les fichiers mentionnés.
-   Ne committer **jamais** de secrets dans le repo (.env, clés API, mots de passe).

## 8) Roadmap & éléments ouverts

-   Pour les titres des sections en mode mobile, faire en sorte que le cadre ne dépasse pas de l'écran
-   Rétablir la mobilité du halo en mode desktop
-   Ajouter un bouton [EN | FR] à gauche du bouton mode clair/sombre pour permettre de changer la langue du site.
-   Ajouter une photo de moi à gauche des boutons dans la section Accueil
-   Améliorer le contraste et l'accessibilité
-   Utiliser un arrière-plan en mode clair plus marqué. Le visuel actuellement est trop "boring".
-   Ajouter CI/CD (GitHub Actions) : build + upload des assets

---

## 9) Consignation des solutions (fichier : README_Solutions.md)

Règle obligatoire — Dès qu'un problème est résolu (bug, incident d'infra, test qui a cassé, décision d'architecture, etc.), consigner systématiquement la description et la solution dans le fichier `README_Solutions.md` à la racine du projet.

But : garder un registre réutilisable des problèmes rencontrés et des solutions appliquées afin de pouvoir extraire des guidelines générales pour d'autres projets.

Contenu minimum requis pour chaque entrée :

-   Titre : court et explicite
-   Date : YYYY-MM-DD
-   Contexte / reproduction : comment reproduire le problème (étapes, environnement)
-   Cause racine : diagnostic bref
-   Solution : commandes, patches, fichiers modifiés, et justification technique
-   Référence : commit SHA, numéro de PR/issue, ou fichier(s) modifié(s)
-   Tags : bug | infra | tests | perf | doc | build | sécurité | autre

Template recommandé (copier/coller pour chaque nouveau cas) :

```
- Titre :
- Date :
- Contexte :
- Cause :
- Solution :
- Référence :
- Tags :
```

Processus / règle :

-   Ne pas fermer ou archiver une tâche/problème sans avoir ajouté une entrée dans `README_Solutions.md`.
-   Garder les descriptions claires et concises — l'objectif est la réutilisabilité des solutions.
-   Conserver les guidelines générales dans cette section du `README.md` pour pouvoir les réutiliser facilement dans de futurs projets.

Cette section sert de référence rapide pour les bonnes pratiques de consignation et doit rester concise ; les détails techniques (patches, diff, logs) vont dans l'entrée correspondante de `README_Solutions.md`.

---

Si tu veux, je peux:

-   générer un snippet GitHub Actions (build + upload via rsync) ; ou
-   préparer un script `deploy.sh` local qui build puis upload ; ou
-   exécuter les commandes d'upload si tu me donnes ton user Alwaysdata.

Dis‑moi ce que tu préfères et je m'en occupe.
