#!/bin/bash

# Script de vérification pré-déploiement pour Dépôt GAZ
# Usage: bash health-check.sh

echo "╔═════════════════════════════════════════════════════════╗"
echo "║  Dépôt GAZ - Vérification de Santé (Health Check)       ║"
echo "╚═════════════════════════════════════════════════════════╝"
echo ""

# Counters
CHECKS_PASSED=0
CHECKS_FAILED=0

# Function to check a requirement
check_requirement() {
    local name=$1
    local command=$2
    
    if command -v $command &> /dev/null; then
        echo "✅ $name"
        ((CHECKS_PASSED++))
    else
        echo "❌ $name"
        ((CHECKS_FAILED++))
    fi
}

# Function to check file existence
check_file() {
    local name=$1
    local path=$2
    
    if [ -f "$path" ]; then
        echo "✅ $name"
        ((CHECKS_PASSED++))
    else
        echo "❌ $name (pas trouvé: $path)"
        ((CHECKS_FAILED++))
    fi
}

# Function to check directory existence
check_directory() {
    local name=$1
    local path=$2
    
    if [ -d "$path" ]; then
        echo "✅ $name"
        ((CHECKS_PASSED++))
    else
        echo "⚠️  $name (pas trouvé: $path)"
        ((CHECKS_FAILED++))
    fi
}

echo "═══ PRÉREQUIS SYSTÈME ═══"
check_requirement "PHP 8.4+" "php"
check_requirement "Composer" "composer"
check_requirement "MariaDB/MySQL" "mysql"
check_requirement "Git" "git"

echo ""
echo "═══ FICHIERS DE CONFIGURATION ═══"
check_file "Fichier .env" ".env"
check_file "Fichier package.json" "package.json"
check_file "Fichier composer.json" "composer.json"
check_file ".env.example" ".env.example"

echo ""
echo "═══ RÉPERTOIRES PRINCIPAUX ═══"
check_directory "Dossier app/" "app"
check_directory "Dossier routes/" "routes"
check_directory "Dossier resources/" "resources"
check_directory "Dossier storage/" "storage"
check_directory "Dossier database/" "database"
check_directory "Dossier vendor/" "vendor"
check_directory "Dossier public/" "public"

echo ""
echo "═══ FICHIERS DE MIGRATION ═══"
check_directory "Dossier migrations/" "database/migrations"
check_file "Migration users" "database/migrations/0001_01_01_000000_create_users_table.php"
check_file "Migration cache" "database/migrations/0001_01_01_000001_create_cache_table.php"

echo ""
echo "═══ FICHIERS DE STOCKAGE ═══"
check_directory "Storage app/" "storage/app"
check_directory "Storage framework/" "storage/framework"
check_directory "Storage logs/" "storage/logs"
check_directory "Storage public/" "storage/app/public"

echo ""
echo "═══ DOSSIERS DE FEATURE ═══"
check_directory "Types de Bouteilles" "storage/app/public/bouteilles"
check_directory "Marques" "storage/app/public/marques"

echo ""
echo "═══ FICHIERS DE DOCUMENTATION ═══"
check_file "README.md" "README.md"
check_file "Deployment Guide" "DEPLOYMENT_GUIDE.md"
check_file "Quick Start" "QUICK_START.md"
check_file "User Guide" "USER_GUIDE.md"

echo ""
echo "═══ FICHIERS DE LANCEMENT ═══"
check_file "Script START.bat" "START.bat"
check_file "Script START.ps1" "START.ps1"

echo ""
echo "═══ RÉSULTATS ═══"
echo "✅ Vérifications réussies: $CHECKS_PASSED"
echo "❌ Vérifications échouées: $CHECKS_FAILED"

if [ $CHECKS_FAILED -eq 0 ]; then
    echo ""
    echo "✅ TOUT EST PRÊT!"
    echo ""
    echo "Commandes suivantes:"
    echo "  1. composer install"
    echo "  2. php artisan migrate"
    echo "  3. php artisan serve"
    exit 0
else
    echo ""
    echo "⚠️  CERTAINES VÉRIFICATIONS ONT ÉCHOUÉ"
    echo ""
    echo "À faire:"
    echo "  1. Installer les dépendances manquantes"
    echo "  2. Créer les dossiers manquants"
    echo "  3. Vérifier les fichiers de configuration"
    exit 1
fi
