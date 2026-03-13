# 🔄 Documentation des Workflows CI/CD

Ce document explique tous les workflows GitHub Actions configurés pour ce projet.

## 📋 Vue d'ensemble

| Workflow | Déclencheur | Durée estimée | Description |
|----------|-------------|---------------|-------------|
| `laravel.yml` | Push/PR | ~5 min | Tests et qualité du code |
| `pull-request.yml` | PR | ~3 min | Validation des PRs |
| `deploy.yml` | Push main/tags | ~10 min | Déploiement automatique |
| `release.yml` | Tags v*.*.* | ~8 min | Création de releases |
| `scheduled.yml` | Cron quotidien | ~2 min | Maintenance automatique |
| `documentation.yml` | Push main | ~5 min | Génération de docs |

## 🔍 Détail des workflows

### 1. Laravel CI/CD Pipeline (`laravel.yml`)

**Déclenchement** : Push ou Pull Request sur `main`, `master`, `develop`

**Jobs** :
1. **Tests & Code Quality**
   - Teste sur PHP 8.2 et 8.3
   - Exécute les tests PHPUnit avec couverture
   - Upload des rapports de couverture vers Codecov

2. **Static Code Analysis**
   - PHPStan (niveau 5)
   - PHP_CodeSniffer (PSR-12)

3. **Security Checks**
   - Vérification des vulnérabilités (security-checker)
   - Audit des dépendances Composer

4. **Build Application**
   - Installation des dépendances production
   - Build des assets NPM
   - Génération de la documentation Swagger
   - Création d'un artifact de déploiement

5. **Notify Success**
   - Notification de succès du pipeline

**Utilisation** :
```bash
# Déclenché automatiquement sur push
git push origin develop

# Ou manuellement via l'interface GitHub
```

### 2. Pull Request Checks (`pull-request.yml`)

**Déclenchement** : Ouverture, synchronisation ou réouverture d'une PR

**Jobs** :
1. **PR Title Check** : Valide le format du titre (conventional commits)
2. **Changed Files Detection** : Détecte les fichiers modifiés
3. **Quick Tests** : Tests rapides sur les fichiers PHP modifiés
4. **Code Quality** : Analyse de code avec PHPStan et CodeSniffer
5. **PR Size Check** : Avertit si la PR est trop grande
6. **PR Comment** : Ajoute un commentaire automatique avec le résumé

**Format de titre requis** :
```
feat(scope): description
fix(scope): description
docs: description
```

### 3. Deploy to Production (`deploy.yml`)

**Déclenchement** :
- Push sur `main`/`master`
- Tags `v*.*.*`
- Manuel via workflow_dispatch

**Jobs** :
1. **Deploy Application**
   - Création du package de déploiement
   - Upload via SCP
   - Exécution des commandes de déploiement
   - Migrations de base de données
   - Cache Laravel
   - Health check post-déploiement
   - Rollback automatique en cas d'échec

**Secrets requis** :
- `DEPLOY_HOST`
- `DEPLOY_USER`
- `DEPLOY_KEY`
- `DEPLOY_PATH`
- `APP_URL`

**Utilisation manuelle** :
```bash
# Via l'interface GitHub
Actions > Deploy to Production > Run workflow
```

### 4. Create Release (`release.yml`)

**Déclenchement** : Push d'un tag `v*.*.*`

**Jobs** :
1. **Create GitHub Release**
   - Build de l'application
   - Génération du changelog automatique
   - Création de l'archive de release
   - Publication sur GitHub Releases
   - Attachement de la collection Postman

**Utilisation** :
```bash
# Créer un tag et le pousser
git tag v1.0.0
git push origin v1.0.0

# La release sera créée automatiquement
```

### 5. Scheduled Maintenance (`scheduled.yml`)

**Déclenchement** : Tous les jours à 2h UTC (cron)

**Jobs** :
1. **Dependency Check** : Vérifie les dépendances obsolètes
2. **Health Check** : Teste la santé de l'application en production
3. **Cleanup** : Nettoie les anciens workflow runs et artifacts
4. **Weekly Report** : Génère un rapport hebdomadaire (lundi)

**Utilisation manuelle** :
```bash
# Via l'interface GitHub
Actions > Scheduled Maintenance > Run workflow
```

### 6. Update Documentation (`documentation.yml`)

**Déclenchement** : Push sur `main` modifiant des fichiers PHP ou docs

**Jobs** :
1. **Generate API Docs** : Génère la documentation Swagger
2. **Generate Code Docs** : Génère la documentation PHPDoc
3. **Deploy Docs** : Déploie sur GitHub Pages

**Accès à la documentation** :
```
https://votre-username.github.io/camwater-api/
```

## 🎯 Bonnes pratiques

### Commits
```bash
# Format recommandé
feat(abonne): add professional subscriber type
fix(auth): resolve token expiration issue
docs: update API documentation
```

### Branches
```
main/master    → Production
develop        → Développement
feature/xxx    → Nouvelles fonctionnalités
fix/xxx        → Corrections de bugs
hotfix/xxx     → Corrections urgentes
```

### Pull Requests
1. Créer une branche depuis `develop`
2. Faire vos modifications
3. Pousser et créer une PR vers `develop`
4. Attendre les checks automatiques
5. Demander une revue
6. Merger après approbation

### Releases
```bash
# Version patch (1.0.0 → 1.0.1)
git tag v1.0.1

# Version minor (1.0.0 → 1.1.0)
git tag v1.1.0

# Version major (1.0.0 → 2.0.0)
git tag v2.0.0

# Pousser le tag
git push origin v1.0.1
```

## 🔧 Configuration

### Activer GitHub Pages
1. Settings > Pages
2. Source: Deploy from a branch
3. Branch: `gh-pages` / `root`

### Protéger les branches
1. Settings > Branches > Add rule
2. Branch name pattern: `main`
3. Cocher :
   - Require pull request reviews
   - Require status checks to pass
   - Require branches to be up to date

### Configurer les environnements
1. Settings > Environments
2. Créer `production` et `staging`
3. Ajouter des reviewers requis
4. Configurer les secrets spécifiques

## 📊 Monitoring

### Badges à ajouter au README

```markdown
![CI/CD](https://github.com/username/camwater-api/workflows/Laravel%20CI%2FCD%20Pipeline/badge.svg)
![Deploy](https://github.com/username/camwater-api/workflows/Deploy%20to%20Production/badge.svg)
[![codecov](https://codecov.io/gh/username/camwater-api/branch/main/graph/badge.svg)](https://codecov.io/gh/username/camwater-api)
```

### Voir les logs
```bash
# Via l'interface GitHub
Actions > Sélectionner un workflow > Voir les détails
```

## 🐛 Troubleshooting

### Workflow ne se déclenche pas
- Vérifier que les workflows sont activés (Settings > Actions)
- Vérifier les branches dans `on.push.branches`
- Vérifier les permissions du token GitHub

### Tests échouent
- Vérifier les logs détaillés dans Actions
- Reproduire localement : `php artisan test`
- Vérifier la configuration de la base de données

### Déploiement échoue
- Vérifier les secrets configurés
- Tester la connexion SSH manuellement
- Vérifier les permissions sur le serveur

### Documentation ne se génère pas
- Vérifier que GitHub Pages est activé
- Vérifier la branche `gh-pages` existe
- Attendre quelques minutes après le déploiement

## 📚 Ressources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Laravel Testing](https://laravel.com/docs/testing)
- [Conventional Commits](https://www.conventionalcommits.org/)
- [Semantic Versioning](https://semver.org/)
