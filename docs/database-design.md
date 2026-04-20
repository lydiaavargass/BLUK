# BLÜK — Diseño de Base de Datos

## Diagrama Entidad-Relación

```
┌──────────────┐       ┌──────────────────────────────────────────┐
│    roles     │       │                 users                    │
├──────────────┤       ├──────────────────────────────────────────┤
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

## Tablas

| Tabla | Descripción | Filas esperadas |
|-------|-------------|-----------------|
| `roles` | Roles del sistema (admin, cliente) | 2 |
| `users` | Usuarios registrados | Variable |
| `categories` | Categorías de productos | 5–10 |
| `products` | Catálogo de productos | 20–50 |
| `carts` | Carritos (por usuario o por sesión) | Variable |
| `cart_items` | Productos dentro de un carrito | Variable |
| `orders` | Pedidos confirmados | Variable |
| `order_items` | Líneas de cada pedido (precio histórico) | Variable |

## Relaciones

| Origen | Tipo | Destino | FK | On Delete |
|--------|------|---------|----|-----------|
| roles | 1:N | users | `users.role_id` | RESTRICT |
| categories | 1:N | products | `products.category_id` | CASCADE |
| users | 1:1 | carts | `carts.user_id` | CASCADE |
| users | 1:N | orders | `orders.user_id` | RESTRICT |
| carts | 1:N | cart_items | `cart_items.cart_id` | CASCADE |
| products | 1:N | cart_items | `cart_items.product_id` | CASCADE |
| orders | 1:N | order_items | `order_items.order_id` | CASCADE |
| products | 1:N | order_items | `order_items.product_id` | RESTRICT |

## Decisiones de diseño

### 1. Precio histórico en `order_items`
El campo `price` en `order_items` guarda el precio **en el momento de la compra**. Si el producto cambia de precio después, los pedidos anteriores mantienen el precio correcto.

### 2. Carrito dual (sesión + BD)
- **Invitados**: se identifican por `session_id`
- **Logueados**: se identifican por `user_id`
- Al hacer login, el carrito de sesión se fusiona con el del usuario

### 3. Unique constraint en `cart_items`
La combinación `(cart_id, product_id)` es única. Si un usuario añade el mismo producto dos veces, se incrementa `quantity` en vez de crear un registro duplicado.

### 4. Status como ENUM
Los estados del pedido están acotados: `pendiente`, `procesando`, `enviado`, `cancelado`. No se necesita una tabla aparte para solo 4 valores fijos.

### 5. On Delete policies
- **RESTRICT** en `users → orders` y `products → order_items`: no se puede borrar un usuario con pedidos ni un producto que aparece en pedidos históricos.
- **CASCADE** en `categories → products`: si se borra una categoría, se borran sus productos (decisión de simplificación académica).
- **CASCADE** en carts/cart_items: datos temporales que no necesitan preservarse.

## Estados de pedido

```
pendiente ──▶ procesando ──▶ enviado
    │
    └──▶ cancelado
```

Solo se puede cancelar un pedido en estado `pendiente`. Al cancelar, se restaura el stock.
