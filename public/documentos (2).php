<?php
require_once __DIR__ . '/../src/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Buscador
$busqueda = $_GET['buscar'] ?? '';
$tipo_filtro = $_GET['tipo'] ?? '';

$sql = "SELECT * FROM documentos WHERE status = 1";
if ($busqueda) {
    $sql .= " AND (titulo LIKE '%$busqueda%' OR resumen LIKE '%$busqueda%' OR numero LIKE '%$busqueda%')";
}
if ($tipo_filtro) {
    $sql .= " AND tipo = '$tipo_filtro'";
}
$sql .= " ORDER BY destacado DESC, fecha DESC, created_at DESC";

$documentos = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

include '../src/Views/partials/header.php';
?>

<main>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4" style="color: #0A2472;">Documentos Oficiales</h1>
            <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
            <p class="lead">Gacetas, ordenanzas, decretos y documentos públicos del Municipio Gaspar Marcano</p>
        </div>
        
        <!-- Buscador y filtros -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <form method="GET" class="row g-2">
                    <div class="col-md-7">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por título, número o descripción..." value="<?php echo htmlspecialchars($busqueda); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="tipo" class="form-select">
                            <option value="">Todos los tipos</option>
                            <option value="gaceta" <?php echo $tipo_filtro == 'gaceta' ? 'selected' : ''; ?>>Gacetas</option>
                            <option value="ordenanza" <?php echo $tipo_filtro == 'ordenanza' ? 'selected' : ''; ?>>Ordenanzas</option>
                            <option value="decreto" <?php echo $tipo_filtro == 'decreto' ? 'selected' : ''; ?>>Decretos</option>
                            <option value="mapa" <?php echo $tipo_filtro == 'mapa' ? 'selected' : ''; ?>>Mapas y Planos</option>
                            <option value="informativo" <?php echo $tipo_filtro == 'informativo' ? 'selected' : ''; ?>>Informativos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Buscar</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if(empty($documentos)): ?>
        <div class="alert alert-info text-center">No hay documentos que coincidan con su búsqueda.</div>
        <?php else: ?>
        
        <!-- Resultados -->
        <div class="row g-4">
            <?php foreach($documentos as $doc): 
                $icono = match($doc['tipo']) {
                    'gaceta' => 'bi-newspaper',
                    'ordenanza' => 'bi-file-text',
                    'decreto' => 'bi-file-earmark',
                    'mapa' => 'bi-map',
                    default => 'bi-file-pdf'
                };
                $color = match($doc['tipo']) {
                    'gaceta' => 'danger',
                    'ordenanza' => 'primary',
                    'decreto' => 'success',
                    'mapa' => 'info',
                    default => 'secondary'
                };
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <i class="bi <?php echo $icono; ?> text-<?php echo $color; ?> fs-2"></i>
                            <?php if($doc['destacado']): ?>
                            <span class="badge bg-warning text-dark">★ Destacado</span>
                            <?php endif; ?>
                        </div>
                        <h6 class="card-title mt-2"><?php echo htmlspecialchars($doc['titulo']); ?></h6>
                        <p class="card-text small text-muted">
                            <i class="bi bi-hash"></i> <?php echo htmlspecialchars($doc['numero']); ?> | 
                            <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($doc['fecha'])); ?>
                        </p>
                        <p class="small"><?php echo htmlspecialchars(substr($doc['resumen'], 0, 100)); ?>...</p>
                        <a href="/gasparmarcano-sitioweb/public/uploads/documentos/<?php echo $doc['archivo_pdf']; ?>" class="btn btn-sm btn-<?php echo $color; ?>" target="_blank">
                            <i class="bi bi-download"></i> Descargar PDF
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../src/Views/partials/footer.php'; ?>