# Plan de Pruebas — Tienda Online BLÜK

**Proyecto:** Tienda online BLÜK  
**Autor:** Xavier Morro Sanz  
**Fecha:** Junio 2026  
**Versión:** 1.0  
**Framework de pruebas:** PHPUnit 11 (integrado en Laravel 11)

---

## 1. Introducción

Este documento define los casos de prueba diseñados para verificar el correcto funcionamiento de la aplicación web BLÜK. Las pruebas cubren los flujos críticos de la tienda: seguridad y control de acceso, catálogo público, carrito de compras, proceso de compra (checkout), y el panel de administración (productos, usuarios y pedidos).

Se utilizan dos tipos de verificación:

- **Pruebas automatizadas (PHPUnit):** Ejecutadas dentro del contenedor Docker mediante `php artisan test`. Garantizan regresión continua.
- **Pruebas manuales (navegador):** Verificación visual y funcional de flujos completos de usuario.

---

## 2. Entorno de pruebas

| Componente        | Detalle                                    |
|-------------------|--------------------------------------------|
| Sistema operativo | Windows (host) + Linux (contenedor Docker) |
| PHP               | 8.2                                        |
| Laravel           | 11.x                                       |
| Base de datos     | MySQL 8 (contenedor Docker)                |
| Servidor web      | Laravel Sail (Nginx interno)               |
| Navegador         | Chrome / Edge                              |
| Runner de tests   | PHPUnit 11                                 |

---

## 3. Casos de Prueba (sección 4.1)

### 3.1. Seguridad y Control de Acceso

| ID    | Caso de Prueba                                              | Precondición                     | Acción                                          | Resultado Esperado                                       | Tipo        |
|-------|-------------------------------------------------------------|----------------------------------|--------------------------------------------------|----------------------------------------------------------|-------------|
| CP-01 | Un visitante no autenticado no accede al dashboard admin    | No autenticado                   | GET `/admin/dashboard`                           | Redirección a `/login`                                   | Automatizada |
| CP-02 | Un cliente (rol `cliente`) no accede al dashboard admin     | Autenticado como cliente         | GET `/admin/dashboard`                           | Respuesta HTTP 403 (Forbidden)                           | Automatizada |
| CP-03 | Un administrador accede al dashboard admin correctamente    | Autenticado como admin           | GET `/admin/dashboard`                           | Respuesta HTTP 200, vista `admin.dashboard`              | Automatizada |
| CP-04 | Un administrador es redirigido al panel admin tras login    | Credenciales de admin válidas    | POST `/login` con email admin                    | Redirección a `/admin/dashboard`                         | Automatizada |
| CP-05 | Un usuario puede cerrar sesión                              | Autenticado                      | POST `/logout`                                   | Sesión destruida, redirección a `/`                      | Automatizada |

### 3.2. Autenticación y Registro

| ID    | Caso de Prueba                                              | Precondición                     | Acción                                          | Resultado Esperado                                       | Tipo        |
|-------|-------------------------------------------------------------|----------------------------------|--------------------------------------------------|----------------------------------------------------------|-------------|
| CP-06 | La pantalla de login se renderiza correctamente             | Ninguna                          | GET `/login`                                     | Respuesta HTTP 200                                       | Automatizada |
| CP-07 | Un usuario puede autenticarse con credenciales válidas      | Usuario registrado               | POST `/login` con datos correctos                | Sesión iniciada, redirección                             | Automatizada |
| CP-08 | Un usuario no puede autenticarse con contraseña inválida    | Usuario registrado               | POST `/login` con contraseña errónea             | Error de validación, sesión no iniciada                  | Automatizada |
| CP-09 | La pantalla de registro se renderiza correctamente          | Ninguna                          | GET `/register`                                  | Respuesta HTTP 200                                       | Automatizada |
| CP-10 | Un nuevo usuario puede registrarse exitosamente             | Ninguna                          | POST `/register` con datos válidos               | Usuario creado en BBDD, sesión iniciada                  | Automatizada |

### 3.3. Perfil de Usuario

