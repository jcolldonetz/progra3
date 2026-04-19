# Proyecto API de Items

Este proyecto es una pequeña API en PHP que gestiona un recurso `Item` usando rutas definidas manualmente y el ORM Eloquent de `illuminate/database`.

## Qué hace

- Inicializa el servidor desde `public/index.php`.
- Enruta peticiones en `src/routes.php`.
- Provee las siguientes rutas:
  - `GET /health` → devuelve el estado de salud en JSON.
  - `GET /` → mensaje indicando que la API funciona.
  - `GET /items` → devuelve todos los items registrados en JSON.
  - `GET /items/new` → muestra un formulario HTML para crear un nuevo item.
  - `POST /items` → recibe datos de un item, valida la entrada y crea el registro.

## Validación y modelo

- `src/models/Item.php`
  - Define el modelo Eloquent para la tabla `items`.
  - Campos rellenables: `name`, `qty`, `price`.
- `src/validators/ItemValidator.php`
  - Valida los datos de creación del item: `name`, `qty` y `price`.
  - Verifica presencia, formato, longitud, cantidad mayor a cero y precio con máximo 2 decimales.

## Tecnología usada

- PHP con enrutamiento manual.
- ORM Eloquent de `illuminate/database`.
- JSON como formato principal de respuesta.
- Formulario HTML simple en `src/views/items_form.php`.

## Reutilización

Las funciones de la API están diseñadas para ser reutilizables:

- **Controlador modular**: `src/controllers/ItemAPIController.php` encapsula la lógica de negocio (obtener y crear items), permitiendo su uso en diferentes rutas o contextos sin duplicar código.
- **Validación independiente**: `src/validators/ItemValidator.php` puede aplicarse a cualquier formulario o endpoint que maneje datos de items.
- **Modelo Eloquent**: `src/models/Item.php` es reutilizable en cualquier parte de la aplicación para interactuar con la base de datos de items.
- **Rutas flexibles**: Las rutas en `src/routes.php` pueden extenderse fácilmente para añadir nuevas funcionalidades (actualizar, eliminar items) reutilizando los mismos controladores y validadores.

Esto facilita la expansión del proyecto y mantiene el código DRY (Don't Repeat Yourself).
