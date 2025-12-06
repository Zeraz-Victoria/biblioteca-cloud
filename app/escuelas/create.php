<?php 
include '../header.php'; 

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'superadmin') {
    header("Location: ../index.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0">Registrar Nueva Escuela</h4>
                </div>
                <div class="card-body">
                    <form action="store.php" method="POST">
                        <div class="form-group">
                            <label>Nombre de la Escuela:</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email de Contacto:</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Dirección:</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        
                        <hr>
                        <h5>Crear Administrador Inicial</h5>
                        <div class="form-group">
                            <label>Nombre del Admin:</label>
                            <input type="text" name="admin_nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email del Admin (Login):</label>
                            <input type="email" name="admin_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Contraseña:</label>
                            <input type="password" name="admin_pass" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Guardar Escuela</button>
                        <a href="index.php" class="btn btn-secondary btn-block">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
