<?php
// admin/noticias/ajax_ia_analizar.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../src/Core/IAMarketing.php';

$data = json_decode(file_get_contents('php://input'), true);
$titulo = $data['titulo'] ?? '';
$resumen = $data['resumen'] ?? '';

$ia = new IAMarketing();
$optimizacion = $ia->optimizarNoticia($titulo, $resumen, '');

echo json_encode([
    'hashtags' => $optimizacion['hashtags'],
    'mejor_horario' => $optimizacion['horario'],
    'copy_redes' => $optimizacion['copy_redes']
]);
?>