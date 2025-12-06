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
$stmt = $conn->prepare("SELECT * FROM AUTOR WHERE id_escuela = :escuela ORDER BY Nombre_autor ASC");
$stmt->execute([':escuela' => $id_escuela]);
$autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-user-edit me-2"></i>Gestión de Autores</h2>
    <a href="autores_form.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Nuevo Autor</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre Autor</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($autores) > 0): ?>
                        <?php foreach ($autores as $autor): ?>
                            <tr>
                                <td><?= $autor['id_autor'] ?></td>
                                <td><?= htmlspecialchars($autor['nombre_autor']) ?></td>
                                <td class="text-center">
                                    <a href="autores_form.php?id=<?= $autor['id_autor'] ?>" class="btn btn-sm btn-warning me-1" title="Editar"><i class="fas fa-edit"></i></a>
                                    <a href="autores_actions.php?action=delete&id=<?= $autor['id_autor'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este autor?');" title="Eliminar"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-3">No hay autores registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>