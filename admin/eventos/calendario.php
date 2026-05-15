<?php
require_once __DIR__ . '/../config/auth.php';
requerirLogin();

require_once __DIR__ . '/../../src/Core/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Obtener eventos del mes actual
$primer_dia = date('Y-m-01');
$ultimo_dia = date('Y-m-t');
$stmt = $conn->prepare("SELECT id, titulo, fecha_evento, resumen FROM contenido WHERE fecha_evento BETWEEN ? AND ? AND status = 1 ORDER BY fecha_evento");
$stmt->bind_param("ss", $primer_dia, $ultimo_dia);
$stmt->execute();
$eventos_mes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Organizar eventos por día
$eventos_por_dia = [];
foreach ($eventos_mes as $ev) {
    $dia = date('j', strtotime($ev['fecha_evento']));
    $eventos_por_dia[$dia][] = $ev;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario Visual - CMS Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Georama', sans-serif; background-color: #f5f5f5; }
        .calendario-dia { border: 1px solid #ddd; min-height: 100px; background: white; transition: all 0.3s; }
        .calendario-dia:hover { background-color: #f0f8ff; transform: scale(1.02); }
        .dia-numero { background-color: #0A2472; color: white; display: inline-block; padding: 2px 10px; border-radius: 20px; margin-bottom: 5px; }
        .evento-item { font-size: 0.75rem; background-color: #FFCD00; color: #0A2472; padding: 2px 5px; border-radius: 3px; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar bg-dark text-white p-3" style="min-height: 100vh;">
                <h6 class="text-center">Gaspar Marcano</h6>
                <hr>
                <a href="../dashboard.php" class="text-white d-block">Dashboard</a>
                <a href="../noticias/" class="text-white d-block">Noticias</a>
                <a href="index.php" class="text-white d-block">Eventos</a>
                <a href="calendario.php" class="text-white d-block fw-bold">📅 Calendario</a>
                <a href="../logout.php" class="text-white d-block mt-3">Salir</a>
            </div>
            
            <div class="col-md-10 p-4">
                <h2><i class="bi bi-calendar3"></i> Calendario de Eventos</h2>
                <p class="text-muted">Vista mensual de actividades del municipio</p>
                
                <div class="row">
                    <?php for($dia = 1; $dia <= date('t'); $dia++): 
                        $fecha_actual = date('Y-m-') . str_pad($dia, 2, '0', STR_PAD_LEFT);
                        $es_hoy = ($fecha_actual == date('Y-m-d'));
                    ?>
                    <div class="col-md-3 col-sm-4 col-6 mb-3">
                        <div class="calendario-dia p-2 rounded <?php echo $es_hoy ? 'border-warning border-3' : ''; ?>">
                            <div class="dia-numero"><?php echo $dia; ?></div>
                            <?php if(isset($eventos_por_dia[$dia])): ?>
                                <?php foreach($eventos_por_dia[$dia] as $evento): ?>
                                    <div class="evento-item" title="<?php echo htmlspecialchars($evento['resumen']); ?>">
                                        <i class="bi bi-calendar-event"></i> <?php echo htmlspecialchars($evento['titulo']); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <small class="text-muted">Sin eventos</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>