# Informe de Desarrollo - Semana 4: Escaparate Público

Este documento detalla las implementaciones realizadas para completar la fase de visualización pública del catálogo de productos, cumpliendo con los requisitos de la Semana 4 del proyecto BLÜK.

## 1. Infraestructura y Configuración
- **Sincronización de Entorno**: Se ha actualizado el archivo `.env` local con las credenciales de base de datos correctas (`sail`/`password`) y la configuración de Docker necesaria (`WWWUSER`, `WWWGROUP`) para asegurar la paridad con el entorno de desarrollo de Xavi.
- **Corrección de Permisos**: Se han ajustado los permisos de las carpetas `storage` y `bootstrap/cache` a `777` para solucionar errores de escritura de logs y compilación de vistas en entornos Windows.
- **Gestión de Sesiones**: Se han limpiado las cachés de configuración y aplicaciones para resolver el error 419 (Page Expired) detectado durante el proceso de autenticación.

## 2. Desarrollo del Catálogo (Frontend)
- **Controlador de Productos**: Implementación de `ProductController` con lógica para:
    - Listado paginado de productos activos.
    - Filtrado dinámico por categorías mediante parámetros en la URL.
    - Validación de estado activo para productos individuales.
- **Layout Público (`store.blade.php`)**: Creación de una plantilla base simplificada y profesional que utiliza los componentes estándar de Tailwind CSS. Se ha priorizado la legibilidad y la facilidad de mantenimiento sobre la complejidad estética excesiva.
- **Vista de Catálogo (`index.blade.php`)**: Diseño de un grid de productos con barra lateral de categorías. Incluye indicadores de stock y precios formateados en euros.
- **Vista de Detalle (`show.blade.php`)**: Maquetación de la ficha de producto individual, mostrando descripción, precio, stock disponible y botón de acción para el carrito (preparado para lógica futura).

## 3. Integración y Rutas
- **Rutas Públicas**: Registro de las rutas `/catalogo` y `/catalogo/{product}` en `web.php`, permitiendo el acceso sin necesidad de autenticación previa.
- **Navegación**: Actualización de los menús de navegación globales para incluir accesos directos al catálogo desde cualquier parte de la aplicación.

## 4. Datos de Prueba
- **Poblamiento de DB**: Ejecución de migraciones y seeders para generar un conjunto de datos inicial (Categorías y Productos) que permita validar el funcionamiento del catálogo de forma inmediata.
- **Usuarios**: Documentación de las credenciales de prueba en el archivo `README.md` (`admin@bluk.test` y `cliente@bluk.test`).
