<?php
session_start();
if (!isset($_SESSION['id_escuela'])) {
    // Si no hay escuela definida, redirigir al login general
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosco Biblioteca</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="kiosco-mode">

    <div class="kiosco-card">
        <div style="margin-bottom: 30px;">
            <i class="fas fa-book-reader fa-4x" style="color: #003366; margin-bottom: 15px;"></i>
            <h1 style="font-size: 2.5rem; text-transform: uppercase; letter-spacing: 2px; margin: 0;">BIBLIOTECA ESCOLAR</h1>
            <h2 style="font-size: 1.2rem; font-weight: 400; color: #666;"><?= htmlspecialchars($_SESSION['nombre_escuela'] ?? 'Mi Biblioteca') ?></h2>
        </div>
        
        <a href="prestamo_libro.php" class="kiosco-btn btn-borrow-book">
            <i class="fas fa-search"></i> Buscar y Pedir Libro
        </a>
        <a href="prestamo_pc.php" class="kiosco-btn btn-borrow-pc">
            <i class="fas fa-desktop"></i> Usar Computadora
        </a>
        <a href="devolucion.php" class="kiosco-btn btn-return-book" style="background: linear-gradient(45deg, #ff9966, #ff5e62);">
            <i class="fas fa-undo"></i> Devoluciones
        </a>
    </div>

</body>
</html>
