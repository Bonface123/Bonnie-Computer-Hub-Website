-- Database for Traffic Management System
CREATE DATABASE IF NOT EXISTS traffic_management;

USE traffic_management;

-- Table for traffic reports
CREATE TABLE IF NOT EXISTS traffic_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(100) NOT NULL,
    congestion_level ENUM('Low', 'Medium', 'High') NOT NULL,
    road_condition VARCHAR(100) NOT NULL,
    report_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for incident reports
CREATE TABLE IF NOT EXISTS incidents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(100) NOT NULL,
    incident_type VARCHAR(50) NOT NULL,
    description TEXT,
    reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for traffic statistics
CREATE TABLE IF NOT EXISTS traffic_statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    statistic_type VARCHAR(50) NOT NULL,
    value INT NOT NULL,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
