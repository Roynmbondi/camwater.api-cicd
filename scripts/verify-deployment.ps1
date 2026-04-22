# Script de vérification du déploiement (PowerShell)
# Usage: .\scripts\verify-deployment.ps1 [staging|production]

param(
    [Parameter(Mandatory=$false)]
    [ValidateSet("staging", "production")]
    [string]$Environment = "staging"
)

Write-Host "🔍 Vérification du déploiement - Environnement: $Environment" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

# Déterminer l'URL en fonction de l'environnement
if ($Environment -eq "production") {
    $ApiUrl = if ($env:PRODUCTION_URL) { $env:PRODUCTION_URL } else { "https://api.votredomaine.com" }
} else {
    $ApiUrl = if ($env:STAGING_URL) { $env:STAGING_URL } else { "https://staging.votredomaine.com" }
}

Write-Host "🌐 URL de l'API: $ApiUrl" -ForegroundColor White
Write-Host ""

# Fonction pour afficher un succès
function Write-Success {
    param([string]$Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

# Fonction pour afficher une erreur
function Write-Error-Custom {
    param([string]$Message)
    Write-Host "❌ $Message" -ForegroundColor Red
}

# Fonction pour afficher un avertissement
function Write-Warning-Custom {
    param([string]$Message)
    Write-Host "⚠️  $Message" -ForegroundColor Yellow
}

# 1. Test de connectivité
Write-Host "1️⃣  Test de connectivité..." -ForegroundColor Cyan
try {
    $response = Invoke-WebRequest -Uri $ApiUrl -Method Head -UseBasicParsing -TimeoutSec 10
    if ($response.StatusCode -in @(200, 301, 302)) {
        Write-Success "Le serveur répond (HTTP $($response.StatusCode))"
    } else {
        Write-Error-Custom "Le serveur répond avec un code inattendu: $($response.StatusCode)"
    }
} catch {
    Write-Error-Custom "Le serveur ne répond pas: $($_.Exception.Message)"
    exit 1
}

# 2. Test du health check
Write-Host ""
Write-Host "2️⃣  Test du health check..." -ForegroundColor Cyan
try {
    $healthResponse = Invoke-RestMethod -Uri "$ApiUrl/api/health" -Method Get -UseBasicParsing
    if ($healthResponse.status -eq "ok") {
        Write-Success "Health check OK"
        Write-Host "   Response: $($healthResponse | ConvertTo-Json -Compress)" -ForegroundColor Gray
    } else {
        Write-Error-Custom "Health check échoué"
        Write-Host "   Response: $($healthResponse | ConvertTo-Json -Compress)" -ForegroundColor Gray
    }
} catch {
    Write-Error-Custom "Health check échoué: $($_.Exception.Message)"
    exit 1
}

# 3. Test du temps de réponse
Write-Host ""
Write-Host "3️⃣  Test du temps de réponse..." -ForegroundColor Cyan
$times = @()
for ($i = 1; $i -le 3; $i++) {
    $stopwatch = [System.Diagnostics.Stopwatch]::StartNew()
    try {
        Invoke-RestMethod -Uri "$ApiUrl/api/health" -Method Get -UseBasicParsing | Out-Null
        $stopwatch.Stop()
        $times += $stopwatch.Elapsed.TotalSeconds
    } catch {
        Write-Warning-Custom "Tentative $i échouée"
    }
}

if ($times.Count -gt 0) {
    $avgTime = ($times | Measure-Object -Average).Average
    $avgTimeMs = [math]::Round($avgTime * 1000, 2)
    
    if ($avgTime -lt 2.0) {
        Write-Success "Temps de réponse acceptable: ${avgTimeMs}ms"
    } else {
        Write-Warning-Custom "Temps de réponse lent: ${avgTimeMs}ms"
    }
} else {
    Write-Warning-Custom "Impossible de mesurer le temps de réponse"
}

# 4. Test du certificat SSL
Write-Host ""
Write-Host "4️⃣  Test du certificat SSL..." -ForegroundColor Cyan
try {
    $uri = [System.Uri]$ApiUrl
    $request = [System.Net.HttpWebRequest]::Create($uri)
    $request.Timeout = 10000
    $request.AllowAutoRedirect = $false
    $response = $request.GetResponse()
    
    if ($request.ServicePoint.Certificate) {
        $cert = [System.Security.Cryptography.X509Certificates.X509Certificate2]$request.ServicePoint.Certificate
        Write-Success "Certificat SSL valide"
        Write-Host "   Expire le: $($cert.NotAfter.ToString('yyyy-MM-dd HH:mm:ss'))" -ForegroundColor Gray
        
        $daysUntilExpiry = ($cert.NotAfter - (Get-Date)).Days
        if ($daysUntilExpiry -lt 30) {
            Write-Warning-Custom "Le certificat expire dans $daysUntilExpiry jours"
        }
    } else {
        Write-Warning-Custom "Impossible de vérifier le certificat SSL"
    }
    
    $response.Close()
} catch {
    Write-Warning-Custom "Impossible de vérifier le certificat SSL: $($_.Exception.Message)"
}

# 5. Test des headers de sécurité
Write-Host ""
Write-Host "5️⃣  Test des headers de sécurité..." -ForegroundColor Cyan
try {
    $response = Invoke-WebRequest -Uri "$ApiUrl/api/health" -Method Get -UseBasicParsing
    
    if ($response.Headers["X-Frame-Options"]) {
        Write-Success "X-Frame-Options présent: $($response.Headers['X-Frame-Options'])"
    } else {
        Write-Warning-Custom "X-Frame-Options manquant"
    }
    
    if ($response.Headers["X-Content-Type-Options"]) {
        Write-Success "X-Content-Type-Options présent: $($response.Headers['X-Content-Type-Options'])"
    } else {
        Write-Warning-Custom "X-Content-Type-Options manquant"
    }
} catch {
    Write-Warning-Custom "Impossible de vérifier les headers de sécurité"
}

# 6. Test de la documentation API
Write-Host ""
Write-Host "6️⃣  Test de la documentation API..." -ForegroundColor Cyan
try {
    $docResponse = Invoke-WebRequest -Uri "$ApiUrl/api/documentation" -Method Head -UseBasicParsing -TimeoutSec 10
    if ($docResponse.StatusCode -eq 200) {
        Write-Success "Documentation API accessible"
    } else {
        Write-Warning-Custom "Documentation API non accessible (HTTP $($docResponse.StatusCode))"
    }
} catch {
    Write-Warning-Custom "Documentation API non accessible"
}

# 7. Test des endpoints principaux
Write-Host ""
Write-Host "7️⃣  Test des endpoints principaux..." -ForegroundColor Cyan
try {
    $authResponse = Invoke-WebRequest -Uri "$ApiUrl/api/auth/login" -Method Get -UseBasicParsing -ErrorAction SilentlyContinue
    $statusCode = $authResponse.StatusCode
} catch {
    $statusCode = $_.Exception.Response.StatusCode.value__
}

if ($statusCode -in @(405, 401, 422)) {
    Write-Success "Endpoint /api/auth/login accessible (HTTP $statusCode)"
} else {
    Write-Warning-Custom "Endpoint /api/auth/login retourne: HTTP $statusCode"
}

# Résumé
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "📊 Résumé de la vérification" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "Environnement: $Environment" -ForegroundColor White
Write-Host "URL: $ApiUrl" -ForegroundColor White
if ($times.Count -gt 0) {
    Write-Host "Temps de réponse: ${avgTimeMs}ms" -ForegroundColor White
}
Write-Host ""
Write-Success "Vérification terminée avec succès !"
Write-Host ""
Write-Host "💡 Prochaines étapes:" -ForegroundColor Yellow
Write-Host "   - Testez les endpoints avec Postman" -ForegroundColor Gray
Write-Host "   - Vérifiez les logs sur le serveur" -ForegroundColor Gray
Write-Host "   - Surveillez les métriques dans GitHub Actions" -ForegroundColor Gray
Write-Host ""
