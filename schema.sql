-- Eliminar tablas si existen para evitar conflictos en reinicios
DROP TABLE IF EXISTS LIBRO;
DROP TABLE IF EXISTS AUTOR;
DROP TABLE IF EXISTS CATEGORIA;

-- Tabla de Autores
CREATE TABLE AUTOR (
    Id_autor SERIAL PRIMARY KEY,
    Nombre_completo VARCHAR(100) NOT NULL,
    Fecha_nac DATE,
    Nacionalidad VARCHAR(50)
);

-- Tabla de Categorías
CREATE TABLE CATEGORIA (
    Id_categoria SERIAL PRIMARY KEY,
    Nombre_categoria VARCHAR(50) NOT NULL,
    Descripcion TEXT
);

-- Tabla de Libros
CREATE TABLE LIBRO (
    Id_libro SERIAL PRIMARY KEY,
    Titulo_libro VARCHAR(150) NOT NULL,
    Id_autor INT,
    Id_categoria INT,
    Anio_publicacion INT,
    Total_ejemplares INT DEFAULT 0,
    Ejemplares_disponibles INT DEFAULT 0,
    CONSTRAINT fk_autor FOREIGN KEY (Id_autor) REFERENCES AUTOR(Id_autor) ON DELETE SET NULL,
    CONSTRAINT fk_categoria FOREIGN KEY (Id_categoria) REFERENCES CATEGORIA(Id_categoria) ON DELETE SET NULL
);

-- Insertar datos de prueba (Opcional)
INSERT INTO AUTOR (Nombre_completo, Fecha_nac, Nacionalidad) VALUES 
('Gabriel García Márquez', '1927-03-06', 'Colombiana'),
('J.K. Rowling', '1965-07-31', 'Británica'),
('George Orwell', '1903-06-25', 'Británica');

INSERT INTO CATEGORIA (Nombre_categoria, Descripcion) VALUES 
('Novela', 'Obras literarias narrativas'),
('Fantasía', 'Elementos sobrenaturales y mágicos'),
('Ciencia Ficción', 'Futuro, espacio, tecnología');

INSERT INTO LIBRO (Titulo_libro, Id_autor, Id_categoria, Anio_publicacion, Total_ejemplares, Ejemplares_disponibles) VALUES 
('Cien años de soledad', 1, 1, 1967, 5, 3),
('Harry Potter y la piedra filosofal', 2, 2, 1997, 10, 8),
('1984', 3, 3, 1949, 7, 0);
