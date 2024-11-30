DELIMITER $$

CREATE FUNCTION contar_estudiantes_por_curso(curso_id_input BIGINT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE total_inscritos INT;

    SELECT COUNT(*) 
    INTO total_inscritos
    FROM inscripciones
    WHERE curso_id = curso_id_input;

    RETURN total_inscritos;
END$$

DELIMITER ;
