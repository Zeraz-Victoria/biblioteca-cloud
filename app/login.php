<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca SaaS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/sb-admin-2.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #003366 0%, #0056b3 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
        }
        .btn-login {
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            background: #003366;
            border: none;
        }
        .btn-login:hover {
            background: #002244;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <i class="fas fa-book-reader fa-3x text-primary mb-3"></i>
            <h2 class="h4 text-gray-900 mb-2">Bienvenido</h2>
            <p class="text-muted">Ingresa a tu Biblioteca Escolar</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Credenciales incorrectas.
            </div>
        <?php endif; ?>

        <form action="auth_login.php" method="POST">
            <div class="form-group mb-3">
                <input type="email" name="email" class="form-control" placeholder="Correo Electrónico" required autofocus>
            </div>
            <div class="form-group mb-4">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login w-100">
                Iniciar Sesión
            </button>
        </form>
        
        <div class="text-center mt-3">
            <a href="#" class="small text-muted">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

</body>
</html>
