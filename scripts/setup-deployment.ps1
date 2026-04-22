# Script de configuration du deploiement
# Ce script t'aide a configurer tout ce dont tu as besoin

Write-Host "Configuration du deploiement CamWater API" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
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

# Fonction pour afficher un succes
function Write-Success {
    param([string]$Message)
    Write-Host "OK $Message" -ForegroundColor Green
}

# Fonction pour afficher une info
function Write-Info {
    param([string]$Message)
    Write-Host "INFO $Message" -ForegroundColor Cyan
}

# Fonction pour afficher un avertissement
function Write-Warning-Custom {
    param([string]$Message)
    Write-Host "ATTENTION $Message" -ForegroundColor Yellow
}

Write-Host "Ce script va t'aider a configurer le deploiement." -ForegroundColor White
Write-Host "Tu auras besoin de:" -ForegroundColor White
Write-Host "  - Tes acces O2Switch (nom d'hote, username)" -ForegroundColor Gray
Write-Host "  - Ton nom de domaine" -ForegroundColor Gray
Write-Host ""

$continue = Read-Host "Continuer ? (O/n)"
if ($continue -eq "n" -or $continue -eq "N") {
    Write-Host "Configuration annulee." -ForegroundColor Yellow
    exit
}

Write-Host ""
Write-Host "Collecte des informations" -ForegroundColor Cyan
Write-Host "=========================" -ForegroundColor Cyan
Write-Host ""

# Collecter les informations
$o2switchHost = Get-UserInput "Nom d'hote O2Switch (ex: votredomaine.com)"
$o2switchUser = Get-UserInput "Nom d'utilisateur SSH O2Switch"
$domain = Get-UserInput "Nom de domaine" $o2switchHost

$stagingSubdomain = Get-UserInput "Sous-domaine staging" "staging"
$prodSubdomain = Get-UserInput "Sous-domaine production" "api"

$stagingDomain = "$stagingSubdomain.$domain"
$prodDomain = "$prodSubdomain.$domain"

$stagingPath = "/home/$o2switchUser/$stagingDomain"
$prodPath = "/home/$o2switchUser/$prodDomain"

Write-Host ""
Write-Host "Recapitulatif" -ForegroundColor Cyan
Write-Host "=============" -ForegroundColor Cyan
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
    Write-Host "Configuration annulee. Relance le script pour recommencer." -ForegroundColor Yellow
    exit
}

# Etape 1: Generer la cle SSH
Write-Host ""
Write-Host "Etape 1: Generation de la cle SSH" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

$sshKeyPath = "$env:USERPROFILE\.ssh\o2switch_deploy"

if (Test-Path $sshKeyPath) {
    Write-Warning-Custom "Une cle SSH existe deja a $sshKeyPath"
    $overwrite = Read-Host "Voulez-vous la remplacer ? (o/N)"
    if ($overwrite -ne "o" -and $overwrite -ne "O") {
        Write-Info "Utilisation de la cle existante"
    } else {
        Remove-Item $sshKeyPath -Force
        Remove-Item "$sshKeyPath.pub" -Force -ErrorAction SilentlyContinue
        Write-Info "Ancienne cle supprimee"
    }
}

if (-not (Test-Path $sshKeyPath)) {
    Write-Info "Generation de la cle SSH..."
    
    # Creer le dossier .ssh s'il n'existe pas
    New-Item -ItemType Directory -Force -Path "$env:USERPROFILE\.ssh" | Out-Null
    
    # Generer la cle
    ssh-keygen -t ed25519 -C "github-deploy-camwater" -f $sshKeyPath -N '""' | Out-Null
    
    if (Test-Path $sshKeyPath) {
        Write-Success "Cle SSH generee avec succes"
    } else {
        Write-Host "ERREUR lors de la generation de la cle SSH" -ForegroundColor Red
        exit 1
    }
}

# Afficher la cle publique
Write-Host ""
Write-Info "Cle publique SSH (a ajouter sur O2Switch):"
Write-Host ""
Write-Host "----------------------------------------" -ForegroundColor Gray
Get-Content "$sshKeyPath.pub"
Write-Host "----------------------------------------" -ForegroundColor Gray
Write-Host ""

# Copier la cle publique dans le presse-papier
Get-Content "$sshKeyPath.pub" | Set-Clipboard
Write-Success "Cle publique copiee dans le presse-papier"

Write-Host ""
Write-Info "Maintenant, tu dois ajouter cette cle sur O2Switch:"
Write-Host "1. Connecte-toi en SSH: ssh $o2switchUser@$o2switchHost" -ForegroundColor Gray
Write-Host "2. Execute: mkdir -p ~/.ssh && chmod 700 ~/.ssh" -ForegroundColor Gray
Write-Host "3. Execute: nano ~/.ssh/authorized_keys" -ForegroundColor Gray
Write-Host "4. Colle la cle (Ctrl+Shift+V)" -ForegroundColor Gray
Write-Host "5. Sauvegarde (Ctrl+X, Y, Enter)" -ForegroundColor Gray
Write-Host "6. Execute: chmod 600 ~/.ssh/authorized_keys" -ForegroundColor Gray
Write-Host ""

