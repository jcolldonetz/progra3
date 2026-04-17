<?php
    include __DIR__ . '/models/item.php';

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
    
    // Devolver lista de items (ejemplo simple, sin paginación ni filtros)
    if ($method === 'GET' && $path === '/items') {
        try {
            $items = Item::all();
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode([
                'ok' => true,
                'items' => $items
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'error' => 'Error al obtener los items'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        exit;
    }

    // Captura de submit de form de items
    if ($method === 'POST' && $path === '/items') {
        require __DIR__ . '/validators/ItemValidator.php';
        // Validar donde capturar los datos: puede ser JSON o form-urlencoded
        $data = json_decode(file_get_contents('php://input'), true) ?? json_decode(json_encode($_POST), true);
var_dump($data);
        $validation = ItemValidator::validateCreate($data);
        
        if (!$validation['valid']) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'errors' => $validation['errors']
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }
        
        // Guardar el item en una base de datos
        try {
            $item = Item::Create($validation['data']);
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'error' => 'Error al crear el item: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }

        // Respuesta de éxito
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(201);
        echo json_encode([
            'ok' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'qty'  => $item->qty,
                'price' => $item->price,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s')
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