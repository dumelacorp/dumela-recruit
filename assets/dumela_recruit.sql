-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 21, 2024 at 07:54 AM
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
-- Database: `dumela_recruit`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `job_title` varchar(50) NOT NULL,
  `level` varchar(20) NOT NULL,
  `resume` varchar(255) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `rate_period` varchar(10) NOT NULL,
  `status` varchar(50) NOT NULL,
  `outsource_rate` decimal(15,2) NOT NULL,
  `outsource_rate_period` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `country`, `state`, `city`, `job_title`, `level`, `resume`, `rate`, `rate_period`, `status`, `outsource_rate`, `outsource_rate_period`, `created_at`) VALUES
(3, 'Babajide', 'Johnson', 'Odejide', 'bo5104a@american.edu', 'Nigeria', 'FCT', 'Abuja', 'Software Engineer', 'Junior', 'AU Award-BJO.pdf', 60000.00, 'Week', 'Not Interviewed', 800000.00, 'Week', '2024-06-20 17:04:02'),
(6, 'Johnson', '', 'Odejide', 'joluwayimika@gmail.com', 'United States', 'VA', 'Alexandria', 'Data Scientist', 'Intermediate', 'I765_Additional_Responses.pdf', 5000.00, 'Hour', 'Not Interviewed', 5000.00, 'Entry', '2024-06-21 02:25:20'),
(7, 'Irimiya', '', 'Thomas', 'Irimiya@gmail.com', 'Nigeria', 'Kaduna', 'Kachia', 'Data Analyst', 'Junior', 'I765_Additional_Responses.pdf', 50000.00, 'Week', 'Not Interviewed', 80000.00, 'Week', '2024-06-21 05:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'yimika@dumelacorp.com', '$2y$10$lPvJelKw0dhUnTN2jFMoWOMW8uHnsOusDfNASKHpoeFZg7xht8z9y', '2024-06-19 05:31:19'),
(6, 'y@dumelacorp.com', '$2y$10$P7yKzc.oQmvw5JhG8DaKR.1ZltLkWoH6FkGyXe8X0uM.hArLbCD5e', '2024-06-19 20:22:33'),
(8, 'ayo@dumelacorp.com', '$2y$10$ZDt2KPY5kcMbbGkgu/ZzgObTx3GkLESgyl/Agz/oJ.ZQ71pSoG4i6', '2024-06-21 04:03:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