$sshConfigured = Read-Host "As-tu ajoute la cle sur O2Switch ? (O/n)"
if ($sshConfigured -eq "n" -or $sshConfigured -eq "N") {
    Write-Warning-Custom "Configure la cle SSH avant de continuer"
    Write-Info "Relance ce script une fois la cle configuree"
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
        Write-Host "Resultat: $testResult" -ForegroundColor Gray
        $continueAnyway = Read-Host "Continuer quand meme ? (o/N)"
        if ($continueAnyway -ne "o" -and $continueAnyway -ne "O") {
            exit
        }
    }
} catch {
    Write-Warning-Custom "Impossible de tester la connexion SSH"
    Write-Host "Erreur: $_" -ForegroundColor Gray
}

# Etape 2: Creer un fichier de configuration
Write-Host ""
Write-Host "Etape 2: Creation du fichier de configuration" -ForegroundColor Cyan
Write-Host "==============================================" -ForegroundColor Cyan
Write-Host ""

$configContent = @"
# Configuration du deploiement CamWater API
# Genere le $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

## Informations O2Switch
O2SWITCH_HOST=$o2switchHost
O2SWITCH_USER=$o2switchUser

## URLs
STAGING_URL=https://$stagingDomain
PRODUCTION_URL=https://$prodDomain

## Chemins
STAGING_PATH=$stagingPath
PRODUCTION_PATH=$prodPath

## Cle SSH
SSH_KEY_PATH=$sshKeyPath

## Bases de donnees (a completer)
# Staging
DB_STAGING_NAME=${o2switchUser}_camwater_staging
DB_STAGING_USER=${o2switchUser}_staging
DB_STAGING_PASSWORD=

# Production
DB_PRODUCTION_NAME=${o2switchUser}_camwater_prod
DB_PRODUCTION_USER=${o2switchUser}_prod
DB_PRODUCTION_PASSWORD=
"@

$configFile = "deployment-config.txt"
$configContent | Out-File -FilePath $configFile -Encoding UTF8

Write-Success "Fichier de configuration cree: $configFile"

# Etape 3: Instructions pour GitHub
Write-Host ""
Write-Host "Etape 3: Configuration des secrets GitHub" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

Write-Info "Tu dois maintenant configurer les secrets GitHub:"
Write-Host ""
Write-Host "1. Va sur: https://github.com/Roynmbondi/camwater.api-cicd/settings/secrets/actions" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Ajoute ces Repository Secrets:" -ForegroundColor Gray
Write-Host ""
Write-Host "   O2SWITCH_SSH_KEY" -ForegroundColor Yellow
Write-Host "   Valeur: [Cle privee complete]" -ForegroundColor Gray
Write-Host ""
Write-Host "   O2SWITCH_HOST" -ForegroundColor Yellow
Write-Host "   Valeur: $o2switchHost" -ForegroundColor Gray
Write-Host ""
Write-Host "   O2SWITCH_USER" -ForegroundColor Yellow
Write-Host "   Valeur: $o2switchUser" -ForegroundColor Gray
Write-Host ""

Write-Host "3. Cree l'environment 'staging' avec ces secrets:" -ForegroundColor Gray
Write-Host ""
Write-Host "   STAGING_PATH" -ForegroundColor Yellow
Write-Host "   Valeur: $stagingPath" -ForegroundColor Gray
Write-Host ""
Write-Host "   STAGING_URL" -ForegroundColor Yellow
Write-Host "   Valeur: https://$stagingDomain" -ForegroundColor Gray
Write-Host ""

Write-Host "4. Cree l'environment 'production' avec ces secrets:" -ForegroundColor Gray
Write-Host ""
Write-Host "   PRODUCTION_PATH" -ForegroundColor Yellow
Write-Host "   Valeur: $prodPath" -ForegroundColor Gray
Write-Host ""
Write-Host "   PRODUCTION_URL" -ForegroundColor Yellow
Write-Host "   Valeur: https://$prodDomain" -ForegroundColor Gray
Write-Host ""

# Copier la cle privee dans le presse-papier
Write-Info "Copie de la cle privee dans le presse-papier..."
Get-Content $sshKeyPath | Set-Clipboard
Write-Success "Cle privee copiee ! Tu peux la coller dans GitHub Secrets"

Write-Host ""
Write-Host "Resume des prochaines etapes" -ForegroundColor Cyan
Write-Host "============================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. OK Cle SSH generee" -ForegroundColor Green
Write-Host "2. A FAIRE Configure les secrets GitHub (voir ci-dessus)" -ForegroundColor Yellow
Write-Host "3. A FAIRE Cree les sous-domaines dans cPanel O2Switch" -ForegroundColor Yellow
Write-Host "4. A FAIRE Cree les bases de donnees MySQL" -ForegroundColor Yellow
Write-Host "5. A FAIRE Active SSL pour les sous-domaines" -ForegroundColor Yellow
Write-Host ""

Write-Info "Consulte .github/CONFIGURATION-SECRETS.md pour les instructions detaillees"
Write-Host ""
Write-Success "Configuration terminee !"
Write-Host ""
Write-Host "Fichier de configuration sauvegarde: $configFile" -ForegroundColor Cyan
Write-Host ""
