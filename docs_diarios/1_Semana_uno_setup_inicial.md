# Semana 1: Setup Inicial y Entorno

**Fase:** Semana 1 (Días 1 al 5)
**Autor:** Xavi / Lidia

## Resumen de la jornada
Se completó la primera semana de planificación y arranque del proyecto BLÜK (Tienda Online), dejando configurado todo el entorno de trabajo y el andamiaje principal de Laravel.

## Tareas completadas

### 1. Definición del Proyecto
- Se cerró el alcance del MVP, determinando lo que entra (catálogo, carrito, pedidos, admin) y lo que no entra (pagos reales, roles complejos, etc.).
- Se definió el flujo de ramas en Git (`main`, `develop`, `feature/...`) y las convenciones de commits.

### 2. Configuración de Laravel y Docker (Sail)
- Creación del proyecto base con Laravel 11.
- Configuración de Laravel Sail para orquestar los contenedores Docker (PHP, MySQL).
- Verificación del correcto funcionamiento del servidor local.

### 3. Autenticación con Laravel Breeze
- Instalación del scaffolding de autenticación (Breeze) usando el stack de Blade.
- Migración inicial de tablas base (`users`, `password_reset_tokens`, etc.).
- Verificación de los flujos de registro e inicio de sesión.
