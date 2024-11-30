DELIMITER $$

CREATE TRIGGER after_usuario_insert
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO reporte_usuarios (
        usuario_id,
        tipo_usuario,
        nombre,
        fecha_ingreso,
        cantidad_cursos_ofrecidos,
        total_ganancias,
        cantidad_cursos_inscritos,
        porcentaje_cursos_terminados,
    )
    VALUES (
        NEW.id,
        (SELECT nombre FROM roles WHERE id = NEW.rol_id), -- Determinar el rol
        NEW.nombre_completo,
        NOW(),
        0, -- Sin cursos ofrecidos inicialmente
        0.00, -- Sin ganancias inicialmente
        0, -- Sin cursos inscritos inicialmente
        0.00, -- Sin porcentaje de cursos completados
    );
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER after_curso_insert
AFTER INSERT ON cursos
FOR EACH ROW
BEGIN
    -- Verificar si el instructor del curso tiene el rol de "Tutor"
    IF (SELECT nombre FROM roles WHERE id = (SELECT rol_id FROM usuarios WHERE id = NEW.instructor_id)) = 'Tutor' THEN
        UPDATE reporte_usuarios
        SET cantidad_cursos_ofrecidos = cantidad_cursos_ofrecidos + 1
        WHERE usuario_id = NEW.instructor_id;
    END IF;
END$$

DELIMITER ;

DELIMITER //

CREATE TRIGGER after_venta_insert
AFTER INSERT ON ventas
FOR EACH ROW
BEGIN
    -- Insertar un nuevo registro en la tabla inscripciones
    INSERT INTO inscripciones (usuario_id, curso_id, progreso, fecha_terminacion)
    VALUES (NEW.comprador_id, NEW.curso_id, 0.0, NULL);
END;

//

DELIMITER ;

DELIMITER //

CREATE TRIGGER after_venta_update_reporte
AFTER INSERT ON ventas
FOR EACH ROW
BEGIN
    -- Actualizar el reporte del usuario que vendió
    UPDATE reporte_usuarios
    SET total_ganancias = total_ganancias + NEW.ingreso
    WHERE usuario_id = NEW.usuario_id;

    -- Actualizar el reporte del usuario que compró
    UPDATE reporte_usuarios
    SET cantidad_cursos_inscritos = cantidad_cursos_inscritos + 1
    WHERE usuario_id = NEW.comprador_id;
END;

//

DELIMITER ;

DELIMITER $$

CREATE TRIGGER crear_chat_curso
AFTER INSERT ON ventas
FOR EACH ROW
BEGIN
    -- Insertar un nuevo chat con el curso_id de la venta
    INSERT INTO chats (curso_perteneciente)
    VALUES (NEW.curso_id);
END $$

DELIMITER ;
