<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

require_once __DIR__ . '/../../src/Core/Database.php';
require_once __DIR__ . '/../../src/Core/IAInversiones.php';

$mensaje = '';
$error = '';

// Obtener sectores para el select
$conn = (new Database())->getConnection();
$sectores_result = $conn->query("SELECT DISTINCT sector FROM inversiones WHERE sector IS NOT NULL UNION SELECT 'Turismo' UNION SELECT 'Pesca' UNION SELECT 'Infraestructura' UNION SELECT 'Agroindustria' UNION SELECT 'Tecnología' ORDER BY sector");
$sectores = [];
while ($row = $sectores_result->fetch_assoc()) {
    $sectores[] = $row['sector'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $sector = $_POST['sector'] ?? '';
    $inversion_estimada = $_POST['inversion_estimada'] ?? '';
    $rentabilidad_proyectada = $_POST['rentabilidad_proyectada'] ?? '';
    $plazo_retorno = $_POST['plazo_retorno'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $beneficios = $_POST['beneficios'] ?? '';
    $requisitos = $_POST['requisitos'] ?? '';
    $contacto_referencia = $_POST['contacto_referencia'] ?? '';
    $destacada = isset($_POST['destacada']) ? 1 : 0;
    $status = $_POST['status'] ?? 1;
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
    
    // IA Análisis de mercado
    $ia = new IAInversiones();
    $analisis = $ia->analizarOportunidad($titulo, $sector, $descripcion);
    
    // Subir imagen
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagen = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_dir . $imagen);
    }
    
    $stmt = $conn->prepare("INSERT INTO inversiones (titulo, slug, sector, inversion_estimada, rentabilidad_proyectada, plazo_retorno, descripcion, beneficios, requisitos, contacto_referencia, imagen_destacada, destacada, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssii", $titulo, $slug, $sector, $inversion_estimada, $rentabilidad_proyectada, $plazo_retorno, $descripcion, $beneficios, $requisitos, $contacto_referencia, $imagen, $destacada, $status);
    
    if ($stmt->execute()) {
        $mensaje = "✅ Oportunidad de inversión creada.<br>";
        $mensaje .= "📊 IA - Potencial: {$analisis['potencial']}% | Tendencia: {$analisis['tendencia']}<br>";
        $mensaje .= "🎯 Recomendación: " . $analisis['recomendaciones'];
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Oportunidad de Inversión + IA - Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({ selector: '#descripcion, #beneficios, #requisitos', height: 200, menubar: false, plugins: 'advlist autolink lists link', toolbar: 'undo redo | bold italic | bullist numlist' });
        
        function actualizarIA() {
            const titulo = document.querySelector('[name="titulo"]').value;
            const sector = document.querySelector('[name="sector"]').value;
            const descripcion = document.querySelector('[name="descripcion"]').value;
            if (titulo.length > 5 && sector) {
                fetch('ajax_ia_inversiones.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({titulo: titulo, sector: sector, descripcion: descripcion})
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('ia_potencial').innerHTML = data.potencial + '%';
                    document.getElementById('ia_potencial_barra').style.width = data.potencial + '%';
                    document.getElementById('ia_tendencia').innerHTML = data.tendencia;
                    document.getElementById('ia_riesgo').innerHTML = data.riesgo;
                    document.getElementById('ia_incentivos').innerHTML = data.incentivos;
                    document.getElementById('ia_analisis').innerHTML = data.analisis_mercado;
                    document.getElementById('ia_recomendaciones').innerHTML = data.recomendaciones;
                    document.getElementById('ia_hashtags').innerHTML = data.hashtags;
                });
            }
        }
    </script>
    <style>
        .ia-card { background: linear-gradient(135deg, #0A2472 0%, #00247D 100%); color: white; border-radius: 16px; }
        .progress-bar-ia { background-color: #FFCD00; }
    </style>
</head>
<body style="background-color: #f5f5f5;">
    <div class="container py-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-graph-up"></i> Nueva Oportunidad de Inversión + IA</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
        
        <?php if($mensaje): ?><div class="alert alert-success"><?php echo $mensaje; ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        
        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Título de la oportunidad *</label>
                                <input type="text" name="titulo" class="form-control" onkeyup="actualizarIA()" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sector *</label>
                                        <select name="sector" class="form-select" onchange="actualizarIA()" required>
                                            <option value="">Seleccionar...</option>
                                            <?php foreach($sectores as $s): ?>
                                            <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Inversión estimada</label>
                                        <input type="text" name="inversion_estimada" class="form-control" placeholder="Ej: 500.000 USD - 1.000.000 USD">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Rentabilidad proyectada</label>
                                        <input type="text" name="rentabilidad_proyectada" class="form-control" placeholder="Ej: 15-20% anual">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Plazo de retorno</label>
                                        <input type="text" name="plazo_retorno" class="form-control" placeholder="Ej: 3-5 años">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción del proyecto *</label>
                                <textarea name="descripcion" id="descripcion" onkeyup="actualizarIA()"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Beneficios e incentivos</label>
                                <textarea name="beneficios" id="beneficios"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Requisitos para invertir</label>
                                <textarea name="requisitos" id="requisitos"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contacto de referencia</label>
                                <input type="text" name="contacto_referencia" class="form-control" placeholder="Oficina de Atención al Inversionista">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Imagen representativa</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="destacada" value="1" class="form-check-input" id="destacada">
                                        <label class="form-check-label" for="destacada">Destacar en portada</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <select name="status" class="form-select">
                                            <option value="1">Activa (visible para inversionistas)</option>
                                            <option value="0">Inactiva (borrador)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Publicar Oportunidad</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="ia-card p-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-robot fs-1 me-2"></i>
                        <h5 class="mb-0">Asistente IA Mercado</h5>
                    </div>
                    <hr class="bg-white-50">
                    
                    <div class="mb-3">
                        <h6>Potencial de inversión</h6>
                        <div class="progress mb-1" style="height: 25px;">
                            <div id="ia_potencial_barra" class="progress-bar progress-bar-ia" style="width: 0%;">0%</div>
                        </div>
                        <small id="ia_potencial">-</small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6"><strong>Tendencia:</strong><br><span id="ia_tendencia">-</span></div>
                        <div class="col-6"><strong>Riesgo:</strong><br><span id="ia_riesgo">-</span></div>
                    </div>
                    
                    <div class="mb-3">
                        <strong><i class="bi bi-gift"></i> Incentivos fiscales:</strong>
                        <p id="ia_incentivos" class="small">-</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong><i class="bi bi-bar-chart"></i> Análisis de mercado:</strong>
                        <p id="ia_analisis" class="small">Complete los datos para ver análisis</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong><i class="bi bi-lightbulb"></i> Recomendación IA:</strong>
                        <p id="ia_recomendaciones" class="small">-</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong><i class="bi bi-hash"></i> Hashtags para difusión:</strong>
                        <p id="ia_hashtags" class="small">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>