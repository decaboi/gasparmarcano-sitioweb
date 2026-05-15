<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

require_once __DIR__ . '/../../src/Core/Database.php';
require_once __DIR__ . '/../../src/Core/IAMarketing.php'; // <-- NUEVO: La IA

$mensaje = '';
$error = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $resumen = trim($_POST['resumen'] ?? '');
    $cuerpo = $_POST['cuerpo'] ?? '';
    $categoria_id = 1;
    $status = $_POST['status'] ?? 1;
    
    // Generar slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
    
    // === OPTIMIZACIÓN CON IA ===
    $ia = new IAMarketing();
    $optimizacion = $ia->optimizarNoticia($titulo, $resumen, $cuerpo);
    
    // Si el usuario activó "Publicación Inteligente", la IA decide el status
    if (isset($_POST['publicacion_inteligente']) && $_POST['publicacion_inteligente'] == '1') {
        $status = $optimizacion['status_recomendado'];
    }
    
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
    
    // Guardar también los metadatos generados por IA
    $keywords = $optimizacion['hashtags'];
    $meta_desc = $optimizacion['descripcion_seo'];
    
    $stmt->bind_param("sssssisss", $titulo, $slug, $resumen, $cuerpo, $imagen, $categoria_id, $status, $keywords, $meta_desc);
    
    if ($stmt->execute()) {
        $mensaje = "✅ Noticia creada exitosamente.<br>";
        $mensaje .= "📢 Recomendación IA: " . $optimizacion['recomendacion'];
    } else {
        $error = "❌ Error al crear: " . $conn->error;
    }
}

// Obtener sugerencias de IA en tiempo real (vía AJAX después)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Noticia + IA Marketing - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#cuerpo',
            height: 400,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            setup: function(editor) {
                editor.on('change', function() {
                    // Cada vez que cambia el contenido, la IA analiza en segundo plano
                    analizarConIA();
                });
            }
        });
        
        function analizarConIA() {
            const titulo = document.querySelector('[name="titulo"]').value;
            const resumen = document.querySelector('[name="resumen"]').value;
            
            if (titulo.length > 5) {
                fetch('ajax_ia_analizar.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({titulo: titulo, resumen: resumen})
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('ia_hashtags').innerHTML = data.hashtags;
                    document.getElementById('ia_horario').innerHTML = data.mejor_horario;
                    document.getElementById('ia_copy').innerHTML = data.copy_redes;
                });
            }
        }
    </script>
</head>
<body style="background-color: #f5f5f5;">
    <div class="container py-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-magic"></i> Crear Noticia con Asistente IA</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
        
        <?php if($mensaje): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Formulario principal -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Título *</label>
                                <input type="text" name="titulo" class="form-control" onkeyup="analizarConIA()" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Resumen</label>
                                <textarea name="resumen" class="form-control" rows="3" onkeyup="analizarConIA()"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contenido completo</label>
                                <textarea name="cuerpo" id="cuerpo"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Imagen destacada</label>
                                <input type="file" name="imagen" class="form-control" accept="image/*">
                            </div>

                            <!-- Sección: Redes Sociales (IA Community Manager) -->
