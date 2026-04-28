# Estándar de Nomenclatura de Documentación

Este proyecto sigue una convención estricta para la creación de archivos dentro de la carpeta `docs_diarios/` creando documentacion diaria antes de realizar un commit, explicando detalladamente y de manera clara lo que se ha hecho. Esto garantiza un historial cronológico claro y una lectura uniforme.

## La Regla

Todos los archivos deben nombrarse siguiendo este patrón:
`{número}_{Nombre_del_update_en_snake_case}.md`

### Requisitos:
1. **Prefijo Numérico**: Debe empezar con un número secuencial basado en el orden de creación (ej. `1_`, `2_`, `10_`). Al crear un nuevo documento, verificar el número del último archivo y sumar 1.
2. **Snake Case**: El resto del nombre debe usar exclusivamente `snake_case` (minúsculas conectadas por guiones bajos).
3. **Capitalización**: Solo la **PRIMERA letra** del nombre del update debe ir en Mayúscula.
4. **Extensión**: Únicamente archivos `.md` (Markdown).

### Ejemplos:
- ✅ `10_Proceso_de_migración_de_base_de_datos.md`
- ✅ `11_Configuración_de_entorno_azure.md`
- ❌ `10_ProcesoDeMigracion.md` (No es snake_case)
- ❌ `11_configuracion_azure.md` (Falta mayúscula inicial)
- ❌ `revisión_final.resolved` (No sigue el estándar numérico ni la extensión)

---
*Nota: Este estándar es obligatorio para mantener la integridad del repositorio y facilitar la trazabilidad arquitectónica.*
