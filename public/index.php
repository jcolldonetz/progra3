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
$scriptDir  = str_replace('\\', '/', dirname($scriptName));

// Si la URL contiene el directorio del script, lo quitamos
if ($scriptDir !== '/' && strpos($path, $scriptDir) === 0) {
    $path = substr($path, strlen($scriptDir));
}

// Quitar /public si se cuela en el path porque el front controller está en /public
$path = preg_replace('#^/public#', '', $path);

// Quitar la base /progra3 cuando el sitio se sirve desde ese subdirectorio
$path = preg_replace('#^/progra3#', '', $path);

// asegurar formato
$path = '/' . ltrim((string)$path, '/');
if ($path === '//') {
    $path = '/';
}

//echo "Request: $method $path\n";

require __DIR__ . '/../src/routes.php';

?>