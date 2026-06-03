<?php

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../validators/CategoryValidator.php';

class CategoryAPIController
{
    private function jsonResponse($data, $code = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function index()
    {
        try {
            $categories = Category::all();
            $this->jsonResponse(['ok' => true, 'categories' => $categories]);
        } catch (Exception $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'Error al obtener categorías'], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                $this->jsonResponse(['ok' => false, 'error' => 'Categoría no encontrada'], 404);
            }
            $this->jsonResponse(['ok' => true, 'category' => $category]);
        } catch (Exception $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'Error al obtener la categoría'], 500);
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $validation = CategoryValidator::validate($data);

        if (!$validation['valid']) {
            $this->jsonResponse(['ok' => false, 'errors' => $validation['errors']], 400);
        }

        try {
            $category = Category::create($validation['data']);
            $this->jsonResponse(['ok' => true, 'category' => $category], 201);
        } catch (Exception $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'Error al crear la categoría'], 500);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $category = Category::find($id);
            if (!$category) {
                $this->jsonResponse(['ok' => false, 'error' => 'Categoría no encontrada'], 404);
            }

            $validation = CategoryValidator::validate($data);
            if (!$validation['valid']) {
                $this->jsonResponse(['ok' => false, 'errors' => $validation['errors']], 400);
            }

            $category->update($validation['data']);
            $this->jsonResponse(['ok' => true, 'category' => $category]);
        } catch (Exception $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'Error al actualizar'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                $this->jsonResponse(['ok' => false, 'error' => 'Categoría no encontrada'], 404);
            }
            
            // Opcional: Validar si tiene ítems antes de borrar
            if ($category->items()->count() > 0) {
                $this->jsonResponse(['ok' => false, 'error' => 'No se puede eliminar una categoría con ítems asociados'], 400);
            }

            $category->delete();
            $this->jsonResponse(['ok' => true, 'message' => 'Categoría eliminada']);
        } catch (Exception $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'Error al eliminar'], 500);
        }
    }

    /**
     * Obtener ítems de una categoría específica
     * GET /api/categories/{id}/items
     */
    public function getItems($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                $this->jsonResponse(['ok' => false, 'error' => 'Categoría no encontrada'], 404);
            }

            $items = $category->items; // Uso de la relación hasMany definida en el modelo
            $this->jsonResponse([
                'ok' => true, 
                'category' => $category->name,
                'items' => $items
            ]);
        } catch (Exception $e) {
            $this->jsonResponse(['ok' => false, 'error' => 'Error al obtener los ítems'], 500);
        }
    }
}