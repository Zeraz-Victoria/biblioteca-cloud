-- Insertar Autores (Evitando duplicados si ya existen)
INSERT INTO AUTOR (Nombre_completo) VALUES 
('Ludmila Zeman'), ('Kenna Bourke'), ('Simon Beaver'), ('Genevieve Kocienda'), 
('Kathryn O''Dell'), ('David Maule'), ('Karen Holmes'), ('Diane Naughton'), 
('Gobierno del estado de Veracruz'), ('Jorge Novelo'), ('Aurelio Nuño Mayer'), 
('Secretaría de educación Pública'), ('David Riveros Rosas'), ('David Solá'), 
('Peter Allen'), ('Grassa Toro Pep Carrió'), ('Denis Guedj'), ('Julia Constenta'), 
('Caroline Shackleton Nathan Paul Turner'), ('Brian Sargent'), ('Juana Karen'), 
('A. Oparin'), ('John Landon'), ('Alejandro Dumas'), ('Anonimo'), ('Victor Hugo'), 
('Varios'), ('Enriqueta Lunez'), ('Mikeas Sanchez'), ('Pablo Neruda'), 
('Alfredo Castañeda'), ('Emilio Carballido'), ('Lope de Rueda Cervantes Calderon'), 
('Hector Campillo Cuautli'), ('Sally Marshall'), ('Jesus Avila'), ('Polter, Lawrence'), 
('Agustin Sanchez Aguilar'), ('Enrique Rajchenberg'), ('Ale del castillo y Moisés Castillo'), 
('Oswaldo Franca Jr.')
ON CONFLICT (Nombre_completo) DO NOTHING;

-- Insertar Libros de las imágenes
-- Nota: Asignamos categorías genéricas (ID 1 = Novela, etc.) ya que no vienen en la imagen.
-- Se asume que las categorías 1-8 ya existen por el seed anterior.

