-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2024 at 06:14 AM
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
-- Database: `cinema_ticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(1, 'Action/Adventure'),
(2, 'Comedy'),
(3, 'Drama'),
(4, 'Fantasy/Sci-Fi');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `year` varchar(4) NOT NULL,
  `genre` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `ticket_price` decimal(15,2) NOT NULL,
  `mdate` date NOT NULL,
  `mtime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `name`, `year`, `genre`, `rating`, `ticket_price`, `mdate`, `mtime`) VALUES
(1, 'Wednesday', '2022', 4, 5, 9.99, '2022-11-28', '23:59:00'),
(2, 'The Adam Project', '2021', 2, 4, 5.50, '2022-06-08', '18:25:00'),
(6, 'The Office', '2005', 2, 5, 5.50, '2005-03-23', '11:36:00'),
(8, 'Bladerunner 2049', '2017', 1, 5, 7.99, '2017-01-08', '08:43:00'),
(9, 'The Gray Man', '2022', 1, 5, 7.68, '2022-08-08', '20:44:00'),
(10, 'Silver Skates', '2020', 3, 5, 7.20, '2020-07-13', '00:54:00'),
(11, 'Interstellar', '2014', 4, 5, 9.30, '2014-11-06', '07:47:00'),
(12, 'Enola Holmes', '2020', 3, 5, 5.30, '2020-07-15', '07:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- Add poster_url column to movies table for movie poster support
ALTER TABLE `movies` ADD COLUMN `poster_url` VARCHAR(255) NULL AFTER `mtime`;

-- Add index for better performance when searching by poster
ALTER TABLE `movies` ADD INDEX `idx_poster_url` (`poster_url`);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
