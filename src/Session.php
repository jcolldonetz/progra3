<?php

// Definir una clase Session con métodos estáticos para manejar la sesión de usuario
class Session
{
    // Iniciar la sesión
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }   
    }

    // Establecer un valor en la sesión
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    // Obtener un valor de la sesión
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    // Verificar si un valor existe en la sesión
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    // Eliminar un valor de la sesión
    public static function clear($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    // Eliminar todos los valores de la sesión
    public static function clearAll()
    {
        self::start();
        $_SESSION = [];
    }

    // Destruir la sesión
    public static function destroy()
    {
        self::start();
        
        // Limpiar el array en memoria
        $_SESSION = [];

        // Borrar la cookie del navegador
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    // Regenerar el ID de sesión para evitar colisiones
    public static function regenerate()
    {
        self::start();
        session_regenerate_id(true);
    }
}

?>