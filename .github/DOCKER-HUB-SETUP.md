# 🐳 Configuration Docker Hub pour CI/CD

## 📋 Étapes de configuration

### 1️⃣ Créer un compte Docker Hub

1. Allez sur https://hub.docker.com/
2. Cliquez sur "Sign Up"
3. Créez votre compte (gratuit)
4. Vérifiez votre email

### 2️⃣ Créer un Access Token

1. Connectez-vous à Docker Hub
2. Cliquez sur votre avatar > **Account Settings**
3. Allez dans **Security** > **Access Tokens**
4. Cliquez sur **New Access Token**
5. Donnez un nom : `github-actions-camwater`
6. Permissions : **Read, Write, Delete**
7. Cliquez sur **Generate**
8. ⚠️ **COPIEZ LE TOKEN** (vous ne pourrez plus le voir)

### 3️⃣ Créer un repository sur Docker Hub

1. Sur Docker Hub, cliquez sur **Repositories**
2. Cliquez sur **Create Repository**
3. Remplissez :
   - **Name** : `camwater-api`
   - **Description** : "API REST pour la gestion d'eau CamWater"
   - **Visibility** : Public ou Private
4. Cliquez sur **Create**

### 4️⃣ Configurer les secrets GitHub

Allez sur votre repository GitHub :
**Settings > Secrets and variables > Actions > New repository secret**

Ajoutez ces deux secrets :

#### Secret 1 : DOCKERHUB_USERNAME
```
Nom: DOCKERHUB_USERNAME
Valeur: votre-username-dockerhub
```

#### Secret 2 : DOCKERHUB_TOKEN
```
Nom: DOCKERHUB_TOKEN
Valeur: [le token que vous avez copié à l'étape 2]
```

## ✅ Vérification

### Tester localement

```bash
# Login à Docker Hub
docker login -u votre-username

# Build l'image
docker build -t votre-username/camwater-api:test .

# Push l'image
docker push votre-username/camwater-api:test

# Pull l'image
docker pull votre-username/camwater-api:test
```

### Tester le workflow GitHub

```bash
# Créer une branche de test
git checkout -b test/docker-pipeline

# Faire un changement
echo "# Docker Test" >> README.md

# Commit et push
git add README.md
git commit -m "test: verify Docker CI/CD pipeline"
git push origin test/docker-pipeline

# Créer une Pull Request sur GitHub
# Le workflow va :
# 1. Exécuter les tests
# 2. Analyser le code
# 3. Build l'image Docker
# 4. Tester l'image
```

### Vérifier sur Docker Hub

1. Allez sur https://hub.docker.com/
2. Cliquez sur votre repository `camwater-api`
3. Vous devriez voir les tags créés par GitHub Actions

## 🏷️ Tags automatiques

Le workflow crée automatiquement ces tags :

| Événement | Tag créé | Exemple |
|-----------|----------|---------|
| Push sur main | `latest` | `camwater-api:latest` |
| Push sur develop | `develop` | `camwater-api:develop` |
| Tag version | `v1.0.0`, `1.0`, `1` | `camwater-api:v1.0.0` |
| Commit SHA | `main-abc1234` | `camwater-api:main-abc1234` |

## 🚀 Utilisation des images

### Pull depuis Docker Hub

```bash
# Dernière version
docker pull votre-username/camwater-api:latest

# Version spécifique
docker pull votre-username/camwater-api:v1.0.0

# Branche develop
docker pull votre-username/camwater-api:develop
```

### Utiliser dans docker-compose.yml

```yaml
services:
  app:
    image: votre-username/camwater-api:latest
    # ... reste de la configuration
```

### Déployer sur un serveur

```bash
# Sur le serveur de production
docker pull votre-username/camwater-api:latest
docker-compose up -d
```

## 📊 Workflow complet

