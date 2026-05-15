<?php
// config.produccion.php - Renombrar a config.php en producción
define('ENVIRONMENT', 'production');
define('SITE_URL', 'https://municipiogaspar.gob.ve');
define('SITE_NAME', 'Municipio Gaspar Marcano - Juan Griego');

// Base de datos (credenciales reales del hosting)
define('DB_HOST', 'localhost');
define('DB_NAME', 'municipio_gaspar');
define('DB_USER', 'usuario_real');
define('DB_PASS', 'contraseña_segura');

// Seguridad
define('SALT', 'clave_secreta_unica_generada_aleatoriamente');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
?>