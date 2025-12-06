#!/bin/bash
cd "$(dirname "$0")"

echo "=========================================="
echo "     INICIANDO SISTEMA DE BIBLIOTECA"
echo "=========================================="
echo ""

# Verificar Docker
if ! docker info > /dev/null 2>&1; then
  echo "Error: Docker no está ejecutándose."
  echo "Por favor, inicia Docker Desktop y vuelve a intentarlo."
  read -p "Presiona Enter para salir..."
  exit 1
fi

echo "Iniciando servidores (Base de Datos + Web)..."
docker-compose up -d

echo ""
echo "Esperando a que los servicios estén listos (10 segundos)..."
sleep 10

echo ""
echo "Abriendo Kiosco y Panel de Administración..."
open "http://localhost:8000/app/kiosco/index.php"
open "http://localhost:8000/app/index.php"

echo ""
echo "=========================================="
echo "     SISTEMA INICIADO CORRECTAMENTE"
echo "=========================================="
