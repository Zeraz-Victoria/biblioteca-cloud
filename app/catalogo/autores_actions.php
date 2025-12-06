<?php
session_start();
if (!isset($_SESSION['id_escuela'])) {
    header("Location: ../login.php");
    exit;
}
$id_escuela = $_SESSION['id_escuela'];

require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['action'])) {
    $conexion = new Conexion();
    $conn = $conexion->conectar();

    $action = $_REQUEST['action'] ?? '';

    try {
        if ($action === 'create') {
            $nombre = $_POST['nombre_autor'];

            $sql = "INSERT INTO AUTOR (id_escuela, Nombre_autor) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_escuela, $nombre]);

        } elseif ($action === 'update') {
            $id = $_POST['id_autor'];
            $nombre = $_POST['nombre_autor'];

            $sql = "UPDATE AUTOR SET Nombre_autor = ? WHERE Id_autor = ? AND id_escuela = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $id, $id_escuela]);

        } elseif ($action === 'delete') {
            $id = $_GET['id'];
            $sql = "DELETE FROM AUTOR WHERE Id_autor = ? AND id_escuela = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id, $id_escuela]);
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    header("Location: autores.php");
    exit;
}
?>
