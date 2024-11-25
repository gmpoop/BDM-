<?php
class KardexController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new KardexModel($db);
    }

    public function getKardexEstudiante()
    {
        // Obtener los parámetros de la solicitud GET
        $usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;

        // Definir filtros a partir de los parámetros GET
        $filtros = [
            'fecha_inicio' => isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null,
            'fecha_fin' => isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null,
            'categoria' => isset($_GET['categoria']) ? $_GET['categoria'] : null, // Cambiar a 'categoria'
            'estatus' => isset($_GET['estatus']) ? $_GET['estatus'] : null // Agregar filtro de estatus
        ];

        try {
            // Obtener los resultados del modelo
            $kardex = $this->model->getKardexByUser($usuario_id, $filtros);

            if ($kardex) {
                // Retornar los datos encontrados en formato JSON
                echo json_encode($kardex);
            } else {
                // Responder con código 404 si no hay resultados
                http_response_code(404);
                echo json_encode(["message" => "No se encontraron registros para el usuario especificado."]);
            }
        } catch (Exception $e) {
            // Manejo de errores
            http_response_code(500);
            echo json_encode(["message" => "Error al procesar la solicitud.", "error" => $e->getMessage()]);
        }
    }

}
