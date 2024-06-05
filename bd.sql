CREATE DATABASE IF NOT EXISTS bd;
USE bd;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL
);

INSERT INTO usuarios (nombre, email, password, rol)
VALUES ('Admin', 'admin@example.com', '$2y$10$...', 'admin'),
       ('Pastor', 'pastor@example.com', '$2y$10$...', 'pastor'),
       ('Lider de 12', 'lider12@example.com', '$2y$10$...', 'lider_12'),
       ('Lider de 144', 'lider144@example.com', '$2y$10$...', 'lider_144'),
       ('Lider de 1728', 'lider1728@example.com', '$2y$10$...', 'lider_1728');

CREATE TABLE reuniones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    fecha DATETIME NOT NULL,
    lider_id INT NOT NULL,
    FOREIGN KEY (lider_id) REFERENCES usuarios(id)
);

CREATE TABLE asistencia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reunion_id INT NOT NULL,
    usuario_id INT NOT NULL,
    asistio BOOLEAN NOT NULL DEFAULT true,
    FOREIGN KEY (reunion_id) REFERENCES reuniones(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE discipulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    lider_id INT NOT NULL,
    FOREIGN KEY (lider_id) REFERENCES usuarios(id)
);

CREATE DATABASE IF NOT EXISTS bd;
USE bd;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL
);

