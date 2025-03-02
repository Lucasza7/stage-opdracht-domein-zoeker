CREATE DATABASE domein_zoeker;

USE domein_zoeker;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    domains TEXT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    tax DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);