<?php
require_once '../conexion.php';

session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_escuela = $_POST['id_escuela'];
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $nfc_id = $_POST['nfc_id'] ?: null;
    $fecha_registro = date('Y-m-d');
    $estado = true; // Activo por defecto

    try {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = "INSERT INTO SOCIO (id_escuela, Nombre_socio, DNI, Telefono, Email, Direccion, Fecha_registro, Estado, nfc_id) 
                VALUES (:escuela, :nombre, :dni, :telefono, :email, :direccion, :fecha, :estado, :nfc_id)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':escuela', $id_escuela);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':fecha', $fecha_registro);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_BOOL);
        $stmt->bindParam(':nfc_id', $nfc_id);

        if ($stmt->execute()) {
            header("Location: index.php?msg=registrado");
        } else {
            echo "Error al registrar.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
