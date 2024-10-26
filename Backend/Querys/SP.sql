-- SP insertar, eliminar y update

-- Cambiar delimitador temporalmente
DELIMITER $$

-- Insertar un rol
CREATE PROCEDURE sp_insertar_rol (
    IN p_nombre VARCHAR(255)
)
BEGIN
    INSERT INTO roles (nombre) VALUES (p_nombre);
END$$

-- Borrar un rol
CREATE PROCEDURE sp_borrar_rol (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM roles WHERE id = p_id;
END$$

-- Modificar un rol
CREATE PROCEDURE sp_modificar_rol (
    IN p_id BIGINT,
    IN p_nombre VARCHAR(255)
)
BEGIN
    UPDATE roles SET nombre = p_nombre WHERE id = p_id;
END$$

-- Insertar un usuario
CREATE PROCEDURE sp_insertar_usuario (
    IN p_nombre_completo VARCHAR(255),
    IN p_genero VARCHAR(50),
    IN p_fecha_nacimiento DATE,
    IN p_foto BLOB,
    IN p_ruta_foto VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_contraseña TEXT,
    IN p_rol_id BIGINT
)
BEGIN
    INSERT INTO usuarios (nombre_completo, genero, fecha_nacimiento, foto, ruta_foto, email, contraseña, rol_id)
    VALUES (p_nombre_completo, p_genero, p_fecha_nacimiento, p_foto, p_ruta_foto, p_email, p_contraseña, p_rol_id);
END$$

-- Borrar un usuario
CREATE PROCEDURE sp_borrar_usuario (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM usuarios WHERE id = p_id;
END$$

-- Modificar un usuario
CREATE PROCEDURE sp_modificar_usuario (
    IN p_id BIGINT,
    IN p_nombre_completo VARCHAR(255),
    IN p_genero VARCHAR(50),
    IN p_fecha_nacimiento DATE,
    IN p_foto BLOB,
    IN p_ruta_foto VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_contraseña TEXT,
    IN p_rol_id BIGINT
)
BEGIN
    UPDATE usuarios
    SET nombre_completo = p_nombre_completo, genero = p_genero, fecha_nacimiento = p_fecha_nacimiento,
        foto = p_foto, ruta_foto = p_ruta_foto, email = p_email, contraseña = p_contraseña, rol_id = p_rol_id
    WHERE id = p_id;
END$$


-- Insertar una categoría
CREATE PROCEDURE sp_insertar_categoria (
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_usuario_creador_id BIGINT
)
BEGIN
    INSERT INTO categorias (nombre, descripcion, usuario_creador_id)
    VALUES (p_nombre, p_descripcion, p_usuario_creador_id);
END$$

-- Borrar una categoría
CREATE PROCEDURE sp_borrar_categoria (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM categorias WHERE id = p_id;
END$$

-- Modificar una categoría
CREATE PROCEDURE sp_modificar_categoria (
    IN p_id BIGINT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_usuario_creador_id BIGINT
)
BEGIN
    UPDATE categorias
    SET nombre = p_nombre, descripcion = p_descripcion, usuario_creador_id = p_usuario_creador_id
    WHERE id = p_id;
END$$

-- Insertar un curso
CREATE PROCEDURE sp_insertar_curso (
    IN p_titulo VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_imagen BLOB,
    IN p_ruta_imagen VARCHAR(255),
    IN p_costo DECIMAL(10, 2),
    IN p_categoria_id BIGINT,
    IN p_instructor_id BIGINT
)
BEGIN
    INSERT INTO cursos (titulo, descripcion, imagen, ruta_imagen, costo, categoria_id, instructor_id)
    VALUES (p_titulo, p_descripcion, p_imagen, p_ruta_imagen, p_costo, p_categoria_id, p_instructor_id);
END$$

-- Borrar un curso
CREATE PROCEDURE sp_borrar_curso (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM cursos WHERE id = p_id;
END$$

-- Modificar un curso
CREATE PROCEDURE sp_modificar_curso (
    IN p_id BIGINT,
    IN p_titulo VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_imagen BLOB,
    IN p_ruta_imagen VARCHAR(255),
    IN p_costo DECIMAL(10, 2),
    IN p_categoria_id BIGINT,
    IN p_instructor_id BIGINT
)
BEGIN
    UPDATE cursos
    SET titulo = p_titulo, descripcion = p_descripcion, imagen = p_imagen, ruta_imagen = p_ruta_imagen,
        costo = p_costo, categoria_id = p_categoria_id, instructor_id = p_instructor_id
    WHERE id = p_id;
END$$


-- Insertar un nivel
CREATE PROCEDURE sp_insertar_nivel (
    IN p_curso_id BIGINT,
    IN p_titulo VARCHAR(255),
    IN p_contenido TEXT,
    IN p_video TEXT
)
BEGIN
    INSERT INTO niveles (curso_id, titulo, contenido, video)
    VALUES (p_curso_id, p_titulo, p_contenido, p_video);
END$$

-- Borrar un nivel
CREATE PROCEDURE sp_borrar_nivel (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM niveles WHERE id = p_id;
END$$

-- Modificar un nivel
CREATE PROCEDURE sp_modificar_nivel (
    IN p_id BIGINT,
    IN p_titulo VARCHAR(255),
    IN p_contenido TEXT,
    IN p_video TEXT
)
BEGIN
    UPDATE niveles
    SET titulo = p_titulo, contenido = p_contenido, video = p_video
    WHERE id = p_id;
END$$


-- Insertar una inscripción
CREATE PROCEDURE sp_insertar_inscripcion (
    IN p_usuario_id BIGINT,
    IN p_curso_id BIGINT
)
BEGIN
    INSERT INTO inscripciones (usuario_id, curso_id)
    VALUES (p_usuario_id, p_curso_id);
END$$

-- Borrar una inscripción
CREATE PROCEDURE sp_borrar_inscripcion (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM inscripciones WHERE id = p_id;
END$$

-- Modificar una inscripción
CREATE PROCEDURE sp_modificar_inscripcion (
    IN p_id BIGINT,
    IN p_progreso DECIMAL(5, 2),
    IN p_fecha_terminacion TIMESTAMP
)
BEGIN
    UPDATE inscripciones
    SET progreso = p_progreso, fecha_terminacion = p_fecha_terminacion
    WHERE id = p_id;
END$$

-- Insertar un comentario
CREATE PROCEDURE sp_insertar_comentario (
    IN p_curso_id BIGINT,
    IN p_usuario_id BIGINT,
    IN p_comentario TEXT,
    IN p_calificacion INT
)
BEGIN
    INSERT INTO comentarios (curso_id, usuario_id, comentario, calificacion)
    VALUES (p_curso_id, p_usuario_id, p_comentario, p_calificacion);
END$$

-- Borrar un comentario
CREATE PROCEDURE sp_borrar_comentario (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM comentarios WHERE id = p_id;
END$$

-- Modificar un comentario
CREATE PROCEDURE sp_modificar_comentario (
    IN p_id BIGINT,
    IN p_comentario TEXT,
    IN p_calificacion INT
)
BEGIN
    UPDATE comentarios
    SET comentario = p_comentario, calificacion = p_calificacion
    WHERE id = p_id;
END$$

-- Insertar un mensaje
CREATE PROCEDURE sp_insertar_mensaje (
    IN p_remitente_id BIGINT,
    IN p_destinatario_id BIGINT,
    IN p_mensaje TEXT
)
BEGIN
    INSERT INTO mensajes (remitente_id, destinatario_id, mensaje)
    VALUES (p_remitente_id, p_destinatario_id, p_mensaje);
END$$

-- Borrar un mensaje
CREATE PROCEDURE sp_borrar_mensaje (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM mensajes WHERE id = p_id;
END$$

-- Modificar un mensaje
CREATE PROCEDURE sp_modificar_mensaje (
    IN p_id BIGINT,
    IN p_mensaje TEXT
)
BEGIN
    UPDATE mensajes
    SET mensaje = p_mensaje
    WHERE id = p_id;
END$$

-- Insertar una venta
CREATE PROCEDURE sp_insertar_venta (
    IN p_curso_id BIGINT,
    IN p_usuario_id BIGINT,
    IN p_forma_pago VARCHAR(50),
    IN p_ingreso DECIMAL(10, 2)
)
BEGIN
    INSERT INTO ventas (curso_id, usuario_id, forma_pago, ingreso)
    VALUES (p_curso_id, p_usuario_id, p_forma_pago, p_ingreso);
END$$

-- Borrar una venta
CREATE PROCEDURE sp_borrar_venta (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM ventas WHERE id = p_id;
END$$

-- Modificar una venta
CREATE PROCEDURE sp_modificar_venta (
    IN p_id BIGINT,
    IN p_forma_pago VARCHAR(50),
    IN p_ingreso DECIMAL(10, 2)
)
BEGIN
    UPDATE ventas
    SET forma_pago = p_forma_pago, ingreso = p_ingreso
    WHERE id = p_id;
END$$
-- Insertar un certificado
CREATE PROCEDURE sp_insertar_certificado (
    IN p_curso_id BIGINT,
    IN p_usuario_id BIGINT,
    IN p_fecha_terminacion TIMESTAMP,
    IN p_nombre_curso VARCHAR(255),
    IN p_nombre_instructor VARCHAR(255)
)
BEGIN
    INSERT INTO certificados (curso_id, usuario_id, fecha_terminacion, nombre_curso, nombre_instructor)
    VALUES (p_curso_id, p_usuario_id, p_fecha_terminacion, p_nombre_curso, p_nombre_instructor);
END$$

-- Borrar un certificado
CREATE PROCEDURE sp_borrar_certificado (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM certificados WHERE id = p_id;
END$$

-- Modificar un certificado
CREATE PROCEDURE sp_modificar_certificado (
    IN p_id BIGINT,
    IN p_nombre_curso VARCHAR(255),
    IN p_nombre_instructor VARCHAR(255),
    IN p_fecha_terminacion TIMESTAMP
)
BEGIN
    UPDATE certificados
    SET nombre_curso = p_nombre_curso, nombre_instructor = p_nombre_instructor, fecha_terminacion = p_fecha_terminacion
    WHERE id = p_id;
END$$

-- Insertar un reporte de usuario
CREATE PROCEDURE sp_insertar_reporte_usuario (
    IN p_usuario_id BIGINT,
    IN p_tipo_usuario VARCHAR(50),
    IN p_nombre VARCHAR(255),
    IN p_fecha_ingreso TIMESTAMP,
    IN p_cantidad_cursos_ofrecidos INT,
    IN p_total_ganancias DECIMAL(10, 2),
    IN p_cantidad_cursos_inscritos INT,
    IN p_porcentaje_cursos_terminados DECIMAL(5, 2)
)
BEGIN
    INSERT INTO reporte_usuarios (usuario_id, tipo_usuario, nombre, fecha_ingreso, cantidad_cursos_ofrecidos, total_ganancias, cantidad_cursos_inscritos, porcentaje_cursos_terminados)
    VALUES (p_usuario_id, p_tipo_usuario, p_nombre, p_fecha_ingreso, p_cantidad_cursos_ofrecidos, p_total_ganancias, p_cantidad_cursos_inscritos, p_porcentaje_cursos_terminados);
END$$

-- Borrar un reporte de usuario
CREATE PROCEDURE sp_borrar_reporte_usuario (
    IN p_id BIGINT
)
BEGIN
    DELETE FROM reporte_usuarios WHERE id = p_id;
END$$
-- Modificar un reporte de usuario
CREATE PROCEDURE sp_modificar_reporte_usuario (
    IN p_id BIGINT,
    IN p_tipo_usuario VARCHAR(50),
    IN p_nombre VARCHAR(255),
    IN p_cantidad_cursos_ofrecidos INT,
    IN p_total_ganancias DECIMAL(10, 2),
    IN p_cantidad_cursos_inscritos INT,
    IN p_porcentaje_cursos_terminados DECIMAL(5, 2)
)
BEGIN
    UPDATE reporte_usuarios
    SET tipo_usuario = p_tipo_usuario, nombre = p_nombre, cantidad_cursos_ofrecidos = p_cantidad_cursos_ofrecidos,
        total_ganancias = p_total_ganancias, cantidad_cursos_inscritos = p_cantidad_cursos_inscritos, porcentaje_cursos_terminados = p_porcentaje_cursos_terminados
    WHERE id = p_id;
END$$

DELIMITER ;