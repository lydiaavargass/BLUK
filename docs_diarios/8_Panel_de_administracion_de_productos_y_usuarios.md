# Informe de Desarrollo - Semana 7: Panel de Administración (Productos y Usuarios)

Este documento detalla las implementaciones realizadas para estructurar el panel de administración y desarrollar la gestión de productos y usuarios, cumpliendo con los objetivos de la Semana 7 del proyecto BLÜK.

---

## 1. Diseño y Estructura del Panel Admin

- **Layout Administrativo (`layouts/admin.blade.php`)**: Creación de una plantilla específica para la zona de administración. Incluye:
    - Barra lateral de navegación con accesos directos al Dashboard, Productos, Pedidos y Usuarios.
    - Menú superior con la información del administrador y botón de logout.
    - Lógica visual en Blade para resaltar el elemento activo de la barra lateral según la ruta actual.
- **Ruta de Acceso**: Configuración de un grupo de rutas bajo el prefijo `/admin` protegido por el middleware `auth` y por nuestro middleware personalizado `App\Http\Middleware\AdminMiddleware`.

---

## 2. Gestión de Productos (CRUD Completo)

Se implementó la lógica de administración en `App\Http\Controllers\Admin\ProductController` y sus respectivas vistas:

- **Listado de Productos (`admin/products/index.blade.php`)**:
    - Tabla paginada que lista todos los productos con datos clave (imagen, nombre, precio, stock, estado de activación).
    - Botones de acción directos para crear, editar o desactivar.
- **Formularios de Creación y Edición (`admin/products/create.blade.php` y `admin/products/edit.blade.php`)**:
    - Campos detallados para nombre, descripción, precio, stock, categoría y subida de imágenes.
    - Validaciones robustas implementadas en `App\Http\Requests\StoreProductRequest` y `UpdateProductRequest` (campos obligatorios, precios positivos, stock entero, subida de archivos imagen válidos).
- **Desactivación Lógica**: En lugar de eliminar físicamente los productos (lo que corrompería las relaciones de pedidos históricos), implementamos un borrado lógico actualizando la columna `is_active` a `false`.

---

## 3. Gestión y Visualización de Usuarios

- **Vista de Usuarios (`admin/users/index.blade.php`)**:
    - Listado paginado de todos los usuarios registrados en el sistema.
    - Identificación visual de roles mediante badges de colores (ej. verde para `admin` y azul para `cliente`).
    - Protección estricta: solo los usuarios autenticados con rol de administrador pueden acceder a esta información.

---

## 4. Archivos Creados y Modificados

- **Controlador**: `App\Http\Controllers\Admin\ProductController`
- **Request de Validación**: `App\Http\Requests\StoreProductRequest`
- **Vistas**:
    - `resources/views/layouts/admin.blade.php`
    - `resources/views/admin/products/index.blade.php`
    - `resources/views/admin/products/create.blade.php`
    - `resources/views/admin/products/edit.blade.php`
    - `resources/views/admin/users/index.blade.php`
- **Rutas en `routes/web.php`**:
    ```php
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('productos', ProductController::class);
        Route::get('usuarios', [UserController::class, 'index'])->name('users.index');
    });
    ```
