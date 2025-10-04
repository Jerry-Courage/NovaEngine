-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2025 at 05:28 PM
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
-- Database: `novaengine`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `about_company` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `company_field` text DEFAULT NULL,
  `opportunity_offered` text DEFAULT NULL,
  `skills_talent_interested_in` text DEFAULT NULL,
  `type` varchar(225) DEFAULT NULL,
  `phone` varchar(225) DEFAULT NULL,
  `website` varchar(225) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dreamers`
--

CREATE TABLE `dreamers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullName` varchar(225) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `country` varchar(225) DEFAULT NULL,
  `goal` text DEFAULT NULL,
  `field_of_interest` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `opportunity_seeking` text DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dreamers`
--

INSERT INTO `dreamers` (`id`, `user_id`, `fullName`, `bio`, `country`, `goal`, `field_of_interest`, `skills`, `opportunity_seeking`, `number`) VALUES
(1, 7, 'Jerry Smith', 'Aspiring Tech C.E.O', 'Ghana', 'Start a tech company', '0', '0', '0', '+233257851583'),
(2, 7, 'Jerry Smith', 'Aspiring Tech C.E.O', 'Ghana', 'Start a tech company', '0', '0', '0', '+233257851583');

-- --------------------------------------------------------

--
-- Table structure for table `investors`
--

CREATE TABLE `investors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullName_or_organization` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `preferred_industries` text DEFAULT NULL,
  `investment_stage` text DEFAULT NULL,
  `investment_range` text DEFAULT NULL,
  `investment_type` text DEFAULT NULL,
  `investment_focus` varchar(255) DEFAULT NULL,
  `phone` varchar(225) DEFAULT NULL,
  `wesite` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mentors`
--

CREATE TABLE `mentors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `expertise` text DEFAULT NULL,
  `years_experience` varchar(255) DEFAULT NULL,
  `mentee_focus` text DEFAULT NULL,
  `support_type` text NOT NULL,
  `phone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('mentor','dreamer','investor','company') NOT NULL,
  `profile_complete` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `user_type`, `profile_complete`) VALUES
(7, 'DanceMonkey', 'jerry_2044@outlook.com', '$2y$10$Mc.cy14n94/lXG8TDNZYeOUP1tF2rpy98ZjZYTmBfcJv/NLULQnqC', 'dreamer', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dreamers`
--
ALTER TABLE `dreamers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `investors`
--
ALTER TABLE `investors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `mentors`
--
ALTER TABLE `mentors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dreamers`
--
ALTER TABLE `dreamers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `investors`
--
ALTER TABLE `investors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mentors`
--
ALTER TABLE `mentors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `dreamers`
--
ALTER TABLE `dreamers`
  ADD CONSTRAINT `dreamers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `investors`
--
ALTER TABLE `investors`
  ADD CONSTRAINT `investors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `mentors`
--
ALTER TABLE `mentors`
  ADD CONSTRAINT `mentors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
