<?php
// cron/publicar_programado.php
// Este script se ejecuta cada 15 minutos (cron job)
// Para pruebas: http://localhost/gasparmarcano-sitioweb/cron/publicar_programado.php

require_once __DIR__ . '/../src/Core/Database.php';
require_once __DIR__ . '/../src/Core/IASocialMedia.php';

$db = new Database();
$conn = $db->getConnection();

// Buscar publicaciones programadas que no se han publicado y cuya hora ha llegado
$ahora = date('Y-m-d H:i:00');
$stmt = $conn->prepare("SELECT * FROM publicaciones_redes WHERE publicada = 0 AND horario_programado <= ?");
$stmt->bind_param("s", $ahora);
$stmt->execute();
$pendientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

foreach ($pendientes as $pub) {
    echo "Procesando publicación ID: " . $pub['id'] . "\n";
    
    // Aquí se integrarían las APIs reales de Facebook e Instagram
    // Por ahora, simulamos la publicación
    
    // Marcar como publicada
    $update = $conn->prepare("UPDATE publicaciones_redes SET publicada = 1, fecha_publicacion = NOW() WHERE id = ?");
    $update->bind_param("i", $pub['id']);
    $update->execute();
    
    echo "✅ Publicación marcada como enviada\n";
}

echo "Proceso completado.\n";
?>