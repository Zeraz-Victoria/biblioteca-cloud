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
            $titulo = $_POST['titulo_libro'];
            $autor = $_POST['id_autor'];
            $categoria = $_POST['id_categoria'];
            $anio = $_POST['anio_publicacion'];
            $total = $_POST['total_ejemplares'];
            $disponibles = $_POST['ejemplares_disponibles'];

            $sql = "INSERT INTO LIBRO (id_escuela, Titulo_libro, Id_autor, Id_categoria, Anio_publicacion, Ejemplares_totales, Ejemplares_disponibles) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_escuela, $titulo, $autor, $categoria, $anio, $total, $disponibles]);

        } elseif ($action === 'update') {
            $id = $_POST['id_libro'];
            $titulo = $_POST['titulo_libro'];
            $autor = $_POST['id_autor'];
            $categoria = $_POST['id_categoria'];
            $anio = $_POST['anio_publicacion'];
            $total = $_POST['total_ejemplares'];
            $disponibles = $_POST['ejemplares_disponibles'];

            $sql = "UPDATE LIBRO SET Titulo_libro = ?, Id_autor = ?, Id_categoria = ?, Anio_publicacion = ?, Ejemplares_totales = ?, Ejemplares_disponibles = ? WHERE Id_libro = ? AND id_escuela = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$titulo, $autor, $categoria, $anio, $total, $disponibles, $id, $id_escuela]);

        } elseif ($action === 'delete') {
            $id = $_GET['id'];
            $sql = "DELETE FROM LIBRO WHERE Id_libro = ? AND id_escuela = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id, $id_escuela]);
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    header("Location: libros.php");
    exit;
}
?>
