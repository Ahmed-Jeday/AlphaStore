-- ============================================
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
    CONSTRAINT favorites_ibfk_2 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT favorites_ibfk_1 FOREIGN KEY (product_id) REFERENCES produits(id) ON DELETE CASCADE
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
    CONSTRAINT reviews_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT reviews_ibfk_2 FOREIGN KEY (product_id) REFERENCES produits(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des journaux de connexion
CREATE TABLE IF NOT EXISTS login_logs (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    status ENUM('success','failed') DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY user_id (user_id),
    CONSTRAINT login_logs_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
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

-- ============================================
-- DONNÉES DE TEST (DEMO)
-- ============================================

-- Les données réelles sont dans alphaStoreDb.sql
-- On peut insérer un admin par défaut si besoin
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@alphastore.com', '$2y$10$JCJk5w5YIjwrmdXtvNoP3OR90.xZpGxFH6l8MvgtvwiRProsncTDW', 'admin')
ON DUPLICATE KEY UPDATE role='admin';