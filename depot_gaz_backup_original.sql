-- phpMyAdmin SQL Dump
-- version 5.2.3deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 11 jan. 2026 à 10:47
-- Version du serveur : 11.8.3-MariaDB-1+b1 from Debian
-- Version de PHP : 8.4.11

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

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `points_fidelite` int(11) NOT NULL DEFAULT 0,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `telephone`, `email`, `adresse`, `points_fidelite`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'Tambo Simo Hedric', '656774288', NULL, NULL, 0, 'actif', '2026-01-10 21:29:40', '2026-01-10 21:29:40');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fournisseur_id` bigint(20) UNSIGNED NOT NULL,
  `numero_commande` varchar(50) NOT NULL,
  `date_livraison_prevue` date DEFAULT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `statut` enum('en_attente','validee','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code_fournisseur` varchar(50) NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `nom`, `code_fournisseur`, `email`, `telephone`, `adresse`, `ville`, `code_postal`, `pays`, `contact_nom`, `contact_fonction`, `conditions_paiement`, `delai_livraison`, `notes`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'Test Fournisseur', 'FOUR-TEST001', 'test@supplier.com', '+221771234567', 'Rue Test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 21:49:37', '2026-01-10 21:49:37'),
(2, 'Butagaz Sénégal', 'FOUR-BG001', 'contact@butagaz.sn', '+221771234567', 'Dakar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 22:50:59', '2026-01-10 22:50:59'),
(3, 'Primagaz Côte d\'Ivoire', 'FOUR-PG001', 'contact@primagaz.ci', '+22507012345', 'Abidjan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 22:50:59', '2026-01-10 22:50:59'),
(4, 'TotalEnergies', 'FOUR-TE001', 'contact@total.sn', '+221338224300', 'Dakar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 22:50:59', '2026-01-10 22:50:59'),
(5, 'fjfggfv', 'FOUR-58DBBA99', 'simo@gmail.com', '656774288', 'fhghg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-10 21:51:59', '2026-01-10 21:51:59');

-- --------------------------------------------------------

--
-- Structure de la table `historique_prix`
--

CREATE TABLE `historique_prix` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  `ancien_prix` decimal(10,2) NOT NULL,
  `nouveau_prix` decimal(10,2) NOT NULL,
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `type_prix` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `marques`
--

CREATE TABLE `marques` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `marques`
--

INSERT INTO `marques` (`id`, `nom`, `statut`, `description`, `created_at`, `updated_at`) VALUES
(1, 'TotalEnergies', 'actif', 'A la maison ou à l\'extérieur, le GPL - gaz de pétrole liquéfié, est là pour répondre à tous vos besoins énergétiques.', '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(2, 'SCTM', 'actif', NULL, '2026-01-10 21:57:36', '2026-01-10 21:57:36');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_10_001_create_marques_table', 1),
(5, '2025_01_10_002_create_types_bouteilles_table', 1),
(6, '2025_01_10_003_create_stocks_table', 1),
(7, '2025_01_10_004_create_mouvements_stocks_table', 1),
(8, '2025_01_10_005_create_clients_table', 1),
(9, '2025_01_10_006_create_transactions_table', 1),
(10, '2025_01_10_007_create_historique_prix_table', 1),
(11, '2025_01_10_008_create_notifications_table', 1),
(12, '2025_01_10_009_create_fournisseurs_table', 1),
(13, '2025_01_10_010_create_commandes_table', 1),
(14, '2026_01_10_220405_add_two_factor_columns_to_users_table', 2),
(15, '2026_01_10_225429_add_statut_to_marques_table', 2);

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_stocks`
--

CREATE TABLE `mouvements_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `type_mouvement` enum('entree','sortie','ajustement') NOT NULL,
  `quantite_pleine` int(11) NOT NULL,
  `quantite_vide` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `motif` varchar(50) DEFAULT NULL,
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mouvements_stocks`
--

INSERT INTO `mouvements_stocks` (`id`, `stock_id`, `type_mouvement`, `quantite_pleine`, `quantite_vide`, `commentaire`, `motif`, `administrateur_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'ajustement', -49, 0, 'Ajustement manuel', NULL, 1, '2026-01-10 21:30:46', '2026-01-10 21:30:46'),
(2, 2, 'ajustement', -30, 0, 'Ajustement manuel', NULL, 1, '2026-01-10 21:30:52', '2026-01-10 21:30:52'),
(3, 1, 'ajustement', 0, -1, 'Ajustement manuel', NULL, 1, '2026-01-10 21:31:06', '2026-01-10 21:31:06');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('alerte_stock','retard_consigne','modification_prix') NOT NULL,
  `message` text NOT NULL,
  `administrateur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut` enum('non_lu','lu') NOT NULL DEFAULT 'non_lu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  `quantite_pleine` int(11) NOT NULL DEFAULT 0,
  `quantite_vide` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stocks`
--

INSERT INTO `stocks` (`id`, `type_bouteille_id`, `quantite_pleine`, `quantite_vide`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, '2026-01-10 21:02:49', '2026-01-10 21:31:06'),
(2, 2, 0, 0, '2026-01-10 21:02:49', '2026-01-10 21:30:52');

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('vente','echange','consigne','retour','recharge') NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `mode_paiement` varchar(50) DEFAULT NULL,
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transactions`
--

