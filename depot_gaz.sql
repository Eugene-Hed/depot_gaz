/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.3-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: depot_gaz
-- ------------------------------------------------------
-- Server version	11.8.3-MariaDB-1+b1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `points_fidelite` int(11) NOT NULL DEFAULT 0,
  `solde_credit` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Montant à rembourser au client',
  `solde_dette` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Montant à payer par le client',
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_email_unique` (`email`),
  KEY `clients_telephone_index` (`telephone`),
  KEY `clients_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `clients` VALUES
(1,'Tambo Simo Hedric','656774288',NULL,NULL,0,0.00,0.00,'actif','2026-01-10 21:29:40','2026-01-10 21:29:40');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `commandes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fournisseur_id` bigint(20) unsigned NOT NULL,
  `numero_commande` varchar(50) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date de création',
  `date_livraison_prevue` date DEFAULT NULL,
  `date_livraison_effective` timestamp NULL DEFAULT NULL COMMENT 'Date réelle de livraison',
  `montant_ht` decimal(10,2) NOT NULL COMMENT 'Hors taxes',
  `montant_taxes` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cout_transport` decimal(10,2) NOT NULL DEFAULT 0.00,
  `statut` enum('en_attente','validee','livree_partielle','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `montant_total` decimal(10,2) NOT NULL,
  `administrateur_id` bigint(20) unsigned NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commandes_numero_commande_unique` (`numero_commande`),
  KEY `commandes_fournisseur_id_index` (`fournisseur_id`),
  KEY `commandes_administrateur_id_index` (`administrateur_id`),
  KEY `commandes_date_commande_index` (`date_commande`),
  KEY `commandes_date_livraison_effective_index` (`date_livraison_effective`),
  CONSTRAINT `commandes_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `commandes_fournisseur_id_foreign` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='montant_total = montant_ht + montant_taxes + cout_transport';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commandes`
--

