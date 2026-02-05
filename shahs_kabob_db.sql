CREATE DATABASE  IF NOT EXISTS `shahs_kabob_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `shahs_kabob_db`;
-- MySQL dump 10.13  Distrib 8.0.43, for macos15 (x86_64)
--
-- Host: localhost    Database: shahs_kabob_db
-- ------------------------------------------------------
-- Server version	9.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `store_id` int NOT NULL,
  `vendor_id` int DEFAULT NULL,
  `order_details` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`),
  KEY `fk_order_vendor` (`vendor_id`),
  CONSTRAINT `fk_order_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (7,5,1,1,'{\"1\":\"12\"}','2026-02-04 18:17:51'),(8,5,1,2,'{\"2\":\"12\",\"3\":\"12\"}','2026-02-04 18:39:54');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Rice','tool','12kl','2026-01-30 16:32:32',1),(2,2,'1503 5x5 Shahs of Kabob','Linen','240537_Kabob','2026-02-01 19:44:41',1),(3,2,'3X5 Mat, Black','Linen','2010-BK','2026-02-01 19:44:41',1),(4,3,'12Z Can 24P Coke','NA Beverage','100722','2026-02-01 19:44:41',1),(5,3,'12Z Can 24P Dt Coke','NA Beverage','100724','2026-02-01 19:44:41',1),(6,4,'Bean Garbanzo','Grocery and Dry Goods','118753','2026-02-01 19:44:41',1),(7,4,'Butter Cup 720-5gm','Dairy','272001','2026-02-01 19:44:41',1),(9,6,'Almas Kashk Bulk 10lb','Grocery and Dry Goods','41412','2026-02-01 19:44:46',1),(10,7,'Cucumber Select 1 1/9 BU','Produce','20415','2026-02-01 19:44:46',1),(11,8,'Avocado Green Hass- 48 Ct','Produce','81755','2026-02-01 19:44:46',1),(12,11,'12/14oz Clear Cold Cup 98mm Rim','Paper Goods','CHC149850- SP-12C','2026-02-01 19:44:46',1),(13,11,'12X12 Flat Dry Wax White Paper 6/1000','Paper Goods','F-12*','2026-02-01 19:44:46',1),(14,11,'Heavy Duty Nitrile Gloves Large','Paper Goods','75034','2026-02-01 19:44:46',1),(16,9,'Afg - Fsh Fine Grind 73/27 Halal','Meat','118651','2026-02-01 19:45:49',1),(17,9,'Fsh Chkn Tenders Jumbo Halal','Poultry','111709429','2026-02-01 19:45:49',1),(18,9,'Gorina - Frz Chkn Tenders Jumbo Halal','Meat','111723941','2026-02-01 19:45:49',1),(19,10,'Dicer 3/8In Gs','Kitchen Utensils and Supplies','6.74651E+11','2026-02-01 19:45:49',1),(20,10,'Pd Tomato 5X6 25LB','Produce','020600427601_5X6','2026-02-01 19:45:49',1),(21,11,'12X12 Flat Dry Wax White Paper 6/1000','Paper Goods','F-12*','2026-02-01 19:45:49',1),(22,11,'15X15 Flat Dry Wax White Paper 3/1000','Paper Goods','F-15-15','2026-02-01 19:45:49',1),(23,11,'24OZ Square Box/ Cb 24','Paper Goods','SP-SBX24','2026-02-01 19:45:49',1),(24,11,'32OZ Square Box/ CB32','Paper Goods','SP-SBX32_32OZ','2026-02-01 19:45:49',1),(25,11,'4 In 1 Wrapped Pp Heavy Duty Set','Paper Goods','41HWPH50B','2026-02-01 19:45:49',1),(26,11,'LD Liners 40x46 1.5mil Black 100 CT','Cleaning Supplies','EC404612K-LD404615BL','2026-02-01 19:45:49',1),(27,11,'Thermal Pos Paper Roll 3','Office Supplies','RR8313-Thermal','2026-02-01 19:45:49',1),(28,11,'T-Shirt Bag 1000 Pcs','Paper Goods','THW1A (THANK)','2026-02-01 19:45:49',1),(29,12,'Dubai Chocolate Cookie','Grocery and Dry Goods','','2026-02-01 19:45:49',1),(30,12,'Shahs Baklava Cheesecake','Grocery and Dry Goods','','2026-02-01 19:45:49',1),(31,12,'Shahs Chocolate Chunks','Grocery and Dry Goods','','2026-02-01 19:45:49',1),(32,12,'Shahs Creme Brulee','Grocery and Dry Goods','','2026-02-01 19:45:49',1),(33,12,'Shahs Pavlovas','Grocery and Dry Goods','','2026-02-01 19:45:49',1);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_settings`
