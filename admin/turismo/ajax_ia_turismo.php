<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../src/Core/IATurismo.php';

$data = json_decode(file_get_contents('php://input'), true);
$titulo = $data['titulo'] ?? '';
$resumen = $data['resumen'] ?? '';

$ia = new IATurismo();
$opt = $ia->optimizarAtractivo($titulo, $resumen, '');

echo json_encode([
    'temporada_ideal' => $opt['temporada_ideal'],
    'etiquetas_viaje' => $opt['etiquetas_viaje'],
    'dato_curioso' => $opt['dato_curioso']
]);
?>