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

CREATE VIEW vista_categorias_con_creador AS
SELECT 
    c.nombre AS categoria_nombre,
    c.fecha_creacion AS categoria_fecha_creacion,
    u.email AS creador_email
FROM 
    categorias c
JOIN 
    usuarios u ON c.usuario_creador_id = u.id;

DELIMITER ;