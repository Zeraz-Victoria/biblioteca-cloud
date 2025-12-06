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

$sql = "SELECT L.*, A.Nombre_autor, C.Nombre_categoria 
        FROM LIBRO L 
        LEFT JOIN AUTOR A ON L.Id_autor = A.Id_autor
        LEFT JOIN CATEGORIA C ON L.Id_categoria = C.Id_categoria
        WHERE L.id_escuela = :escuela
        ORDER BY L.Id_libro ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([':escuela' => $id_escuela]);
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-book me-2"></i>Gestión de Libros</h2>
    <a href="libros_form.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Nuevo Libro</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Categoría</th>
                        <th>Año</th>
                        <th class="text-center">Disp. / Total</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($libros) > 0): ?>
                        <?php foreach ($libros as $libro): ?>
                            <tr>
                                <td><?= $libro['id_libro'] ?></td>
                                <td><strong><?= htmlspecialchars($libro['titulo_libro']) ?></strong></td>
                                <td><?= htmlspecialchars($libro['nombre_autor'] ?? 'Desconocido') ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($libro['nombre_categoria'] ?? 'General') ?></span></td>
                                <td><?= $libro['anio_publicacion'] ?></td>
                                <td class="text-center">
                                    <?= $libro['ejemplares_disponibles'] ?> / <?= $libro['ejemplares_totales'] ?>
                                </td>
                                <td class="text-center">
                                    <a href="libros_form.php?id=<?= $libro['id_libro'] ?>" class="btn btn-sm btn-warning me-1" title="Editar"><i class="fas fa-edit"></i></a>
                                    <a href="libros_actions.php?action=delete&id=<?= $libro['id_libro'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este libro?');" title="Eliminar"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-3">No hay libros registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
