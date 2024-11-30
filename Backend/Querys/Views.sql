DELIMITER $$

DROP VIEW IF EXISTS view_reporte_usuario_estudiantes;

CREATE VIEW view_reporte_usuario_estudiantes AS
SELECT 
    u.id AS usuario_id, -- Agregamos el ID del usuario
    u.email AS correo,
    u.nombre_completo,
    r.fecha_ingreso,
    r.cantidad_cursos_inscritos AS cursos_inscritos,
    r.porcentaje_cursos_terminados
FROM 
    usuarios u
JOIN 
    reporte_usuarios r ON u.id = r.usuario_id
WHERE 
    u.rol_id = 2;

DELIMITER ;

DELIMITER $$

DROP VIEW IF EXISTS view_reporte_usuario_tutores;

CREATE VIEW view_reporte_usuario_tutores AS
SELECT 
    u.id AS usuario_id, -- Agregamos el ID del usuario
    u.email AS correo,
    u.nombre_completo,
    u.ultimo_cambio,
    r.fecha_ingreso,
    r.cantidad_cursos_ofrecidos AS cursos_ofrecidos,
    r.total_ganancias
FROM 
    usuarios u
JOIN 
    reporte_usuarios r ON u.id = r.usuario_id
WHERE 
    u.rol_id = 1;

DELIMITER ;

DELIMITER $$

CREATE VIEW reportes_totales AS
SELECT 
    (SELECT COUNT(*) FROM reporte_usuarios ru
     JOIN usuarios u ON ru.usuario_id = u.id
     WHERE u.rol_id = 2) AS reportes_estudiantes,  -- Conteo de reportes de estudiantes
    (SELECT COUNT(*) FROM reporte_usuarios ru
     JOIN usuarios u ON ru.usuario_id = u.id
     WHERE u.rol_id = 1) AS reportes_tutores,     -- Conteo de reportes de tutores
    (SELECT COUNT(*) FROM reporte_usuarios) AS reportes_totales;  -- Conteo total de reportes

DELIMITER ;

DELIMITER $$

CREATE VIEW vista_comentarios_cursos AS
SELECT 
    cu.id AS curso_id,
    u.nombre_completo AS usuario_nombre,
    c.comentario,
    c.fecha_creacion,
    c.fecha_eliminacion,
    c.calificacion
FROM 
    comentarios c
JOIN 
    cursos cu ON c.curso_id = cu.id
JOIN 
    usuarios u ON c.usuario_id = u.id
ORDER BY 
    cu.titulo, c.fecha_creacion;

DELIMITER ;

DELIMITER $$

CREATE VIEW vista_conversaciones AS
SELECT 
    u.remitente.nombre_completo AS usuario_nombre,
    m.mensaje,
    m.fecha_envio
FROM 
    mensajes m
JOIN 
    usuarios u.remitente ON m.remitente_id = u.remitente.id
JOIN 
    usuarios u.destinatario ON m.destinatario_id = u.destinatario.id
ORDER BY 
    GREATEST(m.remitente_id, m.destinatario_id), 
    LEAST(m.remitente_id, m.destinatario_id), 
    m.fecha_envio;

--SELECT * 
--FROM vista_conversaciones 
--WHERE 
--    (remitente_id = 1 AND destinatario_id = 2) 
--    OR 
--    (remitente_id = 2 AND destinatario_id = 1)
--ORDER BY fecha_envio;

DELIMITER ;;

DELIMITER $$

CREATE VIEW personas_interactuadas AS
SELECT DISTINCT u.nombre_completo, u.foto, u.ruta_foto
FROM usuarios u
JOIN mensajes m ON (m.remitente_id = u.id OR m.destinatario_id = u.id)
WHERE m.remitente_id = :user_id OR m.destinatario_id = :user_id;
 -- SELECT * FROM personas_interactuadas WHERE persona_id != :user_id;

DELIMITER ;;

CREATE VIEW `kardexusuario` AS
    SELECT 
        `u`.`id` AS `usuario_id`,
        `u`.`nombre_completo` AS `usuario`,
        `c`.`id` AS `curso_id`,
        `c`.`titulo` AS `curso`,
        `c`.`categoria_id` AS `categoria_id`,
        `cat`.`nombre` AS `categoria`,
        `i`.`fecha_inscripcion` AS `fecha_inscripcion`,
        `i`.`progreso` AS `progreso`,
        CASE 
            WHEN `i`.`progreso` = 100 THEN 'Completado'
            ELSE 'En progreso'
        END AS `estatus`,
        `i`.`fecha_terminacion` AS `fecha_terminacion`
    FROM 
        `usuarios` `u`
    JOIN 
        `inscripciones` `i` ON (`u`.`id` = `i`.`usuario_id`)
    JOIN 
        `cursos` `c` ON (`i`.`curso_id` = `c`.`id`)
    JOIN 
        `categorias` `cat` ON (`c`.`categoria_id` = `cat`.`id`);

DELIMITER ;

DELIMITER $$

CREATE VIEW vista_certificado AS
SELECT 
    i.usuario_id AS id_usuario,
    i.curso_id AS id_curso,
    u.nombre_completo AS nombre_usuario,
    c.titulo AS nombre_curso,
    i.fecha_terminacion AS fecha_fin,
    CONCAT(ui.nombre_completo) AS nombre_instructor
FROM 
    inscripciones i
JOIN 
    usuarios u ON i.usuario_id = u.id
JOIN 
    cursos c ON i.curso_id = c.id
JOIN 
    usuarios ui ON c.instructor_id = ui.id;

DELIMETER ;