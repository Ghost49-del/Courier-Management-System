-- Create the database
CREATE DATABASE IF NOT EXISTS cms;

-- Switch to the cms database
USE cms;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `sno` int NOT NULL AUTO_INCREMENT,
    `username` varchar(20) NOT NULL,
    `email` varchar(25) DEFAULT NULL,
    `password` varchar(25) NOT NULL,
    `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`sno`),
    UNIQUE KEY `email` (`email`)
);

-- Create place_courier table
CREATE TABLE IF NOT EXISTS `place_courier` (
    `name` varchar(20) NOT NULL,
    `email` varchar(25) NOT NULL,
    `phone` bigint NOT NULL,
    `sadd` varchar(50) NOT NULL,
    `radd` varchar(50) NOT NULL,
    `date` date NOT NULL,
    PRIMARY KEY (`email`,`phone`),
    UNIQUE KEY `uk_phone` (`phone`),
    FOREIGN KEY (`email`) REFERENCES `users`(`email`) ON DELETE CASCADE
);

-- Create feedback table
CREATE TABLE IF NOT EXISTS `feedback` (
    `name` varchar(25) NOT NULL,
    `phone` bigint NOT NULL,
    `feedback` varchar(200) NOT NULL,
    UNIQUE KEY `phone` (`phone`),
    FOREIGN KEY (`phone`) REFERENCES place_courier(`phone`) ON DELETE CASCADE
);

-- Create franchise table
CREATE TABLE IF NOT EXISTS `franchise` (
    `sno` int NOT NULL AUTO_INCREMENT,
    `bname` varchar(20) NOT NULL,
    `bphone` bigint NOT NULL,
    `baddress` varchar(50) NOT NULL,
    PRIMARY KEY (`sno`)
);

-- Create staff table
CREATE TABLE IF NOT EXISTS `staff` (
    `sno` int NOT NULL AUTO_INCREMENT,
    `name` varchar(20) NOT NULL,
    `phone` bigint NOT NULL,
    `address` varchar(50) NOT NULL,
    `govid` varchar(50) NOT NULL,
    PRIMARY KEY (`govid`),
    UNIQUE KEY `phone` (`phone`),
    KEY `sno` (`sno`)
);

-- Add new columns to place_courier table
ALTER TABLE place_courier
ADD COLUMN IF NOT EXISTS order_placed_time DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'Pending',
ADD COLUMN IF NOT EXISTS invoice_date DATE,
ADD COLUMN IF NOT EXISTS account_name VARCHAR(50),
ADD COLUMN IF NOT EXISTS address1 VARCHAR(100),
ADD COLUMN IF NOT EXISTS invoice_number VARCHAR(50),
ADD COLUMN IF NOT EXISTS order_number VARCHAR(50),
ADD COLUMN IF NOT EXISTS external_order VARCHAR(50),
ADD COLUMN IF NOT EXISTS delivery_date_time DATETIME,
ADD COLUMN IF NOT EXISTS area VARCHAR(50),
ADD COLUMN IF NOT EXISTS drivers_name VARCHAR(50);
