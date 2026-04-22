# 🌍 Configuration des Environnements GitHub

Ce guide explique comment configurer les environnements GitHub pour le déploiement automatique.

## 📋 Vue d'ensemble

Nous utilisons 2 environnements:
- **Staging**: Pour tester les nouvelles fonctionnalités
- **Production**: Pour l'application en production

## 🔧 Configuration des environnements

### 1. Créer les environnements

1. Allez dans votre repository GitHub
2. Cliquez sur **Settings** > **Environments**
3. Cliquez sur **New environment**

#### Créer l'environnement "staging"

- **Name**: `staging`
- **Deployment branches**: `develop` uniquement
- **Environment secrets**: (voir ci-dessous)

#### Créer l'environnement "production"

- **Name**: `production`
- **Deployment branches**: `main` uniquement
- **Required reviewers**: Ajoutez-vous (optionnel mais recommandé)
- **Wait timer**: 5 minutes (optionnel)
- **Environment secrets**: (voir ci-dessous)

### 2. Configurer les secrets

#### Secrets globaux (Repository secrets)

Allez dans **Settings** > **Secrets and variables** > **Actions** > **New repository secret**

```
O2SWITCH_SSH_KEY
Description: Clé SSH privée pour se connecter à O2Switch
Valeur: Contenu complet de votre clé privée SSH
       (incluant -----BEGIN OPENSSH PRIVATE KEY----- et -----END OPENSSH PRIVATE KEY-----)

O2SWITCH_HOST
Description: Nom d'hôte O2Switch
Valeur: votredomaine.com (ou l'IP du serveur)

O2SWITCH_USER
Description: Nom d'utilisateur SSH O2Switch
Valeur: votre-username
```

#### Secrets de l'environnement "staging"

Dans **Environments** > **staging** > **Add secret**

```
STAGING_PATH
Description: Chemin vers le répertoire staging sur O2Switch
Valeur: /home/username/staging.votredomaine.com

STAGING_URL
Description: URL de l'environnement staging
Valeur: https://staging.votredomaine.com
```

#### Secrets de l'environnement "production"

Dans **Environments** > **production** > **Add secret**

```
PRODUCTION_PATH
Description: Chemin vers le répertoire production sur O2Switch
Valeur: /home/username/api.votredomaine.com

PRODUCTION_URL
Description: URL de l'environnement production
Valeur: https://api.votredomaine.com
```

## 🔑 Génération de la clé SSH

### Sur Windows (PowerShell)

```powershell
# Générer la clé SSH
ssh-keygen -t ed25519 -C "github-deploy-camwater" -f $env:USERPROFILE\.ssh\o2switch_deploy

# Afficher la clé publique (à ajouter sur O2Switch)
Get-Content $env:USERPROFILE\.ssh\o2switch_deploy.pub

# Afficher la clé privée (à ajouter dans GitHub Secrets)
Get-Content $env:USERPROFILE\.ssh\o2switch_deploy
```

### Sur Linux/Mac

```bash
# Générer la clé SSH
ssh-keygen -t ed25519 -C "github-deploy-camwater" -f ~/.ssh/o2switch_deploy

# Afficher la clé publique (à ajouter sur O2Switch)
cat ~/.ssh/o2switch_deploy.pub

# Afficher la clé privée (à ajouter dans GitHub Secrets)
cat ~/.ssh/o2switch_deploy
```

## 📤 Ajouter la clé publique sur O2Switch

### Méthode 1: Via SSH

```bash
# Se connecter à O2Switch
ssh votre-username@votredomaine.com

# Créer le répertoire .ssh si nécessaire
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Ajouter la clé publique
nano ~/.ssh/authorized_keys
# Collez le contenu de o2switch_deploy.pub
# Sauvegardez avec Ctrl+X, Y, Enter

# Définir les bonnes permissions
chmod 600 ~/.ssh/authorized_keys
```

### Méthode 2: Via cPanel

1. Connectez-vous à cPanel O2Switch
2. Allez dans **Sécurité** > **Clés SSH**
3. Cliquez sur **Gérer** à côté de votre clé publique
4. Cliquez sur **Autoriser**
5. Ou importez directement la clé publique

## ✅ Tester la connexion SSH

```bash
# Tester la connexion avec la clé
ssh -i ~/.ssh/o2switch_deploy votre-username@votredomaine.com

# Si ça fonctionne, vous devriez être connecté sans mot de passe
```

## 🗂️ Structure des répertoires sur O2Switch

Créez cette structure sur votre serveur O2Switch:

```
/home/username/
├── api.votredomaine.com/          # Production
│   ├── .env.production
│   └── (fichiers de l'application)
├── staging.votredomaine.com/      # Staging
│   ├── .env.staging
│   └── (fichiers de l'application)
├── backups/                       # Backups automatiques
└── logs/                          # Logs de déploiement
```

### Créer les répertoires

