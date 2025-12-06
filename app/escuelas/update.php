<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $activo = isset($_POST['activo']) ? 'TRUE' : 'FALSE';

    $conexion = new Conexion();
    $conn = $conexion->conectar();

    $sql = "UPDATE ESCUELAS SET nombre_escuela = :nombre, email_contacto = :email, direccion = :dir, telefono = :tel, activo = $activo WHERE id_escuela = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':dir' => $direccion,
        ':tel' => $telefono,
        ':id' => $id
    ]);

    header("Location: index.php?msg=updated");
}
