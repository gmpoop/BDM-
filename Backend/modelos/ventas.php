<?php
class Ventas {
    private $conn;
    private $table_name = "ventas";

    public $id;
    public $curso_id;
    public $usuario_id;
    public $comprador_id;
    public $fecha_venta;
    public $forma_pago;
    public $ingreso;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Consulta con marcadores de posición "?" para MySQLi
        $query = "INSERT INTO " . $this->table_name . " 
                  (curso_id, usuario_id, comprador_id, forma_pago, ingreso) 
                  VALUES 
                  (?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Verificar si la preparación fue exitosa
        if ($stmt === false) {
            return false;
        }

        // Sanitizar y enlazar los parámetros (tipo: i = entero, s = string, d = decimal)
        $this->curso_id = htmlspecialchars(strip_tags($this->curso_id));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->comprador_id = htmlspecialchars(strip_tags($this->comprador_id));
        $this->forma_pago = htmlspecialchars(strip_tags($this->forma_pago));
        $this->ingreso = htmlspecialchars(strip_tags($this->ingreso));

        // Enlazar los parámetros con sus respectivos tipos
        $stmt->bind_param('iiiss', $this->curso_id, $this->usuario_id, $this->comprador_id, $this->forma_pago, $this->ingreso);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }

        // Si algo sale mal, retornar false
        return false;
    }
}

?>