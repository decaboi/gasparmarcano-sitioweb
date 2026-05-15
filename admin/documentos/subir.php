<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

require_once __DIR__ . '/../../src/Core/Database.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $tipo = $_POST['tipo'] ?? 'informativo';
    $numero = trim($_POST['numero'] ?? '');
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $resumen = trim($_POST['resumen'] ?? '');
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    
    // Subir archivo PDF
    $archivo_pdf = '';
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../uploads/documentos/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $ext = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            $error = "❌ Solo se permiten archivos PDF.";
        } else {
            $archivo_pdf = uniqid() . '.pdf';
            move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_dir . $archivo_pdf);
        }
    } else {
        $error = "❌ Debe seleccionar un archivo PDF.";
    }
    
    if (!$error && $archivo_pdf) {
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("INSERT INTO documentos (titulo, tipo, numero, fecha, resumen, archivo_pdf, destacado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $titulo, $tipo, $numero, $fecha, $resumen, $archivo_pdf, $destacado);
        
        if ($stmt->execute()) {
            $mensaje = "✅ Documento subido exitosamente.";
        } else {
            $error = "❌ Error al guardar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Documento - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f5f5f5;">
    <div class="container py-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-cloud-upload"></i> Subir Documento</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
        
        <?php if($mensaje): ?>
        <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Título del documento *</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="tipo" class="form-select">
                                    <option value="gaceta">Gaceta Municipal</option>
                                    <option value="ordenanza">Ordenanza</option>
                                    <option value="decreto">Decreto</option>
                                    <option value="mapa">Mapa / Plano</option>
                                    <option value="informativo">Informativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Número</label>
                                <input type="text" name="numero" class="form-control" placeholder="Ej: 001-2025">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-check mt-4">
                                <input type="checkbox" name="destacado" value="1" class="form-check-input" id="destacado">
                                <label class="form-check-label" for="destacado">Destacar en portada</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Resumen / Descripción</label>
                        <textarea name="resumen" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Archivo PDF *</label>
                        <input type="file" name="pdf_file" class="form-control" accept=".pdf" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Subir Documento</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>