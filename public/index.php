<?php
// Declarar tipos estrictos para mayor seguridad
declare(strict_types=1);
// Crear conexión a la DB MySQL usando el ORM Eloquent
require_once __DIR__ . '/../config/db.php';

// Método HTTP
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// URI pedida
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Quitar query string
$path = parse_url($requestUri, PHP_URL_PATH);

// Normalizar posibles bases
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir  = str_replace('\\', '/', dirname($scriptName)); // ej: /progra3/public
//echo "Script dir: $scriptDir\n"; // /progra3/public
//echo "Original path: $path\n"; // /progra3/public/health

// Si la URL contiene el directorio del script, lo quitamos
if ($scriptDir !== '/' && strpos($path, $scriptDir) === 0) {
    $path = substr($path, strlen($scriptDir));
} else {
    // quitar /public si aparece
    $path = preg_replace('#^/public#', '', $path);
    // quitar /progra3 si aparece
    $path = preg_replace('#^/progra3#', '', $path);
}

// asegurar formato
$path = '/' . ltrim((string)$path, '/');
if ($path === '//') {
    $path = '/';
}

//echo "Request: $method $path\n";

require __DIR__ . '/../src/routes.php';

?>