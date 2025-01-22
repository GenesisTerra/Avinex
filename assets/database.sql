-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2024 at 06:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_table`
--

CREATE TABLE `blog_table` (
  `id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `topic_title` varchar(255) NOT NULL,
  `image_filename` varchar(255) DEFAULT 'NONE',
  `topic_para` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_table`
--

INSERT INTO `blog_table` (`id`, `user_id`, `topic_title`, `image_filename`, `topic_para`, `created_at`) VALUES
(57, 'gen', 'hello', 'logo.png', 'how do you do', '2024-10-20 16:26:36'),
(58, 'gen', 'hey', 'background2.jpg', 'wats up', '2024-10-20 16:26:59'),
(59, 'terra', 'happy birthday', '1000035766.jpg', 'happpy birthday', '2024-10-20 16:27:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `lock_until` int(11) DEFAULT 0,
  `last_attempt` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `user_id`, `email`, `mobile_number`, `password`, `failed_attempts`, `lock_until`, `last_attempt`, `created_at`) VALUES
(1, 'Aman Hegde', 'GEN', 'amanhegde2527@gmail.com', '7506202138', '$2y$10$Pqb9v/oAqp1hJ7rK2veeruQO3OIN8b8KM.I47NaL6q4nM2p8LI2/i', 0, 0, 1729352984, '2024-09-08 03:27:07'),
(2, 'Aman Hegde', 'terra', 'amanhegde2527@gmail.com', '07506202138', '$2y$10$6PiYwM0gSdR5NKzOF7uYcetC39wC/CsjWmkL5iWzn2js/CCug7wX6', 0, 0, 1729072997, '2024-09-08 09:20:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_table`
--
ALTER TABLE `blog_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_table`
--
ALTER TABLE `blog_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
