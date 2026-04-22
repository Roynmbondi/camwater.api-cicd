# 🔐 Configuration des Secrets GitHub - À COMPLÉTER

Ce fichier contient toutes les informations dont tu as besoin pour configurer les secrets GitHub.

## ⚠️ IMPORTANT

**NE JAMAIS COMMITER CE FICHIER AVEC TES VRAIS SECRETS !**

Une fois rempli, utilise ce fichier uniquement pour copier-coller les valeurs dans GitHub.

---

## 📝 Informations à remplir

### 1. Informations O2Switch

```
Nom d'hôte O2Switch:     _______________________________
Nom d'utilisateur SSH:   _______________________________
Domaine principal:       _______________________________
```

### 2. Sous-domaines

```
Sous-domaine staging:    staging._______________________
Sous-domaine production: api.___________________________
```

### 3. Chemins sur le serveur

```
Chemin staging:          /home/___________/staging.___________
Chemin production:       /home/___________/api.___________
```

### 4. Base de données

#### Staging
```
Nom BDD staging:         _______________________________
User BDD staging:        _______________________________
Password BDD staging:    _______________________________
```

#### Production
```
Nom BDD production:      _______________________________
User BDD production:     _______________________________
Password BDD production: _______________________________
```

---

## 🔑 Génération de la clé SSH

### Étape 1: Générer la clé SSH

Ouvre PowerShell et exécute:

```powershell
# Créer le dossier .ssh s'il n'existe pas
New-Item -ItemType Directory -Force -Path $env:USERPROFILE\.ssh

# Générer la clé SSH
ssh-keygen -t ed25519 -C "github-deploy-camwater" -f $env:USERPROFILE\.ssh\o2switch_deploy -N '""'
```

### Étape 2: Afficher la clé publique

```powershell
# Afficher la clé publique (à ajouter sur O2Switch)
Get-Content $env:USERPROFILE\.ssh\o2switch_deploy.pub
```

**Copie cette clé publique** et note-la ici:

```
Clé publique SSH:
ssh-ed25519 AAAA... github-deploy-camwater
```

### Étape 3: Afficher la clé privée

```powershell
# Afficher la clé privée (à ajouter dans GitHub Secrets)
Get-Content $env:USERPROFILE\.ssh\o2switch_deploy
```

**NE PAS COPIER ICI !** Tu la copieras directement dans GitHub Secrets.

---

## 🌐 Configuration sur O2Switch

### Étape 1: Ajouter la clé SSH sur O2Switch

1. Connecte-toi en SSH à O2Switch:
```bash
ssh ton-username@ton-domaine.com
```

2. Crée le dossier .ssh et ajoute la clé:
```bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
# Colle la clé publique
# Sauvegarde avec Ctrl+X, Y, Enter
chmod 600 ~/.ssh/authorized_keys
```

### Étape 2: Tester la connexion SSH

```powershell
# Tester la connexion avec la clé
ssh -i $env:USERPROFILE\.ssh\o2switch_deploy ton-username@ton-domaine.com
```

Si ça fonctionne, tu es connecté sans mot de passe ! ✅

### Étape 3: Créer les sous-domaines dans cPanel

1. Connecte-toi à cPanel O2Switch
2. Va dans **Domaines** > **Sous-domaines**
3. Crée:
   - Sous-domaine: `staging`
   - Document root: `/home/ton-username/staging.ton-domaine.com/public`
4. Crée:
   - Sous-domaine: `api`
   - Document root: `/home/ton-username/api.ton-domaine.com/public`

### Étape 4: Créer les bases de données

1. Va dans **Bases de données MySQL**
2. Crée une base pour staging:
   - Nom: `ton-username_camwater_staging`
   - User: `ton-username_staging`
   - Password: [génère un mot de passe fort]
3. Crée une base pour production:
   - Nom: `ton-username_camwater_prod`
   - User: `ton-username_prod`
   - Password: [génère un mot de passe fort]

### Étape 5: Activer SSL

1. Va dans **SSL/TLS** > **AutoSSL**
2. Active pour `staging.ton-domaine.com`
3. Active pour `api.ton-domaine.com`

---

## 🔐 Configuration des Secrets GitHub

### Étape 1: Aller dans les paramètres GitHub

