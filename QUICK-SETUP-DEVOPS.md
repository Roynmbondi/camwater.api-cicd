# ⚡ Configuration Rapide DevOps - CamWater API

Guide de configuration rapide pour mettre en place la pipeline CI/CD complète en 30 minutes.

## 📋 Checklist de configuration

- [ ] Compte GitHub avec repository
- [ ] Compte O2Switch avec accès SSH
- [ ] Sous-domaines configurés
- [ ] Bases de données MySQL créées

## 🚀 Configuration en 5 étapes

### Étape 1: Préparer O2Switch (10 min)

#### 1.1 Créer les sous-domaines

Dans cPanel > Domaines > Sous-domaines:

```
Sous-domaine: staging
Document root: /home/username/staging.votredomaine.com/public

Sous-domaine: api
Document root: /home/username/api.votredomaine.com/public
```

#### 1.2 Créer les bases de données

Dans cPanel > Bases de données MySQL:

```
Base de données: username_camwater_staging
Utilisateur: username_staging
Mot de passe: [générer un mot de passe fort]

Base de données: username_camwater_prod
Utilisateur: username_prod
Mot de passe: [générer un mot de passe fort]
```

#### 1.3 Activer SSL

Dans cPanel > SSL/TLS > AutoSSL:
- Activer pour staging.votredomaine.com
- Activer pour api.votredomaine.com

### Étape 2: Configurer SSH (5 min)

#### 2.1 Générer la clé SSH

```bash
# Windows PowerShell
ssh-keygen -t ed25519 -C "github-deploy" -f $env:USERPROFILE\.ssh\o2switch_deploy

# Linux/Mac
ssh-keygen -t ed25519 -C "github-deploy" -f ~/.ssh/o2switch_deploy
```

#### 2.2 Ajouter la clé sur O2Switch

```bash
# Se connecter
ssh votre-username@votredomaine.com

# Ajouter la clé publique
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Coller le contenu de o2switch_deploy.pub
chmod 600 ~/.ssh/authorized_keys
```

#### 2.3 Tester la connexion

```bash
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com
```

### Étape 3: Préparer les répertoires (5 min)

```bash
# Connecté en SSH sur O2Switch
mkdir -p ~/staging.votredomaine.com
mkdir -p ~/api.votredomaine.com
mkdir -p ~/backups
mkdir -p ~/logs

# Créer les fichiers .env
cd ~/staging.votredomaine.com
cat > .env.staging << 'EOF'
APP_NAME="CamWater API Staging"
APP_ENV=staging
APP_KEY=
APP_DEBUG=true
APP_URL=https://staging.votredomaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_camwater_staging
DB_USERNAME=username_staging
DB_PASSWORD=VOTRE_MOT_DE_PASSE_STAGING

JWT_SECRET=
EOF

cd ~/api.votredomaine.com
cat > .env.production << 'EOF'
APP_NAME="CamWater API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://api.votredomaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_camwater_prod
DB_USERNAME=username_prod
DB_PASSWORD=VOTRE_MOT_DE_PASSE_PRODUCTION

JWT_SECRET=
EOF

# Créer les .htaccess
cd ~/staging.votredomaine.com
cat > .htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
EOF

cd ~/api.votredomaine.com
cat > .htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
EOF
```

### Étape 4: Configurer GitHub (5 min)

#### 4.1 Créer les environnements

Dans GitHub > Settings > Environments:

**Environnement "staging":**
- Deployment branches: `develop` uniquement

**Environnement "production":**
- Deployment branches: `main` uniquement
- Required reviewers: Vous-même (optionnel)

#### 4.2 Ajouter les secrets

Dans GitHub > Settings > Secrets and variables > Actions:

**Repository secrets:**

```
O2SWITCH_SSH_KEY
Valeur: [Contenu complet de ~/.ssh/o2switch_deploy]

O2SWITCH_HOST
Valeur: votredomaine.com

O2SWITCH_USER
Valeur: votre-username
```

**Environment secrets (staging):**

```
STAGING_PATH
Valeur: /home/username/staging.votredomaine.com

STAGING_URL
Valeur: https://staging.votredomaine.com
```

**Environment secrets (production):**

```
PRODUCTION_PATH
Valeur: /home/username/api.votredomaine.com

PRODUCTION_URL
Valeur: https://api.votredomaine.com
```

