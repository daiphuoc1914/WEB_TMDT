-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 26, 2025 at 05:34 PM
-- Server version: 8.0.44
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tmdt_vpp`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `prod_id` int NOT NULL,
  `prod_qty` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `prod_id`, `prod_qty`, `created_at`) VALUES
(15, 1, 10, 1, '2025-11-22 14:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `image`, `created_at`) VALUES
(1, 'Bút Viết', 'butbi1.jpg', '2025-11-22 05:27:07'),
(2, 'Sổ Tay - Vở', 'sotay2.jpg', '2025-11-22 05:27:07'),
(3, 'Giấy In - Photo', 'giayin3.jpg', '2025-11-22 05:27:07'),
(4, 'Bút Chì - Bút Màu', 'butchi4.jpg', '2025-11-22 05:27:07'),
(5, 'Dụng Cụ Văn Phòng', 'dungcuvanphong5.jpg', '2025-11-22 05:27:07'),
(6, 'File - Bìa Hồ Sơ', 'fileho6.jpg', '2025-11-22 05:27:07'),
(7, 'Máy Tính', 'maytinh7.jpg', '2025-11-22 05:27:07'),
(8, 'Dụng Cụ Học Sinh', 'dungcuhocsinh8.jpg', '2025-11-22 05:27:07'),
(9, 'Truyện tranh/ Manga', '1764003173.jpg', '2025-11-24 16:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `tracking_no` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_general_ci,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_id` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping` int DEFAULT NULL COMMENT 'ID của shipping_unit',
  `status` tinyint DEFAULT '0',
  `comments` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `tracking_no`, `user_id`, `name`, `email`, `phone`, `address`, `total_price`, `payment_mode`, `payment_id`, `shipping`, `status`, `comments`, `created_at`) VALUES
(1, 'VPP176382195659', 2, 'nguyen van a', 'nva@gmail.com', '0909070705', '17 lê văn việt', '38000.00', 'COD', NULL, 2, 0, 'gần đh spkt', '2025-11-22 14:32:36'),
(2, 'VPP176391775469', 3, 'Yume', 'test@gmail.com', '0987654321', 'wdvw', '42000.00', 'COD', NULL, 2, 0, 'we3e', '2025-11-23 17:09:14');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `prod_id` int NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT 'Giá tại thời điểm mua',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `prod_id`, `qty`, `price`, `create_at`) VALUES
(1, 1, 10, 1, '8000.00', '2025-11-22 14:32:36'),
(2, 2, 2, 1, '12000.00', '2025-11-23 17:09:14');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `catid` int NOT NULL,
  `productName` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `product_desc` mediumtext COLLATE utf8mb4_general_ci,
  `image` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` int DEFAULT '0',
  `trending` tinyint DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `catid`, `productName`, `product_desc`, `image`, `quantity`, `trending`, `price`, `created_at`) VALUES
