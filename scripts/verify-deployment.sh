#!/bin/bash

# Script de vérification du déploiement
# Usage: ./scripts/verify-deployment.sh [staging|production]

set -e

ENVIRONMENT=${1:-staging}
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "🔍 Vérification du déploiement - Environnement: $ENVIRONMENT"
echo "============================================================"

# Couleurs pour l'affichage
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction pour afficher un succès
success() {
    echo -e "${GREEN}✅ $1${NC}"
}

# Fonction pour afficher une erreur
error() {
    echo -e "${RED}❌ $1${NC}"
}

# Fonction pour afficher un avertissement
warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# Déterminer l'URL en fonction de l'environnement
if [ "$ENVIRONMENT" = "production" ]; then
    API_URL="${PRODUCTION_URL:-https://api.votredomaine.com}"
elif [ "$ENVIRONMENT" = "staging" ]; then
    API_URL="${STAGING_URL:-https://staging.votredomaine.com}"
else
    error "Environnement invalide. Utilisez 'staging' ou 'production'"
    exit 1
fi

echo ""
echo "🌐 URL de l'API: $API_URL"
echo ""

# 1. Test de connectivité
echo "1️⃣  Test de connectivité..."
if curl -s --head --request GET "$API_URL" | grep "200\|301\|302" > /dev/null; then
    success "Le serveur répond"
else
    error "Le serveur ne répond pas"
    exit 1
fi

# 2. Test du health check
echo ""
echo "2️⃣  Test du health check..."
HEALTH_RESPONSE=$(curl -s "$API_URL/api/health")

if echo "$HEALTH_RESPONSE" | grep -q "ok"; then
    success "Health check OK"
    echo "   Response: $HEALTH_RESPONSE"
else
    error "Health check échoué"
    echo "   Response: $HEALTH_RESPONSE"
    exit 1
fi

# 3. Test du temps de réponse
echo ""
echo "3️⃣  Test du temps de réponse..."
RESPONSE_TIME=$(curl -o /dev/null -s -w '%{time_total}\n' "$API_URL/api/health")
RESPONSE_TIME_MS=$(echo "$RESPONSE_TIME * 1000" | bc)

if (( $(echo "$RESPONSE_TIME < 2.0" | bc -l) )); then
    success "Temps de réponse acceptable: ${RESPONSE_TIME_MS}ms"
else
    warning "Temps de réponse lent: ${RESPONSE_TIME_MS}ms"
fi

# 4. Test du certificat SSL
echo ""
echo "4️⃣  Test du certificat SSL..."
if echo | openssl s_client -servername $(echo $API_URL | sed 's|https://||') \
    -connect $(echo $API_URL | sed 's|https://||'):443 2>/dev/null | \
    openssl x509 -noout -dates > /dev/null 2>&1; then
    success "Certificat SSL valide"
    
    # Afficher la date d'expiration
    EXPIRY=$(echo | openssl s_client -servername $(echo $API_URL | sed 's|https://||') \
        -connect $(echo $API_URL | sed 's|https://||'):443 2>/dev/null | \
        openssl x509 -noout -enddate | cut -d= -f2)
    echo "   Expire le: $EXPIRY"
else
    warning "Impossible de vérifier le certificat SSL"
fi

# 5. Test des headers de sécurité
echo ""
echo "5️⃣  Test des headers de sécurité..."
HEADERS=$(curl -s -I "$API_URL/api/health")

if echo "$HEADERS" | grep -qi "X-Frame-Options"; then
    success "X-Frame-Options présent"
else
    warning "X-Frame-Options manquant"
fi

if echo "$HEADERS" | grep -qi "X-Content-Type-Options"; then
    success "X-Content-Type-Options présent"
else
    warning "X-Content-Type-Options manquant"
fi

# 6. Test de la documentation API
echo ""
echo "6️⃣  Test de la documentation API..."
if curl -s --head "$API_URL/api/documentation" | grep "200" > /dev/null; then
    success "Documentation API accessible"
else
    warning "Documentation API non accessible"
fi

# 7. Test des endpoints principaux
echo ""
echo "7️⃣  Test des endpoints principaux..."

# Test endpoint auth (doit retourner 405 ou 401)
AUTH_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$API_URL/api/auth/login")
if [ "$AUTH_STATUS" = "405" ] || [ "$AUTH_STATUS" = "401" ] || [ "$AUTH_STATUS" = "422" ]; then
    success "Endpoint /api/auth/login accessible"
else
    warning "Endpoint /api/auth/login retourne: $AUTH_STATUS"
fi

# Résumé
echo ""
echo "============================================================"
echo "📊 Résumé de la vérification"
echo "============================================================"
echo "Environnement: $ENVIRONMENT"
echo "URL: $API_URL"
echo "Temps de réponse: ${RESPONSE_TIME_MS}ms"
echo ""
success "Vérification terminée avec succès !"
echo ""
echo "💡 Prochaines étapes:"
echo "   - Testez les endpoints avec Postman"
echo "   - Vérifiez les logs: tail -f storage/logs/laravel.log"
echo "   - Surveillez les métriques dans GitHub Actions"
echo ""
