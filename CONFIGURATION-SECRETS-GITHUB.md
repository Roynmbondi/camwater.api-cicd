# Configuration des Secrets GitHub pour le Déploiement FTP

## 📋 Informations de connexion FTP

### Serveur FTP O2Switch
- **Serveur FTP**: `ftp.cdiu8226.odns.fr`
- **Port**: `21`
- **Nom d'utilisateur**: `deploy@cdwfs.net`
- **Mot de passe**: [À définir - ne pas mettre ici]
- **Path utilisateur FTP**: `/home/cdiu8226/`
- **Path de déploiement**: `/home/cdiu8226/public_html/mbondi/api`

### URL de l'application
- **Staging URL**: `https://mbondi.cdwfs.net` (ou `http://mbondi.cdwfs.net`)

---

## 🔐 Secrets à configurer dans GitHub

### Étape 1 : Accéder aux Secrets GitHub

1. Aller sur ton repository GitHub : `https://github.com/Roynmbondi/camwater.api-cicd`
2. Cliquer sur **Settings** (Paramètres)
3. Dans le menu de gauche, cliquer sur **Secrets and variables** → **Actions**
4. Cliquer sur **New repository secret**

### Étape 2 : Créer les secrets suivants

#### Secret 1 : FTP_SERVER
- **Name**: `FTP_SERVER`
- **Value**: `ftp.cdiu8226.odns.fr`
- Cliquer sur **Add secret**

#### Secret 2 : FTP_USERNAME
- **Name**: `FTP_USERNAME`
- **Value**: `deploy@cdwfs.net`
- Cliquer sur **Add secret**

#### Secret 3 : FTP_PASSWORD
- **Name**: `FTP_PASSWORD`
- **Value**: `[TON_MOT_DE_PASSE_FTP]`
- ⚠️ **Important** : Entre le vrai mot de passe FTP ici
- Cliquer sur **Add secret**

#### Secret 4 : STAGING_PATH
- **Name**: `STAGING_PATH`
- **Value**: `public_html/mbondi/api`
- ℹ️ **Note** : Chemin relatif depuis `/home/cdiu8226/`
- Cliquer sur **Add secret**

#### Secret 5 : STAGING_URL
- **Name**: `STAGING_URL`
- **Value**: `https://mbondi.cdwfs.net`
- ℹ️ **Note** : Utilise `https://` si SSL est configuré, sinon `http://`
- Cliquer sur **Add secret**

#### Secret 6 : PRODUCTION_PATH (optionnel pour plus tard)
- **Name**: `PRODUCTION_PATH`
- **Value**: `public_html/mbondi/api` (ou un autre chemin pour la production)
- Cliquer sur **Add secret**

#### Secret 7 : PRODUCTION_URL (optionnel pour plus tard)
- **Name**: `PRODUCTION_URL`
- **Value**: `https://votre-domaine-production.com`
- Cliquer sur **Add secret**

---

## ✅ Vérification des secrets

Une fois tous les secrets créés, tu devrais voir dans la liste :

```
FTP_SERVER          ••••••••
FTP_USERNAME        ••••••••
FTP_PASSWORD        ••••••••
STAGING_PATH        ••••••••
STAGING_URL         ••••••••
PRODUCTION_PATH     ••••••••  (optionnel)
PRODUCTION_URL      ••••••••  (optionnel)
```

---

## 🚀 Test du déploiement

### Méthode 1 : Push sur develop
```bash
git add .
git commit -m "test: trigger deployment"
git push origin develop
```

Le workflow `Laravel CI/CD Pipeline Complète` va se déclencher automatiquement et déployer sur staging.

### Méthode 2 : Déclenchement manuel
1. Aller sur **Actions** dans GitHub
2. Sélectionner le workflow **Laravel CI/CD Pipeline Complète**
3. Cliquer sur **Run workflow**
4. Sélectionner la branche `develop`
5. Cliquer sur **Run workflow**

---

## 📊 Suivi du déploiement

