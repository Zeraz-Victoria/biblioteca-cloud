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
$libro = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM LIBRO WHERE Id_libro = ? AND id_escuela = ?");
    $stmt->execute([$id, $id_escuela]);
    $libro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$libro && $id) {
        // Si hay ID pero no encuentra libro (o no es de esta escuela), redirigir
        header("Location: libros.php");
        exit;
    }
}

// Obtener autores y categorías para los select (Filtrados por escuela)
$stmt_autores = $conn->prepare("SELECT Id_autor, Nombre_autor FROM AUTOR WHERE id_escuela = ? ORDER BY Nombre_autor");
$stmt_autores->execute([$id_escuela]);
$autores = $stmt_autores->fetchAll(PDO::FETCH_ASSOC);

$stmt_cats = $conn->prepare("SELECT Id_categoria, Nombre_categoria FROM CATEGORIA WHERE id_escuela = ? ORDER BY Nombre_categoria");
$stmt_cats->execute([$id_escuela]);
$categorias = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= $libro ? 'Editar Libro' : 'Nuevo Libro' ?></h4>
            </div>
            <div class="card-body">
                <form action="libros_actions.php" method="POST">
                    <input type="hidden" name="id_libro" value="<?= $libro['id_libro'] ?? '' ?>">
                    <input type="hidden" name="action" value="<?= $libro ? 'update' : 'create' ?>">

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del Libro</label>
                        <input type="text" class="form-control" id="titulo" name="titulo_libro" value="<?= htmlspecialchars($libro['titulo_libro'] ?? '') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <select class="form-select" id="autor" name="id_autor" required>
                                <option value="">Seleccione un autor</option>
                                <?php foreach ($autores as $autor): ?>
                                    <option value="<?= $autor['id_autor'] ?>" <?= ($libro && $libro['id_autor'] == $autor['id_autor']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($autor['nombre_autor']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria" name="id_categoria" required>
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria['id_categoria'] ?>" <?= ($libro && $libro['id_categoria'] == $categoria['id_categoria']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="anio" class="form-label">Año de Publicación</label>
                            <input type="number" class="form-control" id="anio" name="anio_publicacion" value="<?= $libro['anio_publicacion'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="total" class="form-label">Total Ejemplares</label>
                            <input type="number" class="form-control" id="total" name="total_ejemplares" value="<?= $libro['ejemplares_totales'] ?? 0 ?>" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="disponibles" class="form-label">Ejemplares Disponibles</label>
                            <input type="number" class="form-control" id="disponibles" name="ejemplares_disponibles" value="<?= $libro['ejemplares_disponibles'] ?? 0 ?>" min="0" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="libros.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
