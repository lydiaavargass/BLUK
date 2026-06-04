# Informe de Desarrollo - Semana 10: Dockerización y Despliegue Productivo

Este documento detalla la implementación de la infraestructura del entorno productivo mediante contenedores Docker, cumpliendo con la planificación y el esquema físico de red de la Semana 10 del proyecto BLÜK.

---

## 1. Diseño de la Arquitectura Física Contenerizada

Se diseñó y configuró un entorno de producción que separa las responsabilidades de servicio en contenedores específicos:
1.  **Contenedor Web Server (`bluk_prod_nginx`)**: Servidor Nginx (imagen `nginx:alpine`) que escucha en el puerto `80` interno y expone el puerto `8080` hacia la máquina host. Sirve directamente los archivos estáticos desde `/var/www/html/public` y delega las peticiones dinámicas a PHP.
2.  **Contenedor Application (`bluk_prod_app`)**: Entorno PHP-FPM basado en una imagen de `php:8.4-fpm`. Contiene la lógica del framework Laravel 11, extensiones necesarias y dependencias PHP instaladas.
3.  **Contenedor Database (`bluk_prod_mysql`)**: Servidor de base de datos MySQL 8.4 que almacena y persiste los datos de forma aislada.

---

## 2. Red y Direccionamiento IP Estático

Para cumplir con la topología descrita en la memoria, definimos una red virtual bridge privada llamada `bluk_net` con la subred `172.18.0.0/16` en el archivo `docker-compose.prod.yml`, asignando las siguientes IPs fijas:
-   **Nginx:** `172.18.0.2`
-   **Laravel App (PHP-FPM):** `172.18.0.3`
-   **MySQL:** `172.18.0.4`

El proxy Nginx está configurado para conectarse con PHP-FPM a través de `fastcgi_pass 172.18.0.3:9000;` usando esta asignación estática de IPs.

---

## 3. Guía de Despliegue y Optimización

- **Manual de Despliegue (`DEPLOYMENT.md`)**: Creación de una guía detallada para el administrador de sistemas. Explica el mapeo de red, variables de entorno necesarias, comandos de construcción y configuración de caché para producción (`config:cache`, `route:cache`, `view:cache`).
- **Optimización de Compilación (`.dockerignore`)**: Se configuró para excluir directorios temporales locales (`node_modules`, `vendor`, `.git`, `.atl`) del contexto enviado al motor Docker, acelerando drásticamente el proceso de construcción de imágenes.

---

## 4. Verificación y Resultados

Levantamos el entorno con éxito usando el comando:
```bash
docker compose -f docker-compose.prod.yml up -d --build
```
E inicializamos las tablas e información base:
```bash
docker compose -f docker-compose.prod.yml exec app php artisan migrate --seed --force
```
El entorno de producción quedó operativo en **`http://localhost:8080`**, respondiendo rápidamente y comunicándose de forma exitosa con la base de datos MySQL asignada de producción.
