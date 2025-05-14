-- -----------------------------------------------------
--  Schema for flight_booking
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS flight_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE flight_booking;

-- ----------------------------
--  Table structure for users
-- ----------------------------
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------
--  Table structure for flights
-- ----------------------------
CREATE TABLE IF NOT EXISTS flights (
  id INT AUTO_INCREMENT PRIMARY KEY,
  origin VARCHAR(100) NOT NULL,
  destination VARCHAR(100) NOT NULL,
  flight_date DATETIME NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  seats INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------
--  Table structure for reservations
-- ----------------------------
CREATE TABLE IF NOT EXISTS reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  flight_id INT NOT NULL,
  reserved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------
--  Sample data
-- ----------------------------
INSERT INTO flights (origin, destination, flight_date, price, seats) VALUES
('Gran Canaria','Madrid','2025-06-15 08:00:00',120.00,5),
('Gran Canaria','Barcelona','2025-06-18 10:00:00',150.00,3);