| ID    | Caso de Prueba                                              | Precondición                     | Acción                                          | Resultado Esperado                                       | Tipo        |
|-------|-------------------------------------------------------------|----------------------------------|--------------------------------------------------|----------------------------------------------------------|-------------|
| CP-11 | La página de perfil se muestra correctamente                | Autenticado                      | GET `/profile`                                   | Respuesta HTTP 200                                       | Automatizada |
| CP-12 | El usuario puede actualizar su información de perfil        | Autenticado                      | PATCH `/profile` con datos válidos               | Datos actualizados en BBDD                               | Automatizada |
| CP-13 | El usuario puede eliminar su cuenta con contraseña correcta | Autenticado                      | DELETE `/profile` con contraseña válida          | Cuenta eliminada, sesión destruida                       | Automatizada |
| CP-14 | La cuenta no se elimina con contraseña incorrecta           | Autenticado                      | DELETE `/profile` con contraseña errónea         | Error de validación, cuenta intacta                      | Automatizada |

### 3.4. Catálogo Público y Carrito

| ID    | Caso de Prueba                                              | Precondición                     | Acción                                          | Resultado Esperado                                       | Tipo        |
|-------|-------------------------------------------------------------|----------------------------------|--------------------------------------------------|----------------------------------------------------------|-------------|
| CP-15 | La página de bienvenida se renderiza correctamente          | Ninguna                          | GET `/`                                          | Respuesta HTTP 200                                       | Automatizada |
| CP-16 | El catálogo muestra los productos activos                   | Productos existentes en BBDD     | GET `/catalogo`                                  | Se muestran productos con `is_active = true`             | Manual       |
| CP-17 | El detalle de un producto muestra su información            | Producto existente               | GET `/catalogo/{product}`                        | Nombre, precio, descripción, imagen y stock              | Manual       |
| CP-18 | Se puede añadir un producto al carrito                      | Producto disponible              | POST `/carrito` con `product_id` y `quantity`    | Producto en el carrito, feedback al usuario               | Manual       |
| CP-19 | Se puede actualizar la cantidad de un producto en carrito   | Producto ya en carrito           | PATCH `/carrito/{productId}` con nueva cantidad  | Cantidad actualizada                                     | Manual       |
| CP-20 | Se puede eliminar un producto del carrito                   | Producto ya en carrito           | DELETE `/carrito/{productId}`                    | Producto eliminado del carrito                           | Manual       |

### 3.5. Compra y Gestión de Stock

| ID    | Caso de Prueba                                              | Precondición                     | Acción                                          | Resultado Esperado                                       | Tipo        |
|-------|-------------------------------------------------------------|----------------------------------|--------------------------------------------------|----------------------------------------------------------|-------------|
| CP-21 | El checkout muestra el resumen del carrito                  | Carrito con productos, logueado  | GET `/checkout`                                  | Productos, cantidades y total correctos                  | Manual       |
| CP-22 | Al confirmar pedido se crea la orden y se descuenta stock   | Carrito con productos, logueado  | POST `/checkout`                                 | Orden creada en BBDD, stock decrementado, carrito vacío  | Manual       |
| CP-23 | El historial de pedidos muestra los pedidos del usuario     | Pedido(s) existente(s)           | GET `/pedidos`                                   | Tabla con pedidos del usuario autenticado                | Manual       |
| CP-24 | El detalle del pedido muestra productos y estado            | Pedido existente                 | GET `/pedidos/{order}`                           | Productos, precios históricos, estado y total            | Manual       |

### 3.6. Administración de Productos

| ID    | Caso de Prueba                                              | Precondición                     | Acción                                          | Resultado Esperado                                       | Tipo        |
|-------|-------------------------------------------------------------|----------------------------------|--------------------------------------------------|----------------------------------------------------------|-------------|
| CP-25 | Un visitante no accede a la gestión de productos admin      | No autenticado                   | GET `/admin/productos`                           | Redirección a `/login`                                   | Automatizada |
| CP-26 | Un cliente no accede a la gestión de productos admin        | Autenticado como cliente         | GET `/admin/productos`                           | Respuesta HTTP 403                                       | Automatizada |
| CP-27 | El admin puede listar productos y buscar por nombre         | Autenticado como admin           | GET `/admin/productos?buscar=Classic`            | Solo productos que coinciden con la búsqueda             | Automatizada |
| CP-28 | El admin puede crear un nuevo producto con datos válidos    | Autenticado como admin           | POST `/admin/productos` con datos completos      | Producto creado en BBDD, redirección al listado          | Automatizada |
| CP-29 | La creación falla si faltan campos obligatorios             | Autenticado como admin           | POST `/admin/productos` con datos vacíos         | Errores de validación en sesión                          | Automatizada |
| CP-30 | El admin puede editar y actualizar un producto              | Autenticado como admin           | PUT `/admin/productos/{id}` con datos válidos    | Producto actualizado en BBDD                             | Automatizada |
| CP-31 | El admin puede desactivar (soft-delete) un producto         | Autenticado como admin           | DELETE `/admin/productos/{id}`                   | `is_active = false` en BBDD, producto oculto del catálogo | Automatizada |

