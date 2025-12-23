-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 05:48 PM
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
-- Database: `booking_app_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'zeyad', 'zeyadsamir27@gmail.com', 'General Inquiry', 'dcdcdcdcd', '2025-12-22 15:30:49'),
(2, 'zeyadm', 'zeyadsamir1980@gmail.com', 'General Inquiry', 'i cant book', '2025-12-22 15:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `id` int(11) NOT NULL,
  `flight_number` varchar(20) NOT NULL,
  `departure_city` varchar(50) NOT NULL,
  `arrival_city` varchar(50) NOT NULL,
  `departure_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `airline` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`id`, `flight_number`, `departure_city`, `arrival_city`, `departure_date`, `return_date`, `price`, `airline`, `created_at`) VALUES
(1, '1111', 'cairo', 'dubai', '2025-11-11', '2025-12-12', 2222.00, 'Flyadel', '2025-10-20 17:43:02'),
(2, '12345', 'cairo', 'dubai', '2025-11-12', '2025-11-20', 200.00, 'EgyptAir', '2025-10-21 07:20:28'),
(3, '111', 'cairo', 'riydah', '2025-12-27', NULL, 200.00, 'Flynas', '2025-12-22 01:36:00'),
(4, '2222', 'sssss', 'dddd', '2025-12-27', NULL, 400.00, 'EmiratesAir', '2025-12-22 01:37:52'),
(5, '222222', 'ddddd', 'wwwwww', '2025-12-27', NULL, 33333.00, 'Flyadel', '2025-12-22 01:41:20'),
(6, '22222', 'cairo', 'saudi', '2026-01-01', NULL, 222.00, 'Flyadel', '2025-12-22 01:58:39'),
(7, '1111', 'dededed', 'dededdeded', '2025-12-25', NULL, 333.00, 'QatarAir', '2025-12-22 02:02:18'),
(8, '333333', 'dddd', 'ggggg', '2025-12-25', NULL, 3333.00, 'Flynas', '2025-12-22 02:18:31'),
(9, '333333', 'dddd', 'ggggg', '2025-12-24', NULL, 3333.00, 'SaudiAir', '2025-12-22 14:20:03'),
(10, '2938383', 'cairo', 'meka', '2026-01-08', NULL, 1000.00, 'EgyptAir', '2025-12-22 15:23:21');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `hotel_name` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `stars` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `hotel_name`, `city`, `price_per_night`, `rating`, `created_at`, `check_in`, `check_out`, `stars`) VALUES
(1, 'Cairo Grand Hotel', 'Egypt', 222.00, NULL, '2025-12-22 01:56:55', '1111-02-11', '2222-02-22', 3),
(2, 'Cairo Grand Hotel', 'Egypt', 200.00, NULL, '2025-12-22 01:57:08', '2025-12-31', '2026-01-02', 3),
(3, 'Cairo Grand Hotel', 'Egypt', 100.00, NULL, '2025-12-22 01:57:38', '2025-12-24', '2026-01-01', 5),
(4, 'Nile View Resort', 'Egypt', 500.00, NULL, '2025-12-22 02:16:31', '2025-12-17', '2026-01-01', 4),
(5, 'Jeddah Sea View', 'Saudi', 100.00, NULL, '2025-12-22 02:20:09', '2025-12-31', '2026-01-01', 5);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT '#',
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `booking_type` enum('flight','hotel') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'zeyad', 'zeyadsamir27@gmail.com', '$2y$10$X9hfLcM90SC.bXdzVBdqEeQnkvmQegVZtl3JDQVORYK7/F6LIxiOW', '2025-10-20 17:46:48', 'admin'),
(2, 'zeyad', 'zeyadsamir1980@gmail.com', '$2y$10$V4DdFUiUZW6Mv0SAj5IgUO5PpT4TVY5LoV9tceM5t6mRDzmyHgPnG', '2025-10-21 07:19:08', 'user'),
(3, 'zeyad mohamed samiue', 'ssssssssss@gmail.com', '$2y$10$KLSZfQJQjHhUKCAl1pQt7uS/vWoObhGe03234D2.t.BONgIo33p/.', '2025-12-08 21:14:22', 'user'),
(5, 'zeyadzoz', 'myriamzeyad@gmail.com', '$2y$10$ztSwWIzrtun4j9pegQ.rOuB6RsjceBZDGS5mK64nYc7.pYggJXkmW', '2025-12-22 00:36:23', 'user'),
(6, 'SuperAdmin', 'Admin@test.com', '$2y$10$B5.5uiAP30yTGNavXOIV8.nUueY.q0r2sf4Y9SZrybaecSDnxFVAy', '2025-12-22 14:51:03', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `flight_id` (`flight_id`),
  ADD KEY `hotel_id` (`hotel_id`);

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
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