INSERT INTO `transactions` (`id`, `type`, `client_id`, `type_bouteille_id`, `quantite`, `prix_unitaire`, `montant_total`, `mode_paiement`, `administrateur_id`, `commentaire`, `created_at`, `updated_at`) VALUES
(1, 'recharge', NULL, 1, 1, 6500.00, 6500.00, 'especes', 1, NULL, '2026-01-10 21:30:25', '2026-01-10 21:30:25');

-- --------------------------------------------------------

--
-- Structure de la table `types_bouteilles`
--

CREATE TABLE `types_bouteilles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `taille` varchar(50) NOT NULL,
  `marque_id` bigint(20) UNSIGNED NOT NULL,
  `prix_vente` decimal(10,2) NOT NULL,
  `prix_consigne` decimal(10,2) NOT NULL,
  `prix_recharge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `seuil_alerte` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_bouteilles`
--

INSERT INTO `types_bouteilles` (`id`, `nom`, `taille`, `marque_id`, `prix_vente`, `prix_consigne`, `prix_recharge`, `seuil_alerte`, `created_at`, `updated_at`) VALUES
(1, 'Total 6kg', '6', 1, 13520.00, 10400.00, 1000.00, 5, '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(2, 'Total 12.5kg', '12.5', 1, 241200.00, 17700.00, 0.00, 5, '2026-01-10 21:02:49', '2026-01-10 21:02:49');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `nom_complet`, `email`, `role`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `statut`, `dernier_login`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'TAMBO SIMO Hedric', 'simohedric2023@gmail.com', 'admin', NULL, '$2y$12$39hxIrYLov/yNipQSjK5A.0G8K5lOE3kEDq0vMiA9LtKi.AapNK8q', NULL, NULL, NULL, 'actif', NULL, 'mVzK0BbMsTj7n4DxCcdImOsLZTPbZGe6PfQ8jha3uJxCgYLujo5bfH8vqVfu', '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(2, 'manager', 'Manager Test', 'manager@depotgaz.com', 'manager', NULL, '$2y$12$u78ieRIV4zcfmk6hzKHED.AXW4Jcj8.VLV34xoMfWy/gHyLUO5o4G', NULL, NULL, NULL, 'actif', NULL, NULL, '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(3, 'vendeur', 'Vendeur Test', 'vendeur@depotgaz.com', 'vendeur', NULL, '$2y$12$65PkPYxpGew2uLRpioZnPuUWx2qHc0NLHAFEkOSXkFQ/IiltCAnOS', NULL, NULL, NULL, 'actif', NULL, NULL, '2026-01-10 21:02:49', '2026-01-10 21:02:49');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_email_unique` (`email`),
  ADD KEY `clients_telephone_index` (`telephone`),
  ADD KEY `clients_email_index` (`email`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commandes_numero_commande_unique` (`numero_commande`),
  ADD KEY `commandes_fournisseur_id_index` (`fournisseur_id`),
  ADD KEY `commandes_administrateur_id_index` (`administrateur_id`),
  ADD KEY `commandes_statut_index` (`statut`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fournisseurs_code_fournisseur_unique` (`code_fournisseur`);

--
-- Index pour la table `historique_prix`
--
ALTER TABLE `historique_prix`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historique_prix_administrateur_id_foreign` (`administrateur_id`),
  ADD KEY `historique_prix_type_bouteille_id_index` (`type_bouteille_id`),
  ADD KEY `historique_prix_created_at_index` (`created_at`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `marques`
--
ALTER TABLE `marques`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marques_nom_unique` (`nom`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mouvements_stocks`
--
ALTER TABLE `mouvements_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mouvements_stocks_stock_id_index` (`stock_id`),
  ADD KEY `mouvements_stocks_administrateur_id_index` (`administrateur_id`),
  ADD KEY `mouvements_stocks_created_at_index` (`created_at`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_administrateur_id_index` (`administrateur_id`),
  ADD KEY `notifications_statut_index` (`statut`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stocks_type_bouteille_id_unique` (`type_bouteille_id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_type_bouteille_id_foreign` (`type_bouteille_id`),
  ADD KEY `transactions_client_id_index` (`client_id`),
  ADD KEY `transactions_administrateur_id_index` (`administrateur_id`),
  ADD KEY `transactions_type_index` (`type`),
  ADD KEY `transactions_created_at_index` (`created_at`);

--
-- Index pour la table `types_bouteilles`
--
ALTER TABLE `types_bouteilles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `types_bouteilles_nom_marque_id_unique` (`nom`,`marque_id`),
  ADD KEY `types_bouteilles_marque_id_foreign` (`marque_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `historique_prix`
--
ALTER TABLE `historique_prix`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `marques`
--
ALTER TABLE `marques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `mouvements_stocks`
--
ALTER TABLE `mouvements_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `types_bouteilles`
--
ALTER TABLE `types_bouteilles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `commandes_fournisseur_id_foreign` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`);

--
-- Contraintes pour la table `historique_prix`
--
ALTER TABLE `historique_prix`
  ADD CONSTRAINT `historique_prix_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `historique_prix_type_bouteille_id_foreign` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mouvements_stocks`
--
ALTER TABLE `mouvements_stocks`
  ADD CONSTRAINT `mouvements_stocks_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `mouvements_stocks_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_type_bouteille_id_foreign` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_type_bouteille_id_foreign` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`);

--
-- Contraintes pour la table `types_bouteilles`
--
ALTER TABLE `types_bouteilles`
  ADD CONSTRAINT `types_bouteilles_marque_id_foreign` FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
