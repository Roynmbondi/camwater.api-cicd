# 🎯 Instructions pour toi - Ce qu'il te reste à faire

Salut ! J'ai tout préparé pour toi. Voici exactement ce que tu dois faire maintenant.

---

## ✅ Ce qui est déjà fait

- ✅ Pipeline CI/CD complète créée (3 workflows)
- ✅ Documentation complète (13 fichiers)
- ✅ Scripts de vérification créés
- ✅ Script de configuration automatique créé
- ✅ Code poussé sur GitHub (branche `develop`)

**Total**: ~6700 lignes de code et documentation créées ! 🎉

---

## 🚀 Ce qu'il te reste à faire (15 minutes)

### Étape 1: Exécuter le script de configuration (5 min)

Ouvre PowerShell dans le dossier du projet:

```powershell
cd chemin\vers\camwater-api
.\scripts\setup-deployment.ps1
```

**Le script va te demander:**
1. Ton nom d'hôte O2Switch (ex: votredomaine.com)
2. Ton nom d'utilisateur SSH
3. Ton nom de domaine

**Le script va faire automatiquement:**
- Générer la clé SSH
- Copier la clé dans ton presse-papier
- Créer un fichier de configuration
- Te donner toutes les instructions

### Étape 2: Configurer O2Switch (5 min)

#### 2.1 Ajouter la clé SSH

```bash
# Connecte-toi en SSH
ssh ton-username@ton-domaine.com

# Ajoute la clé (elle est déjà dans ton presse-papier)
mkdir -p ~/.ssh && chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
# Colle avec Ctrl+Shift+V
# Sauvegarde avec Ctrl+X, Y, Enter
chmod 600 ~/.ssh/authorized_keys
exit
```

#### 2.2 Créer les sous-domaines dans cPanel

1. Connecte-toi à cPanel O2Switch
2. Va dans **Domaines** > **Sous-domaines**
3. Crée:
   - Sous-domaine: `staging`
   - Document root: `/home/ton-username/staging.ton-domaine.com/public`
4. Crée:
   - Sous-domaine: `api`
   - Document root: `/home/ton-username/api.ton-domaine.com/public`

#### 2.3 Créer les bases de données

1. Va dans **Bases de données MySQL**
2. Crée une base pour staging:
   - Nom: `ton-username_camwater_staging`
   - User: `ton-username_staging`
   - Password: [génère un mot de passe fort]
3. Crée une base pour production:
   - Nom: `ton-username_camwater_prod`
   - User: `ton-username_prod`
   - Password: [génère un mot de passe fort]

#### 2.4 Activer SSL

1. Va dans **SSL/TLS** > **AutoSSL**
2. Active pour `staging.ton-domaine.com`
3. Active pour `api.ton-domaine.com`

### Étape 3: Configurer GitHub Secrets (5 min)

#### 3.1 Repository Secrets

Va sur: https://github.com/Roynmbondi/camwater.api-cicd/settings/secrets/actions

Clique sur **New repository secret** et ajoute:

**1. O2SWITCH_SSH_KEY**
```
Name: O2SWITCH_SSH_KEY
Value: [La clé privée - le script l'a copiée dans ton presse-papier]
```

Pour récupérer la clé si besoin:
```powershell
Get-Content $env:USERPROFILE\.ssh\o2switch_deploy | Set-Clipboard
```

**2. O2SWITCH_HOST**
```
Name: O2SWITCH_HOST
Value: ton-domaine.com
```

**3. O2SWITCH_USER**
```
Name: O2SWITCH_USER
Value: ton-username
```

#### 3.2 Environment "staging"

1. Va dans **Settings** > **Environments**
2. Clique sur **New environment**
3. Name: `staging`
4. Dans **Deployment branches**: Sélectionne **Selected branches** et ajoute `develop`
5. Ajoute ces secrets:

**STAGING_PATH**
```
Name: STAGING_PATH
Value: /home/ton-username/staging.ton-domaine.com
```

**STAGING_URL**
```
Name: STAGING_URL
Value: https://staging.ton-domaine.com
```

#### 3.3 Environment "production"

1. Clique sur **New environment**
2. Name: `production`
3. Dans **Deployment branches**: Sélectionne **Selected branches** et ajoute `master`
4. Ajoute ces secrets:

**PRODUCTION_PATH**
```
Name: PRODUCTION_PATH
Value: /home/ton-username/api.ton-domaine.com
```

**PRODUCTION_URL**
```
Name: PRODUCTION_URL
Value: https://api.ton-domaine.com
```

---

## ✅ Vérifier que tout fonctionne

### Test 1: Connexion SSH

```powershell
ssh -i $env:USERPROFILE\.ssh\o2switch_deploy ton-username@ton-domaine.com "echo 'SSH OK'"
```

Si ça affiche "SSH OK", c'est bon ! ✅

### Test 2: Préparer les répertoires sur O2Switch

Connecte-toi en SSH et exécute:

```bash
# Créer les répertoires
mkdir -p ~/staging.ton-domaine.com
mkdir -p ~/api.ton-domaine.com
mkdir -p ~/backups
mkdir -p ~/logs

# Créer les .htaccess
cd ~/staging.ton-domaine.com
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

cd ~/api.ton-domaine.com
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

echo "Répertoires créés !"
```

### Test 3: Déclencher le workflow GitHub

