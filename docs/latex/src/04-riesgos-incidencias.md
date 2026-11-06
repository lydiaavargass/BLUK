# Gestión de riesgos, salvaguardas e incidencias

## Riesgos identificados y mitigaciones

Durante la planificación y ejecución de la tienda online **BLÜK**, se identificaron riesgos críticos de integración y operación:

| ID | Riesgo Identificado | Severidad | Mitigación Aplicada |
| :---: | :--- | :---: | :--- |
| **R1** | Inconsistencia de stock por concurrencia | Alta | Encapsulado del flujo de reserva de stock en transacciones atómicas de base de datos (`DB::transaction`). |
| **R2** | Intrusiones fraudulentas al panel administrativo | Muy Alta | Implementación de un middleware exclusivo (`AdminMiddleware`) que intercepta peticiones HTTP y restringe el acceso a administradores. |
| **R3** | Pérdida de carritos de visitantes invitados | Media | Persistencia híbrida mediante almacenamiento local en sesión y fusión automática post-login. |
| **R4** | Conflictos de dependencias entre entornos locales | Media | Aislamiento completo del entorno de desarrollo y ejecución mediante contenedores virtuales de Docker y orquestación con Docker Compose. |

---

## Salvaguardas implementadas en la rama de producción

Para asegurar que el código en la rama principal (`main`) sea estable, se aplican las siguientes reglas y salvaguardas de desarrollo:
*   **Prohibición de pushes directos:** Los desarrolladores deben abrir ramas de características y proponer cambios a través de Pull Requests (PR).
*   **Revisión obligatoria de pares:** Ningún cambio puede ser integrado a la rama principal sin la aprobación manual de los revisores autorizados.
*   **Pruebas previas automatizadas:** Integración de comandos de verificación antes del merge para asegurar la no regresión del código.

---

## Registro de incidencias técnicas y resoluciones

A continuación se detallan las tres incidencias técnicas críticas surgidas durante el desarrollo del proyecto y la forma profesional en que se resolvieron:

### Incidencia 1: Error en build context por carpetas locales en Windows (Docker)
*   **Descripción del problema:** Al intentar construir las imágenes Docker productivas en entornos del sistema operativo Windows, el motor Docker fallaba arrojando un error de archivo no encontrado o solicitud inválida (`invalid file request`) en carpetas internas del directorio `node_modules` y `vendor`. Esto se debe a la imposibilidad de Docker para procesar o seguir enlaces simbólicos creados nativamente por el gestor de paquetes de Windows.
*   **Solución técnica:** Se creó un archivo de exclusión `.dockerignore` en la raíz del proyecto para omitir la transferencia de las carpetas de dependencias locales pesadas (`node_modules`, `vendor`) al daemon de Docker. Esto forzó a que cada contenedor instale sus dependencias de forma interna y aislada, acelerando además la velocidad de compilación notablemente.

### Incidencia 2: Conflicto de nombres de contenedores activos en el host
*   **Descripción del problema:** Al intentar levantar el entorno contenerizado de producción tras haber estado ejecutando el entorno de desarrollo local, el comando fallaba con excepciones de conflicto de nombres, indicando que alias como `bluk_app` o `bluk_mysql` ya se encontraban registrados o en uso por ejecuciones pasadas del proyecto.
*   **Solución técnica:** Modificamos el archivo de orquestación de producción (`docker-compose.prod.yml`) inyectándole un nombre de proyecto unívoco usando la directiva `name: bluk_prod`. Además, renombramos de forma única cada contenedor interno del entorno productivo (`bluk_prod_app`, `bluk_prod_nginx` y `bluk_prod_mysql`), evitando interferencias.

### Incidencia 3: Conflictos de permisos de escritura de sesiones entre entornos
*   **Descripción del problema:** Las ejecuciones en local arrojaban excepciones PHP de denegación de permisos (`Permission denied` en carpetas como `storage/framework/sessions`) al intentar guardar carritos o sesiones de usuario. Esto se produjo porque los archivos temporales creados por el contenedor productivo bajo la cuenta `www-data` no podían ser modificados o escritos por el usuario Sail (`sail`) del contenedor de desarrollo local sobre el mismo volumen compartido en el host.
*   **Solución técnica:** Se ejecutó un comando de asignación recursiva de permisos de lectura y escritura como superusuario (`chmod -R 777`) sobre las carpetas `storage` y `bootstrap/cache`, solucionando de manera definitiva la colisión de permisos entre los usuarios de los entornos.
