# 🚀 Guide de Configuration CI/CD - CamWater API

## ✅ Ce qui a été configuré

Votre projet dispose maintenant d'un système complet d'intégration et de déploiement continu avec **6 workflows GitHub Actions** :

### 📦 Workflows créés

1. **`laravel.yml`** - Pipeline CI/CD principal
   - Tests automatiques (PHP 8.2 & 8.3)
   - Analyse de code statique (PHPStan, CodeSniffer)
   - Vérifications de sécurité
   - Build et création d'artifacts
   - Couverture de code

2. **`pull-request.yml`** - Validation des PRs
   - Vérification du format du titre
   - Tests rapides sur fichiers modifiés
   - Analyse de qualité du code
   - Vérification de la taille de la PR
   - Commentaires automatiques

3. **`deploy.yml`** - Déploiement automatique
   - Déploiement sur serveur via SSH
   - Migrations automatiques
   - Health checks
   - Rollback automatique en cas d'échec

4. **`release.yml`** - Création de releases
   - Génération automatique du changelog
   - Création d'archives de release
   - Publication sur GitHub Releases

5. **`scheduled.yml`** - Maintenance automatique
   - Vérification quotidienne des dépendances
   - Health checks de production
   - Nettoyage automatique
   - Rapports hebdomadaires

6. **`documentation.yml`** - Documentation automatique
   - Génération Swagger/OpenAPI
   - Documentation PHPDoc
   - Déploiement sur GitHub Pages

### 📋 Fichiers de configuration

- **`dependabot.yml`** : Mises à jour automatiques des dépendances
- **`labels.yml`** : Labels pour l'organisation du projet
- **Templates** : Bug reports, feature requests, pull requests
- **Documentation** : SECRETS.md, WORKFLOWS.md

## 🎯 Prochaines étapes

### 1. Pousser vers GitHub

```bash
# Créer le repository sur GitHub (si pas encore fait)
# Puis exécuter :

git remote add origin https://github.com/votre-username/camwater-api.git
git branch -M main
git push -u origin main
```

### 2. Configurer les secrets (IMPORTANT !)

Allez sur GitHub : **Settings > Secrets and variables > Actions**

#### Secrets minimaux pour le déploiement :
```
DEPLOY_HOST=votre-serveur.com
DEPLOY_USER=deploy
DEPLOY_KEY=<votre-clé-ssh-privée>
DEPLOY_PATH=/var/www/camwater-api
APP_URL=https://api.camwater.com
```

📖 Voir `.github/SECRETS.md` pour la liste complète

### 3. Activer GitHub Pages (pour la documentation)

1. **Settings > Pages**
2. Source: **Deploy from a branch**
3. Branch: **gh-pages** / **root**
4. Sauvegarder

### 4. Protéger la branche main

1. **Settings > Branches > Add rule**
2. Branch name pattern: `main`
3. Cocher :
   - ✅ Require pull request reviews before merging
   - ✅ Require status checks to pass before merging
   - ✅ Require branches to be up to date before merging

### 5. Créer les environnements

1. **Settings > Environments**
2. Créer `production` et `staging`
3. Ajouter des reviewers requis pour production
4. Configurer les secrets spécifiques à chaque environnement

### 6. Activer Dependabot

1. **Settings > Code security and analysis**
2. Activer **Dependabot alerts**
3. Activer **Dependabot security updates**

## 🧪 Tester les workflows

### Test local avant push
```bash
# Installer act (optionnel - pour tester localement)
# https://github.com/nektos/act

# Tester le workflow CI
act push

# Tester un workflow spécifique
act -W .github/workflows/laravel.yml
```

### Premier test sur GitHub
```bash
# Créer une branche de test
git checkout -b test/ci-workflow

# Faire un petit changement
echo "# Test CI" >> README.md

# Commit et push
git add README.md
git commit -m "test: verify CI/CD pipeline"
git push origin test/ci-workflow

# Créer une Pull Request sur GitHub
# Les workflows se déclencheront automatiquement !
```

## 📊 Monitoring et badges

### Ajouter des badges au README

