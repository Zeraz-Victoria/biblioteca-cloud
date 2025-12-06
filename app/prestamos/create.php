<?php
require_once '../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

// Obtener libros disponibles
$libros = $conn->query("SELECT id_libro, titulo_libro FROM LIBRO WHERE ejemplares_disponibles > 0 ORDER BY titulo_libro ASC")->fetchAll(PDO::FETCH_ASSOC);

// Obtener socios activos
$socios = $conn->query("SELECT id_socio, nombre_socio FROM SOCIO WHERE estado = true ORDER BY nombre_socio ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registrar Préstamo</h4>
                </div>
                <div class="card-body">
                    <form action="store.php" method="POST">
                        <div class="mb-3">
                            <label for="id_socio" class="form-label">Socio</label>
                            <select class="form-select" id="id_socio" name="id_socio" required>
                                <option value="">Seleccione un socio...</option>
                                <?php foreach($socios as $socio): ?>
                                    <option value="<?= $socio['id_socio'] ?>"><?= htmlspecialchars($socio['nombre_socio']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="id_libro" class="form-label">Libro</label>
                            <select class="form-select" id="id_libro" name="id_libro" required>
                                <option value="">Seleccione un libro...</option>
                                <?php foreach($libros as $libro): ?>
                                    <option value="<?= $libro['id_libro'] ?>"><?= htmlspecialchars($libro['titulo_libro']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_prestamo" class="form-label">Fecha de Préstamo</label>
                                <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_dev_esperada" class="form-label">Fecha Devolución Esperada</label>
                                <input type="date" class="form-control" id="fecha_dev_esperada" name="fecha_dev_esperada" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Registrar Préstamo</button>
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
