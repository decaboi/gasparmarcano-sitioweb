<?php
require_once __DIR__ . '/../src/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Paginación simple
$pagina = $_GET['pagina'] ?? 1;
$por_pagina = 9;
$offset = ($pagina - 1) * $por_pagina;

// Contar total
$count_sql = "SELECT COUNT(*) as total FROM contenido c JOIN categorias cat ON c.categoria_id = cat.id WHERE cat.tipo = 'noticia' AND c.status = 1";
$total_result = $conn->query($count_sql);
$total = $total_result->fetch_assoc()['total'];
$total_paginas = ceil($total / $por_pagina);

// Obtener noticias
$sql = "SELECT c.*, cat.nombre as categoria 
        FROM contenido c 
        JOIN categorias cat ON c.categoria_id = cat.id 
        WHERE cat.tipo = 'noticia' AND c.status = 1 
        ORDER BY c.created_at DESC 
        LIMIT $offset, $por_pagina";
$noticias = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

include '../src/Views/partials/header.php';
?>

<main>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4" style="color: #0A2472;">Noticias del Municipio</h1>
            <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
            <p class="lead">Información oficial de la Alcaldía del Municipio Gaspar Marcano</p>
        </div>
        
        <div class="row g-4">
            <?php foreach($noticias as $noticia): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <?php if($noticia['imagen_destacada']): ?>
                    <img src="/uploads/<?php echo $noticia['imagen_destacada']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <small class="text-muted"><i class="bi bi-calendar3"></i> <?php echo date('d/m/Y', strtotime($noticia['created_at'])); ?></small>
                        <h5 class="card-title mt-2"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($noticia['resumen'], 0, 100)); ?>...</p>
                        <a href="noticia.php?slug=<?php echo $noticia['slug']; ?>" class="btn btn-primary btn-sm">Leer más <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Paginación -->
        <?php if($total_paginas > 1): ?>
        <nav class="mt-5">
            <ul class="pagination justify-content-center">
                <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                    <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</main>

<?php include '../src/Views/partials/footer.php'; ?>