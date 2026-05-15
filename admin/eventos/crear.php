<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

require_once __DIR__ . '/../../src/Core/Database.php';
require_once __DIR__ . '/../../src/Core/IAEventos.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $resumen = trim($_POST['resumen'] ?? '');
    $cuerpo = $_POST['cuerpo'] ?? '';
    $fecha_evento = $_POST['fecha_evento'] ?? '';
    $lugar = $_POST['lugar'] ?? '';
    $categoria_id = 2; // ID de "Eventos Oficiales"
    $status = $_POST['status'] ?? 1;
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
    
    // IA Eventos: optimiza la publicación
    $ia = new IAEventos();
    $optimizacion = $ia->optimizarEvento($titulo, $fecha_evento, $lugar);
    
    // Subir imagen
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagen = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_dir . $imagen);
    }
    
    $conn = (new Database())->getConnection();
    $stmt = $conn->prepare("INSERT INTO contenido (titulo, slug, resumen, cuerpo, imagen_destacada, fecha_evento, categoria_id, status, meta_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssis", $titulo, $slug, $resumen, $cuerpo, $imagen, $fecha_evento, $categoria_id, $status, $optimizacion['keywords']);
    
    if ($stmt->execute()) {
        $mensaje = "✅ Evento creado exitosamente.<br>📢 IA recomienda: " . $optimizacion['recomendacion'];
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Evento + IA - Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({ selector: '#cuerpo', height: 300, menubar: false, plugins: 'advlist autolink lists link', toolbar: 'undo redo | bold italic | bullist numlist' });
        
        function actualizarIA() {
            const titulo = document.querySelector('[name="titulo"]').value;
            const fecha = document.querySelector('[name="fecha_evento"]').value;
            const lugar = document.querySelector('[name="lugar"]').value;
            if (titulo.length > 5) {
                fetch('ajax_ia_eventos.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({accion: 'analizar', titulo: titulo, fecha: fecha, lugar: lugar})
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('ia_alerta').innerHTML = data.alerta;
                    document.getElementById('ia_hashtags').innerHTML = data.hashtags;
                    document.getElementById('ia_tiempo').innerHTML = data.tiempo_restante;
                });
            }
        }
    </script>
</head>
<body style="background-color: #f5f5f5;">
    <div class="container py-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-magic"></i> Nuevo Evento + IA Predictiva</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
        
        <?php if($mensaje): ?><div class="alert alert-success"><?php echo $mensaje; ?></div><?php endif; ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nombre del evento *</label>
                                <input type="text" name="titulo" class="form-control" onkeyup="actualizarIA()" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha del evento *</label>
                                        <input type="date" name="fecha_evento" class="form-control" onchange="actualizarIA()" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Lugar *</label>
                                        <input type="text" name="lugar" class="form-control" placeholder="Ej: Plaza Bolívar, Juan Griego" onkeyup="actualizarIA()" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción corta</label>
                                <textarea name="resumen" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Información completa del evento</label>
                                <textarea name="cuerpo" id="cuerpo"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Imagen del evento</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select">
                                    <option value="1">Publicado</option>
                                    <option value="0">Borrador</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Evento</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-warning">
                    <div class="card-header bg-dark text-white">🤖 Asistente IA Eventos</div>
                    <div class="card-body">
                        <h6><i class="bi bi-bell"></i> Alerta temprana:</h6>
                        <p id="ia_alerta" class="small">Complete la fecha para ver recomendaciones</p>
                        <hr>
                        <h6><i class="bi bi-hash"></i> Hashtags sugeridos:</h6>
                        <p id="ia_hashtags" class="small">#EventoGasparMarcano</p>
                        <hr>
                        <h6><i class="bi bi-hourglass-split"></i> Tiempo para el evento:</h6>
                        <p id="ia_tiempo" class="small">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>