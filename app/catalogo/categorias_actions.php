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
            $nombre = $_POST['nombre_categoria'];

            $sql = "INSERT INTO CATEGORIA (id_escuela, Nombre_categoria) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_escuela, $nombre]);

        } elseif ($action === 'update') {
            $id = $_POST['id_categoria'];
            $nombre = $_POST['nombre_categoria'];

            $sql = "UPDATE CATEGORIA SET Nombre_categoria = ? WHERE Id_categoria = ? AND id_escuela = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $id, $id_escuela]);

        } elseif ($action === 'delete') {
            $id = $_GET['id'];
            $sql = "DELETE FROM CATEGORIA WHERE Id_categoria = ? AND id_escuela = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id, $id_escuela]);
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    header("Location: categorias.php");
    exit;
}
?>
