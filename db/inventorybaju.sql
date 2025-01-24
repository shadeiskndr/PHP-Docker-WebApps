-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2024 at 06:17 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventorybaju`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventorybaju`
--

CREATE TABLE `inventorybaju` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category` varchar(200) NOT NULL,
  `product` varchar(200) NOT NULL,
  `size` char(5) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_product` (`category`, `product`, `size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventorybaju`
--

INSERT INTO `inventorybaju` (`category`, `product`, `size`, `quantity`) VALUES
('Baju', 'Abaya', 'L', 60),
('Baju', 'Abaya', 'M', 25),
('Baju', 'Abaya', 'XS', 50),
('Baju', 'Abaya', 'XL', 50),
('Baju', 'Kemeja', 'S', 25),
('Baju', 'Kemeja', 'M', 15),
('Baju', 'Kemeja', 'L', 45),
('Baju', 'Kemeja', 'XL', 5),
('Telekung', 'Sulam-OneSet', 'L', 35),
('Telekung', 'Sulam-OneSet', 'M', 35),
('Telekung', 'Plain-TudungOnly', 'L', 35),
('Telekung', 'Plain-TudungOnly', 'M', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
