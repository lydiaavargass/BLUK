# Informe de Desarrollo - Semana 6: Pedidos del Cliente

Este documento detalla las implementaciones realizadas para completar la fase de gestión de pedidos del cliente, cumpliendo con todos los objetivos y tareas de la Semana 6 del proyecto BLÜK.

## 1. Checkout (Vista Resumen)

- **Vista de confirmación (`orders/checkout.blade.php`)**: Creación de una vista intermedia donde el usuario revisa el resumen completo de su compra (productos, cantidades, precios, subtotales y total) antes de confirmar el pedido.
- **Validación de carrito vacío**: Si el usuario intenta acceder al checkout con el carrito vacío, se redirige automáticamente al carrito con un mensaje de error.

## 2. Creación de Pedidos

- **Controlador (`OrderController`)**: Implementación del controlador con lógica completa para el flujo de compra:
    - `checkout`: Muestra el resumen pre-confirmación.
    - `store`: Crea el pedido dentro de una transacción de base de datos con `lockForUpdate()` para evitar condiciones de carrera en el stock.
    - `index`: Historial paginado de pedidos del usuario.
    - `show`: Detalle individual de un pedido con verificación de propiedad.

## 3. Gestión de Stock y Vaciado de Carrito

- **Descuento de stock**: Al confirmar el pedido, se valida y descuenta el stock de cada producto usando `decrement()` dentro de la transacción.
- **Precio histórico**: Se guarda el precio del producto en el momento de la compra en `order_items.price`, no el precio actual, evitando inconsistencias si el precio cambia después.
- **Vaciado del carrito**: Tras crear el pedido exitosamente, se eliminan todos los `cart_items` del carrito del usuario.

## 4. Historial de Pedidos

- **Vista de historial (`orders/index.blade.php`)**: Tabla paginada con número de pedido, fecha, total y estado.
- **Badges por estado**: Cada estado del pedido se muestra con un badge de color diferenciado:
    - `pendiente` → amarillo
    - `procesando` → azul
    - `enviado` → verde
    - `cancelado` → rojo

## 5. Detalle de Pedido

- **Vista de detalle (`orders/show.blade.php`)**: Información completa del pedido (fecha, estado, total) junto con la tabla de productos comprados mostrando precios históricos y subtotales.
- **Protección de acceso**: Se verifica que el pedido pertenezca al usuario autenticado (`abort(403)` si no coincide).

## 6. Integración con la Navegación

- **Botón de checkout en el carrito**: Se añadió el botón "Confirmar pedido" al pie del carrito. Para los invitados se muestra "Inicia sesión para comprar".
- **Enlace "Mis Pedidos" en la navegación**: Visible solo para usuarios autenticados, con indicador visual de página activa.

## 7. Rutas Implementadas

- `GET /checkout` → Vista resumen de checkout (auth)
- `POST /checkout` → Crear pedido (auth)
- `GET /pedidos` → Historial de pedidos (auth)
- `GET /pedidos/{order}` → Detalle de un pedido (auth)
