# Guía de Despliegue con Docker — BLÜK

Este documento describe la arquitectura y los pasos necesarios para desplegar y ejecutar la tienda online **BLÜK** en un entorno de producción contenerizado mediante Docker, cumpliendo estrictamente con la topología de red física especificada en la memoria técnica del proyecto.

---

## 1. Arquitectura de Contenedores y Red

El entorno productivo consta de **3 contenedores independientes** interconectados a través de una red virtual privada de tipo bridge denominada `bluk_net` (subred `172.18.0.0/16`):

1. **Servidor Web Nginx** (`bluk_prod_nginx`):
   - **IP Estática**: `172.18.0.2`
   - **Puerto Interno**: `80`
   - **Mapeo Host**: Expueto al puerto `8080` de la máquina host (`http://localhost:8080`).
   - **Función**: Servir archivos estáticos directamente y actuar como proxy inverso para delegar peticiones PHP.

2. **Aplicación Laravel FPM** (`bluk_prod_app`):
   - **IP Estática**: `172.18.0.3`
   - **Puerto Interno**: `9000`
   - **Función**: Procesar la lógica de negocio en PHP a través de PHP-FPM.

3. **Base de Datos MySQL 8.4** (`bluk_prod_mysql`):
   - **IP Estática**: `172.18.0.4`
   - **Puerto Interno**: `3306`
   - **Función**: Persistencia de datos mediante base de datos relacional.

---

## 2. Requisitos Previos

- Tener instalado **Docker Desktop** (en Windows/macOS) o el motor de **Docker** junto con **Docker Compose** (en Linux).
- Clonar el repositorio del proyecto en la máquina de despliegue.

---

## 3. Pasos para el Despliegue

### Paso 3.1: Preparar Variables de Entorno

1. Copiar el archivo de ejemplo para configurar el entorno:
   ```bash
   cp .env.example .env
   ```

2. Editar el archivo `.env` para apuntar al contenedor de base de datos MySQL de producción:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=http://localhost:8080

   DB_CONNECTION=mysql
   DB_HOST=172.18.0.4
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```

*(Nota: Asegúrate de ajustar las contraseñas en entornos productivos reales).*

### Paso 3.2: Construir e Iniciar Contenedores

Ejecutar el siguiente comando desde la raíz del proyecto para construir las imágenes personalizadas y levantar los servicios en segundo plano:

```bash
docker compose -f docker-compose.prod.yml up -d --build
```

Este comando:
- Construirá la imagen de PHP-FPM usando `docker/app/Dockerfile`.
- Descargará las imágenes oficiales de Nginx y MySQL.
- Configurarará la red privada `bluk_net` con direccionamiento IP estático.
- Levantará los 3 contenedores.

### Paso 3.3: Inicializar la Aplicación Laravel

Una vez que los contenedores estén activos, se deben ejecutar los comandos de configuración dentro del contenedor de la aplicación (`bluk_prod_app`):

1. **Instalar dependencias de Composer**:
   ```bash
   docker compose -f docker-compose.prod.yml exec app composer install --no-interaction --optimize-autoloader --no-dev
   ```

2. **Generar la clave de la aplicación**:
   ```bash
   docker compose -f docker-compose.prod.yml exec app php artisan key:generate
   ```

3. **Configurar permisos de directorios**:
   Asegurar que Laravel puede escribir de manera segura en las carpetas de almacenamiento y caché de vistas:
   ```bash
   docker compose -f docker-compose.prod.yml exec app chown -R www-data:www-data storage bootstrap/cache
   docker compose -f docker-compose.prod.yml exec app chmod -R 775 storage bootstrap/cache
   ```

4. **Ejecutar migraciones y semilla de datos (Seeders)**:
   Crear la estructura de la base de datos y poblarla con categorías, productos de streetwear iniciales, y usuarios de prueba:
   ```bash
   docker compose -f docker-compose.prod.yml exec app php artisan migrate --seed --force
   ```

### Paso 3.4: Optimizaciones para Producción

Para garantizar un rendimiento óptimo de la aplicación web en producción, habilitar el almacenamiento en caché de la configuración y rutas de Laravel:

```bash
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
```

---

## 4. Acceso y Verificación

Una vez finalizado el proceso, puedes acceder al sistema desde cualquier navegador web en la máquina host a través de la dirección:

👉 **[http://localhost:8080](http://localhost:8080)**

Para detener el entorno productivo de forma limpia, ejecuta:
```bash
docker compose -f docker-compose.prod.yml down
```