```bash
# Connecté en SSH sur O2Switch
mkdir -p ~/api.votredomaine.com
mkdir -p ~/staging.votredomaine.com
mkdir -p ~/backups
mkdir -p ~/logs

# Définir les permissions
chmod 755 ~/api.votredomaine.com
chmod 755 ~/staging.votredomaine.com
```

## 📝 Créer les fichiers .env

### .env.production

```bash
cd ~/api.votredomaine.com
nano .env.production
```

Contenu minimal:

```env
APP_NAME="CamWater API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://api.votredomaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=votre_base_production
DB_USERNAME=votre_user_production
DB_PASSWORD=votre_password_production

JWT_SECRET=
```

### .env.staging

```bash
cd ~/staging.votredomaine.com
nano .env.staging
```

Contenu minimal:

```env
APP_NAME="CamWater API Staging"
APP_ENV=staging
APP_KEY=
APP_DEBUG=true
APP_URL=https://staging.votredomaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=votre_base_staging
DB_USERNAME=votre_user_staging
DB_PASSWORD=votre_password_staging

JWT_SECRET=
```

### Générer les clés

```bash
# Pour production
cd ~/api.votredomaine.com
php artisan key:generate --env=production
php artisan jwt:secret --env=production

# Pour staging
cd ~/staging.votredomaine.com
php artisan key:generate --env=staging
php artisan jwt:secret --env=staging
```

## 🌐 Configuration des sous-domaines dans cPanel

### 1. Créer le sous-domaine staging

1. cPanel > **Domaines** > **Sous-domaines**
2. **Sous-domaine**: `staging`
3. **Domaine**: `votredomaine.com`
4. **Racine du document**: `/home/username/staging.votredomaine.com/public`
5. Cliquez sur **Créer**

### 2. Créer le sous-domaine api (production)

1. cPanel > **Domaines** > **Sous-domaines**
2. **Sous-domaine**: `api`
3. **Domaine**: `votredomaine.com`
4. **Racine du document**: `/home/username/api.votredomaine.com/public`
5. Cliquez sur **Créer**

### 3. Activer SSL/HTTPS

1. cPanel > **Sécurité** > **SSL/TLS**
2. Activez **AutoSSL** pour les deux sous-domaines
3. Ou installez un certificat Let's Encrypt

## 🔒 Configuration .htaccess

Créez un fichier `.htaccess` dans chaque document root:

```bash
# Pour staging
nano ~/staging.votredomaine.com/.htaccess
```

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# Protection des fichiers sensibles
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "^composer\.(json|lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

Répétez pour production:

```bash
nano ~/api.votredomaine.com/.htaccess
```

## ✅ Vérification de la configuration

### Checklist

- [ ] Environnements GitHub créés (staging, production)
- [ ] Secrets GitHub configurés
- [ ] Clé SSH générée
- [ ] Clé publique ajoutée sur O2Switch
- [ ] Connexion SSH testée
- [ ] Répertoires créés sur O2Switch
- [ ] Fichiers .env créés et configurés
- [ ] Sous-domaines créés dans cPanel
- [ ] SSL/HTTPS activé
- [ ] .htaccess configuré
- [ ] Bases de données MySQL créées

### Test de déploiement

1. Faites un commit sur la branche `develop`:

```bash
git checkout develop
git commit --allow-empty -m "test: trigger staging deployment"
git push origin develop
```

2. Vérifiez dans GitHub Actions que le déploiement se lance
3. Vérifiez que l'application est accessible sur staging
4. Testez le health check:

```bash
curl https://staging.votredomaine.com/api/health
```

## 🐛 Troubleshooting

### Erreur: Permission denied (publickey)

```bash
# Vérifiez que la clé est bien ajoutée sur O2Switch
ssh -i ~/.ssh/o2switch_deploy -v votre-username@votredomaine.com

# Vérifiez les permissions
chmod 600 ~/.ssh/o2switch_deploy
chmod 644 ~/.ssh/o2switch_deploy.pub
```

### Erreur: Host key verification failed

```bash
# Ajoutez l'hôte aux known_hosts
ssh-keyscan -H votredomaine.com >> ~/.ssh/known_hosts
```

### Erreur: Database connection failed

```bash
# Vérifiez les credentials dans .env
# Vérifiez que la base de données existe
# Vérifiez que l'utilisateur a les bonnes permissions
```

### Erreur: 500 Internal Server Error

```bash
# Vérifiez les permissions
chmod -R 755 storage bootstrap/cache

# Vérifiez les logs
tail -f storage/logs/laravel.log
```

## 📞 Support

Si vous rencontrez des problèmes:

1. Vérifiez les logs GitHub Actions
2. Vérifiez les logs Laravel sur le serveur
3. Consultez la documentation O2Switch
4. Contactez le support O2Switch si nécessaire

---

✅ Une fois cette configuration terminée, vos déploiements automatiques fonctionneront !
