# Plan y registro de pruebas de aceptación

## Estrategia de pruebas y aseguramiento de calidad

El plan de pruebas del e-commerce **BLÜK** se diseñó con el fin de verificar que todas las funcionalidades y reglas de negocio respondan de manera correcta ante diversos escenarios de operación, asegurando tanto la experiencia de usuario en el frontend como la estabilidad de la lógica de negocio y persistencia en el backend.

El plan de verificación implementado consta de dos niveles independientes:
1.  **Pruebas Automatizadas (PHPUnit):** Ejecución de tests en el backend para validar el comportamiento del código ante peticiones controladas, cubriendo redirecciones, middleware y flujos lógicos.
2.  **Pruebas Manuales (Aceptación en Navegador):** Inspección visual directa y flujos de punta a punta realizados por el equipo de desarrollo sobre navegadores web para validar responsive e interfaces.

---

## Pruebas automatizadas (PHPUnit)

Las pruebas automatizadas de backend se ejecutan sobre la base del framework de pruebas PHPUnit incorporado en Laravel 11. Se ubicaron en el directorio de pruebas de integración y características `tests/Feature/`.

### Casos de prueba automatizados cubiertos
*   **Seguridad y control de acceso:** Validación del middleware administrativo (`AdminMiddleware`), asegurando que las peticiones a rutas privadas `/admin/*` devuelvan código de denegación `403` o redirecciones a `/login` si no se dispone del rol `admin`.
*   **Catálogo de productos:** Validación de la carga de la tienda pública, filtrado por categorías, búsqueda de artículos y respuesta con código `404` para productos inactivos.
*   **Carrito de compras híbrido:** Adición de productos a la sesión, sincronización automática al base de datos tras la autenticación y vaciado del carro.
*   **Checkout y transacciones:** Proceso de creación del pedido, descuento automático de unidades de stock del catálogo físico e interrupción con excepción controlada en caso de intentar comprar productos con inventario insuficiente.
*   **Cancelación y reabastecimiento:** Validación de que al cancelar administrativamente un pedido, el stock retorne automáticamente a los productos asociados, controlando ejecuciones sucesivas redundantes.

### Comando de ejecución y resultado
La suite de tests completa se ejecuta en el contenedor de desarrollo del proyecto mediante la siguiente instrucción:

```bash
docker compose exec -u sail laravel.test php artisan test
```

El resultado obtenido es:
*   **Tests pasados con éxito:** **49 tests pasaron de manera exitosa** (128 aserciones ejecutadas con 0 errores y 0 fallos), garantizando la no regresión del código.

---

## Pruebas manuales y registro de resultados

Para comprobar el diseño visual adaptable (responsive) y los flujos completos desde la perspectiva del comprador final y del administrador, se ejecutaron 12 casos de prueba (CP) en navegadores web.

### Tabla de registro de pruebas manuales

| ID | Caso de Prueba | Precondición | Acción Realizada | Resultado Esperado | Estado |
| :---: | :--- | :--- | :--- | :--- | :---: |
| **CP-01** | Registro de nuevo cliente | Visitante no registrado | Rellenar formulario de registro con datos correctos. | Cuenta creada e inicio de sesión automático redirigiendo al catálogo. | **Cumple** |
| **CP-02** | Intrusión a rutas administrativas | Sesión iniciada con rol `cliente` | Intentar acceder directamente a `http://localhost:8080/admin/productos`. | Retorno de código de error de acceso restringido `403` (Forbidden). | **Cumple** |
| **CP-03** | Filtrado del catálogo | Catálogo con productos cargados | Pulsar en la categoría "Sudaderas" en la barra lateral. | Visualización exclusiva de sudaderas en el escaparate. | **Cumple** |
| **CP-04** | Búsqueda por palabra clave | Catálogo activo | Buscar la palabra "Oversize" en el cuadro de búsqueda. | Filtrado del catálogo listando solo prendas con esa palabra clave. | **Cumple** |
| **CP-05** | Carrito persistente invitado | Visitante no registrado | Añadir dos camisetas al carrito y cerrar el navegador web. | Al reabrir la web, los artículos continúan guardados en el carrito de sesión. | **Cumple** |
| **CP-06** | Sincronización al loguearse | Carrito de invitado con artículos | Iniciar sesión con una cuenta de cliente existente. | Los artículos del carrito de sesión se migran a la base de datos bajo el perfil. | **Cumple** |
| **CP-07** | Proceso de compra completo | Cliente logueado con stock disponible | Confirmar y finalizar pedido desde el carrito de compras. | Pedido registrado como "pendiente" y stock decrementado en tiempo real. | **Cumple** |
| **CP-08** | Compra con stock excedido | Cliente logueado en checkout | Intentar forzar la compra de una prenda en cantidad superior al stock. | Bloqueo del proceso con mensaje flash de aviso de stock insuficiente. | **Cumple** |
| **CP-09** | CRUD de productos admin | Administrador autenticado | Crear un producto con foto y modificar su precio desde el panel. | Producto creado correctamente con imagen almacenada en disco. | **Cumple** |
| **CP-10** | Ocultación por desactivación | Administrador autenticado | Desactivar un producto marcando su opción `is_active = false`. | El producto deja de mostrarse en el catálogo del cliente inmediatamente. | **Cumple** |
| **CP-11** | Cancelación administrativa | Administrador autenticado | Modificar el estado de un pedido pendiente a "cancelado". | El stock de las prendas asociadas al pedido se restituye automáticamente. | **Cumple** |
| **CP-12** | Adaptabilidad del diseño | Diversos perfiles de usuario | Redimensionar navegador y probar la web desde dispositivos móviles. | Menú móvil colapsable y rejilla de productos reajustada correctamente. | **Cumple** |

Todos los casos de prueba manuales fueron ejecutados de manera exitosa, confirmando el correcto funcionamiento visual, funcional y técnico de la aplicación.
