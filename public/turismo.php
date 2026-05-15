<?php
require_once __DIR__ . '/../src/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT c.* FROM contenido c JOIN categorias cat ON c.categoria_id = cat.id WHERE cat.tipo = 'turismo' AND c.status = 1 ORDER BY c.created_at DESC";
$atracciones = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

include '../src/Views/partials/header.php';
?>

<main>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4" style="color: #0A2472;">Atractivos Turísticos</h1>
            <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
            <p class="lead">Descubra los lugares mágicos de Juan Griego y el Municipio Gaspar Marcano</p>
        </div>
        
        <div class="row g-4">
            <?php foreach($atracciones as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <?php if($item['imagen_destacada']): ?>
                    <img src="/uploads/<?php echo $item['imagen_destacada']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['titulo']); ?>" style="height: 220px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($item['titulo']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($item['resumen'], 0, 120)); ?>...</p>
                        <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTurismo<?php echo $item['id']; ?>">Ver más <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <a href="/gasparmarcano-sitioweb/public/mapa.php" class="btn btn-primary btn-lg">
            <i class="bi bi-map"></i> Ver Mapa Interactivo de Atractivos
            </a>
</div>
            
            <!-- Modal -->
            <div class="modal fade" id="modalTurismo<?php echo $item['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #0A2472; color: white;">
                            <h5 class="modal-title"><?php echo htmlspecialchars($item['titulo']); ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?php if($item['imagen_destacada']): ?>
                            <img src="/uploads/<?php echo $item['imagen_destacada']; ?>" class="img-fluid rounded mb-3" alt="<?php echo htmlspecialchars($item['titulo']); ?>">
                            <?php endif; ?>
                            <?php echo $item['cuerpo']; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include '../src/Views/partials/footer.php'; ?>