<?php
    // Carga el archivo que define el modelo Item y sus métodos de acceso a datos.
    include __DIR__ . '/models/item.php';
    // Carga el controlador que contiene la lógica de negocio para las rutas de items.
    include __DIR__ . '/controllers/ItemAPIController.php';

    // Definir las rutas de la API
    // Ruta de prueba
    if ($method === 'GET' && $path === '/health') {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
    
        echo json_encode([
            'status'      => 'ok',
            'timestamp'   => date('Y-m-d H:i:s'),
            'php_version' => phpversion(),
            'server'      => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
        exit;
    }
    
    // Ruta base opcional
    if ($method === 'GET' && $path === '/') {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
    
        echo json_encode([
            'message' => 'API funcionando',
            'health'  => '/health'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
        exit;
    }

    // Ruta para mostrar la lista de items
    if ($method === 'GET' && $path === '/items') {
        // Mostrar la lista de items en HTML
        require __DIR__ . '/views/items_ui.html';
        exit;
    }
    
    // Devolver lista de items (ejemplo simple, sin paginación ni filtros)
    if ($method === 'GET' && $path === '/api/items') {
        $controller = new ItemAPIController();
        $controller->index();
        exit;
    }

    // Captura de submit de form de items
    if ($method === 'POST' && $path === '/api/items') {
        $controller = new ItemAPIController();
        $controller->store();
        exit;
    }

    // No encontrada
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(404);
    echo json_encode([
        'error' => 'Not Found',
        'path'  => $path
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>