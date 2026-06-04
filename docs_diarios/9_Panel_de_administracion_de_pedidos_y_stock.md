# Informe de Desarrollo - Semana 8: Panel de Administración (Gestión de Pedidos)

Este documento detalla las implementaciones realizadas para el panel de administración enfocado en la gestión de pedidos globales y la lógica transaccional de restauración de inventario, cumpliendo con los objetivos de la Semana 8 del proyecto BLÜK.

---

## 1. Listado Global de Pedidos para Administración

- **Vista de Pedidos (`admin/orders/index.blade.php`)**:
    - Tabla administrativa paginada que reúne todos los pedidos realizados en la tienda.
    - Muestra ID de pedido, nombre del cliente, fecha de creación, importe total facturado y el estado de la compra.
    - Badges coloreados de forma intuitiva según el estado actual (pendiente, procesando, enviado, cancelado).
    - Enlace para ingresar a la vista detallada de cada orden.

---

## 2. Detalle de Pedido Administrativo

- **Vista de Detalle (`admin/orders/show.blade.php`)**:
    - Muestra los datos de contacto y envío del cliente.
    - Muestra el desglose de productos comprados, cantidad adquirida, precio histórico y subtotal.
    - Incorpora un panel lateral interactivo con un formulario para actualizar el estado del pedido a través de un selector (`select`).

---

## 3. Lógica Transaccional de Cancelación y Reposición de Stock

- **Controlador (`Admin\OrderController@update`)**:
    - Cuando el administrador cambia el estado del pedido a `cancelado`, el sistema verifica si el estado anterior no era ya `cancelado` (evitando reposiciones múltiples de stock).
    - Toda la lógica se envuelve en una transacción de base de datos (`DB::transaction`) para garantizar la consistencia.
    - Para cada línea de pedido (`order_items`), se incrementa el stock disponible del producto correspondiente utilizando el método `increment('stock', $item->quantity)`.
    - Solo si todas las reposiciones y la actualización de estado del pedido se completan con éxito, se ejecuta el Commit de la transacción.

---

## 4. Archivos Creados y Modificados

- **Controlador**: `App\Http\Controllers\Admin\OrderController` (métodos `index`, `show` y `update`).
- **Ruta de recursos en `routes/web.php`**:
    ```php
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('pedidos', OrderController::class)->only(['index', 'show', 'update']);
    });
    ```
- **Vistas**:
    - `resources/views/admin/orders/index.blade.php`
    - `resources/views/admin/orders/show.blade.php`
- **Tests**: `tests/Feature/AdminOrderControllerTest.php` (verifica la protección de accesos, actualización de estados y la correcta restauración de inventario de forma unitaria).
