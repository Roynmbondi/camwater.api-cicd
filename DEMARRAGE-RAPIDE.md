# ⚡ Démarrage Rapide - Configuration en 3 étapes

Guide ultra-rapide pour configurer le déploiement. Tout est automatisé !

---

## 🎯 Ce que tu dois faire

### Étape 1: Exécuter le script de configuration (5 min)

Ouvre PowerShell dans le dossier du projet et exécute:

```powershell
.\scripts\setup-deployment.ps1
```

Le script va:
- ✅ Te demander tes informations O2Switch
- ✅ Générer automatiquement la clé SSH
- ✅ Copier la clé dans ton presse-papier
- ✅ Créer un fichier de configuration
- ✅ Te donner toutes les instructions

**Tu auras besoin de:**
- Ton nom d'hôte O2Switch (ex: votredomaine.com)
- Ton nom d'utilisateur SSH
- Ton nom de domaine

### Étape 2: Ajouter la clé SSH sur O2Switch (3 min)

Le script te copie automatiquement la clé publique. Tu dois juste:

1. Te connecter en SSH:
```bash
ssh ton-username@ton-domaine.com
```

2. Exécuter ces commandes:
```bash
mkdir -p ~/.ssh && chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
# Colle la clé (Ctrl+Shift+V)
# Sauvegarde (Ctrl+X, Y, Enter)
chmod 600 ~/.ssh/authorized_keys
```

3. Tester la connexion:
```powershell
ssh -i $env:USERPROFILE\.ssh\o2switch_deploy ton-username@ton-domaine.com
```

Si tu es connecté sans mot de passe, c'est bon ! ✅

### Étape 3: Configurer GitHub Secrets (5 min)

Le script te donne toutes les valeurs à copier-coller.

#### 3.1 Repository Secrets

Va sur: `https://github.com/TON-USERNAME/camwater-api/settings/secrets/actions`

Ajoute ces 3 secrets (le script te donne les valeurs):

1. **O2SWITCH_SSH_KEY** - La clé privée (déjà dans ton presse-papier)
2. **O2SWITCH_HOST** - Ton domaine
3. **O2SWITCH_USER** - Ton username

#### 3.2 Environment "staging"

1. Va dans **Settings** > **Environments**
2. Clique sur **New environment**
3. Name: `staging`
4. Ajoute 2 secrets:
   - **STAGING_PATH** - Le chemin staging
   - **STAGING_URL** - L'URL staging

#### 3.3 Environment "production"

1. Clique sur **New environment**
2. Name: `production`
3. Ajoute 2 secrets:
   - **PRODUCTION_PATH** - Le chemin production
   - **PRODUCTION_URL** - L'URL production

---

## 🚀 C'est tout !

Une fois ces 3 étapes terminées:

1. Le script aura tout configuré localement
2. GitHub aura tous les secrets nécessaires
3. Tu pourras pousser ton code et le déploiement se fera automatiquement

---

## 📋 Checklist rapide

- [ ] Script `setup-deployment.ps1` exécuté
- [ ] Clé SSH ajoutée sur O2Switch
- [ ] Connexion SSH testée et fonctionnelle
- [ ] 3 Repository Secrets ajoutés sur GitHub
- [ ] Environment "staging" créé avec 2 secrets
- [ ] Environment "production" créé avec 2 secrets

---

## ✅ Tester le déploiement

Une fois tout configuré:

```powershell
# Pousser sur develop pour tester
git push origin develop

# Aller voir dans GitHub Actions
# Le workflow devrait se déclencher automatiquement
```

---

## 🆘 Besoin d'aide ?

Si tu bloques:

1. **Le script ne fonctionne pas ?**
   - Vérifie que tu es dans le dossier du projet
   - Vérifie que PowerShell est bien ouvert

2. **La connexion SSH ne fonctionne pas ?**
   - Vérifie que la clé est bien dans `~/.ssh/authorized_keys` sur O2Switch
   - Vérifie les permissions: `chmod 600 ~/.ssh/authorized_keys`

3. **Le workflow GitHub échoue ?**
   - Vérifie que tous les secrets sont bien configurés
   - Vérifie les logs dans GitHub Actions

4. **Besoin de plus de détails ?**
   - Consulte `.github/CONFIGURATION-SECRETS.md`
   - Consulte `O2SWITCH-DEPLOYMENT.md`

---

## 🎉 Après la configuration

Une fois que tout fonctionne:

1. **Développement**: Push sur `develop` → Déploiement automatique sur staging
2. **Production**: Push sur `main` → Déploiement automatique sur production
3. **Monitoring**: Automatique tous les jours à 6h UTC
4. **Tests**: Automatiques à chaque push

---

**Temps total estimé: 15 minutes**

**Prêt ? Lance le script !**

```powershell
.\scripts\setup-deployment.ps1
```
