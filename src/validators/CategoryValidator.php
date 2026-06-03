<?php
declare(strict_types=1);

class CategoryValidator
{
    /**
     * Valida los datos para la creación/actualización de una categoría
     * 
     * @param array $data Array con los datos 'name' y 'description'
     * @return array Array asociativo con 'valid' (bool), 'errors' (array) y 'data' sanitizado
     */
    public static function validate(array $data): array
    {
        $errors = [];
        
        $name = trim((string)($data['name'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));
        
        // Sanitización básica
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        
        // Validación de Nombre
        if ($name === '') {
            $errors['name'] = 'El nombre de la categoría es obligatorio';
        } elseif (strlen($name) > 50) {
            $errors['name'] = 'El nombre no puede exceder los 50 caracteres';
        }

        // Validación de Descripción
        if (strlen($description) > 255) {
            $errors['description'] = 'La descripción no puede exceder los 255 caracteres';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => [
                'name' => $name,
                'description' => $description
            ]
        ];
    }
}