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

## Resumen

En resumen, el proyecto es una API básica para crear y listar items, con validación de los datos de entrada y persistencia en base de datos mediante Eloquent.
