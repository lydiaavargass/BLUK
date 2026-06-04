# Informe de Desarrollo - Semana 9: Calidad Final, Pruebas y Validación

Este documento detalla las actividades de control de calidad, ejecución de pruebas automáticas y manuales, y resolución de incidencias de entorno, correspondientes a la Semana 9 del proyecto BLÜK.

---

## 1. Pruebas Automatizadas (PHPUnit)

Se ejecutó la suite completa de pruebas unitarias y funcionales del backend para corroborar la corrección técnica del sistema:
*   **Comando ejecutado:**
    ```bash
    docker compose exec -u sail laravel.test php artisan test
    ```
*   **Resultados obtenidos:**
    - **49 tests ejecutados de forma exitosa** (128 assertions, 0 fallos).
    - Cobertura completa de los flujos de autenticación, control de accesos basados en roles, operaciones CRUD de productos de administración, lógica de sincronización de carritos e inserción de pedidos con descuento de stock.

---

## 2. Pruebas Manuales (Navegador)

Diseñamos y ejecutamos un plan de pruebas en navegador para validar los flujos de experiencia de usuario:
*   **Flujo de Cliente:**
    1. Registro del usuario `testqa@bluk.test`.
    2. Navegación por catálogo de ropa, agregando una sudadera y una camiseta al carrito.
    3. Ajuste de cantidad del producto y checkout.
    4. Confirmación de compra, verificación de la inserción de registros en base de datos e historial de pedidos del cliente.
*   **Flujo de Administrador:**
    1. Autenticación como `admin@bluk.test`.
    2. Navegación al listado de pedidos globales.
    3. Detalle individual de la orden recién creada.
    4. Actualización del estado a `cancelado`.
    5. Validación en el catálogo de que el stock físico de las prendas fue reabastecido al valor previo a la compra.
*   **Resultados:** Ambos flujos pasaron al 100% sin comportamientos inesperados. Los resultados detallados y capturas de pantalla quedaron consolidados en el archivo formal `PLAN_DE_PRUEBAS.md`.

---

## 3. Resolución de Incidencias Técnicas

Durante esta fase de integración, surgieron problemas de entorno que fueron resueltos con éxito:
- **Incidencia 1: Error 500 por permisos de sesión en Docker sobre Windows**:
    - *Causa:* Los archivos de sesión creados por el contenedor de producción (que corre como `www-data`) chocaban con los permisos de escritura del contenedor de desarrollo (que corre bajo el usuario `sail`).
    - *Solución:* Aplicamos un cambio de permisos recursivo (`chmod -R 777`) ejecutando como root (`-u root`) en ambos entornos sobre las carpetas `storage` y `bootstrap/cache`.
- **Incidencia 2: Pérdida de conexión a base de datos en Desarrollo**:
    - *Causa:* Al orquestar el compose de producción en el puerto 8080, el contenedor `mysql` de desarrollo se había apagado/recreado debido a la coincidencia de nombre de servicio default.
    - *Solución:* Aislamos los nombres del proyecto (`bluk_prod`) en producción y reiniciamos el contenedor mysql de desarrollo ejecutando `docker compose up -d`.
