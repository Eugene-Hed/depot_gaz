-- phpMyAdmin SQL Dump
-- version 5.2.3deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 11 jan. 2026 à 10:47
-- Version du serveur : 11.8.3-MariaDB-1+b1 from Debian
-- Version de PHP : 8.4.11
-- VERSION AMÉLIORÉE AVEC CORRECTIONS CRITIQUES

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `depot_gaz`
--

-- ========================================
-- TABLES FONDAMENTALES
-- ========================================

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL UNIQUE,
  `nom_complet` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `role` enum('admin','manager','vendeur') NOT NULL DEFAULT 'vendeur',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `dernier_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `marques`
--

CREATE TABLE `marques` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL UNIQUE,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `types_bouteilles` (AMÉLIORÉE)
--

CREATE TABLE `types_bouteilles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `taille` varchar(50) NOT NULL,
  `marque_id` bigint(20) UNSIGNED NOT NULL,
  
  -- Tarifs
  `prix_vente` decimal(10,2) NOT NULL,
  `prix_consigne` decimal(10,2) NOT NULL,
  `prix_recharge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prix_bouteille_vide` decimal(10,2) DEFAULT 0.00 COMMENT 'Prix de rachat bouteille vide',
  `prix_retour_vide` decimal(10,2) DEFAULT 0.00 COMMENT 'Remboursement au retour',
  
  -- Gestion stock
  `seuil_alerte` int(11) NOT NULL DEFAULT 5,
  
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `types_bouteilles_nom_marque_id_unique` (`nom`, `marque_id`),
  FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`) ON DELETE CASCADE,
  KEY `types_bouteilles_marque_id_foreign` (`marque_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL UNIQUE,
  `quantite_pleine` int(11) NOT NULL DEFAULT 0,
  `quantite_vide` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stocks_type_bouteille_id_unique` (`type_bouteille_id`),
  FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `mouvements_stocks`
--

CREATE TABLE `mouvements_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `type_mouvement` enum('entree','sortie','ajustement','casse') NOT NULL,
  `quantite_pleine` int(11) NOT NULL,
  `quantite_vide` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `motif` varchar(50) DEFAULT NULL,
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mouvements_stocks_stock_id_index` (`stock_id`),
  KEY `mouvements_stocks_administrateur_id_index` (`administrateur_id`),
  KEY `mouvements_stocks_created_at_index` (`created_at`),
  FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT NULL UNIQUE,
  `adresse` text DEFAULT NULL,
  `points_fidelite` int(11) NOT NULL DEFAULT 0,
  `solde_credit` decimal(10,2) DEFAULT 0.00 COMMENT 'Montant à rembourser au client',
  `solde_dette` decimal(10,2) DEFAULT 0.00 COMMENT 'Montant à payer par le client',
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_email_unique` (`email`),
  KEY `clients_telephone_index` (`telephone`),
  KEY `clients_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TRANSACTIONS COMMERCIALES (AMÉLIORÉES)
-- ========================================

--
-- Structure de la table `transactions` (AMÉLIORÉE)
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero_transaction` varchar(50) NOT NULL UNIQUE,
  `type` enum('vente','echange','consigne','retour','recharge') NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  
  -- Quantités
  `quantite` int(11) NOT NULL DEFAULT 1,
  `quantite_vides_retournees` int(11) DEFAULT 0 COMMENT 'Pour échange/retour',
  
  -- Tarifs
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `consigne_montant` decimal(10,2) NULL COMMENT 'Montant de la consigne',
  `montant_reduction` decimal(10,2) DEFAULT 0.00,
  `montant_net` decimal(10,2) NOT NULL COMMENT 'Après réduction',
  
  -- Paiement
  `mode_paiement` varchar(50) DEFAULT NULL,
  `statut_paiement` enum('en_attente','payé','remboursé','partiellement_payé') DEFAULT 'payé',
  
  -- Consigne/retour
  `transaction_parent_id` bigint(20) UNSIGNED NULL COMMENT 'Lien consigne initiale',
  `date_limite_retour` date NULL COMMENT 'Pour consigne/retour',
  
  -- Admin
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `commentaire` text DEFAULT NULL,
  
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_numero_transaction_unique` (`numero_transaction`),
  KEY `transactions_type_bouteille_id_foreign` (`type_bouteille_id`),
  KEY `transactions_client_id_index` (`client_id`),
  KEY `transactions_administrateur_id_index` (`administrateur_id`),
  KEY `transactions_type_index` (`type`),
  KEY `transactions_created_at_index` (`created_at`),
  KEY `transactions_statut_paiement_index` (`statut_paiement`),
  FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`),
  FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`transaction_parent_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- PAIEMENTS (NOUVEAU - IMPORTANT)
-- ========================================

--
-- Structure de la table `paiements` (NOUVELLE)
--

CREATE TABLE `paiements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  
  `montant_paye` decimal(10,2) NOT NULL,
  `mode_paiement` varchar(50) NOT NULL,
  
  -- Détails selon mode de paiement
  `reference_cheque` varchar(50) NULL COMMENT 'Numéro du chèque',
  `reference_virement` varchar(50) NULL COMMENT 'Référence virement',
  `reference_carte` varchar(50) NULL COMMENT 'Derniers chiffres carte',
  
  `statut` enum('en_attente','confirmé','failed','annulé') DEFAULT 'confirmé',
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  
  `date_paiement` timestamp DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `paiements_transaction_id_index` (`transaction_id`),
  KEY `paiements_client_id_index` (`client_id`),
  KEY `paiements_date_paiement_index` (`date_paiement`),
  FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- GESTION DES FOURNISSEURS (AMÉLIORÉE)
