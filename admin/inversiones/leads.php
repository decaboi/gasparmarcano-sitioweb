<?php
require_once __DIR__ . '/../config/auth.php';
requerirRol('admin');

require_once __DIR__ . '/../../src/Core/Database.php';
$conn = (new Database())->getConnection();

// Marcar como leídos si es necesario
if (isset($_GET['marcar_leido'])) {
    $stmt = $conn->prepare("UPDATE leads_inversionistas SET leido = 1 WHERE id = ?");
    $stmt->bind_param("i", $_GET['marcar_leido']);
    $stmt->execute();
}

$sql = "SELECT l.*, i.titulo as inversion_titulo FROM leads_inversionistas l LEFT JOIN inversiones i ON l.inversion_id = i.id ORDER BY l.created_at DESC";
$leads = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Leads de Inversionistas - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Georama', sans-serif; background-color: #f5f5f5; }
        .sidebar { background-color: #0A2472; min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; }
        .sidebar a:hover, .sidebar a.active { background-color: #FFCD00; color: #0A2472; }
        .lead-no-leido { background-color: #fff3cd; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-0">
                <div class="text-center py-4">
                    <img src="/assets/img/escudo_municipio.png" alt="Escudo" height="60" class="bg-white rounded p-1">
                    <h6 class="mt-2 text-white">Gaspar Marcano</h6>
                </div>
                <hr>
                <nav>
                    <a href="../dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="../noticias/"><i class="bi bi-newspaper"></i> Noticias</a>
                    <a href="../eventos/"><i class="bi bi-calendar"></i> Eventos</a>
                    <a href="../turismo/"><i class="bi bi-tree"></i> Turismo</a>
                    <a href="index.php"><i class="bi bi-graph-up"></i> Inversiones</a>
                    <a href="leads.php" class="active"><i class="bi bi-envelope"></i> Leads</a>
                    <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Salir</a>
                </nav>
            </div>
            
            <div class="col-md-10 p-4">
                <h2><i class="bi bi-envelope"></i> Inversionistas Interesados</h2>
                <p class="text-muted">Personas y empresas que han solicitado información sobre oportunidades de inversión</p>
                
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>#</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>País</th><th>Oportunidad</th><th>Presupuesto</th><th>Fecha</th><th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($leads as $lead): ?>
                                <tr class="<?php echo $lead['leido'] ? '' : 'lead-no-leido'; ?>">
                                    <td><?php echo $lead['id']; ?></td>
                                    <td><?php echo htmlspecialchars($lead['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($lead['email']); ?></td>
                                    <td><?php echo htmlspecialchars($lead['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($lead['pais']); ?></td>
                                    <td><?php echo htmlspecialchars($lead['inversion_titulo'] ?? 'General'); ?></td>
                                    <td><?php echo htmlspecialchars($lead['presupuesto_estimado']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($lead['created_at'])); ?></td>
                                    <td>
                                        <a href="?marcar_leido=<?php echo $lead['id']; ?>" class="btn btn-sm btn-outline-success">Marcar leído</a>
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