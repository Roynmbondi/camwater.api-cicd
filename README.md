# CamWater API - Système de Gestion d'Eau

API REST pour la gestion des abonnés, consommations, factures et paiements pour CamWater.

## 🚀 Technologies

- Laravel 11.x
- PHP 8.2+
- MySQL 8.0+
- JWT Authentication
- Swagger/OpenAPI Documentation

## 📋 Fonctionnalités

- **Authentification JWT** avec gestion de blacklist des tokens
- **Gestion des abonnés** (particuliers et professionnels)
- **Suivi des consommations** d'eau
- **Facturation automatique** avec tarification progressive
- **Gestion des paiements** (Mobile Money, Carte bancaire, Espèces)
- **Système de réclamations**
- **Statistiques et rapports**
- **Logs d'activité** pour audit
- **Documentation API Swagger**

## 🔧 Installation

### Prérequis

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (pour les assets)

### Étapes d'installation

1. Cloner le repository
```bash
git clone https://github.com/votre-username/camwater-api.git
cd camwater-api
```

2. Installer les dépendances
```bash
composer install
npm install
```

3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Configurer la base de données dans `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camwater
DB_USERNAME=root
DB_PASSWORD=
```

5. Exécuter les migrations
```bash
php artisan migrate
```

6. Lancer le serveur
```bash
php artisan serve
```

L'API sera accessible sur `http://localhost:8000`

## 📚 Documentation API

La documentation Swagger est disponible sur :
```
http://localhost:8000/api/documentation
```

Vous pouvez également importer la collection Postman : `CamWater_API.postman_collection.json`

## 🔐 Authentification

L'API utilise JWT (JSON Web Tokens) pour l'authentification.

### Login
```bash
POST /api/auth/login
{
  "email": "user@example.com",
  "password": "password"
}
```

### Utiliser le token
```bash
Authorization: Bearer {votre_token}
```

## 🏗️ Structure du projet

```
app/
├── Console/Commands/      # Commandes artisan personnalisées
├── Http/
│   ├── Controllers/       # Contrôleurs API
│   └── Middleware/        # Middlewares personnalisés
├── Models/                # Modèles Eloquent
└── Services/              # Services métier

database/
├── migrations/            # Migrations de base de données
└── seeders/              # Seeders

routes/
└── api.php               # Routes API
```

## 🧪 Tests

```bash
php artisan test
```

## 🛠️ Commandes utiles

### Nettoyer les tokens expirés
```bash
php artisan tokens:clean
```

### Nettoyer les anciens logs
```bash
php artisan logs:clean
```

### Générer la documentation Swagger
```bash
php artisan l5-swagger:generate
```

## 📊 Rôles et Permissions

- **admin** : Accès complet au système
- **operateur** : Gestion des abonnés, consommations et factures
- **abonne** : Consultation de ses propres données

## 🤝 Contribution

Les contributions sont les bienvenues ! Veuillez suivre ces étapes :

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 License

Ce projet est sous licence MIT.

## 👥 Auteurs

- Votre Nom - Développement initial

## 📞 Contact

Pour toute question, contactez : votre.email@example.com