INSERT INTO LIBRO (Titulo_libro, Id_autor, Color, Total_ejemplares, Ejemplares_disponibles, Descripcion, Id_categoria) VALUES
-- Imagen 1
('Gilgamesh', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Ludmila Zeman'), 'Azul', 4, 4, 'Epopeya antigua sobre el rey de Uruk.', 1),
('Deadly Animals', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Kenna Bourke'), 'Blanco', 1, 1, 'Libro informativo sobre animales peligrosos.', 2),
('Saved! Heroes in Everyday Life', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Simon Beaver'), 'Blanco', 1, 1, 'Historias de héroes cotidianos.', 5),
('What Are the Odds?', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Genevieve Kocienda'), 'Blanco', 1, 1, 'Curiosidades y probabilidades.', 7),
('Jeff Corwin Wild Man', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Kenna Bourke'), 'Blanco', 1, 1, 'Aventuras de Jeff Corwin.', 5),
('Water Vital for Life', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Genevieve Kocienda'), 'Blanco', 1, 1, 'La importancia del agua para la vida.', 2),
('Survival Guide', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Kathryn O''Dell'), 'Blanco', 1, 1, 'Guía de supervivencia.', 8),
('Cool Jobs', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='David Maule'), 'Blanco', 1, 1, 'Trabajos interesantes y poco comunes.', 8),
('Drink Up!', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Karen Holmes'), 'Blanco', 1, 1, 'Información sobre bebidas y salud.', 8),
('Our Green Future', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Diane Naughton'), 'Blanco', 1, 1, 'Ecología y futuro sostenible.', 2),
('Niños y niñas por un México sin obesidad', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Gobierno del estado de Veracruz'), 'Blanco', 1, 1, 'Campaña de salud infantil.', 8),
('Al otro lado de la puerta', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Jorge Novelo'), 'Blanco', 1, 1, 'Narrativa contemporánea.', 1),
('Aprendizajes clave para la educación integral', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Aurelio Nuño Mayer'), 'Blanco', 2, 2, 'Modelo educativo.', 8),
('El libro de la Economía', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Secretaría de educación Pública'), 'Morado', 1, 1, 'Conceptos básicos de economía.', 8),
('La Radiación Solar', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='David Riveros Rosas'), 'Morado', 1, 1, 'Estudio sobre la radiación solar.', 2),
('Haciendo Fácil lo Difícil', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='David Solá'), 'Morado', 1, 1, 'Guía para simplificar tareas.', 8),
('Las Plantas', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Peter Allen'), 'Morado', 1, 1, 'Botánica básica.', 2),

-- Imagen 3
('Conquistadores en el nuevo mundo', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Grassa Toro Pep Carrió'), 'Azul', 1, 1, 'Historia de la conquista.', 6),
('El imperio de los números', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Denis Guedj'), 'Rosa', 1, 1, 'Historia de las matemáticas.', 2),
('Album del Chem', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Julia Constenta'), 'Azul', 1, 1, 'Álbum ilustrado.', 1),
('DEEP BLUE DISCOVERING THE SEA', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Caroline Shackleton Nathan Paul Turner'), 'Blanco', 1, 1, 'Exploración marina.', 2),
('FOUND Discovery And Recovery', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Brian Sargent'), 'Blanco', 1, 1, 'Descubrimientos y recuperaciones.', 2),
('ALIENS IS ANYBODY OUT THERE?', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Genevieve Kocienda'), 'Blanco', 1, 1, 'Vida extraterrestre.', 2),
('IPUSIK AL MATYE LUM Corazon de selva', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Juana Karen'), 'Cafe', 1, 1, 'Relato de la selva.', 1),
('El origen de la vida', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='A. Oparin'), 'Cafe', 1, 1, 'Teoría científica sobre el origen de la vida.', 2),
('Claws', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='John Landon'), 'Blanco', 1, 1, 'Novela de suspenso.', 1),

-- Imagen 4
('El Conde de Montecristo', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Alejandro Dumas'), 'Azul', 1, 1, 'Clásico de la literatura de aventuras.', 1),
('Cuentos de las Mil y Una Noches', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Anonimo'), 'Azul', 1, 1, 'Recopilación de cuentos orientales.', 1),
('Los Miserables', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Victor Hugo'), 'Azul', 1, 1, 'Novela histórica y social.', 1),
('Antología Poética de la Generación del 27', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Varios'), 'Coral', 1, 1, 'Poesía española del siglo XX.', 1),
('Juego de Nahuales', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Enriqueta Lunez'), 'Coral', 1, 1, 'Literatura indígena.', 1),
('Desde mi Medula', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Mikeas Sanchez'), 'Coral', 1, 1, 'Poesía contemporánea.', 1),
('Circo Poetico', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Varios'), 'Coral', 1, 1, 'Antología de poesía.', 1),
('Antología de Poesía Universal', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Anonimo'), 'Coral', 1, 1, 'Selección de poemas del mundo.', 1),
('Poesía de Amor Antología', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Anonimo'), 'Coral', 1, 1, 'Poemas románticos.', 1),
('Veinte Poemas de Amor y una Canción Desesperada', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Pablo Neruda'), 'Coral', 1, 1, 'Obra cumbre de Neruda.', 1),
('Antología de Poesía Mexicana', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Anonimo'), 'Coral', 1, 1, 'Poesía de México.', 1),
('Versos de Amor', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Anonimo'), 'Coral', 1, 1, 'Versos románticos.', 1),
('Libro de Horas', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Alfredo Castañeda'), 'Coral', 1, 1, 'Reflexiones y poesía.', 1),
('Teatro Joven de México', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Emilio Carballido'), 'Rojo', 1, 1, 'Obras de teatro juvenil.', 1),
('Pasos y Entremeses', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Lope de Rueda Cervantes Calderon'), 'Rojo', 1, 1, 'Teatro clásico español.', 1),

-- Imagen 5
('Diccionario Academia de la lengua española', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Hector Campillo Cuautli'), '-', 2, 2, 'Diccionario de referencia.', 8),
('Beats 1', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Sally Marshall'), '-', 1, 1, 'Libro de música o inglés.', 8),
('Beats 3', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Sally Marshall'), '-', 1, 1, 'Libro de música o inglés nivel 3.', 8),
('Diario de la Revolución', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Jesus Avila'), 'Café', 1, 1, 'Crónicas revolucionarias.', 6),
('La historia de la ciencia: Un relato Ilustrado', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Secretaría de educación Pública'), 'Verde', 1, 1, 'Historia de los descubrimientos científicos.', 2),
('Arma de la historia', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Secretaría de educación Pública'), 'Café', 1, 1, 'Libro de historia.', 6),
('A jugar con las matematicas', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Polter, Lawrence'), 'Rosado', 1, 1, 'Matemáticas lúdicas.', 2),
('Viaje al futuro: Relatos de la ciencia ficción', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Agustin Sanchez Aguilar'), 'Azul', 1, 1, 'Cuentos de ciencia ficción.', 2),
('De recursos, bosques y animales', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Aurelio Nuño Mayer'), 'Morado', 1, 1, 'Ecología y recursos naturales.', 2),
('Hablemos de los años 60: La Rebeldia', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Enrique Rajchenberg'), 'Café', 1, 1, 'Historia social de los 60.', 6),
('Los Nadie', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Ale del castillo y Moisés Castillo'), 'Azul', 1, 1, 'Crónica periodística.', 1),
('Las naranjas iguales', (SELECT Id_autor FROM AUTOR WHERE Nombre_completo='Oswaldo Franca Jr.'), 'Azul', 1, 1, 'Narrativa.', 1);
