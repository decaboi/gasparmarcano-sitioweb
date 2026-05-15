<?php
require_once __DIR__ . '/../src/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Eventos próximos (fecha >= hoy)
$hoy = date('Y-m-d');
$stmt = $conn->prepare("SELECT c.* FROM contenido c JOIN categorias cat ON c.categoria_id = cat.id WHERE cat.tipo = 'evento' AND c.status = 1 AND c.fecha_evento >= ? ORDER BY c.fecha_evento ASC");
$stmt->bind_param("s", $hoy);
$stmt->execute();
$eventos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

include '../src/Views/partials/header.php';
?>

<main>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4" style="color: #0A2472;">Calendario de Eventos</h1>
            <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
            <p class="lead">Próximas actividades, festividades y ferias en Juan Griego</p>
        </div>
        
        <?php if(empty($eventos)): ?>
        <div class="alert alert-info text-center">No hay eventos programados próximamente. ¡Vuelva pronto!</div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach($eventos as $evento): 
                $dias_restantes = (strtotime($evento['fecha_evento']) - strtotime($hoy)) / 86400;
                $clase = $dias_restantes <= 3 ? 'border-warning' : 'border-primary';
            ?>
            <div class="col-md-6">
                <div class="card mb-3 border-<?php echo $clase; ?> shadow-sm">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="text-center me-3" style="min-width: 70px;">
                                <div class="bg-primary text-white rounded p-2">
                                    <div class="h4 mb-0"><?php echo date('d', strtotime($evento['fecha_evento'])); ?></div>
                                    <div class="small"><?php echo ucfirst(substr(date('M', strtotime($evento['fecha_evento'])), 0, 3)); ?></div>
                                </div>
                            </div>
                            <div>
                                <h5 class="card-title"><?php echo htmlspecialchars($evento['titulo']); ?></h5>
                                <p class="card-text text-muted small">
                                    <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($evento['resumen']); ?>
                                </p>
                                <?php if($dias_restantes == 0): ?>
                                <span class="badge bg-danger">¡HOY MISMO!</span>
                                <?php elseif($dias_restantes == 1): ?>
                                <span class="badge bg-warning text-dark">MAÑANA</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Faltan <?php echo ceil($dias_restantes); ?> días</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../src/Views/partials/footer.php'; ?>