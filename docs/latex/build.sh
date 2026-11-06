#!/usr/bin/env bash

# Build para la documentación LaTeX (Markdown -> PDF)
set -euo pipefail

LATEX_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SRC_DIR="$LATEX_DIR/src"
BUILD_DIR="$LATEX_DIR/build"
DEFAULTS="$LATEX_DIR/defaults.yml"
FILTER_DIR="$LATEX_DIR/filters"
COMMON_FILTER="$FILTER_DIR/common.lua"
OUTPUT="$BUILD_DIR/memoria.pdf"
BACKEND_DIR="$LATEX_DIR/../../backend"
LOGO_SOURCE="$LATEX_DIR/assets/foc-logo.png"
ASSET_BUILD_DIR="$BUILD_DIR/assets"
ERD_FILES=(
  "$ASSET_BUILD_DIR/er-overview.pdf"
  "$ASSET_BUILD_DIR/er-core-transaccional.pdf"
)

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

require_cmd() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo -e "${RED}✗ Dependencia faltante: $1${NC}"
    exit 1
  fi
}

ensure_erd_assets() {
  for file in "${ERD_FILES[@]}"; do
    if [ ! -s "$file" ]; then
      echo -e "${YELLOW}⚠ Falta ERD generado: $file${NC}"
      echo -e "${YELLOW}  Ejecutá primero: make erd${NC}"
      exit 1
    fi
  done
}

collect_sources() {
  shopt -s nullglob
  local files=("$SRC_DIR"/*.md)
  shopt -u nullglob

  if [ ${#files[@]} -eq 0 ]; then
    echo -e "${RED}✗ No hay fuentes Markdown en: $SRC_DIR${NC}"
    echo "  Creá al menos un archivo, por ejemplo: $SRC_DIR/01-estudio-inicial.md"
    exit 1
  fi

  printf '%s\n' "${files[@]}" | sort
}

logo_is_available() {
  [ -s "$LOGO_SOURCE" ]
}

validate_inputs() {
  local required_files=("$DEFAULTS" "$COMMON_FILTER")
  required_files+=(
    "$FILTER_DIR/headers.lua"
    "$FILTER_DIR/tables.lua"
    "$FILTER_DIR/figures.lua"
    "$FILTER_DIR/pagebreaks.lua"
  )

  for file in "${required_files[@]}"; do
    if [ ! -f "$file" ]; then
      echo -e "${RED}✗ Archivo requerido no encontrado: $file${NC}"
      exit 1
    fi
  done
}

build() {
  require_cmd pandoc
  require_cmd xelatex
  validate_inputs

  mkdir -p "$BUILD_DIR"

  local logo_path_val="$LOGO_SOURCE"
  local font_path_val="$LATEX_DIR/assets/"
  local filter_dir_val="$FILTER_DIR"
  if command -v cygpath >/dev/null 2>&1; then
    logo_path_val="$(cygpath -m "$LOGO_SOURCE")"
    font_path_val="$(cygpath -m "$LATEX_DIR/assets/")"
    filter_dir_val="$(cygpath -m "$FILTER_DIR")"
  fi

  local -a pandoc_logo_args=()
  if logo_is_available; then
    pandoc_logo_args+=("--variable=logo-path:$logo_path_val")
  else
    echo -e "${YELLOW}⚠ Logo no disponible, se usará fallback tipográfico en portada${NC}"
  fi

  local -a pandoc_codeblock_args=()
  if [ -s "$LATEX_DIR/assets/MapleMono-NF-Regular.ttf" ]; then
    pandoc_codeblock_args+=("--variable=codeblockfontpath:$font_path_val")
  fi

  export LUA_PATH="$filter_dir_val/?.lua;$filter_dir_val/?/init.lua;${LUA_PATH:-}"

  # ensure_erd_assets

  mapfile -t src_files < <(collect_sources)

  echo -e "${YELLOW}▶ Compilando documentación LaTeX...${NC}"
  echo "  Fuentes:"
  for file in "${src_files[@]}"; do
    echo "    • $(basename "$file")"
  done

  (
    cd "$LATEX_DIR"

    pandoc "${src_files[@]}" \
      --defaults="$DEFAULTS" \
      "${pandoc_logo_args[@]}" \
      "${pandoc_codeblock_args[@]}" \
      --output "$OUTPUT"
  )

  echo -e "${GREEN}✓ PDF generado: $OUTPUT${NC}"
}

clean() {
  rm -rf "$BUILD_DIR"
  echo -e "${GREEN}✓ Build limpiado: $BUILD_DIR${NC}"
}

erd() {
  require_cmd bunx
  require_cmd python3
  validate_inputs

  if [ ! -d "$BACKEND_DIR" ]; then
    echo -e "${RED}✗ Backend no encontrado: $BACKEND_DIR${NC}"
    exit 1
  fi

  echo -e "${YELLOW}▶ Generando diagramas ER desde Prisma...${NC}"
  mkdir -p "$ASSET_BUILD_DIR"

  rm -f "${ERD_FILES[@]}"
  local temp_schema
  temp_schema="$(mktemp "$BACKEND_DIR/prisma/schema.erd.XXXXXX.prisma")"
  trap "rm -f '$temp_schema'" EXIT
python3 - "$BACKEND_DIR/prisma/schema.prisma" "$temp_schema" <<'PY'
from pathlib import Path
import re
import sys

src = Path(sys.argv[1])
dst = Path(sys.argv[2])
content = src.read_text()
content = re.sub(r'(^\s*disabled\s*=\s*)true', r'\1false', content, flags=re.M)
dst.write_text(content)
PY

  (
    cd "$BACKEND_DIR"
    bunx prisma generate --schema="$temp_schema"
  )

  ensure_erd_assets
  echo -e "${GREEN}✓ ER generado en docs/latex/build/assets/ (PDF vectorial)${NC}"
}

case "${1:-build}" in
  build)
    build
    ;;
  clean)
    clean
    ;;
  erd)
    erd
    ;;
  *)
    echo "Uso: $(basename "$0") [build|clean|erd]"
    exit 1
    ;;
esac
