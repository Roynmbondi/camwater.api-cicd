# 🛠️ Commandes Utiles - CamWater API DevOps

Guide de référence rapide pour toutes les commandes utiles du projet.

---

## 📋 Table des matières

1. [Git & GitHub](#git--github)
2. [Laravel](#laravel)
3. [Tests](#tests)
4. [Qualité du code](#qualité-du-code)
5. [Déploiement](#déploiement)
6. [Monitoring](#monitoring)
7. [SSH & O2Switch](#ssh--o2switch)
8. [GitHub CLI](#github-cli)

---

## 🔀 Git & GitHub

### Workflow de base

```bash
# Cloner le repository
git clone https://github.com/votre-username/camwater-api.git
cd camwater-api

# Créer une branche feature
git checkout -b feature/nouvelle-fonctionnalite

# Faire des modifications
git add .
git commit -m "feat: ajout nouvelle fonctionnalité"

# Pousser vers GitHub
git push origin feature/nouvelle-fonctionnalite

# Créer une Pull Request
# Via l'interface GitHub ou avec gh CLI
gh pr create --title "Nouvelle fonctionnalité" --body "Description"
```

### Workflow de déploiement

```bash
# Déploiement sur staging
git checkout develop
git merge feature/nouvelle-fonctionnalite
git push origin develop
# → Déploiement automatique sur staging

# Déploiement sur production
git checkout main
git merge develop
git push origin main
# → Déploiement automatique sur production
```

### Gestion des branches

```bash
# Lister les branches
git branch -a

# Supprimer une branche locale
git branch -d feature/ancienne-branche

# Supprimer une branche distante
git push origin --delete feature/ancienne-branche

# Mettre à jour depuis main
git checkout develop
git pull origin main
```

### Tags et releases

```bash
# Créer un tag
git tag v1.0.0

# Pousser le tag
git push origin v1.0.0

# Lister les tags
git tag -l

# Supprimer un tag
git tag -d v1.0.0
git push origin --delete v1.0.0
```

---

## 🚀 Laravel

### Installation et configuration

```bash
# Installer les dépendances
composer install
npm install

# Copier le fichier .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Générer le secret JWT
php artisan jwt:secret

# Créer la base de données
php artisan migrate

# Lancer le serveur
php artisan serve
```

### Commandes de développement

```bash
# Lancer le serveur de développement
php artisan serve

# Lancer le serveur avec un port spécifique
php artisan serve --port=8080

# Voir les routes
php artisan route:list

# Voir les informations de l'application
php artisan about

# Créer un contrôleur
php artisan make:controller NomController --api

# Créer un modèle avec migration
php artisan make:model NomModele -m

# Créer une migration
php artisan make:migration create_table_name
```

### Base de données

```bash
# Exécuter les migrations
php artisan migrate

# Exécuter les migrations en production
php artisan migrate --force

# Rollback des migrations
php artisan migrate:rollback

# Rafraîchir la base de données
php artisan migrate:fresh

# Voir l'état des migrations
php artisan migrate:status

# Voir les informations de la base de données
php artisan db:show

# Lancer les seeders
php artisan db:seed
```

### Cache et optimisation

```bash
# Vider tous les caches
php artisan optimize:clear

# Vider le cache de configuration
php artisan config:clear

# Vider le cache des routes
php artisan route:clear

# Vider le cache des vues
php artisan view:clear

# Vider le cache de l'application
php artisan cache:clear

# Optimiser pour la production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Documentation Swagger

```bash
# Générer la documentation Swagger
php artisan l5-swagger:generate

# Voir la documentation
# Ouvrir http://localhost:8000/api/documentation
```

### Logs

```bash
# Voir les logs en temps réel
php artisan pail

# Voir les logs avec filtre
php artisan pail --filter="error"

# Nettoyer les anciens logs
php artisan log:clear
```

---

## 🧪 Tests

### Exécuter les tests

```bash
# Tous les tests
php artisan test

# Tests unitaires uniquement
php artisan test --testsuite=Unit

# Tests fonctionnels uniquement
php artisan test --testsuite=Feature

# Tests avec couverture
php artisan test --coverage

# Tests avec couverture minimale
php artisan test --coverage --min=50

# Tests avec rapport HTML
php artisan test --coverage-html coverage-report

# Test spécifique
php artisan test --filter=NomDuTest

# Tests en parallèle
php artisan test --parallel
```

### Créer des tests

```bash
# Créer un test unitaire
php artisan make:test NomTest --unit

# Créer un test fonctionnel
php artisan make:test NomTest
```

---

## 🔍 Qualité du code

### Laravel Pint (Code Style)

```bash
# Vérifier le style de code
./vendor/bin/pint --test

# Corriger automatiquement
./vendor/bin/pint

# Vérifier un fichier spécifique
./vendor/bin/pint app/Http/Controllers/NomController.php

# Vérifier un répertoire
./vendor/bin/pint app/Models
```

### PHPStan (Analyse statique)

```bash
# Analyser le code (niveau 5)
./vendor/bin/phpstan analyse app --level=5

# Analyser avec rapport détaillé
./vendor/bin/phpstan analyse app --level=5 --error-format=table

# Analyser un fichier spécifique
./vendor/bin/phpstan analyse app/Http/Controllers/NomController.php

# Générer un rapport JSON
./vendor/bin/phpstan analyse app --level=5 --error-format=json > phpstan-report.json
```

### PHP_CodeSniffer (PSR-12)

```bash
# Vérifier le code
./vendor/bin/phpcs --standard=PSR12 app

# Vérifier avec rapport détaillé
./vendor/bin/phpcs --standard=PSR12 app --report=summary

# Corriger automatiquement
./vendor/bin/phpcbf --standard=PSR12 app

# Vérifier un fichier spécifique
./vendor/bin/phpcs --standard=PSR12 app/Http/Controllers/NomController.php
```

### Composer Audit (Sécurité)

```bash
# Vérifier les vulnérabilités
composer audit

# Vérifier avec format table
composer audit --format=table

# Vérifier uniquement les dépendances locked
composer audit --locked

# Valider composer.json
composer validate --strict
```

---

## 🚀 Déploiement

### Vérification du déploiement

```bash
# Windows PowerShell
.\scripts\verify-deployment.ps1 staging
.\scripts\verify-deployment.ps1 production

# Linux/Mac
chmod +x scripts/verify-deployment.sh
./scripts/verify-deployment.sh staging
./scripts/verify-deployment.sh production
```

### Health Check

```bash
# Vérifier staging
curl https://staging.votredomaine.com/api/health

# Vérifier production
curl https://api.votredomaine.com/api/health

# Vérifier avec détails
curl -v https://api.votredomaine.com/api/health

# Mesurer le temps de réponse
curl -o /dev/null -s -w '%{time_total}\n' https://api.votredomaine.com/api/health
```

### Tests de performance

```bash
# Load testing avec Apache Bench
ab -n 1000 -c 10 https://api.votredomaine.com/api/health

# Load testing avec rapport
ab -n 1000 -c 10 -g results.tsv https://api.votredomaine.com/api/health

# Test avec authentification
ab -n 100 -c 5 -H "Authorization: Bearer TOKEN" https://api.votredomaine.com/api/endpoint
```

---

## 📊 Monitoring

### Vérifications manuelles

```bash
# Vérifier l'état de l'API
curl https://api.votredomaine.com/api/health

# Vérifier le certificat SSL
echo | openssl s_client -servername api.votredomaine.com -connect api.votredomaine.com:443 2>/dev/null | openssl x509 -noout -dates

# Vérifier les headers de sécurité
curl -I https://api.votredomaine.com/api/health

# Mesurer le temps de réponse (5 fois)
for i in {1..5}; do curl -o /dev/null -s -w '%{time_total}\n' https://api.votredomaine.com/api/health; done
```

### Logs

```bash
# Voir les logs Laravel (sur le serveur)
tail -f storage/logs/laravel.log

# Voir les dernières erreurs
grep -i "error" storage/logs/laravel.log | tail -20

# Voir les warnings
grep -i "warning" storage/logs/laravel.log | tail -20

# Compter les erreurs
grep -c "error" storage/logs/laravel.log

# Voir les logs d'aujourd'hui
grep "$(date +%Y-%m-%d)" storage/logs/laravel.log
```

---

## 🔐 SSH & O2Switch

### Connexion SSH

```bash
# Se connecter avec clé SSH
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com

# Se connecter avec mot de passe
ssh votre-username@votredomaine.com

# Tester la connexion
ssh -i ~/.ssh/o2switch_deploy -v votre-username@votredomaine.com
```

### Gestion des fichiers

```bash
# Copier un fichier vers le serveur
scp -i ~/.ssh/o2switch_deploy fichier.txt votre-username@votredomaine.com:~/

# Copier un répertoire vers le serveur
scp -i ~/.ssh/o2switch_deploy -r dossier/ votre-username@votredomaine.com:~/

# Télécharger un fichier depuis le serveur
scp -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com:~/fichier.txt ./

# Synchroniser avec rsync
rsync -avz -e "ssh -i ~/.ssh/o2switch_deploy" ./local/ votre-username@votredomaine.com:~/remote/
```

### Commandes sur le serveur

```bash
# Se connecter et exécuter une commande
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "cd api.votredomaine.com && php artisan about"

# Voir l'utilisation du disque
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "df -h"

# Voir l'utilisation de la mémoire
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "free -h"

# Voir les processus
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "top -bn1 | head -20"
```

### Gestion de l'application sur le serveur

```bash
# Mettre l'application en maintenance
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "cd api.votredomaine.com && php artisan down"

# Remettre l'application en ligne
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "cd api.votredomaine.com && php artisan up"

# Exécuter les migrations
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "cd api.votredomaine.com && php artisan migrate --force"

# Vider les caches
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "cd api.votredomaine.com && php artisan optimize:clear"

# Optimiser pour la production
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com "cd api.votredomaine.com && php artisan optimize"
```

---

## 🔧 GitHub CLI

### Installation

```bash
# Windows (avec Chocolatey)
choco install gh

# Mac (avec Homebrew)
brew install gh

# Linux
# Voir https://github.com/cli/cli/blob/trunk/docs/install_linux.md
```

### Authentification

```bash
# Se connecter à GitHub
gh auth login

# Vérifier l'authentification
gh auth status
```

### Workflows

```bash
# Lister les workflows
gh workflow list

# Voir les runs d'un workflow
gh run list --workflow=laravel.yml

# Voir les détails d'un run
gh run view

# Voir les logs d'un run
gh run view --log

# Déclencher un workflow manuellement
gh workflow run laravel.yml

# Télécharger les artifacts
gh run download
```

### Secrets

```bash
# Lister les secrets
gh secret list

# Ajouter un secret
gh secret set NOM_SECRET

# Supprimer un secret
gh secret delete NOM_SECRET
```

### Pull Requests

```bash
# Créer une PR
gh pr create --title "Titre" --body "Description"

# Lister les PRs
gh pr list

# Voir une PR
gh pr view 123

# Merger une PR
gh pr merge 123

# Fermer une PR
gh pr close 123
```

### Issues

```bash
# Créer une issue
gh issue create --title "Titre" --body "Description"

# Lister les issues
gh issue list

# Voir une issue
gh issue view 123

# Fermer une issue
gh issue close 123
```

---

## 📦 NPM & Assets

### Installation et build

```bash
# Installer les dépendances
npm install

# Build pour le développement
npm run dev

# Build pour la production
npm run build

# Watch mode (développement)
npm run watch

# Nettoyer le cache
npm cache clean --force
```

---

## 🐛 Troubleshooting

### Problèmes courants

```bash
# Réinitialiser complètement l'application
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh
php artisan optimize:clear

# Problème de permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Problème de cache
php artisan optimize:clear
composer dump-autoload

# Problème de base de données
php artisan migrate:fresh
php artisan db:show

# Problème de JWT
php artisan jwt:secret --force
php artisan config:clear
```

### Vérifications

```bash
# Vérifier la configuration PHP
php -v
php -m
php -i | grep memory_limit

# Vérifier Composer
composer --version
composer diagnose

# Vérifier Node/NPM
node --version
npm --version

# Vérifier Git
git --version
git config --list
```

---

## 📚 Commandes de documentation

### Générer la documentation

```bash
# Swagger
php artisan l5-swagger:generate

# PHPDoc (si installé)
phpdoc -d app -t docs/api

# Markdown to HTML (si pandoc installé)
pandoc README.md -o README.html
```

---

## 🎯 Commandes rapides pour la démo

```bash
# 1. Vérifier l'état du projet
php artisan about
composer validate
php artisan test

# 2. Déclencher un déploiement
git checkout develop
git commit --allow-empty -m "demo: trigger deployment"
git push origin develop

# 3. Vérifier le déploiement
curl https://staging.votredomaine.com/api/health
.\scripts\verify-deployment.ps1 staging

# 4. Voir les workflows
gh workflow list
gh run list

# 5. Voir les logs
gh run view --log

# 6. Tester l'API
curl https://api.votredomaine.com/api/health
```

---

## 📖 Ressources

- [Documentation Laravel](https://laravel.com/docs)
- [GitHub Actions](https://docs.github.com/en/actions)
- [GitHub CLI](https://cli.github.com/manual/)
- [Composer](https://getcomposer.org/doc/)
- [PHPStan](https://phpstan.org/user-guide/getting-started)

---

**Aide-mémoire créé pour le projet CamWater API DevOps**
