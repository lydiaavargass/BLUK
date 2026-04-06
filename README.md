# BLÜK

BLÜK es el proyecto base del TFG/Proyecto Final de DAW: una tienda online académica desarrollada con Laravel 11, Blade, MySQL, Docker y Laravel Sail.

## MVP cerrado

### Sí entra
- autenticación con Laravel Breeze
- catálogo de productos
- detalle de producto
- carrito básico
- pedidos e historial
- panel interno simple para administración
- documentación técnica y memoria

### No entra
- pasarelas de pago reales
- sistema de roles avanzado
- cupones o fidelización
- reseñas, favoritos o recomendaciones
- marketplace o multitienda
- paneles complejos de analítica

## Stack inicial

- Laravel 11
- PHP 8.2+
- Laravel Sail
- MySQL 8.4
- Blade + Tailwind CSS
- Laravel Breeze

## Puesta en marcha

```bash
docker compose up -d
docker compose exec laravel.test php artisan migrate
docker compose exec laravel.test npm install
```

## Flujo de ramas

- `main`: entregas estables
- `develop`: integración del trabajo del equipo
- `feature/...`: desarrollo de tareas concretas

## Convención de commits

- `feat:` nueva funcionalidad
- `fix:` corrección de error
- `docs:` cambios de documentación
- `style:` ajustes visuales o de formato
- `refactor:` mejora interna sin cambiar comportamiento
- `config:` cambios de entorno o tooling

## Trabajo colaborativo

- cada tarea arranca desde `develop`
- no se trabaja directamente sobre `main`
- los cambios se revisan antes de mezclar
- las decisiones importantes se documentan en el repo

## Documentación útil

- `CONTRIBUTING.md`: normas simples de trabajo
- `resources/views/welcome.blade.php`: home provisional de la Semana 1
- `resources/views/layouts/navigation.blade.php`: navegación base del proyecto