### Étape 5: Premier déploiement (5 min)

#### 5.1 Pousser le code

```bash
# Assurez-vous d'être sur la branche develop
git checkout develop
git add .
git commit -m "feat: initial deployment setup"
git push origin develop
```

#### 5.2 Vérifier le déploiement

1. Allez dans GitHub > Actions
2. Vérifiez que le workflow "Laravel CI/CD Pipeline Complète" s'exécute
3. Attendez que tous les jobs soient verts ✅

#### 5.3 Générer les clés sur le serveur

```bash
# Connecté en SSH sur O2Switch

# Pour staging
cd ~/staging.votredomaine.com
php artisan key:generate --env=staging
php artisan jwt:secret --env=staging
php artisan migrate --force --env=staging

# Pour production
cd ~/api.votredomaine.com
php artisan key:generate --env=production
php artisan jwt:secret --env=production
php artisan migrate --force --env=production
```

#### 5.4 Tester l'API

```bash
# Test staging
curl https://staging.votredomaine.com/api/health

# Test production
curl https://api.votredomaine.com/api/health

# Réponse attendue:
{
  "status": "ok",
  "service": "CamWater API",
  "timestamp": "2024-03-09T10:30:00+00:00",
  "database": "connected"
}
```

## ✅ Vérification finale

### Checklist de validation

- [ ] Les deux sous-domaines sont accessibles en HTTPS
- [ ] Le health check retourne "ok" sur staging
- [ ] Le health check retourne "ok" sur production
- [ ] La documentation Swagger est accessible
- [ ] Les workflows GitHub Actions sont verts
- [ ] Le monitoring quotidien est configuré

### Commandes de vérification

```bash
# Vérifier les workflows GitHub
gh workflow list

# Vérifier les secrets
gh secret list

# Tester le déploiement
./scripts/verify-deployment.sh staging
./scripts/verify-deployment.sh production
```

## 🎯 Prochaines étapes

### Utilisation quotidienne

```bash
# Développement
git checkout develop
# ... faire vos modifications ...
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

### Monitoring

- Les rapports quotidiens sont générés automatiquement
- Consultez GitHub Actions > Artifacts pour les rapports
- Les logs sont disponibles sur le serveur dans `storage/logs/`

### Tests

```bash
# Localement
composer test
./vendor/bin/pint
./vendor/bin/phpstan analyse app

# Dans la pipeline (automatique)
# - Tests unitaires et fonctionnels
# - Analyse statique
# - Audit de sécurité
# - Tests de performance
```

## 🐛 Troubleshooting rapide

### Erreur: Permission denied (publickey)

```bash
# Vérifier la clé SSH
ssh -i ~/.ssh/o2switch_deploy -v votre-username@votredomaine.com

# Vérifier les permissions
chmod 600 ~/.ssh/o2switch_deploy
```

### Erreur: 500 Internal Server Error

```bash
# Sur le serveur
cd ~/api.votredomaine.com
chmod -R 755 storage bootstrap/cache
tail -f storage/logs/laravel.log
```

### Erreur: Database connection failed

```bash
# Vérifier les credentials dans .env
# Vérifier que la base de données existe
# Vérifier les permissions de l'utilisateur MySQL
```

### Workflow GitHub échoue

1. Vérifiez les logs dans GitHub Actions
2. Vérifiez que tous les secrets sont configurés
3. Vérifiez la connexion SSH manuellement
4. Relancez le workflow

## 📚 Documentation complète

Pour plus de détails, consultez:

- [Documentation DevOps complète](PROJET-DEVOPS.md)
- [Guide de déploiement O2Switch](O2SWITCH-DEPLOYMENT.md)
- [Configuration des environnements](.github/ENVIRONMENTS-SETUP.md)
- [Documentation des workflows](.github/WORKFLOWS.md)

## 🎉 Félicitations !

Votre pipeline CI/CD est maintenant opérationnelle ! 🚀

Vous avez maintenant:
- ✅ Déploiement automatique sur staging et production
- ✅ Tests automatisés à chaque push
- ✅ Monitoring quotidien
- ✅ Sécurité renforcée
- ✅ Documentation complète

---

**Temps total de configuration**: ~30 minutes  
**Prochaine étape**: Développer et déployer vos fonctionnalités ! 💪
