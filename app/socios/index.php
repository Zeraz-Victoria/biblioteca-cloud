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
$stmt = $conn->prepare("SELECT * FROM SOCIO WHERE id_escuela = :escuela ORDER BY Nombre_socio ASC");
$stmt->execute([':escuela' => $id_escuela]);
$socios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listado de Alumnos (Socios)</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Alumno
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
                            <th>DNI</th>
                            <th>NFC ID</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($socios as $socio): ?>
                        <tr>
                            <td><?= $socio['id_socio'] ?></td>
                            <td><?= htmlspecialchars($socio['nombre_socio']) ?></td>
                            <td><?= htmlspecialchars($socio['dni']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($socio['nfc_id'] ?? 'N/A') ?></span></td>
                            <td><?= htmlspecialchars($socio['email']) ?></td>
                            <td><?= htmlspecialchars($socio['telefono']) ?></td>
                            <td>
                                <?php if($socio['estado']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactivo</span>
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
