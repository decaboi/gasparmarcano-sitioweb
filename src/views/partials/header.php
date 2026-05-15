<!DOCTYPE html>
<html lang="es">

        <?php
// SEO dinámico
require_once __DIR__ . '/../../Core/SEOGenerator.php';
$seo = new SEOGenerator();

// Detectar página actual
$pagina_actual = basename($_SERVER['PHP_SELF'], '.php');
$titulo_seo = "Municipio Gaspar Marcano - Juan Griego";
$descripcion_seo = "Portal oficial del Municipio Gaspar Marcano. Turismo, inversiones, noticias y gestión institucional en Juan Griego, Isla de Margarita.";

switch($pagina_actual) {
    case 'index':
        $titulo_seo = "Inicio | Municipio Gaspar Marcano - Juan Griego";
        $descripcion_seo = "Bienvenidos al portal oficial del Municipio Gaspar Marcano. Conoce Juan Griego, sus playas, historia y oportunidades de inversión.";
        break;
    case 'historia':
        $titulo_seo = "Historia de Juan Griego | Municipio Gaspar Marcano";
        $descripcion_seo = "Conoce la historia de Juan Griego, fundado en 1730 por el corsario griego. Iglesia San Nicolás de Bari, patrimonio cultural de Margarita.";
        break;
    case 'turismo':
        $titulo_seo = "Turismo en Juan Griego | Playas, Iglesia, Cerro El Copey";
        $descripcion_seo = "Descubre los atractivos turísticos de Juan Griego: playas vírgenes, la Iglesia San Nicolás, gastronomía y más en la Isla de Margarita.";
        break;
    case 'inversionistas':
        $titulo_seo = "Inversiones en Gaspar Marcano | Oportunidades ZDTE";
        $descripcion_seo = "Oportunidades de inversión en Juan Griego. Zona de Desarrollo Turístico Especial, beneficios fiscales y acompañamiento institucional.";
        break;
}
?>