--

DROP TABLE IF EXISTS `purchase_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL,
  `frequency` enum('per_week','bi_weekly','per_month') DEFAULT 'per_month',
  `purchase_day` varchar(255) NOT NULL,
  `end_date` date DEFAULT NULL,
  `notes` text,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_id` (`vendor_id`),
  CONSTRAINT `purchase_settings_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_settings`
--

LOCK TABLES `purchase_settings` WRITE;
/*!40000 ALTER TABLE `purchase_settings` DISABLE KEYS */;
INSERT INTO `purchase_settings` VALUES (6,1,'per_week','Wednesday',NULL,'','2026-02-04 18:20:49'),(8,2,'bi_weekly','Monday,Friday',NULL,'','2026-02-04 18:31:16');
/*!40000 ALTER TABLE `purchase_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
INSERT INTO `stores` VALUES (1,'Shahs of Kabob - Boca Raton','Boca Raton, FL'),(2,'Shahs of Kabob - Ft. Lauderdale','Ft. Lauderdale, FL'),(3,'Shahs of Kabob - Hallandale Beach','Hallandale Beach, FL'),(4,'Shahs of Kabob - Wynwood','Wynwood, Miami, FL'),(5,'Shahs of Kabob - Brickell','Brickell, Miami, FL'),(6,'Shahs of Kabob - Cooper City / Davie','Cooper City/Davie, FL'),(7,'Shahs of Kabob - Coral Gables','Coral Gables, FL'),(8,'Shahs of Kabob - Kendall','Kendall, FL'),(9,'Shahs of Kabob - Miami Lakes','Miami Lakes, FL'),(10,'Shahs of Kabob - Palmetto Bay','Palmetto Bay, FL'),(11,'Shahs of Kabob - South Miami','South Miami, FL');
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_stores`
--

DROP TABLE IF EXISTS `user_stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_stores` (
  `user_id` int NOT NULL,
  `store_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`store_id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `user_stores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_stores_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_stores`
--

LOCK TABLES `user_stores` WRITE;
/*!40000 ALTER TABLE `user_stores` DISABLE KEYS */;
INSERT INTO `user_stores` VALUES (5,1);
/*!40000 ALTER TABLE `user_stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Jayson Rojas','admin@shahs.com','$2y$12$s6JYlU7bMgMtw5KpQOpmmOGKTPALBTxgErSePXtTHg50LNtQXhFlm','admin','2026-01-29 22:35:10'),(5,'Amin E','correo@correo.com','$2y$12$GGnVpTsnkkrTdLX4pXU.IukZ9tiKit/lsQLX.PAF7ALUrpTUG6D6K','user','2026-02-01 20:41:04');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (1,'test','','active'),(2,'ALSCO','Linen and cleaning supplies provider','active'),(3,'Coca-Cola Beverages Florida LLC','Beverage distributor','active'),(4,'Gordon Food Service','Main food and dry goods supplier','active'),(5,'Hialeah Products Co.','Produce and grocery specialist','active'),(6,'Lida','Dairy and specialty dry goods','active'),(7,'Mr. Greens','Fresh produce provider','active'),(8,'Produce Partners','Fresh vegetable and fruit supplier','active'),(9,'Quirch Foods LLC','Meat and poultry distributor','active'),(10,'Restaurant Depot / Jetro','Wholesale restaurant supplies','active'),(11,'Simply Pak LLC','Packaging and paper goods','active'),(12,'Sweet Manifesto','Dessert and bakery supplier','active');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-05 12:47:03
