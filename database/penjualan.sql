-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2024 at 06:35 AM
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
-- Database: `penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `nik` varchar(25) NOT NULL,
  `no_kk` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `ktp` varchar(255) NOT NULL,
  `kk` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `nik`, `no_kk`, `name`, `email`, `phone`, `address`, `ktp`, `kk`, `created_at`) VALUES
(42, '5822236079051302', '3579762232180095', 'Fitriani', 'fitriani24@hotmail.com', '087390779257', 'Jalan Gajah Mada, Medan', 'assets/uploads/ktp.jpg', 'assets/uploads/unnamed.jpg', '2024-07-16 12:06:48'),
(43, '2243008323131912', '8719383185535860', 'Indra Wijaya', 'indra.wijaya58@yahoo.com', '087722868393', 'Jalan Diponegoro, Makassar', 'assets/uploads/ktp.jpg', 'assets/uploads/unnamed.jpg', '2024-07-16 12:06:48'),
(44, '9680974386932167', '1971321409137016', 'Fitriani', 'fitriani42@yahoo.com', '081025450232', 'Jalan Thamrin, Bandung', 'assets/uploads/ktp.jpg', 'assets/uploads/unnamed.jpg', '2024-07-16 12:06:48'),
(45, '3965697780973610', '8259073168978447', 'Dewi Sartika', 'dewi.sartika63@yahoo.com', '081594000631', 'Jalan Diponegoro, Jakarta', 'assets/uploads/ktp.jpg', 'assets/uploads/unnamed.jpg', '2024-07-16 12:06:48'),
(46, '1515717903957910', '7160960591221183', 'Yusuf Hidayat', 'yusuf.hidayat63@hotmail.com', '083211553164', 'Jalan Sudirman, Makassar', 'ktp_4.jpg', 'kk_4.jpg', '2024-07-16 12:06:48'),
(47, '9582248330305130', '1827091524878352', 'Lukman Hakim', 'lukman.hakim53@hotmail.com', '083300960010', 'Jalan Sudirman, Jakarta', 'ktp_5.jpg', 'kk_5.jpg', '2024-07-16 12:06:48'),
(48, '5366547058693146', '7696926722654992', 'Adi Putra', 'adi.putra35@yahoo.com', '083618276833', 'Jalan Pahlawan, Bandung', 'ktp_6.jpg', 'kk_6.jpg', '2024-07-16 12:06:48'),
(49, '7162469550243347', '2582082919222997', 'Ahmad Fauzi', 'ahmad.fauzi80@yahoo.com', '087408615228', 'Jalan Pahlawan, Medan', 'ktp_7.jpg', 'kk_7.jpg', '2024-07-16 12:06:48'),
(50, '1449382584013353', '3863218216847027', 'Yusuf Hidayat', 'yusuf.hidayat24@hotmail.com', '081637152189', 'Jalan Diponegoro, Surabaya', 'ktp_8.jpg', 'kk_8.jpg', '2024-07-16 12:06:48'),
(51, '9030158394262561', '1052884614248045', 'Rina Susanti', 'rina.susanti72@gmail.com', '086976020557', 'Jalan Sudirman, Medan', 'ktp_9.jpg', 'kk_9.jpg', '2024-07-16 12:06:48'),
(52, '9383088611397794', '4851567440150372', 'Yusuf Hidayat', 'yusuf.hidayat81@hotmail.com', '085718177359', 'Jalan Pahlawan, Medan', 'ktp_10.jpg', 'kk_10.jpg', '2024-07-16 12:06:48'),
(53, '3638921361192663', '4862484658651370', 'Joko Purwanto', 'joko.purwanto74@yahoo.com', '084657784079', 'Jalan Sudirman, Surabaya', 'ktp_11.jpg', 'kk_11.jpg', '2024-07-16 12:06:48'),
(54, '7527387451626602', '8145341377497761', 'Nina Mariani', 'nina.mariani83@gmail.com', '082551589264', 'Jalan Pahlawan, Jakarta', 'ktp_12.jpg', 'kk_12.jpg', '2024-07-16 12:06:48'),
(55, '2327803288192507', '2020618971786165', 'Siti Aminah', 'siti.aminah61@gmail.com', '082041073646', 'Jalan Thamrin, Makassar', 'ktp_13.jpg', 'kk_13.jpg', '2024-07-16 12:06:48'),
(56, '5476759397932643', '9670221721038838', 'Rina Susanti', 'rina.susanti47@gmail.com', '083057206823', 'Jalan Sudirman, Jakarta', 'ktp_14.jpg', 'kk_14.jpg', '2024-07-16 12:06:48'),
(57, '2514998520065711', '1690449001710794', 'Indra Wijaya', 'indra.wijaya9@hotmail.com', '082481987220', 'Jalan Gajah Mada, Medan', 'ktp_15.jpg', 'kk_15.jpg', '2024-07-16 12:06:48'),
(58, '1711142877211281', '8140051379358214', 'Indra Wijaya', 'indra.wijaya100@yahoo.com', '084761825318', 'Jalan Gajah Mada, Bandung', 'ktp_16.jpg', 'kk_16.jpg', '2024-07-16 12:06:48'),
(59, '2355048459832002', '4687880962106728', 'Dewi Sartika', 'dewi.sartika16@yahoo.com', '084261262614', 'Jalan Thamrin, Makassar', 'ktp_17.jpg', 'kk_17.jpg', '2024-07-16 12:06:48'),
(60, '3988373884875891', '4951787020374849', 'Joko Purwanto', 'joko.purwanto24@hotmail.com', '082635804249', 'Jalan Thamrin, Jakarta', 'ktp_18.jpg', 'kk_18.jpg', '2024-07-16 12:06:48'),
(61, '8457142705482788', '4514896203883691', 'Rina Susanti', 'rina.susanti29@gmail.com', '081195381285', 'Jalan Gajah Mada, Jakarta', 'ktp_19.jpg', 'kk_19.jpg', '2024-07-16 12:06:48');

-- --------------------------------------------------------

--
-- Table structure for table `motorcycles`
--

CREATE TABLE `motorcycles` (
  `motorcycle_id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `price` varchar(20) NOT NULL,
  `warna` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `motorcycles`
