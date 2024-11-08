CREATE DATABASE gps_locations;

USE gps_locations;

CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    country VARCHAR(255),
    city VARCHAR(255),
    postal_code VARCHAR(20),
    formatted_address TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);