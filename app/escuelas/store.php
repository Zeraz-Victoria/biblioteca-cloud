<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    
    $admin_nombre = $_POST['admin_nombre'];
    $admin_email = $_POST['admin_email'];
    $admin_pass = $_POST['admin_pass'];

    $conexion = new Conexion();
    $conn = $conexion->conectar();

    try {
        $conn->beginTransaction();

        // 1. Crear Escuela
        $sql = "INSERT INTO ESCUELAS (nombre_escuela, email_contacto, direccion, telefono) VALUES (:nombre, :email, :dir, :tel)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':dir' => $direccion,
            ':tel' => $telefono
        ]);
        $id_escuela = $conn->lastInsertId();

        // 2. Crear Admin para esa escuela
        $sql_user = "INSERT INTO USUARIOS (id_escuela, Nombre_usuario, Correo, Contrasena, Tipo_usuario) VALUES (:escuela, :nombre, :email, :pass, 'admin')";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->execute([
            ':escuela' => $id_escuela,
            ':nombre' => $admin_nombre,
            ':email' => $admin_email,
            ':pass' => $admin_pass
        ]);

        $conn->commit();
        header("Location: index.php?msg=created");
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
