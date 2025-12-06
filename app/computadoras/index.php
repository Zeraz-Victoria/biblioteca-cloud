<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

$id_escuela = $_SESSION['id_escuela'];
$stmt = $conn->prepare("SELECT * FROM COMPUTADORA WHERE id_escuela = :escuela ORDER BY nombre ASC");
$stmt->execute([':escuela' => $id_escuela]);
$computadoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Computadoras</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Computadora
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($computadoras as $pc): ?>
                        <tr>
                            <td><?= $pc['id_computadora'] ?></td>
                            <td><?= htmlspecialchars($pc['nombre']) ?></td>
                            <td><?= htmlspecialchars($pc['marca']) ?></td>
                            <td>
                                <?php if($pc['estado']): ?>
                                    <span class="badge bg-success">Disponible</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ocupada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
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
