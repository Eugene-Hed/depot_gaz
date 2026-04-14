-- phpMyAdmin SQL Dump
-- version 5.2.3deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 11 jan. 2026 à 22:42
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
  `solde_credit` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Montant à rembourser au client',
  `solde_dette` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Montant à payer par le client',
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `telephone`, `email`, `adresse`, `points_fidelite`, `solde_credit`, `solde_dette`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'Tambo Simo Hedric', '656774288', NULL, NULL, 0, 0.00, 0.00, 'actif', '2026-01-10 21:29:40', '2026-01-10 21:29:40');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fournisseur_id` bigint(20) UNSIGNED NOT NULL,
  `numero_commande` varchar(50) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date de création',
  `date_livraison_prevue` date DEFAULT NULL,
  `date_livraison_effective` timestamp NULL DEFAULT NULL COMMENT 'Date réelle de livraison',
  `montant_ht` decimal(10,2) NOT NULL COMMENT 'Hors taxes',
  `montant_taxes` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cout_transport` decimal(10,2) NOT NULL DEFAULT 0.00,
  `statut` enum('en_attente','validee','livree_partielle','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `montant_total` decimal(10,2) NOT NULL,
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='montant_total = montant_ht + montant_taxes + cout_transport';

-- --------------------------------------------------------

--
-- Structure de la table `detail_commandes`
--

CREATE TABLE `detail_commandes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commande_id` bigint(20) UNSIGNED NOT NULL,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  `quantite_commandee` int(11) NOT NULL,
  `quantite_livree` int(11) NOT NULL DEFAULT 0,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_ligne` decimal(10,2) NOT NULL,
  `statut_ligne` enum('en_attente','livree_partielle','livree','annulee') NOT NULL DEFAULT 'en_attente',
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
(6, 'Herman (total)', 'FOUR-05539D49', NULL, '+237656774288', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-01-11 20:56:26', '2026-01-11 20:56:26');

-- --------------------------------------------------------

--
-- Structure de la table `historique_prix`
--

CREATE TABLE `historique_prix` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  `ancien_prix` decimal(10,2) NOT NULL,
  `nouveau_prix` decimal(10,2) NOT NULL,
  `raison` varchar(255) DEFAULT NULL COMMENT 'Pourquoi le changement',
  `date_effet` date DEFAULT NULL COMMENT 'Quand applicable',
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
(2, 'SCTM', 'actif', NULL, '2026-01-10 21:57:36', '2026-01-11 20:43:05'),
(3, 'TRADEX', 'actif', NULL, '2026-01-11 20:43:25', '2026-01-11 20:43:25');

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
(15, '2026_01_10_225429_add_statut_to_marques_table', 2),
(16, '2026_01_11_000001_add_improvements_to_types_bouteilles', 3),
(17, '2026_01_11_000002_add_soldes_to_clients', 3),
(18, '2026_01_11_000003_add_casse_to_mouvements_stocks', 3),
(19, '2026_01_11_000004_improve_transactions_table', 3),
(20, '2026_01_11_000005_create_paiements_table', 3),
(21, '2026_01_11_000006_create_detail_commandes_table', 3),
(22, '2026_01_11_000007_improve_commandes_table', 3),
(23, '2026_01_11_000008_improve_historique_prix_table', 3),
(24, '2026_01_11_000009_improve_notifications_table', 3),
(25, '2026_01_11_000010_update_transactions_type_enum', 4),
(26, '2026_01_11_000011_make_prix_consigne_nullable_in_types_bouteilles', 5),
(27, '2026_01_11_000012_remove_nom_from_types_bouteilles', 6),
(28, '2026_01_11_000013_add_statut_to_types_bouteilles', 7),
(29, '2026_01_11_000000_simplify_user_roles', 8);

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_stocks`
--

CREATE TABLE `mouvements_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `type_mouvement` enum('entree','sortie','ajustement','casse') NOT NULL,
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
(1, 1, 'entree', -49, 0, 'Ajustement manuel', NULL, 1, '2026-01-10 21:30:46', '2026-01-10 21:30:46'),
(2, 2, 'entree', -30, 0, 'Ajustement manuel', NULL, 1, '2026-01-10 21:30:52', '2026-01-10 21:30:52'),
(3, 1, 'entree', 0, -1, 'Ajustement manuel', NULL, 1, '2026-01-10 21:31:06', '2026-01-10 21:31:06'),
(4, 1, 'entree', 25, 0, NULL, 'Achat auprès des fournisseurs', 1, '2026-01-11 12:21:36', '2026-01-11 12:21:36'),
(5, 2, 'entree', 10, 0, NULL, 'Achat auprès des fournisseurs', 1, '2026-01-11 12:21:55', '2026-01-11 12:21:55'),
(6, 1, 'entree', 0, 2, 'Ajustement manuel', 'augmentation', 1, '2026-01-11 12:22:27', '2026-01-11 12:22:27');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('alerte_stock','retard_consigne','modification_prix','commande_livree','retour_impaye','retard_paiement') NOT NULL,
  `message` text NOT NULL,
  `priorite` enum('basse','normale','haute','critique') NOT NULL DEFAULT 'normale',
  `entite_type` varchar(255) DEFAULT NULL COMMENT 'Exemple: transaction, commande, stock',
  `entite_id` bigint(20) UNSIGNED DEFAULT NULL,
  `administrateur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut` enum('non_lu','lu','archivee') NOT NULL DEFAULT 'non_lu',
  `date_expiration` timestamp NULL DEFAULT NULL COMMENT 'Quand supprimer la notification',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `montant_paye` decimal(10,2) NOT NULL,
  `mode_paiement` varchar(255) NOT NULL,
  `reference_cheque` varchar(255) DEFAULT NULL COMMENT 'Numéro du chèque',
  `reference_virement` varchar(255) DEFAULT NULL COMMENT 'Référence virement',
  `reference_carte` varchar(255) DEFAULT NULL COMMENT 'Derniers chiffres carte',
  `statut` enum('en_attente','confirmé','failed','annulé') NOT NULL DEFAULT 'confirmé',
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  `date_paiement` timestamp NOT NULL DEFAULT current_timestamp(),
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
(1, 1, 20, 5, '2026-01-10 21:02:49', '2026-01-11 13:12:52'),
(2, 2, 6, 4, '2026-01-10 21:02:49', '2026-01-11 13:11:33');

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero_transaction` varchar(255) NOT NULL COMMENT 'Référence unique de la transaction',
  `type` varchar(50) NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type_bouteille_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `quantite_vides_retournees` int(11) NOT NULL DEFAULT 0 COMMENT 'Pour échange/retour',
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `consigne_montant` decimal(10,2) DEFAULT NULL COMMENT 'Montant de la consigne',
  `montant_reduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_net` decimal(10,2) NOT NULL COMMENT 'Après réduction',
  `mode_paiement` varchar(50) DEFAULT NULL,
  `statut_paiement` enum('en_attente','payé','remboursé','partiellement_payé') NOT NULL DEFAULT 'payé',
  `transaction_parent_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Lien consigne initiale',
  `date_limite_retour` date DEFAULT NULL COMMENT 'Pour consigne/retour',
  `administrateur_id` bigint(20) UNSIGNED NOT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transactions`
--

INSERT INTO `transactions` (`id`, `numero_transaction`, `type`, `client_id`, `type_bouteille_id`, `quantite`, `quantite_vides_retournees`, `prix_unitaire`, `montant_total`, `consigne_montant`, `montant_reduction`, `montant_net`, `mode_paiement`, `statut_paiement`, `transaction_parent_id`, `date_limite_retour`, `administrateur_id`, `commentaire`, `created_at`, `updated_at`) VALUES
(1, '', 'recharge', NULL, 1, 1, 0, 6500.00, 6500.00, NULL, 0.00, 0.00, 'especes', 'payé', NULL, NULL, 1, NULL, '2026-01-10 21:30:25', '2026-01-10 21:30:25'),
(2, 'TRX-20260111140907-1988', 'echange_simple', NULL, 1, 1, 0, 13500.00, 13500.00, 0.00, 0.00, 13500.00, 'especes', 'payé', NULL, NULL, 1, NULL, '2026-01-11 13:09:07', '2026-01-11 13:09:07'),
(3, 'TRX-20260111141133-9996', 'echange_type', NULL, 2, 1, 0, 25000.00, 25000.00, 0.00, 0.00, 25000.00, 'especes', 'payé', NULL, NULL, 1, NULL, '2026-01-11 13:11:33', '2026-01-11 13:11:33'),
(4, 'TRX-20260111141219-1371', 'achat_simple', NULL, 1, 1, 0, 25000.00, 25000.00, 0.00, 0.00, 25000.00, 'especes', 'payé', NULL, NULL, 1, NULL, '2026-01-11 13:12:19', '2026-01-11 13:12:19'),
(5, 'TRX-20260111141252-6128', 'echange_differe', NULL, 1, 1, 0, 15000.00, 15000.00, 0.00, 0.00, 15000.00, 'especes', 'payé', NULL, NULL, 1, NULL, '2026-01-11 13:12:52', '2026-01-11 13:12:52');

-- --------------------------------------------------------

--
-- Structure de la table `types_bouteilles`
--

CREATE TABLE `types_bouteilles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taille` varchar(50) NOT NULL,
  `marque_id` bigint(20) UNSIGNED NOT NULL,
  `prix_vente` decimal(10,2) NOT NULL,
  `prix_consigne` decimal(10,2) DEFAULT NULL,
  `prix_recharge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prix_bouteille_vide` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Prix de rachat bouteille vide',
  `prix_retour_vide` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Remboursement au retour',
  `seuil_alerte` int(11) NOT NULL DEFAULT 5,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_bouteilles`
--

INSERT INTO `types_bouteilles` (`id`, `taille`, `marque_id`, `prix_vente`, `prix_consigne`, `prix_recharge`, `prix_bouteille_vide`, `prix_retour_vide`, `seuil_alerte`, `statut`, `created_at`, `updated_at`) VALUES
(1, '6', 1, 13520.00, 10400.00, 1000.00, 0.00, 0.00, 5, 'actif', '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(2, '12.5', 1, 241200.00, 17700.00, 0.00, 0.00, 0.00, 5, 'actif', '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(3, '3.5', 1, 4500.00, NULL, 2800.00, 0.00, 0.00, 8, 'actif', '2026-01-11 19:52:14', '2026-01-11 19:52:14'),
(4, '10', 1, 15000.00, NULL, 10000.00, 0.00, 0.00, 5, 'actif', '2026-01-11 20:36:32', '2026-01-11 20:40:27');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin',
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
(1, 'admin', 'TAMBO SIMO Hedric', 'simohedric2023@gmail.com', 'admin', NULL, '$2y$12$SjQ1svSiDsH3G9st1P1L7uMQMdmW6G8a2NWKBuEzbbo/dZNlAlYW2', NULL, NULL, NULL, 'actif', NULL, '$2y$12$tlwiwpNDu81miIyo0wkR9u2WTFh0LL0Kwh6pQUh8Kr12Joz3qrYd2', '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(2, 'manager', 'Manager Test', 'manager@depotgaz.com', 'admin', NULL, '$2y$12$u78ieRIV4zcfmk6hzKHED.AXW4Jcj8.VLV34xoMfWy/gHyLUO5o4G', NULL, NULL, NULL, 'actif', NULL, NULL, '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(3, 'vendeur', 'Vendeur Test', 'vendeur@depotgaz.com', 'admin', NULL, '$2y$12$65PkPYxpGew2uLRpioZnPuUWx2qHc0NLHAFEkOSXkFQ/IiltCAnOS', NULL, NULL, NULL, 'actif', NULL, NULL, '2026-01-10 21:02:49', '2026-01-10 21:02:49'),
(4, 'testadmin', 'Admin Test', 'admin@depotgaz.com', 'admin', NULL, '$2y$12$2OMUHz7IuuzTSC.jGn3DoOtSAhKXYzvzbxgGw3NRAai1eEpCItySG', NULL, NULL, NULL, 'actif', NULL, NULL, '2026-01-11 21:39:59', '2026-01-11 21:39:59');

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
  ADD KEY `commandes_date_commande_index` (`date_commande`),
  ADD KEY `commandes_date_livraison_effective_index` (`date_livraison_effective`);

--
-- Index pour la table `detail_commandes`
--
ALTER TABLE `detail_commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_commandes_commande_id_index` (`commande_id`),
  ADD KEY `detail_commandes_type_bouteille_id_index` (`type_bouteille_id`);

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
  ADD KEY `historique_prix_created_at_index` (`created_at`),
  ADD KEY `historique_prix_date_effet_index` (`date_effet`);

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
  ADD KEY `notifications_priorite_index` (`priorite`),
  ADD KEY `notifications_type_index` (`type`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paiements_transaction_id_index` (`transaction_id`),
  ADD KEY `paiements_client_id_index` (`client_id`),
  ADD KEY `paiements_date_paiement_index` (`date_paiement`),
  ADD KEY `paiements_administrateur_id_index` (`administrateur_id`);

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
  ADD UNIQUE KEY `transactions_numero_transaction_unique` (`numero_transaction`),
  ADD KEY `transactions_type_bouteille_id_foreign` (`type_bouteille_id`),
  ADD KEY `transactions_client_id_index` (`client_id`),
  ADD KEY `transactions_administrateur_id_index` (`administrateur_id`),
  ADD KEY `transactions_type_index` (`type`),
  ADD KEY `transactions_created_at_index` (`created_at`),
  ADD KEY `transactions_transaction_parent_id_foreign` (`transaction_parent_id`);

--
-- Index pour la table `types_bouteilles`
--
ALTER TABLE `types_bouteilles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `types_bouteilles_taille_marque_id_unique` (`taille`,`marque_id`),
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
-- AUTO_INCREMENT pour la table `detail_commandes`
--
ALTER TABLE `detail_commandes`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `mouvements_stocks`
--
ALTER TABLE `mouvements_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `types_bouteilles`
--
ALTER TABLE `types_bouteilles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Contraintes pour la table `detail_commandes`
--
ALTER TABLE `detail_commandes`
  ADD CONSTRAINT `detail_commandes_commande_id_foreign` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_commandes_type_bouteille_id_foreign` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`);

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
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `paiements_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `paiements_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `paiements_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `transactions_transaction_parent_id_foreign` FOREIGN KEY (`transaction_parent_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL,
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
