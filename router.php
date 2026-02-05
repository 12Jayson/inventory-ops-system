<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si el archivo existe físicamente (CSS, Imágenes, exito.php), sírvelo normal
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Si no existe, manda la ruta al index.php como si fuera una variable
$_GET['url'] = ltrim($uri, '/');
require_once __DIR__ . '/index.php';