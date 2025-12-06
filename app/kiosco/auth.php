<?php
session_start();
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nfc_id = $_POST['nfc_id'];
    $id_escuela = $_SESSION['id_escuela'];

    try {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $stmt = $conn->prepare("SELECT * FROM SOCIO WHERE nfc_id = :nfc_id AND Estado = TRUE AND id_escuela = :escuela");
        $stmt->bindParam(':nfc_id', $nfc_id);
        $stmt->bindParam(':escuela', $id_escuela);
        $stmt->execute();
        $socio = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($socio) {
            $_SESSION['kiosco_socio'] = $socio;
            header("Location: dashboard.php");
        } else {
            // Si no encuentra por NFC, intenta por DNI (fallback)
            $stmt = $conn->prepare("SELECT * FROM SOCIO WHERE DNI = :dni AND Estado = TRUE AND id_escuela = :escuela");
            $stmt->bindParam(':dni', $nfc_id);
            $stmt->bindParam(':escuela', $id_escuela);
            $stmt->execute();
            $socio = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($socio) {
                $_SESSION['kiosco_socio'] = $socio;
                header("Location: dashboard.php");
            } else {
                header("Location: index.php?error=no_encontrado");
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