<div class="card mb-3">
    <div class="card-header bg-info text-white">
        <i class="bi bi-share"></i> 📱 Publicación en Redes Sociales (IA)
    </div>
    <div class="card-body">
        <div class="form-check mb-3">
            <input type="checkbox" name="publicar_redes" value="1" class="form-check-input" id="publicar_redes" onchange="toggleRedesOptions()">
            <label class="form-check-label" for="publicar_redes">
                <i class="bi bi-robot"></i> Activar publicación automática en redes sociales
            </label>
            <small class="text-muted d-block">La IA generará el contenido y lo publicará en el horario óptimo</small>
        </div>
        
        <div id="redes_options" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Redes sociales destino</label>
                        <select name="redes_destino" class="form-select">
                            <option value="todas">Todas (Facebook + Instagram)</option>
                            <option value="facebook">Solo Facebook</option>
                            <option value="instagram">Solo Instagram</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Modo de publicación</label>
                        <select name="modo_publicacion" class="form-select">
                            <option value="inmediata">Inmediata (aprobación humana)</option>
                            <option value="programada">Programada (horario óptimo sugerido por IA)</option>
                            <option value="borrador">Solo preparar borrador (no publicar)</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Vista previa de la publicación generada por IA -->
            <div class="alert alert-light border mt-2" id="vista_previa_redes">
                <div class="d-flex justify-content-between">
                    <strong><i class="bi bi-robot"></i> Vista previa generada por IA:</strong>
                    <small class="text-muted" id="ia_horario_sugerido"></small>
                </div>
                <hr>
                <div id="ia_facebook_preview">
                    <strong>📘 Facebook:</strong>
                    <p class="small mt-1" id="fb_copy_preview">Cargando sugerencias...</p>
                </div>
                <div id="ia_instagram_preview" class="mt-2">
                    <strong>📷 Instagram:</strong>
                    <p class="small mt-1" id="ig_copy_preview">Cargando sugerencias...</p>
                </div>
                <div id="ia_hashtags_preview" class="mt-2">
                    <strong>🏷️ Hashtags sugeridos:</strong>
                    <p class="small text-primary" id="hashtags_preview">-</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRedesOptions() {
    const checkbox = document.getElementById('publicar_redes');
    const options = document.getElementById('redes_options');
    options.style.display = checkbox.checked ? 'block' : 'none';
    
    if (checkbox.checked) {
        generarVistaPreviaSocial();
    }
}

function generarVistaPreviaSocial() {
    const titulo = document.querySelector('[name="titulo"]').value;
    const resumen = document.querySelector('[name="resumen"]').value;
    const tipo = 'noticia';
    
    if (titulo.length > 5) {
        fetch('ajax_ia_social.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ titulo: titulo, resumen: resumen, tipo: tipo })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('fb_copy_preview').innerHTML = data.facebook.mensaje + '<br>' + data.facebook.hashtags;
            document.getElementById('ig_copy_preview').innerHTML = data.instagram.mensaje + '<br>' + data.instagram.hashtags;
            document.getElementById('hashtags_preview').innerHTML = data.instagram.hashtags;
            document.getElementById('ia_horario_sugerido').innerHTML = '🕒 Horario óptimo: FB ' + data.facebook.horario_recomendado + ' | IG ' + data.instagram.horario_recomendado;
        });
    }
}

// Llamar a la IA cuando se escriba el título
document.querySelector('[name="titulo"]')?.addEventListener('keyup', () => {
    if (document.getElementById('publicar_redes')?.checked) {
        generarVistaPreviaSocial();
    }
});
</script>


                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select">
                                    <option value="1">Publicado ahora</option>
                                    <option value="0">Borrador</option>
                                </select>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="publicacion_inteligente" value="1" class="form-check-input" id="iaMode">
                                <label class="form-check-label" for="iaMode">
                                    <i class="bi bi-robot"></i> Activar Publicación Inteligente (IA decide cuándo publicar)
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar con IA</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Panel de IA (Recomendaciones en vivo) -->
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-robot"></i> Asistente IA Marketing
                    </div>
                    <div class="card-body">
                        <h6><i class="bi bi-hash"></i> Hashtags sugeridos:</h6>
                        <p id="ia_hashtags" class="small">#JuanGriego #7Transformaciones #TurismoVenezuela</p>
                        <hr>
                        <h6><i class="bi bi-clock"></i> Mejor horario para publicar:</h6>
                        <p id="ia_horario" class="small">Jueves 10:00 AM o Sábado 7:00 PM</p>
                        <hr>
                        <h6><i class="bi bi-facebook"></i> Copy para redes sociales:</h6>
                        <p id="ia_copy" class="small">Descubre lo nuevo en Juan Griego... 🌊🇻🇪</p>
                        <hr>
                        <div class="alert alert-info small">
                            <i class="bi bi-shield-check"></i> Ética IA: Cumple con el Código de Ética del MINCYT y Ley de Infogobierno.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>