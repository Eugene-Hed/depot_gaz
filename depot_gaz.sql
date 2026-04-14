-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: depot_gaz
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `points_fidelite` int NOT NULL DEFAULT '0',
  `solde_credit` decimal(10,2) DEFAULT '0.00' COMMENT 'Montant à rembourser au client',
  `solde_dette` decimal(10,2) DEFAULT '0.00' COMMENT 'Montant à payer par le client',
  `statut` enum('actif','inactif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
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
INSERT INTO `clients` VALUES (1,'Tambo Simo Hedric','656774288',NULL,NULL,0,0.00,0.00,'actif','2026-01-10 21:29:40','2026-01-10 21:29:40');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fournisseur_id` bigint unsigned NOT NULL,
  `numero_commande` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_commande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_livraison_prevue` date DEFAULT NULL,
  `date_livraison_effective` timestamp NULL DEFAULT NULL COMMENT 'Date de réception réelle',
  `montant_ht` decimal(10,2) NOT NULL,
  `montant_taxes` decimal(10,2) DEFAULT '0.00',
  `cout_transport` decimal(10,2) DEFAULT '0.00',
  `montant_total` decimal(10,2) NOT NULL COMMENT 'HT + taxes + transport',
  `statut` enum('en_attente','validee','livree_partielle','livree','annulee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `administrateur_id` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_commande` (`numero_commande`),
  UNIQUE KEY `commandes_numero_commande_unique` (`numero_commande`),
  KEY `commandes_fournisseur_id_index` (`fournisseur_id`),
  KEY `commandes_administrateur_id_index` (`administrateur_id`),
  KEY `commandes_statut_index` (`statut`),
  KEY `commandes_date_commande_index` (`date_commande`),
  CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`),
  CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commandes`
--

LOCK TABLES `commandes` WRITE;
/*!40000 ALTER TABLE `commandes` DISABLE KEYS */;
/*!40000 ALTER TABLE `commandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_commandes`
--

DROP TABLE IF EXISTS `detail_commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_commandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `commande_id` bigint unsigned NOT NULL,
  `type_bouteille_id` bigint unsigned NOT NULL,
  `quantite_commandee` int NOT NULL,
  `quantite_livree` int DEFAULT '0',
  `quantite_restante` int GENERATED ALWAYS AS ((`quantite_commandee` - `quantite_livree`)) STORED COMMENT 'Calculé automatiquement',
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_ligne` decimal(10,2) NOT NULL,
  `statut_ligne` enum('en_attente','livree_partielle','livree','annulee') COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_commandes_commande_id_index` (`commande_id`),
  KEY `detail_commandes_type_bouteille_id_index` (`type_bouteille_id`),
  CONSTRAINT `detail_commandes_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_commandes_ibfk_2` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_commandes`
--

LOCK TABLES `detail_commandes` WRITE;
/*!40000 ALTER TABLE `detail_commandes` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_commandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fournisseurs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_fournisseur` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_fonction` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conditions_paiement` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delai_livraison` int DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('actif','inactif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_fournisseur` (`code_fournisseur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fournisseurs`
--

LOCK TABLES `fournisseurs` WRITE;
/*!40000 ALTER TABLE `fournisseurs` DISABLE KEYS */;
/*!40000 ALTER TABLE `fournisseurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historique_prix`
--

DROP TABLE IF EXISTS `historique_prix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historique_prix` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type_bouteille_id` bigint unsigned NOT NULL,
  `type_prix` enum('vente','consigne','recharge','retour_vide') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Quel prix?',
  `ancien_prix` decimal(10,2) NOT NULL,
  `nouveau_prix` decimal(10,2) NOT NULL,
  `raison` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Pourquoi le changement',
  `date_effet` date DEFAULT NULL COMMENT 'Quand applicable',
  `administrateur_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historique_prix_type_bouteille_id_index` (`type_bouteille_id`),
  KEY `historique_prix_administrateur_id_index` (`administrateur_id`),
  KEY `historique_prix_created_at_index` (`created_at`),
  KEY `historique_prix_date_effet_index` (`date_effet`),
  CONSTRAINT `historique_prix_ibfk_1` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historique_prix_ibfk_2` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historique_prix`
--

LOCK TABLES `historique_prix` WRITE;
/*!40000 ALTER TABLE `historique_prix` DISABLE KEYS */;
/*!40000 ALTER TABLE `historique_prix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marques`
--

DROP TABLE IF EXISTS `marques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marques` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('actif','inactif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marques`
--

LOCK TABLES `marques` WRITE;
/*!40000 ALTER TABLE `marques` DISABLE KEYS */;
INSERT INTO `marques` VALUES (1,'TotalEnergies','actif','A la maison ou à l\'extérieur, le GPL - gaz de pétrole liquéfié, est là pour répondre à tous vos besoins énergétiques.','1776170217_Logo_TotalEnergies.svg','2026-01-10 21:02:49','2026-04-14 11:36:57'),(2,'SCTM','actif',NULL,'1776170264_sctm_logo.png','2026-01-10 21:57:36','2026-04-14 11:37:44'),(3,'TRADEX','actif',NULL,'1776170816_tradex.png','2026-04-14 11:26:33','2026-04-14 11:46:56'),(4,'BOCOM','actif',NULL,'1776170831_bocom.png','2026-04-14 11:26:42','2026-04-14 11:47:11'),(5,'Ola','actif',NULL,'1776170846_Ola.png','2026-04-14 11:27:06','2026-04-14 11:47:26'),(6,'Afri GAz','actif',NULL,'1776170862_afriquia_gaz_logo.jpeg','2026-04-14 11:27:21','2026-04-14 11:47:42'),(7,'Pleingaz','actif',NULL,'1776170880_pleingaz.png','2026-04-14 11:28:06','2026-04-14 11:48:00'),(8,'Oilibya','actif',NULL,'1776170900_oilibya-logo.png','2026-04-14 11:28:30','2026-04-14 11:48:20'),(9,'Glocal','actif',NULL,'1776170915_globalgazl.png','2026-04-14 11:28:57','2026-04-14 11:48:35'),(10,'Green oil','actif',NULL,'1776170930_greenOil.jpeg','2026-04-14 11:29:20','2026-04-14 11:48:50'),(11,'Tankoil','actif',NULL,'1776170948_tankoil_logo.png','2026-04-14 11:30:18','2026-04-14 11:49:08'),(12,'StarGas','actif',NULL,'1776170961_stargas.png','2026-04-14 11:31:08','2026-04-14 11:49:21'),(13,'Camgaz','actif',NULL,'1776170977_camgaz.jpg','2026-04-14 11:31:32','2026-04-14 11:49:37'),(14,'Mrs','actif',NULL,'1776170996_mrs.jpg','2026-04-14 11:31:51','2026-04-14 11:49:56');
/*!40000 ALTER TABLE `marques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_01_22_161334_add_image_to_marques_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mouvements_stocks`
--

DROP TABLE IF EXISTS `mouvements_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mouvements_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` bigint unsigned NOT NULL,
  `type_mouvement` enum('entree','sortie','ajustement','casse') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite_pleine` int NOT NULL,
  `quantite_vide` int NOT NULL,
  `commentaire` text COLLATE utf8mb4_unicode_ci,
  `motif` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `administrateur_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mouvements_stocks_stock_id_index` (`stock_id`),
  KEY `mouvements_stocks_administrateur_id_index` (`administrateur_id`),
  KEY `mouvements_stocks_created_at_index` (`created_at`),
  CONSTRAINT `mouvements_stocks_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mouvements_stocks_ibfk_2` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mouvements_stocks`
--

LOCK TABLES `mouvements_stocks` WRITE;
/*!40000 ALTER TABLE `mouvements_stocks` DISABLE KEYS */;
INSERT INTO `mouvements_stocks` VALUES (1,3,'entree',20,0,NULL,'Achat auprès des fournisseur',1,'2026-04-14 11:02:57','2026-04-14 11:02:57'),(4,3,'sortie',1,0,NULL,'Vente/Échange (echange_simple)',1,'2026-04-14 11:11:06','2026-04-14 11:11:06'),(5,3,'entree',0,1,NULL,'Échange (Retour vide)',1,'2026-04-14 11:11:06','2026-04-14 11:11:06'),(6,3,'sortie',1,0,NULL,'Vente/Échange (echange_simple)',1,'2026-04-14 13:26:45','2026-04-14 13:26:45'),(7,3,'entree',0,1,NULL,'Échange (Retour vide)',1,'2026-04-14 13:26:45','2026-04-14 13:26:45');
/*!40000 ALTER TABLE `mouvements_stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('alerte_stock','retard_consigne','modification_prix','commande_livree','retour_impaye','retard_paiement') COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priorite` enum('basse','normale','haute','critique') COLLATE utf8mb4_unicode_ci DEFAULT 'normale',
  `entite_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Exemple: transaction, commande, stock',
  `entite_id` bigint unsigned DEFAULT NULL,
  `administrateur_id` bigint unsigned DEFAULT NULL,
  `statut` enum('non_lu','lu','archivee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'non_lu',
  `date_expiration` timestamp NULL DEFAULT NULL COMMENT 'Quand supprimer la notification',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_administrateur_id_index` (`administrateur_id`),
  KEY `notifications_statut_index` (`statut`),
  KEY `notifications_priorite_index` (`priorite`),
  KEY `notifications_type_index` (`type`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paiements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `montant_paye` decimal(10,2) NOT NULL,
  `mode_paiement` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_cheque` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro du chèque',
  `reference_virement` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Référence virement',
  `reference_carte` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Derniers chiffres carte',
  `statut` enum('en_attente','confirmé','failed','annulé') COLLATE utf8mb4_unicode_ci DEFAULT 'confirmé',
  `administrateur_id` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `date_paiement` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paiements_transaction_id_index` (`transaction_id`),
  KEY `paiements_client_id_index` (`client_id`),
  KEY `paiements_date_paiement_index` (`date_paiement`),
  KEY `administrateur_id` (`administrateur_id`),
  CONSTRAINT `paiements_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `paiements_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `paiements_ibfk_3` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paiements`
--

LOCK TABLES `paiements` WRITE;
/*!40000 ALTER TABLE `paiements` DISABLE KEYS */;
/*!40000 ALTER TABLE `paiements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('gOrvl7hrAyMRQ3VXWLSzvbIBghNl0s3sKhxqdXSp',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTU1TQXBWUVk2MWJaSWpPN0tqdHMxR0dZbEpFdXhYUndNWGROeDVSYyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1776177634);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type_bouteille_id` bigint unsigned NOT NULL,
  `quantite_pleine` int NOT NULL DEFAULT '0',
  `quantite_vide` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_bouteille_id` (`type_bouteille_id`),
  UNIQUE KEY `stocks_type_bouteille_id_unique` (`type_bouteille_id`),
  CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` VALUES (3,4,18,2,'2026-04-14 11:02:56','2026-04-14 13:26:45');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_transaction` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('vente','echange','consigne','retour','recharge','echange_simple','echange_type','achat_simple','echange_differe') COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `type_bouteille_id` bigint unsigned NOT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  `quantite_vides_retournees` int DEFAULT '0' COMMENT 'Pour échange/retour',
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `consigne_montant` decimal(10,2) DEFAULT NULL COMMENT 'Montant de la consigne',
  `montant_reduction` decimal(10,2) DEFAULT '0.00',
  `montant_net` decimal(10,2) NOT NULL COMMENT 'Après réduction',
  `mode_paiement` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut_paiement` enum('en_attente','payé','remboursé','partiellement_payé') COLLATE utf8mb4_unicode_ci DEFAULT 'payé',
  `transaction_parent_id` bigint unsigned DEFAULT NULL COMMENT 'Lien consigne initiale',
  `date_limite_retour` date DEFAULT NULL COMMENT 'Pour consigne/retour',
  `administrateur_id` bigint unsigned NOT NULL,
  `commentaire` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_transaction` (`numero_transaction`),
  UNIQUE KEY `transactions_numero_transaction_unique` (`numero_transaction`),
  KEY `transactions_type_bouteille_id_foreign` (`type_bouteille_id`),
  KEY `transactions_client_id_index` (`client_id`),
  KEY `transactions_administrateur_id_index` (`administrateur_id`),
  KEY `transactions_type_index` (`type`),
  KEY `transactions_created_at_index` (`created_at`),
  KEY `transactions_statut_paiement_index` (`statut_paiement`),
  KEY `transaction_parent_id` (`transaction_parent_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`type_bouteille_id`) REFERENCES `types_bouteilles` (`id`),
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`administrateur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`transaction_parent_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'TRX-20260414121106-9335','echange_simple',NULL,4,1,0,6500.00,6500.00,0.00,0.00,6500.00,'especes','payé',NULL,NULL,1,NULL,'2026-04-14 11:11:06','2026-04-14 11:11:06'),(2,'TRX-20260414142645-3358','echange_simple',NULL,4,1,0,6500.00,6500.00,0.00,0.00,6500.00,'especes','payé',NULL,NULL,1,NULL,'2026-04-14 13:26:45','2026-04-14 13:26:45');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_bouteilles`
--

DROP TABLE IF EXISTS `types_bouteilles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `types_bouteilles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taille` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marque_id` bigint unsigned NOT NULL,
  `prix_vente` decimal(10,2) NOT NULL,
  `prix_consigne` decimal(10,2) NOT NULL,
  `prix_recharge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prix_bouteille_vide` decimal(10,2) DEFAULT '0.00' COMMENT 'Prix de rachat bouteille vide',
  `prix_retour_vide` decimal(10,2) DEFAULT '0.00' COMMENT 'Remboursement au retour',
  `seuil_alerte` int NOT NULL DEFAULT '5',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('actif','inactif') COLLATE utf8mb4_unicode_ci DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `types_bouteilles_nom_marque_id_unique` (`nom`,`marque_id`),
  KEY `types_bouteilles_marque_id_foreign` (`marque_id`),
  CONSTRAINT `types_bouteilles_ibfk_1` FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_bouteilles`
--

LOCK TABLES `types_bouteilles` WRITE;
/*!40000 ALTER TABLE `types_bouteilles` DISABLE KEYS */;
INSERT INTO `types_bouteilles` VALUES (4,'TotalEnergies 12,5kg','12,5',1,24160.00,17660.00,6500.00,0.00,0.00,10,'1776168006_total-gaz-12-5kg-81-0.jpeg','actif','2026-04-14 11:00:06','2026-04-14 11:00:06'),(5,'TotalEnergies 6kg','6',1,13520.00,10400.00,3120.00,0.00,0.00,5,'1776171127_total-gaz-6-kg.jpg','actif','2026-04-14 11:52:07','2026-04-14 11:52:07'),(6,'TotalEnergies 35kg','35',1,63200.00,45000.00,18200.00,0.00,0.00,5,'1776171237_35kg.png','actif','2026-04-14 11:53:57','2026-04-14 11:53:57'),(7,'SCTM 12,5kg','12,5',2,24500.00,18000.00,6500.00,0.00,0.00,5,'1776171423_sctm-12-5.png','actif','2026-04-14 11:57:03','2026-04-14 11:59:17'),(8,'SCTM 6kg','6',2,15000.00,12000.00,3000.00,0.00,0.00,4,'1776171496_sctm-6-kg.jpg','actif','2026-04-14 11:58:16','2026-04-14 11:58:52'),(9,'TRADEX 2,75kg','2,75',3,11500.00,10000.00,1500.00,0.00,0.00,5,NULL,'actif','2026-04-14 12:01:54','2026-04-14 12:01:54'),(10,'TRADEX 12,5kg','12,5',3,26500.00,20000.00,6500.00,0.00,0.00,4,'1776171808_tradex-12kg.jpeg','actif','2026-04-14 12:03:28','2026-04-14 12:03:28'),(11,'BOCOM 6kg','6',4,18200.00,15000.00,3200.00,0.00,0.00,5,'1776171961_bocom-6kg.jpeg','actif','2026-04-14 12:06:01','2026-04-14 12:06:01'),(12,'BOCOM 12,5kg','12,5',4,25000.00,18500.00,6500.00,0.00,0.00,5,'1776172060_bocom-12kg.jpg','actif','2026-04-14 12:07:40','2026-04-14 12:07:40'),(13,'Ola 6kg','6',5,15000.00,12000.00,3000.00,0.00,0.00,5,'1776172156_ola-6kg.jpeg','actif','2026-04-14 12:09:16','2026-04-14 12:09:16'),(14,'Ola 12,5kg','12,5',5,24500.00,18000.00,6500.00,0.00,0.00,4,'1776172239_ola-12kg.jpg','actif','2026-04-14 12:10:39','2026-04-14 12:10:39'),(15,'Afri GAz 6kg','6',6,15000.00,12000.00,3000.00,0.00,0.00,5,NULL,'actif','2026-04-14 12:12:07','2026-04-14 12:12:07'),(16,'Afri GAz 12,5kg','12,5',6,24500.00,18000.00,6500.00,0.00,0.00,4,'1776172369_afrigaz-12kg.jpg','actif','2026-04-14 12:12:49','2026-04-14 12:12:49'),(17,'Pleingaz 12,5kg','12,5',7,24500.00,18000.00,6500.00,0.00,0.00,4,'1776172558_pleingaz-12kg.png','actif','2026-04-14 12:15:58','2026-04-14 12:15:58'),(18,'Pleingaz 6kg','6',7,15000.00,12000.00,3000.00,0.00,0.00,5,'1776172590_pleinGaz-6kg.png','actif','2026-04-14 12:16:30','2026-04-14 12:16:30'),(19,'Oilibya 6kg','6',8,18200.00,15000.00,3200.00,0.00,0.00,5,NULL,'actif','2026-04-14 12:18:33','2026-04-14 12:18:33'),(20,'Oilibya 12,5kg','12,5',8,41500.00,35000.00,6500.00,0.00,0.00,5,'1776172764_oilibya-12kg.jpg','actif','2026-04-14 12:19:24','2026-04-14 12:19:24'),(21,'Glocal 12,5kg','12,5',9,41500.00,35000.00,6500.00,0.00,0.00,5,'1776172874_glocal-gaz-12kg.jpg','actif','2026-04-14 12:21:14','2026-04-14 12:21:14'),(22,'Glocal 6kg','6',9,18200.00,15000.00,3200.00,0.00,0.00,5,'1776172909_glocal-gaz-6kg.jpg','actif','2026-04-14 12:21:49','2026-04-14 12:21:49'),(23,'Green oil 12,5kg','12,5',10,41500.00,35000.00,6500.00,0.00,0.00,5,'1776173074_green-oil-12kg.png','actif','2026-04-14 12:24:34','2026-04-14 12:24:34'),(24,'Green oil 6kg','6',10,18200.00,15000.00,3200.00,0.00,0.00,5,NULL,'actif','2026-04-14 12:26:58','2026-04-14 12:26:58'),(25,'Tankoil 12,5kg','12,5',11,41500.00,35000.00,6500.00,0.00,0.00,5,'1776173309_tank-oil-12kg.png','actif','2026-04-14 12:28:29','2026-04-14 12:28:29'),(26,'StarGas 12,5kg','12,5',12,41500.00,35000.00,6500.00,0.00,0.00,4,'1776173394_stargas-12kg.png','actif','2026-04-14 12:29:54','2026-04-14 12:29:54'),(27,'Camgaz 6kg','6',13,18200.00,15000.00,3200.00,0.00,0.00,5,'1776173475_camgaz-6kg.jpg','actif','2026-04-14 12:31:15','2026-04-14 12:31:15'),(28,'Camgaz 12,5kg','12,5',13,41500.00,35000.00,6500.00,0.00,0.00,5,'1776173498_camgaz-12-5kg.png','actif','2026-04-14 12:31:38','2026-04-14 12:31:38'),(29,'Mrs 6kg','6',14,18200.00,15000.00,3200.00,0.00,0.00,5,NULL,'actif','2026-04-14 12:32:48','2026-04-14 12:32:48'),(30,'Mrs 12,5kg','12,5',14,41500.00,35000.00,6500.00,0.00,0.00,5,'1776173605_mrs-gaz-12-5kg.png','actif','2026-04-14 12:33:25','2026-04-14 12:33:25');
/*!40000 ALTER TABLE `types_bouteilles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_complet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','manager','vendeur') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'vendeur',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `statut` enum('actif','inactif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `dernier_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','TAMBO SIMO Hedric','simohedric2023@gmail.com','admin',NULL,'$2y$12$39hxIrYLov/yNipQSjK5A.0G8K5lOE3kEDq0vMiA9LtKi.AapNK8q',NULL,NULL,NULL,'actif',NULL,NULL,'2026-01-10 21:02:49','2026-01-10 21:02:49'),(2,'manager','Manager Test','manager@depotgaz.com','manager',NULL,'$2y$12$u78ieRIV4zcfmk6hzKHED.AXW4Jcj8.VLV34xoMfWy/gHyLUO5o4G',NULL,NULL,NULL,'actif',NULL,NULL,'2026-01-10 21:02:49','2026-01-10 21:02:49'),(3,'vendeur','Vendeur Test','vendeur@depotgaz.com','vendeur',NULL,'$2y$12$65PkPYxpGew2uLRpioZnPuUWx2qHc0NLHAFEkOSXkFQ/IiltCAnOS',NULL,NULL,NULL,'actif',NULL,NULL,'2026-01-10 21:02:49','2026-01-10 21:02:49');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-14 15:42:05
