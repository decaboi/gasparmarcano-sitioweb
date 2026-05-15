<?php
// src/Models/ContenidoModel.php
require_once __DIR__ . '/../Core/Database.php';

class ContenidoModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener los últimos 3 contenidos de una categoría específica (Ej: Turismo o Noticias)
    public function getUltimosPorCategoria($categoria_nombre, $limite = 3) {
        $sql = "SELECT c.id, c.titulo, c.slug, c.resumen, c.imagen_destacada, c.created_at, cat.nombre as categoria 
                FROM contenido c 
                JOIN categorias cat ON c.categoria_id = cat.id 
                WHERE cat.nombre = ? AND c.status = 1 
                ORDER BY c.created_at DESC LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $categoria_nombre, $limite);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Obtener Slider (Noticias destacadas)
    public function getSlider() {
        // Selecciona noticias que tengan imagen destacada
        $sql = "SELECT titulo, slug, imagen_destacada, resumen FROM contenido WHERE status = 1 AND imagen_destacada IS NOT NULL ORDER BY RAND() LIMIT 3";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}
?>