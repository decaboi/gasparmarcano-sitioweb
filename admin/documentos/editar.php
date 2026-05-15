<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

require_once __DIR__ . '/../../src/Core/Database.php';

$id = $_GET['id'] ?? 0;
$mensaje = '';
$error = '';

$db = new Database();
$conn = $db->getConnection();

// Obtener documento actual
$stmt = $conn->prepare("SELECT * FROM documentos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$doc = $stmt->get_result()->fetch_assoc();

if (!$doc) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $tipo = $_POST['tipo'] ?? 'informativo';
    $numero = trim($_POST['numero'] ?? '');
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $resumen = trim($_POST['resumen'] ?? '');
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $status = isset($_POST['status']) ? 1 : 0;
    
    $stmt = $conn->prepare("UPDATE documentos SET titulo=?, tipo=?, numero=?, fecha=?, resumen=?, destacado=?, status=? WHERE id=?");
    $stmt->bind_param("sssssiii", $titulo, $tipo, $numero, $fecha, $resumen, $destacado, $status, $id);
    
    if ($stmt->execute()) {
        $mensaje = "✅ Documento actualizado exitosamente.";
        // Recargar datos
        $stmt = $conn->prepare("SELECT * FROM documentos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $doc = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "❌ Error al actualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Documento - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f5f5f5;">
    <div class="container py-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-pencil"></i> Editar Documento</h2>
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
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título del documento *</label>
                        <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($doc['titulo']); ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="tipo" class="form-select">
                                    <option value="gaceta" <?php echo $doc['tipo'] == 'gaceta' ? 'selected' : ''; ?>>Gaceta Municipal</option>
                                    <option value="ordenanza" <?php echo $doc['tipo'] == 'ordenanza' ? 'selected' : ''; ?>>Ordenanza</option>
                                    <option value="decreto" <?php echo $doc['tipo'] == 'decreto' ? 'selected' : ''; ?>>Decreto</option>
                                    <option value="mapa" <?php echo $doc['tipo'] == 'mapa' ? 'selected' : ''; ?>>Mapa / Plano</option>
                                    <option value="informativo" <?php echo $doc['tipo'] == 'informativo' ? 'selected' : ''; ?>>Informativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Número</label>
                                <input type="text" name="numero" class="form-control" value="<?php echo htmlspecialchars($doc['numero']); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha" class="form-control" value="<?php echo $doc['fecha']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-check mt-4">
                                <input type="checkbox" name="destacado" value="1" class="form-check-input" id="destacado" <?php echo $doc['destacado'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="destacado">Destacar en portada</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Resumen / Descripción</label>
                        <textarea name="resumen" class="form-control" rows="3"><?php echo htmlspecialchars($doc['resumen']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="1" <?php echo $doc['status'] == 1 ? 'selected' : ''; ?>>Publicado (visible al público)</option>
                            <option value="0" <?php echo $doc['status'] == 0 ? 'selected' : ''; ?>>Oculto (borrador)</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-file-pdf"></i> Archivo actual: 
                        <a href="/gasparmarcano-sitioweb/public/uploads/documentos/<?php echo $doc['archivo_pdf']; ?>" target="_blank"><?php echo $doc['archivo_pdf']; ?></a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cambios</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>