LOCK TABLES `commandes` WRITE;
/*!40000 ALTER TABLE `commandes` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `commandes` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `detail_commandes`
--

DROP TABLE IF EXISTS `detail_commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_commandes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `commande_id` bigint(20) unsigned NOT NULL,
  `type_bouteille_id` bigint(20) unsigned NOT NULL,
  `quantite_commandee` int(11) NOT NULL,
  `quantite_livree` int(11) NOT NULL DEFAULT 0,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_ligne` decimal(10,2) NOT NULL,
  `statut_ligne` enum('en_attente','livree_partielle','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_commandes_commande_id_index` (`commande_id`),
  KEY `detail_commandes_type_bouteille_id_index` (`type_bouteille_id`),
  CONSTRAINT `detail_commandes_commande_id_foreign` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_commandes_type_bouteille_id_foreign` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_commandes`
--

LOCK TABLES `detail_commandes` WRITE;
/*!40000 ALTER TABLE `detail_commandes` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `detail_commandes` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fournisseurs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fournisseurs_code_fournisseur_unique` (`code_fournisseur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fournisseurs`
--

LOCK TABLES `fournisseurs` WRITE;
/*!40000 ALTER TABLE `fournisseurs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `fournisseurs` VALUES
(1,'Test Fournisseur','FOUR-TEST001','test@supplier.com','+221771234567','Rue Test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'actif','2026-01-10 21:49:37','2026-01-10 21:49:37'),
(2,'Butagaz Sénégal','FOUR-BG001','contact@butagaz.sn','+221771234567','Dakar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'actif','2026-01-10 22:50:59','2026-01-10 22:50:59'),
(3,'Primagaz Côte d\'Ivoire','FOUR-PG001','contact@primagaz.ci','+22507012345','Abidjan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'actif','2026-01-10 22:50:59','2026-01-10 22:50:59'),
(4,'TotalEnergies','FOUR-TE001','contact@total.sn','+221338224300','Dakar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'actif','2026-01-10 22:50:59','2026-01-10 22:50:59'),
(5,'fjfggfv','FOUR-58DBBA99','simo@gmail.com','656774288','fhghg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'actif','2026-01-10 21:51:59','2026-01-10 21:51:59');
/*!40000 ALTER TABLE `fournisseurs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `historique_prix`
--

DROP TABLE IF EXISTS `historique_prix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `historique_prix` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_bouteille_id` bigint(20) unsigned NOT NULL,
  `ancien_prix` decimal(10,2) NOT NULL,
  `nouveau_prix` decimal(10,2) NOT NULL,
  `raison` varchar(255) DEFAULT NULL COMMENT 'Pourquoi le changement',
  `date_effet` date DEFAULT NULL COMMENT 'Quand applicable',
  `administrateur_id` bigint(20) unsigned NOT NULL,
  `type_prix` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historique_prix_administrateur_id_foreign` (`administrateur_id`),
  KEY `historique_prix_type_bouteille_id_index` (`type_bouteille_id`),
  KEY `historique_prix_created_at_index` (`created_at`),
  KEY `historique_prix_date_effet_index` (`date_effet`),
  CONSTRAINT `historique_prix_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `historique_prix_type_bouteille_id_foreign` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historique_prix`
--

LOCK TABLES `historique_prix` WRITE;
/*!40000 ALTER TABLE `historique_prix` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `historique_prix` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `marques`
--

DROP TABLE IF EXISTS `marques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `marques` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marques_nom_unique` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marques`
--

LOCK TABLES `marques` WRITE;
/*!40000 ALTER TABLE `marques` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `marques` VALUES
(1,'TotalEnergies','actif','A la maison ou à l\'extérieur, le GPL - gaz de pétrole liquéfié, est là pour répondre à tous vos besoins énergétiques.','2026-01-10 21:02:49','2026-01-10 21:02:49'),
(2,'SCTM','actif',NULL,'2026-01-10 21:57:36','2026-01-10 21:57:36');
/*!40000 ALTER TABLE `marques` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_01_10_001_create_marques_table',1),
(5,'2025_01_10_002_create_types_bouteilles_table',1),
(6,'2025_01_10_003_create_stocks_table',1),
(7,'2025_01_10_004_create_mouvements_stocks_table',1),
(8,'2025_01_10_005_create_clients_table',1),
(9,'2025_01_10_006_create_transactions_table',1),
(10,'2025_01_10_007_create_historique_prix_table',1),
(11,'2025_01_10_008_create_notifications_table',1),
(12,'2025_01_10_009_create_fournisseurs_table',1),
(13,'2025_01_10_010_create_commandes_table',1),
(14,'2026_01_10_220405_add_two_factor_columns_to_users_table',2),
(15,'2026_01_10_225429_add_statut_to_marques_table',2),
(16,'2026_01_11_000001_add_improvements_to_types_bouteilles',3),
(17,'2026_01_11_000002_add_soldes_to_clients',3),
(18,'2026_01_11_000003_add_casse_to_mouvements_stocks',3),
(19,'2026_01_11_000004_improve_transactions_table',3),
(20,'2026_01_11_000005_create_paiements_table',3),
(21,'2026_01_11_000006_create_detail_commandes_table',3),
(22,'2026_01_11_000007_improve_commandes_table',3),
(23,'2026_01_11_000008_improve_historique_prix_table',3),
(24,'2026_01_11_000009_improve_notifications_table',3),
(25,'2026_01_11_000010_update_transactions_type_enum',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `mouvements_stocks`
--

DROP TABLE IF EXISTS `mouvements_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mouvements_stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` bigint(20) unsigned NOT NULL,
  `type_mouvement` enum('entree','sortie','ajustement','casse') NOT NULL,
  `quantite_pleine` int(11) NOT NULL,
  `quantite_vide` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `motif` varchar(50) DEFAULT NULL,
  `administrateur_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mouvements_stocks_stock_id_index` (`stock_id`),
  KEY `mouvements_stocks_administrateur_id_index` (`administrateur_id`),
  KEY `mouvements_stocks_created_at_index` (`created_at`),
  CONSTRAINT `mouvements_stocks_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `mouvements_stocks_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mouvements_stocks`
--

LOCK TABLES `mouvements_stocks` WRITE;
/*!40000 ALTER TABLE `mouvements_stocks` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `mouvements_stocks` VALUES
(1,1,'entree',-49,0,'Ajustement manuel',NULL,1,'2026-01-10 21:30:46','2026-01-10 21:30:46'),
(2,2,'entree',-30,0,'Ajustement manuel',NULL,1,'2026-01-10 21:30:52','2026-01-10 21:30:52'),
(3,1,'entree',0,-1,'Ajustement manuel',NULL,1,'2026-01-10 21:31:06','2026-01-10 21:31:06'),
(4,1,'entree',25,0,NULL,'Achat auprès des fournisseurs',1,'2026-01-11 12:21:36','2026-01-11 12:21:36'),
(5,2,'entree',10,0,NULL,'Achat auprès des fournisseurs',1,'2026-01-11 12:21:55','2026-01-11 12:21:55'),
(6,1,'entree',0,2,'Ajustement manuel','augmentation',1,'2026-01-11 12:22:27','2026-01-11 12:22:27');
/*!40000 ALTER TABLE `mouvements_stocks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('alerte_stock','retard_consigne','modification_prix','commande_livree','retour_impaye','retard_paiement') NOT NULL,
  `message` text NOT NULL,
  `priorite` enum('basse','normale','haute','critique') NOT NULL DEFAULT 'normale',
  `entite_type` varchar(255) DEFAULT NULL COMMENT 'Exemple: transaction, commande, stock',
  `entite_id` bigint(20) unsigned DEFAULT NULL,
  `administrateur_id` bigint(20) unsigned DEFAULT NULL,
  `statut` enum('non_lu','lu','archivee') NOT NULL DEFAULT 'non_lu',
  `date_expiration` timestamp NULL DEFAULT NULL COMMENT 'Quand supprimer la notification',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_administrateur_id_index` (`administrateur_id`),
  KEY `notifications_priorite_index` (`priorite`),
  KEY `notifications_type_index` (`type`),
  CONSTRAINT `notifications_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `paiements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `montant_paye` decimal(10,2) NOT NULL,
  `mode_paiement` varchar(255) NOT NULL,
  `reference_cheque` varchar(255) DEFAULT NULL COMMENT 'Numéro du chèque',
  `reference_virement` varchar(255) DEFAULT NULL COMMENT 'Référence virement',
  `reference_carte` varchar(255) DEFAULT NULL COMMENT 'Derniers chiffres carte',
  `statut` enum('en_attente','confirmé','failed','annulé') NOT NULL DEFAULT 'confirmé',
  `administrateur_id` bigint(20) unsigned NOT NULL,
  `notes` text DEFAULT NULL,
  `date_paiement` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paiements_transaction_id_index` (`transaction_id`),
  KEY `paiements_client_id_index` (`client_id`),
  KEY `paiements_date_paiement_index` (`date_paiement`),
  KEY `paiements_administrateur_id_index` (`administrateur_id`),
  CONSTRAINT `paiements_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `paiements_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `paiements_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paiements`
--

LOCK TABLES `paiements` WRITE;
/*!40000 ALTER TABLE `paiements` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `paiements` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_bouteille_id` bigint(20) unsigned NOT NULL,
  `quantite_pleine` int(11) NOT NULL DEFAULT 0,
  `quantite_vide` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stocks_type_bouteille_id_unique` (`type_bouteille_id`),
  CONSTRAINT `stocks_type_bouteille_id_foreign` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `stocks` VALUES
(1,1,25,2,'2026-01-10 21:02:49','2026-01-11 12:22:27'),
(2,2,7,3,'2026-01-10 21:02:49','2026-01-11 12:48:35');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero_transaction` varchar(255) NOT NULL COMMENT 'Référence unique de la transaction',
  `type` varchar(50) NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `type_bouteille_id` bigint(20) unsigned NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `quantite_vides_retournees` int(11) NOT NULL DEFAULT 0 COMMENT 'Pour échange/retour',
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `consigne_montant` decimal(10,2) DEFAULT NULL COMMENT 'Montant de la consigne',
  `montant_reduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_net` decimal(10,2) NOT NULL COMMENT 'Après réduction',
  `mode_paiement` varchar(50) DEFAULT NULL,
  `statut_paiement` enum('en_attente','payé','remboursé','partiellement_payé') NOT NULL DEFAULT 'payé',
  `transaction_parent_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Lien consigne initiale',
  `date_limite_retour` date DEFAULT NULL COMMENT 'Pour consigne/retour',
  `administrateur_id` bigint(20) unsigned NOT NULL,
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
  KEY `transactions_transaction_parent_id_foreign` (`transaction_parent_id`),
  CONSTRAINT `transactions_administrateur_id_foreign` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `transactions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_transaction_parent_id_foreign` FOREIGN KEY (`transaction_parent_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_type_bouteille_id_foreign` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `transactions` VALUES
(1,'','recharge',NULL,1,1,0,6500.00,6500.00,NULL,0.00,0.00,'especes','payé',NULL,NULL,1,NULL,'2026-01-10 21:30:25','2026-01-10 21:30:25');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `types_bouteilles`
--

DROP TABLE IF EXISTS `types_bouteilles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `types_bouteilles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `taille` varchar(50) NOT NULL,
  `marque_id` bigint(20) unsigned NOT NULL,
  `prix_vente` decimal(10,2) NOT NULL,
  `prix_consigne` decimal(10,2) NOT NULL,
  `prix_recharge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prix_bouteille_vide` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Prix de rachat bouteille vide',
  `prix_retour_vide` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Remboursement au retour',
  `seuil_alerte` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `types_bouteilles_nom_marque_id_unique` (`nom`,`marque_id`),
  KEY `types_bouteilles_marque_id_foreign` (`marque_id`),
  CONSTRAINT `types_bouteilles_marque_id_foreign` FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_bouteilles`
--

LOCK TABLES `types_bouteilles` WRITE;
/*!40000 ALTER TABLE `types_bouteilles` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `types_bouteilles` VALUES
(1,'Total 6kg','6',1,13520.00,10400.00,1000.00,0.00,0.00,5,'2026-01-10 21:02:49','2026-01-10 21:02:49'),
(2,'Total 12.5kg','12.5',1,241200.00,17700.00,0.00,0.00,0.00,5,'2026-01-10 21:02:49','2026-01-10 21:02:49');
/*!40000 ALTER TABLE `types_bouteilles` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'admin','TAMBO SIMO Hedric','simohedric2023@gmail.com','admin',NULL,'$2y$12$SjQ1svSiDsH3G9st1P1L7uMQMdmW6G8a2NWKBuEzbbo/dZNlAlYW2',NULL,NULL,NULL,'actif',NULL,'$2y$12$tlwiwpNDu81miIyo0wkR9u2WTFh0LL0Kwh6pQUh8Kr12Joz3qrYd2','2026-01-10 21:02:49','2026-01-10 21:02:49'),
(2,'manager','Manager Test','manager@depotgaz.com','manager',NULL,'$2y$12$u78ieRIV4zcfmk6hzKHED.AXW4Jcj8.VLV34xoMfWy/gHyLUO5o4G',NULL,NULL,NULL,'actif',NULL,NULL,'2026-01-10 21:02:49','2026-01-10 21:02:49'),
(3,'vendeur','Vendeur Test','vendeur@depotgaz.com','vendeur',NULL,'$2y$12$65PkPYxpGew2uLRpioZnPuUWx2qHc0NLHAFEkOSXkFQ/IiltCAnOS',NULL,NULL,NULL,'actif',NULL,NULL,'2026-01-10 21:02:49','2026-01-10 21:02:49');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-01-11 15:00:30
