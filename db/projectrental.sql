-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2024 at 06:18 AM
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
-- Database: `projectrental`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contactNo` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `registration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `name`, `contactNo`, `address`, `email`, `password`, `registration_date`) VALUES
(1, 'Shahathir', '0123456789', 'Gombak', 'shahis@getnada.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '2022-12-28'),
(2, 'John', '0173985932', 'Ampang', 'john@apple.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2022-12-28'),
(4, 'Kojimbo', '01326287854', 'Kuala Lumpur', 'kojimbo@kjp.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '2022-12-29'),
(5, 'Fragile', '01176285784', 'Ipoh', 'fragile@strand.com', 'a9993e364706816aba3e25717850c26c9cd0d89d', '2022-12-29'),
(6, 'Malingen', '01928837664', 'Petaling Jaya', 'malingen@mama.com', 'f38cfe2e2facbcc742bad63f91ad55637300cb45', '2022-12-29');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `managerID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`managerID`, `name`, `email`, `password`) VALUES
(1, 'Mr. Sam Porter Bridges', 'sambridges@admin.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `rentalID` int(11) NOT NULL,
  `startDate` datetime NOT NULL,
  `returnDate` datetime NOT NULL,
  `durationNo` int(11) NOT NULL,
  `hourOrDay` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `vehicleID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental`
--

INSERT INTO `rental` (`rentalID`, `startDate`, `returnDate`, `durationNo`, `hourOrDay`, `status`, `totalPrice`, `vehicleID`, `customerID`) VALUES
(3, '2023-01-28 10:25:00', '2023-01-28 13:25:00', 3, 'Hour', 'Approved', 126.00, 5, 1),
(4, '2023-01-03 01:30:00', '2023-01-05 01:30:00', 2, 'Day', 'Pending', 3001.00, 8, 1),
(5, '2023-02-16 19:30:00', '2023-02-17 07:30:00', 12, 'Hour', 'Pending', 1458.00, 2, 2),
(6, '2023-01-15 00:30:00', '2023-01-20 00:30:00', 5, 'Day', 'Pending', 1150.00, 6, 2),
(7, '2023-01-18 15:30:00', '2023-01-18 21:30:00', 6, 'Hour', 'Pending', 325.38, 9, 4),
(8, '2023-02-28 14:35:00', '2023-03-07 14:35:00', 7, 'Day', 'Approved', 12954.90, 12, 4),
(9, '2023-01-09 14:00:00', '2023-01-11 14:00:00', 2, 'Day', 'Pending', 1220.00, 3, 5),
(10, '2023-04-01 19:24:00', '2023-04-02 05:24:00', 10, 'Hour', 'Pending', 420.00, 5, 5),
(11, '2023-03-24 22:00:00', '2023-03-27 22:00:00', 3, 'Day', 'Approved', 5102.40, 13, 6),
(12, '2023-02-18 11:35:00', '2023-02-22 11:35:00', 4, 'Day', 'Pending', 4401.56, 11, 6),
(14, '2023-01-03 19:13:00', '2023-01-03 22:13:00', 3, 'Hour', 'Pending', 66.45, 3, 1),
(16, '2023-01-07 10:48:00', '2023-01-07 22:48:00', 12, 'Hour', 'Approved', 114.48, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicleID` int(11) NOT NULL,
  `vehicleType` varchar(255) NOT NULL,
  `vehicleModel` varchar(255) NOT NULL,
  `ratePerHour` decimal(10,2) NOT NULL,
  `ratePerDay` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`vehicleID`, `vehicleType`, `vehicleModel`, `ratePerHour`, `ratePerDay`) VALUES
(1, 'Motorcycle', 'Toyota Altis', 15.90, 450.00),
(2, 'Truck', 'Ford F-150', 121.50, 3230.20),
(3, 'Car', 'Honda CR-V', 22.15, 610.00),
(5, 'Car', 'BMW 330E', 42.00, 1200.00),
(6, 'Car', 'Proton Iriz', 9.54, 235.00),
(7, 'Truck', 'GMC Sierra', 101.00, 2640.00),
(8, 'Motorcycle', 'Honda EX-5', 60.10, 1500.50),
(9, 'Motorcycle', 'Yamaha Y15ZR', 54.23, 1440.22),
(10, 'Van', 'Nissan NV200', 35.02, 950.59),
(11, 'Van', 'Ford Transit', 42.12, 1100.39),
(12, 'Bus', 'Volvo B9TL', 70.13, 1850.70),
(13, 'Bus', 'Isuzu NQR', 69.20, 1700.80),
(14, 'Mixer', 'Concrete', 100.00, 2500.00);

-- --------------------------------------------------------

--
-- Table structure for table `vehicletypes`
--

CREATE TABLE `vehicletypes` (
  `v_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicletypes`
--

INSERT INTO `vehicletypes` (`v_id`, `type`) VALUES
(1, 'Car'),
(2, 'Motorcycle'),
(3, 'Truck'),
(4, 'Bus'),
(5, 'Crane'),
(6, 'Van'),
(7, 'Trailer'),
(8, 'Mixer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`managerID`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`rentalID`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicleID`);

--
-- Indexes for table `vehicletypes`
--
ALTER TABLE `vehicletypes`
  ADD PRIMARY KEY (`v_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `managerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `rentalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `vehicleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vehicletypes`
--
ALTER TABLE `vehicletypes`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
