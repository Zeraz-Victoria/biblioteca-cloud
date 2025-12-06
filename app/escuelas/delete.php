<?php
require_once '../conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Proteger la escuela demo
    if ($id == 1) {
        header("Location: index.php?error=demo");
        exit;
    }

    $conexion = new Conexion();
    $conn = $conexion->conectar();

    try {
        // Borrado en cascada manual (o confiar en FKs si estuvieran configuradas con ON DELETE CASCADE, pero mejor manual por seguridad)
        // Por ahora, solo desactivamos para no perder datos históricos accidentalmente, o borramos si el usuario insiste.
        // Vamos a hacer un borrado "soft" (desactivar) o hard delete? El usuario pidió "borrar".
        // Haremos borrado real de dependencias primero.
        
        $conn->beginTransaction();
        
        // Borrar dependencias (orden inverso)
        $conn->prepare("DELETE FROM PRESTAMOS WHERE id_escuela = :id")->execute([':id' => $id]);
        $conn->prepare("DELETE FROM PRESTAMO_COMPUTADORA WHERE id_escuela = :id")->execute([':id' => $id]);
        $conn->prepare("DELETE FROM LIBRO WHERE id_escuela = :id")->execute([':id' => $id]);
        $conn->prepare("DELETE FROM SOCIO WHERE id_escuela = :id")->execute([':id' => $id]);
        $conn->prepare("DELETE FROM USUARIOS WHERE id_escuela = :id")->execute([':id' => $id]);
        $conn->prepare("DELETE FROM COMPUTADORA WHERE id_escuela = :id")->execute([':id' => $id]);
        $conn->prepare("DELETE FROM AUTOR WHERE id_escuela = :id")->execute([':id' => $id]);
        $conn->prepare("DELETE FROM CATEGORIA WHERE id_escuela = :id")->execute([':id' => $id]);
        $conn->prepare("DELETE FROM EDITORIAL WHERE id_escuela = :id")->execute([':id' => $id]);
        
        // Finalmente borrar escuela
        $conn->prepare("DELETE FROM ESCUELAS WHERE id_escuela = :id")->execute([':id' => $id]);
        
        $conn->commit();
        header("Location: index.php?msg=deleted");
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error al eliminar: " . $e->getMessage();
    }
}
