<?php
require_once __DIR__ . '/../src/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Obtener documentos publicados
$sql = "SELECT * FROM documentos WHERE status = 1 ORDER BY destacado DESC, fecha DESC";
$documentos = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

// Agrupar por tipo
$gacetas = array_filter($documentos, fn($d) => $d['tipo'] == 'gaceta');
$ordenanzas = array_filter($documentos, fn($d) => $d['tipo'] == 'ordenanza');
$decretos = array_filter($documentos, fn($d) => $d['tipo'] == 'decreto');
$mapas = array_filter($documentos, fn($d) => $d['tipo'] == 'mapa');
$informativos = array_filter($documentos, fn($d) => $d['tipo'] == 'informativo');

include '../src/Views/partials/header.php';
?>

<main>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4" style="color: #0A2472;">Documentos Oficiales</h1>
            <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
            <p class="lead">Gacetas, ordenanzas, decretos y documentos públicos del Municipio Gaspar Marcano</p>
            <p class="text-muted">Transparencia y acceso a la información - Ley de Infogobierno</p>
        </div>
        
        <!-- Buscador -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="input-group">
                    <input type="text" id="buscador" class="form-control" placeholder="Buscar documento...">
                    <button class="btn btn-primary" onclick="buscarDocumento()"><i class="bi bi-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        
        <?php if(empty($documentos)): ?>
        <div class="alert alert-info text-center">No hay documentos disponibles en este momento.</div>
        <?php else: ?>
        
        <!-- Gacetas -->
        <?php if(!empty($gacetas)): ?>
        <div class="mb-5">
            <h3 class="border-bottom pb-2" style="border-color: #dc3545 !important;"><i class="bi bi-newspaper text-danger"></i> Gacetas Municipales</h3>
            <div class="row g-3 mt-2">
                <?php foreach($gacetas as $doc): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-file-pdf-fill text-danger fs-2 float-end"></i>
                            <h6 class="card-title"><?php echo htmlspecialchars($doc['titulo']); ?></h6>
                            <p class="card-text small text-muted">
                                <i class="bi bi-hash"></i> <?php echo $doc['numero']; ?> | 
                                <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($doc['fecha'])); ?>
                            </p>
                            <p class="small"><?php echo htmlspecialchars(substr($doc['resumen'], 0, 80)); ?>...</p>
                            <a href="/uploads/documentos/<?php echo $doc['archivo_pdf']; ?>" class="btn btn-sm btn-danger" target="_blank">
                                <i class="bi bi-download"></i> Descargar PDF
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Ordenanzas -->
        <?php if(!empty($ordenanzas)): ?>
        <div class="mb-5">
            <h3 class="border-bottom pb-2" style="border-color: #0d6efd !important;"><i class="bi bi-file-text text-primary"></i> Ordenanzas</h3>
            <div class="row g-3 mt-2">
                <?php foreach($ordenanzas as $doc): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-file-pdf-fill text-danger fs-2 float-end"></i>
                            <h6 class="card-title"><?php echo htmlspecialchars($doc['titulo']); ?></h6>
                            <p class="card-text small text-muted">
                                <i class="bi bi-hash"></i> <?php echo $doc['numero']; ?> | 
                                <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($doc['fecha'])); ?>
                            </p>
                            <a href="/uploads/documentos/<?php echo $doc['archivo_pdf']; ?>" class="btn btn-sm btn-primary" target="_blank">
                                <i class="bi bi-download"></i> Descargar PDF
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Decretos -->
        <?php if(!empty($decretos)): ?>
        <div class="mb-5">
            <h3 class="border-bottom pb-2" style="border-color: #198754 !important;"><i class="bi bi-file-earmark text-success"></i> Decretos</h3>
            <div class="row g-3 mt-2">
                <?php foreach($decretos as $doc): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-file-pdf-fill text-danger fs-2 float-end"></i>
                            <h6 class="card-title"><?php echo htmlspecialchars($doc['titulo']); ?></h6>
                            <p class="card-text small text-muted">
                                <i class="bi bi-hash"></i> <?php echo $doc['numero']; ?> | 
                                <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($doc['fecha'])); ?>
                            </p>
                            <a href="/uploads/documentos/<?php echo $doc['archivo_pdf']; ?>" class="btn btn-sm btn-success" target="_blank">
                                <i class="bi bi-download"></i> Descargar PDF
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Mapas -->
        <?php if(!empty($mapas)): ?>
        <div class="mb-5">
            <h3 class="border-bottom pb-2" style="border-color: #0dcaf0 !important;"><i class="bi bi-map text-info"></i> Mapas y Planos</h3>
            <div class="row g-3 mt-2">
                <?php foreach($mapas as $doc): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-file-pdf-fill text-danger fs-2 float-end"></i>
                            <h6 class="card-title"><?php echo htmlspecialchars($doc['titulo']); ?></h6>
                            <a href="/uploads/documentos/<?php echo $doc['archivo_pdf']; ?>" class="btn btn-sm btn-info" target="_blank">
                                <i class="bi bi-download"></i> Ver mapa
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php endif; ?>
    </div>
</main>

<script>
function buscarDocumento() {
    const busqueda = document.getElementById('buscador').value.toLowerCase();
    const tarjetas = document.querySelectorAll('.card');
    tarjetas.forEach(tarjeta => {
        const titulo = tarjeta.querySelector('.card-title')?.innerText.toLowerCase() || '';
        tarjeta.parentElement.parentElement.style.display = titulo.includes(busqueda) ? '' : 'none';
    });
}
</script>

<?php include '../src/Views/partials/footer.php'; ?>