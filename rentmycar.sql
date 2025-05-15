-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 01, 2021 at 03:06 PM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rentmycar`
--
CREATE DATABASE IF NOT EXISTS `rentmycar` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `rentmycar`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `PASSWORD` varchar(10) NOT NULL,
  `title` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `adress1` varchar(50) NOT NULL,
  `adress2` varchar(50) DEFAULT NULL,
  `adress3` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `profile_blob` longblob,
  `profile_url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_details`
--

DROP TABLE IF EXISTS `vehicle_details`;
CREATE TABLE IF NOT EXISTS `vehicle_details` (
  `vehicle_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vehicle_make` varchar(50) NOT NULL,
  `vehicle_model` varchar(100) NOT NULL,
  `vehicle_bodytype` varchar(500) NOT NULL,
  `fuel_type` varchar(100) NOT NULL,
  `mileage` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `year` varchar(5) NOT NULL,
  `num_doors` int(2) NOT NULL,
  `video_url` varchar(100) DEFAULT NULL,
  `image_url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`vehicle_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
