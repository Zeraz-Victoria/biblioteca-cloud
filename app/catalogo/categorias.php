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
$stmt = $conn->prepare("SELECT * FROM CATEGORIA WHERE id_escuela = :escuela ORDER BY Nombre_categoria ASC");
$stmt->execute([':escuela' => $id_escuela]);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-tags me-2"></i>Gestión de Categorías</h2>
    <a href="categorias_form.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Nueva Categoría</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($categorias) > 0): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?= $categoria['id_categoria'] ?></td>
                                <td><?= htmlspecialchars($categoria['nombre_categoria']) ?></td>
                                <td class="text-center">
                                    <a href="categorias_form.php?id=<?= $categoria['id_categoria'] ?>" class="btn btn-sm btn-warning me-1" title="Editar"><i class="fas fa-edit"></i></a>
                                    <a href="categorias_actions.php?action=delete&id=<?= $categoria['id_categoria'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta categoría?');" title="Eliminar"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-3">No hay categorías registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
