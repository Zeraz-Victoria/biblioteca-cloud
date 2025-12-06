-- Insertar Escuela Demo
INSERT INTO ESCUELAS (nombre_escuela, email_contacto) VALUES ('Escuela Demo', 'admin@escuelademo.com');

-- Insertar Usuario Admin (Vinculado a Escuela 1)
INSERT INTO USUARIOS (id_escuela, Nombre_usuario, Correo, Contrasena, Tipo_usuario) 
VALUES (1, 'Admin Demo', 'admin@test.com', '12345', 'admin');

-- Insertar Super Admin (Global) - Vinculado a Escuela 1 por restricción FK, pero con rol especial
INSERT INTO USUARIOS (id_escuela, Nombre_usuario, Correo, Contrasena, Tipo_usuario) 
VALUES (1, 'Super Admin', 'super@admin.com', 'root', 'superadmin');

-- Categorías
INSERT INTO CATEGORIA (id_escuela, Nombre_categoria) VALUES 
(1, 'Novela'), (1, 'Ciencia Ficción'), (1, 'Historia'), (1, 'Tecnología');

-- Autores
INSERT INTO AUTOR (id_escuela, Nombre_autor) VALUES 
(1, 'Gabriel García Márquez'), (1, 'Isaac Asimov'), (1, 'J.K. Rowling');

-- Editoriales
INSERT INTO EDITORIAL (id_escuela, Nombre_editorial) VALUES 
(1, 'Editorial Planeta'), (1, 'Penguin Random House');

-- Libros
INSERT INTO LIBRO (id_escuela, Titulo_libro, Id_autor, Id_editorial, Anio_publicacion, Id_categoria, Ejemplares_totales, Ejemplares_disponibles, Ubicacion) VALUES
(1, 'Cien años de soledad', 1, 1, 1967, 1, 5, 5, 'Estante A'),
(1, 'Yo, Robot', 2, 2, 1950, 2, 3, 3, 'Estante B'),
(1, 'Harry Potter y la Piedra Filosofal', 3, 1, 1997, 1, 10, 10, 'Estante C');

-- Socios
INSERT INTO SOCIO (id_escuela, DNI, nfc_id, Nombre_socio, Email, Telefono, Direccion, Fecha_registro, Estado) VALUES
(1, '11111111', '12345', 'Juan Pérez', 'juan@mail.com', '5551234', 'Calle Falsa 123', '2023-01-01', TRUE),
(1, '22222222', '67890', 'Maria Lopez', 'maria@mail.com', '5555678', 'Av. Siempre Viva 742', '2023-02-01', TRUE);

-- Computadoras
INSERT INTO COMPUTADORA (id_escuela, nombre, marca, estado) VALUES
(1, 'PC-01', 'Dell', TRUE),
(1, 'PC-02', 'HP', TRUE),
(1, 'PC-03', 'Lenovo', TRUE);
