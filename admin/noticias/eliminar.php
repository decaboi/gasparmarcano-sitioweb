<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

$id = $_GET['id'] ?? 0;

if ($id) {
    require_once __DIR__ . '/../../src/Core/Database.php';
    $conn = (new Database())->getConnection();
    
    // Obtener imagen para borrarla del servidor
    $stmt = $conn->prepare("SELECT imagen_destacada FROM contenido WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && $row['imagen_destacada']) {
        $img_path = __DIR__ . '/../../uploads/' . $row['imagen_destacada'];
        if (file_exists($img_path)) unlink($img_path);
    }
    
    // Eliminar registro
    $stmt = $conn->prepare("DELETE FROM contenido WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: index.php');
exit();
?>