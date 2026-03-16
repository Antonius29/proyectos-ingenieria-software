<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion - Sistema de Gestion de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="logo-section">
            <i class="bi bi-box-seam" style="font-size: 48px; color: var(--color-primary);"></i>
            <h1>SGI</h1>
            <p>Sistema de Gestión de Inventario</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?modulo=auth&accion=procesar_login" method="post">
            <div class="form-group">
                <label for="email"><i class="bi bi-envelope"></i> Correo Electronico</label>
                <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com">
            </div>

            <div class="form-group">
                <label for="password"><i class="bi bi-lock"></i> Contrasena</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contrasena">
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                        <i class="bi bi-eye" id="icon-password"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesion
            </button>
        </form>

        <div class="divider">o</div>

        <div class="text-link">
            No tienes cuenta? <a href="index.php?modulo=auth&accion=registro">Registrate aqui</a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('icon-' + fieldId);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
