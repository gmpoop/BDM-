DROP TABLE IF EXISTS Curso;
CREATE TABLE Curso (
    id INT AUTO_INCREMENT PRIMARY KEY,            -- Identificador único de cada curso
    nombre VARCHAR(255) NOT NULL,                 -- Nombre del curso
    categoria VARCHAR(100) NOT NULL,              -- Categoría del curso
    cantidad_niveles INT NOT NULL,                -- Cantidad de niveles en el curso
    creado_por VARCHAR(255) NOT NULL,             -- Nombre o identificador del creador del curso
    descripcion_corta VARCHAR(500),               -- Descripción corta del curso (opcional)
    descripcion_larga TEXT,                       -- Descripción larga del curso (puede ser más detallada)
    imagen_url VARCHAR(255),                      -- URL de la imagen (nombre del archivo y su ruta)
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha de creación del curso
);

DROP TABLE IF EXISTS Nivel;
CREATE TABLE Nivel (
    id INT AUTO_INCREMENT PRIMARY KEY,            -- Identificador único de cada curso
    nivel INT NOT NULL,
    idCurso INT NOT NULL,-- Nombre del curso
    descripcion_corta VARCHAR(500),               -- Descripción corta del curso (opcional)
    descripcion_larga TEXT,                       -- Descripción larga del curso (puede ser más detallada)
    video_url VARCHAR(255),                      -- URL de la imagen (nombre del archivo y su ruta)
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha de creación del curso
);