-- ========================================

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `code_fournisseur` varchar(50) NOT NULL UNIQUE,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `contact_nom` varchar(255) DEFAULT NULL,
  `contact_fonction` varchar(100) DEFAULT NULL,
  `conditions_paiement` varchar(50) DEFAULT NULL,
  `delai_livraison` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `commandes` (AMÉLIORÉE)
--

CREATE TABLE `commandes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fournisseur_id` bigint(20) UNSIGNED NOT NULL,
  `numero_commande` varchar(50) NOT NULL UNIQUE,
  
  `date_commande` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_livraison_prevue` date DEFAULT NULL,
  `date_livraison_effective` timestamp NULL COMMENT 'Date de réception réelle',
  
  `montant_ht` decimal(10,2) NOT NULL,
  `montant_taxes` decimal(10,2) DEFAULT 0.00,
  `cout_transport` decimal(10,2) DEFAULT 0.00,
  `montant_total` decimal(10,2) NOT NULL COMMENT 'HT + taxes + transport',
  
  `statut` enum('en_attente','validee','livree_partielle','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commandes_numero_commande_unique` (`numero_commande`),
  KEY `commandes_fournisseur_id_index` (`fournisseur_id`),
  KEY `commandes_administrateur_id_index` (`administrateur_id`),
  KEY `commandes_statut_index` (`statut`),
  KEY `commandes_date_commande_index` (`date_commande`),
  FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`),
  FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `detail_commandes` (NOUVELLE - CRITIQUE)
--

CREATE TABLE `detail_commandes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `commande_id` bigint(20) UNSIGNED NOT NULL,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  
  `quantite_commandee` int(11) NOT NULL,
  `quantite_livree` int(11) DEFAULT 0,
  `quantite_restante` int(11) AS (quantite_commandee - quantite_livree) STORED COMMENT 'Calculé automatiquement',
  
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_ligne` decimal(10,2) NOT NULL,
  
  `statut_ligne` enum('en_attente','livree_partielle','livree','annulee') DEFAULT 'en_attente',
  
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `detail_commandes_commande_id_index` (`commande_id`),
  KEY `detail_commandes_type_bouteille_id_index` (`type_bouteille_id`),
  FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- PRIX ET HISTORIQUE (AMÉLIORÉ)
-- ========================================

--
-- Structure de la table `historique_prix` (AMÉLIORÉE)
--

CREATE TABLE `historique_prix` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  
  `type_prix` enum('vente','consigne','recharge','retour_vide') NOT NULL COMMENT 'Quel prix?',
  `ancien_prix` decimal(10,2) NOT NULL,
  `nouveau_prix` decimal(10,2) NOT NULL,
  `raison` varchar(255) DEFAULT NULL COMMENT 'Pourquoi le changement',
  
  `date_effet` date DEFAULT NULL COMMENT 'Quand applicable',
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `historique_prix_type_bouteille_id_index` (`type_bouteille_id`),
  KEY `historique_prix_administrateur_id_index` (`administrateur_id`),
  KEY `historique_prix_created_at_index` (`created_at`),
  KEY `historique_prix_date_effet_index` (`date_effet`),
  FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- NOTIFICATIONS (AMÉLIORÉE)
-- ========================================

--
-- Structure de la table `notifications` (AMÉLIORÉE)
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  
  `type` enum('alerte_stock','retard_consigne','modification_prix','commande_livree','retour_impaye','retard_paiement') NOT NULL,
  `message` text NOT NULL,
  `priorite` enum('basse','normale','haute','critique') DEFAULT 'normale',
  
  -- Lien à l'entité
  `entite_type` varchar(50) NULL COMMENT 'Exemple: transaction, commande, stock',
  `entite_id` bigint(20) UNSIGNED NULL,
  
  `administrateur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut` enum('non_lu','lu','archivee') NOT NULL DEFAULT 'non_lu',
  
  `date_expiration` timestamp NULL COMMENT 'Quand supprimer la notification',
  
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `notifications_administrateur_id_index` (`administrateur_id`),
  KEY `notifications_statut_index` (`statut`),
  KEY `notifications_priorite_index` (`priorite`),
  KEY `notifications_type_index` (`type`),
  FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLES SYSTÈME
-- ========================================

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL UNIQUE,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- DONNÉES DE TEST
-- ========================================

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `nom_complet`, `email`, `role`, `password`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'TAMBO SIMO Hedric', 'simohedric2023@gmail.com', 'admin', '$2y$12$39hxIrYLov/yNipQSjK5A.0G8K5lOE3kEDq0vMiA9LtKi.AapNK8q', 'actif', '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(2, 'manager', 'Manager Test', 'manager@depotgaz.com', 'manager', '$2y$12$u78ieRIV4zcfmk6hzKHED.AXW4Jcj8.VLV34xoMfWy/gHyLUO5o4G', 'actif', '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(3, 'vendeur', 'Vendeur Test', 'vendeur@depotgaz.com', 'vendeur', '$2y$12$65PkPYxpGew2uLRpioZnPuUWx2qHc0NLHAFEkOSXkFQ/IiltCAnOS', 'actif', '2026-01-10 21:02:49', '2026-01-10 21:02:49');

--
-- Déchargement des données de la table `marques`
--

INSERT INTO `marques` (`id`, `nom`, `statut`, `description`, `created_at`, `updated_at`) VALUES
(1, 'TotalEnergies', 'actif', 'A la maison ou à l\'extérieur, le GPL - gaz de pétrole liquéfié, est là pour répondre à tous vos besoins énergétiques.', '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(2, 'SCTM', 'actif', NULL, '2026-01-10 21:57:36', '2026-01-10 21:57:36');

--
-- Déchargement des données de la table `types_bouteilles`
--

INSERT INTO `types_bouteilles` (`id`, `nom`, `taille`, `marque_id`, `prix_vente`, `prix_consigne`, `prix_recharge`, `prix_bouteille_vide`, `prix_retour_vide`, `seuil_alerte`, `created_at`, `updated_at`) VALUES
(1, 'Total 6kg', '6', 1, 13520.00, 10400.00, 1000.00, 500.00, 400.00, 5, '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(2, 'Total 12.5kg', '12.5', 1, 24120.00, 17700.00, 6000.00, 1000.00, 800.00, 5, '2026-01-10 21:02:49', '2026-01-10 21:02:49');

--
-- Déchargement des données de la table `stocks`
--

INSERT INTO `stocks` (`id`, `type_bouteille_id`, `quantite_pleine`, `quantite_vide`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, '2026-01-10 21:02:49', '2026-01-10 21:31:06'),
(2, 2, 0, 0, '2026-01-10 21:02:49', '2026-01-10 21:30:52');

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `telephone`, `email`, `adresse`, `points_fidelite`, `solde_credit`, `solde_dette`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'Tambo Simo Hedric', '656774288', NULL, NULL, 0, 0.00, 0.00, 'actif', '2026-01-10 21:29:40', '2026-01-10 21:29:40');

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `nom`, `code_fournisseur`, `email`, `telephone`, `adresse`, `ville`, `code_postal`, `pays`, `contact_nom`, `contact_fonction`, `conditions_paiement`, `delai_livraison`, `notes`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'Test Fournisseur', 'FOUR-TEST001', 'test@supplier.com', '+221771234567', 'Rue Test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 21:49:37', '2026-01-10 21:49:37'),
(2, 'Butagaz Sénégal', 'FOUR-BG001', 'contact@butagaz.sn', '+221771234567', 'Dakar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 22:50:59', '2026-01-10 22:50:59'),
(3, 'Primagaz Côte d\'Ivoire', 'FOUR-PG001', 'contact@primagaz.ci', '+22507012345', 'Abidjan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 22:50:59', '2026-01-10 22:50:59'),
(4, 'TotalEnergies', 'FOUR-TE001', 'contact@total.sn', '+221338224300', 'Dakar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 22:50:59', '2026-01-10 22:50:59'),
(5, 'fjfggfv', 'FOUR-58DBBA99', 'simo@gmail.com', '656774288', 'fhghg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 21:51:59', '2026-01-10 21:51:59');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@OLD_COLLATION_CONNECTION */;
