<?php
include '../src/Views/partials/header.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $asunto = trim($_POST['asunto'] ?? '');
    $mensaje_texto = trim($_POST['mensaje'] ?? '');
    
    if ($nombre && $email && $mensaje_texto) {
        // Aquí se enviaría correo (simulado por ahora)
        $mensaje = '<div class="alert alert-success">✅ Mensaje enviado exitosamente. Nos comunicaremos pronto.</div>';
    } else {
        $mensaje = '<div class="alert alert-danger">❌ Por favor complete todos los campos.</div>';
    }
}
?>

<main>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 text-center" style="color: #0A2472;">Contacto</h1>
                <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
                <p class="text-center lead">Comuníquese con la Alcaldía del Municipio Gaspar Marcano</p>
                
                <?php echo $mensaje; ?>
                
                <div class="row mt-5">
                    <div class="col-md-5">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5><i class="bi bi-geo-alt" style="color: #0A2472;"></i> Dirección</h5>
                                <p>Calle Bolívar, Casa Municipal<br>Juan Griego, Municipio Gaspar Marcano<br>Isla de Margarita, Nueva Esparta</p>
                                
                                <h5><i class="bi bi-telephone" style="color: #0A2472;"></i> Teléfono</h5>
                                <p>+58 (295) 123-4567</p>
                                
                                <h5><i class="bi bi-envelope" style="color: #0A2472;"></i> Correo</h5>
                                <p>contacto@municipiogaspar.gob.ve</p>
                                
                                <h5><i class="bi bi-clock" style="color: #0A2472;"></i> Horario</h5>
                                <p>Lunes a Viernes: 8:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre completo *</label>
                                        <input type="text" name="nombre" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Correo electrónico *</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Asunto</label>
                                        <input type="text" name="asunto" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mensaje *</label>
                                        <textarea name="mensaje" class="form-control" rows="5" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enviar mensaje <i class="bi bi-send"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../src/Views/partials/footer.php'; ?>