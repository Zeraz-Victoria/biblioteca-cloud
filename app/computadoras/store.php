<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $estado = true; // Disponible

    try {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = "INSERT INTO COMPUTADORA (nombre, marca, estado) VALUES (:nombre, :marca, :estado)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_BOOL);

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
