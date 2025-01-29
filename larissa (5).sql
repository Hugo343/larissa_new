-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 12:29 AM
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
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_revenue` decimal(10,2) GENERATED ALWAYS AS (`price`) STORED,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `service_id`, `appointment_date`, `appointment_time`, `status`, `created_at`, `price`, `completed_at`) VALUES
(1, 3, 9, '2025-03-26', '12:00:00', 'confirmed', '2025-01-24 13:32:14', 450000.00, '2025-03-25 17:00:00'),
(2, 3, 12, '2025-02-12', '00:00:00', 'confirmed', '2025-01-28 12:20:35', 210000.00, '2025-02-11 17:00:00'),
(3, 3, 6, '2025-03-12', '12:12:00', 'cancelled', '2025-01-28 12:36:49', 80000.00, NULL),
(5, 3, 14, '2025-02-25', '00:00:00', 'confirmed', '2025-01-28 12:54:06', 100000.00, NULL),
(7, 3, 1, '2025-02-21', '00:00:00', 'confirmed', '2025-01-28 20:05:17', 60000.00, NULL),
(8, 3, 1, '2025-02-21', '00:00:00', 'confirmed', '2025-01-28 20:05:24', 60000.00, NULL),
(9, 3, 1, '2025-02-21', '00:00:00', 'pending', '2025-01-28 20:05:44', 60000.00, NULL),
(10, 73, 10, '2025-03-12', '00:00:00', 'cancelled', '2025-01-28 20:25:29', 270000.00, NULL),
(12, 73, 1, '2025-12-12', '00:00:00', 'cancelled', '2025-01-28 20:34:01', 60000.00, NULL),
(13, 73, 1, '2025-03-13', '12:12:00', 'pending', '2025-01-28 20:46:25', 60000.00, NULL),
(14, 73, 1, '2025-12-12', '00:00:00', 'pending', '2025-01-28 20:54:59', 60000.00, NULL),
(17, 73, 1, '2025-12-13', '17:17:00', 'pending', '2025-01-28 21:09:10', 60000.00, NULL);

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
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `bio`, `image_url`) VALUES
(1, 'Jane Doe', 'Senior Stylist', 'Jane has over 10 years of experience in hair styling and coloring.', 'images/team/1.jpg'),
(2, 'John Smith', 'Makeup Artist', 'John is a certified makeup artist specializing in bridal and special event makeup.', 'images/team/2.jpg'),
(3, 'Emily Brown', 'Nail Technician', 'Emily is passionate about nail art and has won several nail design competitions.', 'images/team/3.jpg'),
(4, 'Sarah Johnson', 'Esthetician', 'Sarah specializes in skincare treatments and has a passion for helping clients achieve healthy, glowing skin.', 'images/team/4.jpg'),
(5, 'Lisa Chen', 'Hair Colorist', 'Lisa is an expert in hair coloring techniques and loves creating vibrant, personalized looks for her clients.', 'images/team/5.jpg'),
(6, 'Maria Rodriguez', 'Massage Therapist', 'Maria is a certified massage therapist with expertise in various massage techniques to help clients relax and rejuvenate.', 'images/team/6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `position`, `content`, `created_at`) VALUES
(1, 'Siti Rahma', 'Regular Customer', 'Larissa Salon Studio is my go-to place for all my beauty needs. The staff is always friendly and professional, and the results are always amazing!', '2025-01-28 14:56:55'),
(2, 'Anita Wijaya', 'Local Influencer', 'Ive had my makeup done here for several events, and Im always impressed with the skill and creativity of the artists. Highly recommended!', '2025-01-28 14:56:55'),
(3, 'Budi Santoso', 'Businessman', 'The grooming services at Larissa Salon Studio are top-notch. I always leave feeling refreshed and confident.', '2025-01-28 14:56:55');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `created_at`, `is_admin`) VALUES
(3, 'hugo123', 'hugo@gmail.com', '$2y$10$77Ej0VdEWj.REY0f0xWQveA4glKSGXbAHYYKYHHTvmeZ3.83c1oMi', 'hugogabriel', '0988218323', '2025-01-24 11:27:30', 0),
(4, 'hugo33', 'gogabriel2003@gmail.com', '$2y$10$B2ynH1gbpBP2M2blRgqhTOePXG9LwYqjITBL/RHu7GjN.7IpwfnJe', 'hugo', '08953423423', '2025-01-24 19:45:38', 0),
(69, 'adminhugo', 'gogabriel1410@gmail.com', '1c910cef708602ead93da4f79767a182da1d6618b695cd5a55c477abc9f8bcaa', 'Hugo Gabriel', '0895613231486', '0000-00-00 00:00:00', 1),
(70, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', '1234567890', '2025-01-24 20:57:08', 1),
(71, 'nadia123', 'nadia@gmail.com', '$2y$10$w5vIWyOD0oLC5b1C0zIS2uuTS9QDJrX9j/4yXCUfcLDPYqQKhFQei', 'nadia', '123213123', '2025-01-28 13:01:49', 0),
(73, 'hugo321', 'hugoooo@gmail.com', '$2y$10$.h056moHwaOxx2etVtMWwOTNJwX.jf20odB.xHdHqHZdsEFxHOUTW', 'hugo', '0988213', '2025-01-28 13:14:02', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

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