<?php echo $seo->generarMetaTags($titulo_seo, $descripcion_seo, 'website'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Portal Oficial del Municipio Gaspar Marcano - Juan Griego, Nueva Esparta. Turismo, inversiones, noticias y gestión institucional.">
    <meta name="author" content="Alcaldía del Municipio Gaspar Marcano">
    
    <title>Alcaldía Gaspar Marcano | Juan Griego - Nueva Esparta</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- TIPOGRAFÍA OFICIAL: GEORAMA (Manual de Marca Gobierno 2025) -->
    <link href="https://fonts.googleapis.com/css2?family=Georama:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Estilos personalizados (Cumplen con WCAG 2.1) -->
    <style>
        /* ============================================
           PALETA DE COLORES OFICIAL - MARCA GOBIERNO 2025
           Basada en la Bandera Nacional (Capítulo 5)
        ============================================ */
        :root {
            --amarillo-patria: #FFCD00;
            --azul-patria: #00247D;
            --rojo-patria: #DA291C;
            --blanco: #FFFFFF;
            --negro: #000000;
            --azul-gobierno: #0A2472;
            --gris-texto: #333333;
            --gris-claro: #F5F5F5;
        }
        
        * {
            font-family: 'Georama', sans-serif;
        }
        
        /* Jerarquía de texto según manual (página 28) */
        h1, .h1 {
            font-weight: 700;
            font-size: 2.5rem;
            line-height: 1.2;
            color: var(--azul-gobierno);
        }
        h2, .h2 {
            font-weight: 600;
            font-size: 1.8rem;
            line-height: 1.3;
            color: var(--azul-gobierno);
        }
        h3, .h3 {
            font-weight: 600;
            font-size: 1.5rem;
        }
        .lead {
            font-weight: 400;
            font-size: 1.2rem;
            line-height: 1.4;
        }
        body {
            font-weight: 400;
            font-size: 1rem;
            line-height: 1.5;
            color: var(--gris-texto);
        }
        .small-text, .text-small {
            font-weight: 300;
            font-size: 0.85rem;
        }
        
        /* Accesibilidad: Focus visible para navegación por teclado (WCAG 2.1) */
        a:focus-visible, button:focus-visible, .btn:focus-visible {
            outline: 3px solid var(--amarillo-patria);
            outline-offset: 2px;
            border-radius: 4px;
        }
        
        /* Botones institucionales */
        .btn-primary {
            background-color: var(--azul-gobierno);
            border-color: var(--azul-gobierno);
        }
        .btn-primary:hover {
            background-color: var(--azul-patria);
            border-color: var(--azul-patria);
        }
        .btn-warning {
            background-color: var(--amarillo-patria);
            border-color: var(--amarillo-patria);
            color: var(--azul-gobierno);
            font-weight: 600;
        }
        .btn-warning:hover {
            background-color: #e6b800;
            border-color: #e6b800;
        }
        
        /* Navegación principal */
        .navbar-gobierno {
            background-color: var(--azul-gobierno);
            border-bottom: 2px solid var(--amarillo-patria);
        }
        .navbar-gobierno .nav-link {
            color: var(--blanco) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .navbar-gobierno .nav-link:hover {
            color: var(--amarillo-patria) !important;
            transform: translateY(-1px);
        }
        .navbar-gobierno .nav-link.active {
            color: var(--amarillo-patria) !important;
            border-bottom: 2px solid var(--amarillo-patria);
        }
        
        /* Header institucional */
        .header-gobierno {
            background: linear-gradient(135deg, var(--azul-gobierno) 0%, var(--azul-patria) 100%);
        }
        
        /* Restricción: Tamaño mínimo del logo (manual página 11) */
        .marca-gobierno {
            min-width: 300px;
        }
        
        /* Restricción: No separar isotipo de texto */
        .unidad-visual {
            display: inline-block;
        }
    </style>
</head>
<body>

<!-- ============================================== -->
<!-- HEADER INSTITUCIONAL - MARCA GOBIERNO 2025      -->
<!-- Basado en Manual de Marca Capítulo 8.2 y 8.1   -->
<!-- Alcaldía Bolivariana de Gaspar Marcano         -->
<!-- Gestión: Alcalde Yul Armas                     -->
<!-- ============================================== -->
<div class="header-gobierno">
    <div class="container">
        <div class="row align-items-center py-3">
          <!-- Columna 1: Escudo del Municipio (MÁS GRANDE) -->
<div class="col-4 col-md-3 text-center text-md-start">
    <img src="assets/img/logo_header_marcano.png" 
         alt="Escudo del Municipio Gaspar Marcano" 
         class="img-fluid" 
         style="max-height: 120px; width: auto;">
</div>
            
            <!-- Columna 2: MARCA GOBIERNO (Unidad visual obligatoria - Capítulo 7) -->
            <div class="col-4 col-md-6 text-center marca-gobierno">
                <div class="unidad-visual">
                    <!-- Isotipo: Bandera Nacional (Forma ondulante - Capítulo 3.1) -->
                  
                    <!-- Logotipo textual (Capítulo 7.1)
                    <div class="text-white" style="line-height: 1.2;">
                        <small class="d-block" style="font-size: 0.7rem; letter-spacing: 1.5px;">GOBIERNO BOLIVARIANO DE</small>
                        <strong style="font-size: 1rem; letter-spacing: 2px;">VENEZUELA</strong>
                    </div> -->
                    <!-- Identificación de la Alcaldía -->
                   
                </div>
            </div>
            
           <!-- Columna 3: Logo SERIMUN (MÁS GRANDE) -->
<div class="col-4 col-md-3 text-center text-md-end">
    <a href="http://localhost/serimun/municipios/gaspar-marcano/" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
        <img src="assets/img/logo-serimun.png" 
             alt="SERIMUN - Portal del Contribuyente" 
             class="img-fluid" 
             style="max-height: 80px; width: auto;">
        <small class="d-block text-white-50" style="font-size: 0.65rem;">Portal Contribuyente</small>
    </a>
</div>>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MENÚ DE NAVEGACIÓN PRINCIPAL                   -->
<!-- ============================================== -->
<nav class="navbar navbar-expand-lg navbar-gobierno sticky-top">
    <div class="container">
        <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Menú de navegación">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
    <li class="nav-item"><a class="nav-link" href="/gasparmarcano-sitioweb/public/index.php">Inicio</a></li>
    <li class="nav-item"><a class="nav-link" href="/gasparmarcano-sitioweb/public/historia.php">Historia</a></li>
    <li class="nav-item"><a class="nav-link" href="/gasparmarcano-sitioweb/public/turismo.php">Turismo</a></li>
    <li class="nav-item"><a class="nav-link" href="/gasparmarcano-sitioweb/public/mapa.php">🗺️ Mapa Turístico</a></li>  <!-- NUEVO -->
    <li class="nav-item"><a class="nav-link" href="/gasparmarcano-sitioweb/public/noticias.php">Noticias</a></li>
    <li class="nav-item"><a class="nav-link" href="/gasparmarcano-sitioweb/public/eventos.php">Eventos</a></li>
    <li class="nav-item"><a class="nav-link" href="/gasparmarcano-sitioweb/public/inversionistas.php">Inversiones</a></li>
    <li class="nav-item"><a class="nav-link" href="/gasparmarcano-sitioweb/public/contacto.php">Contacto</a></li>
</ul>
        </div>
    </div>
</nav>

<!-- Espaciado para que el contenido no pegue al menú -->
<div class="mb-4"></div>