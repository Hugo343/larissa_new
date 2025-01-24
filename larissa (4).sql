-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 08:22 PM
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
-- Database: `larissa`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `service_id`, `appointment_date`, `appointment_time`, `status`, `created_at`) VALUES
(1, 3, 9, '2025-03-26', '12:00:00', 'pending', '2025-01-24 13:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Potong & Styling'),
(2, 'Perawatan Kuku'),
(3, 'Make Up & Hairdo Paket Reguler'),
(4, 'Make Up & Hairdo Paket Hemat'),
(5, 'Wedding Package');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category_id`, `name`, `description`, `price`, `duration`) VALUES
(1, 1, 'Cuci & Potong Rambut', 'Layanan cuci dan potong rambut', 60000.00, 60),
(2, 1, 'Cuci Catok Blow/Curly', 'Layanan cuci dan catok rambut', 60000.00, 60),
(3, 1, 'Cuci Catok Dry Standar', 'Layanan cuci dan catok rambut standar', 60000.00, 60),
(4, 1, 'Cuci Wave', 'Layanan cuci dan wave rambut', 50000.00, 45),
(5, 2, 'Nail Gel Polos (1-2 Warna)', 'Perawatan kuku dengan gel polos', 60000.00, 45),
(6, 2, 'Nail Art (2-3 Motif)', 'Perawatan kuku dengan nail art', 80000.00, 60),
(7, 2, 'Nail Art Full Cat Eye', 'Perawatan kuku dengan nail art cat eye', 90000.00, 75),
(8, 2, 'Nail Art Full Design', 'Perawatan kuku dengan nail art full design', 100000.00, 90),
(9, 3, 'Prewedding', 'Layanan make up dan hairdo untuk prewedding', 450000.00, 120),
(10, 3, 'Graduation (Hairdo)', 'Layanan hairdo untuk wisuda', 270000.00, 90),
(11, 3, 'Lamaran/Tunangan', 'Layanan make up dan hairdo untuk lamaran atau tunangan', 290000.00, 100),
(12, 3, 'Party', 'Layanan make up dan hairdo untuk pesta', 210000.00, 75),
(13, 4, 'Paket Hemat 70K', 'Potong Rambut, Cuci Blow, Vitamin Rambut', 70000.00, 90),
(14, 4, 'Paket Hemat 100K', 'Potong Rambut, Creambath/Hair Mask, Catok Blow Vitamin', 100000.00, 120),
(15, 4, 'Paket 150K', 'Creambath, Catok Blow Vitamin, Nail Gel Polos, Eyelash Extension Natural', 150000.00, 180),
(16, 5, 'Wedding Package', 'Paket lengkap untuk pernikahan', 5000000.00, 480);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `created_at`) VALUES
(3, 'hugo123', 'hugo@gmail.com', '$2y$10$77Ej0VdEWj.REY0f0xWQveA4glKSGXbAHYYKYHHTvmeZ3.83c1oMi', 'hugogabriel', '0988218323', '2025-01-24 11:27:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_service_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `fk_service_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