### 3.7. Administración de Usuarios

| ID    | Caso de Prueba                                              | Precondición                     | Acción                                          | Resultado Esperado                                       | Tipo        |
|-------|-------------------------------------------------------------|----------------------------------|--------------------------------------------------|----------------------------------------------------------|-------------|
| CP-32 | Un visitante no accede al listado de usuarios admin         | No autenticado                   | GET `/admin/usuarios`                            | Redirección a `/login`                                   | Automatizada |
| CP-33 | Un cliente no accede al listado de usuarios admin           | Autenticado como cliente         | GET `/admin/usuarios`                            | Respuesta HTTP 403                                       | Automatizada |
| CP-34 | El admin puede ver la lista de usuarios con sus roles       | Autenticado como admin           | GET `/admin/usuarios`                            | Tabla con nombre, email, rol (badge) y fecha de registro | Automatizada |

### 3.8. Administración de Pedidos

| ID    | Caso de Prueba                                              | Precondición                     | Acción                                          | Resultado Esperado                                       | Tipo        |
|-------|-------------------------------------------------------------|----------------------------------|--------------------------------------------------|----------------------------------------------------------|-------------|
| CP-35 | Un visitante no accede a la gestión de pedidos admin        | No autenticado                   | GET `/admin/pedidos`                             | Redirección a `/login`                                   | Automatizada |
| CP-36 | Un cliente no accede a la gestión de pedidos admin          | Autenticado como cliente         | GET `/admin/pedidos`                             | Respuesta HTTP 403                                       | Automatizada |
| CP-37 | El admin puede listar los pedidos                           | Autenticado como admin           | GET `/admin/pedidos`                             | Tabla con pedidos, cliente, estado y total                | Automatizada |
| CP-38 | El admin puede ver el detalle de un pedido                  | Autenticado como admin           | GET `/admin/pedidos/{id}`                        | Datos del cliente, productos y estado                    | Automatizada |
| CP-39 | El admin puede actualizar el estado de un pedido            | Autenticado como admin           | PATCH `/admin/pedidos/{id}` con `status`         | Estado actualizado en BBDD                               | Automatizada |
| CP-40 | Al cancelar un pedido se restaura el stock                  | Pedido en estado `pendiente`     | PATCH `/admin/pedidos/{id}` con `status=cancelado` | Stock incrementado en cada producto del pedido          | Automatizada |
| CP-41 | Cancelar un pedido ya cancelado no restaura stock de nuevo  | Pedido ya en estado `cancelado`  | PATCH `/admin/pedidos/{id}` con `status=cancelado` | Stock no cambia, pedido permanece cancelado              | Automatizada |

---

## 4. Registro de Pruebas Realizadas (sección 4.2)

### 4.1. Resultados de las Pruebas Automatizadas (PHPUnit)

**Fecha de ejecución:** 4 de junio de 2026  
**Comando:** `docker compose exec -u sail laravel.test php artisan test`  
**Resultado global:** ✅ 49 tests, 128 assertions, 0 failures  
**Duración:** 19.87s

