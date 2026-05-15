<?php
// src/Core/Database.php
class Database {
    private $host = "localhost";
    private $db_name = "municipio_gaspar";
    private $username = "root"; // Cambiar por usuario real de producción
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Forzamos la codificación UTF-8 para evitar caracteres raros en la historia
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            $this->conn->set_charset("utf8mb4");
        } catch(Exception $e) {
            error_log("Error de conexión: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>