<?php
class VentasController {
    private $db;
    private $ventas;

    public function __construct($db) {
        $this->db = $db;
        $this->ventas = new Ventas($this->db);
    }

    public function createVenta() {
        // Obtener datos JSON de la solicitud
        $data = json_decode(file_get_contents("php://input"));

        // Asignar valores a las propiedades del modelo
        $this->ventas->curso_id = $data->curso_id;
        $this->ventas->usuario_id = $data->usuario_id;
        $this->ventas->comprador_id = $data->comprador_id;
        $this->ventas->forma_pago = $data->forma_pago;
        $this->ventas->ingreso = $data->ingreso;

        // Crear la venta
        if ($this->ventas->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Venta creada exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Error al crear la venta."]);
        }
    }
}
?>