-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2023 at 03:38 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ps`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorys`
--

CREATE TABLE `categorys` (
  `id_category` int(11) NOT NULL,
  `category_name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categorys`
--

INSERT INTO `categorys` (`id_category`, `category_name`) VALUES
(1, 'Category A'),
(2, 'Category B'),
(3, 'Category C'),
(4, 'Category D'),
(5, 'Category E'),
(6, 'Category New Year'),
(7, 'Numero Vac'),
(8, 'CatK120'),
(9, 'CAT5555'),
(10, 'Cr_Example');

-- --------------------------------------------------------

--
-- Table structure for table `journal_products`
--

CREATE TABLE `journal_products` (
  `id_product` int(11) NOT NULL,
  `id_product_ref` int(11) NOT NULL,
  `operation_date` date NOT NULL,
  `operation_type` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `operation_quantity` int(11) NOT NULL,
  `des_src` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `journal_products`
--

INSERT INTO `journal_products` (`id_product`, `id_product_ref`, `operation_date`, `operation_type`, `id_responsable`, `operation_quantity`, `des_src`) VALUES
(160, 3, '2023-08-01', 1, 1, 78, 'Marché'),
(161, 3, '2023-08-01', 1, 1, 92, 'Marché'),
(161, 3, '2023-08-01', 2, 2, 17, 'Stock Rabat'),
(161, 3, '2023-08-01', 3, 1, 17, 'Stock Tanger'),
(161, 3, '2023-08-01', 2, 2, 20, 'Stock Rabat'),
(161, 3, '2023-08-01', 3, 1, 20, 'Stock Rabat'),
(161, 3, '2023-08-01', 4, 1, 8, 'Mr/Mme Sellami Jamila'),
(160, 3, '2023-08-01', 4, 1, 18, 'Mr/Mme Sellami Jamila'),
(160, 3, '2023-08-01', 4, 1, 10, 'Mr/Mme  '),
(160, 3, '2023-08-01', 4, 1, 7, 'Mr/Mme Sellami Jamila'),
(166, 3, '2023-08-01', 1, 1, 12, 'Marché'),
(166, 3, '2023-08-01', 5, 1, 12, 'Département des Opérations de Disposition'),
(167, 3, '2023-08-02', 1, 1, 55, 'Marché'),
(168, 8, '2023-08-02', 1, 1, 29, 'Marché'),
(168, 8, '2023-08-02', 5, 1, 29, 'Département des Opérations de Disposition'),
(169, 4, '2023-08-02', 1, 1, 88, 'Marché'),
(169, 4, '2023-08-02', 5, 1, 88, 'Département des Opérations de Disposition'),
(170, 4, '2023-08-02', 1, 1, 22, 'Marché'),
(171, 4, '2023-08-02', 1, 1, 86, 'Marché'),
(171, 4, '2023-08-02', 5, 1, 86, 'Département des Opérations de Disposition'),
(172, 5, '2023-08-02', 1, 1, 99, 'Marché'),
(172, 5, '2023-08-02', 5, 1, 99, 'Département des Opérations de Disposition'),
(173, 5, '2023-08-02', 1, 1, 67, 'Marché'),
(173, 5, '2023-08-02', 5, 1, 67, 'Département des Opérations de Disposition'),
(174, 16, '2023-08-05', 1, 1, 24, 'Marché'),
(175, 8, '2023-08-05', 1, 4, 90, 'Marché'),
(176, 8, '2023-08-05', 1, 4, 89, 'Marché'),
(177, 4, '2023-08-05', 1, 4, 76, 'Marché'),
(178, 13, '2023-08-05', 1, 4, 73, 'Marché'),
(179, 9, '2023-08-05', 1, 3, 177, 'Marché'),
(180, 12, '2023-08-05', 1, 3, 206, 'Marché'),
(175, 8, '2023-08-05', 2, 2, 22, 'Stock Centrale'),
(175, 8, '2023-08-05', 3, 4, 22, 'Stock Tanger'),
(174, 16, '2023-08-07', 4, 1, 15, 'Mr/Mme Nour-dine Ahmed'),
(170, 4, '2023-08-07', 4, 1, 5, 'Mr/Mme Nour-dine Ahmed'),
(182, 5, '2023-08-07', 1, 1, 90, 'Marché'),
(183, 14, '2023-08-07', 1, 1, 133, 'Marché'),
(184, 9, '2023-08-07', 1, 2, 80, 'Marché');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id_product` int(11) NOT NULL,
  `id_product_ref` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `price_per_unit` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `experation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_product`, `id_product_ref`, `id_category`, `id_responsable`, `price_per_unit`, `quantity`, `experation_date`) VALUES
(160, 3, 2, 1, '12.08', 30, '2023-08-13'),
(161, 3, 2, 1, '10.15', 17, '2023-08-09'),
(162, 3, 2, 2, '10.15', 20, '2023-08-09'),
(163, 3, 2, 2, '10.15', 10, '2023-08-09'),
(164, 3, 2, 2, '10.15', 17, '2023-08-09'),
(165, 3, 2, 2, '10.15', 20, '2023-08-09'),
(166, 3, 2, 1, '12.16', 0, '2023-07-31'),
(167, 3, 2, 1, '7.27', 55, '2023-08-04'),
(168, 8, 1, 1, '104.26', 0, '2023-08-01'),
(169, 4, 1, 1, '3.17', 0, '2023-07-31'),
(170, 4, 1, 1, '8.17', 17, '2023-08-12'),
(171, 4, 1, 1, '6.18', 0, '2023-07-31'),
(172, 5, 4, 1, '67.24', 0, '2023-07-31'),
(173, 5, 4, 1, '12.13', 0, '2023-07-31'),
(174, 16, 10, 1, '12.25', 9, '2023-09-08'),
(175, 8, 1, 4, '13.17', 68, '2023-08-25'),
(176, 8, 1, 4, '7.08', 89, '2023-08-20'),
(177, 4, 1, 4, '6.14', 76, '2023-08-13'),
(178, 13, 5, 4, '11.15', 73, '2023-08-18'),
(179, 9, 1, 3, '0.86', 177, '2023-08-08'),
(180, 12, 7, 3, '4.24', 206, NULL),
(181, 8, 1, 2, '13.17', 22, '2023-08-25'),
(182, 5, 4, 1, '13.16', 90, '2023-07-31'),
(183, 14, 8, 1, '2.29', 133, '2023-08-01'),
(184, 9, 1, 2, '1.21', 80, '2023-08-02');

-- --------------------------------------------------------

--
-- Table structure for table `products_reference`
--

CREATE TABLE `products_reference` (
  `id_product_ref` int(11) NOT NULL,
  `product_name` varchar(45) NOT NULL,
  `id_category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products_reference`
--

INSERT INTO `products_reference` (`id_product_ref`, `product_name`, `id_category`) VALUES
(1, 'Product A', 5),
(2, 'Product B', 3),
(3, 'Product C', 2),
(4, 'Product D', 1),
(5, 'Product E', 4),
(8, 'Produit Nouveau', 1),
(9, 'Product New 101', 1),
(10, 'produit nouveau', 6),
(11, 'Vaccine Vache', 4),
(12, 'Vaccine 1234', 7),
(13, 'Vaccine Chien', 5),
(14, 'Prt2299', 8),
(15, 'PRT55555', 9),
(16, 'Pr_Example', 10);

-- --------------------------------------------------------

--
-- Table structure for table `requested_by_employee`
--

CREATE TABLE `requested_by_employee` (
  `id_product` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `quantity_requested` int(11) NOT NULL,
  `processing_status` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `id_request_employee` int(11) NOT NULL,
  `operation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requested_by_employee`
--

INSERT INTO `requested_by_employee` (`id_product`, `id_responsable`, `quantity_requested`, `processing_status`, `message`, `id_user`, `id_request_employee`, `operation_date`) VALUES
(160, 1, 13, 3, NULL, 2, 42, '2023-08-01'),
(161, 1, 8, 3, NULL, 2, 43, '2023-08-01'),
(160, 1, 18, 3, NULL, 2, 44, '2023-08-01'),
(160, 1, 10, 2, NULL, 2, 45, '2023-08-01'),
(160, 1, 7, 2, NULL, 2, 46, '2023-08-01'),
(174, 1, 15, 3, NULL, 9, 47, '2023-08-07'),
(170, 1, 5, 2, NULL, 9, 48, '2023-08-07'),
(161, 1, 10, 0, NULL, 9, 49, '2023-08-05'),
(160, 1, 6, 0, NULL, 2, 50, '2023-08-06');

-- --------------------------------------------------------

--
-- Table structure for table `requested_by_respo`
--

CREATE TABLE `requested_by_respo` (
  `id_request_respo` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_responsable_asking` int(11) NOT NULL,
  `id_responsable_receving` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `quantity_requested` int(11) NOT NULL,
  `processing_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requested_by_respo`
--

INSERT INTO `requested_by_respo` (`id_request_respo`, `id_product`, `id_responsable_asking`, `id_responsable_receving`, `message`, `quantity_requested`, `processing_status`) VALUES
(99, 161, 2, 1, NULL, 10, 3),
(100, 161, 2, 1, NULL, 20, 2),
(101, 161, 2, 1, NULL, 17, 3),
(102, 161, 2, 1, NULL, 20, 2),
(103, 179, 1, 3, 'Important!', 48, 0),
(104, 176, 2, 4, NULL, 16, 0),
(105, 178, 2, 4, 'urgent', 17, 1),
(106, 175, 2, 4, NULL, 22, 3),
(107, 174, 2, 1, 'On est besoin de ce produit', 19, 0);

-- --------------------------------------------------------

--
-- Table structure for table `responsables`
--

CREATE TABLE `responsables` (
  `id_responsable` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `stock_name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `responsables`
--

INSERT INTO `responsables` (`id_responsable`, `id_user`, `stock_name`) VALUES
(1, 1, 'Rabat'),
(2, 4, 'Tanger'),
(3, 6, 'CasaBlanca'),
(4, 7, 'Centrale');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `id_responsable`, `first_name`, `last_name`, `email`, `password`) VALUES
(1, 1, 'Ali', 'Muhammed', 'Ali@example.com', 'password1'),
(2, 1, 'Jamila', 'Sellami', 'jamila@example.com', 'password2'),
(3, 2, 'Mbarek', 'Bujmaa', 'MbarekBujmaa@example.com', 'password3'),
(4, 2, 'Imane', 'Ait-Elhessen', 'Imane@example.com', 'password4'),
(5, 3, 'Dda', 'Hmad', 'Hmad@example.com', 'password5'),
(6, 3, 'Souhail', 'Toujani', 'souhail_toujanir@example.com', 'password6'),
(7, 4, 'Mahedi', 'Chamali', 'chamali@example.com', 'password7'),
(8, 4, 'Omar', 'Cherkaoui', 'cherkaoui@example.com', 'password8'),
(9, 1, 'Ahmed', 'Nour-dine', 'nourDine@example.com', 'password9');

-- --------------------------------------------------------

--
-- Table structure for table `waste_departement`
--

CREATE TABLE `waste_departement` (
  `id_product` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `disposition_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `waste_departement`
--

INSERT INTO `waste_departement` (`id_product`, `id_responsable`, `disposition_date`) VALUES
(168, 1, '2023-08-02'),
(169, 1, '2023-08-02'),
(171, 1, '2023-08-01'),
(172, 1, '2023-08-02'),
(173, 1, '2023-08-02'),
(183, 1, '2023-08-03'),
(167, 1, NULL),
(184, 2, '2023-08-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorys`
--
ALTER TABLE `categorys`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`);

--
-- Indexes for table `products_reference`
--
ALTER TABLE `products_reference`
  ADD PRIMARY KEY (`id_product_ref`);

--
-- Indexes for table `requested_by_employee`
--
ALTER TABLE `requested_by_employee`
  ADD PRIMARY KEY (`id_request_employee`);

--
-- Indexes for table `requested_by_respo`
--
ALTER TABLE `requested_by_respo`
  ADD PRIMARY KEY (`id_request_respo`);

--
-- Indexes for table `responsables`
--
ALTER TABLE `responsables`
  ADD PRIMARY KEY (`id_responsable`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorys`
--
ALTER TABLE `categorys`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `products_reference`
--
ALTER TABLE `products_reference`
  MODIFY `id_product_ref` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `requested_by_employee`
--
ALTER TABLE `requested_by_employee`
  MODIFY `id_request_employee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `requested_by_respo`
--
ALTER TABLE `requested_by_respo`
  MODIFY `id_request_respo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `responsables`
--
ALTER TABLE `responsables`
  MODIFY `id_responsable` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
