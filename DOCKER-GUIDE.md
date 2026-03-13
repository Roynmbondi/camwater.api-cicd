# 🐳 Guide Docker - CamWater API

## 📋 Prérequis

- Docker 20.10+
- Docker Compose 2.0+
- Compte Docker Hub

## 🚀 Démarrage rapide

### 1. Configuration locale

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Éditer les variables d'environnement
nano .env
```

### 2. Lancer avec Docker Compose

```bash
# Build et démarrer tous les services
docker-compose up -d

# Voir les logs
docker-compose logs -f

# L'API sera disponible sur http://localhost:8000
```

### 3. Initialisation

```bash
# Exécuter les migrations
docker-compose exec app php artisan migrate

# Créer un utilisateur admin (optionnel)
docker-compose exec app php artisan db:seed
```

## 🏗️ Build de l'image Docker

### Build local

```bash
# Build l'image
docker build -t camwater-api:latest .

# Tester l'image
docker run -p 8000:80 camwater-api:latest
```

### Build multi-plateforme

```bash
# Créer un builder
docker buildx create --name mybuilder --use

# Build pour plusieurs architectures
docker buildx build \
  --platform linux/amd64,linux/arm64 \
  -t votre-username/camwater-api:latest \
  --push .
```

## 📦 Structure des services

```
┌─────────────────────────────────────────┐
│           camwater-app                  │
│     Laravel + PHP-FPM + Nginx           │
│         Port: 8000 → 80                 │
└─────────────────────────────────────────┘
                    │
        ┌───────────┴───────────┐
        ▼                       ▼
┌──────────────┐       ┌──────────────┐
│ camwater-db  │       │camwater-redis│
│  MySQL 8.0   │       │  Redis 7     │
│  Port: 3306  │       │  Port: 6379  │
└──────────────┘       └──────────────┘
```

## 🔧 Commandes utiles

### Gestion des conteneurs

```bash
# Démarrer les services
docker-compose up -d

# Arrêter les services
docker-compose down

# Redémarrer un service
docker-compose restart app

# Voir les logs
docker-compose logs -f app

# Entrer dans un conteneur
docker-compose exec app sh
```

### Laravel Artisan

```bash
# Exécuter des commandes artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan queue:work
```

### Base de données

```bash
# Accéder à MySQL
docker-compose exec db mysql -u root -p

# Backup de la base de données
docker-compose exec db mysqldump -u root -p camwater > backup.sql

# Restaurer la base de données
docker-compose exec -T db mysql -u root -p camwater < backup.sql
```

## 🔐 Variables d'environnement

Variables requises dans `.env` :

```env
# Application
APP_NAME="CamWater API"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=camwater
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# JWT
JWT_SECRET=votre_jwt_secret
JWT_TTL=60
```

## 🚀 Déploiement sur serveur

### 1. Préparer le serveur

```bash
# Installer Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# Installer Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### 2. Déployer l'application

```bash
# Cloner le repository
git clone https://github.com/votre-username/camwater-api.git
cd camwater-api

# Configurer l'environnement
cp .env.example .env
nano .env

# Lancer les services
docker-compose up -d

# Vérifier le statut
docker-compose ps
```

### 3. Configuration Nginx (reverse proxy)

```nginx
server {
    listen 80;
    server_name api.camwater.com;

    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

## 📊 Monitoring

### Health check

```bash
# Vérifier la santé de l'application
curl http://localhost:8000/api/health

# Vérifier les conteneurs
docker-compose ps
docker-compose top
```

### Logs

```bash
# Logs de l'application
docker-compose logs -f app

# Logs de la base de données
docker-compose logs -f db

# Logs de tous les services
docker-compose logs -f
```

### Métriques

```bash
# Utilisation des ressources
docker stats

# Espace disque
docker system df
```

## 🔄 Mise à jour

### Mise à jour de l'application

```bash
# Pull la dernière version
git pull origin main

# Rebuild l'image
docker-compose build app

# Redémarrer les services
docker-compose up -d

# Exécuter les migrations
docker-compose exec app php artisan migrate --force
```

### Mise à jour depuis Docker Hub

```bash
# Pull la dernière image
docker pull votre-username/camwater-api:latest

# Redémarrer avec la nouvelle image
docker-compose up -d
```

## 🐛 Troubleshooting

### Problème : Conteneur ne démarre pas

```bash
# Voir les logs détaillés
docker-compose logs app

# Vérifier la configuration
docker-compose config

# Reconstruire l'image
docker-compose build --no-cache app
```

### Problème : Erreur de connexion à la base de données

```bash
# Vérifier que MySQL est prêt
docker-compose exec db mysqladmin ping -h localhost

# Vérifier les variables d'environnement
docker-compose exec app env | grep DB_

# Redémarrer la base de données
docker-compose restart db
```

### Problème : Permissions

```bash
# Corriger les permissions du storage
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www:www storage bootstrap/cache
```

### Nettoyage

```bash
# Supprimer les conteneurs arrêtés
docker-compose down

# Supprimer les volumes (⚠️ supprime les données)
docker-compose down -v

# Nettoyer le système Docker
docker system prune -a
```

## 📚 Ressources

- [Documentation Docker](https://docs.docker.com/)
- [Documentation Docker Compose](https://docs.docker.com/compose/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Docker Hub](https://hub.docker.com/)

## 🎯 Bonnes pratiques

1. ✅ Toujours utiliser des tags de version spécifiques en production
2. ✅ Ne jamais commiter le fichier `.env`
3. ✅ Utiliser des secrets Docker pour les données sensibles
4. ✅ Faire des backups réguliers de la base de données
5. ✅ Monitorer les logs et les métriques
6. ✅ Mettre à jour régulièrement les images de base
7. ✅ Utiliser des health checks
8. ✅ Limiter les ressources des conteneurs en production
