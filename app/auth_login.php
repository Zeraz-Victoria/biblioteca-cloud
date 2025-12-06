<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conexion = new Conexion();
    $conn = $conexion->conectar();

    // Buscar usuario
    $stmt = $conn->prepare("SELECT * FROM USUARIOS WHERE Correo = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar contraseña (en producción usar password_verify)
    if ($user && $user['contrasena'] === $password) {
        // Login Exitoso
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['id_escuela'] = $user['id_escuela']; // CLAVE PARA SAAS
        $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

        // Obtener nombre de la escuela para mostrarlo
        $stmt = $conn->prepare("SELECT nombre_escuela FROM ESCUELAS WHERE id_escuela = :id");
        $stmt->execute([':id' => $user['id_escuela']]);
        $_SESSION['nombre_escuela'] = $stmt->fetchColumn();

        // Redirección basada en el rol
        if ($user['tipo_usuario'] == 'superadmin') {
            header("Location: escuelas/index.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        header("Location: login.php?error=1");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