(1, 1, 'Bút Bi Thiên Long TL-027 (Hộp 20 cây)', 'Bút bi cao cấp, mực đều, viết êm', 'tl027.jpg', 150, 1, '45000.00', '2025-11-22 05:29:58'),
(2, 1, 'Bút Gel FlexOffice FO-GEL01 (Đen)', 'Ngòi 0.5mm, mực gel không lem', 'gel01.jpg', 199, 1, '12000.00', '2025-11-22 05:29:58'),
(3, 2, 'Sổ Lò Xo A5 200 Trang FlexOffice', 'Sổ caro, bìa cứng đẹp', 'soa5.jpg', 80, 1, '35000.00', '2025-11-22 05:29:58'),
(4, 3, 'Giấy Photo IK Plus 70gsm', 'Giấy in phổ thông', 'ikplus.jpg', 120, 1, '68000.00', '2025-11-22 05:29:58'),
(5, 4, 'Bút Chì 2B Thiên Long', 'Bút chì gỗ tự nhiên', 'butchi2b.jpg', 500, 1, '5000.00', '2025-11-22 05:29:58'),
(6, 5, 'Dao Rọc Giấy FlexOffice', 'Lưỡi thép không gỉ', 'daoroc.jpg', 90, 1, '25000.00', '2025-11-22 05:29:58'),
(7, 6, 'Bìa Còng 5cm Thiên Long', 'Đựng hồ sơ dày', 'biacong.jpg', 50, 1, '55000.00', '2025-11-22 05:29:58'),
(8, 7, 'Máy Tính Casio FX-570VN Plus', 'Máy tính khoa học chính hãng', 'casio570.jpg', 30, 1, '650000.00', '2025-11-22 05:29:58'),
(9, 8, 'Bộ Dụng Cụ Học Tập 8 Món', 'Compas, thước kẻ, tẩy, gọt', 'bohocsinh.jpg', 100, 1, '85000.00', '2025-11-22 05:29:58'),
(10, 8, 'Gọt Bút Chì Thiên Long', 'Gọt êm, không gãy ngòi', 'gotbut.jpg', 199, 1, '8000.00', '2025-11-22 05:29:58'),
(11, 9, 'Blue Box 12', '.....', '1764002980.jpg', 11, 0, '40000.00', '2025-11-24 16:26:32'),
(13, 9, 'Hoa thơm kiêu hãnh 10', '....', '1764003593.jpg', 44, 0, '55000.00', '2025-11-24 16:58:44'),
(14, 9, 'Rắc rối đáng yêu 3', '....', '1764004008.webp', 36, 0, '50000.00', '2025-11-24 17:06:48'),
(15, 9, 'Masamune báo thù 4', '.....', '1764004089.jpg', 18, 0, '38000.00', '2025-11-24 17:08:09'),
(16, 9, 'Masamune báo thù 1', '...', '1764177741.jpg', 36, 0, '38000.00', '2025-11-26 17:22:21'),
(17, 9, 'Masamune báo thù 3', '...', '1764177784.jpg', 14, 0, '38000.00', '2025-11-26 17:23:04'),
(18, 9, 'Blue Box 9', '...', '1764177961.webp', 11, 0, '40000.00', '2025-11-26 17:26:01'),
(19, 9, 'Blue Box 8', '...', '1764177995.webp', 13, 0, '40000.00', '2025-11-26 17:26:35'),
(20, 9, 'Blue Box 6', '...', '1764178040.webp', 36, 0, '40000.00', '2025-11-26 17:27:21'),
(21, 9, 'Blue Box 4', '....', '1764178065.webp', 13, 0, '40000.00', '2025-11-26 17:27:45'),
(22, 9, 'Blue Box 7', '...', '1764178083.webp', 11, 0, '40000.00', '2025-11-26 17:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_unit`
--

CREATE TABLE `shipping_unit` (
  `id` int NOT NULL,
  `name_ship` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_unit`
--

INSERT INTO `shipping_unit` (`id`, `name_ship`, `price`, `status`) VALUES
(1, 'Giao hàng tiết kiệm', '15000.00', 1),
(2, 'Giao hàng nhanh', '30000.00', 1),
(3, 'Grab', '45000.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `type` tinyint DEFAULT '0' COMMENT '0: User, 1: Admin',
  `token` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `type`, `token`, `status`, `created_at`) VALUES
(1, 'Nguyễn Đại Phước', 'daiphuoc1914@gmail.com', '0399640717', 'daiphuoc136', 0, NULL, 1, '2025-11-22 06:53:30'),
(2, 'nguyen van a', 'nva@gmail.com', '0909070705', '12345', 0, NULL, 1, '2025-11-22 14:30:47'),
(3, 'Yume', 'test@gmail.com', '0987654321', '123', 0, NULL, 1, '2025-11-23 15:17:22'),
(4, 'itosuy', 'chia@gmail.com', '0987654321', '123', 1, NULL, 1, '2025-11-23 17:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `prod_id` int NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `prod_id` (`prod_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_no` (`tracking_no`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `prod_id` (`prod_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catid` (`catid`);

--
-- Indexes for table `shipping_unit`
--
ALTER TABLE `shipping_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `prod_id` (`prod_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `shipping_unit`
--
ALTER TABLE `shipping_unit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`prod_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`prod_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`catid`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`prod_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
