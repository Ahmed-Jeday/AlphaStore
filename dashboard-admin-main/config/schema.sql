-- ============================================
<<<<<<< HEAD
-- SCHÉMA BASE DE DONNÉES - AlphaStore Admin
-- Basé sur la structure réelle de alphaStoreDb.sql
-- ============================================

USE alphastore;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) DEFAULT NULL,
    UNIQUE KEY name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des couleurs
CREATE TABLE IF NOT EXISTS colors (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) DEFAULT NULL,
    UNIQUE KEY name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des produits (nom correct: produits)
CREATE TABLE IF NOT EXISTS produits (
    id VARCHAR(20) NOT NULL PRIMARY KEY,
    name VARCHAR(150) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10,2) DEFAULT NULL,
    stock INT DEFAULT NULL,
    sku VARCHAR(100) DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    category_id INT DEFAULT NULL,
    color_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY category_id (category_id),
    KEY color_id (color_id),
    CONSTRAINT produits_ibfk_1 FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    CONSTRAINT produits_ibfk_2 FOREIGN KEY (color_id) REFERENCES colors(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('admin','customer') DEFAULT 'customer',
    is_active TINYINT(1) DEFAULT 1,
    password VARCHAR(255) NOT NULL,
    reset_token_hash VARCHAR(64) DEFAULT NULL,
    reset_token_expires_at DATETIME DEFAULT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    verification_code VARCHAR(6) DEFAULT NULL,
    code_expiry DATETIME DEFAULT NULL,
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des commandes (correction: total_price au lieu de total)
CREATE TABLE IF NOT EXISTS orders (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    total_price DECIMAL(10,2) DEFAULT NULL,
    status ENUM('pending','paid','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY user_id (user_id),
    CONSTRAINT orders_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des articles de commande (correction: price au lieu de unit_price)
CREATE TABLE IF NOT EXISTS order_items (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id INT DEFAULT NULL,
    product_id VARCHAR(30) DEFAULT NULL,
    quantity INT DEFAULT NULL,
    price DECIMAL(10,2) DEFAULT NULL,
    PRIMARY KEY (id),
    KEY order_id (order_id),
    KEY product_id (product_id),
    CONSTRAINT order_items_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT order_items_ibfk_2 FOREIGN KEY (product_id) REFERENCES produits(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des favoris
CREATE TABLE IF NOT EXISTS favorites (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_id (user_id,product_id),
    KEY fk_favorites_product (product_id),
    CONSTRAINT favorites_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des avis/commentaires
CREATE TABLE IF NOT EXISTS reviews (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id VARCHAR(30) NOT NULL,
    rating DECIMAL(2,1) DEFAULT NULL CHECK (rating BETWEEN 0 AND 5),
    comment TEXT DEFAULT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    updated_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_id (user_id,product_id),
    KEY product_id (product_id),
    CONSTRAINT reviews_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des journaux de connexion
CREATE TABLE IF NOT EXISTS login_logs (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    status ENUM('success','failed') DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des profils utilisateurs
CREATE TABLE IF NOT EXISTS profiles (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) DEFAULT NULL,
    gender ENUM('male','female') DEFAULT NULL,
    lastname VARCHAR(100) DEFAULT NULL,
    firstname VARCHAR(100) NOT NULL,
    avatar INT DEFAULT NULL,
    user_id INT DEFAULT NULL,
    age INT DEFAULT NULL CHECK (age > 0),
    PRIMARY KEY (id),
    KEY user_id_fk (user_id),
    CONSTRAINT profiles_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table panier
CREATE TABLE IF NOT EXISTS cart (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    produit_id VARCHAR(32) NOT NULL,
    quantite INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY fk_user (user_id),
    KEY fk_produit (produit_id),
    CONSTRAINT cart_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT cart_ibfk_2 FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
=======
-- SCHÉMA BASE DE DONNÉES - E-Commerce Admin
-- ============================================

CREATE DATABASE IF NOT EXISTS ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce_db;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des commandes
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des articles de commande
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Table des favoris
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_fav (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
>>>>>>> c9b4dfd97ac92a7c1c6cf615116ce52bc0f3ba68

-- ============================================
-- DONNÉES DE TEST (DEMO)
-- ============================================

INSERT INTO categories (name) VALUES ('Électronique'), ('Vêtements'), ('Livres'), ('Maison'), ('Sport');

INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@shop.com', '$2y$10$JCJk5w5YIjwrmdXtvNoP3OR90.xZpGxFH6l8MvgtvwiRProsncTDW', 'admin'),
('Alice Martin', 'alice@example.com', '$2y$10$JCJk5w5YIjwrmdXtvNoP3OR90.xZpGxFH6l8MvgtvwiRProsncTDW', 'user'),
('Bob Dupont', 'bob@example.com', '$2y$10$JCJk5w5YIjwrmdXtvNoP3OR90.xZpGxFH6l8MvgtvwiRProsncTDW', 'user'),
('Clara Leroy', 'clara@example.com', '$2y$10$JCJk5w5YIjwrmdXtvNoP3OR90.xZpGxFH6l8MvgtvwiRProsncTDW', 'user'),
('David Moreau', 'david@example.com', '$2y$10$JCJk5w5YIjwrmdXtvNoP3OR90.xZpGxFH6l8MvgtvwiRProsncTDW', 'user');
-- Mot de passe pour tous : "password"

INSERT INTO products (name, description, price, stock, category_id) VALUES
('iPhone 15 Pro', 'Dernier smartphone Apple avec puce A17', 1299.99, 45, 1),
('MacBook Air M2', 'Ordinateur portable ultra-léger', 1599.99, 12, 1),
('Samsung 4K TV', 'Télévision 55 pouces 4K OLED', 899.99, 8, 1),
('Nike Air Max', 'Chaussures de running confortables', 149.99, 0, 5),
('Veste Levi''s', 'Veste en jean classique', 89.99, 34, 2),
('Python avancé', 'Livre de programmation Python', 39.99, 67, 3),
('Canapé moderne', 'Canapé 3 places tissu gris', 699.99, 5, 4),
('Lampe LED bureau', 'Lampe de bureau avec variateur', 49.99, 23, 4),
('Vélo électrique', 'Vélo électrique 250W batterie longue durée', 1200.00, 3, 5),
('Casque Sony WH-1000XM5', 'Casque Bluetooth réduction de bruit', 379.99, 18, 1);

INSERT INTO orders (user_id, total, status, created_at) VALUES
(2, 1449.98, 'delivered', NOW() - INTERVAL 30 DAY),
(3, 899.99, 'shipped', NOW() - INTERVAL 20 DAY),
(4, 239.98, 'processing', NOW() - INTERVAL 10 DAY),
(5, 1649.99, 'pending', NOW() - INTERVAL 5 DAY),
(2, 379.99, 'delivered', NOW() - INTERVAL 3 DAY),
(3, 49.99, 'delivered', NOW() - INTERVAL 2 DAY),
(4, 1299.99, 'shipped', NOW() - INTERVAL 1 DAY),
(5, 89.99, 'pending', NOW());

<<<<<<< HEAD
=======
INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(1, 1, 1, 1299.99), (1, 6, 1, 39.99), (1, 8, 1, 49.99),
(2, 3, 1, 899.99),
(3, 5, 1, 89.99), (3, 8, 1, 49.99), (3, 6, 1, 39.99), (3, 6, 1, 39.99),
(4, 2, 1, 1599.99),
(5, 10, 1, 379.99),
(6, 8, 1, 49.99),
(7, 1, 1, 1299.99),
(8, 5, 1, 89.99);
>>>>>>> c9b4dfd97ac92a7c1c6cf615116ce52bc0f3ba68

INSERT INTO favorites (user_id, product_id) VALUES
(2,1),(2,2),(2,10),(3,1),(3,3),(3,5),(4,1),(4,6),(4,9),(5,1),(5,2),(5,4),(5,10);