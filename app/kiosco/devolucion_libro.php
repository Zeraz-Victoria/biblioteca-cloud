<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['kiosco_socio'])) {
    header("Location: index.php");
    exit;
}

$socio = $_SESSION['kiosco_socio'];
$mensaje = "";

$conexion = new Conexion();
$conn = $conexion->conectar();

// Procesar devolución
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_prestamo'])) {
    $id_prestamo = $_POST['id_prestamo'];
    $id_libro = $_POST['id_libro'];
    $fecha_devolucion = date('Y-m-d');

    try {
        // Actualizar préstamo
        $stmt = $conn->prepare("UPDATE PRESTAMOS SET Fecha_devolucion_real = :fecha WHERE Id_prestamo = :id");
        $stmt->bindParam(':fecha', $fecha_devolucion);
        $stmt->bindParam(':id', $id_prestamo);
        $stmt->execute();

        // Actualizar stock
        $stmt = $conn->prepare("UPDATE LIBRO SET Ejemplares_disponibles = Ejemplares_disponibles + 1 WHERE Id_libro = :id");
        $stmt->bindParam(':id', $id_libro);
        $stmt->execute();

        $mensaje = "¡Libro devuelto con éxito!";
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Listar préstamos activos
$stmt = $conn->prepare("
    SELECT p.Id_prestamo, l.Titulo_libro, l.Id_libro, p.Fecha_prestamo 
    FROM PRESTAMOS p
    JOIN LIBRO l ON p.Id_libro = l.Id_libro
    WHERE p.Id_socio = :socio AND p.Fecha_devolucion_real IS NULL
");
$stmt->bindParam(':socio', $socio['id_socio']);
$stmt->execute();
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devolver Libro - Kiosco</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="kiosco-mode">
    <div class="kiosco-card" style="max-width: 800px;">
        <h2><i class="fas fa-undo"></i> Devolver Libro</h2>
        
        <?php if($mensaje): ?>
            <div style="background: #4caf50; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <?php if(empty($prestamos)): ?>
            <p>No tienes libros pendientes de devolver.</p>
        <?php else: ?>
            <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                <?php foreach($prestamos as $p): ?>
                    <div style="background: #233050; padding: 15px; margin-bottom: 10px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3 style="margin: 0;"><?= htmlspecialchars($p['titulo_libro']) ?></h3>
                            <small>Prestado el: <?= $p['fecha_prestamo'] ?></small>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="id_prestamo" value="<?= $p['id_prestamo'] ?>">
                            <input type="hidden" name="id_libro" value="<?= $p['id_libro'] ?>">
                            <button type="submit" class="kiosco-btn btn-return-book" style="width: auto; padding: 10px 20px; font-size: 1rem;">
                                Devolver
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <a href="dashboard.php" class="kiosco-btn" style="background: #555; margin-top: 20px;">Volver</a>
    </div>
</body>
</html>
