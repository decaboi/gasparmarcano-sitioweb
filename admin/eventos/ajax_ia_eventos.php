<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../src/Core/IAEventos.php';

$data = json_decode(file_get_contents('php://input'), true);
$accion = $data['accion'] ?? '';

$ia = new IAEventos();

if ($accion === 'recomendar') {
    echo json_encode([
        'recomendacion' => 'Los fines de semana y días festivos tienen mayor asistencia. Considere publicar con 15 días de anticipación.'
    ]);
} elseif ($accion === 'analizar') {
    $opt = $ia->optimizarEvento($data['titulo'] ?? '', $data['fecha'] ?? '', $data['lugar'] ?? '');
    echo json_encode([
        'alerta' => $opt['alerta'],
        'hashtags' => $opt['hashtags'],
        'tiempo_restante' => $opt['tiempo_restante']
    ]);
} else {
    echo json_encode(['error' => 'Acción no válida']);
}
?>