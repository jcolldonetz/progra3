<?php
    // Crear conexión a la DB MySQL usando el ORM Eloquent
    require __DIR__ . '/../vendor/autoload.php';
    use Illuminate\Database\Capsule\Manager as Capsule;
    $capsule = new Capsule;
    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'items_db',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);
    // Hacer que Capsule esté disponible globalmente
    $capsule->setAsGlobal();
    // Iniciar Eloquent ORM
    $capsule->bootEloquent();
?>