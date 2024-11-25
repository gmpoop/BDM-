DELIMITER $$

CREATE VIEW view_reporte_usuario_estudiantes AS
SELECT 
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

CREATE VIEW view_reporte_usuario_tutores AS
SELECT 
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

CREATE VIEW KardexUsuario AS
SELECT 
    u.id AS usuario_id,
    u.nombre_completo AS usuario,
    c.id AS curso_id,
    c.titulo AS curso,
    c.categoria_id,
    i.fecha_inscripcion,
    i.progreso,
    CASE 
        WHEN i.progreso = 100 THEN 'Completado'
        ELSE 'En progreso'
    END AS estatus,
    i.fecha_terminacion
FROM 
    usuarios u
JOIN 
    inscripciones i ON u.id = i.usuario_id
JOIN 
    cursos c ON i.curso_id = c.id;

DELIMITER ;