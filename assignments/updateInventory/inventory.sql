-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2022 at 06:02 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sitename`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `category` varchar(200) NOT NULL,
  `product` varchar(200) NOT NULL,
  `size` char(5) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`category`, `product`, `size`, `quantity`) VALUES
('Baju', 'Abaya', 'L', 2),
('Baju', 'Abaya', 'M', 25),
('Baju', 'Abaya', 'S', 50),
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
