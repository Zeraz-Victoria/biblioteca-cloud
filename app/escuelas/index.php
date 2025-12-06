<?php
include '../header.php';
require_once '../conexion.php';

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'superadmin') {
    header("Location: ../index.php");
    exit;
}

$conexion = new Conexion();
$conn = $conexion->conectar();

$sql = "SELECT * FROM ESCUELAS ORDER BY id_escuela ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$escuelas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Escuelas</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Escuela
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email Contacto</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($escuelas as $escuela): ?>
                            <tr>
                                <td><?= $escuela['id_escuela'] ?></td>
                                <td><strong><?= htmlspecialchars($escuela['nombre_escuela']) ?></strong></td>
                                <td><?= htmlspecialchars($escuela['email_contacto']) ?></td>
                                <td><?= date('d/m/Y', strtotime($escuela['fecha_registro'])) ?></td>
                                <td>
                                    <?php if($escuela['activo']): ?>
                                        <span class="badge badge-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $escuela['id_escuela'] ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Evitar borrar la escuela 1 (Demo) por seguridad -->
                                    <?php if($escuela['id_escuela'] != 1): ?>
                                        <a href="delete.php?id=<?= $escuela['id_escuela'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro? Esto borrará TODOS los datos de esta escuela.');">
                                            <i class="fas fa-trash"></i>
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
