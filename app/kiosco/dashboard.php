<?php
session_start();
if (!isset($_SESSION['kiosco_socio'])) {
    header("Location: index.php");
    exit;
}
$socio = $_SESSION['kiosco_socio'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal - Kiosco</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="kiosco-mode">

    <div class="kiosco-card">
        <h2>Hola, <?= htmlspecialchars($socio['nombre_socio']) ?></h2>
        <p>¿Qué deseas hacer hoy?</p>

        <a href="prestamo_libro.php" class="kiosco-btn btn-borrow-book">
            <i class="fas fa-book"></i> Pedir Libro
        </a>
        <a href="devolucion_libro.php" class="kiosco-btn btn-return-book">
            <i class="fas fa-undo"></i> Devolver Libro
        </a>
        <a href="prestamo_pc.php" class="kiosco-btn btn-borrow-pc">
            <i class="fas fa-desktop"></i> Usar Computadora
        </a>
        <a href="devolucion_pc.php" class="kiosco-btn btn-return-pc">
            <i class="fas fa-power-off"></i> Liberar Computadora
        </a>

        <a href="logout.php" class="btn" style="color: #e94560; margin-top: 20px;">
            <i class="fas fa-sign-out-alt"></i> Salir
        </a>
    </div>

</body>
</html>
