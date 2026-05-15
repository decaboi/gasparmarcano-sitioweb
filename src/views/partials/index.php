<?php
// Front Controller básico
require_once __DIR__ . '/../src/models/contenidomodel.php';

$model = new ContenidoModel();
$noticias = $model->getUltimosPorCategoria("Noticias Destacadas", 4);
$turismo = $model->getUltimosPorCategoria("Atractivos Turísticos", 3);
$slider = $model->getSlider();

// Incluir el Header (con la nueva Marca Gobierno)
include '../src/views/partials/header.php';
?>

<main>
    <!-- ========================================== -->
    <!-- SLIDER DE NOTICIAS DESTACADAS              -->
    <!-- ========================================== -->
    <div class="container-fluid px-0 mb-5">
        <div id="sliderTurismo" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php $i = 0; foreach($slider as $slide): ?>
                <button type="button" data-bs-target="#sliderTurismo" data-bs-slide-to="<?php echo $i; ?>" class="<?php echo $i === 0 ? 'active' : ''; ?>" aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $i+1; ?>"></button>
                <?php $i++; endforeach; ?>
            </div>
            <div class="carousel-inner">
                <?php $active = 'active'; foreach($slider as $slide): ?>
                <div class="carousel-item <?php echo $active; $active = ''; ?>">
                    <?php if($slide['imagen_destacada']): ?>
                    <img src="/uploads/<?php echo $slide['imagen_destacada']; ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($slide['titulo']); ?>" style="height: 500px; object-fit: cover;">
                    <?php else: ?>
                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 500px; background: linear-gradient(135deg, #00247D, #0A2472);">
                        <h2 class="text-white">Juan Griego te espera</h2>
                    </div>
                    <?php endif; ?>
                    <div class="carousel-caption bg-dark bg-opacity-50 rounded p-3">
                        <h3 class="text-white"><?php echo htmlspecialchars($slide['titulo']); ?></h3>
                        <p class="text-white"><?php echo htmlspecialchars(substr($slide['resumen'], 0, 120)); ?>...</p>
                        <a href="/noticia.php?slug=<?php echo $slide['slug']; ?>" class="btn btn-warning">Leer más <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#sliderTurismo" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#sliderTurismo" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>

    <div class="container">
        <!-- ========================================== -->
        <!-- BIENVENIDA Y MENSAJE DEL ALCALDE           -->
        <!-- ========================================== -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold" style="color: #0A2472;">Bienvenidos a Juan Griego</h1>
                <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
                <p class="lead text-muted">"Tierra de historia, mar y tradición. Comprometidos con las 7 Transformaciones para el desarrollo de nuestro pueblo."</p>
                <p class="fw-semibold">— <span style="color: #DA291C;">Alcalde Bolivariano Yul Armas</span> —</p>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECCIÓN HISTORIA (Patrimonio Cultural)     -->
        <!-- ========================================== -->
        <div class="row align-items-center mb-5 g-4">
            <div class="col-md-6">
                <h2 style="color: #0A2472;">Historia Viva: Juan Griego</h2>
                <p>Fundada en <strong>1730</strong> por el corsario griego Juan Griego, esta ciudad es una de las más emblemáticas de la Isla de Margarita. Su <strong>Iglesia San Nicolás de Bari</strong> (siglo XVIII), sus casas coloniales y su bahía protegida la convierten en un destino único en el Caribe venezolano.</p>
                <p>Hoy, bajo el <strong>Plan de la Patria 7T</strong> y la <strong>Ley Orgánica de Turismo</strong>, nos reinventamos como un destino sostenible, respetando nuestras raíces y mirando al futuro.</p>
                <a href="/historia.php" class="btn btn-primary">Descubrir más <i class="bi bi-book"></i></a>
            </div>
            <div class="col-md-6">
              <img src="assets/img/iglesia.jpg" alt="Iglesia" class="img-fluid rounded shadow">
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECCIÓN TURISMO (Atractivos)              -->
        <!-- ========================================== -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-4" style="color: #0A2472;">Atractivos Turísticos</h2>
                <div class="row g-4">
                    <?php foreach($turismo as $item): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <?php if($item['imagen_destacada']): ?>
                            <img src="/uploads/<?php echo $item['imagen_destacada']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['titulo']); ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                            <div class="bg-secondary" style="height: 200px; background: linear-gradient(135deg, #FFCD00, #DA291C);"></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['titulo']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars(substr($item['resumen'], 0, 100)); ?>...</p>
                                <a href="/turismo.php?slug=<?php echo $item['slug']; ?>" class="btn btn-outline-primary btn-sm">Ver más <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="/turismo.php" class="btn btn-warning">Ver todos los atractivos <i class="bi bi-tree"></i></a>
                </div>
            </div>
            <div class="text-center mt-4">
    <a href="/gasparmarcano-sitioweb/public/turismo.php" class="btn btn-warning">Ver todos los atractivos</a>
    <a href="/gasparmarcano-sitioweb/public/mapa.php" class="btn btn-primary ms-2">
        <i class="bi bi-map"></i> Ver Mapa Interactivo
    </a>
</div>
        </div>

        <!-- ========================================== -->
        <!-- SECCIÓN INVERSIONES (Ley de Turismo + 7T)  -->
        <!-- ========================================== -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="bg-light p-5 rounded-4 text-center" style="background: linear-gradient(135deg, #F5F5F5, #E8ECF1);">
                    <i class="bi bi-graph-up" style="font-size: 3rem; color: #0A2472;"></i>
                    <h2 class="mt-3" style="color: #0A2472;">Oportunidades de Inversión</h2>
                    <p class="lead">Zonas de Desarrollo Turístico Especial (ZDTE), beneficios fiscales y acompañamiento institucional.</p>
                    <p>Alineado con la <strong>Ley Orgánica de Turismo</strong>, el <strong>Plan de la Patria 7T</strong> y la nueva <strong>Ley de Promoción de Inversiones</strong>.</p>
                    <a href="/public.php/inversionistas.php" class="btn btn-primary btn-lg mt-3">Quiero invertir en Gaspar Marcano <i class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECCIÓN NOTICIAS RECIENTES                -->
        <!-- ========================================== -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-4" style="color: #0A2472;">Últimas Noticias</h2>
                <div class="row g-4">
                    <?php foreach($noticias as $noticia): ?>
                    <div class="col-md-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <?php if($noticia['imagen_destacada']): ?>
                            <img src="/uploads/<?php echo $noticia['imagen_destacada']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>" style="height: 150px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <small class="text-muted"><i class="bi bi-calendar3"></i> <?php echo date('d/m/Y', strtotime($noticia['created_at'])); ?></small>
                                <h6 class="card-title mt-2"><?php echo htmlspecialchars($noticia['titulo']); ?></h6>
                                <a href="/noticia.php?slug=<?php echo $noticia['slug']; ?>" class="stretched-link text-primary">Leer noticia <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="/noticias.php" class="btn btn-outline-primary">Ver todas las noticias <i class="bi bi-newspaper"></i></a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../src/Views/partials/footer.php'; ?>