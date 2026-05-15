<?php
// admin/config/auth.php
session_start();

// Verificar si el usuario está logueado
function estaLogueado() {
    return isset($_SESSION['usuario_id']) && isset($_SESSION['usuario_nombre']);
}

// Verificar si tiene un rol específico
function tieneRol($rol_requerido) {
    if (!estaLogueado()) return false;
    return $_SESSION['usuario_rol'] === $rol_requerido;
}

// Redirigir si no está logueado
function requerirLogin() {
    if (!estaLogueado()) {
        header('Location: /admin/login.php');
        exit();
    }
}

// Redirigir si no tiene el rol adecuado
function requerirRol($rol_requerido) {
    requerirLogin();
    if (!tieneRol($rol_requerido)) {
        header('Location: /admin/dashboard.php?error=sin_permisos');
        exit();
    }
}

// Obtener datos del usuario actual
function usuarioActual() {
    if (!estaLogueado()) return null;
    return [
        'id' => $_SESSION['usuario_id'],
        'nombre' => $_SESSION['usuario_nombre'],
        'email' => $_SESSION['usuario_email'],
        'rol' => $_SESSION['usuario_rol']
    ];
}
?>