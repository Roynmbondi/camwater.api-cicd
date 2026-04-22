# Script de configuration du déploiement
# Ce script t'aide à configurer tout ce dont tu as besoin

Write-Host "🚀 Configuration du déploiement CamWater API" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""

# Fonction pour demander une information
function Get-UserInput {
    param(
        [string]$Prompt,
        [string]$Default = ""
    )
    
    if ($Default) {
        $input = Read-Host "$Prompt [$Default]"
        if ([string]::IsNullOrWhiteSpace($input)) {
            return $Default
        }
        return $input
    } else {
        return Read-Host $Prompt
    }
}

# Fonction pour afficher un succès
function Write-Success {
    param([string]$Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

# Fonction pour afficher une info
function Write-Info {
    param([string]$Message)
    Write-Host "ℹ️  $Message" -ForegroundColor Cyan
}

# Fonction pour afficher un avertissement
function Write-Warning-Custom {
    param([string]$Message)
    Write-Host "⚠️  $Message" -ForegroundColor Yellow
}

Write-Host "Ce script va t'aider à configurer le déploiement." -ForegroundColor White
Write-Host "Tu auras besoin de:" -ForegroundColor White
Write-Host "  - Tes accès O2Switch (nom d'hôte, username)" -ForegroundColor Gray
Write-Host "  - Ton nom de domaine" -ForegroundColor Gray
Write-Host ""

$continue = Read-Host "Continuer ? (O/n)"
if ($continue -eq "n" -or $continue -eq "N") {
    Write-Host "Configuration annulée." -ForegroundColor Yellow
    exit
}

Write-Host ""
Write-Host "📝 Collecte des informations" -ForegroundColor Cyan
Write-Host "=============================" -ForegroundColor Cyan
Write-Host ""

# Collecter les informations
$o2switchHost = Get-UserInput "Nom d'hôte O2Switch (ex: votredomaine.com)"
$o2switchUser = Get-UserInput "Nom d'utilisateur SSH O2Switch"
$domain = Get-UserInput "Nom de domaine" $o2switchHost

$stagingSubdomain = Get-UserInput "Sous-domaine staging" "staging"
$prodSubdomain = Get-UserInput "Sous-domaine production" "api"

$stagingDomain = "$stagingSubdomain.$domain"
$prodDomain = "$prodSubdomain.$domain"

$stagingPath = "/home/$o2switchUser/$stagingDomain"
$prodPath = "/home/$o2switchUser/$prodDomain"

Write-Host ""
Write-Host "📋 Récapitulatif" -ForegroundColor Cyan
Write-Host "================" -ForegroundColor Cyan
Write-Host ""
Write-Host "O2Switch Host:        $o2switchHost" -ForegroundColor White
Write-Host "O2Switch User:        $o2switchUser" -ForegroundColor White
Write-Host "Domaine:              $domain" -ForegroundColor White
Write-Host "Staging URL:          https://$stagingDomain" -ForegroundColor White
Write-Host "Production URL:       https://$prodDomain" -ForegroundColor White
Write-Host "Staging Path:         $stagingPath" -ForegroundColor White
Write-Host "Production Path:      $prodPath" -ForegroundColor White
Write-Host ""

$confirm = Read-Host "Ces informations sont-elles correctes ? (O/n)"
if ($confirm -eq "n" -or $confirm -eq "N") {
    Write-Host "Configuration annulée. Relance le script pour recommencer." -ForegroundColor Yellow
    exit
}

# Étape 1: Générer la clé SSH
Write-Host ""
Write-Host "🔑 Étape 1: Génération de la clé SSH" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

$sshKeyPath = "$env:USERPROFILE\.ssh\o2switch_deploy"

if (Test-Path $sshKeyPath) {
    Write-Warning-Custom "Une clé SSH existe déjà à $sshKeyPath"
    $overwrite = Read-Host "Voulez-vous la remplacer ? (o/N)"
    if ($overwrite -ne "o" -and $overwrite -ne "O") {
        Write-Info "Utilisation de la clé existante"
    } else {
        Remove-Item $sshKeyPath -Force
        Remove-Item "$sshKeyPath.pub" -Force -ErrorAction SilentlyContinue
        Write-Info "Ancienne clé supprimée"
    }
}

if (-not (Test-Path $sshKeyPath)) {
    Write-Info "Génération de la clé SSH..."
    
    # Créer le dossier .ssh s'il n'existe pas
    New-Item -ItemType Directory -Force -Path "$env:USERPROFILE\.ssh" | Out-Null
    
    # Générer la clé
    ssh-keygen -t ed25519 -C "github-deploy-camwater" -f $sshKeyPath -N '""' | Out-Null
    
    if (Test-Path $sshKeyPath) {
        Write-Success "Clé SSH générée avec succès"
    } else {
        Write-Host "❌ Erreur lors de la génération de la clé SSH" -ForegroundColor Red
        exit 1
    }
}

# Afficher la clé publique
Write-Host ""
Write-Info "Clé publique SSH (à ajouter sur O2Switch):"
Write-Host ""
Write-Host "----------------------------------------" -ForegroundColor Gray
Get-Content "$sshKeyPath.pub"
Write-Host "----------------------------------------" -ForegroundColor Gray
Write-Host ""

# Copier la clé publique dans le presse-papier
Get-Content "$sshKeyPath.pub" | Set-Clipboard
Write-Success "Clé publique copiée dans le presse-papier"

Write-Host ""
Write-Info "Maintenant, tu dois ajouter cette clé sur O2Switch:"
Write-Host "1. Connecte-toi en SSH: ssh $o2switchUser@$o2switchHost" -ForegroundColor Gray
Write-Host "2. Exécute: mkdir -p ~/.ssh && chmod 700 ~/.ssh" -ForegroundColor Gray
Write-Host "3. Exécute: nano ~/.ssh/authorized_keys" -ForegroundColor Gray
Write-Host "4. Colle la clé (Ctrl+Shift+V)" -ForegroundColor Gray
Write-Host "5. Sauvegarde (Ctrl+X, Y, Enter)" -ForegroundColor Gray
Write-Host "6. Exécute: chmod 600 ~/.ssh/authorized_keys" -ForegroundColor Gray
Write-Host ""

$sshConfigured = Read-Host "As-tu ajouté la clé sur O2Switch ? (O/n)"
if ($sshConfigured -eq "n" -or $sshConfigured -eq "N") {
    Write-Warning-Custom "Configure la clé SSH avant de continuer"
    Write-Info "Relance ce script une fois la clé configurée"
    exit
}

# Tester la connexion SSH
Write-Host ""
Write-Info "Test de la connexion SSH..."

try {
    $testResult = ssh -i $sshKeyPath -o StrictHostKeyChecking=no -o ConnectTimeout=10 "$o2switchUser@$o2switchHost" "echo 'SSH OK'" 2>&1
    
    if ($testResult -match "SSH OK") {
        Write-Success "Connexion SSH fonctionnelle !"
    } else {
        Write-Warning-Custom "La connexion SSH ne fonctionne pas correctement"
        Write-Host "Résultat: $testResult" -ForegroundColor Gray
        $continueAnyway = Read-Host "Continuer quand même ? (o/N)"
        if ($continueAnyway -ne "o" -and $continueAnyway -ne "O") {
            exit
        }
    }
} catch {
    Write-Warning-Custom "Impossible de tester la connexion SSH"
    Write-Host "Erreur: $_" -ForegroundColor Gray
}

# Étape 2: Créer un fichier de configuration
Write-Host ""
Write-Host "📝 Étape 2: Création du fichier de configuration" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

$configContent = @"
# Configuration du déploiement CamWater API
# Généré le $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

## Informations O2Switch
O2SWITCH_HOST=$o2switchHost
O2SWITCH_USER=$o2switchUser

## URLs
STAGING_URL=https://$stagingDomain
PRODUCTION_URL=https://$prodDomain

## Chemins
STAGING_PATH=$stagingPath
PRODUCTION_PATH=$prodPath

## Clé SSH
SSH_KEY_PATH=$sshKeyPath

## Bases de données (à compléter)
# Staging
DB_STAGING_NAME=${o2switchUser}_camwater_staging
DB_STAGING_USER=${o2switchUser}_staging
DB_STAGING_PASSWORD=

# Production
DB_PRODUCTION_NAME=${o2switchUser}_camwater_prod
DB_PRODUCTION_USER=${o2switchUser}_prod
DB_PRODUCTION_PASSWORD=
"@

$configFile = ".deployment-config"
$configContent | Out-File -FilePath $configFile -Encoding UTF8

Write-Success "Fichier de configuration créé: $configFile"

# Étape 3: Instructions pour GitHub
Write-Host ""
Write-Host "🔐 Étape 3: Configuration des secrets GitHub" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""

Write-Info "Tu dois maintenant configurer les secrets GitHub:"
Write-Host ""
Write-Host "1. Va sur: https://github.com/TON-USERNAME/camwater-api/settings/secrets/actions" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Ajoute ces Repository Secrets:" -ForegroundColor Gray
Write-Host ""
Write-Host "   O2SWITCH_SSH_KEY" -ForegroundColor Yellow
Write-Host "   Valeur: [Clé privée complète]" -ForegroundColor Gray
Write-Host ""
Write-Host "   O2SWITCH_HOST" -ForegroundColor Yellow
Write-Host "   Valeur: $o2switchHost" -ForegroundColor Gray
Write-Host ""
Write-Host "   O2SWITCH_USER" -ForegroundColor Yellow
Write-Host "   Valeur: $o2switchUser" -ForegroundColor Gray
Write-Host ""

Write-Host "3. Crée l'environment 'staging' avec ces secrets:" -ForegroundColor Gray
Write-Host ""
Write-Host "   STAGING_PATH" -ForegroundColor Yellow
Write-Host "   Valeur: $stagingPath" -ForegroundColor Gray
Write-Host ""
Write-Host "   STAGING_URL" -ForegroundColor Yellow
Write-Host "   Valeur: https://$stagingDomain" -ForegroundColor Gray
Write-Host ""

Write-Host "4. Crée l'environment 'production' avec ces secrets:" -ForegroundColor Gray
Write-Host ""
Write-Host "   PRODUCTION_PATH" -ForegroundColor Yellow
Write-Host "   Valeur: $prodPath" -ForegroundColor Gray
Write-Host ""
Write-Host "   PRODUCTION_URL" -ForegroundColor Yellow
Write-Host "   Valeur: https://$prodDomain" -ForegroundColor Gray
Write-Host ""

# Copier la clé privée dans le presse-papier
Write-Info "Copie de la clé privée dans le presse-papier..."
Get-Content $sshKeyPath | Set-Clipboard
Write-Success "Clé privée copiée ! Tu peux la coller dans GitHub Secrets"

Write-Host ""
Write-Host "📋 Résumé des prochaines étapes" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. ✅ Clé SSH générée" -ForegroundColor Green
Write-Host "2. ⏳ Configure les secrets GitHub (voir ci-dessus)" -ForegroundColor Yellow
Write-Host "3. ⏳ Crée les sous-domaines dans cPanel O2Switch" -ForegroundColor Yellow
Write-Host "4. ⏳ Crée les bases de données MySQL" -ForegroundColor Yellow
Write-Host "5. ⏳ Active SSL pour les sous-domaines" -ForegroundColor Yellow
Write-Host ""

Write-Info "Consulte .github/CONFIGURATION-SECRETS.md pour les instructions détaillées"
Write-Host ""
Write-Success "Configuration terminée !"
Write-Host ""
