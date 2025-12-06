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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_prestamo_pc'])) {
    $id_prestamo = $_POST['id_prestamo_pc'];
    $id_pc = $_POST['id_computadora'];
    $fecha_devolucion = date('Y-m-d H:i:s');

    try {
        // Actualizar préstamo
        $stmt = $conn->prepare("UPDATE PRESTAMO_COMPUTADORA SET fecha_devolucion = :fecha WHERE id_prestamo_pc = :id");
        $stmt->bindParam(':fecha', $fecha_devolucion);
        $stmt->bindParam(':id', $id_prestamo);
        $stmt->execute();

        // Liberar PC
        $stmt = $conn->prepare("UPDATE COMPUTADORA SET estado = TRUE WHERE id_computadora = :id");
        $stmt->bindParam(':id', $id_pc);
        $stmt->execute();

        $mensaje = "¡Computadora liberada con éxito!";
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Listar préstamos activos de PC
$stmt = $conn->prepare("
    SELECT p.id_prestamo_pc, c.nombre, c.id_computadora, p.fecha_prestamo 
    FROM PRESTAMO_COMPUTADORA p
    JOIN COMPUTADORA c ON p.id_computadora = c.id_computadora
    WHERE p.id_socio = :socio AND p.fecha_devolucion IS NULL
");
$stmt->bindParam(':socio', $socio['id_socio']);
$stmt->execute();
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Liberar Computadora - Kiosco</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="kiosco-mode">
    <div class="kiosco-card" style="max-width: 800px;">
        <h2><i class="fas fa-power-off"></i> Liberar Computadora</h2>
        
        <?php if($mensaje): ?>
            <div style="background: #4caf50; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <?php if(empty($prestamos)): ?>
            <p>No estás usando ninguna computadora actualmente.</p>
        <?php else: ?>
            <div style="text-align: left;">
                <?php foreach($prestamos as $p): ?>
                    <div style="background: #233050; padding: 15px; margin-bottom: 10px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3 style="margin: 0;"><?= htmlspecialchars($p['nombre']) ?></h3>
                            <small>Inicio: <?= $p['fecha_prestamo'] ?></small>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="id_prestamo_pc" value="<?= $p['id_prestamo_pc'] ?>">
                            <input type="hidden" name="id_computadora" value="<?= $p['id_computadora'] ?>">
                            <button type="submit" class="kiosco-btn btn-return-pc" style="width: auto; padding: 10px 20px; font-size: 1rem;">
                                Liberar
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