```
┌─────────────────────────────────────────────────────────┐
│  1. Developer push code to GitHub                       │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  2. GitHub Actions triggered                            │
│     ├─ Run tests (PHPUnit)                             │
│     ├─ Code quality (PHPStan, CodeSniffer)             │
│     └─ Security checks                                  │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  3. Build Docker image                                  │
│     ├─ Multi-stage build                               │
│     ├─ Optimize for production                         │
│     └─ Multi-platform (amd64, arm64)                   │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  4. Security scan (Trivy)                               │
│     └─ Check for vulnerabilities                        │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  5. Push to Docker Hub                                  │
│     ├─ Tag: latest                                      │
│     ├─ Tag: branch name                                 │
│     └─ Tag: commit SHA                                  │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  6. Image available on Docker Hub                       │
│     └─ Ready to deploy anywhere                         │
└─────────────────────────────────────────────────────────┘
```

## 🔒 Sécurité

### Bonnes pratiques

1. ✅ **Ne jamais** commiter les tokens Docker Hub
2. ✅ Utiliser des Access Tokens (pas le mot de passe)
3. ✅ Limiter les permissions des tokens
4. ✅ Régénérer les tokens régulièrement
5. ✅ Utiliser des images de base officielles
6. ✅ Scanner les images pour les vulnérabilités
7. ✅ Utiliser des tags de version spécifiques en production

### Rotation des tokens

```bash
# Tous les 3-6 mois :
# 1. Créer un nouveau token sur Docker Hub
# 2. Mettre à jour le secret GitHub
# 3. Supprimer l'ancien token sur Docker Hub
```

## 📈 Monitoring

### Voir les builds sur GitHub

1. GitHub > Actions
2. Sélectionner "Docker CI/CD Pipeline"
3. Voir les détails de chaque étape

### Voir les images sur Docker Hub

1. Docker Hub > Repositories > camwater-api
2. Onglet "Tags" : voir tous les tags
3. Onglet "Insights" : statistiques de pulls

### Webhooks (optionnel)

Configurer des webhooks pour être notifié :

1. Docker Hub > Repository > Webhooks
2. Ajouter une URL de webhook
3. Recevoir des notifications sur chaque push

## 🎯 Déploiement automatique

### Option 1 : Watchtower (auto-update)

```yaml
# Ajouter à docker-compose.yml
services:
  watchtower:
    image: containrrr/watchtower
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
    command: --interval 300 --cleanup
```

### Option 2 : Webhook sur le serveur

```bash
# Installer webhook
sudo apt-get install webhook

# Créer un script de déploiement
cat > /opt/deploy.sh << 'EOF'
#!/bin/bash
cd /var/www/camwater-api
docker-compose pull
docker-compose up -d
EOF

chmod +x /opt/deploy.sh
```

## 🆘 Troubleshooting

### Erreur : "unauthorized: authentication required"

```bash
# Vérifier les secrets GitHub
# Settings > Secrets > DOCKERHUB_USERNAME et DOCKERHUB_TOKEN

# Tester localement
docker login -u votre-username
```

### Erreur : "denied: requested access to the resource is denied"

```bash
# Vérifier que le repository existe sur Docker Hub
# Vérifier les permissions du token
```

### Build échoue

```bash
# Voir les logs détaillés dans GitHub Actions
# Tester le build localement :
docker build -t test .
```

## 📚 Ressources

- [Docker Hub Documentation](https://docs.docker.com/docker-hub/)
- [GitHub Actions Docker](https://docs.github.com/en/actions/publishing-packages/publishing-docker-images)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)

## ✨ Félicitations !

Votre pipeline Docker CI/CD est maintenant configurée ! 🎉

Chaque push sur GitHub va automatiquement :
- ✅ Tester votre code
- ✅ Analyser la qualité
- ✅ Build l'image Docker
- ✅ Scanner les vulnérabilités
- ✅ Publier sur Docker Hub

**Prochaine étape** : Push votre code et regardez la magie opérer ! 🚀
