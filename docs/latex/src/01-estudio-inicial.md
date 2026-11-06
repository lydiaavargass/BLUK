# Estudio inicial previo a la realización del proyecto

## Clasificación de empresas del sector por organización y servicio

El proyecto se enmarca dentro del sector del comercio electrónico (e-commerce), concretamente en la venta online de productos textiles y moda urbana. Las empresas de este sector pueden clasificarse bajo diversos criterios organizativos y comerciales:

### Según su modelo de negocio
*   **B2C (Business to Consumer):** Empresas que venden directamente al consumidor final. BLÜK se clasifica bajo esta categoría, ya que distribuye prendas y accesorios directamente a clientes individuales a través de su portal web.
*   **B2B (Business to Business):** Transacciones comerciales entre empresas.
*   **C2C (Consumer to Consumer):** Portales de compraventa o intercambio entre consumidores finales.

### Según su estructura organizativa
*   **Microempresas:** Menos de 10 trabajadores. BLÜK nace como una microempresa de nueva creación, gestionada inicialmente por sus propios socios fundadores (Xavier Morro y Lidia Vargas), quienes asumen las tareas técnicas, de catálogo y de administración.
*   **Pequeñas y medianas empresas (PYMEs):** Organizaciones con plantillas mayores que requieren estructuras de delegación formal.
*   **Grandes corporaciones:** Cadenas de distribución multinacionales con almacenes inteligentes y distribución global.

### Según el tipo de producto o servicio
*   **Productos físicos:** Ropa, calzado y accesorios textiles (como el catálogo de ropa urbana de estilo surfer y skater de BLÜK).
*   **Productos digitales y servicios:** Descargas de software, consultoría o plataformas SaaS.

---

## Ejemplo de estructura organizativa del sector

Para una tienda de ropa online urbana en crecimiento, la estructura matricial recomendada incluye los siguientes departamentos funcionales:

*   **Dirección General:** Toma de decisiones estratégicas, asignación presupuestaria y alianzas de marca.
*   **Departamento de Marketing y Redes Sociales:** Encargado de la publicidad online, gestión de influencers, posicionamiento SEO/SEM y diseño de campañas estacionales.
*   **Departamento de Ventas y Atención al Cliente:** Gestión de devoluciones, resolución de dudas del proceso de checkout y soporte postventa.
*   **Departamento Técnico (TI):** Desarrollo, mantenimiento correctivo/evolutivo y despliegue de la infraestructura web.
*   **Logística y Almacén:** Control del stock físico, empaquetado de pedidos y coordinación con la agencia de transportes.

En la fase inicial de **BLÜK**, todas estas responsabilidades recaen directamente sobre los socios fundadores, simplificando la comunicación pero aumentando la carga polivalente de cada rol.

---

## Necesidades demandadas que cubre el proyecto

BLÜK cubre un conjunto claro de necesidades tanto comerciales para la marca como operacionales y funcionales para los compradores:

| Necesidad del Sector | Implementación en BLÜK |
| :--- | :--- |
| **Presencia digital de marca** | Creación de un catálogo online accesible desde navegadores web tradicionales y dispositivos móviles. |
| **Venta continua 24/7** | Tienda online automatizada que permite agregar productos al carrito y confirmar pedidos a cualquier hora. |
| **Control de inventario en tiempo real** | Descuento automático de stock al finalizar una compra y reposición del mismo en caso de cancelación administrativa de un pedido. |
| **Seguridad de información sensible** | Cifrado seguro de contraseñas de usuarios registrados y protección contra vulnerabilidades comunes como inyección SQL y ataques CSRF. |
| **Autonomía administrativa** | Un panel privado protegido para que los administradores controlen el catálogo y los pedidos globales sin depender de un desarrollador. |

---

## Descripción del proyecto

**BLÜK** es una tienda online de ropa urbana de estilo surfer y skater. El sistema centraliza la exposición de prendas organizadas por categorías y automatiza de extremo a extremo el flujo transaccional de compra del cliente final y la administración interna de stock y pedidos.

La aplicación consta de dos áreas bien delimitadas:
1.  **Frontend del Cliente:** Navegación del catálogo de ropa, visualización de productos por categorías, buscador de productos, carrito persistente híbrido y vista de su historial de compras en su perfil.
2.  **Panel de Administración (Backoffice):** Interfaz protegida por middleware para la administración de productos (CRUD con carga de imágenes y desactivación lógica), control de stock físico, listado de usuarios y gestión de pedidos globales (con cancelación y reposición automática de existencias).

---

## Justificación del tipo de proyecto elegido

El desarrollo de una aplicación web de e-commerce transaccional con arquitectura MVC responde a las siguientes motivaciones profesionales y pedagógicas del ciclo de Desarrollo de Aplicaciones Web (DAW):

*   **Integración Multidisciplinar:** Obliga a combinar todas las áreas del ciclo formativo: maquetación semántica y de estilos visuales (CSS/Tailwind), programación lógica del backend y del flujo transaccional (Laravel 11 / PHP), diseño y modelado de bases de datos relacionales (MySQL) y virtualización/despliegue del entorno (Docker).
*   **Idoneidad del Framework:** Laravel 11 es el estándar de facto en desarrollo PHP, ofreciendo de forma nativa Eloquent ORM para prevenir inyecciones SQL, andamiajes de autenticación seguros (Breeze) y herramientas de pruebas muy pulidas (PHPUnit).
*   **Resolución de Problemas Complejos:** Desafíos reales como evitar la sobreventa de stock bajo concurrencia y la sincronización de carritos anónimos a base de datos.

---

## Características principales del proyecto elegido

*   **Arquitectura MVC limpia:** Organización de código en modelos Eloquent (`app/Models`), vistas de servidor Blade (`resources/views`) y controladores HTTP (`app/Http/Controllers`).
*   **Base de datos relacional:** MySQL 8.4 normalizada para garantizar la consistencia en el histórico de facturación (precios de venta congelados en `order_items`).
*   **Gestión híbrida de sesiones:** Doble sistema de carrito (sesión local del navegador para invitados y almacenamiento relacional persistente para usuarios autenticados con sincronización post-login).
*   **Despliegue y portabilidad:** Estructura contenerizada completa con Docker y Docker Compose para aislar el servidor web, el runtime de PHP y el motor de base de datos MySQL en una red bridge interna dedicada.
