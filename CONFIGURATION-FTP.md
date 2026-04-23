# 🚀 Configuration Déploiement FTP - Guide Simple

Puisque ton API est déjà en ligne, on va juste automatiser le déploiement via FTP.

---

## 📋 Ce dont tu as besoin

### 1. Informations FTP (depuis cPanel)

Connecte-toi à ton cPanel : `https://cdiu8226.odns.fr:2083`

Va dans **Comptes FTP** et note :
- **Serveur FTP** : `cdiu8226.odns.fr` ou `ftp.cdiu8226.odns.fr`
- **Nom d'utilisateur FTP** : `cdiu8226` (ou un compte FTP spécifique)
- **Mot de passe FTP** : ton mot de passe FTP

### 2. Chemins des répertoires

Note les chemins complets de tes applications :
- **Staging** : `/home/cdiu8226/staging.cdiu8226.odns.fr` (ou ton chemin)
- **Production** : `/home/cdiu8226/api.cdiu8226.odns.fr` (ou ton chemin actuel)

---

## 🔐 Configuration GitHub Secrets

Va sur : https://github.com/Roynmbondi/camwater.api-cicd/settings/secrets/actions

### Repository Secrets (3 secrets)

Clique sur **New repository secret** et ajoute :

#### 1. FTP_SERVER
```
Name: FTP_SERVER
Value: cdiu8226.odns.fr
```
(ou `ftp.cdiu8226.odns.fr`)

#### 2. FTP_USERNAME
```
Name: FTP_USERNAME
Value: cdiu8226
```
(ton nom d'utilisateur FTP)

#### 3. FTP_PASSWORD
```
Name: FTP_PASSWORD
Value: ton_mot_de_passe_ftp
```
(ton mot de passe FTP)

---

## 🌍 Configuration des Environments

### Environment "staging"

1. Va dans **Settings** > **Environments**
2. Clique sur **New environment**
3. Name: `staging`
4. Dans **Deployment branches** : Sélectionne `develop`
5. Ajoute ces 2 secrets :

**STAGING_PATH**
```
Name: STAGING_PATH
Value: /home/cdiu8226/staging.cdiu8226.odns.fr
```
(remplace par ton chemin réel)

**STAGING_URL**
```
Name: STAGING_URL
Value: https://staging.cdiu8226.odns.fr
```
(ton URL staging)

### Environment "production"

1. Clique sur **New environment**
2. Name: `production`
3. Dans **Deployment branches** : Sélectionne `main` et `master`
4. Ajoute ces 2 secrets :

**PRODUCTION_PATH**
```
Name: PRODUCTION_PATH
Value: /home/cdiu8226/public_html
```
(remplace par le chemin de ton API actuelle)

**PRODUCTION_URL**
```
Name: PRODUCTION_URL
Value: https://cdiu8226.odns.fr
```
(ton URL de production actuelle)

---

## ✅ Checklist de configuration

- [ ] Secret `FTP_SERVER` ajouté
- [ ] Secret `FTP_USERNAME` ajouté
- [ ] Secret `FTP_PASSWORD` ajouté
- [ ] Environment `staging` créé
- [ ] Secret `STAGING_PATH` ajouté dans staging
- [ ] Secret `STAGING_URL` ajouté dans staging
- [ ] Environment `production` créé
- [ ] Secret `PRODUCTION_PATH` ajouté dans production
- [ ] Secret `PRODUCTION_URL` ajouté dans production

---

## 🚀 Comment ça fonctionne

### Déploiement automatique

1. **Tu push sur `develop`** :
   ```bash
   git push origin develop
   ```
   → Le workflow se déclenche
   → Les tests s'exécutent
   → L'application est buildée
   → Les fichiers sont uploadés via FTP sur staging

2. **Tu push sur `main`** :
   ```bash
   git push origin main
   ```
   → Le workflow se déclenche
   → Les tests s'exécutent
   → L'application est buildée
   → Les fichiers sont uploadés via FTP sur production

### Après le déploiement FTP

Les fichiers sont uploadés automatiquement, mais tu dois exécuter quelques commandes manuellement dans le **Terminal cPanel** :

```bash
# Pour staging
cd /home/cdiu8226/staging.cdiu8226.odns.fr
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan optimize

# Pour production
cd /home/cdiu8226/public_html
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan optimize
```

---

## 🎯 Avantages de cette méthode

- ✅ Pas besoin de SSH
- ✅ Utilise les accès FTP que tu as déjà
- ✅ Déploiement automatique via GitHub Actions
- ✅ Tests automatiques avant déploiement
- ✅ Simple et rapide

---

## 📝 Notes importantes

### Fichiers exclus du déploiement

Ces fichiers ne seront PAS uploadés (pour la sécurité) :
- `.git/`
- `node_modules/`
- `tests/`
- `.env`

Tu dois créer/maintenir le fichier `.env` manuellement sur le serveur.

### Permissions

Après le premier déploiement, assure-toi que les permissions sont correctes :

```bash
chmod -R 755 storage bootstrap/cache
```

---

## 🆘 Troubleshooting

### Le déploiement échoue ?

1. Vérifie que tous les secrets sont bien configurés
2. Vérifie que les chemins sont corrects
3. Vérifie que le mot de passe FTP est correct
4. Regarde les logs dans GitHub Actions

### Les fichiers ne s'uploadent pas ?

1. Vérifie que le serveur FTP est accessible
2. Vérifie les permissions du répertoire cible
3. Vérifie que l'espace disque est suffisant

---

## 🎉 C'est tout !

Une fois configuré :
- Push sur `develop` → Déploiement automatique sur staging
- Push sur `main` → Déploiement automatique sur production
- Monitoring automatique tous les jours
- Tests automatiques à chaque push

**Temps de configuration : 10 minutes**

---

**Prêt ? Configure les secrets GitHub et c'est parti !** 🚀