Le workflow devrait déjà être en cours d'exécution car tu as poussé sur `develop`.

Vérifie sur: https://github.com/Roynmbondi/camwater.api-cicd/actions

Tu devrais voir le workflow "Laravel CI/CD Pipeline Complète" en cours.

---

## 📋 Checklist complète

### Configuration locale
- [ ] Script `setup-deployment.ps1` exécuté
- [ ] Clé SSH générée
- [ ] Fichier `.deployment-config` créé

### O2Switch
- [ ] Clé SSH ajoutée dans `~/.ssh/authorized_keys`
- [ ] Connexion SSH testée
- [ ] Sous-domaine `staging` créé
- [ ] Sous-domaine `api` créé
- [ ] Base de données staging créée
- [ ] Base de données production créée
- [ ] SSL activé pour staging
- [ ] SSL activé pour production
- [ ] Répertoires créés sur le serveur
- [ ] Fichiers .htaccess créés

### GitHub
- [ ] Secret `O2SWITCH_SSH_KEY` ajouté
- [ ] Secret `O2SWITCH_HOST` ajouté
- [ ] Secret `O2SWITCH_USER` ajouté
- [ ] Environment `staging` créé
- [ ] Secret `STAGING_PATH` ajouté
- [ ] Secret `STAGING_URL` ajouté
- [ ] Environment `production` créé
- [ ] Secret `PRODUCTION_PATH` ajouté
- [ ] Secret `PRODUCTION_URL` ajouté

### Tests
- [ ] Connexion SSH fonctionne
- [ ] Workflow GitHub s'exécute
- [ ] Tous les jobs sont verts ✅

---

## 🎯 Après la configuration

Une fois que tout est configuré et que le workflow est vert:

### 1. Créer les fichiers .env sur le serveur

Connecte-toi en SSH et crée les fichiers .env:

```bash
# Pour staging
cd ~/staging.ton-domaine.com
cat > .env.staging << 'EOF'
APP_NAME="CamWater API Staging"
APP_ENV=staging
APP_KEY=
APP_DEBUG=true
APP_URL=https://staging.ton-domaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ton-username_camwater_staging
DB_USERNAME=ton-username_staging
DB_PASSWORD=TON_PASSWORD_STAGING

JWT_SECRET=
EOF

# Pour production
cd ~/api.ton-domaine.com
cat > .env.production << 'EOF'
APP_NAME="CamWater API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://api.ton-domaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ton-username_camwater_prod
DB_USERNAME=ton-username_prod
DB_PASSWORD=TON_PASSWORD_PRODUCTION

JWT_SECRET=
EOF
```

### 2. Attendre le premier déploiement

Le workflow va:
1. Exécuter tous les tests ✅
2. Faire l'analyse de code ✅
3. Vérifier la sécurité ✅
4. Builder l'application ✅
5. Déployer sur staging ✅

### 3. Générer les clés sur le serveur

Une fois le premier déploiement terminé:

```bash
# Pour staging
cd ~/staging.ton-domaine.com
php artisan key:generate --env=staging
php artisan jwt:secret --env=staging
php artisan migrate --force --env=staging

# Pour production (quand tu pusheras sur master)
cd ~/api.ton-domaine.com
php artisan key:generate --env=production
php artisan jwt:secret --env=production
php artisan migrate --force --env=production
```

### 4. Tester l'API

```bash
# Test staging
curl https://staging.ton-domaine.com/api/health

# Test production (après déploiement sur master)
curl https://api.ton-domaine.com/api/health
```

Réponse attendue:
```json
{
  "status": "ok",
  "service": "CamWater API",
  "timestamp": "2024-03-09T10:30:00+00:00",
  "database": "connected"
}
```

---

## 📚 Documentation utile

- **DEMARRAGE-RAPIDE.md** - Guide ultra-rapide
- **.github/CONFIGURATION-SECRETS.md** - Instructions détaillées
- **O2SWITCH-DEPLOYMENT.md** - Guide complet O2Switch
- **COMMANDES-UTILES.md** - Toutes les commandes
- **RESUME-FINAL.md** - Résumé de tout le projet

---

## 🆘 Besoin d'aide ?

### Le workflow échoue ?

1. Vérifie les logs dans GitHub Actions
2. Vérifie que tous les secrets sont bien configurés
3. Vérifie que la clé SSH fonctionne

### La connexion SSH ne fonctionne pas ?

```powershell
# Teste avec verbose
ssh -i $env:USERPROFILE\.ssh\o2switch_deploy -v ton-username@ton-domaine.com
```

### Le déploiement échoue ?

1. Vérifie que les répertoires existent sur O2Switch
2. Vérifie les permissions
3. Vérifie les logs du workflow

---

## 🎉 C'est tout !

Une fois ces étapes terminées:

- ✅ Push sur `develop` → Déploiement automatique sur staging
- ✅ Push sur `master` → Déploiement automatique sur production
- ✅ Monitoring automatique tous les jours
- ✅ Tests automatiques à chaque push

**Temps total: 15 minutes**

**Bon courage ! 🚀**

---

## 📞 Contact

Si tu bloques vraiment, consulte:
1. Les logs GitHub Actions
2. La documentation dans les fichiers .md
3. Le fichier `.deployment-config` créé par le script

**Tout est prêt, il ne te reste plus qu'à ajouter tes accès ! 💪**
