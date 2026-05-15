<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

$id = $_GET['id'] ?? 0;
if ($id) {
    require_once __DIR__ . '/../../src/Core/Database.php';
    $conn = (new Database())->getConnection();
    
    // Obtener el archivo para eliminarlo
    $stmt = $conn->prepare("SELECT archivo_pdf FROM documentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    
    if ($row && $row['archivo_pdf']) {
        $path = __DIR__ . '/../../uploads/documentos/' . $row['archivo_pdf'];
        if (file_exists($path)) unlink($path);
    }
    
    $stmt = $conn->prepare("DELETE FROM documentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('Location: index.php');
exit();
?>