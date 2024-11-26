-- Crear tablas de la base de datos

-- Tabla de roles
CREATE TABLE roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL
);

-- Tabla de usuarios
CREATE TABLE usuarios (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre_completo VARCHAR(255) NOT NULL,
    genero VARCHAR(50),
    fecha_nacimiento DATE,
    foto BLOB, -- Cambio a BLOB para almacenar la imagen
    ruta_foto VARCHAR(255), -- Nueva columna para la ruta de la imagen
    email VARCHAR(255) UNIQUE NOT NULL,
    contraseña TEXT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    intentos_fallidos INT DEFAULT 0,
    estado VARCHAR(50) DEFAULT 'activo',
    rol_id BIGINT REFERENCES roles(id)
);

-- Tabla de categorías
CREATE TABLE categorias (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    usuario_creador_id BIGINT REFERENCES usuarios(id),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de cursos
CREATE TABLE cursos (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen BLOB, -- Cambio a BLOB para almacenar la imagen
    ruta_imagen VARCHAR(255), -- Nueva columna para la ruta de la imagen
    costo DECIMAL(10, 2),
    estado VARCHAR(50) DEFAULT 'activo',
    categoria_id BIGINT REFERENCES categorias(id),
    instructor_id BIGINT REFERENCES usuarios(id)
);

-- Tabla de niveles
CREATE TABLE niveles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    curso_id BIGINT REFERENCES cursos(id),
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT,
    video TEXT
);

-- Tabla de inscripciones
CREATE TABLE inscripciones (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    usuario_id BIGINT REFERENCES usuarios(id),
    curso_id BIGINT REFERENCES cursos(id),
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    progreso DECIMAL(5, 2) DEFAULT 0.0,
    fecha_terminacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de comentarios
CREATE TABLE comentarios (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    curso_id BIGINT REFERENCES cursos(id),
    usuario_id BIGINT REFERENCES usuarios(id),
    comentario TEXT,
    calificacion INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_eliminacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    causa_eliminacion TEXT
);

-- Tabla de mensajes
CREATE TABLE mensajes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    remitente_id BIGINT REFERENCES usuarios(id),
    destinatario_id BIGINT REFERENCES usuarios(id),
    mensaje TEXT,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de ventas
CREATE TABLE ventas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    curso_id BIGINT REFERENCES cursos(id),
    usuario_id BIGINT REFERENCES usuarios(id),
    fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    forma_pago VARCHAR(50),
    ingreso DECIMAL(10, 2)
);

-- Tabla de certificados
CREATE TABLE certificados (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    curso_id BIGINT REFERENCES cursos(id),
    usuario_id BIGINT REFERENCES usuarios(id),
    fecha_terminacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nombre_curso VARCHAR(255),
    nombre_instructor VARCHAR(255)
);

-- Tabla de reporte de usuarios
CREATE TABLE reporte_usuarios (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    usuario_id BIGINT REFERENCES usuarios(id),
    tipo_usuario VARCHAR(50) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cantidad_cursos_ofrecidos INT,
    total_ganancias DECIMAL(10, 2),
    cantidad_cursos_inscritos INT,
    porcentaje_cursos_terminados DECIMAL(5, 2)
);

