# Portfolio — Laravel 12 + Vite + TailwindCSS

Résumé rapide

-   Projet: site portfolio (FR UI, code EN).
-   Stack: Laravel 12 (PHP 8.3), Vite 7, TailwindCSS v4, Alpine.js.
-   Objectif: site statique côté contenu + formulaire de contact envoyé via Mailgun.

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
-   Utilisation de Mailgun pour gérer les e-mails via le formulaire de contact
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
# si vous utilisez les queues
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

-   Pour vérifier la délivrabilité Mailgun: consultez les events dans le dashboard Mailgun.

## 7) Conventions de code

-   CSS: tout le CSS (y compris overrides page‑scoped) -> `resources/css/app.css`.
-   JS: tout le JS spécifique au site -> `resources/js/app.js`.
-   Blade views: ne garder que le HTML et les directives Blade. Centraliser CSS/JS dans les fichiers mentionnés.
-   Ne committer **jamais** de secrets dans le repo (.env, clés API, mots de passe).

## 8) Roadmap & éléments ouverts

-   Publier sur Alwaysdata (déploiement automatisé si souhaité)
-   Rendre les envois d'e‑mail asynchrones via queue
-   Améliorer le contraste et l'accessibilité
-   Nettoyer les styles et regrouper les règles page‑scoped
-   Ajouter CI/CD (GitHub Actions) : build + upload des assets

---

Si tu veux, je peux:

-   générer un snippet GitHub Actions (build + upload via rsync) ; ou
-   préparer un script `deploy.sh` local qui build puis upload ; ou
-   exécuter les commandes d'upload si tu me donnes ton user Alwaysdata.

Dis‑moi ce que tu préfères et je m'en occupe.
