# 💧 CamWater API - Système de Gestion d'Eau

[![CI/CD Pipeline](https://github.com/votre-username/camwater-api/workflows/Laravel%20CI%2FCD%20Pipeline%20Complète/badge.svg)](https://github.com/votre-username/camwater-api/actions)
[![Monitoring](https://github.com/votre-username/camwater-api/workflows/Monitoring%20%26%20Logs/badge.svg)](https://github.com/votre-username/camwater-api/actions)
[![Performance Tests](https://github.com/votre-username/camwater-api/workflows/Performance%20Tests/badge.svg)](https://github.com/votre-username/camwater-api/actions)

> 🚀 API REST complète avec pipeline CI/CD automatisée, monitoring 24/7 et déploiement continu sur O2Switch

API REST pour la gestion des abonnés, consommations, factures et paiements pour CamWater.

## 🎯 Projet DevOps

Ce projet implémente une chaîne DevOps complète incluant:

- ✅ **CI/CD automatisée** avec GitHub Actions
- ✅ **Tests automatisés** (unitaires, fonctionnels, performance)
- ✅ **Monitoring 24/7** avec métriques et logs
- ✅ **Déploiement automatique** sur O2Switch (staging + production)
- ✅ **Sécurité** (scan de vulnérabilités, audit)
- ✅ **Documentation complète** (API, workflows, déploiement)

📖 **Documentation complète**: [PROJET-DEVOPS.md](PROJET-DEVOPS.md)

## 🚀 Technologies

- Laravel 12.x
- PHP 8.2/8.3
- MySQL 8.0+
- JWT Authentication
- Swagger/OpenAPI Documentation
- GitHub Actions (CI/CD)
- O2Switch (Hébergement)

## 📋 Fonctionnalités

- **Authentification JWT** avec gestion de blacklist des tokens
- **Gestion des abonnés** (particuliers et professionnels)
- **Suivi des consommations** d'eau
- **Facturation automatique** avec tarification progressive
- **Gestion des paiements** (Mobile Money, Carte bancaire, Espèces)
- **Système de réclamations**
- **Statistiques et rapports**
- **Logs d'activité** pour audit
- **Documentation API Swagger**

## 🔧 Installation

### Prérequis

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (pour les assets)

### Étapes d'installation

1. Cloner le repository
```bash
git clone https://github.com/votre-username/camwater-api.git
cd camwater-api
```

2. Installer les dépendances
```bash
composer install
npm install
```

3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Configurer la base de données dans `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camwater
DB_USERNAME=root
DB_PASSWORD=
```

5. Exécuter les migrations
```bash
php artisan migrate
```

6. Lancer le serveur
```bash
php artisan serve
```

L'API sera accessible sur `http://localhost:8000`

## 📚 Documentation API

La documentation Swagger est disponible sur :
```
http://localhost:8000/api/documentation
```

Vous pouvez également importer la collection Postman : `CamWater_API.postman_collection.json`

## 🔐 Authentification

L'API utilise JWT (JSON Web Tokens) pour l'authentification.

### Login
```bash
POST /api/auth/login
{
  "email": "user@example.com",
  "password": "password"
}
```

### Utiliser le token
```bash
Authorization: Bearer {votre_token}
```

## 🏗️ Structure du projet

```
app/
├── Console/Commands/      # Commandes artisan personnalisées
├── Http/
│   ├── Controllers/       # Contrôleurs API
│   └── Middleware/        # Middlewares personnalisés
├── Models/                # Modèles Eloquent
└── Services/              # Services métier

database/
├── migrations/            # Migrations de base de données
└── seeders/              # Seeders

routes/
└── api.php               # Routes API
```

## 🧪 Tests

```bash
php artisan test
```

## 🛠️ Commandes utiles

### Nettoyer les tokens expirés
```bash
php artisan tokens:clean
```

### Nettoyer les anciens logs
```bash
php artisan logs:clean
```

### Générer la documentation Swagger
```bash
php artisan l5-swagger:generate
```

## 📊 Rôles et Permissions

- **admin** : Accès complet au système
- **operateur** : Gestion des abonnés, consommations et factures
- **abonne** : Consultation de ses propres données

## 🤝 Contribution

Les contributions sont les bienvenues ! Veuillez suivre ces étapes :

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 License

Ce projet est sous licence MIT.

## 👥 Auteurs

- Votre Nom - Développement initial

## 📞 Contact

Pour toute question, contactez : votre.email@example.com


## 🚀 Déploiement

### Déploiement automatique

Le projet utilise GitHub Actions pour le déploiement automatique:

- **Staging**: Déploiement automatique sur push vers `develop`
- **Production**: Déploiement automatique sur push vers `main`

### Configuration du déploiement

1. **Configurer les secrets GitHub**:
   - `O2SWITCH_SSH_KEY`: Clé SSH privée
   - `O2SWITCH_HOST`: Nom d'hôte O2Switch
   - `O2SWITCH_USER`: Nom d'utilisateur SSH
   - `STAGING_PATH`: Chemin staging
   - `STAGING_URL`: URL staging
   - `PRODUCTION_PATH`: Chemin production
   - `PRODUCTION_URL`: URL production

2. **Suivre le guide de configuration**:
   - [Configuration O2Switch](O2SWITCH-DEPLOYMENT.md)
   - [Configuration des environnements](.github/ENVIRONMENTS-SETUP.md)

### Workflow de déploiement

```bash
# Développement
git checkout develop
git add .
git commit -m "feat: nouvelle fonctionnalité"
git push origin develop
# → Déploiement automatique sur staging

# Production
git checkout main
git merge develop
git push origin main
# → Déploiement automatique sur production
```

### Vérifier le déploiement

```bash
# Tester le health check
curl https://api.votredomaine.com/api/health

# Ou utiliser le script de vérification
chmod +x scripts/verify-deployment.sh
./scripts/verify-deployment.sh production
```

## 📊 Monitoring

Le monitoring automatique s'exécute quotidiennement et vérifie:

- ✅ État de santé de l'API
- ✅ Temps de réponse
- ✅ Certificat SSL
- ✅ Métriques serveur (CPU, RAM, Disk)
- ✅ Analyse des logs
- ✅ Détection d'erreurs critiques

Les rapports sont disponibles dans GitHub Actions > Artifacts.

## 🧪 Tests et Qualité

### Tests automatisés

```bash
# Tests unitaires
php artisan test --testsuite=Unit

# Tests fonctionnels
php artisan test --testsuite=Feature

# Tests avec couverture
php artisan test --coverage
```

### Qualité du code

```bash
# Formatage du code (Laravel Pint)
./vendor/bin/pint

# Analyse statique (PHPStan)
./vendor/bin/phpstan analyse app --level=5

# Vérification PSR-12
./vendor/bin/phpcs --standard=PSR12 app
```

### Tests de performance

Les tests de performance s'exécutent automatiquement dans la pipeline CI/CD:

- Load testing (1000 requêtes)
- Database performance
- Memory profiling
- API response time

## 📖 Documentation

### Guides disponibles

- 📘 [Guide de démarrage rapide](QUICK-START.md)
- 📗 [Documentation DevOps complète](PROJET-DEVOPS.md)
- 📙 [Configuration O2Switch](O2SWITCH-DEPLOYMENT.md)
- 📕 [Configuration des environnements](.github/ENVIRONMENTS-SETUP.md)
- 📓 [Documentation des workflows](.github/WORKFLOWS.md)
- 📔 [Configuration CI/CD](CI-CD-SETUP.md)

### API Documentation

- **Swagger UI**: `/api/documentation`
- **Postman Collection**: `CamWater_API.postman_collection.json`
- **Health Check**: `/api/health`

## 🔒 Sécurité

### Fonctionnalités de sécurité

- ✅ Authentification JWT avec blacklist
- ✅ Rate limiting sur les endpoints sensibles
- ✅ Validation stricte des données
- ✅ Protection CSRF
- ✅ Headers de sécurité
- ✅ Audit de sécurité automatique (Composer Audit)
- ✅ Scan de vulnérabilités dans la pipeline

### Bonnes pratiques

- Les secrets sont stockés dans GitHub Secrets
- Les clés SSH sont dédiées au déploiement
- Les fichiers .env ne sont jamais commités
- HTTPS obligatoire en production
- Permissions strictes sur les fichiers

## 🤝 Contribution

### Workflow Git

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'feat: Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

### Format des commits

Nous utilisons [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: Ajout d'une nouvelle fonctionnalité
fix: Correction d'un bug
docs: Modification de la documentation
style: Changement de style (formatage)
refactor: Refactoring du code
test: Ajout ou modification de tests
chore: Tâches de maintenance
```

## 📊 Statistiques du projet

- **Lignes de code**: ~5000+
- **Tests**: 20+ tests automatisés
- **Couverture**: > 50%
- **Endpoints API**: 30+
- **Temps de build**: ~25 min
- **Uptime**: > 99%

## 🎯 Roadmap

### Court terme
- [x] Pipeline CI/CD complète
- [x] Monitoring automatisé
- [x] Déploiement automatique
- [x] Tests de performance
- [ ] Dashboard Grafana
- [ ] Notifications Slack

### Moyen terme
- [ ] Multi-environnement (dev/staging/prod)
- [ ] Blue/Green deployment
- [ ] Tests E2E
- [ ] API versioning

### Long terme
- [ ] Migration Kubernetes
- [ ] Infrastructure as Code (Terraform)
- [ ] Service mesh
- [ ] Observabilité avancée

## 📞 Support

Pour toute question ou problème:

1. Consultez la [documentation](PROJET-DEVOPS.md)
2. Vérifiez les [issues GitHub](https://github.com/votre-username/camwater-api/issues)
3. Créez une nouvelle issue si nécessaire

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👥 Auteurs

- **Votre Nom** - *Développement initial* - [VotreGitHub](https://github.com/votre-username)

## 🙏 Remerciements

- Laravel Framework
- GitHub Actions
- O2Switch
- Tous les contributeurs

---

**Projet réalisé dans le cadre du cours DevOps**  
**Date**: Mars 2024  
**Version**: 1.0.0

⭐ N'oubliez pas de mettre une étoile si ce projet vous a été utile !
