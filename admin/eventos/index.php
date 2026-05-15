<?php
require_once __DIR__ . '/../config/auth.php';
requerirLogin();

require_once __DIR__ . '/../../src/Core/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Obtener eventos (próximos primero)
$sql = "SELECT c.id, c.titulo, c.resumen, c.imagen_destacada, c.fecha_evento, c.created_at, c.status, cat.nombre as categoria 
        FROM contenido c 
        JOIN categorias cat ON c.categoria_id = cat.id 
        WHERE cat.tipo = 'evento'
        ORDER BY c.fecha_evento ASC";
$result = $conn->query($sql);
$eventos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Eventos - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Georama', sans-serif; background-color: #f5f5f5; }
        .sidebar { background-color: #0A2472; min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; }
        .sidebar a:hover, .sidebar a.active { background-color: #FFCD00; color: #0A2472; }
        .evento-proximo { border-left: 4px solid #28a745; }
        .evento-hoy { border-left: 4px solid #ffc107; background-color: #fff3cd; }
        .evento-pasado { border-left: 4px solid #6c757d; opacity: 0.7; }
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
                    <a href="index.php" class="active"><i class="bi bi-calendar"></i> Eventos</a>
                    <a href="../turismo/"><i class="bi bi-tree"></i> Turismo</a>
                    <a href="../documentos/"><i class="bi bi-file-pdf"></i> Documentos</a>
                    <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Salir</a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-calendar"></i> Calendario de Eventos</h2>
                    <a href="crear.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Evento</a>
                </div>
                
                <!-- Vista rápida: Hoy y próximos -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5><i class="bi bi-calendar-today"></i> Eventos Hoy</h5>
                                <h2 class="mb-0"><?php 
                                    $hoy = date('Y-m-d');
                                    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM contenido WHERE fecha_evento = ? AND status = 1");
                                    $stmt->bind_param("s", $hoy);
                                    $stmt->execute();
                                    echo $stmt->get_result()->fetch_assoc()['total'];
                                ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5><i class="bi bi-calendar-week"></i> Próximos 7 días</h5>
                                <h2 class="mb-0"><?php 
                                    $prox = date('Y-m-d', strtotime('+7 days'));
                                    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM contenido WHERE fecha_evento BETWEEN ? AND ? AND status = 1");
                                    $stmt->bind_param("ss", $hoy, $prox);
                                    $stmt->execute();
                                    echo $stmt->get_result()->fetch_assoc()['total'];
                                ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5><i class="bi bi-robot"></i> IA Recomienda</h5>
                                <small id="ia_recomienda">Analizando eventos...</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Listado de eventos -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>ID</th><th>Evento</th><th>Fecha</th><th>Lugar</th><th>Estado</th><th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($eventos as $evento): 
                                    $clase = '';
                                    $fecha_evento = $evento['fecha_evento'];
                                    if($fecha_evento == date('Y-m-d')) $clase = 'evento-hoy';
                                    elseif($fecha_evento < date('Y-m-d')) $clase = 'evento-pasado';
                                    elseif($fecha_evento > date('Y-m-d')) $clase = 'evento-proximo';
                                ?>
                                <tr class="<?php echo $clase; ?>">
                                    <td><?php echo $evento['id']; ?></td>
                                    <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($evento['fecha_evento'])); ?></td>
                                    <td><?php echo htmlspecialchars($evento['resumen']); ?></td>
                                    <td><?php echo $evento['status'] == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Borrador</span>'; ?></td>
                                    <td>
                                        <a href="editar.php?id=<?php echo $evento['id']; ?>" class="text-primary me-2"><i class="bi bi-pencil"></i></a>
                                        <a href="eliminar.php?id=<?php echo $evento['id']; ?>" class="text-danger" onclick="return confirm('¿Eliminar este evento?')"><i class="bi bi-trash"></i></a>
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
    
    <script>
        // Cargar recomendación IA
        fetch('ajax_ia_eventos.php', {method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({accion: 'recomendar'})})
            .then(res => res.json())
            .then(data => document.getElementById('ia_recomienda').innerHTML = data.recomendacion);
    </script>
</body>
</html>