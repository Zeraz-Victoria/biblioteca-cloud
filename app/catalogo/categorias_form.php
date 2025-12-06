<?php
session_start();
if (!isset($_SESSION['id_escuela'])) {
    header("Location: ../login.php");
    exit;
}
$id_escuela = $_SESSION['id_escuela'];

require_once '../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

$id = $_GET['id'] ?? null;
$categoria = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM CATEGORIA WHERE Id_categoria = ? AND id_escuela = ?");
    $stmt->execute([$id, $id_escuela]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<?php include '../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= $categoria ? 'Editar Categoría' : 'Nueva Categoría' ?></h4>
            </div>
            <div class="card-body">
                <form action="categorias_actions.php" method="POST">
                    <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?? '' ?>">
                    <input type="hidden" name="action" value="<?= $categoria ? 'update' : 'create' ?>">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de Categoría</label>
                        <input type="text" class="form-control" id="nombre" name="nombre_categoria" value="<?= htmlspecialchars($categoria['nombre_categoria'] ?? '') ?>" required>
                    </div>



                    <div class="d-flex justify-content-between">
                        <a href="categorias.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
