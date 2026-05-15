<?php
require_once __DIR__ . '/../config/auth.php';
requerirLogin();

require_once __DIR__ . '/../../src/Core/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Obtener todos los atractivos turísticos
$sql = "SELECT c.id, c.titulo, c.resumen, c.imagen_destacada, c.created_at, c.status, cat.nombre as categoria 
        FROM contenido c 
        JOIN categorias cat ON c.categoria_id = cat.id 
        WHERE cat.tipo = 'turismo'
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);
$atracciones = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Atractivos Turísticos - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Georama', sans-serif; background-color: #f5f5f5; }
        .sidebar { background-color: #0A2472; min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; }
        .sidebar a:hover, .sidebar a.active { background-color: #FFCD00; color: #0A2472; }
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
                    <a href="index.php" class="active"><i class="bi bi-tree"></i> Turismo</a>
                    <a href="../documentos/"><i class="bi bi-file-pdf"></i> Documentos</a>
                    <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Salir</a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-tree"></i> Atractivos Turísticos</h2>
                    <a href="crear.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Atractivo</a>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>ID</th><th>Imagen</th><th>Nombre</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($atracciones as $item): ?>
                                <tr>
                                    <td><?php echo $item['id']; ?></td>
                                    <td>
                                        <?php if($item['imagen_destacada']): ?>
                                            <img src="/uploads/<?php echo $item['imagen_destacada']; ?>" width="50" height="40" style="object-fit: cover;">
                                        <?php else: ?>
                                            <i class="bi bi-image text-muted"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($item['titulo']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($item['created_at'])); ?></td>
                                    <td><?php echo $item['status'] == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?></td>
                                    <td>
                                        <a href="editar.php?id=<?php echo $item['id']; ?>" class="text-primary me-2"><i class="bi bi-pencil"></i></a>
                                        <a href="eliminar.php?id=<?php echo $item['id']; ?>" class="text-danger" onclick="return confirm('¿Eliminar este atractivo?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>