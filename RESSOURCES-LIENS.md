# 🔗 Ressources et Liens - Projet DevOps CamWater API

Liste complète de toutes les ressources, liens et références pour le projet.

---

## 🌐 URLs du projet (à personnaliser)

### Environnements

```
Staging:     https://staging.votredomaine.com
Production:  https://api.votredomaine.com
```

### Endpoints principaux

```
Health Check:        /api/health
Documentation API:   /api/documentation
Login:              /api/auth/login
```

### Exemples complets

```bash
# Health check staging
curl https://staging.votredomaine.com/api/health

# Health check production
curl https://api.votredomaine.com/api/health

# Documentation Swagger
https://api.votredomaine.com/api/documentation
```

---

## 📚 Documentation du projet

### Documentation principale

| Document | Description | Pages |
|----------|-------------|-------|
| [README.md](README.md) | Vue d'ensemble du projet | - |
| [PROJET-DEVOPS.md](PROJET-DEVOPS.md) | Documentation DevOps complète | 15 |
| [RAPPORT-PROJET-DEVOPS.md](RAPPORT-PROJET-DEVOPS.md) | Rapport académique | 15 |

### Guides de configuration

| Document | Description | Pages |
|----------|-------------|-------|
| [QUICK-SETUP-DEVOPS.md](QUICK-SETUP-DEVOPS.md) | Configuration rapide (30 min) | 6 |
| [O2SWITCH-DEPLOYMENT.md](O2SWITCH-DEPLOYMENT.md) | Guide déploiement O2Switch | 10 |
| [.github/ENVIRONMENTS-SETUP.md](.github/ENVIRONMENTS-SETUP.md) | Configuration environnements | 8 |

### Guides d'utilisation

| Document | Description | Pages |
|----------|-------------|-------|
| [COMMANDES-UTILES.md](COMMANDES-UTILES.md) | Aide-mémoire commandes | 10 |
| [PRESENTATION-DEMO.md](PRESENTATION-DEMO.md) | Guide présentation | 8 |
| [FICHIERS-CREES.md](FICHIERS-CREES.md) | Liste fichiers créés | - |
| [RESUME-FINAL.md](RESUME-FINAL.md) | Résumé final | - |

### Documentation technique

| Document | Description |
|----------|-------------|
| [.github/WORKFLOWS.md](.github/WORKFLOWS.md) | Documentation workflows |
| [.github/BADGES.md](.github/BADGES.md) | Badges pour README |
| [CI-CD-SETUP.md](CI-CD-SETUP.md) | Configuration CI/CD |

---

## 🔄 Workflows GitHub Actions

### Fichiers de workflows

```
.github/workflows/laravel.yml              - Pipeline CI/CD principale
.github/workflows/monitoring.yml           - Monitoring & Logs
.github/workflows/performance-tests.yml    - Tests de performance
```

### URLs GitHub Actions

```
# Remplacer VOTRE-USERNAME par votre nom d'utilisateur GitHub

Actions:
https://github.com/VOTRE-USERNAME/camwater-api/actions

Workflow CI/CD:
https://github.com/VOTRE-USERNAME/camwater-api/actions/workflows/laravel.yml

Workflow Monitoring:
https://github.com/VOTRE-USERNAME/camwater-api/actions/workflows/monitoring.yml

Workflow Performance:
https://github.com/VOTRE-USERNAME/camwater-api/actions/workflows/performance-tests.yml
```

---

## 🛠️ Technologies et outils

### Backend

| Technologie | Version | Documentation |
|-------------|---------|---------------|
| Laravel | 12.x | https://laravel.com/docs/12.x |
| PHP | 8.2/8.3 | https://www.php.net/docs.php |
| MySQL | 8.0+ | https://dev.mysql.com/doc/ |
| JWT Auth | 2.8+ | https://jwt-auth.readthedocs.io/ |

### DevOps

| Outil | Documentation |
|-------|---------------|
| GitHub Actions | https://docs.github.com/en/actions |
| GitHub CLI | https://cli.github.com/manual/ |
| SSH | https://www.openssh.com/manual.html |

### Qualité & Tests

