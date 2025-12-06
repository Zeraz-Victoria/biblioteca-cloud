<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_socio = $_POST['id_socio'];
    $id_libro = $_POST['id_libro'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_dev_esperada = $_POST['fecha_dev_esperada'];

    try {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $conn->beginTransaction();

        // 1. Verificar disponibilidad del libro
        $stmtCheck = $conn->prepare("SELECT ejemplares_disponibles FROM LIBRO WHERE id_libro = :id_libro");
        $stmtCheck->bindParam(':id_libro', $id_libro);
        $stmtCheck->execute();
        $libro = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($libro['ejemplares_disponibles'] > 0) {
            // 2. Registrar el préstamo
            $sql = "INSERT INTO PRESTAMOS (Id_libro, Id_socio, Fecha_prestamo, Fecha_dev_esperada) 
                    VALUES (:id_libro, :id_socio, :fecha_prestamo, :fecha_dev_esperada)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_libro', $id_libro);
            $stmt->bindParam(':id_socio', $id_socio);
            $stmt->bindParam(':fecha_prestamo', $fecha_prestamo);
            $stmt->bindParam(':fecha_dev_esperada', $fecha_dev_esperada);
            $stmt->execute();

            // 3. Actualizar stock del libro
            $sqlUpdate = "UPDATE LIBRO SET ejemplares_disponibles = ejemplares_disponibles - 1 WHERE id_libro = :id_libro";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':id_libro', $id_libro);
            $stmtUpdate->execute();

            $conn->commit();
            header("Location: index.php?msg=prestamo_registrado");
        } else {
            $conn->rollBack();
            die("Error: No hay ejemplares disponibles de este libro.");
        }

    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
