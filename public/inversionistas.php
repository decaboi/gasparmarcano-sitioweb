<?php
require_once __DIR__ . '/../src/Core/Database.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT * FROM inversiones WHERE status = 1 ORDER BY destacada DESC, created_at DESC";
$inversiones = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

include '../src/Views/partials/header.php';
?>

<main>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4" style="color: #0A2472;">Oportunidades de Inversión</h1>
            <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
            <p class="lead">Descubra las mejores oportunidades para invertir en el Municipio Gaspar Marcano, Juan Griego</p>
            <p class="text-muted">Alineado con la <strong>Ley de Promoción de Inversiones</strong> y el <strong>Plan de la Patria 7T</strong></p>
        </div>
        
        <!-- Beneficios destacados -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card text-center h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-shield-check" style="font-size: 2rem; color: #0A2472;"></i>
                        <h6 class="mt-2">Seguridad Jurídica</h6>
                        <small class="text-muted">Marcos legales claros y estables</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-graph-up" style="font-size: 2rem; color: #0A2472;"></i>
                        <h6 class="mt-2">Alta Rentabilidad</h6>
                        <small class="text-muted">Proyecciones de crecimiento sostenido</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-gift" style="font-size: 2rem; color: #0A2472;"></i>
                        <h6 class="mt-2">Incentivos Fiscales</h6>
                        <small class="text-muted">ZDTE y exoneraciones especiales</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-headset" style="font-size: 2rem; color: #0A2472;"></i>
                        <h6 class="mt-2">Acompañamiento</h6>
                        <small class="text-muted">Ventanilla única del inversionista</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Listado de oportunidades -->
        <div class="row g-4">
            <?php foreach($inversiones as $inv): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <?php if($inv['imagen_destacada']): ?>
                    <img src="/uploads/<?php echo $inv['imagen_destacada']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($inv['titulo']); ?>" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <?php if($inv['destacada']): ?>
                        <span class="badge bg-warning text-dark mb-2">★ Destacada</span>
                        <?php endif; ?>
                        <span class="badge bg-info mb-2"><?php echo htmlspecialchars($inv['sector']); ?></span>
                        <h5 class="card-title"><?php echo htmlspecialchars($inv['titulo']); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlspecialchars(substr($inv['descripcion'], 0, 120)); ?>...</p>
                        <div class="mb-2">
                            <small class="text-success fw-bold">💰 <?php echo htmlspecialchars($inv['inversion_estimada']); ?></small>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalInversion<?php echo $inv['id']; ?>">
                            Ver detalles <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Modal de detalles -->
            <div class="modal fade" id="modalInversion<?php echo $inv['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #0A2472; color: white;">
                            <h5 class="modal-title"><?php echo htmlspecialchars($inv['titulo']); ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo nl2br(htmlspecialchars($inv['descripcion'])); ?></p>
                            <hr>
                            <h6>💰 Inversión estimada:</h6>
                            <p><?php echo htmlspecialchars($inv['inversion_estimada']); ?></p>
                            <h6>📈 Rentabilidad proyectada:</h6>
                            <p><?php echo htmlspecialchars($inv['rentabilidad_proyectada']); ?></p>
                            <h6>⏱️ Plazo de retorno:</h6>
                            <p><?php echo htmlspecialchars($inv['plazo_retorno']); ?></p>
                            <h6>🎁 Beneficios e incentivos:</h6>
                            <p><?php echo nl2br(htmlspecialchars($inv['beneficios'])); ?></p>
                            <h6>📋 Requisitos:</h6>
                            <p><?php echo nl2br(htmlspecialchars($inv['requisitos'])); ?></p>
                            <hr>
                            <div class="alert alert-info">
                                <i class="bi bi-telephone"></i> Contacto: <strong><?php echo htmlspecialchars($inv['contacto_referencia']); ?></strong>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLead" onclick="document.getElementById('lead_inversion_id').value = <?php echo $inv['id']; ?>">
                                <i class="bi bi-envelope"></i> Solicitar más información
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Modal para formulario de contacto -->
        <div class="modal fade" id="modalLead" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #0A2472; color: white;">
                        <h5 class="modal-title">Solicitar información</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="enviar-lead.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="inversion_id" id="lead_inversion_id" value="">
                            <div class="mb-3">
                                <label class="form-label">Nombre completo *</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo electrónico *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">País</label>
                                <input type="text" name="pais" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Presupuesto estimado</label>
                                <select name="presupuesto_estimado" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="Menos de 50.000 USD">Menos de 50.000 USD</option>
                                    <option value="50.000 - 250.000 USD">50.000 - 250.000 USD</option>
                                    <option value="250.000 - 1.000.000 USD">250.000 - 1.000.000 USD</option>
                                    <option value="Más de 1.000.000 USD">Más de 1.000.000 USD</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mensaje</label>
                                <textarea name="mensaje" class="form-control" rows="3" placeholder="¿Qué información necesita?"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../src/Views/partials/footer.php'; ?>