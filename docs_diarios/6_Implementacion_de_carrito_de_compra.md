# Informe de Desarrollo - Semana 5: Carrito de Compra

Este documento detalla las implementaciones realizadas para completar la fase de gestión del carrito de compras, cumpliendo con todos los objetivos y tareas de la Semana 5 del proyecto BLÜK.

## 1. Módulo del Carrito (Invitados y Autenticados)
- **Controlador Dual (`CartController`)**: Implementación del controlador con lógica específica para diferenciar entre usuarios invitados (guardado en sesión) y usuarios autenticados (persistencia en la base de datos).
    - `index`: Muestra el contenido del carrito sumando subtotales y total.
    - `store`: Añade un producto con validación de stock máximo disponible.
    - `update`: Actualiza la cantidad con validaciones de stock.
    - `destroy`: Elimina un producto específico del carrito.

## 2. Persistencia en Base de Datos
- **Carrito Persistente**: Para los usuarios logueados se consultan y persisten los cambios directamente en las tablas `carts` y `cart_items` en la base de datos MySQL, asegurando que el carrito no se pierda al cerrar sesión.

## 3. Sincronización al Iniciar Sesión
- **Event Listener (`SyncCartOnLogin`)**: Creación de un listener que escucha el evento `Login` de Laravel. Al iniciar sesión, los ítems guardados en la sesión (como invitado) se fusionan automáticamente con el carrito de la base de datos del usuario, sin exceder el stock disponible. Se ha registrado en el `AppServiceProvider`.

## 4. Frontend y Vistas
- **Vista del Carrito (`cart/index.blade.php`)**: Creación de una plantilla Blade que muestra una tabla completa de ítems, precios, cantidades actualizables, subtotales y el total final.
- **Ficha de Producto (`show.blade.php`)**: Actualización del botón de "Añadir al carrito" a un formulario real con selector de cantidad.
- **Navegación (`store.blade.php`)**: Integración del enlace al "Carrito" en la barra de navegación del layout público.
