<?php
// admin/noticias/ajax_ia_social.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../src/Core/IASocialMedia.php';

$data = json_decode(file_get_contents('php://input'), true);
$titulo = $data['titulo'] ?? '';
$resumen = $data['resumen'] ?? '';
$tipo = $data['tipo'] ?? 'noticia';
$url_base = 'https://municipiogaspar.gob.ve/noticia.php?slug=';

$ia = new IASocialMedia();
$contenido = $ia->generarContenidoRedes($titulo, $resumen, $url_base, $tipo);

echo json_encode($contenido);
?>