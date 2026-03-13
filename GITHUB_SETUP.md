# 🚀 Instructions pour pousser vers GitHub

## Étape 1 : Créer un repository sur GitHub

1. Allez sur https://github.com
2. Cliquez sur le bouton "+" en haut à droite
3. Sélectionnez "New repository"
4. Remplissez les informations :
   - **Repository name** : `camwater-api`
   - **Description** : "API REST pour la gestion d'eau CamWater"
   - **Visibility** : Public ou Private (selon votre choix)
   - ⚠️ **NE PAS** cocher "Initialize with README" (on a déjà un README)
5. Cliquez sur "Create repository"

## Étape 2 : Lier votre projet local à GitHub

Copiez l'URL de votre repository (elle ressemble à : `https://github.com/votre-username/camwater-api.git`)

Puis exécutez ces commandes dans votre terminal :

```bash
# Ajouter le remote GitHub
git remote add origin https://github.com/votre-username/camwater-api.git

# Vérifier que le remote est bien ajouté
git remote -v

# Pousser votre code vers GitHub
git push -u origin master
```

## Étape 3 : Vérifier sur GitHub

Retournez sur GitHub et rafraîchissez la page. Vous devriez voir tous vos fichiers !

## 📌 Commandes Git utiles pour la suite

### Créer une nouvelle branche pour une fonctionnalité
```bash
git checkout -b feature/nom-de-la-fonctionnalite
```

### Voir l'état de vos modifications
```bash
git status
```

### Ajouter et commiter des changements
```bash
git add .
git commit -m "type(scope): description du changement"
```

### Pousser une branche vers GitHub
```bash
git push origin nom-de-la-branche
```

### Mettre à jour depuis GitHub
```bash
git pull origin master
```

## 🌿 Workflow recommandé (Git Flow)

1. **master/main** : Code en production
2. **develop** : Code en développement
3. **feature/xxx** : Nouvelles fonctionnalités
4. **fix/xxx** : Corrections de bugs
5. **hotfix/xxx** : Corrections urgentes en production

### Créer la branche develop
```bash
git checkout -b develop
git push -u origin develop
```

## 🔒 Protéger la branche master

Sur GitHub :
1. Allez dans Settings > Branches
2. Ajoutez une règle pour "master"
3. Cochez "Require pull request reviews before merging"
4. Cochez "Require status checks to pass before merging"

## ✅ Checklist finale

- [ ] Repository créé sur GitHub
- [ ] Remote ajouté localement
- [ ] Code poussé vers GitHub
- [ ] README.md visible sur GitHub
- [ ] .env n'est PAS visible (vérifié par .gitignore)
- [ ] Branche develop créée (optionnel)
- [ ] Protection de branche configurée (optionnel)

Votre projet est maintenant sur GitHub ! 🎉
