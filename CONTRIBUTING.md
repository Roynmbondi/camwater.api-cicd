# Guide de Contribution

Merci de votre intérêt pour contribuer à CamWater API !

## 🔄 Processus de contribution

1. **Fork** le repository
2. **Clone** votre fork localement
3. **Créer une branche** pour votre fonctionnalité
4. **Développer** et tester vos modifications
5. **Commit** avec des messages clairs
6. **Push** vers votre fork
7. **Créer une Pull Request**

## 📝 Standards de code

### PHP
- Suivre PSR-12 pour le style de code
- Utiliser les types stricts
- Documenter les méthodes avec PHPDoc
- Nommer les variables de manière explicite

### Commits
Format : `type(scope): message`

Types :
- `feat`: Nouvelle fonctionnalité
- `fix`: Correction de bug
- `docs`: Documentation
- `style`: Formatage
- `refactor`: Refactoring
- `test`: Tests
- `chore`: Maintenance

Exemple : `feat(abonne): add professional subscriber type`

## 🧪 Tests

Toujours ajouter des tests pour les nouvelles fonctionnalités :
```bash
php artisan test
```

## 📋 Checklist avant PR

- [ ] Code respecte PSR-12
- [ ] Tests ajoutés et passent
- [ ] Documentation mise à jour
- [ ] Pas de conflits avec main
- [ ] Commit messages clairs

## 🐛 Signaler un bug

Utilisez les GitHub Issues avec :
- Description claire du problème
- Étapes pour reproduire
- Comportement attendu vs actuel
- Environnement (PHP, Laravel, MySQL versions)

Merci pour votre contribution ! 🙏
