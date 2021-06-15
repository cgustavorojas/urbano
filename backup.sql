-- MySQL dump 10.13  Distrib 8.0.25, for Linux (x86_64)
--
-- Host: localhost    Database: urbano
-- ------------------------------------------------------
-- Server version	8.0.25-0ubuntu0.20.04.1

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
-- Table structure for table `cliente_cliente`
--

DROP TABLE IF EXISTS `cliente_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `id_grupo` int DEFAULT NULL,
  `obs` text,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `cliente_cliente_UN` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_cliente`
--

LOCK TABLES `cliente_cliente` WRITE;
/*!40000 ALTER TABLE `cliente_cliente` DISABLE KEYS */;
INSERT INTO `cliente_cliente` VALUES (1,'nombre 1','apellido 1','ape1@gmail.com',4,'añsdlkjfñldk'),(3,'nombre 2','ape 2','mail2@gmail.com',6,'añsldkfjasñdlkf'),(4,'nom 3','ape 3','mail3@hotmail.com',4,'añlsdkjfañsdlkfj\r\n\r\nsdfsd'),(5,'nom 4','ape 4','mail4@gmail.com',5,'sdsd'),(7,'ñlkj','ñlkj','pepe@river2.com',4,'ñsldfjsd'),(8,'pepe','pepe','pepe@river.com',4,'sds'),(9,'prueba','pp','rojas@yahoo.com',6,'sdfajsdfñlk'),(10,'asd','asdf','cgustavorojas@gmail.com',6,'sdfsd'),(11,'erter','erter','cramirez@hotmail.com',6,'dgdf');
/*!40000 ALTER TABLE `cliente_cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_grupo`
--

DROP TABLE IF EXISTS `cliente_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_grupo` (
  `id_grupo` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_grupo`),
  UNIQUE KEY `cliente_grupo_UN` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_grupo`
--

LOCK TABLES `cliente_grupo` WRITE;
/*!40000 ALTER TABLE `cliente_grupo` DISABLE KEYS */;
INSERT INTO `cliente_grupo` VALUES (4,'Grupo 1'),(5,'Grupo 2'),(7,'Grupo 4');
/*!40000 ALTER TABLE `cliente_grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_archivo`
--

DROP TABLE IF EXISTS `gral_archivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_archivo` (
  `id_archivo` int NOT NULL,
  `tabla` varchar(50) NOT NULL,
  `pk` int NOT NULL,
  `f_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archivo` varchar(120) NOT NULL,
  `txt` varchar(120) DEFAULT NULL,
  `nota` longtext,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_archivo`),
  KEY `ix_archivo__pk` (`tabla`,`pk`,`f_alta`,`id_archivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_archivo`
--

LOCK TABLES `gral_archivo` WRITE;
/*!40000 ALTER TABLE `gral_archivo` DISABLE KEYS */;
INSERT INTO `gral_archivo` VALUES (1,'mecenazgo_proyecto',100121,'2017-11-19 18:39:59','Proyectos Aprobados V2.xls','pppp',NULL,949),(4,'mecenazgo_proyecto',100119,'2017-11-20 11:42:10','fcbapco.xls','jmmn',NULL,949),(5,'mecenazgo_proyecto',100122,'2017-11-20 11:44:38','proyectos presentados por disciplina.xls','ddd',NULL,949),(6,'mecenazgo_proyecto',100122,'2017-11-20 11:45:29','proyectos presentados por disciplina.xls','hghg',NULL,949);
/*!40000 ALTER TABLE `gral_archivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_codificador`
--

DROP TABLE IF EXISTS `gral_codificador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_codificador` (
  `id_codificador` int NOT NULL AUTO_INCREMENT,
  `id_sistema` varchar(60) NOT NULL,
  `esquema` varchar(60) NOT NULL,
  `tabla` varchar(60) NOT NULL,
  `txt` varchar(100) NOT NULL,
  `allow_insert` bit(1) NOT NULL DEFAULT b'1',
  `allow_update` bit(1) NOT NULL DEFAULT b'1',
  `allow_delete` bit(1) NOT NULL DEFAULT b'1',
  `key_col` varchar(60) NOT NULL,
  `key_type` varchar(30) NOT NULL,
  `key_length` int DEFAULT NULL,
  `txt_col` varchar(60) NOT NULL,
  `txt_length` int NOT NULL,
  `flag_ro_col` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id_codificador`),
  KEY `FK_gral_codificador_gral_sistema` (`id_sistema`),
  CONSTRAINT `FK_gral_codificador_gral_sistema` FOREIGN KEY (`id_sistema`) REFERENCES `gral_sistema` (`id_sistema`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_codificador`
--

LOCK TABLES `gral_codificador` WRITE;
/*!40000 ALTER TABLE `gral_codificador` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_codificador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_config`
--

DROP TABLE IF EXISTS `gral_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_config` (
  `id_config` varchar(100) NOT NULL,
  `id_sistema` varchar(100) NOT NULL,
  `valor` varchar(100) DEFAULT NULL,
  `txt` varchar(100) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `minimo` int DEFAULT NULL,
  `maximo` int DEFAULT NULL,
  `maxlength` int DEFAULT '0',
  PRIMARY KEY (`id_config`),
  KEY `FK_gral_config_gral_sistema` (`id_sistema`),
  CONSTRAINT `FK_gral_config_gral_sistema` FOREIGN KEY (`id_sistema`) REFERENCES `gral_sistema` (`id_sistema`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_config`
--

LOCK TABLES `gral_config` WRITE;
/*!40000 ALTER TABLE `gral_config` DISABLE KEYS */;
INSERT INTO `gral_config` VALUES ('gral.screen.titulo','GRAL','Urbano','Urbano','string',NULL,NULL,100);
/*!40000 ALTER TABLE `gral_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_direccion`
--

DROP TABLE IF EXISTS `gral_direccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_direccion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `calle` varchar(255) NOT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `piso` varchar(30) DEFAULT NULL,
  `departamento` varchar(20) DEFAULT NULL,
  `id_ciudad` int DEFAULT NULL,
  `codigo_postal` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_direccion`
--

LOCK TABLES `gral_direccion` WRITE;
/*!40000 ALTER TABLE `gral_direccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_direccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_estado_import`
--

DROP TABLE IF EXISTS `gral_estado_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_estado_import` (
  `estado` varchar(10) NOT NULL,
  `txt` varchar(100) NOT NULL,
  PRIMARY KEY (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_estado_import`
--

LOCK TABLES `gral_estado_import` WRITE;
/*!40000 ALTER TABLE `gral_estado_import` DISABLE KEYS */;
INSERT INTO `gral_estado_import` VALUES ('E','Error'),('P','Procesado'),('R','Recibido');
/*!40000 ALTER TABLE `gral_estado_import` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_help`
--

DROP TABLE IF EXISTS `gral_help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_help` (
  `id_help` varchar(60) NOT NULL,
  `id_sistema` varchar(60) NOT NULL,
  `titulo` varchar(120) DEFAULT NULL,
  `html` longtext,
  `f_alta` datetime NOT NULL,
  `f_update` datetime NOT NULL,
  `orden` int DEFAULT NULL,
  PRIMARY KEY (`id_help`),
  KEY `ix_help__titulo` (`titulo`),
  KEY `ix_help__sistema` (`id_sistema`,`titulo`),
  CONSTRAINT `FK_gral_help_gral_sistema` FOREIGN KEY (`id_sistema`) REFERENCES `gral_sistema` (`id_sistema`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_help`
--

LOCK TABLES `gral_help` WRITE;
/*!40000 ALTER TABLE `gral_help` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_import`
--

DROP TABLE IF EXISTS `gral_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_import` (
  `id_import` int NOT NULL AUTO_INCREMENT,
  `class` varchar(50) NOT NULL,
  `archivo` varchar(100) DEFAULT NULL,
  `bytes` int DEFAULT NULL,
  `lineas` int DEFAULT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'R',
  `f_original` datetime DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `error` longtext,
  `id_usuario` int DEFAULT NULL,
  `txt` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_import`),
  KEY `ix_import__fecha` (`class`,`fecha`),
  KEY `FK_gral_import_gral_estado_import` (`estado`),
  KEY `FK_gral_import_gral_usuario` (`id_usuario`),
  CONSTRAINT `FK_gral_import_gral_estado_import` FOREIGN KEY (`estado`) REFERENCES `gral_estado_import` (`estado`) ON UPDATE CASCADE,
  CONSTRAINT `FK_gral_import_gral_import_class` FOREIGN KEY (`class`) REFERENCES `gral_import_class` (`class`) ON UPDATE CASCADE,
  CONSTRAINT `FK_gral_import_gral_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `gral_usuario` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_import`
--

LOCK TABLES `gral_import` WRITE;
/*!40000 ALTER TABLE `gral_import` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_import` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_import_class`
--

DROP TABLE IF EXISTS `gral_import_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_import_class` (
  `class` varchar(50) NOT NULL,
  `id_sistema` varchar(50) NOT NULL,
  `txt` varchar(120) NOT NULL,
  `es_manual` bit(1) NOT NULL DEFAULT b'1',
  `trim_left` bit(1) NOT NULL DEFAULT b'1',
  `trim_right` bit(1) NOT NULL DEFAULT b'1',
  `ignorar_vacias` bit(1) NOT NULL DEFAULT b'1',
  `help` longtext,
  PRIMARY KEY (`class`),
  KEY `FK_gral_import_class_gral_sistema` (`id_sistema`),
  CONSTRAINT `FK_gral_import_class_gral_sistema` FOREIGN KEY (`id_sistema`) REFERENCES `gral_sistema` (`id_sistema`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_import_class`
--

LOCK TABLES `gral_import_class` WRITE;
/*!40000 ALTER TABLE `gral_import_class` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_import_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_localidad`
--

DROP TABLE IF EXISTS `gral_localidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_localidad` (
  `id_localidad` int NOT NULL AUTO_INCREMENT,
  `provincia` varchar(4) NOT NULL,
  `txt` varchar(50) NOT NULL,
  PRIMARY KEY (`id_localidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_localidad`
--

LOCK TABLES `gral_localidad` WRITE;
/*!40000 ALTER TABLE `gral_localidad` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_localidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_log`
--

DROP TABLE IF EXISTS `gral_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_log` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` int DEFAULT NULL,
  `evento` varchar(30) NOT NULL,
  `tabla` varchar(100) DEFAULT NULL,
  `pk` int DEFAULT NULL,
  `data` longtext,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `ix_log__tabla` (`tabla`,`pk`,`fecha`),
  KEY `ix_log__fecha` (`fecha`),
  KEY `ix_log__usuario` (`id_usuario`,`fecha`),
  KEY `ix_log__evento` (`evento`,`fecha`),
  CONSTRAINT `FK_gral_log_gral_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `gral_usuario` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_log`
--

LOCK TABLES `gral_log` WRITE;
/*!40000 ALTER TABLE `gral_log` DISABLE KEYS */;
INSERT INTO `gral_log` VALUES (61,'2021-06-12 11:12:37',1,'gral_login.ldap.notfound','gral_usuario',NULL,'usuario=rjg','::1'),(62,'2021-06-12 12:23:25',1,'gral_logout',NULL,NULL,NULL,'::1'),(63,'2021-06-12 12:29:35',1,'gral_logout',NULL,NULL,NULL,'::1'),(64,'2021-06-12 12:32:52',1,'gral_logout',NULL,NULL,NULL,'::1'),(65,'2021-06-12 12:34:40',1,'gral_logout',NULL,NULL,NULL,'::1'),(66,'2021-06-12 13:00:32',1,'gral_logout',NULL,NULL,NULL,'::1'),(67,'2021-06-12 13:00:35',NULL,'gral_login.ldap.notfound','gral_usuario',NULL,'usuario=rjg','::1'),(68,'2021-06-12 13:02:19',NULL,'gral_login.ldap.notfound','gral_usuario',NULL,'usuario=rjg','::1'),(69,'2021-06-12 13:02:25',NULL,'gral_login.ldap.notfound','gral_usuario',NULL,'usuario=admi','::1'),(70,'2021-06-12 13:10:48',1,'gral_logout',NULL,NULL,NULL,'::1'),(71,'2021-06-12 13:13:35',1,'gral_logout',NULL,NULL,NULL,'::1'),(72,'2021-06-12 13:13:52',1,'gral_logout',NULL,NULL,NULL,'::1'),(73,'2021-06-12 15:20:17',NULL,'gral_login.ldap.notfound','gral_usuario',NULL,'usuario=rjg','::1'),(74,'2021-06-13 11:34:33',1,'gral_login.db.badpasswd','gral_usuario',1,'usuario=admin','::1'),(75,'2021-06-13 11:35:16',1,'gral_login.db.badpasswd','gral_usuario',1,'usuario=admin','::1'),(76,'2021-06-13 11:35:20',1,'gral_login.ldap.notfound','gral_usuario',NULL,'usuario=zz','::1'),(77,'2021-06-13 20:51:15',1,'gral_logout',NULL,NULL,NULL,'::1'),(78,'2021-06-13 20:58:22',1,'gral_logout',NULL,NULL,NULL,'::1'),(79,'2021-06-13 20:58:38',1,'gral_logout',NULL,NULL,NULL,'::1'),(80,'2021-06-13 21:01:56',1,'gral_logout',NULL,NULL,NULL,'::1'),(81,'2021-06-13 21:04:22',1,'gral_logout',NULL,NULL,NULL,'::1'),(82,'2021-06-13 21:12:39',1,'gral_logout',NULL,NULL,NULL,'::1'),(83,'2021-06-13 21:15:05',1,'gral_logout',NULL,NULL,NULL,'::1'),(84,'2021-06-13 21:15:38',1,'gral_logout',NULL,NULL,NULL,'::1'),(85,'2021-06-13 21:19:40',1,'gral_logout',NULL,NULL,NULL,'::1'),(86,'2021-06-13 21:54:15',1,'gral_logout',NULL,NULL,NULL,'::1'),(87,'2021-06-13 21:59:58',1,'gral_logout',NULL,NULL,NULL,'::1'),(88,'2021-06-13 22:04:31',1,'gral_logout',NULL,NULL,NULL,'::1'),(89,'2021-06-13 22:05:13',1,'cliente_grupo.del','cliente_grupo',1,NULL,'::1'),(90,'2021-06-13 22:06:14',1,'cliente_grupo.del','cliente_grupo',2,NULL,'::1'),(91,'2021-06-13 22:19:32',1,'gral_logout',NULL,NULL,NULL,'::1'),(92,'2021-06-13 22:24:04',1,'gral_logout',NULL,NULL,NULL,'::1'),(93,'2021-06-13 22:28:06',1,'gral_cliente.del','gral_cliente',2,NULL,'::1'),(94,'2021-06-13 22:28:10',1,'gral_cliente.del','gral_cliente',2,NULL,'::1'),(95,'2021-06-13 22:28:41',1,'cliente_cliente.del','cliente_cliente',2,NULL,'::1'),(96,'2021-06-13 23:01:20',1,'gral_logout',NULL,NULL,NULL,'::1'),(97,'2021-06-13 23:22:05',1,'gral_logout',NULL,NULL,NULL,'::1'),(98,'2021-06-13 23:27:08',1,'gral_logout',NULL,NULL,NULL,'::1'),(99,'2021-06-13 23:29:22',1,'gral_logout',NULL,NULL,NULL,'::1'),(100,'2021-06-13 23:31:08',1,'gral_logout',NULL,NULL,NULL,'::1'),(101,'2021-06-13 23:32:39',1,'gral_logout',NULL,NULL,NULL,'::1'),(102,'2021-06-13 23:37:55',1,'gral_logout',NULL,NULL,NULL,'::1'),(103,'2021-06-13 23:40:06',1,'gral_logout',NULL,NULL,NULL,'::1'),(104,'2021-06-13 23:43:05',1,'gral_logout',NULL,NULL,NULL,'::1'),(105,'2021-06-13 23:49:32',1,'gral_logout',NULL,NULL,NULL,'::1'),(106,'2021-06-14 00:04:42',1,'gral_logout',NULL,NULL,NULL,'::1'),(107,'2021-06-14 00:07:35',1,'cliente_grupo.del','cliente_grupo',6,NULL,'::1'),(108,'2021-06-14 00:10:20',1,'gral_logout',NULL,NULL,NULL,'::1'),(109,'2021-06-14 00:24:08',1,'gral_logout',NULL,NULL,NULL,'::1'),(110,'2021-06-14 00:25:17',1,'gral_logout',NULL,NULL,NULL,'::1'),(111,'2021-06-14 22:20:31',1,'gral_logout',NULL,NULL,NULL,'::1'),(112,'2021-06-14 22:21:37',1,'gral_logout',NULL,NULL,NULL,'::1'),(113,'2021-06-14 22:32:10',1,'gral_logout',NULL,NULL,NULL,'::1'),(114,'2021-06-14 22:47:39',1,'gral_logout',NULL,NULL,NULL,'::1'),(115,'2021-06-14 22:50:17',1,'gral_logout',NULL,NULL,NULL,'::1'),(116,'2021-06-14 22:58:07',1,'gral_logout',NULL,NULL,NULL,'::1'),(117,'2021-06-14 23:00:32',1,'gral_logout',NULL,NULL,NULL,'::1'),(118,'2021-06-14 23:00:39',1,'gral_logout',NULL,NULL,NULL,'::1'),(119,'2021-06-14 23:03:48',1,'gral_logout',NULL,NULL,NULL,'::1'),(120,'2021-06-14 23:13:30',1,'gral_logout',NULL,NULL,NULL,'::1'),(121,'2021-06-14 23:17:16',1,'gral_logout',NULL,NULL,NULL,'::1'),(122,'2021-06-14 23:25:46',1,'gral_logout',NULL,NULL,NULL,'::1'),(123,'2021-06-14 23:36:33',1,'gral_logout',NULL,NULL,NULL,'::1'),(124,'2021-06-14 23:38:08',1,'gral_logout',NULL,NULL,NULL,'::1'),(125,'2021-06-14 23:50:34',1,'gral_logout',NULL,NULL,NULL,'::1');
/*!40000 ALTER TABLE `gral_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_log_estado`
--

DROP TABLE IF EXISTS `gral_log_estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_log_estado` (
  `id_log_estado` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `tabla` varchar(100) NOT NULL,
  `pk` int NOT NULL,
  `anterior` varchar(10) DEFAULT NULL,
  `nuevo` varchar(10) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_log_estado`),
  KEY `FK_gral_log_estado_gral_usuario` (`id_usuario`),
  CONSTRAINT `FK_gral_log_estado_gral_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `gral_usuario` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_log_estado`
--

LOCK TABLES `gral_log_estado` WRITE;
/*!40000 ALTER TABLE `gral_log_estado` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_log_estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_nota`
--

DROP TABLE IF EXISTS `gral_nota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_nota` (
  `id_nota` int NOT NULL AUTO_INCREMENT,
  `tabla` varchar(50) NOT NULL,
  `pk` int NOT NULL,
  `txt` varchar(120) NOT NULL,
  `fecha` date NOT NULL,
  `f_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nota` longtext,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_nota`),
  KEY `ix_nota_pk` (`tabla`,`pk`,`fecha`,`id_nota`),
  KEY `ix_mece_nota_pk` (`tabla`,`pk`,`fecha`,`id_nota`),
  KEY `FK_gral_nota_gral_usuario` (`id_usuario`),
  CONSTRAINT `FK_gral_nota_gral_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `gral_usuario` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_nota`
--

LOCK TABLES `gral_nota` WRITE;
/*!40000 ALTER TABLE `gral_nota` DISABLE KEYS */;
INSERT INTO `gral_nota` VALUES (1,'gral_usuario',719,'asdasds','2017-11-19','2017-11-19 09:14:25','asdsadsa',1),(2,'gral_usuario',719,'terrible es esto','2017-11-19','2017-11-19 09:14:56','sdfdsf\r\n\r\nvamos a comprar una araña para javolin',1),(3,'gral_usuario',719,'mama esta cansada','2017-11-19','2017-11-19 09:21:58','tiene sueño mama',1);
/*!40000 ALTER TABLE `gral_nota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_perfil`
--

DROP TABLE IF EXISTS `gral_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_perfil` (
  `id_perfil` varchar(60) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `si_sistema` int DEFAULT '0',
  `id_sistema` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`),
  KEY `FK_gral_perfil_gral_sistema` (`id_sistema`),
  CONSTRAINT `FK_gral_perfil_gral_sistema` FOREIGN KEY (`id_sistema`) REFERENCES `gral_sistema` (`id_sistema`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_perfil`
--

LOCK TABLES `gral_perfil` WRITE;
/*!40000 ALTER TABLE `gral_perfil` DISABLE KEYS */;
INSERT INTO `gral_perfil` VALUES ('DD','dd',0,'GRAL'),('FULL','Todos los permisos',1,NULL),('GRAL_FULL','General (full)',1,'GRAL');
/*!40000 ALTER TABLE `gral_perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_perfil_permiso`
--

DROP TABLE IF EXISTS `gral_perfil_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_perfil_permiso` (
  `id_perfil_permiso` int NOT NULL AUTO_INCREMENT,
  `id_perfil` varchar(60) NOT NULL,
  `id_permiso` varchar(60) NOT NULL,
  PRIMARY KEY (`id_perfil_permiso`),
  KEY `FK_gral_perfil_permiso_gral_perfil` (`id_perfil`),
  KEY `FK_gral_perfil_permiso_gral_permiso` (`id_permiso`),
  CONSTRAINT `FK_gral_perfil_permiso_gral_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `gral_perfil` (`id_perfil`) ON UPDATE CASCADE,
  CONSTRAINT `FK_gral_perfil_permiso_gral_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `gral_permiso` (`id_permiso`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102470 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_perfil_permiso`
--

LOCK TABLES `gral_perfil_permiso` WRITE;
/*!40000 ALTER TABLE `gral_perfil_permiso` DISABLE KEYS */;
INSERT INTO `gral_perfil_permiso` VALUES (102441,'FULL','GRAL_PERFIL_RW'),(102442,'FULL','GRAL_USUARIO_ALTA'),(102443,'FULL','GRAL_USUARIO_DELETE'),(102444,'FULL','GRAL_USUARIO_EDIT'),(102445,'FULL','GRAL_USUARIO_PASSWD'),(102446,'FULL','GRAL_USUARIO_PERFILES'),(102447,'FULL','GRAL_VIEW_USUARIO'),(102449,'FULL','GRAL_SMENU_USUARIOS'),(102451,'FULL','GRAL_SMENU_CONFIG'),(102453,'FULL','GRAL_SMENU_DEBUG'),(102455,'FULL','GRAL_CONFIG'),(102458,'FULL','GRAL_DEBUG'),(102460,'FULL','GRAL_LOG'),(102463,'FULL','GRAL_USUARIOS'),(102468,'FULL','CLIENTE_MENU_CLIENTE'),(102469,'FULL','CLIENTE_MENU_GRUPO');
/*!40000 ALTER TABLE `gral_perfil_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_permiso`
--

DROP TABLE IF EXISTS `gral_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_permiso` (
  `id_permiso` varchar(60) NOT NULL,
  `id_sistema` varchar(60) NOT NULL,
  `tipo` varchar(1) NOT NULL DEFAULT 'P',
  `descripcion` varchar(100) NOT NULL,
  `help` longtext,
  `link` varchar(250) DEFAULT NULL,
  `padre` varchar(60) DEFAULT NULL,
  `orden_menu` int DEFAULT NULL,
  `si_publico` int DEFAULT '0',
  PRIMARY KEY (`id_permiso`),
  KEY `ix_permiso__sistema` (`id_sistema`,`padre`,`orden_menu`),
  KEY `ix_permiso__padre` (`padre`,`orden_menu`),
  CONSTRAINT `FK_gral_permiso_gral_sistema` FOREIGN KEY (`id_sistema`) REFERENCES `gral_sistema` (`id_sistema`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_permiso`
--

LOCK TABLES `gral_permiso` WRITE;
/*!40000 ALTER TABLE `gral_permiso` DISABLE KEYS */;
INSERT INTO `gral_permiso` VALUES ('CLIENTE_MENU_CLIENTE','CLIENTE','M','Listado de Clientes',NULL,'abm/query.php?clearStack=true&screen=ClienteClienteQuery',NULL,10,0),('CLIENTE_MENU_GRUPO','CLIENTE','M','Listado de Grupos',NULL,'abm/query.php?clearStack=true&screen=ClienteGrupoQuery',NULL,20,0),('GRAL_CONFIG','GRAL','M','Parámetros de Configuración','Habilita las opciones de agregar, modificar y eliminar en la pantalla de parámetros de configuración.','abm/query.php?screen=GralConfigQuery','GRAL_SMENU_CONFIG',5,0),('GRAL_DEBUG','GRAL','M','Información de DEBUG','Habilita las opciones de agregar, modificar y eliminar en la pantalla de información de debug.','gral/debug.php','GRAL_SMENU_DEBUG',1,0),('GRAL_LOG','GRAL','M','Log General','Habilita las opciones de agregar, modificar y eliminar en la pantalla de log general.','abm/query.php?clearStack=true&screen=GralLogQuery','GRAL_SMENU_DEBUG',4,0),('GRAL_PERFIL_RW','GRAL','P','Creación y modificación de perfiles','Habilita las opciones de agregar, modificar y eliminar en la pantalla de creación y modificación de perfiles.',NULL,NULL,NULL,0),('GRAL_SMENU_CONFIG','GRAL','S','Configuración',NULL,NULL,NULL,98,0),('GRAL_SMENU_DEBUG','GRAL','S','Debug',NULL,NULL,NULL,200,0),('GRAL_SMENU_USUARIOS','GRAL','S','Seguridad',NULL,NULL,NULL,5,0),('GRAL_USUARIOS','GRAL','M','Administración de usuarios','Habilita las opciones de agregar, modificar y eliminar en la pantalla de administración de usuarios.','abm/query.php?screen=GralUsuarioQuery&clearStack=true','GRAL_SMENU_USUARIOS',2,0),('GRAL_USUARIO_ALTA','GRAL','P','Agregar usuarios','Habilita las opciones de agregar, modificar y eliminar en la pantalla de agregar usuarios.',NULL,NULL,NULL,0),('GRAL_USUARIO_DELETE','GRAL','P','Eliminar usuarios','Habilita las opciones de agregar, modificar y eliminar en la pantalla de eliminar usuarios.',NULL,NULL,NULL,0),('GRAL_USUARIO_EDIT','GRAL','P','Editar datos de usuarios','Habilita las opciones de agregar, modificar y eliminar en la pantalla de editar datos de usuarios.',NULL,NULL,NULL,0),('GRAL_USUARIO_PASSWD','GRAL','P','Forzar nueva password a un usuario','Habilita las opciones de agregar, modificar y eliminar en la pantalla de forzar nueva password a un usuario.',NULL,NULL,NULL,0),('GRAL_USUARIO_PERFILES','GRAL','P','Asignar perfiles a usuarios','Habilita las opciones de agregar, modificar y eliminar en la pantalla de asignar perfiles a usuarios.',NULL,NULL,NULL,0),('GRAL_VIEW_USUARIO','GRAL','P','Habilita pantalla de view de usuarios',NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `gral_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_preferencia`
--

DROP TABLE IF EXISTS `gral_preferencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_preferencia` (
  `id_preferencia` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `q_page_size` int DEFAULT NULL,
  `q_nav_pos` varchar(10) DEFAULT NULL,
  `q_show_filters` int DEFAULT NULL,
  `excel_num_format` varchar(10) DEFAULT NULL,
  `q_action_pos` varchar(10) DEFAULT NULL,
  `q_print_page_size` int DEFAULT NULL,
  `num_format` varchar(10) DEFAULT NULL,
  `date_format` varchar(10) DEFAULT NULL,
  `excel_date_format` varchar(10) DEFAULT NULL,
  `ej` int DEFAULT NULL,
  `excel_list_sep` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_preferencia`),
  KEY `FK_gral_preferencia_gral_usuario` (`id_usuario`),
  CONSTRAINT `FK_gral_preferencia_gral_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `gral_usuario` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_preferencia`
--

LOCK TABLES `gral_preferencia` WRITE;
/*!40000 ALTER TABLE `gral_preferencia` DISABLE KEYS */;
INSERT INTO `gral_preferencia` VALUES (2,NULL,20,'bottom',1,'spanish','left',40,NULL,NULL,'Y-m-d',NULL,NULL),(3,1,20,NULL,NULL,NULL,NULL,NULL,'spanish','d/m/Y',NULL,NULL,NULL);
/*!40000 ALTER TABLE `gral_preferencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_provincia`
--

DROP TABLE IF EXISTS `gral_provincia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_provincia` (
  `provincia` varchar(4) NOT NULL,
  `txt` longtext NOT NULL,
  PRIMARY KEY (`provincia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_provincia`
--

LOCK TABLES `gral_provincia` WRITE;
/*!40000 ALTER TABLE `gral_provincia` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_provincia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_sched`
--

DROP TABLE IF EXISTS `gral_sched`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_sched` (
  `id_sched` int NOT NULL AUTO_INCREMENT,
  `txt` varchar(120) NOT NULL,
  `class` varchar(50) NOT NULL,
  `dia` varchar(30) NOT NULL DEFAULT '*',
  `dia_semana` varchar(30) NOT NULL DEFAULT '*',
  `hora` varchar(30) NOT NULL DEFAULT '*',
  `minuto` varchar(30) NOT NULL DEFAULT '*',
  `params` longtext,
  `loguear` bit(1) NOT NULL DEFAULT b'1',
  `activo` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id_sched`),
  KEY `FK_gral_sched_gral_sched_class` (`class`),
  CONSTRAINT `FK_gral_sched_gral_sched_class` FOREIGN KEY (`class`) REFERENCES `gral_sched_class` (`class`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_sched`
--

LOCK TABLES `gral_sched` WRITE;
/*!40000 ALTER TABLE `gral_sched` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_sched` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_sched_class`
--

DROP TABLE IF EXISTS `gral_sched_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_sched_class` (
  `class` varchar(50) NOT NULL,
  `txt` varchar(120) NOT NULL,
  `help` longtext,
  `id_sistema` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`class`),
  KEY `FK_gral_sched_class_gral_sistema` (`id_sistema`),
  CONSTRAINT `FK_gral_sched_class_gral_sistema` FOREIGN KEY (`id_sistema`) REFERENCES `gral_sistema` (`id_sistema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_sched_class`
--

LOCK TABLES `gral_sched_class` WRITE;
/*!40000 ALTER TABLE `gral_sched_class` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_sched_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_sistema`
--

DROP TABLE IF EXISTS `gral_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_sistema` (
  `id_sistema` varchar(60) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `orden_menu` int DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `activo` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id_sistema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_sistema`
--

LOCK TABLES `gral_sistema` WRITE;
/*!40000 ALTER TABLE `gral_sistema` DISABLE KEYS */;
INSERT INTO `gral_sistema` VALUES ('CLIENTE','Clientes',3,'gral/home.php?id_sistema=',_binary ''),('GRAL','General',0,'gral/home.php',_binary '');
/*!40000 ALTER TABLE `gral_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_tabla`
--

DROP TABLE IF EXISTS `gral_tabla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_tabla` (
  `tabla` varchar(100) NOT NULL,
  `txt` varchar(100) NOT NULL,
  `screen` varchar(50) DEFAULT NULL,
  `tabla_estado` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`tabla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_tabla`
--

LOCK TABLES `gral_tabla` WRITE;
/*!40000 ALTER TABLE `gral_tabla` DISABLE KEYS */;
/*!40000 ALTER TABLE `gral_tabla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_usuario`
--

DROP TABLE IF EXISTS `gral_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(25) NOT NULL,
  `estado` varchar(1) NOT NULL DEFAULT 'A',
  `fecha_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_baja` datetime DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_cambio` datetime DEFAULT NULL,
  `txt` varchar(120) NOT NULL,
  `dni` int DEFAULT NULL,
  `celular` varchar(100) DEFAULT NULL,
  `forzar_cambio` int DEFAULT NULL,
  `is_admin` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=971 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_usuario`
--

LOCK TABLES `gral_usuario` WRITE;
/*!40000 ALTER TABLE `gral_usuario` DISABLE KEYS */;
INSERT INTO `gral_usuario` VALUES (1,'admin','A','2010-03-01 17:05:58',NULL,'21232f297a57a5a743894a0e4a801fc3','Sr.','Administrador',NULL,NULL,NULL,'Administrador, Sr.',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `gral_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gral_usuario_perfil`
--

DROP TABLE IF EXISTS `gral_usuario_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gral_usuario_perfil` (
  `id_usuario_perfil` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_perfil` varchar(60) NOT NULL,
  `with_grant` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_usuario_perfil`),
  KEY `FK_gral_usuario_perfil_gral_usuario` (`id_usuario`),
  KEY `FK_gral_usuario_perfil_gral_perfil` (`id_perfil`),
  CONSTRAINT `FK_gral_usuario_perfil_gral_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `gral_perfil` (`id_perfil`) ON UPDATE CASCADE,
  CONSTRAINT `FK_gral_usuario_perfil_gral_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `gral_usuario` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1684 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gral_usuario_perfil`
--

LOCK TABLES `gral_usuario_perfil` WRITE;
/*!40000 ALTER TABLE `gral_usuario_perfil` DISABLE KEYS */;
INSERT INTO `gral_usuario_perfil` VALUES (1664,1,'FULL',0);
/*!40000 ALTER TABLE `gral_usuario_perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `gral_v_permiso`
--

DROP TABLE IF EXISTS `gral_v_permiso`;
/*!50001 DROP VIEW IF EXISTS `gral_v_permiso`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `gral_v_permiso` AS SELECT 
 1 AS `id_usuario`,
 1 AS `id_permiso`,
 1 AS `id_sistema`,
 1 AS `tipo`,
 1 AS `descripcion`,
 1 AS `help`,
 1 AS `link`,
 1 AS `padre`,
 1 AS `orden_menu`,
 1 AS `orden_sistema`,
 1 AS `descripcion_sistema`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `gral_v_permiso`
--

/*!50001 DROP VIEW IF EXISTS `gral_v_permiso`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `gral_v_permiso` AS select distinct `up`.`id_usuario` AS `id_usuario`,`pm`.`id_permiso` AS `id_permiso`,`pm`.`id_sistema` AS `id_sistema`,`pm`.`tipo` AS `tipo`,`pm`.`descripcion` AS `descripcion`,`pm`.`help` AS `help`,`pm`.`link` AS `link`,`pm`.`padre` AS `padre`,`pm`.`orden_menu` AS `orden_menu`,`s`.`orden_menu` AS `orden_sistema`,`s`.`descripcion` AS `descripcion_sistema` from (`gral_usuario_perfil` `up` join (`gral_perfil_permiso` `pp` join (`gral_permiso` `pm` join `gral_sistema` `s` on((`pm`.`id_sistema` = `s`.`id_sistema`))) on((`pp`.`id_permiso` = `pm`.`id_permiso`))) on((`up`.`id_perfil` = `pp`.`id_perfil`))) where (`pm`.`si_publico` = 0) union select `u`.`id_usuario` AS `id_usuario`,`pm`.`id_permiso` AS `id_permiso`,`pm`.`id_sistema` AS `id_sistema`,`pm`.`tipo` AS `tipo`,`pm`.`descripcion` AS `descripcion`,`pm`.`help` AS `help`,`pm`.`link` AS `link`,`pm`.`padre` AS `padre`,`pm`.`orden_menu` AS `orden_menu`,`s`.`orden_menu` AS `orden_sistema`,`s`.`descripcion` AS `descripcion_sistema` from (`gral_usuario` `u` join (`gral_permiso` `pm` join `gral_sistema` `s` on((`pm`.`id_sistema` = `s`.`id_sistema`)))) where (`pm`.`si_publico` = 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-14 23:57:11
