<?php
session_start();
if (!isset($_SESSION['id_escuela'])) {
    header("Location: login.php");
    exit;
}

require_once 'conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

// Contadores para el dashboard (Filtrados por Escuela)
$id_escuela = $_SESSION['id_escuela'];

$total_libros = $conn->query("SELECT COUNT(*) FROM LIBRO WHERE id_escuela = $id_escuela")->fetchColumn();
$total_autores = $conn->query("SELECT COUNT(*) FROM AUTOR WHERE id_escuela = $id_escuela")->fetchColumn();
$total_categorias = $conn->query("SELECT COUNT(*) FROM CATEGORIA WHERE id_escuela = $id_escuela")->fetchColumn();
$total_socios = $conn->query("SELECT COUNT(*) FROM SOCIO WHERE id_escuela = $id_escuela")->fetchColumn();
$total_prestamos = $conn->query("SELECT COUNT(*) FROM PRESTAMOS WHERE id_escuela = $id_escuela AND fecha_devolucion_real IS NULL")->fetchColumn();
$total_pcs = $conn->query("SELECT COUNT(*) FROM COMPUTADORA WHERE id_escuela = $id_escuela AND estado = TRUE")->fetchColumn();
?>

<?php include 'header.php'; ?>

<div class="row g-4 mt-2">
    <!-- Libros -->
    <div class="col-md-4 mb-4">
        <a href="catalogo/libros.php" class="text-decoration-none">
            <div class="stat-widget primary">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-value"><?= $total_libros ?></div>
                <div class="stat-label">Libros</div>
            </div>
        </a>
    </div>

    <!-- Autores -->
    <div class="col-md-4 mb-4">
        <a href="catalogo/autores.php" class="text-decoration-none">
            <div class="stat-widget success">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-feather-alt"></i>
                </div>
                <div class="stat-value"><?= $total_autores ?></div>
                <div class="stat-label">Autores</div>
            </div>
        </a>
    </div>

    <!-- Categorías -->
    <div class="col-md-4 mb-4">
        <a href="catalogo/categorias.php" class="text-decoration-none">
            <div class="stat-widget warning">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-value"><?= $total_categorias ?></div>
                <div class="stat-label">Categorías</div>
            </div>
        </a>
    </div>

    <!-- Alumnos -->
    <div class="col-md-4 mb-4">
        <a href="socios/index.php" class="text-decoration-none">
            <div class="stat-widget info">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value"><?= $total_socios ?></div>
                <div class="stat-label">Alumnos</div>
            </div>
        </a>
    </div>

    <!-- Préstamos -->
    <div class="col-md-4 mb-4">
        <a href="prestamos/index.php" class="text-decoration-none">
            <div class="stat-widget danger">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-hand-holding-book"></i>
                </div>
                <div class="stat-value"><?= $total_prestamos ?></div>
                <div class="stat-label">Préstamos</div>
            </div>
        </a>
    </div>

    <!-- Computadoras -->
    <div class="col-md-4 mb-4">
        <a href="computadoras/index.php" class="text-decoration-none">
            <div class="stat-widget primary">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-desktop"></i>
                </div>
                <div class="stat-value"><?= $total_pcs ?></div>
                <div class="stat-label">Computadoras</div>
            </div>
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>
