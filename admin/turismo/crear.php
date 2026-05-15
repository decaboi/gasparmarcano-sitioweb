<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

require_once __DIR__ . '/../../src/Core/Database.php';
require_once __DIR__ . '/../../src/Core/IATurismo.php'; // Nueva IA de turismo

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $resumen = trim($_POST['resumen'] ?? '');
    $cuerpo = $_POST['cuerpo'] ?? '';
    $categoria_id = 4; // ID de "Atractivos Turísticos"
    $status = $_POST['status'] ?? 1;
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
    
    // IA Turística: optimiza el contenido
    $ia = new IATurismo();
    $optimizacion = $ia->optimizarAtractivo($titulo, $resumen, $cuerpo);
    
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
    $stmt = $conn->prepare("INSERT INTO contenido (titulo, slug, resumen, cuerpo, imagen_destacada, categoria_id, status, meta_keywords, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisss", $titulo, $slug, $resumen, $cuerpo, $imagen, $categoria_id, $status, $optimizacion['keywords'], $optimizacion['descripcion']);
    
    if ($stmt->execute()) {
        $mensaje = "✅ Atractivo turístico creado.<br>🌴 IA recomienda: " . $optimizacion['recomendacion'];
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Atractivo Turístico + IA - Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({ selector: '#cuerpo', height: 400, menubar: false, plugins: 'advlist autolink lists link image', toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist' });
        
        function actualizarIA() {
            const titulo = document.querySelector('[name="titulo"]').value;
            const resumen = document.querySelector('[name="resumen"]').value;
            if (titulo.length > 5) {
                fetch('ajax_ia_turismo.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({titulo: titulo, resumen: resumen})
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('ia_temp').innerHTML = data.temporada_ideal;
                    document.getElementById('ia_etiquetas').innerHTML = data.etiquetas_viaje;
                    document.getElementById('ia_dato').innerHTML = data.dato_curioso;
                });
            }
        }
    </script>
</head>
<body style="background-color: #f5f5f5;">
    <div class="container py-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-magic"></i> Nuevo Atractivo Turístico + IA</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
        
        <?php if($mensaje): ?><div class="alert alert-success"><?php echo $mensaje; ?></div><?php endif; ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nombre del atractivo *</label>
                                <input type="text" name="titulo" class="form-control" onkeyup="actualizarIA()" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción corta</label>
                                <textarea name="resumen" class="form-control" rows="3" onkeyup="actualizarIA()"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Información completa</label>
                                <textarea name="cuerpo" id="cuerpo"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Imagen del atractivo</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select">
                                    <option value="1">Publicado</option>
                                    <option value="0">Borrador</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Atractivo</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-header bg-dark">🌴 Asistente IA Turística</div>
                    <div class="card-body">
                        <h6><i class="bi bi-calendar"></i> Temporada ideal:</h6>
                        <p id="ia_temp" class="small">Diciembre - Abril (mejor clima)</p>
                        <hr>
                        <h6><i class="bi bi-tags"></i> Etiquetas de viaje:</h6>
                        <p id="ia_etiquetas" class="small">#JuanGriego #Aventura #Cultura</p>
                        <hr>
                        <h6><i class="bi bi-star"></i> Dato curioso:</h6>
                        <p id="ia_dato" class="small">La bahía es una de las más protegidas del Caribe</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>