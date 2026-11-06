# Módulo LaTeX de VeriTix

Este módulo encapsula TODO lo necesario para compilar la memoria PDF a partir de Markdown.

## Estructura

- `src/*.md`: capítulos en orden (se concatenan al compilar)
- `filters/`: `common.lua` + filtros por responsabilidad (`headers.lua`, `tables.lua`, `figures.lua`, `pagebreaks.lua`)
- `defaults.yml`: concentra la configuración de Pandoc/LaTeX del build
- `template.tex`: plantilla única (todo el preámbulo, macros y estructura)
- `metadata.yml`: metadatos globales (título, autores, idioma, etc.)
- `assets/`: recursos gráficos del documento (logo y otros assets estáticos)
- `build/assets/`: diagramas ER derivados en PDF vectorial (generados por Prisma ERD)
- `build.sh`: script principal (build/clean/erd)
- `Makefile`: fachada simple sobre `build.sh`
- `build/`: artefactos de salida

## Organización de `template.tex`

El archivo `template.tex` está organizado en secciones claras:

```tex
%% CLASE BASE
%% PAQUETES FUNDAMENTALES
%% TIPOGRAFÍA Y IDIOMA
%% GEOMETRÍA Y COLORES
%% INTERLINEADO Y MICROTIPOGRAFÍA
%% TABLAS
%% LISTAS
%% GRÁFICOS Y FIGURAS
%% CÓDIGO FUENTE
%% TIKZ (para portada)
%% TÍTULOS DE SECCIÓN — KOMA-Script
%% ENCABEZADO Y PIE DE PÁGINA
%% UTILIDADES PANDOC
%% HYPERREF — SIEMPRE AL FINAL
%% PORTADA — TikZ
%% PÁGINA LEGAL
%% INICIO DEL DOCUMENTO
```

Cada sección tiene `%%` como separador visual para facilitar navegación y mantenimiento.

Nota: los ERD se generan directamente como PDF vectorial ajustado al contenido en `build/assets/` desde Prisma ERD (`make erd`).

## Requisitos

- `pandoc`
- `xelatex` (TeX Live)
  `monofont` controla el texto monoespaciado inline. En `metadata.yml` usamos `Noto Sans Mono` y `template.tex` cae a `TeX Gyre Cursor` si no está disponible; los bloques de código usan `Maple Mono NF` y, si no está instalada, el build toma `docs/latex/assets/MapleMono-NF-Regular.ttf`.

## Instalacion de requisitos en ubuntu

```bash
sudo apt update
sudo apt install pandoc texlive-xetex
```

Y para la fuente, la puedes descargar desde [Maple Mono NF en GitHub](https://github.com/subframe7536/maple-font/releases) y luego instalarla en tu sistema operativo.

## Uso

Desde `docs/latex/`:
Para compilar, primero ejecutá `make erd` para generar `build/assets/er-*.pdf`.
Ese target usa un schema temporal que habilita los ERD solo para esa ejecución; el schema base los deja deshabilitados por defecto.

```bash
make erd && make build
```

Salida esperada: `build/memoria.pdf`.

## Referencia rápida Markdown → LaTeX

| Lo que escribís                                               | Qué genera                                              |
| ------------------------------------------------------------- | ------------------------------------------------------- |
| Tabla Markdown normal                                         | Tabla LaTeX con encabezado estilizado                   |
| `::: latex-figure width=0.95\\linewidth needspace=8` + imagen | `figure` con ancho configurable y `\Needspace` opcional |
| `::: pagebreak :::`                                           | `\newpage`                                              |
| `::: clearpage :::`                                           | `\clearpage`                                            |

### Ejemplos

```md
| Columna 1 | Columna 2 |
| --------- | --------- |
| A         | B         |
```

```md
::: latex-figure width=0.95\\linewidth needspace=8
![Mi figura](assets/diagrama.png)
:::
```

```md
::: pagebreak
:::
```

```md
::: clearpage
:::
```

## Convenciones

- No mezclar documentación general del proyecto aquí; eso vive en `docs/markdown/`.
- `build/` contiene artefactos generados, no fuente documental.
- Los `.md` deben quedarse en Markdown semántico: tablas normales, figuras con fenced divs y sin LaTeX crudo.
- El estilo de tablas, figuras y el espaciado antes de encabezados vive en `template.tex` + `filters/`.
- El zebra striping está centralizado en `template.tex` y aplica a `tabular`, `tabularx` y `longtable`.
