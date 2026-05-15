<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../src/Core/IAInversiones.php';

$data = json_decode(file_get_contents('php://input'), true);
$titulo = $data['titulo'] ?? '';
$sector = $data['sector'] ?? 'Turismo';
$descripcion = $data['descripcion'] ?? '';

$ia = new IAInversiones();
$analisis = $ia->analizarOportunidad($titulo, $sector, $descripcion);

echo json_encode([
    'potencial' => $analisis['potencial'],
    'tendencia' => $analisis['tendencia'],
    'riesgo' => $analisis['riesgo'],
    'incentivos' => $analisis['incentivos'],
    'analisis_mercado' => $analisis['analisis_mercado'],
    'recomendaciones' => $analisis['recomendaciones'],
    'hashtags' => $analisis['hashtags']
]);
?>