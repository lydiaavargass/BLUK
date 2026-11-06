# Modelado de la solución y desarrollo de módulos

## Arquitectura general del sistema

La tienda online **BLÜK** se ha desarrollado adoptando el patrón arquitectónico de diseño de software **Modelo-Vista-Controlador (MVC)**, estructurado nativamente por el framework Laravel 11. Este patrón organiza el código separando la interfaz de usuario de la lógica de negocio y persistencia:

*   **Modelos (Model):** Representan la lógica de datos y persisten la información haciendo uso de Eloquent ORM. Están ubicados en el directorio `app/Models/`.
*   **Vistas (View):** Definen la interfaz gráfica que ve el usuario, utilizando el motor de plantillas Blade de Laravel. Se ubican en `resources/views/`.
*   **Controladores (Controller):** Actúan como intermediarios procesando las peticiones HTTP (`Requests`), ejecutando la lógica y retornando las respuestas (`Responses`) o vistas correspondientes. Se ubican en `app/Http/Controllers/`.

---

## Tabla de componentes y tecnologías

El stack real que conforma la solución de software se resume en la siguiente tabla:

| Componente | Tecnología | Función Principal |
| :--- | :--- | :--- |
| **Frontend** | Blade + Tailwind CSS | Interfaz gráfica interactiva y responsive para el usuario. |
| **Backend** | Laravel 11 (PHP 8.2) | Control de flujo de peticiones, seguridad y lógica transaccional. |
| **Persistencia** | MySQL 8.4 | Almacenamiento relacional de usuarios, catálogo, carritos y pedidos. |
| **Servidor Web** | Nginx | Servidor HTTP de producción para atender peticiones FastCGI. |
| **Contenedores** | Docker & Docker Compose | Virtualización y aislamiento de servicios en redes bridge estáticas. |
| **Control de versiones** | Git / GitHub | Gestión del código fuente y control de cambios del equipo. |

---

## Arquitectura física y direccionamiento de red

En producción, el sistema BLÜK se ejecuta en tres contenedores de Docker conectados mediante una red virtual privada bridge interna llamada `bluk_net` con la subred `172.18.0.0/16`.

### Tabla de direccionamiento IP interno

| Servicio / Contenedor | Red Docker | IP Interna | Puerto Interno | Puerto Host Mapeado |
| :--- | :---: | :---: | :---: | :---: |
| **Nginx** (`bluk_prod_nginx`) | `bluk_net` | `172.18.0.2` | 80 | 8080 (HTTP) |
| **Laravel App** (`bluk_prod_app`) | `bluk_net` | `172.18.0.3` | 9000 | - |
| **MySQL** (`bluk_prod_mysql`) | `bluk_net` | `172.18.0.4` | 3306 | 3306 (Localhost) |

---

## Diseño de Base de Datos

### Diagrama Entidad-Relación (ERD)

La base de datos relacional de la aplicación organiza la información de la tienda siguiendo este esquema estructurado:

```
┌──────────────┐       ┌──────────────────────────────────────────┐
│    roles     │       │                 users                    │
│──────────────│       │──────────────────────────────────────────│
│ id       PK  │──┐    │ id                PK                     │
│ name         │  └───>│ role_id           FK → roles             │
│ created_at   │       │ name                                     │
│ updated_at   │       │ email             UNIQUE                 │
└──────────────┘       │ email_verified_at                        │
                       │ password                                 │
                       │ remember_token                           │
                       │ created_at                               │
                       │ updated_at                               │
                       └──────────┬───────────────┬───────────────┘
                                  │               │
                                  │               │
                    ┌─────────────┘               └──────────────┐
                    │                                            │
                    ▼                                            ▼
          ┌─────────────────┐                        ┌──────────────────┐
          │      carts      │                        │      orders      │
          ├─────────────────┤                        ├──────────────────┤
          │ id          PK  │                        │ id           PK  │
          │ user_id     FK? │                        │ user_id      FK  │
          │ session_id  IDX │                        │ status       ENUM│
          │ created_at      │                        │ total            │
          │ updated_at      │                        │ created_at       │
          └────────┬────────┘                        │ updated_at       │
                   │                                 └────────┬─────────┘
                   │                                          │
                   ▼                                          ▼
          ┌─────────────────┐                        ┌──────────────────┐
          │   cart_items     │                        │   order_items    │
          ├─────────────────┤                        ├──────────────────┤
          │ id          PK  │                        │ id           PK  │
          │ cart_id     FK  │                        │ order_id     FK  │
          │ product_id  FK ─┼──┐                ┌───>│ product_id   FK  │
          │ quantity        │  │                │    │ price            │
          │ created_at      │  │                │    │ quantity         │
          │ updated_at      │  │                │    │ created_at       │
          └─────────────────┘  │                │    │ updated_at       │
            UQ(cart,product)   │                │    └──────────────────┘
                               │                │
                               ▼                │
                    ┌──────────────────────┐     │
                    │      products        │     │
                    ├──────────────────────┤     │
                    │ id              PK   │─────┘
                    │ category_id     FK   │
                    │ name                 │
                    │ description          │
                    │ price                │
                    │ stock                │
                    │ image           NULL │
                    │ is_active            │
                    │ created_at           │
                    │ updated_at           │
                    └──────────┬───────────┘
                               │
                               │
                    ┌──────────┴───────────┐
                    │     categories       │
                    ├──────────────────────┤
                    │ id              PK   │
                    │ name           UQ    │
                    │ description    NULL  │
                    │ created_at           │
                    │ updated_at           │
                    └──────────────────────┘
```

