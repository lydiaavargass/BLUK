# Identificación de necesidades y diseño del proyecto

## Estudio inicial y planificación del proyecto

### Fases del proyecto y su contenido

El desarrollo de la tienda online BLÜK se planificó en cinco fases fundamentales secuenciadas a lo largo de un período estimado de 12 semanas:

*   **Fase 1: Análisis y diseño (Semanas 1–2):** Estudio detallado de requisitos funcionales y no funcionales, modelado de base de datos relacional y definición del esquema conceptual.
*   **Fase 2: Desarrollo backend (Semanas 3–6):** Instalación del framework Laravel 11, configuración de base de datos en Docker, migración de tablas, andamiaje de autenticación (Breeze) y desarrollo de modelos, controladores e inyección de transacciones atómicas.
*   **Fase 3: Desarrollo frontend (Semanas 7–9):** Creación de plantillas Blade dinámicas y maquetación visual responsive con Tailwind CSS, adaptando el catálogo, el carrito y el panel de administración.
*   **Fase 4: Integración y pruebas (Semanas 10–11):** Ejecución de pruebas automatizadas unitarias/funcionales con PHPUnit y pruebas de usabilidad manuales en navegador.
*   **Fase 5: Despliegue (Semana 12):** Dockerización del entorno productivo mediante contenedores independientes para Nginx, PHP y MySQL.

### Especificar los objetivos del proyecto

*   **Objetivo 1:** Implementar una tienda online funcional de e-commerce que cubra de manera integral la compra de productos.
*   **Objetivo 2:** Diseñar y estructurar una base de datos normalizada para evitar redundancias y garantizar la consistencia transaccional.
*   **Objetivo 3:** Aplicar mecanismos de seguridad integrados, como cifrado de contraseñas (bcrypt), validación de formularios y tokens CSRF.
*   **Objetivo 4:** Implementar roles diferenciados para el control de accesos (`cliente` y `admin`).
*   **Objetivo 5:** Proveer un despliegue contenerizado portable y replicable en cualquier servidor destino.

### Especificar recursos hardware y software

*   **Recursos Hardware:**
    *   Ordenador personal de desarrollo (Procesador equivalente a Intel i5 o AMD Ryzen 5, 8 GB de memoria RAM, almacenamiento SSD).
    *   Dispositivos móviles (smartphone) para verificar la adaptabilidad visual de la interfaz.
*   **Recursos Software:**
    *   Sistema operativo: Windows 10/11 (host de desarrollo) y Linux Alpine/Ubuntu (entorno interno de contenedores Docker).
    *   Entorno de desarrollo: IDE PHPStorm o VS Code.
    *   Tecnologías core: PHP 8.2, Laravel 11 y MySQL 8.
    *   Entorno local: Docker Desktop con Laravel Sail.
    *   Control de versiones: Git y repositorio remoto en GitHub.
    *   Navegadores web: Google Chrome, Mozilla Firefox y Microsoft Edge.

### Especificar recursos materiales y personales

*   **Recursos Personales:** El desarrollo es llevado a cabo de forma íntegra por el alumno Xavier Morro Sanz (con la colaboración de Lidia Vargas para el diseño de catálogo e identidad gráfica), con el soporte metodológico del equipo docente y tutor de FOC.
*   **Recursos Materiales:** Equipo informático de desarrollo, conexión a internet de banda ancha y entorno virtual local de base de datos y servidor de correo ficticio de desarrollo.

### Asociación de fases y recursos

*   **Fase de Análisis y Diseño:** Requiere procesador de textos para documentación técnica y herramientas de modelado de base de datos.
*   **Fases de Desarrollo (Backend y Frontend):** Demanda el uso del IDE, motor de base de datos MySQL local y el runtime de PHP montado en el contenedor de Docker Sail.
*   **Fase de Pruebas:** Utiliza suites de testing automáticas (PHPUnit) y navegadores web para validación manual de responsive.
*   **Fase de Despliegue:** Requiere el orquestador Docker Compose para empaquetar la infraestructura en producción.

---

## Aspectos fiscales y laborales

### Obligaciones de protección de datos (RGPD / LOPDGDD)

Al procesar información de personas físicas (nombre, correo electrónico, contraseñas y registros de compras), el sistema BLÜK cumple con el Reglamento General de Protección de Datos (RGPD) y la Ley Orgánica de Protección de Datos y Garantía de los Derechos Digitales (LOPDGDD):
*   **Principio de minimización:** Solo se recaban los datos esenciales para procesar el pedido e iniciar sesión.
*   **Seguridad:** Almacenamiento seguro de contraseñas mediante hash irreversible (bcrypt).
*   **Derechos ARSOPL:** Los usuarios pueden ejercer sus derechos de acceso, rectificación y supresión directamente desde su perfil personal de cliente.

### Aspectos fiscales y laborales de la empresa

Para la explotación comercial real de la marca de ropa urbana:
*   **Obligaciones fiscales:** Alta en el Censo de Empresarios (modelos 036/037), encuadramiento en el Impuesto de Actividades Económicas (IAE), y liquidaciones periódicas de IVA (modelo 303) e IRPF o Impuesto sobre Sociedades.
*   **Obligaciones laborales:** Alta de autónomo para los socios fundadores, registro de contratos de personal (si aplica) y cotizaciones sociales correspondientes.
*   **Prevención de riesgos laborales:** Evaluación ergonómica del puesto de desarrollo de software para mitigar fatiga visual y lesiones musculares.

---

## Viabilidad económica

### Presupuesto económico estimado (Primer año de explotación)

| Concepto | Coste Estimado | Función |
| :--- | :---: | :--- |
| **Dominio web (`.com`)** | 15 € / año | Nombre identificador de la marca en internet |
| **Hosting VPS básico** | 120 € / año | Servidor privado virtual para despliegue Docker |
| **Certificado SSL/TLS** | 0 € (Let's Encrypt) | Encriptación de datos en tránsito (HTTPS) |
| **Servicios de correo** | 30 € / año | SMTP para notificaciones de pedidos |
| **Imagen de marca inicial** | 100 € | Logotipo, diseño de camisetas y pegatinas |
| **Contingencias técnicas** | 50 € | Margen para gastos imprevistos de software |
| **Total Estimado Primer Año** | **315 €** | Coste de entrada reducido y asumible |

### Financiación necesaria y subvenciones

*   **Financiación:** Al no requerir infraestructura física ni maquinaria costosa, la inversión de 315 € es asumida al 100% por los propios socios fundadores con fondos personales.
*   **Ayudas futuras:** Subvenciones autonómicas para jóvenes emprendedores o bonificaciones del Kit Digital para la implantación de soluciones e-commerce en microempresas.
