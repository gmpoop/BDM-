<?php

class Database {
    private $host = "localhost";
    private $db_name = "bdm"; // Nombre de tu base de datos
    private $username = "root"; // Usuario de tu base de datos
    private $password = ""; // Contraseña de tu base de datos
    public $conn;

    public function getConnection() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        // Verificar conexión
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
