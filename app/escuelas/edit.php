<?php 
include '../header.php'; 
require_once '../conexion.php';

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'superadmin') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];
$conexion = new Conexion();
$conn = $conexion->conectar();
$stmt = $conn->prepare("SELECT * FROM ESCUELAS WHERE id_escuela = :id");
$stmt->execute([':id' => $id]);
$escuela = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4 class="m-0">Editar Escuela</h4>
                </div>
                <div class="card-body">
                    <form action="update.php" method="POST">
                        <input type="hidden" name="id" value="<?= $escuela['id_escuela'] ?>">
                        <div class="form-group">
                            <label>Nombre de la Escuela:</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($escuela['nombre_escuela']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email de Contacto:</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($escuela['email_contacto']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Dirección:</label>
                            <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($escuela['direccion']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($escuela['telefono']) ?>">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="activo" id="activo" <?= $escuela['activo'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="activo">Escuela Activa</label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Actualizar</button>
                        <a href="index.php" class="btn btn-secondary btn-block">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
