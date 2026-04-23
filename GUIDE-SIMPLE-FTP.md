# ⚡ Guide Ultra-Simple - Déploiement FTP

## 🎯 Ce que tu dois faire (10 minutes)

### Étape 1 : Trouve tes infos FTP (2 min)

1. Connecte-toi à cPanel : `https://cdiu8226.odns.fr:2083`
2. Va dans **Comptes FTP**
3. Note :
   - Serveur : `cdiu8226.odns.fr`
   - Username : `cdiu8226`
   - Password : [ton mot de passe FTP]

### Étape 2 : Configure GitHub (8 min)

Va sur : https://github.com/Roynmbondi/camwater.api-cicd/settings/secrets/actions

#### Ajoute 3 Repository Secrets :

1. **FTP_SERVER** = `cdiu8226.odns.fr`
2. **FTP_USERNAME** = `cdiu8226`
3. **FTP_PASSWORD** = [ton mot de passe FTP]

#### Crée l'environment "staging" :

1. Settings > Environments > New environment
2. Name : `staging`
3. Ajoute 2 secrets :
   - **STAGING_PATH** = `/home/cdiu8226/staging.cdiu8226.odns.fr`
   - **STAGING_URL** = `https://staging.cdiu8226.odns.fr`

#### Crée l'environment "production" :

1. New environment
2. Name : `production`
3. Ajoute 2 secrets :
   - **PRODUCTION_PATH** = `/home/cdiu8226/public_html` (ou le chemin de ton API actuelle)
   - **PRODUCTION_URL** = `https://cdiu8226.odns.fr` (ou ton URL actuelle)

---

## ✅ C'est tout !

Maintenant :
- Push sur `develop` → Déploiement automatique sur staging
- Push sur `main` → Déploiement automatique sur production

---

## 🚀 Tester

```bash
git push origin develop
```

Puis va voir : https://github.com/Roynmbondi/camwater.api-cicd/actions

Le workflow devrait se lancer automatiquement ! 🎉

---

**Temps total : 10 minutes**
