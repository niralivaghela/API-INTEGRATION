CREATE DATABASE weather_dashboard;
USE weather_dashboard;

CREATE TABLE search_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100),
  temperature FLOAT,
  description VARCHAR(255),
  icon VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
