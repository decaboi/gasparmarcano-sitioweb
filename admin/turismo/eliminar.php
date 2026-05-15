<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

$id = $_GET['id'] ?? 0;
if ($id) {
    require_once __DIR__ . '/../../src/Core/Database.php';
    $conn = (new Database())->getConnection();
    $stmt = $conn->prepare("SELECT imagen_destacada FROM contenido WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row && $row['imagen_destacada']) {
        $path = __DIR__ . '/../../uploads/' . $row['imagen_destacada'];
        if (file_exists($path)) unlink($path);
    }
    $stmt = $conn->prepare("DELETE FROM contenido WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('Location: index.php');
?>