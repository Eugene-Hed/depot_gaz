# 🏭 Dépôt GAZ - Système de Gestion de Stock

Système complet de gestion de stock pour dépôt de bouteilles de gaz. Application web moderne développée avec **Laravel 12** et **Bootstrap 5**.

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.46-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4+-purple?style=flat-square&logo=php)
![Database](https://img.shields.io/badge/MariaDB-11.8-blue?style=flat-square&logo=mariadb)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952b3?style=flat-square&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

</div>

## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Stack Technique](#-stack-technique)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Structure du Projet](#-structure-du-projet)
- [API Endpoints](#-api-endpoints)
- [Contribuer](#-contribuer)

## ✨ Fonctionnalités

### 📊 Dashboard Intégré
- 📈 KPI en temps réel (Revenu, Transactions, Stock, Clients)
- 📉 Graphiques de ventes (Derniers 7 jours)
- 🎯 Top 5 produits les plus vendus
- 📦 État du stock par type de bouteille
- 🔔 Système d'alertes pour stocks faibles
- 📝 Transactions récentes

### 💾 Gestion de Stock
- ✅ Suivi en temps réel des stocks (pleines/vides)
- 🔄 Mouvements de stock traçables
- ⚠️ Alertes de rupture de stock
- 📊 Statistiques par type et marque
- 🏷️ Gestion des types de bouteilles (tailles, prix)

### 📋 Gestion des Clients
- 👥 Base de données clients complète
- 📞 Informations de contact
- 🔒 Suivi des statuts (actif/inactif)
- 📊 Historique des transactions par client

### 💰 Gestion des Transactions
- 🛒  4 types de transactions (Achat Simple, Échange Simple, Échange Type, Échange Différé)
- 💳 Modes de paiement variés
- 📝 Détails complets (montant, consigne, réduction)
- 🔐 Traçabilité complète avec utilisateur & date

### 🏭 Gestion des Fournisseurs
- 🏢 Base de données fournisseurs
- 📝 Informations de contact
- 💼 Historique des commandes
- 📊 Statistiques des fournitures

### 📊 Système de Rapports et Exports
- 📥 Exports multi-formats:
  - 📊 **CSV** (Microsoft Excel compatible)
  - 📋 **XLSX** (Format natif Excel)
  - 📄 **PDF** (Rapports formatés)
- 📈 Rapports disponibles:
  - Clients
  - Transactions détaillées (avec anonymisation)
  - Stocks
  - Fournisseurs
  - Types de bouteilles
  - Marques

### 🔐 Authentification Sécurisée
- 🔑 Fortify authentication (Laravel)
- 👤 Gestion des rôles utilisateurs
- 🔒 Admin-only role simplifiée
- 📱 Interface de login moderne

## 🛠️ Stack Technique

### Backend
- **Framework**: Laravel 12.46.0
- **Language**: PHP 8.4.11
- **ORM**: Eloquent
- **Migrations**: Database schema versioning
- **Authentication**: Laravel Fortify

### Frontend
- **CSS Framework**: Bootstrap 5.3
- **Icons**: Bootstrap Icons
- **Charts**: Chart.js
- **Template Engine**: Blade

### Database
- **DBMS**: MariaDB 11.8.3
- **Tables**: 21 tables
- **Migrations**: Full versioning system

### Libraries
- **Excel Export**: maatwebsite/excel ^3.1
- **PDF Generation**: barryvdh/laravel-dompdf ^3.1

## 🚀 Installation

### Prérequis
- PHP 8.4+
- Composer
- MariaDB 11.8+
- Node.js & npm (optionnel, pour assets)

### Étapes

1. **Cloner le repository**
```bash
git clone https://github.com/Eugene-Hed/depot_gaz.git
cd depot_gaz
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**
Éditer `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=depot_gaz
DB_USERNAME=root
DB_PASSWORD=
```

5. **Exécuter les migrations**
```bash
php artisan migrate
```

6. **Seeder la base de données** (optionnel)
```bash
php artisan db:seed
```

7. **Démarrer l'application**
```bash
php artisan serve
```

L'application est accessible à `http://localhost:8000`

## ⚙️ Configuration

### Fichiers de Configuration
- `.env` - Variables d'environnement
- `config/app.php` - Configuration globale
- `config/database.php` - Connexion database
- `config/fortify.php` - Authentification

### Variables d'Environnement Importantes
```env
APP_NAME="Dépôt GAZ"
APP_ENV=local
APP_DEBUG=true
DB_DATABASE=depot_gaz
MAIL_MAILER=smtp
```

## 📖 Utilisation

### Authentification
1. Accéder à `http://localhost:8000/login`
2. Identifiants de test:
   - **Username**: `admin`
   - **Password**: `password`

### Dashboard
- Vue d'ensemble de l'activité
- Alertes en temps réel
- Statistiques KPI
- Graphiques de tendances

### Gestion du Stock
- Consulter les niveaux de stock
- Enregistrer les mouvements
- Voir les alertes de rupture

### Exports
- Naviguer vers **Rapports** → **Export**
- Choisir le type de rapport
- Sélectionner le format (CSV/XLSX/PDF)
- Télécharger

## 📁 Structure du Projet

```
depot_gaz/
├── app/
│   ├── Actions/          # Actions Fortify
│   ├── Http/
│   │   └── Controllers/  # Contrôleurs
│   ├── Models/           # Modèles Eloquent
│   └── Providers/        # Service providers
├── config/               # Configuration
├── database/
│   ├── migrations/       # Migrations DB
│   ├── seeders/          # Seeders
│   └── factories/        # Factories
├── resources/
│   ├── views/            # Templates Blade
│   │   ├── auth/         # Pages auth
│   │   ├── dashboard/    # Dashboard
│   │   └── rapports/     # Rapports
│   └── css/js/          # Assets
├── routes/
│   └── web.php          # Routes
├── storage/             # Fichiers générés
├── tests/               # Tests
├── public/              # Assets publics
└── README.md
```

## 🔌 API Endpoints

### Dashboard
- `GET /dashboard` - Page principale

### Stock
- `GET /stocks` - Liste des stocks
- `GET /stocks/{id}` - Détail du stock
- `POST /stocks` - Créer un stock
- `PUT /stocks/{id}` - Mettre à jour

### Transactions
- `GET /transactions` - Liste des transactions
- `POST /transactions` - Enregistrer une transaction

### Clients
- `GET /clients` - Liste des clients
- `POST /clients` - Créer un client

### Rapports & Exports
- `GET /rapports` - Page d'exports
- `GET /rapports/export?type=X&format=Y` - Export CSV/XLSX
- `GET /rapports/export-pdf?type=X` - Export PDF

## 🤝 Contribuer

Les contributions sont bienvenues! Pour contribuer:

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add amazing feature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 License

Ce projet est sous license MIT. Voir le fichier `LICENSE` pour plus de détails.

## ✉️ Contact

**Eugene-Hed** - [GitHub](https://github.com/Eugene-Hed)

---

<div align="center">

**Fait avec ❤️ pour la gestion efficace des dépôts de gaz**

</div>

