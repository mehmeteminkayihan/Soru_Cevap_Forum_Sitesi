CREATE DATABASE  IF NOT EXISTS `acdatabase` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `acdatabase`;
-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: acdatabase
-- ------------------------------------------------------
-- Server version	8.0.34

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
-- Table structure for table `answer_reaction_log`
--

DROP TABLE IF EXISTS `answer_reaction_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `answer_reaction_log` (
  `user_id` int NOT NULL,
  `answer_id` int NOT NULL,
  `reaction_type` enum('like','dislike') NOT NULL,
  PRIMARY KEY (`answer_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `answer_reaction_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `answer_reaction_log_ibfk_2` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answer_reaction_log`
--

LOCK TABLES `answer_reaction_log` WRITE;
/*!40000 ALTER TABLE `answer_reaction_log` DISABLE KEYS */;
INSERT INTO `answer_reaction_log` VALUES (6,1,'like'),(6,2,'like'),(5,3,'like'),(6,4,'dislike'),(6,5,'dislike'),(5,6,'dislike');
/*!40000 ALTER TABLE `answer_reaction_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `answers` (
  `answer_id` int NOT NULL AUTO_INCREMENT,
  `questions_id` int NOT NULL,
  `user_id` int NOT NULL,
  `answer` mediumtext NOT NULL,
  `answer_like` int DEFAULT '0',
  `answer_dislike` int DEFAULT '0',
  `answer_date` datetime NOT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `user_id` (`user_id`),
  KEY `questions_id` (`questions_id`),
  CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`questions_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES (1,26,6,'deneme',1,0,'2024-01-17 23:05:53'),(2,25,6,'babababab',1,0,'2024-01-17 23:06:37'),(3,19,6,'adsadasdad',1,0,'2024-01-17 23:06:59'),(4,24,6,'deneme',0,1,'2024-01-18 19:26:03'),(5,23,6,'qwıuyeqweq',0,1,'2024-01-18 19:26:17'),(6,25,5,'mmm',0,1,'2024-01-18 19:37:19'),(7,19,5,'babababa',0,0,'2024-01-18 19:37:52');
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authorizations`
--

DROP TABLE IF EXISTS `authorizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authorizations` (
  `authorization_id` int NOT NULL AUTO_INCREMENT,
  `authorization_name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`authorization_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authorizations`
--

LOCK TABLES `authorizations` WRITE;
/*!40000 ALTER TABLE `authorizations` DISABLE KEYS */;
INSERT INTO `authorizations` VALUES (1,'admin'),(2,'moderatör'),(3,'kullanıcı');
/*!40000 ALTER TABLE `authorizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Diğer'),(2,'Teknoloji'),(3,'Sanat'),(4,'Kitap'),(5,'Film'),(6,'Spor'),(7,'Müzik'),(8,'Bilim'),(9,'Moda'),(10,'Gezi'),(11,'Yemek'),(12,'Sağlık'),(13,'Oyun'),(14,'Doğa'),(15,'Eğitim'),(16,'Tarih');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_information`
--

DROP TABLE IF EXISTS `company_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_information` (
  `authorization_id` int NOT NULL,
  `company_about` mediumtext,
  `company_phonenumber` varchar(25) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `privacy_policy` mediumtext NOT NULL,
  `Linkedin` varchar(255) DEFAULT NULL,
  KEY `authorization_id` (`authorization_id`),
  CONSTRAINT `company_information_ibfk_1` FOREIGN KEY (`authorization_id`) REFERENCES `authorizations` (`authorization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_information`
--

LOCK TABLES `company_information` WRITE;
/*!40000 ALTER TABLE `company_information` DISABLE KEYS */;
INSERT INTO `company_information` VALUES (1,'Anında Cevap Hakkında :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nAnında Cevap, kuruluşundan bugüne kadar 01.01.2024 yılından itibaren sektöründe öncü ve yenilikçi adımlar atan bir kuruluştur.Bu doğrultuda, müşterilerimize sağladığımız ürün ve hizmetlerimizle kalite standartlarını sürekli olarak yükseltmeyi hedeflemekteyiz.<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nVizyonumuz :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nAnında Cevap , Kullanıcılarımızın her türlü sorusuna anında ve güvenilir bir şekilde cevap bulmalarını sağlamak. Bilgiye erişimi kolaylaştırarak, zengin ve doğrulanmış içeriklerimizle herkesin sorularına anlamlı ve tatmin edici yanıtlar sunmak. Kullanıcı deneyimini ön planda tutarak, herkesin öğrenme sürecine katkı sağlayan bir platform olmak için çalışıyoruz. Sorularınızı sormak için bir alan, doğru cevapları bulmak için bir kaynak olarak hizmet vermek ve topluluğumuzu bilgi paylaşımı üzerine inşa etmek amacımızdır.<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nSosyal Sorumluluk ve Sürdürülebilirlik :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nAnında Cevap şirketi, sadece ticari başarıları değil, aynı zamanda topluma ve çevreye duyduğu sorumluluğun bilincindedir. Sosyal sorumluluk projeleriyle topluma katkı sağlarken, çevre dostu uygulamalarla da sürdürülebilir bir gelecek için çaba göstermektedir. Şirket olarak, etik değerlere bağlı kalarak, toplumsal ve çevresel fayda odaklı projelere destek vermekteyiz.<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nTakımımız :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nAnında Cevap şirketi, deneyimli ve uzman bir ekiple çalışmaktadır. İşe alım süreçlerinde yetenekli bireyleri şirketimize kazandırarak, her bir çalışanın katkısını değerli bulmak ve onların gelişimine olanak sağlamak temel prensibimizdir. Takımımızın çeşitliliği ve uzmanlığı, şirketimizin başarısındaki en önemli unsurlardan biridir.<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nİletişim :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nEğer Anında Cevap şirketi hakkında daha fazla bilgi almak veya iş birliği olanakları hakkında konuşmak isterseniz, bizimle iletişime geçmekten çekinmeyin. Size en iyi şekilde yardımcı olmak için buradayız.<br /><br /><br /><br />\r\n','546 484 02 11','anindacevap@gmail.comm','Araplar Aile Yaşam Merkezi C2/Blok Ankara/Mamak','Anında Cevap Hakkında :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nAnında Cevap, kuruluşundan bugüne kadar 01.01.2024 yılından itibaren sektöründe öncü ve yenilikçi adımlar atan bir kuruluştur.Bu doğrultuda, müşterilerimize sağladığımız ürün ve hizmetlerimizle kalite standartlarını sürekli olarak yükseltmeyi hedeflemekteyiz.<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nVizyonumuz :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nAnında Cevap , Kullanıcılarımızın her türlü sorusuna anında ve güvenilir bir şekilde cevap bulmalarını sağlamak. Bilgiye erişimi kolaylaştırarak, zengin ve doğrulanmış içeriklerimizle herkesin sorularına anlamlı ve tatmin edici yanıtlar sunmak. Kullanıcı deneyimini ön planda tutarak, herkesin öğrenme sürecine katkı sağlayan bir platform olmak için çalışıyoruz. Sorularınızı sormak için bir alan, doğru cevapları bulmak için bir kaynak olarak hizmet vermek ve topluluğumuzu bilgi paylaşımı üzerine inşa etmek amacımızdır.<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nSosyal Sorumluluk ve Sürdürülebilirlik :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nAnında Cevap şirketi, sadece ticari başarıları değil, aynı zamanda topluma ve çevreye duyduğu sorumluluğun bilincindedir. Sosyal sorumluluk projeleriyle topluma katkı sağlarken, çevre dostu uygulamalarla da sürdürülebilir bir gelecek için çaba göstermektedir. Şirket olarak, etik değerlere bağlı kalarak, toplumsal ve çevresel fayda odaklı projelere destek vermekteyiz.<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nTakımımız :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nAnında Cevap şirketi, deneyimli ve uzman bir ekiple çalışmaktadır. İşe alım süreçlerinde yetenekli bireyleri şirketimize kazandırarak, her bir çalışanın katkısını değerli bulmak ve onların gelişimine olanak sağlamak temel prensibimizdir. Takımımızın çeşitliliği ve uzmanlığı, şirketimizin başarısındaki en önemli unsurlardan biridir.<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nİletişim :<br /><br /><br /><br />\r\n<br /><br /><br /><br />\r\nEğer Anında Cevap şirketi hakkında daha fazla bilgi almak veya iş birliği olanakları hakkında konuşmak isterseniz, bizimle iletişime geçmekten çekinmeyin. Size en iyi şekilde yardımcı olmak için buradayız.<br /><br /><br /><br />\r\n','www.linkedin.com/in/mehmet-emin-kayıhan-287313237');
/*!40000 ALTER TABLE `company_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact` (
  `contact_id` int NOT NULL AUTO_INCREMENT,
  `contact_nick` varchar(50) NOT NULL,
  `contact_email` varchar(256) NOT NULL,
  `contact_title` mediumtext NOT NULL,
  `contact_message` mediumtext NOT NULL,
  `contact_date` datetime NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES (1,'deneme','dene@gmail.com','adasda','dsadasd','2024-01-18 17:35:02'),(2,'ornek','ornek@gmail.com','site hızı','siteniz çok yavaş','2024-01-18 18:13:58');
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `questions_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `question_title` mediumtext NOT NULL,
  `questions` mediumtext NOT NULL,
  `question_date` datetime NOT NULL,
  `question_like` int DEFAULT '0',
  `question_dislike` int DEFAULT '0',
  `number_of_views` int DEFAULT '0',
  `total_responses_received` int DEFAULT '0',
  PRIMARY KEY (`questions_id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,3,2,'Teknolojinin İnsan İlişkilerine Etkisi','Teknolojinin gelişimi insan ilişkilerini nasıl etkiliyor ve bu etkileşimleri nasıl optimize edebiliriz?','2024-01-17 22:04:36',0,0,1,0),(2,3,6,'Sağlıklı Yaşam İçin 10 Adım','Sağlıklı bir yaşam tarzına geçiş yapmak isteyenler için temel adımları içeren bir rehber nasıl olmalıdır?','2024-01-17 22:05:02',0,0,0,0),(3,3,4,'Doğanın Şarkısı','Doğanın güzelliklerini ve insanın doğa ile olan bağını anlatan bir şiir nasıl yazılabilir?','2024-01-17 22:05:24',0,0,0,0),(4,3,4,'Doğanın Şarkısı','Doğanın güzelliklerini ve insanın doğa ile olan bağını anlatan bir şiir nasıl yazılabilir?','2024-01-17 22:07:52',0,0,0,0),(5,5,16,'Geçmişten Günümüze','Tarihle ilgili en ilginç dönem veya olay nedir?\\\"','2024-01-17 22:09:43',0,0,0,0),(6,5,1,'Geleneğin Dışında Bir Diğer','Hayatınızın dönüm noktalarından biri olarak gördüğünüz, geleneksel normlardan sıyrılan bir deneyim yaşadınız mı? Bu deneyim sizi nasıl etkiledi?','2024-01-17 22:10:26',0,0,0,0),(7,5,6,'Spor Tutkusu','Hangi spor dalını takip ediyorsunuz ve bu sporun sizin için taşıdığı anlam nedir? Sporun hayatınızdaki rolünü düşündüğünüzde, fiziksel ve zihinsel sağlığınıza olan etkilerini nasıl değerlendiriyorsunuz?','2024-01-17 22:10:49',0,0,0,0),(8,4,7,'Melodinin Gücü','Yaşamınızın farklı anlarında hangi şarkılar size ilham veriyor? Müziğin sizin için anlamı nedir ve belirli bir şarkının hayatınız üzerindeki etkilerini düşündüğünüzde neler hissediyorsunuz?','2024-01-17 22:11:25',0,0,0,0),(9,4,9,'Tarzınızın Sırları','Moda dünyasında kendi tarzınızı oluştururken, hangi tasarımcılardan veya stillerden ilham alıyorsunuz? Tarzınızın sizin kişiliğinizi nasıl yansıttığını düşündüğünüzde, moda ile ilişkinizdeki en unutulmaz anı nedir?','2024-01-17 22:11:45',0,0,0,0),(10,4,10,'Keşfetmenin Gücü','Gittiğiniz en unutulmaz yer hangisiydi? Bu seyahat sırasında yaşadığınız deneyimler ve bu yerin size kattıkları hakkında daha fazla detay paylaşabilir misiniz?','2024-01-17 22:12:03',0,0,0,0),(11,4,11,'Lezzetli Anılar','En son yediğiniz harika bir yemek nedir? Bu yemekle ilgili anılarınızı paylaşırken, lezzetin sizin için taşıdığı anlamları da ifade edebilir misiniz?','2024-01-17 22:12:35',0,0,0,0),(13,2,13,'Oyun Dünyasında Bir Yolculuk','En sevdiğiniz video oyunu nedir ve bu oyun sizin için neden önemli? Oyunlarla ilgili olarak, oyun dünyasındaki gelişmeleri takip ediyor musunuz ve favori oyunlarınızın sizin üzerinizdeki etkilerini nasıl değerlendiriyorsunuz?','2024-01-17 22:13:50',0,0,0,0),(14,2,14,'Doğanın Güzelliği','Doğada en çok zaman geçirmeyi sevdiğiniz yer neresidir? Doğayla bağlantınızın size sağladığı huzur ve enerjiyi düşündüğünüzde, bu deneyimleri daha derinlemesine anlatır mısınız?','2024-01-17 22:14:04',0,0,0,0),(15,2,15,'Bilgiye Açılan Kapı: Eğitim','Kariyerinizdeki en önemli öğrenme deneyimi nedir? Eğitim almak ve sürekli öğrenmeye açık olmak sizin için neden bu kadar önemli?','2024-01-17 22:14:20',0,0,0,0),(16,2,2,'Dijital Dönüşüm ve Toplumsal Etkileri','Teknolojik gelişmelerle birlikte toplum üzerindeki etkileri nasıl değerlendiriyorsunuz? Dijital dönüşümün sosyal ilişkiler, iş dünyası ve kültür üzerindeki rolünü düşündüğünüzde, hangi sonuçlar öne çıkıyor?','2024-01-17 22:15:15',0,0,0,0),(17,6,3,'Sanatın Toplumsal Mesajları','Sanat eserleri toplumsal konularda sıkça mesajlar iletiyor. Sizin için anlamlı olan bir sanat eseri var mı? Bu eser size ne tür düşünceler aşıladı ve sanatın toplumsal değişim üzerindeki rolünü düşündüğünüzde neler hissediyorsunuz?','2024-01-17 22:19:16',0,0,0,0),(18,6,5,'Sinemanın Toplumsal Yansımaları','Bir film izlediğinizde, filmdeki toplumsal temaların ve mesajların sizin üzerinizdeki etkilerini nasıl değerlendiriyorsunuz? Hangi film sizin için özel bir anlam taşıyor ve bu filmi izlerken yaşadığınız duygular nelerdi?','2024-01-17 22:19:32',1,0,1,0),(19,6,8,'Bilimin Sosyal Sorumluluğu','Bilimin toplum üzerindeki sorumlulukları nelerdir? Bilim insanlarının etik sorunlarla başa çıkma şekillerini ve bilimin sosyal ilerlemeye olan katkılarını nasıl değerlendiriyorsunuz?','2024-01-17 22:21:05',2,0,2,2),(20,7,10,'Yerel Kültürleri Keşfetmek','Yerel kültürleri keşfetme konusundaki tutkunuz nedir? Seyahat ettiğiniz yerlerde, yerel kültürlerin size öğrettikleri ve yaşattıkları hakkında anılarınızı paylaşabilir misiniz?','2024-01-17 22:24:45',0,0,0,0),(21,7,11,'Yemeklerin Kültürel Çeşitliliği','Farklı kültürlerin mutfağını deneyimleme şansınız oldu mu? Yemeklerin kültürel bağlamdaki rolünü düşündüğünüzde, bu deneyimlerin sizin üzerinizdeki etkilerini nasıl değerlendiriyorsunuz?','2024-01-17 22:25:00',0,0,0,0),(22,7,13,'Oyunlar ve Yaratıcılık','Video oyunları sadece eğlence aracı mı, yoksa yaratıcılığınızı geliştirmenin bir yolu mu? Oyunlarla ilgili bir hikaye, karakter veya dünya sizi derinlemesine etkileyen bir şekilde var mı?','2024-01-17 22:25:26',1,0,1,0),(23,7,14,'Doğanın İyileştirici Gücü','Doğada zaman geçirmenin size sağladığı sağlık avantajlarını düşündüğünüzde, doğanın iyileştirici gücü hakkındaki düşüncelerinizi paylaşabilir misiniz? En sevdiğiniz doğa deneyimini anlatır mısınız?','2024-01-17 22:25:37',1,0,2,1),(24,7,1,'Çeşitlilik ve Dahil Edicilik','Çeşitlilik ve dahil edicilik konularına ne kadar önem veriyorsunuz? Kendi çevrenizde veya iş yaşamınızda çeşitliliği teşvik etmek ve dahil edici bir ortam oluşturmak için neler yapıyorsunuz?','2024-01-17 22:26:10',0,0,2,1),(25,7,2,'Yapay Zeka ve Etik Sorunlar','Yapay zeka teknolojisinin etik boyutları hakkında nasıl düşünüyorsunuz? Bu teknolojinin toplum üzerindeki olası etkileri ve kontrol mekanizmaları konusundaki görüşlerinizi paylaşabilir misiniz?','2024-01-17 22:26:26',2,0,3,2),(26,7,4,'Distopya Edebiyatı ve Gerçeklik','Distopik kitapları okumak sizi nasıl etkiliyor? Bu tür eserlerdeki karamsar senaryoları düşündüğünüzde, gerçek hayatımızda bu temaların nasıl yansıdığını düşünüyor musunuz?','2024-01-17 22:26:38',2,1,4,2);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction_log`
--

DROP TABLE IF EXISTS `reaction_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reaction_log` (
  `user_id` int NOT NULL,
  `questions_id` int NOT NULL,
  `reaction_type` enum('like','dislike') NOT NULL,
  PRIMARY KEY (`questions_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reaction_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `reaction_log_ibfk_2` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`questions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction_log`
--

LOCK TABLES `reaction_log` WRITE;
/*!40000 ALTER TABLE `reaction_log` DISABLE KEYS */;
INSERT INTO `reaction_log` VALUES (6,18,'like'),(5,19,'like'),(6,19,'like'),(7,22,'like'),(6,23,'like'),(5,25,'like'),(6,25,'like'),(5,26,'dislike'),(6,26,'like');
/*!40000 ALTER TABLE `reaction_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_details`
--

DROP TABLE IF EXISTS `user_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_details` (
  `user_id` int NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `user_about` mediumtext,
  `number_of_questions` int DEFAULT '0',
  `number_of_answers` int DEFAULT '0',
  `country` varchar(50) DEFAULT NULL,
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_details`
--

LOCK TABLES `user_details` WRITE;
/*!40000 ALTER TABLE `user_details` DISABLE KEYS */;
INSERT INTO `user_details` VALUES (2,'canbmaj7','assets/images/user/pp.jpg','adana dan katlıyorum',5,0,'adana'),(3,'mehmet','assets/images/user/pp1.jpg','ashdkahdkajhdakdad',4,0,'ankara'),(4,'tedex','assets/images/user/pp6.jpg','<3',4,0,'izmir'),(5,'halil','assets/images/user/pp4.jpg','mersin',3,1,'mersin'),(6,'memo','assets/images/user/1038746-3489378170.jpg','.......',3,1,'yozgat'),(7,'Nathalia','assets/images/user/pp5.jpg','TWD',7,0,'BRAZIL');
/*!40000 ALTER TABLE `user_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_nickname` varchar(50) NOT NULL,
  `user_email` varchar(256) NOT NULL,
  `user_password` varchar(256) NOT NULL,
  `registration_date` datetime NOT NULL,
  `user_contract` tinyint DEFAULT NULL,
  `forgot` text,
  `authorization_id` int NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `authorization_id` (`authorization_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`authorization_id`) REFERENCES `authorizations` (`authorization_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'canbmaj7','ahmetcanotlu@gmail.com','$2y$10$OMJSscwh5CdDKr3tfF1gGuBowGxC/uu37hVED8kwwFT681Ypb2Ouq','2024-01-17 19:48:19',1,NULL,3),(3,'mehmet','mehmetemin.kyhn@gmail.com','$2y$10$Cl1/4RP3EHxfkv33RUaiN.GL4Qg839bFs.0AcBe03yY1PH2weLmIu','2024-01-17 19:48:29',1,NULL,3),(4,'tedex','tedex@gmail.com','$2y$10$EHBlZx5hWDnwoNfzzYtakOKs6bToDxRanpjfg.9ZeDm7UFPSN1UAa','2024-01-17 19:48:43',1,NULL,2),(5,'halil','halil@gmail.com','$2y$10$olgixhVnzEmy7D92v5FGVubdo1Pd3lCsPzv3KMkEq.v22LTyw9Jiy','2024-01-17 19:48:54',1,NULL,3),(6,'memo','memo@gmail.com','$2y$10$yQN3kEDigDhKZHXR886DTu0shvuHR0mBcUnMzIC8Dpy3uO9qlIfkm','2024-01-17 20:15:53',1,NULL,1),(7,'Nathalia','nati@gmail.com','$2y$10$TFvJZxNeKMbQW/nnSrGITOcYsj1M3wI6kDuKkECL9/NpttpnBINRW','2024-01-17 20:22:25',1,NULL,3);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `view_log`
--

DROP TABLE IF EXISTS `view_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `view_log` (
  `view_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `questions_id` int NOT NULL,
  PRIMARY KEY (`view_id`),
  UNIQUE KEY `unique_view` (`user_id`,`questions_id`),
  KEY `questions_id` (`questions_id`),
  CONSTRAINT `view_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `view_log_ibfk_2` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`questions_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_log`
--

LOCK TABLES `view_log` WRITE;
/*!40000 ALTER TABLE `view_log` DISABLE KEYS */;
INSERT INTO `view_log` VALUES (15,5,19),(14,5,25),(13,5,26),(1,6,18),(10,6,19),(12,6,23),(11,6,24),(9,6,25),(8,6,26),(3,7,1),(7,7,22),(6,7,23),(5,7,24),(4,7,25),(2,7,26);
/*!40000 ALTER TABLE `view_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-01-18 21:29:11
