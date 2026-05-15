<!-- ============================================== -->
<!-- FOOTER INSTITUCIONAL - MARCA GOBIERNO 2025     -->
<!-- Rutas corregidas para que funcionen desde /public -->
<!-- ============================================== -->
<footer class="site-footer mt-5 pt-4" style="background-color: #0A2472; color: #ffffff;">
    <div class="container">
        <!-- Fila 1: Información institucional y enlaces -->
        <div class="row g-4 py-4">
            
            <!-- Columna 1: Identidad municipal -->
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-3">
                    <img src="/gasparmarcano-sitioweb/public/assets/img/escudo_municipio.png" alt="Escudo Gaspar Marcano" height="60" class="bg-white rounded p-1 me-3" onerror="this.src='https://placehold.co/60x60/0A2472/FFFFFF?text=Escudo'">
                    <div>
                        <h6 class="text-white mb-0">Municipio Gaspar Marcano</h6>
                        <small class="text-white-50">Juan Griego - Nueva Esparta</small>
                    </div>
                </div>
                <p class="small text-white-50">
                    "Tierra de historia, mar y tradición. Comprometidos con las <strong>7 Transformaciones (7T)</strong> 
                    para el desarrollo turístico, económico y social de nuestro pueblo."
                </p>
                <!-- Botón SERIMUN (Ley de Simplificación de Trámites) -->
                <a href="https://sermun.gob.ve/ciudadano/login.html" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="btn btn-outline-light btn-sm"
                   aria-label="Portal del Contribuyente SERIMUN">
                   <i class="bi bi-box-arrow-up-right"></i> Portal del Contribuyente
                </a>
            </div>
            
            <!-- Columna 2: Enlaces oficiales (Ley de Infogobierno) -->
            <div class="col-md-4">
                <h6 class="text-uppercase mb-3 fw-bold" style="color: #FFCD00;">Enlaces Oficiales</h6>
                <ul class="list-unstyled">
                   <li class="mb-2"><a href="http://localhost/serimun/public/index.html#transparencia" class="text-white-50 text-decoration-none hover-link" target="_blank" rel="noopener noreferrer"><i class="bi bi-shield-check"></i> Transparencia (SERIMUN)</a></li>
                    <li class="mb-2"><a href="/gasparmarcano-sitioweb/public/noticias.php" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-newspaper"></i> Noticias y Boletines</a></li>
                    <li class="mb-2"><a href="/gasparmarcano-sitioweb/public/eventos.php" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-calendar"></i> Calendario de Eventos</a></li>
                    <li class="mb-2"><a href="/gasparmarcano-sitioweb/public/documentos.php" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-file-pdf"></i> Gacetas y Ordenanzas</a></li>
                    <li class="mb-2"><a href="/gasparmarcano-sitioweb/public/turismo.php" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-tree"></i> Portal Turístico</a></li>
                    <li class="mb-2"><a href="/gasparmarcano-sitioweb/public/inversionistas.php" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-graph-up"></i> Oportunidades de Inversión</a></li>
                    <li class="mb-2">
                     <a href="/gasparmarcano-sitioweb/public/mapa.php" class="text-white-50 text-decoration-none hover-link">
                       <i class="bi bi-map"></i> Mapa Turístico Interactivo
                </a>
</li>
           
                </ul>
            </div>
            
            <!-- Columna 3: Contacto y Plan 7T -->
            <div class="col-md-4">
                <h6 class="text-uppercase mb-3 fw-bold" style="color: #FFCD00;">Contacto y Atención</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-geo-alt-fill text-warning"></i> Calle Bolívar, Casa Municipal, Juan Griego</li>
                    <li class="mb-2"><i class="bi bi-telephone-fill text-warning"></i> +58 (295) 123-4567</li>
                    <li class="mb-2"><i class="bi bi-envelope-fill text-warning"></i> <a href="mailto:contacto@municipiogaspar.gob.ve" class="text-white-50">contacto@municipiogaspar.gob.ve</a></li>
                </ul>
                <div class="mt-3">
                    <h6 class="text-uppercase mb-2 fw-bold" style="color: #FFCD00; font-size: 0.7rem;">Plan de la Patria 7T</h6>
                    <p class="small text-white-50">
                        Alineados con las <strong>7 Transformaciones</strong> para la nueva era: Economía, Seguridad, Social, Política, Internacional, Ecológica y Comunicacional.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Línea divisoria -->
        <hr style="background-color: rgba(255,255,255,0.2);">
        
        <!-- Fila 2: Políticas y créditos -->
        <div class="row pb-3">
            <div class="col-md-6 text-center text-md-start small text-white-50">
                <i class="bi bi-c-circle"></i> <?php echo date('Y'); ?> Alcaldía del Municipio Gaspar Marcano.<br>
                Todos los derechos reservados. | 
                <a href="/gasparmarcano-sitioweb/public/politicas-privacidad.php" class="text-white-50 text-decoration-none">Políticas de Privacidad</a> | 
                <a href="/gasparmarcano-sitioweb/public/terminos-uso.php" class="text-white-50 text-decoration-none">Términos de Uso</a>
            </div>
            <div class="col-md-6 text-center text-md-end small text-white-50">
                Desarrollado bajo la <strong>Ley de Infogobierno</strong> y el <strong>Manual de Marca Gobierno 2025</strong>.<br>
                Optimizado para accesibilidad <strong>WCAG 2.1 (Nivel AA)</strong>.
            </div>
        </div>
        
        <!-- Fila 3: Acceso al CMS (Operadores y Alcalde) -->
        <div class="row pb-3">
            <div class="col-12 text-center">
                <a href="/gasparmarcano-sitioweb/admin/login.php" class="btn btn-outline-warning btn-sm" style="border-color: #FFCD00; color: #FFCD00;">
                    <i class="bi bi-shield-lock"></i> Área de Operadores y Alcalde
                </a>
                <small class="d-block text-white-50 mt-1">Acceso restringido al personal autorizado</small>
            </div>
        </div>
    </div>
</footer>

<!-- Estilos adicionales del footer -->
<style>
    .hover-link:hover {
        color: #FFCD00 !important;
        text-decoration: underline !important;
        transition: all 0.3s ease;
    }
    .site-footer a:focus-visible {
        outline: 2px solid #FFCD00;
        outline-offset: 2px;
        border-radius: 4px;
    }
    .btn-outline-light:hover {
        background-color: #FFCD00 !important;
        border-color: #FFCD00 !important;
        color: #0A2472 !important;
    }
    .btn-outline-warning:hover {
        background-color: #FFCD00 !important;
        border-color: #FFCD00 !important;
        color: #0A2472 !important;
    }
</style>

<!-- Widget del Chatbot con IA -->
<script src="/gasparmarcano-sitioweb/public/assets/js/chatbot.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>