1. Aller sur **Actions** dans GitHub
2. Cliquer sur le workflow en cours d'exécution
3. Observer les étapes :
   - ✅ Linting & Code Quality
   - ✅ Tests PHP 8.2, 8.3
   - ✅ Static Code Analysis
   - ✅ Security Audit
   - ✅ Build Application
   - ✅ Performance Metrics
   - ✅ Deploy to Staging

4. Si le déploiement réussit, vérifier l'application :
   - Ouvrir `https://mbondi.cdwfs.net/api/health`
   - Tu devrais voir : `{"status":"ok","service":"CamWater API",...}`

---

## 🔧 Configuration post-déploiement sur O2Switch

### Via cPanel Terminal ou SSH

Une fois les fichiers uploadés, tu dois exécuter ces commandes sur le serveur :

```bash
# Se connecter en SSH ou utiliser le Terminal cPanel
cd /home/cdiu8226/public_html/mbondi/api

# Créer le fichier .env s'il n'existe pas
cp .env.example .env

# Éditer le .env avec les bonnes valeurs
nano .env

# Générer la clé d'application
php artisan key:generate

# Générer le secret JWT
php artisan jwt:secret

# Donner les permissions
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs

# Exécuter les migrations
php artisan migrate --force

# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Configuration du .env sur le serveur

Éditer `/home/cdiu8226/public_html/mbondi/api/.env` :

```env
APP_NAME="CamWater API"
APP_ENV=production
APP_KEY=base64:... (généré par php artisan key:generate)
APP_DEBUG=false
APP_URL=https://mbondi.cdwfs.net

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cdiu8226_camwater
DB_USERNAME=cdiu8226_camwater
DB_PASSWORD=[MOT_DE_PASSE_DB]

JWT_SECRET=... (généré par php artisan jwt:secret)
JWT_TTL=60
JWT_REFRESH_TTL=20160

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error
```

---

## 🔍 Dépannage

### Erreur : "Input required and not supplied: server"
- ✅ **Solution** : Vérifier que tous les secrets sont bien créés dans GitHub

### Erreur : "FTP connection failed"
- Vérifier que le serveur FTP est correct : `ftp.cdiu8226.odns.fr`
- Vérifier que le port est `21`
- Vérifier le nom d'utilisateur : `deploy@cdwfs.net`
- Vérifier le mot de passe FTP

### Erreur : "Directory not found"
- Vérifier que le chemin `STAGING_PATH` est correct : `public_html/mbondi/api`
- Le chemin doit être relatif depuis `/home/cdiu8226/`

### L'application ne fonctionne pas après déploiement
1. Vérifier que le fichier `.env` existe sur le serveur
2. Vérifier les permissions : `chmod -R 755 storage bootstrap/cache`
3. Vérifier les logs : `tail -f storage/logs/laravel.log`
4. Exécuter les commandes d'optimisation

### Health check échoue
1. Vérifier que l'URL est correcte : `https://mbondi.cdwfs.net/api/health`
2. Vérifier que le fichier `.htaccess` est présent dans `public/`
3. Vérifier la configuration Apache/Nginx sur O2Switch

---

## 📝 Notes importantes

1. **Ne jamais commiter le mot de passe FTP** dans le code
2. **Les secrets GitHub sont chiffrés** et ne peuvent pas être lus une fois créés
3. **Le déploiement FTP écrase les fichiers** existants (sauf ceux dans `exclude`)
4. **Le fichier `.env` n'est pas déployé** - il doit être créé manuellement sur le serveur
5. **Les migrations doivent être exécutées manuellement** après le premier déploiement

---

## 🎯 Prochaines étapes

1. ✅ Configurer tous les secrets dans GitHub
2. ✅ Pousser un commit sur `develop` pour déclencher le déploiement
3. ✅ Vérifier que le déploiement réussit
4. ✅ Configurer le `.env` sur le serveur
5. ✅ Exécuter les migrations
6. ✅ Tester l'application : `https://mbondi.cdwfs.net/api/health`

---

## 📞 Support

Si tu rencontres des problèmes :
1. Vérifier les logs du workflow dans GitHub Actions
2. Vérifier les logs Laravel sur le serveur : `storage/logs/laravel.log`
3. Vérifier les logs Apache/Nginx sur O2Switch
4. Contacter le support O2Switch si nécessaire
