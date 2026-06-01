<?php

require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../validators/ItemValidator.php';

class ItemAPIController
{
    /**
     * Obtener lista de items
     * GET /api/items?q=texto
     */
    public function index()
    {
        try {
            $filter = trim((string)($_GET['q'] ?? ''));
            $query = Item::query();

            if ($filter !== '') {
                $query->where('name', 'LIKE', '%' . $filter . '%');
            }

            $items = $query->get();
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
        $data = json_decode(file_get_contents('php://input'), true);

        if(json_last_error() !== JSON_ERROR_NONE) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'error' => 'Invalid JSON input'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            return;
        }

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

    /**
     * Obtener un item por ID
     * GET /api/items/{id}
     */
    public function show($id)
    {
        try {
            $item = Item::find($id);

            if (!$item) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(404);
                echo json_encode([
                    'ok' => false,
                    'error' => 'Item no encontrado'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                return;
            }

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode([
                'ok' => true,
                'item' => $item
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'error' => 'Error al obtener el item'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * Actualizar un item existente
     * PUT /api/items/{id}
     */
    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'error' => 'Invalid JSON input'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            return;
        }

        try {
            $item = Item::find($id);
            if (!$item) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(404);
                echo json_encode([
                    'ok' => false,
                    'error' => 'Item no encontrado'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                return;
            }

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

            $item->update($validation['data']);

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode([
                'ok' => true,
                'item' => $item
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'error' => 'Error al actualizar el item: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * Eliminar un item
     * DELETE /api/items/{id}
     */
    public function destroy($id)
    {
        try {
            $item = Item::find($id);
            if (!$item) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(404);
                echo json_encode([
                    'ok' => false,
                    'error' => 'Item no encontrado'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                return;
            }

            $item->delete();

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode([
                'ok' => true,
                'message' => 'Item eliminado correctamente'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'error' => 'Error al eliminar el item'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
    }
}
