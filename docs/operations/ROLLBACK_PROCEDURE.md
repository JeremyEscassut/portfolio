# Procédure de rollback - Portfolio

Cette procédure est pensée pour un déploiement simple sur Alwaysdata avec un dépôt Laravel et un transfert par rsync.

## Objectif

Permettre de revenir rapidement à la version précédente du site si la mise en production provoque un incident fonctionnel.

## Principe

Avant chaque déploiement, le script de déploiement crée une sauvegarde de l’application dans un sous-répertoire `backups/` sur le serveur distant.

Si un problème apparaît après publication, la procédure de rollback restaure cette sauvegarde en quelques étapes.

## Pré-requis

- SSH configuré vers le serveur Alwaysdata.
- Accès au dossier de production du site.
- Les variables `REMOTE_USER`, `REMOTE_HOST` et `REMOTE_PATH` doivent être valides.

## Exécution

### 1. Préparer la sauvegarde avant le déploiement

Le script de déploiement doit être lancé avec la commande suivante :

```bash
./scripts/deploy.sh
```

Le déploiement crée automatiquement une sauvegarde dans :

```bash
/home/jeremyescassut/www/Portfolio/backups/
```

### 2. Lancer le rollback si nécessaire

Depuis la racine du projet :

```bash
./scripts/rollback.sh
```

Le script restaure la dernière sauvegarde disponible dans `backups/` sur le serveur distant.

## Ce que fait le rollback

- restaure les fichiers depuis la sauvegarde la plus récente,
- conserve les fichiers sensibles comme `.env` en dehors de la restauration,
- nettoie les caches Laravel pour éviter un état incohérent.

## Vérifications post-rollback

Après restauration, vérifier manuellement :

- la page d’accueil,
- le CV,
- le formulaire de contact,
- les assets statiques et la vidéo de démo.

## Notes de sécurité

- Ne jamais supprimer la sauvegarde immédiatement après un déploiement réussi.
- Conserver au moins une copie de secours pendant quelques jours.
- Si un incident touche la base de données ou les fichiers uploadés, la procédure doit être adaptée.
