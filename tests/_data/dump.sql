/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.6.19 : Database - currency
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cur_currency` */
START TRANSACTION;

DROP TABLE IF EXISTS `cur_currency`;

CREATE TABLE `cur_currency` (
  `symbol` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `rate` decimal(19,4) NOT NULL,
  PRIMARY KEY (`symbol`),
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cur_currency` */

insert  into `cur_currency`(`symbol`,`rate`) values ('USD',75.0000),('EUR',85.0000),('RUB',1.0000),('UAH',37.0000);

COMMIT;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
