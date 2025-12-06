@echo off
title Sistema de Biblioteca - Launcher
echo ==========================================
echo      INICIANDO SISTEMA DE BIBLIOTECA
echo ==========================================
echo.
echo Verificando Docker...
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo Error: Docker no esta ejecutandose.
    echo Por favor, inicia Docker Desktop y vuelve a intentarlo.
    pause
    exit
)

echo Iniciando servidores (Base de Datos + Web)...
docker-compose up -d

echo.
echo Esperando a que los servicios esten listos (10 segundos)...
timeout /t 10 /nobreak >nul

echo.
echo Abriendo Kiosco y Panel de Administracion...
start http://localhost:8000/app/kiosco/index.php
start http://localhost:8000/app/index.php

echo.
echo ==========================================
echo      SISTEMA INICIADO CORRECTAMENTE
echo ==========================================
echo Puedes minimizar esta ventana.
pause
