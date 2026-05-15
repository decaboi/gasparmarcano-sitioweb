<?php
// Front Controller básico
require_once __DIR__ . '/../src/models/contenidomodel.php';

$model = new ContenidoModel();
$noticias = $model->getUltimosPorCategoria("Noticias Destacadas", 4);
$turismo = $model->getUltimosPorCategoria("Atractivos Turísticos", 3);
$slider = $model->getSlider();

// Incluir el Header (Cumple con la normativa de Símbolos Patrios)
include '../src/views/partials/header.php';
?>

<main>
    <!-- SLIDER INICIO - Adaptado a la imagen de Juangriego -->
    <div id="sliderTurismo" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php $active = 'active'; foreach($slider as $slide): ?>
            <div class="carousel-item <?php echo $active; $active = ''; ?>">
                <img src="/uploads/<?php echo $slide['imagen_destacada']; ?>" class="d-block w-100" alt="<?php echo $slide['titulo']; ?>" style="height: 500px; object-fit: cover;">
                <div class="carousel-caption bg-dark bg-opacity-50 rounded">
                    <h5><?php echo $slide['titulo']; ?></h5>
                    <p><?php echo substr($slide['resumen'], 0, 100); ?>...</p>
                    <a href="/noticia/<?php echo $slide['slug']; ?>" class="btn btn-primary">Leer más</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#sliderTurismo" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#sliderTurismo" data-bs-slide="prev">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <!-- Sección de Historia (Ley de Turismo y Plan 7T) -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="text-primary">Juan Griego: Historia Viva</h2>
                <p>Fundada en 1730 por el corsario Juan Griego, esta ciudad es patrimonio del oriente venezolano. Hoy, bajo el Plan de la Patria 7T, nos reinventamos como un destino de clase mundial, respetando nuestras raíces y mirando al futuro.</p>
                <a href="/historia" class="btn btn-outline-secondary">Descubrir +</a>
            </div>
            <div class="col-md-6">
                <img src="uploads/assets/img/iglesia-san-juan-evangelista-juangriego.jpg" class="img-fluid rounded shadow" alt="Iglesia San Juan Evangelista">
            </div>
        </div>

        <div class="text-center mt-4">
    <a href="/gasparmarcano-sitioweb/public/turismo.php" class="btn btn-outline-primary">Ver todos los atractivos <i class="bi bi-arrow-right"></i></a>
    <a href="/gasparmarcano-sitioweb/public/mapa.php" class="btn btn-primary ms-2">
        <i class="bi bi-map"></i> Ver Mapa Interactivo
    </a>
</div>
    </div>

    <!-- Módulo de Inversiones (Atraer capitales) -->
    <div class="bg-light p-5 text-center">
        <div class="container">
            <h3>Oportunidades de Inversión</h3>
            <p>Zonas turísticas especiales, beneficios fiscales y acompañamiento institucional. Alineado con la <strong>Ley de Promoción de Inversiones</strong> y el nuevo régimen económico de las 7T.</p>
            <a href="/inversionistas" class="btn btn-success btn-lg">Quiero Invertir en Gaspar Marcano</a>
        </div>
    </div>
</main>

<?php include '../src/views/partials/footer.php'; ?>