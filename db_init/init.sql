-- Tabla de Escuelas (Tenants)
CREATE TABLE ESCUELAS (
    id_escuela SERIAL PRIMARY KEY,
    nombre_escuela VARCHAR(255) NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    email_contacto VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- Tablas del Sistema (Ahora con id_escuela)

CREATE TABLE CATEGORIA (
    Id_categoria SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    Nombre_categoria VARCHAR(100) NOT NULL,
    CONSTRAINT fk_cat_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela)
);

CREATE TABLE AUTOR (
    Id_autor SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    Nombre_autor VARCHAR(100) NOT NULL,
    CONSTRAINT fk_autor_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela)
);

CREATE TABLE EDITORIAL (
    Id_editorial SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    Nombre_editorial VARCHAR(100) NOT NULL,
    CONSTRAINT fk_edit_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela)
);

CREATE TABLE LIBRO (
    Id_libro SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    Titulo_libro VARCHAR(255) NOT NULL,
    Id_autor INT,
    Id_editorial INT,
    Anio_publicacion INT,
    Id_categoria INT,
    Ejemplares_totales INT DEFAULT 1,
    Ejemplares_disponibles INT DEFAULT 1,
    Ubicacion VARCHAR(100),
    CONSTRAINT fk_libro_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela),
    CONSTRAINT fk_libro_autor FOREIGN KEY (Id_autor) REFERENCES AUTOR(Id_autor),
    CONSTRAINT fk_libro_editorial FOREIGN KEY (Id_editorial) REFERENCES EDITORIAL(Id_editorial),
    CONSTRAINT fk_libro_categoria FOREIGN KEY (Id_categoria) REFERENCES CATEGORIA(Id_categoria)
);

CREATE TABLE SOCIO (
    Id_socio SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    DNI VARCHAR(15),
    nfc_id VARCHAR(50), -- No UNIQUE globalmente, solo por escuela (se maneja en lógica o índice compuesto)
    Nombre_socio VARCHAR(255),
    Email VARCHAR(100),
    Telefono VARCHAR(10) NOT NULL,
    Direccion VARCHAR(255) NOT NULL,
    Fecha_registro DATE,
    Estado BOOLEAN DEFAULT TRUE,
    CONSTRAINT fk_socio_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela),
    UNIQUE(id_escuela, nfc_id) -- NFC único por escuela
);

CREATE TABLE USUARIOS (
    Id_usuario SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    Nombre_usuario VARCHAR(100) NOT NULL,
    Correo VARCHAR(100) NOT NULL, -- Login único global o por escuela? Mejor global para SaaS simple
    Contrasena VARCHAR(255) NOT NULL,
    Tipo_usuario VARCHAR(20) NOT NULL, -- 'admin', 'bibliotecario', 'superadmin'
    CONSTRAINT fk_user_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela),
    UNIQUE(Correo)
);

CREATE TABLE PRESTAMOS (
    Id_prestamo SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    Id_libro INT,
    Id_socio INT,
    Fecha_prestamo DATE,
    Fecha_devolucion_real DATE,
    Fecha_dev_esperada DATE,
    CONSTRAINT fk_prest_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela),
    CONSTRAINT fk_prestamo_libro FOREIGN KEY (Id_libro) REFERENCES LIBRO(Id_libro),
    CONSTRAINT fk_prestamo_socio FOREIGN KEY (Id_socio) REFERENCES SOCIO(Id_socio)
);

CREATE TABLE COMPUTADORA (
    id_computadora SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    marca VARCHAR(50),
    estado BOOLEAN DEFAULT TRUE,
    CONSTRAINT fk_pc_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela)
);

CREATE TABLE PRESTAMO_COMPUTADORA (
    id_prestamo_pc SERIAL PRIMARY KEY,
    id_escuela INT NOT NULL,
    id_computadora INT,
    id_socio INT,
    fecha_prestamo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion TIMESTAMP NULL,
    CONSTRAINT fk_ppc_escuela FOREIGN KEY (id_escuela) REFERENCES ESCUELAS(id_escuela),
    CONSTRAINT fk_pc FOREIGN KEY (id_computadora) REFERENCES COMPUTADORA(id_computadora),
    CONSTRAINT fk_socio_pc FOREIGN KEY (id_socio) REFERENCES SOCIO(Id_socio)
);