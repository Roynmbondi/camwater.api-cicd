# 🔐 Configuration des Secrets GitHub

Ce document liste tous les secrets nécessaires pour les workflows CI/CD.

## Comment ajouter des secrets

1. Allez sur votre repository GitHub
2. Cliquez sur **Settings** > **Secrets and variables** > **Actions**
3. Cliquez sur **New repository secret**
4. Ajoutez chaque secret listé ci-dessous

## Secrets requis

### Déploiement (deploy.yml)

| Secret | Description | Exemple |
|--------|-------------|---------|
| `DEPLOY_HOST` | Adresse IP ou domaine du serveur | `192.168.1.100` ou `server.example.com` |
| `DEPLOY_USER` | Nom d'utilisateur SSH | `deploy` |
| `DEPLOY_KEY` | Clé privée SSH (format PEM) | Contenu de `~/.ssh/id_rsa` |
| `DEPLOY_PORT` | Port SSH (optionnel, défaut: 22) | `22` |
| `DEPLOY_PATH` | Chemin de déploiement sur le serveur | `/var/www/camwater-api` |
| `APP_URL` | URL de l'application | `https://api.camwater.com` |

### Base de données (optionnel pour tests)

| Secret | Description | Exemple |
|--------|-------------|---------|
| `DB_HOST` | Hôte de la base de données | `localhost` |
| `DB_DATABASE` | Nom de la base de données | `camwater_test` |
| `DB_USERNAME` | Utilisateur de la base de données | `root` |
| `DB_PASSWORD` | Mot de passe de la base de données | `password` |

### Notifications (optionnel)

| Secret | Description | Exemple |
|--------|-------------|---------|
| `SLACK_WEBHOOK` | Webhook Slack pour notifications | `https://hooks.slack.com/...` |
| `DISCORD_WEBHOOK` | Webhook Discord pour notifications | `https://discord.com/api/webhooks/...` |

## Génération de la clé SSH pour le déploiement

```bash
# Sur votre machine locale
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/github_deploy

# Copier la clé publique sur le serveur
ssh-copy-id -i ~/.ssh/github_deploy.pub user@server.com

# Copier la clé privée dans les secrets GitHub
cat ~/.ssh/github_deploy
# Copiez tout le contenu (y compris BEGIN et END) dans le secret DEPLOY_KEY
```

## Variables d'environnement (optionnel)

Vous pouvez aussi définir des variables d'environnement (non secrètes) :

1. **Settings** > **Secrets and variables** > **Actions** > **Variables**
2. Ajoutez des variables comme :
   - `PHP_VERSION`: `8.2`
   - `NODE_VERSION`: `20`
   - `DEPLOY_BRANCH`: `main`

## Environnements GitHub

Pour le workflow de déploiement, créez des environnements :

1. **Settings** > **Environments**
2. Créez `production` et `staging`
3. Configurez les règles de protection :
   - Required reviewers
   - Wait timer
   - Deployment branches

## Vérification

Après avoir ajouté les secrets, vérifiez qu'ils sont bien configurés :

```bash
# Les secrets apparaîtront masqués dans les logs
echo "Secrets configured: ✅"
```

## Sécurité

⚠️ **Important** :
- Ne commitez JAMAIS de secrets dans le code
- Utilisez des clés SSH dédiées pour le déploiement
- Rotez régulièrement vos secrets
- Limitez les permissions des clés SSH
- Utilisez des environnements protégés pour la production

## Troubleshooting

### Erreur : "Secret not found"
- Vérifiez l'orthographe exacte du secret
- Les secrets sont sensibles à la casse

### Erreur de connexion SSH
- Vérifiez que la clé publique est bien sur le serveur
- Testez la connexion manuellement : `ssh -i ~/.ssh/github_deploy user@server`
- Vérifiez les permissions : `chmod 600 ~/.ssh/github_deploy`

### Workflow ne se déclenche pas
- Vérifiez les branches configurées dans `on.push.branches`
- Vérifiez que les workflows sont activés dans Settings > Actions
