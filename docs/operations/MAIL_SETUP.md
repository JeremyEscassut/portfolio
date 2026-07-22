# MAIL_SETUP - Portfolio

## Objectif

Documenter la configuration e-mail du formulaire de contact sans exposer de secrets.

## Regles de securite

- Ne jamais versionner de credentials SMTP/Mailgun dans le repository.
- Stocker les secrets uniquement dans `.env` local/serveur.
- En cas d'exposition accidentelle, rotation immediate des secrets.

## Variables attendues (`.env`)

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="Portfolio"
MAIL_CONTACT_ADDRESS=
```

## Validation locale

1. Configurer les variables `MAIL_*`.
2. Vider le cache config: `php artisan config:clear`.
3. Tester le formulaire de contact depuis l'UI.
4. Verifier `storage/logs/laravel.log` en cas d'echec.

## Validation de non-regression

- Test automatise: `php artisan test --filter=ContactFormTest`.
- Test manuel: envoi d'un message reel vers `MAIL_CONTACT_ADDRESS`.

## Note securite

Un ancien fichier racine contenait des valeurs sensibles historisees.
Action recommandee: considerer ces credentials comme compromises et les faire tourner (rotation).
