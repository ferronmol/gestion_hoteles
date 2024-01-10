CREATE DATABASE hoteles;

USE hoteles;

CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  contraseña VARCHAR(255) NOT NULL,
  fecha_registro DATETIME NOT NULL,
  rol TINYINT NOT NULL 
);

CREATE TABLE hoteles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  direccion VARCHAR(255) NOT NULL,
  ciudad VARCHAR(255) NOT NULL,
  pais VARCHAR(255) NOT NULL,
  num_habitaciones INT NOT NULL,
  descripcion TEXT NOT NULL,
  foto MEDIUMBLOB
);

CREATE TABLE habitaciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_hotel INT NOT NULL,
  num_habitacion INT NOT NULL,
  tipo VARCHAR(255) NOT NULL,
  precio FLOAT NOT NULL,
  descripcion TEXT NOT NULL,
  FOREIGN KEY (id_hotel) REFERENCES hoteles(id)
);

CREATE TABLE reservas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  id_hotel INT NOT NULL,
  id_habitacion INT NOT NULL,
  fecha_entrada DATE NOT NULL,
  fecha_salida DATE NOT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
  FOREIGN KEY (id_hotel) REFERENCES hoteles(id),
  FOREIGN KEY (id_habitacion) REFERENCES habitaciones(id)
);

INSERT INTO usuarios (nombre, contraseña, fecha_registro, rol) VALUES
  ('juan', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', '2022-01-01', 1), -- contraseña original: 'password', rol: administrador
  ('maria', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2022-01-02', 0); -- contraseña original: '123456', rol: usuario normal

INSERT INTO hoteles (nombre, direccion, ciudad, pais, num_habitaciones, descripcion, foto) VALUES
  ('Hotel 1', 'Calle 1', 'Madrid', 'España', 10, 'Este es un hotel de lujo en el centro de Madrid', NULL),
  ('Hotel 2', 'Calle 2', 'Barcelona', 'España', 20, 'Este es un hotel con piscina en la playa de Barcelona', NULL);

INSERT INTO habitaciones (id_hotel, num_habitacion, tipo, precio, descripcion) VALUES
  (1, 1, 'individual', 50, 'Habitación individual con vistas a la ciudad'),
  (1, 2, 'doble', 75, 'Habitación doble con vistas a la montaña'),
  (2, 1, 'suite', 100, 'Suite con terraza y vistas al mar');

INSERT INTO reservas (id_usuario, id_hotel, id_habitacion, fecha_entrada, fecha_salida) VALUES
  (1, 1, 1, '2022-01-10', '2022-01-15'),
  (1, 2, 3, '2022-01-20', '2022-01-25'),
  (2, 1, 2, '2022-02-01', '2022-02-05');
