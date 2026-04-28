# Cierre de la Semana 3 y Correcciones de Seguridad

**Fecha:** 24 de Abril de 2026
**Autor:** Xavi / AI

## Resumen de la jornada
Hoy nos enfocamos en cerrar los cabos sueltos de la **Semana 3** del plan original, específicamente en la parte de seguridad, roles y middlewares, garantizando que el sistema base sea robusto antes de avanzar al catálogo de productos.

## Tareas completadas

### 1. Creación del Middleware de Administrador
- **Archivo:** `app/Http/Middleware/AdminMiddleware.php`
- **Descripción:** Se creó el middleware encargado de proteger las rutas del futuro panel de administración. Este middleware utiliza el método `isAdmin()` (previamente definido en el modelo `User`) para verificar el rol del usuario autenticado.
- **Comportamiento:** Si el usuario no es administrador, intercepta la petición y devuelve un error **403 (Acceso restringido)**.

### 2. Registro del Middleware en Bootstrap
- **Archivo:** `bootstrap/app.php`
- **Descripción:** En Laravel 11 ya no existe el `Kernel.php` para la web. Por lo tanto, se registró el alias `'admin'` directamente en la configuración de la aplicación usando `$middleware->alias()`.
- **Beneficio:** Ahora podemos proteger cualquier grupo de rutas simplemente usando `->middleware('admin')`.

### 3. Asignación automática de rol en el registro
- **Archivo:** `app/Http/Controllers/Auth/RegisteredUserController.php`
- **Descripción:** El flujo de registro por defecto de Laravel Breeze no contemplaba nuestro sistema de roles, lo que provocaba que los nuevos usuarios se crearan con un `role_id` nulo.
- **Solución implementada:** Se importó el modelo `Role` y se forzó la búsqueda del rol `cliente` mediante `firstOrFail()`. Ahora, al momento del registro, el usuario recibe automáticamente el ID de este rol, manteniendo la integridad referencial de la base de datos y preparando el terreno para el uso del carrito persistente.

## Próximos pasos
Con la base de roles y permisos completamente cerrada (Días 14 y 15 del plan), el proyecto está listo para iniciar la **Semana 4**, que consistirá en el desarrollo del Catálogo Público (Controladores de producto, vistas y filtrado).
