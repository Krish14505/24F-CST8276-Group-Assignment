-- Database script designed by Paulo Granjeiro (041118057) and implemented by Kyla, Leonardo, and Craig
-- Collaborators: Krish and Yazid.

-- NOTE!! This file can be dragged into phpmyadmin to create the database and tables but it won't work if there are comments
-- Drop existing database if it exists
DROP DATABASE IF EXISTS geolocation;
CREATE DATABASE geolocation;
USE geolocation;

-- Drop tables if they exist before creating them
DROP TABLE IF EXISTS RoutePoints;
DROP TABLE IF EXISTS Routes;
DROP TABLE IF EXISTS Locations;
DROP TABLE IF EXISTS Users;

-- Users Table
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Locations Table
CREATE TABLE Locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    country VARCHAR(255),
    city VARCHAR(255),
    postal_code VARCHAR(20),
    formatted_address TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Routes Table
CREATE TABLE Routes (
    route_id INT AUTO_INCREMENT PRIMARY KEY,
    route_name VARCHAR(50),
    total_distance DOUBLE,
    start_location_id INT,
    end_location_id INT,
    Users_user_id INT,
    FOREIGN KEY (start_location_id) REFERENCES Locations(location_id) ON DELETE CASCADE,
    FOREIGN KEY (end_location_id) REFERENCES Locations(location_id) ON DELETE CASCADE,
    FOREIGN KEY (Users_user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- RoutePoints Table
CREATE TABLE RoutePoints (
    route_point_id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT,
    sequence INT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (route_id) REFERENCES Routes(route_id) ON DELETE CASCADE
);

-- Drop existing user if it exists
DROP USER IF EXISTS 'geo_user'@'localhost';
-- Create custom database user
CREATE USER 'geo_user'@'localhost' IDENTIFIED BY 'geo_PASSWORD69!';
-- Grant privileges to user
GRANT ALL PRIVILEGES ON geolocation.* TO 'geo_user'@'localhost';
FLUSH PRIVILEGES;
