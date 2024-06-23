-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2024 at 06:13 AM
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
-- Database: `carforsale`
--

-- --------------------------------------------------------

--
-- Table structure for table `carforsale`
--

CREATE TABLE `carforsale` (
  `carForSaleID` int(11) NOT NULL,
  `manufacturerName` varchar(15) NOT NULL,
  `modelName` varchar(15) NOT NULL,
  `acquisitionPrice` int(11) NOT NULL,
  `dateAcquired` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carforsale`
--

INSERT INTO `carforsale` (`carForSaleID`, `manufacturerName`, `modelName`, `acquisitionPrice`, `dateAcquired`) VALUES
(1, 'Volkswagen', 'Jetta', 13300, '2007-01-07'),
(2, 'Renault', 'Laguna', 14700, '2007-02-12'),
(3, 'Ford', 'Focus', 13600, '2007-03-09'),
(4, 'Daewoo', 'Tico', 1100, '2007-04-17'),
(5, 'Toyota', 'Avensis', 14500, '2007-05-04'),
(6, 'Alfa Romeo', '156', 8700, '2007-06-23'),
(7, 'Volkswagen', 'Passat', 22200, '2007-07-16'),
(8, 'Renault', 'Clio', 6400, '2007-08-22'),
(9, 'Ford', 'Fiesta', 6900, '2007-09-11'),
(10, 'Daewoo', 'Cielo', 3600, '2007-10-18'),
(11, 'Toyota', 'Rav4', 24900, '2007-11-11'),
(12, 'Alfa Romeo', '147', 7500, '2007-12-25');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturername`
--

CREATE TABLE `manufacturername` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturername`
--

INSERT INTO `manufacturername` (`id`, `name`) VALUES
(1, 'Volkswagen'),
(2, 'Renault'),
(3, 'Ford'),
(4, 'Daewoo'),
(5, 'Toyota'),
(6, 'Alfa Romeo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carforsale`
--
ALTER TABLE `carforsale`
  ADD PRIMARY KEY (`carForSaleID`);

--
-- Indexes for table `manufacturername`
--
ALTER TABLE `manufacturername`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carforsale`
--
ALTER TABLE `carforsale`
  MODIFY `carForSaleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `manufacturername`
--
ALTER TABLE `manufacturername`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
