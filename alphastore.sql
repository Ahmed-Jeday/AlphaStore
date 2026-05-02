-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2026 at 11:11 PM
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
-- Database: `alphastore`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `produit_id` varchar(32) NOT NULL,
  `quantite` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Accessoires'),
(13, 'Appareils photo'),
(7, 'Audio'),
(10, 'Caméras & drones'),
(11, 'Composants PC'),
(9, 'Écrans'),
(3, 'Enfant'),
(1, 'Femme'),
(15, 'haut'),
(2, 'Homme'),
(12, 'Montres connectées'),
(5, 'Ordinateurs portables'),
(8, 'Périphériques'),
(6, 'Smartphones'),
(14, 'Tablettes');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`) VALUES
(20, 'Argent'),
(10, 'Beige'),
(11, 'Beige clair'),
(15, 'Beige imprimé'),
(6, 'Beige naturel'),
(2, 'Blanc'),
(7, 'Bleu'),
(5, 'Bleu clair'),
(4, 'Bleu denim'),
(24, 'Doré'),
(21, 'Graphite'),
(19, 'Gris chiné'),
(8, 'Gris rayé'),
(16, 'Gris-vert'),
(1, 'Jaune'),
(3, 'Jaune pastel'),
(9, 'Marron'),
(18, 'Marron et Blanc'),
(12, 'Marron foncé'),
(13, 'Noir'),
(17, 'Noir et Blanc'),
(14, 'Rose fuchsia'),
(23, 'Rouge'),
(22, 'Violet');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(3, 27, 'GAP-055', '2026-04-27 21:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('success','failed') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `email`, `status`, `created_at`) VALUES
(1, NULL, 'ahmed.jday2005@gmail.com', 'success', '2026-04-18 22:35:56'),
(2, NULL, 'ahmed.jday2005@gmail.com', 'failed', '2026-04-19 18:49:09'),
(3, NULL, 'ahmed.jday2005@gmail.com', 'failed', '2026-04-19 18:49:16'),
(4, NULL, 'ahmed.jday2005@gmail.com', 'success', '2026-04-19 18:49:26'),
(5, NULL, 'ahmed.jday2005@gmail.com', 'failed', '2026-04-19 18:54:22'),
(6, NULL, 'ahmed.jday2005@gmail.com', 'failed', '2026-04-19 18:54:29'),
(7, NULL, 'ahmed.jday2005@gmail.com', 'success', '2026-04-19 18:54:37'),
(8, NULL, 'ahmed.jday2005@gmail.com', 'success', '2026-04-19 23:05:50'),
(9, NULL, 'jday99708@gmail.com', 'success', '2026-04-19 23:07:38'),
(10, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-20 17:14:46'),
(11, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-21 13:26:48'),
(12, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-22 11:36:48'),
(13, 27, 'ahmed.jday2005@gmail.com', 'failed', '2026-04-22 13:30:08'),
(14, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-22 13:30:16'),
(15, NULL, 'ahmed27@gmail.com', 'failed', '2026-04-23 10:04:41'),
(16, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-23 10:05:04'),
(17, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-23 10:07:46'),
(18, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-27 21:06:15'),
(19, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-27 21:06:15'),
(20, 27, 'ahmed.jday2005@gmail.com', 'success', '2026-04-27 21:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`) VALUES
(1, 27, 33.99, 'pending', '2026-04-27 21:20:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` varchar(30) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 'GAP-055', 1, 33.99);

-- --------------------------------------------------------

--
-- Table structure for table `pc_builds`
--

CREATE TABLE `pc_builds` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT 'Mon Build',
  `total_price` decimal(10,2) DEFAULT NULL,
  `usage_profile` enum('gaming','workstation','budget','streaming') DEFAULT 'gaming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pc_build_items`
--

CREATE TABLE `pc_build_items` (
  `id` int(11) NOT NULL,
  `build_id` int(11) NOT NULL,
  `component_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pc_components`
--

CREATE TABLE `pc_components` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `component_type` enum('cpu','gpu','motherboard','ram','psu','storage','case') NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `performance_score` int(11) DEFAULT 50,
  `tdp` int(11) DEFAULT 0,
  `socket` varchar(50) DEFAULT NULL,
  `form_factor` varchar(50) DEFAULT NULL,
  `ram_type` varchar(20) DEFAULT NULL,
  `ram_slots` int(11) DEFAULT NULL,
  `ram_modules` int(11) DEFAULT NULL,
  `wattage` int(11) DEFAULT NULL,
  `gpu_max_length` int(11) DEFAULT NULL,
  `gpu_length` int(11) DEFAULT NULL,
  `specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specs`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pc_components`
--

INSERT INTO `pc_components` (`id`, `name`, `component_type`, `brand`, `price`, `stock`, `image_url`, `performance_score`, `tdp`, `socket`, `form_factor`, `ram_type`, `ram_slots`, `ram_modules`, `wattage`, `gpu_max_length`, `gpu_length`, `specs`, `created_at`) VALUES
(1, 'AMD Ryzen 7 7800X3D', 'cpu', 'AMD', 449.00, 15, NULL, 98, 120, 'AM5', NULL, 'DDR5', NULL, NULL, NULL, NULL, NULL, '{\"cores\": 8, \"threads\": 16, \"base_clock\": \"4.2GHz\", \"boost_clock\": \"5.0GHz\"}', '2026-05-02 21:10:21'),
(2, 'Intel Core i7-14700K', 'cpu', 'Intel', 419.00, 20, NULL, 96, 125, 'LGA1700', NULL, 'DDR5', NULL, NULL, NULL, NULL, NULL, '{\"cores\": 20, \"threads\": 28, \"base_clock\": \"3.4GHz\", \"boost_clock\": \"5.6GHz\"}', '2026-05-02 21:10:21'),
(3, 'AMD Ryzen 5 7600', 'cpu', 'AMD', 229.00, 45, NULL, 75, 65, 'AM5', NULL, 'DDR5', NULL, NULL, NULL, NULL, NULL, '{\"cores\": 6, \"threads\": 12, \"base_clock\": \"3.8GHz\", \"boost_clock\": \"5.1GHz\"}', '2026-05-02 21:10:21'),
(4, 'Intel Core i5-13400F', 'cpu', 'Intel', 209.00, 30, NULL, 70, 65, 'LGA1700', NULL, 'DDR4', NULL, NULL, NULL, NULL, NULL, '{\"cores\": 10, \"threads\": 16, \"base_clock\": \"2.5GHz\", \"boost_clock\": \"4.6GHz\"}', '2026-05-02 21:10:21'),
(5, 'AMD Ryzen 9 9950X', 'cpu', 'AMD', 649.00, 10, NULL, 100, 170, 'AM5', NULL, 'DDR5', NULL, NULL, NULL, NULL, NULL, '{\"cores\": 16, \"threads\": 32, \"base_clock\": \"4.3GHz\", \"boost_clock\": \"5.7GHz\"}', '2026-05-02 21:10:21'),
(6, 'NVIDIA GeForce RTX 4080 Super', 'gpu', 'NVIDIA', 1099.00, 8, NULL, 97, 320, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 310, '{\"vram\": \"16GB GDDR6X\", \"interface\": \"PCIe 4.0\"}', '2026-05-02 21:10:21'),
(7, 'NVIDIA GeForce RTX 4070 Ti Super', 'gpu', 'NVIDIA', 849.00, 12, NULL, 90, 285, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '{\"vram\": \"16GB GDDR6X\", \"interface\": \"PCIe 4.0\"}', '2026-05-02 21:10:21'),
(8, 'AMD Radeon RX 7800 XT', 'gpu', 'AMD', 529.00, 18, NULL, 85, 263, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 267, '{\"vram\": \"16GB GDDR6\", \"interface\": \"PCIe 4.0\"}', '2026-05-02 21:10:21'),
(9, 'NVIDIA GeForce RTX 4060', 'gpu', 'NVIDIA', 299.00, 50, NULL, 65, 115, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 198, '{\"vram\": \"8GB GDDR6\", \"interface\": \"PCIe 4.0\"}', '2026-05-02 21:10:21'),
(10, 'NVIDIA GeForce RTX 5090 FE', 'gpu', 'NVIDIA', 1999.00, 3, NULL, 100, 450, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 336, '{\"vram\": \"32GB GDDR7\", \"interface\": \"PCIe 5.0\"}', '2026-05-02 21:10:21'),
(11, 'ASUS ROG STRIX B650-A GAMING WIFI', 'motherboard', 'ASUS', 259.00, 25, NULL, 80, 0, 'AM5', 'ATX', 'DDR5', 4, NULL, NULL, NULL, NULL, '{\"chipset\": \"B650\", \"wifi\": true}', '2026-05-02 21:10:21'),
(12, 'MSI MAG Z790 TOMAHAWK WIFI', 'motherboard', 'MSI', 289.00, 15, NULL, 85, 0, 'LGA1700', 'ATX', 'DDR5', 4, NULL, NULL, NULL, NULL, '{\"chipset\": \"Z790\", \"wifi\": true}', '2026-05-02 21:10:21'),
(13, 'Gigabyte B650M DS3H', 'motherboard', 'Gigabyte', 129.00, 40, NULL, 60, 0, 'AM5', 'mATX', 'DDR5', 4, NULL, NULL, NULL, NULL, '{\"chipset\": \"B650\", \"wifi\": false}', '2026-05-02 21:10:21'),
(14, 'ASUS PRIME B760M-K DDR4', 'motherboard', 'ASUS', 109.00, 35, NULL, 55, 0, 'LGA1700', 'mATX', 'DDR4', 2, NULL, NULL, NULL, NULL, '{\"chipset\": \"B760\", \"wifi\": false}', '2026-05-02 21:10:21'),
(15, 'Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz', 'ram', 'Corsair', 139.00, 60, NULL, 90, 0, NULL, NULL, 'DDR5', NULL, 2, NULL, NULL, NULL, '{\"speed\": \"6000MHz\", \"latency\": \"CL36\"}', '2026-05-02 21:10:21'),
(16, 'G.Skill Ripjaws V 16GB (2x8GB) DDR4 3200MHz', 'ram', 'G.Skill', 49.00, 100, NULL, 70, 0, NULL, NULL, 'DDR4', NULL, 2, NULL, NULL, NULL, '{\"speed\": \"3200MHz\", \"latency\": \"CL16\"}', '2026-05-02 21:10:21'),
(17, 'Crucial Pro 64GB (2x32GB) DDR5 5600MHz', 'ram', 'Crucial', 199.00, 20, NULL, 95, 0, NULL, NULL, 'DDR5', NULL, 2, NULL, NULL, NULL, '{\"speed\": \"5600MHz\", \"latency\": \"CL46\"}', '2026-05-02 21:10:21'),
(18, 'Corsair RM750e 750W 80+ Gold', 'psu', 'Corsair', 119.00, 30, NULL, 85, 0, NULL, NULL, NULL, NULL, NULL, 750, NULL, NULL, '{\"modular\": \"Full\", \"efficiency\": \"80+ Gold\"}', '2026-05-02 21:10:21'),
(19, 'EVGA 600 W1 80+ White', 'psu', 'EVGA', 55.00, 50, NULL, 50, 0, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, '{\"modular\": \"No\", \"efficiency\": \"80+ White\"}', '2026-05-02 21:10:21'),
(20, 'Seasonic Prime TX-1000 1000W 80+ Titanium', 'psu', 'Seasonic', 329.00, 10, NULL, 100, 0, NULL, NULL, NULL, NULL, NULL, 1000, NULL, NULL, '{\"modular\": \"Full\", \"efficiency\": \"80+ Titanium\"}', '2026-05-02 21:10:21'),
(21, 'Samsung 990 Pro 2TB NVMe', 'storage', 'Samsung', 189.00, 40, NULL, 98, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{\"type\": \"NVMe Gen4\", \"read_speed\": \"7450MB/s\"}', '2026-05-02 21:10:21'),
(22, 'Crucial P3 1TB NVMe', 'storage', 'Crucial', 69.00, 80, NULL, 75, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{\"type\": \"NVMe Gen3\", \"read_speed\": \"3500MB/s\"}', '2026-05-02 21:10:21'),
(23, 'Seagate Barracuda 2TB HDD', 'storage', 'Seagate', 59.00, 60, NULL, 40, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{\"type\": \"SATA HDD\", \"rpm\": 7200}', '2026-05-02 21:10:21'),
(24, 'NZXT H5 Flow', 'case', 'NZXT', 99.00, 25, NULL, 80, 0, NULL, 'ATX', NULL, NULL, NULL, NULL, 365, NULL, '{\"type\": \"Mid Tower\", \"fans_included\": 2}', '2026-05-02 21:10:21'),
(25, 'Corsair 4000D Airflow', 'case', 'Corsair', 104.00, 30, NULL, 85, 0, NULL, 'ATX', NULL, NULL, NULL, NULL, 360, NULL, '{\"type\": \"Mid Tower\", \"fans_included\": 2}', '2026-05-02 21:10:21'),
(26, 'Cooler Master Q300L', 'case', 'Cooler Master', 49.00, 50, NULL, 60, 0, NULL, 'mATX', NULL, NULL, NULL, NULL, 360, NULL, '{\"type\": \"Micro Tower\", \"fans_included\": 1}', '2026-05-02 21:10:21');

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE `produits` (
  `id` varchar(20) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `product_type` enum('haut','bas','accessoire','chaussure','ensemble') DEFAULT NULL,
  `season` enum('ete','hiver','mi_saison','toutes_saisons') DEFAULT 'toutes_saisons'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `name`, `description`, `price`, `stock`, `sku`, `image_path`, `category_id`, `color_id`, `created_at`, `product_type`, `season`) VALUES
('GAP-001', 'Ensemble chemise et short', 'Ensemble chemise et short de couleur Jaune pour Femme. Idéal pour un look décontracté.', 49.99, 25, 'SKU-GAP-001', 'product_images/product1/produit_2.jpg', 1, 1, '2026-05-01 10:38:17', 'ensemble', 'toutes_saisons'),
('GAP-002', 'Débardeur basique', 'Débardeur basique de couleur Blanc pour Femme. Un indispensable de la garde-robe.', 19.99, 120, 'SKU-GAP-002', 'product_images/product2/produit_8.jpg', 1, 2, '2026-05-01 10:38:17', 'haut', 'toutes_saisons'),
('GAP-003', 'Débardeur', 'Débardeur de couleur Jaune pour Femme. Léger et confortable.', 15.99, 85, 'SKU-GAP-003', 'product_images/product3/produit_17.jpg', 1, 1, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-004', 'Pull léger à manches longues', 'Pull léger à manches longues de couleur Jaune pour Femme.', 39.99, 42, 'SKU-GAP-004', 'product_images/product4/produit_23.jpg', 1, 1, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-005', 'Chemise à manches courtes', 'Chemise à manches courtes de couleur Blanc pour Homme.', 34.99, 63, 'SKU-GAP-005', 'product_images/product5/produit_29.jpg', 2, 2, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-006', 'Ensemble top et jupe', 'Ensemble top et jupe de couleur Jaune pastel pour Enfant.', 29.99, 38, 'SKU-GAP-006', 'product_images/product6/produit_34.jpg', 3, 3, '2026-05-01 10:38:18', 'ensemble', 'toutes_saisons'),
('GAP-007', 'Robe t-shirt', 'Robe t-shirt de couleur Jaune pastel pour Enfant.', 24.99, 57, 'SKU-GAP-007', 'product_images/product7/produit_38.jpg', 3, 3, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-008', 'T-shirt basique', 'T-shirt basique de couleur Blanc pour Enfant.', 12.99, 200, 'SKU-GAP-008', 'product_images/product8/produit_42.jpg', 3, 2, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-009', 'T-shirt graphique 93', 'T-shirt graphique 93 de couleur Jaune pour Enfant.', 17.99, 74, 'SKU-GAP-009', 'product_images/product9/produit_48.jpg', 3, 1, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-010', 'T-shirt classique', 'T-shirt classique de couleur Jaune pastel pour Homme.', 18.99, 92, 'SKU-GAP-010', 'product_images/product10/produit_52.jpg', 2, 3, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-011', 'Débardeur sans manches', 'Débardeur sans manches de couleur Blanc pour Femme.', 14.99, 110, 'SKU-GAP-011', 'product_images/product11/produit_58.jpg', 1, 2, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-012', 'Veste en jean', 'Veste en jean de couleur Bleu denim pour Enfant.', 45.99, 18, 'SKU-GAP-012', 'product_images/product12/produit_65.jpg', 3, 4, '2026-05-01 10:38:18', 'haut', 'toutes_saisons'),
('GAP-013', 'Veste en jean classique', 'Veste en jean classique de couleur Bleu denim pour Enfant.', 47.99, 22, 'SKU-GAP-013', 'product_images/product13/produit_68.jpg', 3, 4, '2026-05-01 10:38:19', 'haut', 'toutes_saisons'),
('GAP-014', 'Débardeur cintré', 'Débardeur cintré de couleur Blanc pour Femme.', 16.99, 67, 'SKU-GAP-014', 'product_images/product14/produit_72.jpg', 1, 2, '2026-05-01 10:38:19', 'haut', 'toutes_saisons'),
('GAP-015', 'T-shirt manches courtes', 'T-shirt manches courtes de couleur Blanc pour Femme.', 13.99, 155, 'SKU-GAP-015', 'product_images/product15/produit_79.jpg', 1, 2, '2026-05-01 10:38:19', 'haut', 'toutes_saisons'),
('GAP-016', 'T-shirt col rond', 'T-shirt col rond de couleur Blanc pour Femme.', 13.99, 140, 'SKU-GAP-016', 'product_images/product16/produit_86.jpg', 1, 2, '2026-05-01 10:38:19', 'haut', 'toutes_saisons'),
('GAP-017', 'Chemise rayée ample', 'Chemise rayée ample de couleur Bleu clair pour Femme.', 36.99, 31, 'SKU-GAP-017', 'product_images/product17/produit_94.jpg', 1, 5, '2026-05-01 10:38:19', 'haut', 'toutes_saisons'),
('GAP-018', 'Sac cabas tressé', 'Sac cabas tressé de couleur Beige naturel pour Accessoires.', 29.99, 45, 'SKU-GAP-018', 'product_images/product18/produit_99.jpg', 4, 6, '2026-05-01 10:38:19', 'accessoire', 'toutes_saisons'),
('GAP-019', 'Chemise à motifs', 'Chemise à motifs de couleur Bleu pour Enfant.', 22.99, 53, 'SKU-GAP-019', 'product_images/product19/produit_103.jpg', 3, 7, '2026-05-01 10:38:19', 'haut', 'ete'),
('GAP-020', 'T-shirt manches longues', 'T-shirt manches longues de couleur Blanc pour Enfant.', 15.99, 88, 'SKU-GAP-020', 'product_images/product20/produit_107.jpg', 3, 2, '2026-05-01 10:38:19', 'haut', 'toutes_saisons'),
('GAP-021', 'Ensemble pyjama chemise pantalon', 'Ensemble pyjama chemise pantalon Gris rayé Femme.', 39.99, 34, 'SKU-GAP-021', 'product_images/product21/produit_112.jpg', 1, 8, '2026-05-01 10:38:19', 'ensemble', 'toutes_saisons'),
('GAP-022', 'Ensemble pyjama chemise pantalon', 'Ensemble pyjama Gris rayé Femme.', 39.99, 29, 'SKU-GAP-022', 'product_images/product22/produit_121.jpg', 1, 8, '2026-05-01 10:38:20', 'ensemble', 'toutes_saisons'),
('GAP-023', 'Ensemble pyjama court', 'Pyjama court Bleu clair Enfant.', 27.99, 61, 'SKU-GAP-023', 'product_images/product23/produit_126.jpg', 3, 5, '2026-05-01 10:38:20', 'ensemble', 'toutes_saisons'),
('GAP-024', 'Polo texturé', 'Polo Marron Homme.', 32.99, 47, 'SKU-GAP-024', 'product_images/product24/produit_129.jpg', 2, 9, '2026-05-01 10:38:20', 'haut', 'toutes_saisons'),
('GAP-025', 'T-shirt basique manches courtes', 'T-shirt Blanc Homme.', 14.99, 178, 'SKU-GAP-025', 'product_images/product25/produit_134.jpg', 2, 2, '2026-05-01 10:38:20', 'haut', 'toutes_saisons'),
('GAP-026', 'Débardeur col V', 'Débardeur Blanc Femme.', 17.99, 96, 'SKU-GAP-026', 'product_images/product26/produit_148.jpg', 1, 2, '2026-05-01 10:38:20', 'haut', 'toutes_saisons'),
('GAP-027', 'Grand sac cabas', 'Sac Beige Accessoires.', 34.99, 27, 'SKU-GAP-027', 'product_images/product27/produit_153.jpg', 4, 10, '2026-05-01 10:38:20', 'accessoire', 'toutes_saisons'),
('GAP-028', 'Surchemise légère', 'Surchemise Blanc Enfant.', 28.99, 39, 'SKU-GAP-028', 'product_images/product28/produit_145.jpg', 3, 2, '2026-05-01 10:38:20', 'haut', 'toutes_saisons'),
('GAP-029', 'Ensemble chemise short', 'Ensemble Beige clair Femme.', 44.99, 33, 'SKU-GAP-029', 'product_images/product29/produit_160.jpg', 1, 11, '2026-05-01 10:38:20', 'ensemble', 'toutes_saisons'),
('GAP-030', 'Ensemble chemise manches longues', 'Ensemble Beige clair Femme.', 46.99, 26, 'SKU-GAP-030', 'product_images/product30/produit_165.jpg', 1, 11, '2026-05-01 10:38:20', 'ensemble', 'toutes_saisons'),
('GAP-031', 'Top court manches longues', 'Top Blanc Femme.', 23.99, 52, 'SKU-GAP-031', 'product_images/product31/produit_172.jpg', 1, 2, '2026-05-01 10:38:20', 'haut', 'toutes_saisons'),
('GAP-032', 'Chemise en lin', 'Chemise Beige clair Homme.', 42.99, 41, 'SKU-GAP-032', 'product_images/product32/produit_177.jpg', 2, 11, '2026-05-01 10:38:21', 'haut', 'toutes_saisons'),
('GAP-033', 'Débardeur fines bretelles', 'Débardeur Marron Femme.', 16.99, 73, 'SKU-GAP-033', 'product_images/product33/produit_182.jpg', 1, 9, '2026-05-01 10:38:21', 'haut', 'toutes_saisons'),
('GAP-034', 'Pantalon ample', 'Pantalon Blanc Femme.', 38.99, 48, 'SKU-GAP-034', 'product_images/product34/produit_188.jpg', 1, 2, '2026-05-01 10:38:21', 'bas', 'toutes_saisons'),
('GAP-035', 'Chemise manches longues', 'Chemise Marron Homme.', 37.99, 55, 'SKU-GAP-035', 'product_images/product35/produit_193.jpg', 2, 9, '2026-05-01 10:38:21', 'haut', 'toutes_saisons'),
('GAP-036', 'Sandales plates', 'Sandales Marron et Blanc.', 27.99, 35, 'SKU-GAP-036', 'product_images/product36/produit_197.jpg', 4, 18, '2026-05-01 10:38:21', 'accessoire', 'toutes_saisons'),
('GAP-037', 'T-shirt classique', 'T-shirt Marron Homme.', 18.99, 84, 'SKU-GAP-037', 'product_images/product37/LV04RD008G_UB1_main.webp', 2, 9, '2026-05-01 10:38:21', 'haut', 'toutes_saisons'),
('GAP-038', 'Ensemble t-shirt short', 'Ensemble Beige clair Femme.', 41.99, 30, 'SKU-GAP-038', 'product_images/product38/produit_201.jpg', 1, 11, '2026-05-01 10:38:21', 'ensemble', 'toutes_saisons'),
('GAP-039', 'Chemise fluide', 'Chemise Marron foncé Femme.', 39.99, 44, 'SKU-GAP-039', 'product_images/product39/produit_208.jpg', 1, 12, '2026-05-01 10:38:21', 'haut', 'toutes_saisons'),
('GAP-040', 'Ensemble chemise pantalon', 'Ensemble Marron foncé Femme.', 54.99, 21, 'SKU-GAP-040', 'product_images/product40/produit_214.jpg', 1, 12, '2026-05-01 10:38:21', 'ensemble', 'toutes_saisons'),
('GAP-041', 'Chemise manches courtes', 'Chemise Noir Homme.', 33.99, 59, 'SKU-GAP-041', 'product_images/product41/produit_222.jpg', 2, 13, '2026-05-01 10:38:21', 'haut', 'toutes_saisons'),
('GAP-042', 'Pull texturé', 'Pull Rose fuchsia Femme.', 43.99, 37, 'SKU-GAP-042', 'product_images/product42/produit_227.jpg', 1, 14, '2026-05-01 10:38:21', 'haut', 'toutes_saisons'),
('GAP-043', 'Débardeur côtelé', 'Débardeur Noir Femme.', 17.99, 91, 'SKU-GAP-043', 'product_images/product43/produit_232.jpg', 1, 13, '2026-05-01 10:38:21', 'haut', 'toutes_saisons'),
('GAP-044', 'Ensemble fluide', 'Ensemble Rose fuchsia Femme.', 52.99, 24, 'SKU-GAP-044', 'product_images/product44/produit_237.jpg', 1, 14, '2026-05-01 10:38:22', 'ensemble', 'toutes_saisons'),
('GAP-045', 'Ensemble vue face', 'Ensemble Rose fuchsia Femme.', 52.99, 23, 'SKU-GAP-045', 'product_images/product45/produit_243.jpg', 1, 14, '2026-05-01 10:38:22', 'ensemble', 'toutes_saisons'),
('GAP-046', 'Haut sans manches', 'Haut Noir Femme.', 26.99, 49, 'SKU-GAP-046', 'product_images/product46/produit_253.jpg', 1, 13, '2026-05-01 10:38:22', 'haut', 'toutes_saisons'),
('GAP-047', 'Chemise motifs', 'Chemise Beige imprimé Homme.', 35.99, 42, 'SKU-GAP-047', 'product_images/product47/produit_257.jpg', 2, 15, '2026-05-01 10:38:22', 'haut', 'toutes_saisons'),
('GAP-048', 'Chemise texturée', 'Chemise Gris-vert Homme.', 36.99, 38, 'SKU-GAP-048', 'product_images/product48/produit_262.jpg', 2, 16, '2026-05-01 10:38:22', 'haut', 'toutes_saisons'),
('GAP-049', 'Surchemise', 'Surchemise Noir Homme.', 40.99, 32, 'SKU-GAP-049', 'product_images/product49/produit_267.jpg', 2, 13, '2026-05-01 10:38:22', 'haut', 'toutes_saisons'),
('GAP-050', 'Sac cabas zébré', 'Sac Noir et Blanc.', 31.99, 28, 'SKU-GAP-050', 'product_images/product50/produit_272.jpg', 4, 17, '2026-05-01 10:38:22', 'accessoire', 'toutes_saisons'),
('GAP-051', 'Ensemble débardeur pantalon', 'Ensemble Gris chiné Femme.', 48.99, 19, 'SKU-GAP-051', 'product_images/product51/produit_276.jpg', 1, 19, '2026-05-01 10:38:22', 'ensemble', 'toutes_saisons'),
('GAP-052', 'Ensemble pull pantalon', 'Ensemble Beige imprimé Femme.', 56.99, 16, 'SKU-GAP-052', 'product_images/product52/produit_285.jpg', 1, 15, '2026-05-01 10:38:22', 'ensemble', 'toutes_saisons'),
('GAP-053', 'Ensemble tunique pantalon', 'Ensemble Beige Femme.', 50.99, 20, 'SKU-GAP-053', 'product_images/product53/produit_291.jpg', 1, 10, '2026-05-01 10:38:22', 'ensemble', 'toutes_saisons'),
('GAP-054', 'Ensemble chemise short', 'Ensemble Blanc Femme.', 47.99, 27, 'SKU-GAP-054', 'product_images/product54/produit_299.jpg', 1, 2, '2026-05-01 10:38:22', 'ensemble', 'toutes_saisons'),
('GAP-055', 'Chemise légère', 'Chemise Blanc Femme.', 33.99, 40, 'SKU-GAP-055', 'product_images/product55/produit_309.jpg', 1, 2, '2026-05-01 10:38:23', 'haut', 'toutes_saisons'),
('GAP-056', 'Top bustier', 'Top Blanc Femme.', 21.99, 65, 'SKU-GAP-056', 'product_images/product56/produit_314.jpg', 1, 2, '2026-05-01 10:38:23', 'haut', 'toutes_saisons');

-- --------------------------------------------------------

--
-- Table structure for table `produits_t`
--

CREATE TABLE `produits_t` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produits_t`
--

INSERT INTO `produits_t` (`id`, `name`, `description`, `price`, `stock`, `sku`, `image_path`, `category`, `color`, `created_at`) VALUES
(1, 'MacBook Pro M3 Pro 14\"', 'Ordinateur portable Apple avec puce M3 Pro, 18 Go de RAM, 512 Go SSD, écran Liquid Retina XDR', 2499.99, 12, 'MBP-M3P-14-512', 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef', 'Ordinateurs', 'Gris Sidéral', '2025-11-15 10:23:45'),
(2, 'iPhone 16 Pro Max', 'Smartphone Apple avec écran Super Retina XDR 6,9\", caméra 48 MP, Titanium Design', 1479.99, 28, 'IP16-PM-256', 'https://images.unsplash.com/photo-1726660578-8f5f2e4c8f0d', 'Smartphones', 'Titane Noir', '2026-01-22 14:12:30'),
(3, 'Samsung Galaxy S25 Ultra', 'Smartphone Android haut de gamme avec stylet S Pen, zoom 100x et IA Galaxy', 1399.99, 15, 'SGS25U-512', 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf', 'Smartphones', 'Titane Gris', '2026-02-10 09:45:12'),
(4, 'Sony WH-1000XM6', 'Casque audio sans fil à réduction de bruit active leader du marché', 429.99, 47, 'WH1000XM6-B', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e', 'Audio', 'Noir', '2025-09-05 16:30:00'),
(5, 'Dell XPS 14 (2026)', 'Ultrabook premium avec écran OLED 3.2K, Intel Core Ultra 7, 32 Go RAM', 1899.99, 8, 'XPS14-2026-32', 'https://images.unsplash.com/photo-1593642632823-8f785ba67e6c', 'Ordinateurs', 'Argent', '2026-03-01 11:20:15'),
(6, 'Logitech MX Master 3S', 'Souris sans fil ergonomique pour professionnels avec capteur 8000 DPI', 99.99, 124, 'MXM3S-GR', 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46', 'Périphériques', 'Gris Graphite', '2025-08-12 08:55:40'),
(7, 'Samsung 49\" Odyssey G9 OLED', 'Écran gaming ultra-large 49 pouces, courbé, 240Hz, Dual QHD', 1499.99, 6, 'ODG9-OLED', 'https://images.unsplash.com/photo-1593305841992-05c8f2c1f1e0', 'Écrans', 'Noir/Blanc', '2026-01-05 13:40:22'),
(8, 'Apple AirPods Max 2', 'Casque audio supra-auriculaire premium avec audio spatial et ANC', 599.99, 19, 'APM2-SL', 'https://images.unsplash.com/photo-1583394838339-acb4b2e9b2c6', 'Audio', 'Argent', '2025-12-18 17:05:55'),
(9, 'DJI Mini 4 Pro', 'Drone compact avec caméra 4K 60fps, transmission O4 et détection d\'obstacles', 759.99, 33, 'DJI-M4P', 'https://images.unsplash.com/photo-1473968512647-3e447244af8f', 'Drones', 'Gris Clair', '2025-10-20 10:15:30'),
(10, 'NVIDIA RTX 5090 Founders Edition', 'Carte graphique haut de gamme Blackwell avec 32 Go GDDR7', 2499.99, 4, 'RTX5090-FE', 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7', 'Composants', 'Anthracite', '2026-03-20 14:55:10'),
(11, 'Google Pixel 9 Pro XL', 'Smartphone avec IA Gemini avancée, écran 6,8\" et appareil photo exceptionnel', 1099.99, 22, 'PIX9PXL-128', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9', 'Smartphones', 'Porcelaine', '2025-08-25 09:30:45'),
(12, 'ASUS ROG Zephyrus G16', 'PC portable gaming ultrafin avec RTX 4070 et écran 240Hz', 1799.99, 14, 'ROG-G16-4070', 'https://images.unsplash.com/photo-1603302576837-37561b2e2308', 'Ordinateurs', 'Gris Éclipse', '2026-02-28 16:40:18'),
(13, 'Apple Watch Ultra 3', 'Montre connectée extrême avec GPS double fréquence et autonomie 36h', 899.99, 31, 'AWU3-TI', 'https://images.unsplash.com/photo-1434493789847-2f02dc6e35f7', 'Wearables', 'Titane Naturel', '2026-01-15 11:25:00'),
(14, 'Sony A7R V', 'Appareil photo hybride plein format 61 MP avec stabilisation 8 stops', 3899.99, 7, 'A7RV-BODY', 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a', 'Photo', 'Noir', '2025-07-10 18:12:35'),
(15, 'Razer BlackWidow V4 Pro', 'Clavier mécanique gaming RGB avec switches Orange tactiles', 229.99, 56, 'RBW4P-T', 'https://images.unsplash.com/photo-1541140532154-b024d705b90a', 'Périphériques', 'Noir', '2025-11-30 07:50:22'),
(16, 'Samsung Galaxy Tab S10 Ultra', 'Tablette Android 14,8\" avec S Pen incluse et puce Snapdragon 8 Gen 4', 1199.99, 18, 'GTS10U-256', 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0', 'Tablettes', 'Gris Anthracite', '2026-03-05 12:18:40'),
(17, 'Bose QuietComfort Ultra', 'Casque à réduction de bruit la plus performante de Bose', 429.99, 41, 'QCULTRA-BLK', 'https://images.unsplash.com/photo-1484704849700-f032a568e944', 'Audio', 'Noir', '2025-09-28 15:05:55'),
(18, 'Lenovo ThinkPad X1 Carbon Gen 13', 'Ultrabook professionnel léger et ultra-résistant', 1699.99, 9, 'TPX1C13-32', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853', 'Ordinateurs', 'Noir Carbone', '2025-12-22 10:40:12'),
(19, 'GoPro Hero 13 Black', 'Caméra d\'action 5.3K avec HyperSmooth 6.0 et batteries longue durée', 399.99, 67, 'GP13-BLK', 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32', 'Photo', 'Noir', '2026-02-14 08:35:20'),
(20, 'AMD Ryzen 9 9950X3D', 'Processeur gaming 16 cœurs avec 3D V-Cache de nouvelle génération', 699.99, 25, 'RYZ9950X3D', 'https://images.unsplash.com/photo-1591799264318-7e6ef8e0b9e9', 'Composants', 'Gris Métal', '2026-03-12 14:22:05'),
(21, 'Microsoft Surface Laptop 7', 'Ultrabook avec Snapdragon X Elite, écran tactile PixelSense 13.8\"', 1299.99, 13, 'SL7-13-XE', 'https://images.unsplash.com/photo-1498050108023-c5249f4df085', 'Ordinateurs', 'Platine', '2025-10-08 09:15:30'),
(22, 'OnePlus 13', 'Smartphone flagship avec charge ultra-rapide 100W et écran 120Hz fluide', 899.99, 37, 'OP13-512', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9', 'Smartphones', 'Vert Forêt', '2026-01-28 11:50:45'),
(23, 'Keychron Q1 HE', 'Clavier mécanique custom avec switches magnétiques Hall Effect', 189.99, 48, 'KQ1HE', 'https://images.unsplash.com/photo-1541140532154-b024d705b90a', 'Périphériques', 'Bleu Marine', '2025-11-10 16:30:00'),
(24, 'LG UltraGear 45GR95QE', 'Écran OLED gaming 45\" courbé, 240Hz, 0.03ms', 1299.99, 11, 'LG45GR95QE', 'https://images.unsplash.com/photo-1593302576837-37561b2e2308', 'Écrans', 'Noir', '2025-12-05 13:10:25'),
(25, 'Garmin Fenix 8 Solar', 'Montre multisport premium avec énergie solaire et carte topo intégrée', 999.99, 16, 'FENIX8-SOL', 'https://images.unsplash.com/photo-1434493789847-2f02dc6e35f7', 'Wearables', 'Titane Orange', '2026-02-20 07:45:12');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `avatar` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL CHECK (`age` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `phone`, `gender`, `lastname`, `firstname`, `avatar`, `user_id`, `age`) VALUES
(1, NULL, NULL, NULL, '', NULL, 15, NULL),
(8, '', 'female', '', 'ahmed', 0, 27, 25);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` varchar(30) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL CHECK (`rating` between 0 and 5),
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `status`, `updated_at`, `created_at`) VALUES
(2, 27, 'GAP-048', 5.0, 'test - test', 'pending', NULL, '2026-04-20 17:19:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `is_active` tinyint(1) DEFAULT 1,
  `password` varchar(255) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(6) DEFAULT NULL,
  `code_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `is_active`, `password`, `reset_token_hash`, `reset_token_expires_at`, `is_verified`, `verification_code`, `code_expiry`) VALUES
(4, 'ahmed', 'ahmed.jday5@gmail.com', 'customer', 1, '$2y$10$.fLfFAmkuqCL8k5j3Vyr6uJzDZF/9ZNFPzZCjUGMpStyXTsHUC0EK', NULL, NULL, 0, NULL, NULL),
(6, 'ahmed', 'ahmed.jday00@gmail.com', 'customer', 1, '$2y$10$XEzxbT.8KJ.A2smqrY2MeeAqVq6XYqUa0ZQqL7A60Gz.JD9JVk94u', NULL, NULL, 0, NULL, NULL),
(7, 'ahmed', 'ahmed.jday8@gmail.com', 'customer', 1, '$2y$10$5iC5GvSxhn91ymFW7dgU7.x3Er14BfQJKu/M4IkACn7eepcDB7o3y', NULL, NULL, 0, NULL, NULL),
(8, 'ahmed', 'ahmed.jday89@gmail.com', 'customer', 1, '$2y$10$/OtQlyrmNd4TgkJkreuFDusquQ.XpMLKzxSm3rsoF/7Nwy2qvQQSa', NULL, NULL, 0, NULL, NULL),
(14, 'ahmed', 'ahmed.jday75@gmail.com', 'customer', 1, '$2y$10$nw/OKdoKCS1JTZycT/T0FeGRFOxx5TrFJCGnyMW5W.8T.JoxW9uqO', NULL, NULL, 0, NULL, NULL),
(15, 'testuser', 'test@example.com', 'customer', 1, '$2y$10$gEjMWHO4rZfXzRJsuKM4Qe302APIle7/JvYHFC3a3OIfoRLJi/rnC', NULL, NULL, 0, NULL, NULL),
(27, 'ahmed', 'ahmed.jday2005@gmail.com', 'customer', 1, '$2y$10$KwfjGmsV0on85V9m8271UeTXVnroqALYHYMYEl6WRZMVJ0UfGVb7u', 'e94a035b3f726be6f70be79756e51454784adce0bfdb2efb7791f9bba04eb574', '2026-04-21 19:34:18', 1, NULL, NULL),
(28, 'admin', 'admin12@gmail.com', 'admin', 1, '$2y$10$KwfjGmsV0on85V9m8271UeTXVnroqALYHYMYEl6WRZMVJ0UfGVb7u', NULL, NULL, 1, NULL, NULL),
(29, 'ahmed', 'admin123@gmail.com', 'admin', 1, '$2y$10$Su1MUkk3BiLt4LrTQmEnzezLYPw9stezs4SfciJdYKPcFvz7wwEba', NULL, NULL, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_produit` (`produit_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `fk_favorites_product` (`product_id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `pc_builds`
--
ALTER TABLE `pc_builds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pc_build_items`
--
ALTER TABLE `pc_build_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `build_id` (`build_id`),
  ADD KEY `component_id` (`component_id`);

--
-- Indexes for table `pc_components`
--
ALTER TABLE `pc_components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Indexes for table `produits_t`
--
ALTER TABLE `produits_t`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_fk` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pc_builds`
--
ALTER TABLE `pc_builds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pc_build_items`
--
ALTER TABLE `pc_build_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pc_components`
--
ALTER TABLE `pc_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_produit` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_product` FOREIGN KEY (`product_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produits` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pc_builds`
--
ALTER TABLE `pc_builds`
  ADD CONSTRAINT `pc_builds_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pc_build_items`
--
ALTER TABLE `pc_build_items`
  ADD CONSTRAINT `pc_build_items_ibfk_1` FOREIGN KEY (`build_id`) REFERENCES `pc_builds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pc_build_items_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `pc_components` (`id`);

--
-- Constraints for table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