```markdown
![CI/CD Pipeline](https://github.com/votre-username/camwater-api/workflows/Laravel%20CI%2FCD%20Pipeline/badge.svg)
![Deploy](https://github.com/votre-username/camwater-api/workflows/Deploy%20to%20Production/badge.svg)
```

### Voir les workflows en action

1. Allez sur l'onglet **Actions** de votre repository
2. Vous verrez tous les workflows et leur statut
3. Cliquez sur un workflow pour voir les détails

## 🔄 Workflow de développement recommandé

### Pour une nouvelle fonctionnalité

```bash
# 1. Créer une branche depuis develop
git checkout develop
git pull origin develop
git checkout -b feature/nouvelle-fonctionnalite

# 2. Développer et tester localement
php artisan test

# 3. Commit avec conventional commits
git add .
git commit -m "feat(module): add new feature"

# 4. Push et créer une PR
git push origin feature/nouvelle-fonctionnalite
# Créer la PR sur GitHub vers develop

# 5. Les workflows automatiques vont :
#    - Vérifier le format du titre
#    - Exécuter les tests
#    - Analyser le code
#    - Ajouter un commentaire avec le résumé

# 6. Après approbation, merger la PR
# 7. Les tests se relancent sur develop
```

### Pour un déploiement en production

```bash
# 1. Merger develop dans main
git checkout main
git pull origin main
git merge develop
git push origin main

# 2. Le workflow de déploiement se déclenche automatiquement
# 3. Surveiller dans Actions > Deploy to Production

# 4. Pour une release versionnée
git tag v1.0.0
git push origin v1.0.0

# 5. Le workflow de release crée automatiquement :
#    - Une GitHub Release
#    - Un changelog
#    - Une archive téléchargeable
```

## 📚 Documentation complète

- **`.github/WORKFLOWS.md`** : Détails de tous les workflows
- **`.github/SECRETS.md`** : Configuration des secrets
- **`CONTRIBUTING.md`** : Guide de contribution
- **`README.md`** : Documentation du projet

## 🎨 Personnalisation

### Modifier les workflows

Les fichiers sont dans `.github/workflows/`. Vous pouvez :
- Ajuster les versions de PHP testées
- Modifier les branches déclencheurs
- Ajouter des étapes personnalisées
- Configurer des notifications (Slack, Discord, etc.)

### Ajouter des notifications

Exemple pour Slack dans `laravel.yml` :

```yaml
- name: Notify Slack
  if: always()
  uses: 8398a7/action-slack@v3
  with:
    status: ${{ job.status }}
    webhook_url: ${{ secrets.SLACK_WEBHOOK }}
```

## ✨ Fonctionnalités avancées

### Déploiement manuel

```bash
# Via l'interface GitHub
Actions > Deploy to Production > Run workflow
# Choisir l'environnement (production/staging)
```

### Rollback en cas de problème

Le workflow de déploiement inclut un rollback automatique, mais vous pouvez aussi :

```bash
# Se connecter au serveur
ssh deploy@votre-serveur.com

# Revenir à la version précédente
cd /var/www/camwater-api
ln -sfn backup-YYYYMMDD-HHMMSS current
cd current
php artisan up
```

### Rapports de couverture

Configurez Codecov pour des rapports détaillés :

1. Allez sur https://codecov.io
2. Connectez votre repository
3. Le token est automatiquement configuré

## 🐛 Troubleshooting

### Les workflows ne se déclenchent pas
- Vérifier que Actions est activé : Settings > Actions > Allow all actions
- Vérifier les permissions : Settings > Actions > Workflow permissions

### Tests échouent sur GitHub mais pas localement
- Vérifier la version de PHP
- Vérifier les variables d'environnement
- Consulter les logs détaillés dans Actions

### Déploiement échoue
- Vérifier les secrets configurés
- Tester la connexion SSH manuellement
- Vérifier les permissions sur le serveur

## 🎉 Félicitations !

Votre projet dispose maintenant d'un système CI/CD professionnel avec :
- ✅ Tests automatiques
- ✅ Analyse de code
- ✅ Déploiement automatique
- ✅ Releases automatiques
- ✅ Documentation automatique
- ✅ Maintenance automatique

**Prochaine étape** : Poussez votre code sur GitHub et regardez la magie opérer ! 🚀