| Outil | Documentation |
|-------|---------------|
| PHPUnit | https://phpunit.de/documentation.html |
| PHPStan | https://phpstan.org/user-guide/getting-started |
| Laravel Pint | https://laravel.com/docs/pint |
| PHP_CodeSniffer | https://github.com/squizlabs/PHP_CodeSniffer/wiki |

### Frontend

| Technologie | Documentation |
|-------------|---------------|
| Vite | https://vitejs.dev/guide/ |
| Node.js | https://nodejs.org/docs/ |
| NPM | https://docs.npmjs.com/ |

---

## 📖 Ressources Laravel

### Documentation officielle

```
Laravel Documentation:     https://laravel.com/docs
Laravel API Reference:     https://laravel.com/api/12.x/
Laravel News:             https://laravel-news.com/
Laracasts:                https://laracasts.com/
```

### Packages utilisés

```
JWT Auth:                 https://github.com/PHP-Open-Source-Saver/jwt-auth
L5 Swagger:              https://github.com/DarkaOnLine/L5-Swagger
Laravel Pint:            https://github.com/laravel/pint
```

### Commandes Artisan

```bash
# Voir toutes les commandes
php artisan list

# Aide sur une commande
php artisan help migrate

# Documentation en ligne
https://laravel.com/docs/artisan
```

---

## 🔧 GitHub

### Repository

```
# Remplacer VOTRE-USERNAME

Repository:
https://github.com/VOTRE-USERNAME/camwater-api

Clone HTTPS:
https://github.com/VOTRE-USERNAME/camwater-api.git

Clone SSH:
git@github.com:VOTRE-USERNAME/camwater-api.git
```

### GitHub Features

```
Issues:
https://github.com/VOTRE-USERNAME/camwater-api/issues

Pull Requests:
https://github.com/VOTRE-USERNAME/camwater-api/pulls

Actions:
https://github.com/VOTRE-USERNAME/camwater-api/actions

Settings:
https://github.com/VOTRE-USERNAME/camwater-api/settings

Secrets:
https://github.com/VOTRE-USERNAME/camwater-api/settings/secrets/actions

Environments:
https://github.com/VOTRE-USERNAME/camwater-api/settings/environments
```

---

## 🌍 O2Switch

### cPanel

```
URL cPanel:              https://votredomaine.com:2083
Documentation O2Switch:  https://faq.o2switch.fr/
Support O2Switch:        https://www.o2switch.fr/support/
```

### Fonctionnalités utilisées

```
Sous-domaines:           cPanel > Domaines > Sous-domaines
Bases de données:        cPanel > Bases de données MySQL
Clés SSH:               cPanel > Sécurité > Clés SSH
SSL/TLS:                cPanel > Sécurité > SSL/TLS
Gestionnaire de fichiers: cPanel > Fichiers > Gestionnaire de fichiers
```

---

## 📊 Monitoring et métriques

### Health Check

```bash
# Staging
curl https://staging.votredomaine.com/api/health

# Production
curl https://api.votredomaine.com/api/health
```

### Métriques collectées

```
- CPU usage
- Memory (RAM) usage
- Disk usage
- API response time
- Error rate
- Database connection
```

### Rapports

```
GitHub Actions > Artifacts:
- Performance Report
- Monitoring Report
- Log Analysis Report
```

---

## 🔒 Sécurité

### Outils de sécurité

```
Composer Audit:          composer audit
Security Headers:        https://securityheaders.com/
SSL Test:               https://www.ssllabs.com/ssltest/
```

### Bonnes pratiques

```
OWASP Top 10:           https://owasp.org/www-project-top-ten/
PHP Security:           https://www.php.net/manual/en/security.php
Laravel Security:       https://laravel.com/docs/security
```

---

## 🧪 Tests

### Tests de performance

```bash
# Apache Bench
ab -n 1000 -c 10 https://api.votredomaine.com/api/health

# cURL avec timing
curl -o /dev/null -s -w '%{time_total}\n' https://api.votredomaine.com/api/health
```

### Outils de test

```
Postman:                https://www.postman.com/
Insomnia:               https://insomnia.rest/
HTTPie:                 https://httpie.io/
```

---

