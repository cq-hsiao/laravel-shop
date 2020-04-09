-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: laravel-shop
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

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
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,1,'首页','fa-bar-chart','/',NULL,NULL,'2020-02-26 09:01:25'),(2,0,10,'系统管理','fa-tasks',NULL,NULL,NULL,'2020-04-09 11:06:42'),(3,2,11,'管理员','fa-users','auth/users',NULL,NULL,'2020-04-09 11:06:42'),(4,2,12,'角色','fa-user','auth/roles',NULL,NULL,'2020-04-09 11:06:42'),(5,2,13,'权限','fa-ban','auth/permissions',NULL,NULL,'2020-04-09 11:06:42'),(6,2,14,'菜单','fa-bars','auth/menu',NULL,NULL,'2020-04-09 11:06:42'),(7,2,15,'操作日志','fa-history','auth/logs',NULL,NULL,'2020-04-09 11:06:42'),(8,0,2,'用户管理','fa-users','/users',NULL,'2020-02-26 09:37:53','2020-02-28 07:52:17'),(9,0,4,'商品管理','fa-cubes','/products',NULL,'2020-02-28 07:52:07','2020-03-24 10:36:12'),(10,0,8,'订单管理','fa-cny','/orders',NULL,'2020-03-18 17:34:06','2020-04-09 11:06:42'),(11,0,9,'优惠券管理','fa-ticket','/coupon_codes',NULL,'2020-03-20 17:38:54','2020-04-09 11:06:42'),(12,0,3,'类目管理','fa-bars','/categories',NULL,'2020-03-24 10:35:46','2020-03-24 10:36:12'),(13,9,5,'普通商品','fa-cube','/products',NULL,'2020-04-01 18:06:15','2020-04-01 18:08:05'),(14,9,6,'众筹商品','fa-flag-checkered','/crowdfunding_products',NULL,'2020-04-01 18:08:00','2020-04-01 18:08:06'),(15,9,7,'秒杀商品','fa-flash','/seckill_products',NULL,'2020-04-09 11:06:36','2020-04-09 11:06:42');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'All permission','*','','*',NULL,NULL),(2,'Dashboard','dashboard','GET','/',NULL,NULL),(3,'Login','auth.login','','/auth/login\r\n/auth/logout',NULL,NULL),(4,'User setting','auth.setting','GET,PUT','/auth/setting',NULL,NULL),(5,'Auth management','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,NULL),(6,'用户管理','users','','/users*','2020-02-26 09:47:28','2020-02-26 09:47:28'),(7,'商品管理','products','','/products*','2020-03-21 16:20:37','2020-03-21 16:20:37'),(8,'优惠券管理','coupon_codes','','/coupon_codes*','2020-03-21 16:21:13','2020-03-21 16:21:13'),(9,'订单管理','orders','','/orders*','2020-03-21 16:21:27','2020-03-21 16:21:27');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (1,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL),(2,3,NULL,NULL),(2,4,NULL,NULL),(2,6,NULL,NULL),(2,7,NULL,NULL),(2,8,NULL,NULL),(2,9,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_users`
--

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2020-02-26 08:45:41','2020-02-26 08:45:41'),(2,'运营','operation','2020-02-26 09:50:44','2020-02-26 09:50:44');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_user_permissions`
--

LOCK TABLES `admin_user_permissions` WRITE;
/*!40000 ALTER TABLE `admin_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$0tVg4k.rYF5kIJlMNbcQoOafJBKmlOGvcPUcUU.nviww.GvCSS3o6','Administrator',NULL,'No8kSC1CjRyrVCCyEz50uc4uU70gh2XQiN6s8SLznTwvUWh6Avzmaly9gJm1','2020-02-26 08:45:41','2020-02-26 08:45:41'),(2,'operator','$2y$10$yPp0ZH5leMh79QiZeypynuS9rNq2WdANQzNmutR9sPItgesRWblMa','运营','images/wallhaven-6k2ogx.jpg','V3Y8AqTYOCPP65tlIObk6yRGrfpVUBovV9zB4yRPh9wKRKGwoVv9mvF9vRKO','2020-02-26 09:52:37','2020-03-21 16:24:48');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-09  3:23:07
