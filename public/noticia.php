<?php
require_once __DIR__ . '/../src/Core/Database.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header('Location: noticias.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT c.*, cat.nombre as categoria FROM contenido c JOIN categorias cat ON c.categoria_id = cat.id WHERE c.slug = ? AND c.status = 1");
$stmt->bind_param("s", $slug);
$stmt->execute();
$noticia = $stmt->get_result()->fetch_assoc();

if (!$noticia) {
    header('Location: noticias.php');
    exit();
}

include '../src/Views/partials/header.php';
?>

<main>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="noticias.php">Noticias</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($noticia['titulo']); ?></li>
                    </ol>
                </nav>
                
                <h1 class="display-5" style="color: #0A2472;"><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
                <div class="text-muted mb-4">
                    <i class="bi bi-calendar3"></i> <?php echo date('d/m/Y', strtotime($noticia['created_at'])); ?>
                    <span class="mx-2">|</span>
                    <i class="bi bi-folder"></i> <?php echo $noticia['categoria']; ?>
                </div>
                
                <?php if($noticia['imagen_destacada']): ?>
                <img src="/uploads/<?php echo $noticia['imagen_destacada']; ?>" class="img-fluid rounded shadow mb-4" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                <?php endif; ?>
                
                <div class="contenido-noticia">
                    <?php echo $noticia['cuerpo']; ?>
                </div>
                
                <hr class="my-4">
                <div class="d-flex justify-content-between">
                    <a href="noticias.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Volver a noticias</a>
                    <div>
                        <span class="text-muted">Compartir: </span>
                        <a href="#" class="text-primary me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-info me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-success"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .contenido-noticia img { max-width: 100%; height: auto; margin: 20px 0; border-radius: 8px; }
    .contenido-noticia h2, .contenido-noticia h3 { color: #0A2472; margin-top: 25px; }
    .contenido-noticia p { line-height: 1.8; }
</style>

<?php include '../src/Views/partials/footer.php'; ?>