# 🚀 Guide de Déploiement O2Switch

Ce guide explique comment configurer le déploiement automatique de l'API CamWater sur O2Switch.

## 📋 Prérequis

- Un compte O2Switch avec accès SSH
- Un sous-domaine configuré (ex: api.votredomaine.com)
- Accès au cPanel O2Switch
- Git installé sur votre serveur O2Switch

## 🔧 Configuration O2Switch

### 1. Créer le sous-domaine

1. Connectez-vous à votre cPanel O2Switch
2. Allez dans **Domaines** > **Sous-domaines**
3. Créez un sous-domaine (ex: `api`)
4. Notez le chemin du document root (ex: `/home/username/api.votredomaine.com`)

### 2. Configuration SSH

#### Générer une clé SSH pour le déploiement

```bash
# Sur votre machine locale
ssh-keygen -t ed25519 -C "github-deploy" -f ~/.ssh/o2switch_deploy
```

#### Ajouter la clé publique sur O2Switch

1. Connectez-vous en SSH à O2Switch:
```bash
ssh votre-username@votredomaine.com
```

2. Ajoutez la clé publique:
```bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
# Collez le contenu de o2switch_deploy.pub
chmod 600 ~/.ssh/authorized_keys
```

### 3. Préparer les répertoires

```bash
# Connecté en SSH sur O2Switch
cd ~

# Créer la structure
mkdir -p api.votredomaine.com
mkdir -p backups
mkdir -p logs

# Créer les fichiers .env
cd api.votredomaine.com
nano .env.production
```

#### Contenu du fichier `.env.production`:

```env
APP_NAME="CamWater API"
APP_ENV=production
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://api.votredomaine.com

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_FAKER_LOCALE=fr_FR

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=votre_base_de_donnees
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

JWT_SECRET=VOTRE_JWT_SECRET
JWT_TTL=60
JWT_REFRESH_TTL=20160
JWT_ALGO=HS256

VITE_APP_NAME="${APP_NAME}"
```

#### Créer aussi `.env.staging` pour l'environnement de staging

### 4. Configuration de la base de données

1. Dans cPanel, allez dans **Bases de données MySQL**
2. Créez une nouvelle base de données
3. Créez un utilisateur MySQL
4. Associez l'utilisateur à la base de données avec tous les privilèges
5. Notez les informations de connexion

### 5. Configuration du .htaccess

Créez un fichier `.htaccess` dans le document root du sous-domaine:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## 🔐 Configuration GitHub Secrets

Allez dans votre repository GitHub > Settings > Secrets and variables > Actions

Ajoutez les secrets suivants:

### Secrets obligatoires:

```
O2SWITCH_SSH_KEY
Contenu: La clé privée SSH (o2switch_deploy)

O2SWITCH_HOST
Contenu: votredomaine.com

O2SWITCH_USER
Contenu: votre-username

STAGING_PATH
Contenu: /home/username/staging.votredomaine.com

STAGING_URL
Contenu: https://staging.votredomaine.com

PRODUCTION_PATH
Contenu: /home/username/api.votredomaine.com

PRODUCTION_URL
Contenu: https://api.votredomaine.com
```

### Comment ajouter la clé SSH:

```bash
# Sur votre machine locale
cat ~/.ssh/o2switch_deploy
# Copiez TOUT le contenu (y compris BEGIN et END)
```

## 🚀 Processus de Déploiement

### Déploiement automatique

Le déploiement se fait automatiquement:

- **Staging**: Push sur la branche `develop`
- **Production**: Push sur la branche `main`

### Workflow de déploiement:

1. **Développement local**
```bash
git checkout develop
# Faites vos modifications
git add .
git commit -m "feat: nouvelle fonctionnalité"
git push origin develop
```

2. **Le pipeline CI/CD s'exécute automatiquement:**
   - ✅ Linting du code
   - ✅ Tests unitaires et fonctionnels
   - ✅ Analyse statique (PHPStan)
   - ✅ Audit de sécurité
   - ✅ Build de l'application
   - ✅ Métriques de performance
   - 🚀 Déploiement sur staging

3. **Validation sur staging**
```bash
# Testez votre application sur staging
curl https://staging.votredomaine.com/api/health
```

4. **Déploiement en production**
```bash
git checkout main
git merge develop
git push origin main
# Le déploiement en production démarre automatiquement
```

## 🔍 Vérification du déploiement

### Health Check

```bash
# Vérifier que l'API répond
curl https://api.votredomaine.com/api/health

# Réponse attendue:
{
  "status": "ok",
  "service": "CamWater API",
  "timestamp": "2024-03-09T10:30:00+00:00",
  "database": "connected"
}
```

### Vérifier les logs

```bash
# Connectez-vous en SSH
ssh votre-username@votredomaine.com

# Voir les logs Laravel
cd api.votredomaine.com/storage/logs
tail -f laravel.log
```

## 🔄 Rollback en cas de problème

Si le déploiement échoue, un rollback automatique est effectué.

Pour un rollback manuel:

```bash
# Connectez-vous en SSH
ssh votre-username@votredomaine.com

cd api.votredomaine.com
LATEST_BACKUP=$(ls -t ../backups/backup-*.tar.gz | head -1)
tar -xzf $LATEST_BACKUP
php artisan config:cache
php artisan up
```

## 📊 Monitoring

Le monitoring automatique s'exécute tous les jours à 6h UTC:

- ✅ Health check de l'API
- ✅ Vérification du temps de réponse
- ✅ Vérification du certificat SSL
- ✅ Collecte des métriques serveur
- ✅ Analyse des logs
- ✅ Nettoyage des anciens logs

## 🛠️ Commandes utiles

### Sur le serveur O2Switch:

```bash
# Voir l'état de l'application
cd api.votredomaine.com
php artisan about

# Mettre l'application en maintenance
php artisan down --message="Maintenance en cours"

# Remettre l'application en ligne
php artisan up

# Vider les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Exécuter les migrations
php artisan migrate --force

# Voir les logs en temps réel
tail -f storage/logs/laravel.log
```

## 🔒 Sécurité

### Permissions recommandées:

```bash
cd api.votredomaine.com
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

### Protéger les fichiers sensibles:

Ajoutez dans `.htaccess` du document root:

```apache
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

## 📞 Support

En cas de problème:

1. Vérifiez les logs GitHub Actions
2. Vérifiez les logs Laravel sur le serveur
3. Testez la connexion SSH manuellement
4. Vérifiez les permissions des fichiers
5. Contactez le support O2Switch si nécessaire

## 🎯 Checklist de déploiement

- [ ] Sous-domaine créé sur O2Switch
- [ ] Clé SSH générée et ajoutée
- [ ] Base de données MySQL créée
- [ ] Fichiers .env configurés
- [ ] Secrets GitHub configurés
- [ ] .htaccess configuré
- [ ] Premier déploiement testé
- [ ] Health check fonctionnel
- [ ] Monitoring activé

---

✅ Une fois cette configuration terminée, vos déploiements seront entièrement automatisés !
