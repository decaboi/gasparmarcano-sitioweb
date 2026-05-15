<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../src/Core/Database.php';
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Por favor, complete todos los campos.';
    } else {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Buscar usuario por email
        $stmt = $conn->prepare("SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            
            // Verificar contraseña (BCRYPT)
            if (password_verify($password, $usuario['password'])) {
                // Login exitoso
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                
                // Registrar log de acceso
                $log_stmt = $conn->prepare("INSERT INTO logs_actividad (usuario_id, accion, ip) VALUES (?, 'login', ?)");
                $ip = $_SERVER['REMOTE_ADDR'];
                $log_stmt->bind_param("is", $usuario['id'], $ip);
                $log_stmt->execute();
                
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Contraseña incorrecta.';
            }
        } else {
            $error = 'Usuario no encontrado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al CMS - Alcaldía Gaspar Marcano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Georama:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Georama', sans-serif;
            background: linear-gradient(135deg, #0A2472 0%, #00247D 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            padding: 2rem;
            max-width: 400px;
            width: 90%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header img {
            max-height: 80px;
            margin-bottom: 1rem;
        }
        .btn-login {
            background-color: #0A2472;
            color: white;
            width: 100%;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            background-color: #00247D;
        }
        .alert {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="/assets/img/escudo_municipio.png" alt="Escudo Gaspar Marcano">
            <h4 class="mt-2" style="color: #0A2472;">Panel de Administración</h4>
            <small class="text-muted">Alcaldía del Municipio Gaspar Marcano</small>
            <hr>
            <p class="text-muted small">Gestión del Alcalde Bolivariano Yul Armas</p>
        </div>
        
        <?php if($error): ?>
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-login">Ingresar al sistema</button>
        </form>
        
        <div class="text-center mt-4">
            <small class="text-muted">
                Portal desarrollado bajo la Ley de Infogobierno<br>
                y el Plan de la Patria 7T
            </small>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>