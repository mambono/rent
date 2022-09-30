-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.38-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for bike_rental
CREATE DATABASE IF NOT EXISTS `bike_rental` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `bike_rental`;

-- Dumping structure for table bike_rental.bikes
DROP TABLE IF EXISTS `bikes`;
CREATE TABLE IF NOT EXISTS `bikes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT '0',
  `vendor_id` int(11) DEFAULT '0',
  `short_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT '0',
  `color` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT '0',
  `hourly_cost` decimal(20,6) DEFAULT '0.000000',
  `size` tinyint(2) DEFAULT '0',
  `electric` tinyint(1) DEFAULT '0',
  `gear_speed` int(11) DEFAULT '0',
  `created_by` int(11) DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT '0',
  `modified_on` datetime DEFAULT NULL,
  `delete_flag` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK__cities` (`city_id`),
  KEY `FK__users` (`vendor_id`),
  CONSTRAINT `FK__cities` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `FK__users` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Dumping data for table bike_rental.bikes: ~3 rows (approximately)
/*!40000 ALTER TABLE `bikes` DISABLE KEYS */;
INSERT INTO `bikes` (`id`, `city_id`, `vendor_id`, `short_name`, `color`, `hourly_cost`, `size`, `electric`, `gear_speed`, `created_by`, `created_on`, `modified_by`, `modified_on`, `delete_flag`) VALUES
	(1, 1, 1, 'Comerera', 'Black', 50.000000, 5, 1, 21, NULL, '2022-09-28 19:08:16', NULL, '2022-09-28 19:24:29', 0),
	(2, 4, NULL, 'Mountain Bike', 'Blue', 65.000000, 12, 0, 18, NULL, '2022-09-28 19:42:08', NULL, '2022-09-28 19:42:40', 0),
	(3, 5, NULL, 'Electric Scooter', 'Green', 120.000000, 15, 1, 6, NULL, '2022-09-28 19:43:13', 0, NULL, 0);
/*!40000 ALTER TABLE `bikes` ENABLE KEYS */;

-- Dumping structure for table bike_rental.bike_bookings
DROP TABLE IF EXISTS `bike_bookings`;
CREATE TABLE IF NOT EXISTS `bike_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booked_by` int(11) DEFAULT NULL,
  `bike_id` int(11) DEFAULT NULL,
  `booked_on` datetime DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `delete_flag` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_bike_bookings_users` (`booked_by`),
  KEY `FK_bike_bookings_bikes` (`bike_id`),
  CONSTRAINT `FK_bike_bookings_bikes` FOREIGN KEY (`bike_id`) REFERENCES `bikes` (`id`),
  CONSTRAINT `FK_bike_bookings_users` FOREIGN KEY (`booked_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table bike_rental.bike_bookings: ~0 rows (approximately)
/*!40000 ALTER TABLE `bike_bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bike_bookings` ENABLE KEYS */;

-- Dumping structure for table bike_rental.cities
DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `delete_flag` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `counties_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

-- Dumping data for table bike_rental.cities: ~5 rows (approximately)
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` (`id`, `city`, `created_by`, `created_on`, `modified_by`, `modified_on`, `delete_flag`) VALUES
	(1, 'Mombasa', 1, '2022-09-30 10:34:40', NULL, '2022-09-30 10:45:30', 0),
	(2, 'Eldoret', 1, '2022-09-30 10:35:10', NULL, '2022-09-30 10:55:30', 0),
	(3, 'Nakuru', 1, '2022-09-30 10:35:30', NULL, NULL, 0),
	(4, 'Kisumu', 1, '2022-09-30 10:35:30', NULL, NULL, 0),
	(5, 'Nairobi', 1, '2022-09-30 10:35:30', NULL, NULL, 0);
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;

-- Dumping structure for table bike_rental.feedback
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL DEFAULT '0',
  `comments` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `rating` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `delete_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK__bookings` (`booking_id`) USING BTREE,
  CONSTRAINT `FK_feedback_bike_bookings` FOREIGN KEY (`booking_id`) REFERENCES `bike_bookings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Dumping data for table bike_rental.feedback: ~0 rows (approximately)
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;

-- Dumping structure for table bike_rental.usergroups
DROP TABLE IF EXISTS `usergroups`;
CREATE TABLE IF NOT EXISTS `usergroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `delete_flag` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table bike_rental.usergroups: ~3 rows (approximately)
/*!40000 ALTER TABLE `usergroups` DISABLE KEYS */;
INSERT INTO `usergroups` (`id`, `name`, `created_by`, `created_on`, `modified_by`, `modified_on`, `delete_flag`) VALUES
	(1, 'Administrator', 1, '2022-09-30 11:35:30', NULL, NULL, 0),
	(2, 'Vendor', 1, '2022-09-30 11:35:30', NULL, NULL, 0),
	(3, 'Standard', 1, '2022-09-30 11:35:30', NULL, NULL, 0);
/*!40000 ALTER TABLE `usergroups` ENABLE KEYS */;

-- Dumping structure for table bike_rental.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `delete_flag` tinyint(1) DEFAULT '1',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table bike_rental.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `group_id`, `password`, `first_name`, `last_name`, `email`, `mobile_number`, `delete_flag`, `created_on`) VALUES
	(1, 0, 'fcf8fb3ca42e0dd5d86de9c32f30aeba', 'System', 'Admin', 'admin@bikes.com', NULL, 1, '2022-09-30 12:14:26');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