| ID    | Resultado | Observaciones                                 |
|-------|-----------|-----------------------------------------------|
| CP-01 | ✅ PASS    | Redirección a `/login` correcta               |
| CP-02 | ✅ PASS    | HTTP 403 retornado                             |
| CP-03 | ✅ PASS    | HTTP 200, vista correcta                       |
| CP-04 | ✅ PASS    | Redirección a `/admin/dashboard`               |
| CP-05 | ✅ PASS    | Sesión destruida                               |
| CP-06 | ✅ PASS    | Pantalla de login renderizada                  |
| CP-07 | ✅ PASS    | Autenticación exitosa                          |
| CP-08 | ✅ PASS    | Error de validación, sesión no iniciada        |
| CP-09 | ✅ PASS    | Pantalla de registro renderizada               |
| CP-10 | ✅ PASS    | Usuario creado y sesión iniciada               |
| CP-11 | ✅ PASS    | Página de perfil renderizada                   |
| CP-12 | ✅ PASS    | Información actualizada en BBDD                |
| CP-13 | ✅ PASS    | Cuenta eliminada correctamente                 |
| CP-14 | ✅ PASS    | Error retornado, cuenta intacta                |
| CP-15 | ✅ PASS    | Página de bienvenida HTTP 200                  |
| CP-25 | ✅ PASS    | Redirección a `/login` correcta               |
| CP-26 | ✅ PASS    | HTTP 403 retornado                             |
| CP-27 | ✅ PASS    | Filtrado por búsqueda funcional                |
| CP-28 | ✅ PASS    | Producto creado en BBDD                        |
| CP-29 | ✅ PASS    | Errores de validación retornados               |
| CP-30 | ✅ PASS    | Producto actualizado en BBDD                   |
| CP-31 | ✅ PASS    | `is_active = false` en BBDD                   |
| CP-32 | ✅ PASS    | Redirección a `/login` correcta               |
| CP-33 | ✅ PASS    | HTTP 403 retornado                             |
| CP-34 | ✅ PASS    | Listado con roles visible                      |
| CP-35 | ✅ PASS    | Redirección a `/login` correcta               |
| CP-36 | ✅ PASS    | HTTP 403 retornado                             |
| CP-37 | ✅ PASS    | Listado de pedidos renderizado                 |
| CP-38 | ✅ PASS    | Detalle de pedido renderizado                  |
| CP-39 | ✅ PASS    | Estado actualizado correctamente               |
| CP-40 | ✅ PASS    | Stock restaurado al cancelar                   |
| CP-41 | ✅ PASS    | Stock no se duplica al re-cancelar             |

### 4.2. Resultados de las Pruebas Manuales (Navegador)

**Fecha de ejecución:** 4 de junio de 2026  
**Navegador:** Chrome (subagente automatizado)  
**URL base:** `http://localhost`

| ID    | Resultado | Observaciones                                 |
|-------|-----------|-----------------------------------------------|
| CP-16 | ✅ PASS    | Catálogo muestra productos activos con imágenes, precios y nombres |
| CP-17 | ✅ PASS    | Detalle de Camiseta BLÜK Classic: nombre, descripción, precio (29.99 €), stock (50) e imagen |
| CP-18 | ✅ PASS    | Producto añadido al carrito (cantidad 2), feedback visual correcto |
| CP-19 | ✅ PASS    | Cantidad actualizable desde la vista del carrito |
| CP-20 | ✅ PASS    | Producto eliminable desde la vista del carrito |
| CP-21 | ✅ PASS    | Checkout muestra resumen con productos, cantidades y total (59.98 €) |
| CP-22 | ✅ PASS    | Orden #1 creada en BBDD, stock decrementado de 50 a 48, carrito vaciado |
| CP-23 | ✅ PASS    | Historial de pedidos muestra el pedido #1 con estado "Pendiente" |
| CP-24 | ✅ PASS    | Detalle del pedido muestra productos, precios históricos, estado y total |

---

## 5. Evaluación del cumplimiento (sección 4.3)

| Requisito del proyecto                          | Estado   | Evidencia                           |
|-------------------------------------------------|----------|-------------------------------------|
| Registro e inicio de sesión de usuarios         | ✅ Cumple | CP-06 a CP-10                       |
| Gestión de perfiles                             | ✅ Cumple | CP-11 a CP-14                       |
| Consulta de detalle de producto                 | ✅ Cumple | CP-16, CP-17                        |
| Añadir productos al carrito                     | ✅ Cumple | CP-18 a CP-20                       |
| Gestión de pedidos (cliente)                    | ✅ Cumple | CP-21 a CP-24                       |
| Panel de administración con CRUD de productos   | ✅ Cumple | CP-25 a CP-31                       |
| Gestión de stock                                | ✅ Cumple | CP-22 (descuento) + CP-40, CP-41 (restauración) |
| Sistema de autenticación con roles              | ✅ Cumple | CP-01 a CP-05                       |
| Seguridad (hash, CSRF, validaciones, ORM)       | ✅ Cumple | Bcrypt en contraseñas, CSRF en forms, validación backend, Eloquent ORM |
| Diseño responsive                               | ✅ Cumple | Verificado visualmente en navegador  |
