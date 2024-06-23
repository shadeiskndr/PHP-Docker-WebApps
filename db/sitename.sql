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
-- Database: `sitename`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `first_name` varchar(15) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `registration_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `registration_date`) VALUES
(1, 'John', 'Lennon', 'john@beatles.com', '2a50435b0f512f60988db719106a258fb7e338ff', '2022-11-11 14:52:57'),
(2, 'Paul', 'McCartney', 'paul@beatles.com', '6ae16792c502a5b47da180ce8456e5ae7d65e262', '2022-11-11 14:52:57'),
(3, 'George', 'Harrison', 'george@beatles.com', '1af17e73721dbe0c40011b82ed4bb1a7dbe3ce29', '2022-11-11 14:52:57'),
(4, 'Ringo', 'Starr', 'ringo@beatles.com', '520f73691bcf89d508d923a2dbc8e6fa58efb522', '2022-11-11 14:52:57'),
(5, 'Shahathir', 'Iskandar', 'shahathir@beatles.com', '1479fd8b0ff0f8dcbb6269bf3130410ef767f8b3', '2022-11-11 14:52:57'),
(6, 'Larrie', 'Ullman', 'larrie@beatles.com', '265b11d407c862ea192a8410d23f331de8c26758', '2022-11-11 15:34:29'),
(9, 'Shah', ' Is', 'shahis@getnada.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2022-12-16 14:50:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
