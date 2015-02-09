-- MySQL dump 10.15  Distrib 10.0.15-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: reserver
-- ------------------------------------------------------
-- Server version	10.0.15-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `access_groups`
--

DROP TABLE IF EXISTS `access_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access_groups` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `can_reserve` int(1) NOT NULL,
  `can_request` int(1) NOT NULL,
  `can_allow_requests` int(1) NOT NULL,
  `can_change_items` int(1) NOT NULL,
  `can_change_users` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_groups`
--

LOCK TABLES `access_groups` WRITE;
/*!40000 ALTER TABLE `access_groups` DISABLE KEYS */;
INSERT INTO `access_groups` VALUES (1,'student',0,1,0,0,0),(2,'teacher',1,0,1,0,0),(3,'admin',1,1,1,1,1);
/*!40000 ALTER TABLE `access_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `count` int(1) NOT NULL,
  `available_count` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (3,'Raspberry Pi 2 Model B','The Raspberry Pi 2 Model B is the second generation Raspberry Pi. It replaced the original Raspberry Pi 1 Model B+ in February 2015.','',12,12),(4,'Ooculus Rift','The Rift is an upcoming virtual reality head-mounted display, being developed by Oculus VR.','',5,0),(5,' Arduino','Arduino is an open-source computer hardware and software company, project and user community that designs and manufactures kits for building digital devices and interactive objects that can sense and control the physical world.','',20,4),(7,'Kiwifruit','The kiwifruit or Chinese gooseberry (sometimes shortened to kiwi outside New Zealand), is the edible berry of a woody vine in the genus Actinidia.','',44,44);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `item_id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `reserved_at` date NOT NULL,
  `reserved_from` date NOT NULL,
  `reserved_until` date NOT NULL,
  `count` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue`
--

LOCK TABLES `queue` WRITE;
/*!40000 ALTER TABLE `queue` DISABLE KEYS */;
INSERT INTO `queue` VALUES (1,3,35,'2015-02-03','2015-02-09','2015-02-10',3);
/*!40000 ALTER TABLE `queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requests` (
  `id` int(1) NOT NULL,
  `item_id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `requested_at` date NOT NULL,
  `reserve_from` date NOT NULL,
  `reserve_until` date NOT NULL,
  `status` text NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reserved_items`
--

DROP TABLE IF EXISTS `reserved_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reserved_items` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `item_id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `reserved_at` date NOT NULL,
  `reserved_from` date NOT NULL,
  `reserved_until` date NOT NULL,
  `returned` int(1) NOT NULL,
  `returned_at` date NOT NULL,
  `count` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserved_items`
--

LOCK TABLES `reserved_items` WRITE;
/*!40000 ALTER TABLE `reserved_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserved_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `access_group` varchar(255) NOT NULL,
  `send_reminders` int(1) DEFAULT NULL,
  `session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (35,'soud','$2y$10$w9Hbyq.v0SWZRE/rOXtapeld80oNkdGYAUanUWauPQP7JN7WUuzTi','a@a.com','Steven Oud','admin',1,'2d80ef6b82cf3ab06d3c32b64c28c27a');
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

-- Dump completed on 2015-02-09 12:25:39
