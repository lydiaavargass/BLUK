# Semana 3: Relaciones Eloquent y Seeders Base

**Fase:** Semana 3 (Días 11 al 13)
**Autor:** Xavi / Lidia

## Resumen de la jornada
Tras finalizar las migraciones, esta fase conectó el modelo de base de datos a nivel de aplicación (ORM Eloquent) y pobló las tablas con datos de prueba estructurados para poder visualizar el catálogo.

## Tareas completadas

### 1. Modelos Eloquent y Relaciones
- Se crearon y configuraron todos los modelos principales (`Role`, `User`, `Category`, `Product`, `Cart`, `CartItem`, `Order`, `OrderItem`).
- Se establecieron las relaciones correctas: `belongsTo()`, `hasMany()`, y `hasOne()` según el diseño previo.
- Se implementaron *casts* en los modelos (ej. `price` a `decimal:2` en `Product`).

### 2. Desarrollo de Seeders
- **RoleSeeder:** Generación de roles `admin` y `cliente`.
- **CategorySeeder:** Generación de categorías ficticias.
- **ProductSeeder:** Carga masiva de productos de prueba con precios, descripciones y control de stock inicial.
- **DatabaseSeeder:** Orquestación central. Se aseguraron los tiempos de ejecución llamando a los seeders en el orden correcto (por restricciones de claves foráneas) y se crearon dos usuarios base: `admin@bluk.test` y `cliente@bluk.test`.

### 3. Método Auxiliar de Roles
- Implementación de la función `isAdmin()` en el modelo `User` para facilitar las comprobaciones de autorización a nivel de código y plantillas Blade.
