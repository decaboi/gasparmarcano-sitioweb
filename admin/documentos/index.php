<?php
require_once __DIR__ . '/../config/auth.php';
requerirLogin();

require_once __DIR__ . '/../../src/Core/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Verificar si la tabla existe, si no, crearla
$verificar_tabla = $conn->query("SHOW TABLES LIKE 'documentos'");
if ($verificar_tabla->num_rows == 0) {
    // Crear la tabla documentos
    $sql_crear = "CREATE TABLE IF NOT EXISTS documentos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(200) NOT NULL,
        tipo ENUM('gaceta', 'ordenanza', 'decreto', 'informativo', 'mapa') DEFAULT 'informativo',
        numero VARCHAR(50),
        fecha DATE,
        resumen TEXT,
        archivo_pdf VARCHAR(255) NOT NULL,
        downloads INT DEFAULT 0,
        destacado TINYINT DEFAULT 0,
        status TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql_crear);
}

$sql = "SELECT * FROM documentos ORDER BY fecha DESC, created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    $documentos = [];
    $error_sql = "Error en la consulta: " . $conn->error;
} else {
    $documentos = $result->fetch_all(MYSQLI_ASSOC);
    $error_sql = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Documentos - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Georama', sans-serif; background-color: #f5f5f5; }
        .sidebar { background-color: #0A2472; min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; }
        .sidebar a:hover, .sidebar a.active { background-color: #FFCD00; color: #0A2472; }
        .badge-gaceta { background-color: #dc3545; }
        .badge-ordenanza { background-color: #0d6efd; }
        .badge-decreto { background-color: #198754; }
        .badge-mapa { background-color: #0dcaf0; color: #000; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="text-center py-4">
                    <img src="/assets/img/escudo_municipio.png" alt="Escudo" height="60" class="bg-white rounded p-1">
                    <h6 class="mt-2 text-white">Gaspar Marcano</h6>
                </div>
                <hr class="bg-white-50">
                <nav>
                    <a href="../dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="../noticias/"><i class="bi bi-newspaper"></i> Noticias</a>
                    <a href="../eventos/"><i class="bi bi-calendar"></i> Eventos</a>
                    <a href="../turismo/"><i class="bi bi-tree"></i> Turismo</a>
                    <a href="../inversiones/"><i class="bi bi-graph-up"></i> Inversiones</a>
                    <a href="index.php" class="active"><i class="bi bi-file-pdf"></i> Documentos</a>
                    <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Salir</a>
                </nav>
            </div>
            
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-file-pdf"></i> Gestor de Documentos</h2>
                    <a href="subir.php" class="btn btn-primary"><i class="bi bi-cloud-upload"></i> Subir Documento</a>
                </div>
                
                <?php if($error_sql): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error_sql; ?>
                </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Archivo</th>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th>Número</th>
                                        <th>Fecha</th>
                                        <th>Descargas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($documentos)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-folder2-open fs-1 d-block"></i>
                                            No hay documentos cargados. 
                                            <a href="subir.php">Suba el primer documento</a>
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                    <?php foreach($documentos as $doc): ?>
                                    <tr>
                                        <td><?php echo $doc['id']; ?></td>
                                        <td><i class="bi bi-file-pdf text-danger fs-3"></i></td>
                                        <td><?php echo htmlspecialchars($doc['titulo']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $doc['tipo']; ?>">
                                                <?php echo ucfirst($doc['tipo']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($doc['numero']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($doc['fecha'])); ?></td>
                                        <td><?php echo $doc['downloads']; ?></td>
                                        <td>
                                            <a href="editar.php?id=<?php echo $doc['id']; ?>" class="text-primary me-2"><i class="bi bi-pencil"></i></a>
                                            <a href="eliminar.php?id=<?php echo $doc['id']; ?>" class="text-danger" onclick="return confirm('¿Eliminar este documento?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>