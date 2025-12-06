<?php
session_start();
if (!isset($_SESSION['id_escuela'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

// Listar PCs disponibles
$id_escuela = $_SESSION['id_escuela'];
$stmt = $conn->prepare("SELECT * FROM COMPUTADORA WHERE estado = TRUE AND id_escuela = :escuela ORDER BY nombre ASC");
$stmt->execute([':escuela' => $id_escuela]);
$pcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usar Computadora - Kiosco</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="kiosco-mode">
    <div class="kiosco-card" style="max-width: 800px;">
        <h2><i class="fas fa-desktop"></i> Computadoras Disponibles</h2>
        
        <?php if(empty($pcs)): ?>
            <p>No hay computadoras disponibles en este momento.</p>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 20px;">
            <?php foreach($pcs as $pc): ?>
                <a href="validar_nfc.php?action=borrow_pc&item_id=<?= $pc['id_computadora'] ?>" class="kiosco-btn btn-borrow-pc" style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-decoration: none;">
                    <i class="fas fa-laptop fa-2x mb-2"></i>
                    <span><?= htmlspecialchars($pc['nombre']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <a href="index.php" class="kiosco-btn" style="background: #555; margin-top: 20px;">Volver</a>
    </div>
</body>
</html>
