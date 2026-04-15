<?php
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

    // Ruta para mostrar el formulario de nuevo item
    if ($method === 'GET' && $path === '/items/new') {
        // Mostrar el formulario HTML para crear un nuevo item
        require __DIR__ . '/views/items_form.php';
        exit;
    }
    
    // Captura de submit de form de items
    if ($method === 'POST' && $path === '/items') {
        require __DIR__ . '/validators/ItemValidator.php';
        
        // Validar los datos del POST
        $validation = ItemValidator::validateCreate($_POST);
        
        if (!$validation['valid']) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'errors' => $validation['errors']
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }
        
        // Aquí podrías guardar el item en una base de datos o archivo
        // Por ahora solo respondemos con lo recibido
        
        // Respuesta de éxito
        $data = $validation['data'];
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(201);
        echo json_encode([
            'ok' => true,
            'item' => [
                'name' => $data['name'],
                'qty'  => $data['qty']
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

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