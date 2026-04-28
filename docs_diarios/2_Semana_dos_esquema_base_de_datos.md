# Semana 2: Diseño de Base de Datos y Migraciones

**Fase:** Semana 2 (Días 6 al 10)
**Autor:** Xavi / Lidia

## Resumen de la jornada
Se trasladó el diseño lógico de la base de datos (diagrama Entidad-Relación) al código del framework mediante la creación estructurada de migraciones para soportar todo el modelo de negocio del MVP.

## Tareas completadas

### 1. Esquema General de Base de Datos
- Documentación del diseño en `docs/database-design.md`, estableciendo las tablas, tipos de datos y reglas de integridad referencial (`CASCADE`, `RESTRICT`).

### 2. Creación de Migraciones (Core)
- **Roles y Usuarios:**
  - Migración `create_roles_table`.
  - Migración `add_role_id_to_users_table` (para vincular usuarios a un rol específico).
- **Catálogo:**
  - Migración `create_categories_table`.
  - Migración `create_products_table` (incluyendo control de stock y `is_active`).

### 3. Creación de Migraciones (Transaccional)
- **Carrito de Compras:**
  - Migración `create_carts_table` (soporte dual para `user_id` o `session_id`).
  - Migración `create_cart_items_table` (con restricción única entre carrito y producto).
- **Pedidos:**
  - Migración `create_orders_table` (estados del pedido mediante ENUM: `pendiente`, `procesando`, `enviado`, `cancelado`).
  - Migración `create_order_items_table` (almacenando el precio histórico para prevenir cambios retroactivos).
