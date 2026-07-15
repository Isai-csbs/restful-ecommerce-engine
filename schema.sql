CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Users table with role-based segregation
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products table with indexing for fast lookups
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_product_title (title)
) ENGINE=InnoDB;

-- Insert sample seed data
INSERT INTO products (title, description, price, stock_quantity) VALUES
('Premium Wireless Headphones', 'Noise-canceling over-ear headphones.', 149.99, 50),
('Mechanical Gaming Keyboard', 'RGB backlit mechanical switches.', 89.95, 35),
('Ergonomic Wireless Mouse', 'High precision optical tracking.', 45.00, 100);
