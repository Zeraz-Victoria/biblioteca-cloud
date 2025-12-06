<?php include '../header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registrar Nueva Computadora</h4>
                </div>
                <div class="card-body">
                    <form action="store.php" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre / Identificador</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: PC-01" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca / Modelo</label>
                            <input type="text" class="form-control" id="marca" name="marca" placeholder="Ej: Dell Optiplex" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Guardar Computadora</button>
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
