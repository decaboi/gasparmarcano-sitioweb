<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../src/Core/IADocumentos.php';

$titulo = $_GET['titulo'] ?? '';

$ia = new IADocumentos();
$resultado = $ia->clasificarDocumento($titulo);

echo json_encode($resultado);
?>