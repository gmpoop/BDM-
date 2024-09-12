

DROP TABLE IF EXISTS Carrito; 

CREATE TABLE Carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,    -- Columna de ID con incremento automático
    id_User INT NOT NULL,    -- Columna de ID con incremento automático
    nombre VARCHAR(255) NOT NULL,         -- Columna de nombre con longitud máxima de 255 caracteres
    categoria VARCHAR(100) NOT NULL,      -- Columna de categoría con longitud máxima de 100 caracteres
    nivel INT NOT NULL,                   -- Columna de nivel de tipo entero
    precio DECIMAL(10, 2) NULL        -- Columna de precio con 10 dígitos en total y 2 decimales
);


