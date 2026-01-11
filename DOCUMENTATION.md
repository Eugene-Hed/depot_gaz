# 📚 Documentation - Dépôt de Gaz

## Vue d'ensemble

Application **Laravel 11** complète pour la gestion d'un dépôt de gaz avec :
- Gestion du stock (entrées/sorties/ajustements)
- Transactions commerciales (ventes, recharges, échanges)
- Base de clients avec fidélité
- Dashboard avec statistiques
- Authentification multi-rôles

---

## 🔐 Authentification

### Endpoints de connexion

```
POST /login
GET /login
POST /logout
```

### Comptes de test

```
admin / password       (Rôle: Admin)
manager / password     (Rôle: Manager)
vendeur / password     (Rôle: Vendeur)
```

### Middleware

Toutes les routes sauf login/logout nécessitent `auth`:
```php
Route::middleware('auth')->group(function () { ... });
```

---

## 📊 Modules principaux

### 1️⃣ Dashboard
**Route:** `GET /dashboard`

Affiche :
- Total stocks (pleines + vides)
- Articles en rupture
- Transactions du jour
- Clients actifs
- Alertes stock

---

### 2️⃣ Gestion des Stocks

#### Lister les stocks
```
GET /stocks
```
**Response:** Pagination 20 items
- Type bouteille
- Marque
- Quantités pleines/vides
- Statut (OK/Rupture)

#### Créer un mouvement
```
GET /stocks/create          → Formulaire
POST /stocks                → Enregistrer
```

**Paramètres POST:**
```json
{
  "id_type_bouteille": 1,
  "type_mouvement": "entree|sortie|ajustement",
  "quantite_pleine": 10,
  "quantite_vide": 5,
  "motif": "vente|casse|...",
  "commentaire": "..."
}
```

#### Ajuster un stock
```
GET /stocks/{stock}/edit    → Formulaire
PUT /stocks/{stock}         → Enregistrer
```

---

### 3️⃣ Transactions (Ventes)

#### Lister les transactions
```
GET /transactions
```

**Colonnes affichées:**
- Date/Heure
- Type (vente, recharge, échange, etc.)
- Bouteille
- Quantité
- Client
- Montant
- Mode paiement

#### Créer une transaction
```
GET /transactions/create    → Formulaire
POST /transactions          → Enregistrer
```

**Paramètres POST:**
```json
{
  "type": "vente|recharge|echange|consigne|retour",
  "id_client": null,
  "id_type_bouteille": 1,
  "quantite": 5,
  "prix_unitaire": 13520.00,
  "montant_total": 67600.00,
  "mode_paiement": "especes|cheque|carte|virement",
  "commentaire": "..."
}
```

**Automatismes:**
- Mise à jour du stock (baisse de quantite_pleine)
- Augmentation quantite_vide pour ventes
- Ajout de points fidélité au client (1pt/100 FCFA)
- Vérification stock disponible

#### Voir détail transaction
```
GET /transactions/{transaction}
```

---

### 4️⃣ Gestion des Clients

#### Lister les clients
```
GET /clients
```

**Infos affichées:**
- Nom
- Téléphone
- Email
- Points fidélité
- Statut (actif/inactif)
- Date inscription

#### Ajouter un client
```
GET /clients/create         → Formulaire
POST /clients               → Enregistrer
```

**Paramètres POST:**
```json
{
  "nom": "Jean Dupont",
  "telephone": "+33612345678",
  "email": "jean@example.com",
  "adresse": "123 rue Test"
}
```

#### Modifier un client
```
GET /clients/{client}/edit  → Formulaire
PUT /clients/{client}       → Enregistrer
```

---

## 🗄️ Modèles de données

### Users
```sql
- id (bigint)
- username (string) UNIQUE
- nom_complet (string)
- email (string) UNIQUE
- password (string)
- role (enum: admin, manager, vendeur)
- statut (enum: actif, inactif)
- dernier_login (timestamp)
- timestamps
```

### TypeBouteilles
```sql
- id
- nom (string)
- taille (string)
- marque_id (FK)
- prix_vente (decimal)
- prix_consigne (decimal)
- prix_recharge (decimal)
- seuil_alerte (int)
- timestamps
```

### Stock
```sql
- id
- type_bouteille_id (FK) UNIQUE
- quantite_pleine (int)
- quantite_vide (int)
- timestamps
```

### Mouvements Stock
```sql
- id
- stock_id (FK)
- type_mouvement (enum: entree, sortie, ajustement)
- quantite_pleine (int)
- quantite_vide (int)
- commentaire (text)
- motif (string)
- administrateur_id (FK)
- timestamps
```

### Transactions
```sql
- id
- type (enum: vente, echange, consigne, retour, recharge)
- client_id (FK nullable)
- type_bouteille_id (FK)
- quantite (int)
- prix_unitaire (decimal)
- montant_total (decimal)
- mode_paiement (string)
- administrateur_id (FK)
- commentaire (text)
- timestamps
```

### Clients
```sql
- id
- nom (string)
- telephone (string) UNIQUE
- email (string) nullable UNIQUE
- adresse (text)
- points_fidelite (int default 0)
- statut (enum: actif, inactif)
- timestamps
```

---

## 🔧 Commandes utiles

```bash
# Afficher toutes les routes
php artisan route:list

# Exécuter les migrations
php artisan migrate:fresh --seed

# Exécuter les tests
php artisan test

# Générer des données de test
php artisan tinker
> App\Models\Client::factory(50)->create();

# Voir les logs
tail -f storage/logs/laravel.log

# Cache
php artisan cache:clear
php artisan config:clear
```

---

## ✨ Features à venir

- [ ] API REST endpoints
- [ ] Export PDF factures
- [ ] Export Excel rapports
- [ ] Email notifications
- [ ] SMS alerts
- [ ] Mobile app
- [ ] Dashboard charts
- [ ] Multi-langues
- [ ] Tests unitaires complets
- [ ] Caching Redis

---

## 🐛 Troubleshooting

### Erreur: "Unknown column 'seuil_alerte'"
Les seuils sont dans `types_bouteilles`, pas dans `stocks`.

### Erreur: "CSRF token mismatch"
Incluez le token dans les formulaires:
```blade
{{ csrf_field() }}
```

### Session expire rapidement
Modifier `SESSION_LIFETIME` dans `.env` (en minutes)

---

## 📞 Support

Vérifier les logs:
```bash
tail -f storage/logs/laravel.log
```

Plus d'infos: https://laravel.com/docs

---

**Version:** 1.0.0  
**Date:** 10 janvier 2026