### Detalle de tablas relacionales

*   **`roles`:** Define perfiles de acceso autorizados en el sistema (`admin` y `cliente`).
*   **`users`:** Cuentas de usuario registradas conectadas a un `role_id` (clave foránea).
*   **`categories`:** Organiza los artículos en categorías lógicas de streetwear (Sudaderas, Camisetas, Pantalones, Accesorios).
*   **`products`:** Registro del catálogo de artículos físicos con su precio, stock actual en almacén y el estado lógico `is_active`.
*   **`carts` y `cart_items`:** Mecanismo relacional de persistencia de productos añadidos al carro. Soporta almacenamiento para visitantes anónimos (`session_id`) y usuarios logueados (`user_id`).
*   **`orders` y `order_items`:** Registro consolidado de compras. `order_items` congela el campo `price` al momento de la venta para independizar el pedido histórico de futuras modificaciones en el catálogo.

### Reglas de integridad aplicadas
*   **`CASCADE` en `categories` y `carts`:** Si se da de baja una categoría o un carrito, los registros hijos asociados se borran automáticamente.
*   **`RESTRICT` en `users` y `products` respecto a compras:** Evita borrar usuarios con pedidos completados o productos que aparecen en el histórico de compras de `order_items`.
*   **Unique Constraint en `cart_items`:** La clave única compuesta `(cart_id, product_id)` garantiza que los productos agregados de forma duplicada al carrito incrementen su cantidad en lugar de duplicar filas.

---

## Implementación de los módulos de la aplicación

### 1. Autenticación y control de accesos
Se utiliza **Laravel Breeze** adaptado para redirecciones lógicas en función del rol asignado. Para proteger el backoffice administrativo de accesos fraudulentos de usuarios clientes o visitantes, se implementó el siguiente middleware interceptor (`AdminMiddleware`):

```php
public function handle(Request $request, Closure $next)
{
    if (auth()->check() && auth()->user()->role->name === 'admin') {
        return $next($request);
    }
    abort(403, 'Acceso no autorizado.');
}
```

### 2. Carrito de compras híbrido
El flujo gestionado por `CartController` permite:
*   **Modo Invitado:** Los productos agregados se almacenan como un array indexado dentro de la sesión de PHP en el servidor.
*   **Modo Autenticado:** Los artículos se persisten directamente en la base de datos MySQL en las tablas `carts` y `cart_items`.
*   **Fusión de carrito (Post-Login):** Al autenticarse un cliente, un escuchador de eventos de sesión recupera los productos del carrito de sesión temporal de invitado y los fusiona en las tablas correspondientes vinculándolo a su `user_id`.

### 3. Transacción de pedidos y reserva de stock
La creación de un pedido (`OrderController@store`) realiza un descuento físico del inventario disponible en almacén. Para prevenir inconsistencias y condiciones de carrera bajo solicitudes de compra concurrentes, el proceso completo se encapsula en una transacción de base de datos (`DB::transaction`):

```php
DB::transaction(function () use ($cart, $user) {
    $order = Order::create([
        'user_id' => $user->id,
        'status' => 'pendiente',
        'total' => $cart->total(),
    ]);

    foreach ($cart->items as $item) {
        $product = $item->product;
        if ($product->stock < $item->quantity) {
            throw new Exception("Stock insuficiente para: " . $product->name);
        }
        $product->decrement('stock', $item->quantity);
        $order->items()->create([
            'product_id' => $product->id,
            'price' => $product->price,
            'quantity' => $item->quantity,
        ]);
    }
    $cart->delete();
});
```

### 4. Cancelación de pedidos y reposición automática
Desde el panel administrativo, cambiar el estado de un pedido a `cancelado` desencadena un evento automático para reabastecer el almacén sumando la cantidad cancelada al stock actual del producto. Este proceso cuenta con guardas lógicas de estado para evitar la reposición duplicada ante llamadas repetidas.