## 📚 Ressources d'apprentissage

### DevOps

```
DevOps Roadmap:         https://roadmap.sh/devops
GitHub Actions Learn:   https://docs.github.com/en/actions/learn-github-actions
CI/CD Best Practices:   https://www.atlassian.com/continuous-delivery/principles/continuous-integration-vs-delivery-vs-deployment
```

### Laravel

```
Laravel Bootcamp:       https://bootcamp.laravel.com/
Laracasts:             https://laracasts.com/
Laravel Daily:         https://laraveldaily.com/
```

### PHP

```
PHP The Right Way:      https://phptherightway.com/
PHP Standards:          https://www.php-fig.org/psr/
Modern PHP:            https://modernphp.com/
```

---

## 🎓 Standards et conventions

### Code Style

```
PSR-12:                 https://www.php-fig.org/psr/psr-12/
Laravel Conventions:    https://laravel.com/docs/contributions#coding-style
```

### Git

```
Conventional Commits:   https://www.conventionalcommits.org/
Git Flow:              https://nvie.com/posts/a-successful-git-branching-model/
Semantic Versioning:   https://semver.org/
```

### API

```
REST API Best Practices: https://restfulapi.net/
JSON API:               https://jsonapi.org/
OpenAPI Specification:  https://swagger.io/specification/
```

---

## 🔗 Liens utiles

### Outils en ligne

```
JSON Formatter:         https://jsonformatter.org/
Base64 Encode/Decode:   https://www.base64encode.org/
JWT Debugger:          https://jwt.io/
Regex Tester:          https://regex101.com/
Cron Expression:       https://crontab.guru/
```

### Générateurs

```
UUID Generator:         https://www.uuidgenerator.net/
Password Generator:     https://passwordsgenerator.net/
SSH Key Generator:      https://www.ssh.com/academy/ssh/keygen
```

### Validateurs

```
JSON Validator:         https://jsonlint.com/
YAML Validator:         https://www.yamllint.com/
Markdown Preview:       https://markdownlivepreview.com/
```

---

## 📞 Support et communauté

### Laravel

```
Laravel Discord:        https://discord.gg/laravel
Laravel Forums:         https://laracasts.com/discuss
Stack Overflow:         https://stackoverflow.com/questions/tagged/laravel
```

### GitHub

```
GitHub Community:       https://github.community/
GitHub Support:         https://support.github.com/
```

### O2Switch

```
Support O2Switch:       https://www.o2switch.fr/support/
FAQ O2Switch:          https://faq.o2switch.fr/
Forum O2Switch:        https://forum.o2switch.fr/
```

---

## 📦 Collection Postman

### Fichier local

```
CamWater_API.postman_collection.json
```

### Import dans Postman

1. Ouvrir Postman
2. File > Import
3. Sélectionner le fichier JSON
4. Configurer les variables d'environnement

### Variables d'environnement

```json
{
  "base_url": "https://api.votredomaine.com",
  "token": "votre_jwt_token"
}
```

---

## 🎯 Checklist de personnalisation

### URLs à remplacer

- [ ] `VOTRE-USERNAME` → Votre nom d'utilisateur GitHub
- [ ] `votredomaine.com` → Votre domaine
- [ ] `staging.votredomaine.com` → Votre sous-domaine staging
- [ ] `api.votredomaine.com` → Votre sous-domaine production

### Fichiers à personnaliser

- [ ] README.md (badges)
- [ ] RAPPORT-PROJET-DEVOPS.md (nom)
- [ ] PRESENTATION-DEMO.md (informations)
- [ ] Tous les fichiers de documentation

---

## 📖 Documentation externe recommandée

### Livres

```
"The Phoenix Project" - Gene Kim
"The DevOps Handbook" - Gene Kim
"Continuous Delivery" - Jez Humble
"Clean Code" - Robert C. Martin
```

### Cours en ligne

```
Udemy - DevOps Courses
Coursera - DevOps Specialization
Pluralsight - DevOps Path
LinkedIn Learning - DevOps
```

---

**Dernière mise à jour**: Mars 2024  
**Projet**: CamWater API DevOps  
**Statut**: ✅ Complet
