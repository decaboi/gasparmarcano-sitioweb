<?php
require_once __DIR__ . '/../src/Core/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inversion_id = $_POST['inversion_id'] ?? null;
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $presupuesto_estimado = $_POST['presupuesto_estimado'] ?? '';
    $mensaje = trim($_POST['mensaje'] ?? '');
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("INSERT INTO leads_inversionistas (inversion_id, nombre, email, telefono, pais, presupuesto_estimado, mensaje, ip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $inversion_id, $nombre, $email, $telefono, $pais, $presupuesto_estimado, $mensaje, $ip);
    
    if ($stmt->execute()) {
        // Redirigir con mensaje de éxito
        header('Location: inversionistas.php?mensaje=exito');
    } else {
        header('Location: inversionistas.php?mensaje=error');
    }
} else {
    header('Location: inversionistas.php');
}
?>