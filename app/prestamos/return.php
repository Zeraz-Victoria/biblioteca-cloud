<?php
require_once '../conexion.php';

if (isset($_GET['id'])) {
    $id_prestamo = $_GET['id'];
    $fecha_devolucion_real = date('Y-m-d');

    try {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $conn->beginTransaction();

        // 1. Obtener el ID del libro asociado al préstamo
        $stmtGet = $conn->prepare("SELECT id_libro FROM PRESTAMOS WHERE id_prestamo = :id_prestamo");
        $stmtGet->bindParam(':id_prestamo', $id_prestamo);
        $stmtGet->execute();
        $prestamo = $stmtGet->fetch(PDO::FETCH_ASSOC);

        if ($prestamo) {
            $id_libro = $prestamo['id_libro'];

            // 2. Actualizar fecha de devolución
            $sqlUpdatePrestamo = "UPDATE PRESTAMOS SET fecha_devolucion_real = :fecha_real WHERE id_prestamo = :id_prestamo";
            $stmtUpdatePrestamo = $conn->prepare($sqlUpdatePrestamo);
            $stmtUpdatePrestamo->bindParam(':fecha_real', $fecha_devolucion_real);
            $stmtUpdatePrestamo->bindParam(':id_prestamo', $id_prestamo);
            $stmtUpdatePrestamo->execute();

            // 3. Aumentar stock del libro
            $sqlUpdateLibro = "UPDATE LIBRO SET ejemplares_disponibles = ejemplares_disponibles + 1 WHERE id_libro = :id_libro";
            $stmtUpdateLibro = $conn->prepare($sqlUpdateLibro);
            $stmtUpdateLibro->bindParam(':id_libro', $id_libro);
            $stmtUpdateLibro->execute();

            $conn->commit();
            header("Location: index.php?msg=libro_devuelto");
        } else {
            die("Error: Préstamo no encontrado.");
        }

    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
