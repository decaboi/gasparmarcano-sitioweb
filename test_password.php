<?php
// test_password.php - Ubicado en la raíz del proyecto
// Ruta: C:/xampp/htdocs/gasparmarcano-sitioweb/test_password.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔐 Diagnóstico de Login</h1>";

// Incluir la conexión a la base de datos
require_once __DIR__ . '/src/Core/Database.php';

$email = 'alcalde@gasparmarcano.gob.ve';
$password_ingresada = 'admin123'; // <-- La que usted va a escribir en el login

echo "<p>Buscando usuario: <strong>" . htmlspecialchars($email) . "</strong></p>";
echo "<p>Contraseña de prueba: <strong>" . $password_ingresada . "</strong></p>";
echo "<hr>";

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("❌ Error de conexión a la base de datos");
}

$stmt = $conn->prepare("SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    
    echo "<h3>📋 Datos del usuario encontrado:</h3>";
    echo "<ul>";
    echo "<li>ID: " . $usuario['id'] . "</li>";
    echo "<li>Nombre: " . htmlspecialchars($usuario['nombre']) . "</li>";
    echo "<li>Email: " . htmlspecialchars($usuario['email']) . "</li>";
    echo "<li>Rol: " . htmlspecialchars($usuario['rol']) . "</li>";
    echo "<li>Hash almacenado: <code>" . htmlspecialchars($usuario['password']) . "</code></li>";
    echo "<li>Longitud del hash: " . strlen($usuario['password']) . " caracteres</li>";
    echo "</ul>";
    
    if (password_verify($password_ingresada, $usuario['password'])) {
        echo "<p style='color:green; font-size:1.2rem;'>✅ <strong>CONTRASEÑA CORRECTA</strong></p>";
        echo "<p>Puede iniciar sesión en <a href='/admin/login.php'>/admin/login.php</a></p>";
    } else {
        echo "<p style='color:red; font-size:1.2rem;'>❌ <strong>CONTRASEÑA INCORRECTA</strong></p>";
        echo "<p>El hash almacenado NO coincide con la contraseña '$password_ingresada'.</p>";
        
        // Generar un nuevo hash correcto
        $nuevo_hash = password_hash('admin123', PASSWORD_DEFAULT);
        echo "<hr>";
        echo "<h3>🔧 Solución automática:</h3>";
        echo "<p>Ejecute este SQL en phpMyAdmin para resetear la contraseña:</p>";
        echo "<code style='display:block; background:#f0f0f0; padding:10px; margin:10px 0;'>";
        echo "UPDATE usuarios SET password = '" . $nuevo_hash . "' WHERE email = '" . $email . "';";
        echo "</code>";
    }
} else {
    echo "<p style='color:red;'>❌ Usuario NO encontrado con email: " . htmlspecialchars($email) . "</p>";
    echo "<p>Revise los usuarios existentes con esta consulta SQL:</p>";
    echo "<code style='display:block; background:#f0f0f0; padding:10px;'>SELECT id, nombre, email, rol FROM usuarios;</code>";
}

$conn->close();
?>