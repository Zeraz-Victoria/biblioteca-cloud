<?php
session_start();
if (!isset($_SESSION['id_escuela'])) {
    header("Location: ../login.php");
    exit;
}
$id_escuela = $_SESSION['id_escuela'];
require_once '../conexion.php';

$action = $_REQUEST['action'] ?? '';
$item_id = $_REQUEST['item_id'] ?? '';
$mensaje = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nfc_id'])) {
    $nfc_id = $_POST['nfc_id'];
    $conexion = new Conexion();
    $conn = $conexion->conectar();

    // 1. Validar Socio
    $stmt = $conn->prepare("SELECT * FROM SOCIO WHERE nfc_id = :nfc_id AND Estado = TRUE AND id_escuela = :escuela");
    $stmt->bindParam(':nfc_id', $nfc_id);
    $stmt->bindParam(':escuela', $id_escuela);
    $stmt->execute();
    $socio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$socio) {
        // Fallback a DNI
        $stmt = $conn->prepare("SELECT * FROM SOCIO WHERE DNI = :dni AND Estado = TRUE AND id_escuela = :escuela");
        $stmt->bindParam(':dni', $nfc_id);
        $stmt->bindParam(':escuela', $id_escuela);
        $stmt->execute();
        $socio = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($socio) {
        // 2. Ejecutar Acción
        try {
            if ($action == 'borrow_book') {
                // 2.1 Validar si ya tiene préstamos activos (Límite: 1 libro a la vez)
                $stmt = $conn->prepare("SELECT COUNT(*) FROM PRESTAMOS WHERE Id_socio = :socio AND Fecha_devolucion_real IS NULL");
                $stmt->bindParam(':socio', $socio['id_socio']);
                $stmt->execute();
                $active_loans = $stmt->fetchColumn();

                if ($active_loans > 0) {
                    $mensaje = "Error: Tienes libros pendientes de devolver. Solo puedes llevar uno a la vez.";
                } else {
                    // Verificar stock
                    $stmt = $conn->prepare("SELECT Ejemplares_disponibles FROM LIBRO WHERE Id_libro = :id AND id_escuela = :escuela");
                    $stmt->bindParam(':id', $item_id);
                    $stmt->bindParam(':escuela', $id_escuela);
                    $stmt->execute();
                    $libro = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($libro && $libro['ejemplares_disponibles'] > 0) {
                        $fecha_prestamo = date('Y-m-d');
                        $fecha_esperada = date('Y-m-d', strtotime('+7 days'));
                        
                        $sql = "INSERT INTO PRESTAMOS (id_escuela, Id_libro, Id_socio, Fecha_prestamo, Fecha_dev_esperada) 
                                VALUES (:escuela, :libro, :socio, :fecha, :esperada)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':escuela', $_SESSION['id_escuela']);
                        $stmt->bindParam(':libro', $item_id);
                        $stmt->bindParam(':socio', $socio['id_socio']);
                        $stmt->bindParam(':fecha', $fecha_prestamo);
                        $stmt->bindParam(':esperada', $fecha_esperada);
                        $stmt->execute();

                        $conn->prepare("UPDATE LIBRO SET Ejemplares_disponibles = Ejemplares_disponibles - 1 WHERE Id_libro = :id")->execute([':id' => $item_id]);
                        
                        $mensaje = "¡Préstamo exitoso! Disfruta tu libro, " . $socio['nombre_socio'];
                        $success = true;
                    } else {
                        $mensaje = "Error: Libro agotado.";
                    }
                }

            } elseif ($action == 'borrow_pc') {
                // 2.2 Validar si ya tiene PC asignada (Límite: 1 PC a la vez)
                $stmt = $conn->prepare("SELECT COUNT(*) FROM PRESTAMO_COMPUTADORA WHERE id_socio = :socio AND fecha_devolucion IS NULL");
                $stmt->bindParam(':socio', $socio['id_socio']);
                $stmt->execute();
                $active_pc = $stmt->fetchColumn();

                if ($active_pc > 0) {
                    $mensaje = "Error: Ya tienes una computadora asignada. Debes liberarla primero.";
                } else {
                    // Verificar PC
                    $stmt = $conn->prepare("SELECT estado FROM COMPUTADORA WHERE id_computadora = :id AND id_escuela = :escuela");
                    $stmt->bindParam(':id', $item_id);
                    $stmt->bindParam(':escuela', $id_escuela);
                    $stmt->execute();
                    $pc = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($pc && $pc['estado']) {
                        $sql = "INSERT INTO PRESTAMO_COMPUTADORA (id_escuela, id_computadora, id_socio) VALUES (:escuela, :pc, :socio)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':escuela', $_SESSION['id_escuela']);
                        $stmt->bindParam(':pc', $item_id);
                        $stmt->bindParam(':socio', $socio['id_socio']);
                        $stmt->execute();

                        $conn->prepare("UPDATE COMPUTADORA SET estado = FALSE WHERE id_computadora = :id")->execute([':id' => $item_id]);

                        $mensaje = "¡Computadora asignada! Hola, " . $socio['nombre_socio'];
                        $success = true;
                    } else {
                        $mensaje = "Error: Computadora ocupada.";
                    }
                }
            }
        } catch (PDOException $e) {
            $mensaje = "Error de base de datos: " . $e->getMessage();
        }
    } else {
        $mensaje = "Error: Chip NFC no reconocido o alumno inactivo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validar NFC - Kiosco</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="kiosco-mode">
    <div class="kiosco-card">
        <?php if ($success): ?>
            <i class="fas fa-check-circle fa-5x mb-4" style="color: #4caf50;"></i>
            <h2>¡Listo!</h2>
            <p class="lead"><?= $mensaje ?></p>
            <script>
                setTimeout(function() { window.location.href = 'index.php'; }, 3000);
            </script>
            <a href="index.php" class="kiosco-btn" style="background: #4caf50;">Volver al Inicio</a>
        <?php else: ?>
            <?php if ($mensaje): ?>
                <div style="background: #e74a3b; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <i class="fas fa-wifi fa-5x mb-4" style="color: #e94560;"></i>
            <h2>Confirmar Acción</h2>
            <p>Acerca tu credencial para confirmar</p>
            
            <form action="" method="POST">
                <input type="hidden" name="action" value="<?= htmlspecialchars($action) ?>">
                <input type="hidden" name="item_id" value="<?= htmlspecialchars($item_id) ?>">
                <input type="text" name="nfc_id" class="nfc-input" placeholder="Esperando chip..." autofocus autocomplete="off">
            </form>
            
            <a href="index.php" class="kiosco-btn" style="background: #e74a3b; margin-top: 20px;">Cancelar</a>
            
            <script>
                document.querySelector('.nfc-input').focus();
                document.body.addEventListener('click', function() {
                    document.querySelector('.nfc-input').focus();
                });
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
