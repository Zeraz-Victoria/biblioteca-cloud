<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_escuela'])) {
    header("Location: /app/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - <?= htmlspecialchars($_SESSION['nombre_escuela']) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/app/css/style.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="/app/index.php">
        <i class="fas fa-school mr-2"></i> <?= htmlspecialchars($_SESSION['nombre_escuela']) ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        
        <?php if(isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'superadmin'): ?>
            <!-- MENÚ SUPER ADMIN -->
            <li class="nav-item">
              <a class="nav-link" href="/app/escuelas/index.php" style="color: #ffc107 !important; font-weight: bold;">
                  <i class="fas fa-university"></i> Gestión de Escuelas
              </a>
            </li>

        <?php else: ?>
            <!-- MENÚ ADMINISTRADOR DE BIBLIOTECA -->
            <!-- MENÚ ADMINISTRADOR DE BIBLIOTECA -->
            <!-- Se eliminaron los enlaces repetitivos (Libros, Autores, etc) para limpiar la pantalla 
                 ya que ahora el acceso es por los íconos grandes del Dashboard -->
            
            <li class="nav-item">
                <a class="nav-link btn btn-outline-light ml-2" href="/app/kiosco/index.php" target="_blank">
                    <i class="fas fa-tablet-alt"></i> Modo Kiosco
                </a>
            </li>
        <?php endif; ?>

      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link btn btn-danger text-white ml-3" href="/app/logout.php">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container">
