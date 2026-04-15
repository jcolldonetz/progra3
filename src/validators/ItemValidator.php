<?php
declare(strict_types=1);

class ItemValidator
{
    /**
     * Valida los datos para la creación de un item
     * 
     * @param array $data Array con los datos 'name' y 'qty'
     * @return array Array asociativo con 'valid' (bool) y 'errors' (array)
     */
    public static function validateCreate(array $data): array
    {
        $errors = [];
        
        // Extraer y sanitizar datos
        $name = $data['name'] ?? '';
        $qty = $data['qty'] ?? 0;
        
        // Sanitizar inputs
        $name = trim($name);
        $qty = (int)trim((string)$qty);
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        
        // Aplicar reglas de validación
        $errors = self::validateName($name, $errors);
        $errors = self::validateQuantity($qty, $errors);
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => [
                'name' => $name,
                'qty' => $qty
            ]
        ];
    }
    
    /**
     * Valida el campo name
     * 
     * @param string $name
     * @param array $errors
     * @return array Errores actualizados
     */
    private static function validateName(string $name, array $errors): array
    {
        if ($name === '') {
            $errors['name'] = 'Name is required';
            return $errors;
        }
        
        if (strlen($name) > 50) {
            $errors['name'] = 'Name must be less than 50 characters';
        }
        
        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $name)) {
            $errors['name'] = 'Name can only contain letters, numbers and spaces';
        }
        
        return $errors;
    }
    
    /**
     * Valida el campo qty (cantidad)
     * 
     * @param int $qty
     * @param array $errors
     * @return array Errores actualizados
     */
    private static function validateQuantity(int $qty, array $errors): array
    {
        if ($qty <= 0) {
            $errors['qty'] = 'Quantity must be greater than zero';
            return $errors;
        }
        
        if ($qty > 1000) {
            $errors['qty'] = 'Quantity must be less than 1000';
        }
        
        return $errors;
    }
}
?>
