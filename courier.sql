-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: November 11, 2024 at 09:06 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = '+05:45';

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;

-- Database: `courier`

-- --------------------------------------------------------

-- Table structure for table `customers`
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `address` VARCHAR(100) NOT NULL,
  `phone` BIGINT(20) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Empty the `customers` table
TRUNCATE TABLE `customers`;

-- Reset AUTO_INCREMENT for `customers`
ALTER TABLE `customers` AUTO_INCREMENT = 1;

-- --------------------------------------------------------

-- Table structure for table `employee`
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) DEFAULT NULL, -- Made username nullable with default NULL
  `password` VARCHAR(255) NOT NULL,
  `education` VARCHAR(40) NOT NULL,
  `designation` VARCHAR(30) NOT NULL,
  `address` VARCHAR(100) NOT NULL,
  `phone` BIGINT(20) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) -- Ensure username is unique
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Empty the `employee` table
TRUNCATE TABLE `employee`;

-- Reset AUTO_INCREMENT for `employee`
ALTER TABLE `employee` AUTO_INCREMENT = 1;

-- --------------------------------------------------------

-- Table structure for table `orders`
DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `customer_id` INT(11) NOT NULL,
  `customer_email` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `phone` BIGINT(20) NOT NULL CHECK (`phone` > 0),
  `toname` VARCHAR(255) NOT NULL,
  `toaddress` VARCHAR(255) NOT NULL,
  `tophone` BIGINT(20) NOT NULL CHECK (`tophone` > 0),
  `weight` VARCHAR(100) NOT NULL,
  `price` INT(11) NOT NULL DEFAULT 0 CHECK (`price` >= 0),
  `status` ENUM('approved', 'pending','declined') NOT NULL DEFAULT 'pending',
  `delivery_received_status` ENUM('received', 'not received') NOT NULL DEFAULT 'not received',
  `delivery_delivered_status` ENUM('delivered', 'not delivered') NOT NULL DEFAULT 'not delivered',
  `received_time` DATETIME DEFAULT NULL,
  `delivery_time` DATETIME DEFAULT NULL,
  `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `received_by_emp_name` VARCHAR(50) DEFAULT NULL, -- Stores employee username permanently
  `delivered_by_emp_name` VARCHAR(50) DEFAULT NULL, -- Stores employee username permanently
  PRIMARY KEY (`id`),
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`customer_email`) REFERENCES `customers`(`email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Empty the `orders` table
TRUNCATE TABLE `orders`;

-- Reset AUTO_INCREMENT for `orders`
ALTER TABLE `orders` AUTO_INCREMENT = 1;

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
