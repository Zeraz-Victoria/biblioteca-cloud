<?php
session_start();
if (!isset($_SESSION['id_escuela'])) {
    header("Location: ../login.php");
    exit;
}
$id_escuela = $_SESSION['id_escuela'];
require_once '../conexion.php';

$step = 1;
$socio = null;
$mensaje = "";

$conexion = new Conexion();
$conn = $conexion->conectar();

// Paso 1: Identificar Usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nfc_id'])) {
    $nfc_id = $_POST['nfc_id'];
    
    $stmt = $conn->prepare("SELECT * FROM SOCIO WHERE nfc_id = :nfc_id AND Estado = TRUE AND id_escuela = :escuela");
    $stmt->bindParam(':nfc_id', $nfc_id);
    $stmt->bindParam(':escuela', $id_escuela);
    $stmt->execute();
    $socio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$socio) {
        $stmt = $conn->prepare("SELECT * FROM SOCIO WHERE DNI = :dni AND Estado = TRUE AND id_escuela = :escuela");
        $stmt->bindParam(':dni', $nfc_id);
        $stmt->bindParam(':escuela', $id_escuela);
        $stmt->execute();
        $socio = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($socio) {
        $step = 2;
        $_SESSION['return_socio'] = $socio;
    } else {
        $mensaje = "Usuario no encontrado.";
    }
} elseif (isset($_SESSION['return_socio'])) {
    $step = 2;
    $socio = $_SESSION['return_socio'];
}

// Paso 2: Procesar Devolución
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return_type'])) {
    $type = $_POST['return_type'];
    $id = $_POST['item_id'];
    
    try {
        if ($type == 'book') {
            $id_libro = $_POST['book_id'];
            $conn->prepare("UPDATE PRESTAMOS SET Fecha_devolucion_real = CURRENT_DATE WHERE Id_prestamo = :id")->execute([':id' => $id]);
            $conn->prepare("UPDATE LIBRO SET Ejemplares_disponibles = Ejemplares_disponibles + 1 WHERE Id_libro = :id")->execute([':id' => $id_libro]);
            $mensaje = "Libro devuelto correctamente.";
        } elseif ($type == 'pc') {
            $id_pc = $_POST['pc_id'];
            $conn->prepare("UPDATE PRESTAMO_COMPUTADORA SET fecha_devolucion = CURRENT_TIMESTAMP WHERE id_prestamo_pc = :id")->execute([':id' => $id]);
            $conn->prepare("UPDATE COMPUTADORA SET estado = TRUE WHERE id_computadora = :id")->execute([':id' => $id_pc]);
            $mensaje = "Computadora liberada correctamente.";
        }
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Cargar Préstamos si estamos en paso 2
$prestamos_libros = [];
$prestamos_pcs = [];

if ($step == 2 && $socio) {
    // Libros
    $stmt = $conn->prepare("
        SELECT p.Id_prestamo, l.Titulo_libro, l.Id_libro, p.Fecha_prestamo 
        FROM PRESTAMOS p
        JOIN LIBRO l ON p.Id_libro = l.Id_libro
        WHERE p.Id_socio = :socio AND p.Fecha_devolucion_real IS NULL
    ");
    $stmt->execute([':socio' => $socio['id_socio']]);
    $prestamos_libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // PCs
    $stmt = $conn->prepare("
        SELECT p.id_prestamo_pc, c.nombre, c.id_computadora, p.fecha_prestamo 
        FROM PRESTAMO_COMPUTADORA p
        JOIN COMPUTADORA c ON p.id_computadora = c.id_computadora
        WHERE p.id_socio = :socio AND p.fecha_devolucion IS NULL
    ");
    $stmt->execute([':socio' => $socio['id_socio']]);
    $prestamos_pcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devoluciones - Kiosco</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="kiosco-mode">
    <div class="kiosco-card" style="max-width: 800px;">
        <h2><i class="fas fa-undo"></i> Devoluciones</h2>
        
        <?php if($mensaje): ?>
            <div style="background: #4caf50; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
            <p>Para ver tus préstamos, identifica tu usuario.</p>
            <form action="" method="POST">
                <input type="text" name="nfc_id" class="nfc-input" placeholder="Escanea tu chip..." autofocus>
            </form>
            <script>
                document.querySelector('.nfc-input').focus();
            </script>
        <?php else: ?>
            <h3>Hola, <?= htmlspecialchars($socio['nombre_socio']) ?></h3>
            
            <?php if(empty($prestamos_libros) && empty($prestamos_pcs)): ?>
                <p>No tienes préstamos activos.</p>
            <?php endif; ?>

            <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                <!-- Libros -->
                <?php foreach($prestamos_libros as $p): ?>
                    <div class="kiosco-item">
                        <div>
                            <i class="fas fa-book" style="color: #003366;"></i> <strong><?= htmlspecialchars($p['titulo_libro']) ?></strong><br>
                            <small><?= $p['fecha_prestamo'] ?></small>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="return_type" value="book">
                            <input type="hidden" name="item_id" value="<?= $p['id_prestamo'] ?>">
                            <input type="hidden" name="book_id" value="<?= $p['id_libro'] ?>">
                            <button type="submit" class="kiosco-btn btn-return-book" style="width: auto; padding: 5px 15px; font-size: 0.9rem; margin: 0;">Devolver</button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <!-- PCs -->
                <?php foreach($prestamos_pcs as $p): ?>
                    <div class="kiosco-item">
                        <div>
                            <i class="fas fa-desktop" style="color: #003366;"></i> <strong><?= htmlspecialchars($p['nombre']) ?></strong><br>
                            <small><?= $p['fecha_prestamo'] ?></small>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="return_type" value="pc">
                            <input type="hidden" name="item_id" value="<?= $p['id_prestamo_pc'] ?>">
                            <input type="hidden" name="pc_id" value="<?= $p['id_computadora'] ?>">
                            <button type="submit" class="kiosco-btn btn-return-pc" style="width: auto; padding: 5px 15px; font-size: 0.9rem; margin: 0;">Liberar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <a href="logout.php" class="kiosco-btn" style="background: #e74a3b; margin-top: 20px;">Cerrar Sesión</a>
        <?php endif; ?>

        <a href="index.php" class="kiosco-btn" style="background: #6c757d; margin-top: 10px;">Volver al Menú</a>
    </div>
</body>
</html>