1. Va sur ton repository GitHub
2. Clique sur **Settings**
3. Va dans **Secrets and variables** > **Actions**

### Étape 2: Ajouter les Repository Secrets

Clique sur **New repository secret** et ajoute:

#### Secret 1: O2SWITCH_SSH_KEY

```
Name: O2SWITCH_SSH_KEY
Value: [Colle TOUT le contenu de la clé privée]
       (incluant -----BEGIN OPENSSH PRIVATE KEY----- et -----END OPENSSH PRIVATE KEY-----)
```

Pour obtenir la clé:
```powershell
Get-Content $env:USERPROFILE\.ssh\o2switch_deploy | Set-Clipboard
# La clé est maintenant dans ton presse-papier
```

#### Secret 2: O2SWITCH_HOST

```
Name: O2SWITCH_HOST
Value: ton-domaine.com
```

#### Secret 3: O2SWITCH_USER

```
Name: O2SWITCH_USER
Value: ton-username
```

### Étape 3: Créer les Environments

#### Environment "staging"

1. Va dans **Settings** > **Environments**
2. Clique sur **New environment**
3. Name: `staging`
4. Clique sur **Configure environment**
5. Dans **Deployment branches**: Sélectionne **Selected branches** et ajoute `develop`
6. Ajoute les secrets d'environnement:

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

#### Environment "production"

1. Clique sur **New environment**
2. Name: `production`
3. Clique sur **Configure environment**
4. Dans **Deployment branches**: Sélectionne **Selected branches** et ajoute `main` et `master`
5. (Optionnel) Ajoute-toi comme **Required reviewers**
6. Ajoute les secrets d'environnement:

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

## 📋 Checklist de configuration

### Sur O2Switch

- [ ] Clé SSH publique ajoutée dans `~/.ssh/authorized_keys`
- [ ] Connexion SSH testée et fonctionnelle
- [ ] Sous-domaine `staging` créé
- [ ] Sous-domaine `api` créé
- [ ] Base de données staging créée
- [ ] Base de données production créée
- [ ] SSL activé pour staging
- [ ] SSL activé pour production

### Sur GitHub

- [ ] Secret `O2SWITCH_SSH_KEY` ajouté
- [ ] Secret `O2SWITCH_HOST` ajouté
- [ ] Secret `O2SWITCH_USER` ajouté
- [ ] Environment `staging` créé
- [ ] Secret `STAGING_PATH` ajouté dans staging
- [ ] Secret `STAGING_URL` ajouté dans staging
- [ ] Environment `production` créé
- [ ] Secret `PRODUCTION_PATH` ajouté dans production
- [ ] Secret `PRODUCTION_URL` ajouté dans production

---

## 🚀 Préparer les répertoires sur O2Switch

Une fois connecté en SSH, exécute ces commandes:

```bash
# Créer les répertoires
mkdir -p ~/staging.ton-domaine.com
mkdir -p ~/api.ton-domaine.com
mkdir -p ~/backups
mkdir -p ~/logs

# Créer les fichiers .env
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
```

---

## ✅ Test final

Une fois tout configuré, teste:

```powershell
# Test de connexion SSH
ssh -i $env:USERPROFILE\.ssh\o2switch_deploy ton-username@ton-domaine.com "echo 'SSH OK'"

# Si ça affiche "SSH OK", c'est bon ! ✅
```

---

## 🎯 Prochaine étape

Une fois TOUT configuré:

1. Va sur GitHub
2. Clique sur **Actions**
3. Tu devrais voir le workflow "Laravel CI/CD Pipeline Complète"
4. Il devrait se déclencher automatiquement après le push

Ou déclenche-le manuellement:
1. Actions > Laravel CI/CD Pipeline Complète
2. Run workflow > Branch: develop > Run workflow

---

## 📞 Besoin d'aide ?

Si tu rencontres un problème:

1. Vérifie que tous les secrets sont bien configurés
2. Vérifie que la clé SSH fonctionne
3. Vérifie les logs dans GitHub Actions
4. Consulte `O2SWITCH-DEPLOYMENT.md` pour plus de détails

---

**⚠️ RAPPEL: Ne jamais commiter ce fichier avec tes vrais secrets !**
