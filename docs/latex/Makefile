SHELL := /usr/bin/env bash

.PHONY: help build clean erd

help:
	@printf "Targets disponibles:\n"
	@printf "  make build  - Compila PDF desde Markdown\n"
	@printf "  make erd    - Regenera diagrama ER desde Prisma\n"
	@printf "  make clean  - Limpia artefactos de build\n"

build:
	./build.sh build

clean:
	./build.sh clean

erd:
	./build.sh erd
