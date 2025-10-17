# 📝 Cheat Sheet Console — Projet Portfolio (Laravel)

---

## 1️⃣ Tests (Laravel / PHPUnit)

### ⚡ Lancer les tests

```bash
# Tous les tests (recommandé)
php artisan test

# Test suite spécifique
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Filtrer par nom/méthode
php artisan test --filter=ContactForm

# Exécuter un fichier de test précis via PHPUnit
vendor/bin/phpunit tests/Unit/ExampleTest.php

# Utiliser un fichier de config spécifique PHPUnit
vendor/bin/phpunit -c phpunit.xml
```

## 📊 Rapports et couverture

```bash
# Couverture HTML (Xdebug/PCOV requis)
vendor/bin/phpunit --coverage-html storage/coverage
open storage/coverage/index.html

# Couverture via l’interface artisan
php artisan test --coverage

# Rapport JUnit (pour CI/CD)
vendor/bin/phpunit --log-junit storage/logs/junit.xml

# Mode détaillé/debug
vendor/bin/phpunit --verbose
vendor/bin/phpunit --debug
vendor/bin/phpunit --show-diff

# Groupes PHPUnit
vendor/bin/phpunit --group groupName
vendor/bin/phpunit --exclude-group groupName
```

## 2️⃣ Artisan (génération de code)

### ✨ Création de code

```bash
# Controller
php artisan make:controller HomeController

# Modèle + Factory (pour seed/tests)
php artisan make:model Project --factory
php artisan make:model Contact --factory

# Form Request (validation)
php artisan make:request ContactRequest

# Migration
php artisan make:migration create_contacts_table

# Mailable (email contact)
php artisan make:mail ContactMessage

# Test
php artisan make:test ContactFormTest
```

### 🗄 Base de données

```bash
# Migrations
php artisan migrate
php artisan migrate:fresh --seed   # Réinitialiser + seed (dev)

# Seeders
php artisan db:seed
php artisan db:seed --class=DatabaseSeeder

# Info: SQLite par défaut; bascule MySQL MAMP (port 8889) en adaptant .env
# Exemple .env (MySQL MAMP):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=8889
# DB_DATABASE=portfolio
# DB_USERNAME=root
# DB_PASSWORD=root
```

## 3️⃣ Dev server, Vite et cache

### 🚀 Serveur local & Vite

```bash
# Serveur Laravel
php artisan serve --host=127.0.0.1 --port=8000

# Vite (HMR) / Tailwind
npm run dev

# Lancer Artisan + Vite en parallèle (script ajouté)
npm run dev:all

# Build de production
npm run build
```

### 🧹 Cache / optimisation

```bash
# Nettoyage global
php artisan optimize:clear

# Cibles spécifiques
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 🛠 Inspection

```bash
php artisan route:list
php artisan about
php artisan tinker
```

### 💻 Ouvrir VSCode

```bash

code .

```

### 💻 Exporter l'arborescence du projet

```bash

tree -L 3 -I "vendor|node_modules|.git|var" > tree.txt

```

### ✉️ Mails (dev)

```bash
# Log mailer (par défaut en dev)
# Les emails sont visibles dans storage/logs/laravel.log

# Pour Gmail (production ultérieurement), configurer MAIL_* dans .env
```

### 🎹 Raccourcis Terminal

```bash

# Afficher / Masquer la fenêtre Terminal

Cmd + J

```

# Efface l'écran et l'historique du scrollback

```bash

clear && printf '\e[3J'

```

# Rétablit la dernière version pushée

```bash

git reset --hard HEAD
git clean -fd

```

# Coller un bloc de code sur Discord

```language
content
```

# Formatage du code PHP (Laravel Pint)

```bash
vendor/bin/pint          # Formatage
vendor/bin/pint --test   # Dry run (sans modifier)
```