--

INSERT INTO `motorcycles` (`motorcycle_id`, `model`, `brand`, `price`, `warna`, `stock`, `created_at`) VALUES
(32, 'PCX', 'Honda', '56857119', 'Putih', 2, '2024-07-16 12:01:44'),
(33, 'Ninja 250', 'Kawasaki', '54293993', 'Hitam', 5, '2024-07-16 12:01:44'),
(34, 'Mio', 'Yamaha', '25790034', 'Hitam', 4, '2024-07-16 12:01:44'),
(35, 'Z250', 'Kawasaki', '29358802', 'Merah', 7, '2024-07-16 12:01:44'),
(36, 'GSX-R150', 'Suzuki', '37513201', 'Abu-abu', 10, '2024-07-16 12:01:44'),
(37, 'Satria F150', 'Suzuki', '42198565', 'Merah', 3, '2024-07-16 12:01:44'),
(38, 'Aerox', 'Yamaha', '19361673', 'Putih', 8, '2024-07-16 12:01:44'),
(39, 'Ninja 250', 'Kawasaki', '24226537', 'Biru', 1, '2024-07-16 12:01:44'),
(40, 'GSX-R150', 'Suzuki', '53272066', 'Biru', 3, '2024-07-16 12:01:44'),
(41, 'Ninja 250', 'Kawasaki', '52986029', 'Hitam', 9, '2024-07-16 12:01:44'),
(42, 'W175', 'Kawasaki', '32167664', 'Putih', 3, '2024-07-16 12:01:44'),
(43, 'Mio', 'Yamaha', '57174158', 'Biru', 5, '2024-07-16 12:01:44'),
(44, '946', 'Vespa', '41499048', 'Putih', 5, '2024-07-16 12:01:44'),
(45, 'Z250', 'Kawasaki', '22970337', 'Merah', 3, '2024-07-16 12:01:44'),
(46, 'GTS', 'Vespa', '36273713', 'Merah', 10, '2024-07-16 12:01:44'),
(47, 'Aerox', 'Yamaha', '57292547', 'Hitam', 7, '2024-07-16 12:01:44'),
(48, 'Vario', 'Honda', '59741714', 'Hitam', 4, '2024-07-16 12:01:44'),
(49, 'Ninja 250', 'Kawasaki', '40615544', 'Merah', 10, '2024-07-16 12:01:44'),
(50, 'Satria F150', 'Suzuki', '32140997', 'Merah', 5, '2024-07-16 12:01:44'),
(51, 'W175', 'Kawasaki', '30977221', 'Merah', 6, '2024-07-16 12:01:44');

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `sale_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `motorcycle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `total_price` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`sale_id`, `customer_id`, `motorcycle_id`, `user_id`, `sale_date`, `total_price`, `created_at`, `payment_type`) VALUES
(56, 54, 37, 5, '2023-05-01', '42198565', '2024-07-16 12:07:11', 'Cash'),
(57, 57, 50, 6, '2023-11-18', '32140997', '2024-07-16 12:07:11', 'Credit'),
(58, 47, 39, 4, '2023-07-14', '24226537', '2024-07-16 12:07:11', 'Cash'),
(59, 48, 41, 4, '2023-10-18', '52986029', '2024-07-16 12:07:11', 'Credit'),
(60, 52, 41, 6, '2023-11-19', '52986029', '2024-07-16 12:07:11', 'Credit'),
(61, 57, 47, 4, '2023-01-01', '57292547', '2024-07-16 12:07:11', 'Cash'),
(62, 42, 50, 4, '2023-04-20', '32140997', '2024-07-16 12:07:11', 'Cash'),
(63, 59, 37, 6, '2023-04-24', '42198565', '2024-07-16 12:07:11', 'Cash'),
(64, 47, 37, 4, '2023-12-22', '42198565', '2024-07-16 12:07:11', 'Credit'),
(65, 56, 47, 4, '2023-02-06', '57292547', '2024-07-16 12:07:11', 'Credit'),
(66, 55, 44, 6, '2023-09-15', '41499048', '2024-07-16 12:07:11', 'Credit'),
(67, 58, 34, 4, '2023-05-19', '25790034', '2024-07-16 12:07:11', 'Cash'),
(68, 61, 45, 4, '2023-05-27', '22970337', '2024-07-16 12:07:11', 'Cash'),
(69, 53, 42, 6, '2023-11-03', '32167664', '2024-07-16 12:07:11', 'Credit'),
(70, 51, 41, 4, '2023-11-04', '52986029', '2024-07-16 12:07:11', 'Credit'),
(71, 53, 45, 6, '2023-12-28', '22970337', '2024-07-16 12:07:11', 'Credit'),
(72, 49, 50, 6, '2023-08-06', '32140997', '2024-07-16 12:07:11', 'Cash'),
(73, 60, 45, 6, '2023-08-12', '22970337', '2024-07-16 12:07:11', 'Cash'),
(74, 60, 40, 6, '2023-11-10', '53272066', '2024-07-16 12:07:11', 'Credit'),
(75, 54, 37, 4, '2023-07-21', '42198565', '2024-07-16 12:07:11', 'Credit');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `nama` char(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','sales') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(4, 'Sales', 'sales', '$2y$10$n0WGl5lAtMwrzcLivqQPPOMHUIq69UXVVmWU8GW9dr63cMwXHp/Pm', 'sales', '2024-07-14 14:49:19'),
(5, 'Admin', 'admin', '$2y$10$uZdLGTk5Wy0jXJxLEiUfvOTIczIs.tHuOH6P7lHhHPNLVWh//cDlC', 'admin', '2024-07-14 14:49:43'),
(6, 'salesone', 'sales1', '$2y$10$ZHfhyRy/jxwTcmJryDwPT.VoMKDNKyS4./WzmmqQVOJ9wopGbsnmK', 'sales', '2024-07-14 15:00:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `motorcycles`
--
ALTER TABLE `motorcycles`
  ADD PRIMARY KEY (`motorcycle_id`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `motorcycle_id` (`motorcycle_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `motorcycles`
--
ALTER TABLE `motorcycles`
  MODIFY `motorcycle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `sale_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `sale_ibfk_2` FOREIGN KEY (`motorcycle_id`) REFERENCES `motorcycles` (`motorcycle_id`),
  ADD CONSTRAINT `sale_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
