# Desarrollo sostenible y Objetivos de Desarrollo Sostenible (ODS)

## ODS relevantes para la actividad profesional

En el marco del desarrollo de la plataforma de comercio electrónico **BLÜK**, se han seleccionado tres Objetivos de Desarrollo Sostenible (ODS) que se vinculan de manera directa con la actividad profesional del desarrollo web y la digitalización comercial:

### ODS 9 — Industria, innovación e infraestructura
La creación de plataformas de software sólidas, estables y contenerizadas impulsa la digitalización de la economía local. BLÜK aporta valor directo al tomar una marca de ropa urbana de carácter físico y dotarla de una infraestructura digital robusta que automatiza procesos y facilita la innovación comercial.
*   **Relación con BLÜK:** La modularidad de la arquitectura MVC de Laravel y el empaquetado mediante contenedores de Docker garantizan que la infraestructura de la tienda pueda escalar u hospedarse de forma portable en cualquier servidor en la nube sin requerir la reconstrucción de sus piezas técnicas.

### ODS 12 — Producción y consumo responsables
La digitalización comercial ayuda a optimizar las cadenas de suministro. El control preciso del inventario y la venta directa reducen intermediarios, disminuyen el desperdicio de existencias y promueven una comercialización consciente de recursos textiles en el sector de la moda.
*   **Relación con BLÜK:** El sistema de control de stock en tiempo real y la reposición automática de productos ante pedidos cancelados previenen descuadres de inventario. Esto permite a los gestores de la marca fabricar o almacenar solo lo necesario, evitando el sobrestock y fomentando un modelo de producción "just in time". Además, la maquetación ligera mediante Tailwind CSS optimiza el tráfico de datos transferidos, reduciendo el consumo energético de procesamiento en los dispositivos de los clientes.

### ODS 13 — Acción por el clima
El sector tecnológico genera impacto energético en servidores y centros de datos. La optimización del código del lado del servidor (PHP) y de las consultas a base de datos disminuye el tiempo de CPU requerido, reduciendo directamente las emisiones indirectas de carbono del hospedaje web.
*   **Relación con BLÜK:** BLÜK elimina por completo la necesidad de facturas y registros físicos en papel, centralizando todo el historial transaccional de manera digital en la base de datos relacional. Asimismo, consolidar las ventas a través de una plataforma online optimiza las rutas de envío y logística en comparación con los desplazamientos físicos individuales de los clientes a puntos de venta locales.

---

## Análisis de riesgos y oportunidades en sostenibilidad

| ODS | Oportunidades Profesionales | Riesgos Asociados |
| :---: | :--- | :--- |
| **ODS 9** | Fomentar la adopción de arquitecturas portables y libres (Docker, MySQL, PHP) en pequeñas y medianas empresas. | Obsolescencia rápida de herramientas o alta dependencia de plataformas en la nube de carácter privativo. |
| **ODS 12** | Optimizar interfaces web para reducir la transferencia de datos y la huella de procesamiento del cliente. | Fomentar compras impulsivas e insostenibles si el proceso de checkout se simplifica en exceso sin consciencia de consumo. |
| **ODS 13** | Reducir el consumo en el servidor depurando consultas N+1 y aplicando índices eficientes en la base de datos. | Aumento desmedido de la huella de carbono digital por el uso de servidores VPS ineficientes o no optimizados. |

---

## Acciones y compromisos profesionales
*   **Eficiencia en código:** Optimizar las relaciones y consultas Eloquent para evitar cargas de datos innecesarias a memoria, reduciendo el consumo del CPU del servidor web.
*   **Políticas "Paperless":** Eliminar la impresión física de tickets y hojas de pedidos sustituyéndola por una vista digital interactiva del historial en la base de datos del perfil del comprador.
*   **Software libre:** Priorizar el uso de tecnologías de código abierto (open-source) que reduzcan barreras económicas y fomenten la interoperabilidad técnica y la transparencia.
