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
    elseif ($method === 'GET' && $path === '/items') {
        // Mostrar la lista de items en HTML
        require __DIR__ . '/views/items_ui.html';
        exit;
    }
    
    // Devolver lista de items (ejemplo simple, sin paginación ni filtros)
    elseif ($method === 'GET' && $path === '/api/items') {
        $controller = new ItemAPIController();
        $controller->index();
        exit;
    }
    // Obtener un item por ID
    elseif ($method === 'GET' && preg_match('#^/api/items/(\d+)$#', $path, $matches)) {
        $controller = new ItemAPIController();
        $controller->show((int)$matches[1]);
        exit;
    }

    // Actualizar un item
    elseif ($method === 'PUT' && preg_match('#^/api/items/(\d+)$#', $path, $matches)) {
        $controller = new ItemAPIController();
        $controller->update((int)$matches[1]);
        exit;
    }

    // Eliminar un item
    elseif ($method === 'DELETE' && preg_match('#^/api/items/(\d+)$#', $path, $matches)) {
        $controller = new ItemAPIController();
        $controller->destroy((int)$matches[1]);
        exit;
    }
    // Captura de submit de form de items
    elseif ($method === 'POST' && $path === '/api/items') {
        $controller = new ItemAPIController();
        $controller->store();
        exit;
    }

    // Demostrar manejo de sesión
    elseif ($method === 'GET' && $path === '/session') {
        require_once __DIR__ . '/../src/Session.php';
        
        // Regenerar sesión para evitar colisiones con otras sesiones (opcional)
        Session::regenerate();
        
        //var_dump(session_status());
        // var_dump($_SESSION);
        Session::start();
        // var_dump(session_status());
        // Session::destroy();
        // var_dump(session_status());
        // var_dump($_SESSION);
        // Session::start();
        // var_dump($_SESSION);
        Session::set('test', 'Hello, Session!');
        //Session::set('user', 'Javi');
        var_dump($_SESSION);
        // Session::clear('test');
        // var_dump($_SESSION);

        // Session::destroy();
        // var_dump($_SESSION);
    }

    // Demostrar manejo de cookies
    elseif ($method === 'GET' && $path === '/cookies') {
        // Establecer una cookie de ejemplo
        setcookie('test_cookie', 'Hello, Cookies!', time() + 3600, '/');
        
        // Mostrar las cookies recibidas
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode([
            'cookies' => $_COOKIE
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