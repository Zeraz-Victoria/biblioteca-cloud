<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

// Consulta para obtener préstamos con detalles de libro y socio
$id_escuela = $_SESSION['id_escuela'];
$sql = "SELECT p.id_prestamo, l.titulo_libro, s.nombre_socio, p.fecha_prestamo, p.fecha_dev_esperada, p.fecha_devolucion_real 
        FROM PRESTAMOS p
        JOIN LIBRO l ON p.id_libro = l.id_libro
        JOIN SOCIO s ON p.id_socio = s.id_socio
        WHERE p.id_escuela = :escuela
        ORDER BY p.fecha_prestamo DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':escuela' => $id_escuela]);
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Préstamos</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Préstamo
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            <th>Socio</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Esperada</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prestamos as $prestamo): ?>
                        <tr>
                            <td><?= $prestamo['id_prestamo'] ?></td>
                            <td><?= htmlspecialchars($prestamo['titulo_libro']) ?></td>
                            <td><?= htmlspecialchars($prestamo['nombre_socio']) ?></td>
                            <td><?= $prestamo['fecha_prestamo'] ?></td>
                            <td><?= $prestamo['fecha_dev_esperada'] ?></td>
                            <td>
                                <?php if($prestamo['fecha_devolucion_real']): ?>
                                    <span class="badge bg-success">Devuelto (<?= $prestamo['fecha_devolucion_real'] ?>)</span>
                                <?php else: ?>
                                    <?php if(date('Y-m-d') > $prestamo['fecha_dev_esperada']): ?>
                                        <span class="badge bg-danger">Vencido</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!$prestamo['fecha_devolucion_real']): ?>
                                    <a href="return.php?id=<?= $prestamo['id_prestamo'] ?>" class="btn btn-sm btn-info" onclick="return confirm('¿Confirmar devolución?')">
                                        <i class="fas fa-undo"></i> Devolver
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
