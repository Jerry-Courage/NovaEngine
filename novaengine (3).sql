-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2025 at 08:26 PM
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
-- Table structure for table `chat_contacts`
--

CREATE TABLE `chat_contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `last_message_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_contacts`
--

INSERT INTO `chat_contacts` (`id`, `user_id`, `contact_id`, `last_message_time`) VALUES
(1, 16, 14, '2025-10-06 07:38:20'),
(2, 14, 16, '2025-10-06 07:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
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
(6, 14, 'Jerry Courage Yahkwenneh', 'Passionate about AI', 'Ghana', 'Start a robotic company', 'Technology,Marketting,Music,Computer science', 'Programming', 'funding', '+233257851583');

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
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investors`
--

INSERT INTO `investors` (`id`, `user_id`, `fullName_or_organization`, `bio`, `country`, `preferred_industries`, `investment_stage`, `investment_range`, `investment_type`, `investment_focus`, `phone`, `website`) VALUES
(2, 16, 'InvestCorp', 'Venture Capitalist supporting African startups in Tech', 'Ghana', 'Technology', 'Seed', '$5,000 - $10,000', 'Funding', 'Tech Innovations', '0244057628', '');

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

--
-- Dumping data for table `mentors`
--

INSERT INTO `mentors` (`id`, `user_id`, `fullName`, `bio`, `country`, `expertise`, `years_experience`, `mentee_focus`, `support_type`, `phone`) VALUES
(1, 10, 'Dr. Kofi Mensah', 'Tech entrepreneur and mentor in AI & IoT startups.', 'Ghana', 'Artificial Intelligence, IoT, Leadership', '10', 'Early-stage founders', 'Mentorship, Strategic Guidance', '+233200111222');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`) VALUES
(2, 14, 16, 'hi', 1, '2025-10-06 07:22:58'),
(3, 14, 16, 'Hello', 1, '2025-10-06 07:23:57'),
(4, 14, 16, 'hi', 1, '2025-10-06 07:24:15'),
(5, 14, 16, 'hi', 1, '2025-10-06 07:31:11'),
(6, 16, 14, 'Yh whats up', 1, '2025-10-06 07:32:00'),
(7, 14, 16, 'hi', 1, '2025-10-06 07:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `pitches`
--

CREATE TABLE `pitches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `goal` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `file_path` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `typing_status`
--

CREATE TABLE `typing_status` (
  `user_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('typing','stop') DEFAULT 'stop',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typing_status`
--

INSERT INTO `typing_status` (`user_id`, `receiver_id`, `status`, `updated_at`) VALUES
(14, 16, 'stop', '2025-10-06 07:38:20'),
(16, 14, 'stop', '2025-10-06 07:37:17');

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
(10, 'TechMentor', 'mentor1@example.com', '$2y$10$Kz3bDqsDemoPassxxxxxx', 'mentor', 1),
(12, 'InnovaCorp', 'company1@example.com', '$2y$10$Kz3bDqsDemoPassxxxxxx', 'company', 1),
(14, 'JSmith', 'jerry_2044@outlook.com', '$2y$10$FOXWmvBO2DjKrJNr3xAree1dRrbESB6T2Sy2Fa8hgUe0A3gZXB5N.', 'dreamer', 1),
(16, 'InvestorCorp', 'investor@gmail.com', '$2y$10$mfSPBfgVZ3UekASRdhxQrOEKqcNov5bFNQcAhBzE.BrKh2qrU53R2', 'investor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `user_id` int(11) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_online` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`user_id`, `last_active`, `is_online`) VALUES
(14, '2025-10-06 08:50:01', 1),
(16, '2025-10-06 09:01:37', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_contacts`
--
ALTER TABLE `chat_contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_chat` (`user_id`,`contact_id`),
  ADD KEY `contact_id` (`contact_id`);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sender` (`sender_id`),
  ADD KEY `idx_receiver` (`receiver_id`),
  ADD KEY `idx_sender_receiver` (`sender_id`,`receiver_id`,`created_at`);

--
-- Indexes for table `pitches`
--
ALTER TABLE `pitches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `typing_status`
--
ALTER TABLE `typing_status`
  ADD PRIMARY KEY (`user_id`,`receiver_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_contacts`
--
ALTER TABLE `chat_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dreamers`
--
ALTER TABLE `dreamers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `investors`
--
ALTER TABLE `investors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mentors`
--
ALTER TABLE `mentors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pitches`
--
ALTER TABLE `pitches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_contacts`
--
ALTER TABLE `chat_contacts`
  ADD CONSTRAINT `chat_contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_contacts_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `pitches`
--
ALTER TABLE `pitches`
  ADD CONSTRAINT `pitches_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
