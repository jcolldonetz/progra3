<?php

require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../validators/ItemValidator.php';

class ItemAPIController
{
    /**
     * Obtener lista de items
     * GET /items
     */
    public function index()
    {
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
    }

    /**
     * Crear un nuevo item
     * POST /items
     */
    public function store()
    {
        // Validar donde capturar los datos: puede ser JSON o form-urlencoded
        $data = json_decode(file_get_contents('php://input'), true) ?? json_decode(json_encode($_POST), true);

        $validation = ItemValidator::validateCreate($data);

        if (!$validation['valid']) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'errors' => $validation['errors']
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            return;
        }

        // Guardar el item en la base de datos
        try {
            $item = Item::Create($validation['data']);
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'error' => 'Error al crear el item: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            return;
        }

        // Respuesta de éxito
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(201);
        echo json_encode([
            'ok' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s')
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
