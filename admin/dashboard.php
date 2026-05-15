<?php
require_once __DIR__ . '/config/auth.php';
requerirLogin();

require_once __DIR__ . '/../src/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Estadísticas generales
$stats = [];

// Total de noticias
$result = $conn->query("SELECT COUNT(*) as total FROM contenido c JOIN categorias cat ON c.categoria_id = cat.id WHERE cat.tipo = 'noticia'");
$stats['noticias'] = $result->fetch_assoc()['total'];

// Total de eventos próximos
$hoy = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM contenido c JOIN categorias cat ON c.categoria_id = cat.id WHERE cat.tipo = 'evento' AND c.fecha_evento >= ?");
$stmt->bind_param("s", $hoy);
$stmt->execute();
$stats['eventos'] = $stmt->get_result()->fetch_assoc()['total'];

// Total de atractivos turísticos
$result = $conn->query("SELECT COUNT(*) as total FROM contenido c JOIN categorias cat ON c.categoria_id = cat.id WHERE cat.tipo = 'turismo'");
$stats['turismo'] = $result->fetch_assoc()['total'];

// Total de oportunidades de inversión
$result = $conn->query("SELECT COUNT(*) as total FROM inversiones WHERE status = 1");
$stats['inversiones'] = $result->fetch_assoc()['total'];

// Total de documentos
$result = $conn->query("SELECT COUNT(*) as total FROM documentos WHERE status = 1");
$stats['documentos'] = $result->fetch_assoc()['total'];

// Visitas (simulado - para implementar después con analytics real)
$stats['visitas_hoy'] = rand(120, 350);
$stats['visitas_mes'] = rand(2500, 5000);

// IA Predictiva: Recomendaciones basadas en datos
$ia_recomendaciones = [];

if ($stats['noticias'] < 5) {
    $ia_recomendaciones[] = "📰 Hay pocas noticias publicadas. Se recomienda crear al menos 5 noticias para mantener informada a la ciudadanía.";
}
if ($stats['eventos'] == 0) {
    $ia_recomendaciones[] = "📅 No hay eventos próximos. Considere agregar actividades del calendario municipal.";
}
if ($stats['inversiones'] == 0) {
    $ia_recomendaciones[] = "💰 No hay oportunidades de inversión activas. Esto puede limitar la atracción de capitales.";
}
if ($stats['documentos'] < 3) {
    $ia_recomendaciones[] = "📄 Pocos documentos públicos. La transparencia es clave según la Ley de Infogobierno.";
}

$usuario = usuarioActual();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Georama', sans-serif; background-color: #f5f5f5; }
        .sidebar { background-color: #0A2472; min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; }
        .sidebar a:hover, .sidebar a.active { background-color: #FFCD00; color: #0A2472; }
        .card-stats { border: none; border-radius: 12px; transition: transform 0.3s; }
        .card-stats:hover { transform: translateY(-5px); }
        .ia-card { background: linear-gradient(135deg, #0A2472 0%, #00247D 100%); color: white; border-radius: 16px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="text-center py-4">
                    <img src="/gasparmarcano-sitioweb/public/assets/img/escudo_municipio.png" alt="Escudo" height="60" class="bg-white rounded p-1" onerror="this.src='https://placehold.co/60x60/0A2472/FFFFFF?text=E'">
                    <h6 class="mt-2 text-white">Gaspar Marcano</h6>
                    <small class="text-white-50">Alcalde Yul Armas</small>
                </div>
                <hr class="bg-white-50">
                <nav>
                    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="noticias/"><i class="bi bi-newspaper"></i> Noticias</a>
                    <a href="eventos/"><i class="bi bi-calendar"></i> Eventos</a>
                    <a href="turismo/"><i class="bi bi-tree"></i> Turismo</a>
                    <a href="inversiones/"><i class="bi bi-graph-up"></i> Inversiones</a>
                    <a href="documentos/"><i class="bi bi-file-pdf"></i> Documentos</a>
                    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Salir</a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <!-- Top Navbar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">Panel de Control</h2>
                        <small class="text-muted">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?> (<?php echo ucfirst($usuario['rol']); ?>)</small>
                    </div>
                    <div>
                        <span class="badge bg-primary p-2"><?php echo date('d/m/Y H:i'); ?></span>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="card card-stats bg-primary text-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Noticias</h6>
                                    <h2 class="mb-0"><?php echo $stats['noticias']; ?></h2>
                                    <small>Publicadas</small>
                                </div>
                                <i class="bi bi-newspaper" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats bg-success text-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Eventos</h6>
                                    <h2 class="mb-0"><?php echo $stats['eventos']; ?></h2>
                                    <small>Próximos</small>
                                </div>
                                <i class="bi bi-calendar" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats bg-warning text-dark p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Turismo</h6>
                                    <h2 class="mb-0"><?php echo $stats['turismo']; ?></h2>
                                    <small>Atractivos</small>
                                </div>
                                <i class="bi bi-tree" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats bg-info text-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Documentos</h6>
                                    <h2 class="mb-0"><?php echo $stats['documentos']; ?></h2>
                                    <small>Subidos</small>
                                </div>
                                <i class="bi bi-file-pdf" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <strong><i class="bi bi-graph-up"></i> Estadísticas de Contenido</strong>
                            </div>
                            <div class="card-body">
                                <canvas id="statsChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <strong><i class="bi bi-robot"></i> IA Predictiva - Recomendaciones</strong>
                            </div>
                            <div class="card-body">
                                <?php if(empty($ia_recomendaciones)): ?>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i> ¡Excelente trabajo! El sitio tiene contenido balanceado. Sigue así.
                                </div>
                                <div class="text-center mt-3">
                                    <i class="bi bi-star-fill text-warning fs-1"></i>
                                    <p class="mt-2">El municipio está en el camino correcto según el Plan de la Patria 7T.</p>
                                </div>
                                <?php else: ?>
                                <ul class="list-unstyled">
                                    <?php foreach($ia_recomendaciones as $rec): ?>
                                    <li class="mb-3"><i class="bi bi-lightbulb text-warning"></i> <?php echo $rec; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actividad reciente -->
                <div class="card">
                    <div class="card-header bg-white">
                        <strong><i class="bi bi-clock-history"></i> Actividad reciente</strong>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info text-center mb-0">
                            <i class="bi bi-info-circle"></i> Próximamente: registro de actividad de usuarios y estadísticas de visitas.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const ctx = document.getElementById('statsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Noticias', 'Eventos', 'Turismo', 'Inversiones', 'Documentos'],
                datasets: [{
                    label: 'Cantidad',
                    data: [<?php echo $stats['noticias']; ?>, <?php echo $stats['eventos']; ?>, <?php echo $stats['turismo']; ?>, <?php echo $stats['inversiones']; ?>, <?php echo $stats['documentos']; ?>],
                    backgroundColor: ['#0A2472', '#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                    borderWidth: 0,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    </script>
</body>
</html>