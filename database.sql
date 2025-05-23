-- EasyFly
DROP DATABASE IF EXISTS easyfly;
CREATE DATABASE easyfly DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE easyfly;

-- Usuarios ---------------------------------------------------------
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  email VARCHAR(120) UNIQUE,
  password VARCHAR(255),
  is_admin TINYINT DEFAULT 0
);

-- Vuelos -------------------------------------------------------
CREATE TABLE vuelos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pais_destino VARCHAR(80),
  aerolineas VARCHAR(255),
  capacidad INT,
  plazas_disponibles INT,
  precio DECIMAL(10,2) DEFAULT 100.00
);

-- Reservations (incluye fecha_vuelo) ----------------------------
CREATE TABLE reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  vuelo_id INT,
  codigo_reserva VARCHAR(20),
  fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  fecha_vuelo DATETIME NOT NULL,       -- día y hora elegidos por el usuario
  origen VARCHAR(255)             NULL,
  destino_detalle VARCHAR(255)    NULL,
  pasajero_tipo VARCHAR(50)       NULL,
  equipaje VARCHAR(50)            NULL,
  clase VARCHAR(50)               NULL,
  mascota VARCHAR(50)             NULL,
  precio_final DECIMAL(10,2)      NULL,

  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (vuelo_id)  REFERENCES vuelos(id)    ON DELETE CASCADE
);

-- Usuarios (contraseñas: admin123, 1234)
INSERT INTO usuarios (nombre,email,password,is_admin) VALUES
('Administrador', 'richi3fvg@gmail.com', '$2b$12$olYOUA7WLZmAPdeV/kQbuuKYDQ5wHW/j3Qk/5iJzTUDTL04JNo02O', 1),
('Richard', 'the4lpha0ne@gmail.com', '$2y$10$PE/sbSL83uZzNKI2RhUgtuUZ5.IJQL9V8QnpvgW6xJ5XfECEStfKa', 0);

-- Datos de los Vuelos --------------------------------------------------
INSERT INTO vuelos (pais_destino, aerolineas, capacidad, plazas_disponibles, precio) VALUES
('Argentina','Iberia,American Airlines,Delta Airlines',180,180,470),
('Brasil','Iberia,American Airlines,Delta Airlines',175,175,425),
('Francia','Iberia,American Airlines,Delta Airlines',190,190,50),
('Alemania','Iberia,American Airlines,Delta Airlines',185,185,60),
('Italia','Iberia,American Airlines,Delta Airlines',175,175,45),
('Japón','American Airlines,Delta Airlines',200,200,400),
('México','Iberia,American Airlines,Delta Airlines',180,180,230),
('España','Iberia,American Airlines,Delta Airlines',175,175,25),
('Reino Unido','Iberia,American Airlines,Delta Airlines',190,190,60),
('Estados Unidos','Iberia,American Airlines,Delta Airlines',165,165,190);
