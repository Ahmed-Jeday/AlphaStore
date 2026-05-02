-- Migration pour la feature PC Builder
-- AlphaStore - Customer Build PC

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `pc_components`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `pc_components` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `component_type` ENUM('cpu','gpu','motherboard','ram','psu','storage','case') NOT NULL,
    `brand` VARCHAR(100),
    `price` DECIMAL(10,2) NOT NULL,
    `stock` INT DEFAULT 0,
    `image_url` VARCHAR(500),
    
    -- Performance (score normalisé 0-100)
    `performance_score` INT DEFAULT 50,
    
    -- Consommation (watts)
    `tdp` INT DEFAULT 0,
    
    -- Champs de compatibilité
    `socket` VARCHAR(50) DEFAULT NULL,       -- CPU & Mobo: 'AM5', 'LGA1700', 'AM4'
    `form_factor` VARCHAR(50) DEFAULT NULL,  -- Mobo & Case: 'ATX', 'mATX', 'ITX'
    `ram_type` VARCHAR(20) DEFAULT NULL,     -- Mobo & RAM: 'DDR4', 'DDR5'
    `ram_slots` INT DEFAULT NULL,            -- Mobo: nombre de slots
    `ram_modules` INT DEFAULT NULL,          -- RAM: nombre de barrettes dans le kit
    `wattage` INT DEFAULT NULL,              -- PSU: puissance en watts
    `gpu_max_length` INT DEFAULT NULL,       -- Case: longueur GPU max (mm)
    `gpu_length` INT DEFAULT NULL,           -- GPU: longueur (mm)
    
    -- Specs affichées (JSON libre pour l'UI)
    `specs` JSON DEFAULT NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `pc_builds`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `pc_builds` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `name` VARCHAR(255) DEFAULT 'Mon Build',
    `total_price` DECIMAL(10,2),
    `usage_profile` ENUM('gaming','workstation','budget','streaming') DEFAULT 'gaming',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `pc_build_items`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `pc_build_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `build_id` INT NOT NULL,
    `component_id` INT NOT NULL,
    FOREIGN KEY (`build_id`) REFERENCES `pc_builds`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`component_id`) REFERENCES `pc_components`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Insertion des données initiales (Données réalistes 2024-2025)
-- --------------------------------------------------------

INSERT INTO `pc_components` (`name`, `component_type`, `brand`, `price`, `stock`, `performance_score`, `tdp`, `socket`, `form_factor`, `ram_type`, `ram_slots`, `ram_modules`, `wattage`, `gpu_max_length`, `gpu_length`, `specs`) VALUES

-- CPUs
('AMD Ryzen 7 7800X3D', 'cpu', 'AMD', 449.00, 15, 98, 120, 'AM5', NULL, 'DDR5', NULL, NULL, NULL, NULL, NULL, '{"cores": 8, "threads": 16, "base_clock": "4.2GHz", "boost_clock": "5.0GHz"}'),
('Intel Core i7-14700K', 'cpu', 'Intel', 419.00, 20, 96, 125, 'LGA1700', NULL, 'DDR5', NULL, NULL, NULL, NULL, NULL, '{"cores": 20, "threads": 28, "base_clock": "3.4GHz", "boost_clock": "5.6GHz"}'),
('AMD Ryzen 5 7600', 'cpu', 'AMD', 229.00, 45, 75, 65, 'AM5', NULL, 'DDR5', NULL, NULL, NULL, NULL, NULL, '{"cores": 6, "threads": 12, "base_clock": "3.8GHz", "boost_clock": "5.1GHz"}'),
('Intel Core i5-13400F', 'cpu', 'Intel', 209.00, 30, 70, 65, 'LGA1700', NULL, 'DDR4', NULL, NULL, NULL, NULL, NULL, '{"cores": 10, "threads": 16, "base_clock": "2.5GHz", "boost_clock": "4.6GHz"}'),
('AMD Ryzen 9 9950X', 'cpu', 'AMD', 649.00, 10, 100, 170, 'AM5', NULL, 'DDR5', NULL, NULL, NULL, NULL, NULL, '{"cores": 16, "threads": 32, "base_clock": "4.3GHz", "boost_clock": "5.7GHz"}'),

-- GPUs
('NVIDIA GeForce RTX 4080 Super', 'gpu', 'NVIDIA', 1099.00, 8, 97, 320, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 310, '{"vram": "16GB GDDR6X", "interface": "PCIe 4.0"}'),
('NVIDIA GeForce RTX 4070 Ti Super', 'gpu', 'NVIDIA', 849.00, 12, 90, 285, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 305, '{"vram": "16GB GDDR6X", "interface": "PCIe 4.0"}'),
('AMD Radeon RX 7800 XT', 'gpu', 'AMD', 529.00, 18, 85, 263, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 267, '{"vram": "16GB GDDR6", "interface": "PCIe 4.0"}'),
('NVIDIA GeForce RTX 4060', 'gpu', 'NVIDIA', 299.00, 50, 65, 115, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 198, '{"vram": "8GB GDDR6", "interface": "PCIe 4.0"}'),
('NVIDIA GeForce RTX 5090 FE', 'gpu', 'NVIDIA', 1999.00, 3, 100, 450, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 336, '{"vram": "32GB GDDR7", "interface": "PCIe 5.0"}'),

-- Motherboards
('ASUS ROG STRIX B650-A GAMING WIFI', 'motherboard', 'ASUS', 259.00, 25, 80, 0, 'AM5', 'ATX', 'DDR5', 4, NULL, NULL, NULL, NULL, '{"chipset": "B650", "wifi": true}'),
('MSI MAG Z790 TOMAHAWK WIFI', 'motherboard', 'MSI', 289.00, 15, 85, 0, 'LGA1700', 'ATX', 'DDR5', 4, NULL, NULL, NULL, NULL, '{"chipset": "Z790", "wifi": true}'),
('Gigabyte B650M DS3H', 'motherboard', 'Gigabyte', 129.00, 40, 60, 0, 'AM5', 'mATX', 'DDR5', 4, NULL, NULL, NULL, NULL, '{"chipset": "B650", "wifi": false}'),
('ASUS PRIME B760M-K DDR4', 'motherboard', 'ASUS', 109.00, 35, 55, 0, 'LGA1700', 'mATX', 'DDR4', 2, NULL, NULL, NULL, NULL, '{"chipset": "B760", "wifi": false}'),

-- RAM
('Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz', 'ram', 'Corsair', 139.00, 60, 90, 0, NULL, NULL, 'DDR5', NULL, 2, NULL, NULL, NULL, '{"speed": "6000MHz", "latency": "CL36"}'),
('G.Skill Ripjaws V 16GB (2x8GB) DDR4 3200MHz', 'ram', 'G.Skill', 49.00, 100, 70, 0, NULL, NULL, 'DDR4', NULL, 2, NULL, NULL, NULL, '{"speed": "3200MHz", "latency": "CL16"}'),
('Crucial Pro 64GB (2x32GB) DDR5 5600MHz', 'ram', 'Crucial', 199.00, 20, 95, 0, NULL, NULL, 'DDR5', NULL, 2, NULL, NULL, NULL, '{"speed": "5600MHz", "latency": "CL46"}'),

-- PSUs
('Corsair RM750e 750W 80+ Gold', 'psu', 'Corsair', 119.00, 30, 85, 0, NULL, NULL, NULL, NULL, NULL, 750, NULL, NULL, '{"modular": "Full", "efficiency": "80+ Gold"}'),
('EVGA 600 W1 80+ White', 'psu', 'EVGA', 55.00, 50, 50, 0, NULL, NULL, NULL, NULL, NULL, 600, NULL, NULL, '{"modular": "No", "efficiency": "80+ White"}'),
('Seasonic Prime TX-1000 1000W 80+ Titanium', 'psu', 'Seasonic', 329.00, 10, 100, 0, NULL, NULL, NULL, NULL, NULL, 1000, NULL, NULL, '{"modular": "Full", "efficiency": "80+ Titanium"}'),

-- Storage
('Samsung 990 Pro 2TB NVMe', 'storage', 'Samsung', 189.00, 40, 98, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{"type": "NVMe Gen4", "read_speed": "7450MB/s"}'),
('Crucial P3 1TB NVMe', 'storage', 'Crucial', 69.00, 80, 75, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{"type": "NVMe Gen3", "read_speed": "3500MB/s"}'),
('Seagate Barracuda 2TB HDD', 'storage', 'Seagate', 59.00, 60, 40, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '{"type": "SATA HDD", "rpm": 7200}'),

-- Cases
('NZXT H5 Flow', 'case', 'NZXT', 99.00, 25, 80, 0, NULL, 'ATX', NULL, NULL, NULL, NULL, 365, NULL, '{"type": "Mid Tower", "fans_included": 2}'),
('Corsair 4000D Airflow', 'case', 'Corsair', 104.00, 30, 85, 0, NULL, 'ATX', NULL, NULL, NULL, NULL, 360, NULL, '{"type": "Mid Tower", "fans_included": 2}'),
('Cooler Master Q300L', 'case', 'Cooler Master', 49.00, 50, 60, 0, NULL, 'mATX', NULL, NULL, NULL, NULL, 360, NULL, '{"type": "Micro Tower", "fans_included": 1}');

COMMIT;
