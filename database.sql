
-- EasyFly database schema
CREATE DATABASE IF NOT EXISTS easyfly DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE easyfly;

-- Users
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  email VARCHAR(120) UNIQUE,
  password VARCHAR(255),
  is_admin TINYINT DEFAULT 0
);

-- Flights
CREATE TABLE vuelos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pais_destino VARCHAR(80),
  aerolineas VARCHAR(255),
  capacidad INT,
  plazas_disponibles INT,
  precio DECIMAL(10,2) DEFAULT 100.00
);

-- Reservations
CREATE TABLE reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  vuelo_id INT,
  codigo_reserva VARCHAR(20),
  fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (vuelo_id) REFERENCES vuelos(id) ON DELETE CASCADE
);

-- Sample admin user (password: admin123)
INSERT INTO usuarios (nombre,email,password,is_admin) VALUES
('Administrador','admin@easyfly.local', '$2b$12$olYOUA7WLZmAPdeV/kQbuuKYDQ5wHW/j3Qk/5iJzTUDTL04JNo02O', 1);

-- Flights data
INSERT INTO vuelos (pais_destino, aerolineas, capacidad, plazas_disponibles, precio) VALUES
('Argentina','Iberia,American Airlines,Delta Airlines',180,180,1000),
('Brasil','Iberia,American Airlines,Delta Airlines',175,175,950),
('Francia','Iberia,American Airlines,Delta Airlines',190,190,300),
('Alemania','Iberia,American Airlines,Delta Airlines',185,185,320),
('Italia','Iberia,American Airlines,Delta Airlines',175,175,280),
('Japón','American Airlines,Delta Airlines',200,200,1200),
('México','Iberia,American Airlines,Delta Airlines',180,180,800),
('España','Iberia,American Airlines,Delta Airlines',175,175,150),
('Reino Unido','Iberia,American Airlines,Delta Airlines',190,190,350),
('Estados Unidos','Iberia,American Airlines,Delta Airlines',165,165,700);
