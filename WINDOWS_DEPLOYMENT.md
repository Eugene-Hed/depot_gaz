# Déploiement Local sur Windows - Dépôt GAZ

## Prérequis

- **PHP 8.4+** avec extensions: mbstring, json, pdo_mysql, openssl
- **Composer** (dernière version)
- **MariaDB/MySQL 11.8+**
- **Node.js & npm** (optionnel, pour assets)
- **Git** (optionnel)

## Installation Complète

### 1. Préparer l'environnement

```powershell
# Créer un dossier pour le projet
New-Item -ItemType Directory -Path "C:\Depot-GAZ" -Force
cd C:\Depot-GAZ

# Télécharger/copier les fichiers du projet
# Ou cloner si vous avez Git
git clone https://github.com/Eugene-Hed/depot_gaz.git .
```

### 2. Installer les dépendances PHP

```powershell
cd C:\Depot-GAZ
composer install
```

### 3. Configurer l'environnement

```powershell
# Copier le fichier .env
Copy-Item ".env.example" ".env"

# Générer la clé application
php artisan key:generate
```

Éditer `.env`:
```env
APP_NAME="Dépôt GAZ"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=depot_gaz
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Créer la base de données

```powershell
# Via MariaDB MySQL client
mysql -u root -p -e "CREATE DATABASE depot_gaz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Ou via phpMyAdmin (interface web de MariaDB)
```

### 5. Exécuter les migrations

```powershell
php artisan migrate
php artisan db:seed
```

### 6. Configurer le storage (uploads d'images)

```powershell
php artisan storage:link
```

### 7. Démarrer l'application

```powershell
php artisan serve
```

L'application est accessible à: **http://localhost:8000**

## Système de Période de Test

La plateforme inclut un système de limiteur de durée pour les tests.

### Configurer la durée de test

Éditer `.env`:
```env
# Durée en jours (défaut: 30)
TEST_PERIOD_DAYS=30

# Date de début (optionnel, sinon utilise la date du premier accès)
TEST_START_DATE=2026-01-27
```

### Fonctionnement

- L'application affiche un **compteur de jours restants** sur le dashboard
- Passé la date limite, l'application affiche une **page d'expiration**
- Les utilisateurs voient: "Période de test expirée. Contactez l'administrateur."

### Réinitialiser la période

```powershell
# Vider le cache de la date de démarrage
php artisan cache:clear

# Ou supprimer le fichier de stockage
Remove-Item "storage/app/test_start_date.txt" -ErrorAction SilentlyContinue
```

## Identifiants de test

**Username**: `admin`  
**Password**: `password`

## Commandes Utiles

```powershell
# Vider le cache
php artisan cache:clear

# Réinitialiser la base de données
php artisan migrate:refresh

# Voir les routes disponibles
php artisan route:list

# Vérifier la configuration
php artisan about
```

## Dépannage

### Erreur de connexion à la base de données
- Vérifier que MariaDB est lancé
- Vérifier les identifiants dans `.env`
- Vérifier que la base `depot_gaz` existe

### Erreur de permission sur le storage
```powershell
# Accorder les permissions
icacls "storage" /grant "*S-1-1-0:(OI)(CI)F" /T
icacls "bootstrap/cache" /grant "*S-1-1-0:(OI)(CI)F" /T
```

### Images ne s'affichent pas
```powershell
# Refaire le lien symbolique
php artisan storage:link --force
```

### Port 8000 déjà utilisé
```powershell
# Utiliser un autre port
php artisan serve --port=8001
```

## Support

Pour toute question, vérifiez les logs:
```powershell
Get-Content "storage/logs/laravel.log" -Tail 50
```

---

Plateforme prête pour les tests locaux sur Windows !
