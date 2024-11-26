<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Configuración de la base de datos
// Obtener la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
try {
    // Conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Verificar si se proporcionó el rol como parámetro
    $rol = isset($_GET['rol']) ? intval($_GET['rol']) : null;

    if ($rol === null || ($rol !== 2 && $rol !== 3)) {
        echo json_encode([
            "success" => false,
            "message" => "Rol inválido. Usa ?rol=2 para estudiantes o ?rol=3 para instructores."
        ]);
        exit;
    }

    // Consulta SQL basada en el rol
    $sql = ($rol === 2)
        ? "SELECT u.id AS id_usuario, u.nombre, u.fecha_ingreso, COUNT(c.id) AS cursos_inscritos, 
              ROUND((SUM(c.completado) / COUNT(c.id)) * 100, 2) AS porcentaje_cursos_terminados
            FROM usuarios u
            LEFT JOIN cursos_inscritos c ON u.id = c.usuario_id
            WHERE u.rol_id = 2
            GROUP BY u.id"
        : "SELECT u.id AS id_usuario, u.nombre, u.fecha_ingreso, COUNT(c.id) AS cursos_ofrecidos, 
                SUM(c.ganancias) AS total_ganancias
            FROM usuarios u
            LEFT JOIN cursos c ON u.id = c.instructor_id
            WHERE u.rol_id = 3
            GROUP BY u.id";

    // Ejecutar la consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtener los resultados
    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    echo json_encode([
        "success" => true,
        "data" => $reportes
    ]);
} catch (PDOException $e) {
    // Manejar errores
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>
