# Guion de Presentación: Avance del Proyecto BLÜK (Semanas 1-5)

Este documento está diseñado como una guía o guion para vuestra presentación. Está estructurado para demostrar no solo **qué** habéis hecho, sino **por qué** habéis tomado ciertas decisiones arquitectónicas. Recordad: el objetivo es demostrar dominio del código y de los fundamentos.

---

## 1. Introducción al Proyecto y Arquitectura Base
*Objetivo: Sentar las bases tecnológicas y demostrar que no es un proyecto "hecho al azar", sino con cimientos sólidos.*

**Puntos a comentar:**
- **¿Qué es BLÜK?** Es un MVP (Producto Mínimo Viable) de un e-commerce académico desarrollado bajo el stack TALL (con variaciones: Laravel 11, Blade, Tailwind CSS y MySQL).
- **Entorno Estandarizado:** Mencionad que utilizáis **Docker y Laravel Sail**. *Argumento:* "Queríamos garantizar que todo el equipo tuviera exactamente el mismo entorno de ejecución, evitando el clásico problema de 'en mi máquina sí funciona'. Además, hemos tenido que lidiar con permisos en sistemas Windows y persistencia de base de datos en contenedores."
- **Arquitectura:** Seguimos estrictamente el patrón **MVC (Modelo-Vista-Controlador)** que impone Laravel. Mantenemos los controladores delgados (Thin Controllers) delegando la persistencia a los modelos.

---

## 2. Autenticación y Seguridad (Base)
*Objetivo: Explicar cómo manejáis a los usuarios antes de entrar en la tienda.*

**Puntos a comentar:**
- **Sistema elegido:** Implementamos Laravel Breeze. *Argumento:* "Nos proporciona un scaffolding robusto, seguro y probado para el registro, login y recuperación de contraseñas, permitiéndonos centrarnos en la lógica de negocio (la tienda) en lugar de reinventar la rueda de la autenticación."
- **Roles Iniciales:** Hemos preparado el terreno con un middleware básico (`AdminMiddleware`) y usuarios preconfigurados (`admin@bluk.test` y `cliente@bluk.test`) para facilitar las pruebas del tribunal o los profesores.

---

## 3. Escaparate y Catálogo (El trabajo de Lydia)
*Objetivo: Mostrar la interfaz principal y cómo se comunican base de datos y vistas.*

**Puntos a comentar:**
- **Diseño de Base de Datos:** Tenemos las entidades `Product` y `Category` relacionadas (1 a N). Usamos Seeders para poblar la base de datos con datos de prueba realistas sin tener que meterlos a mano.
- **Controlador (`ProductController`):**
  - **Eficiencia:** Hemos implementado paginación desde el principio para no sobrecargar el servidor si el catálogo crece.
  - **Filtrado Dinámico:** El controlador puede recibir parámetros en la URL para filtrar los productos por categoría de forma dinámica, devolviendo solo lo que interesa a la vista.
- **Vistas (Blade + Tailwind):** Hemos adoptado un patrón de layouts (`store.blade.php`). *Argumento:* "En lugar de repetir el header y el footer en cada página, usamos un layout maestro en el que inyectamos el contenido específico (`index` o `show`). Esto es crucial para la mantenibilidad del código."

---

## 4. El Motor del Carrito (El trabajo de Xavi integrado)
*Objetivo: Esta es la parte técnicamente más compleja hasta ahora. Demuestra manejo de estado y persistencia cruzada.*

**Puntos a comentar:**
- **El Reto:** ¿Cómo permitimos que un usuario compre sin registrarse, pero no pierda su carrito si decide iniciar sesión?
- **La Solución (Carrito Dual):**
  - Si eres **invitado**: El `CartController` guarda los productos temporalmente en la **Sesión** del navegador.
  - Si estás **autenticado**: El controlador escribe directamente en las tablas `carts` y `cart_items` de la base de datos MySQL.
- **La Magia (Sincronización):** Mencionad el **Event Listener** (`SyncCartOnLogin`). *Argumento:* "Hemos creado un Listener enganchado al evento de Login de Laravel. En el momento en que el usuario mete su contraseña, Laravel intercepta la sesión, vuelca los productos guardados en la base de datos del usuario, y limpia la sesión. Esto ofrece una experiencia de usuario (UX) impecable y sin fricciones."

---

## 5. Próximos Pasos (Lo que falta del MVP)
*Objetivo: Ser transparentes sobre el estado del proyecto y demostrar que tenéis un roadmap claro.*

**Puntos a comentar:**
1. **Pedidos (Checkout):** Transformar el carrito (entidad temporal) en un Pedido (entidad final), gestionando el decremento real del stock de los productos.
2. **Panel de Administración (Backoffice):** Construir el CRUD (Crear, Leer, Actualizar, Borrar) para que el rol Administrador gestione el catálogo sin tocar el código o la base de datos.
3. **Historial de Cliente:** Vistas para que el usuario pueda ver el estado de sus pedidos pasados.

---

> **💡 Consejo del Arquitecto para la Presentación:**
> No os limitéis a enseñar pantallas. Abrid el código. Enseñad el `ProductController` para hablar de la paginación. Enseñad el archivo `SyncCartOnLogin.php` para explicar la sincronización de la sesión a la base de datos. Los profesores/evaluadores valoran infinitamente más ver código limpio y estructurado que un frontend bonito pero vacío por dentro.
