<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Gestion de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="login-page">
    <div class="login-container" style="max-width: 500px;">
        <div class="logo-section">
            <i class="bi bi-person-plus" style="font-size: 48px; color: var(--color-primary);"></i>
            <h1>CREAR CUENTA</h1>
            <p>Completa el formulario para registrarte</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?modulo=auth&accion=procesar_registro" method="post">
            <div class="form-group">
                <label for="nombre"><i class="bi bi-person"></i> Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre completo" value="<?php echo isset($datos['nombre']) ? htmlspecialchars($datos['nombre']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email"><i class="bi bi-envelope"></i> Correo Electronico</label>
                <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com" value="<?php echo isset($datos['email']) ? htmlspecialchars($datos['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="telefono"><i class="bi bi-telephone"></i> Telefono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="+593 99 999 9999" value="<?php echo isset($datos['telefono']) ? htmlspecialchars($datos['telefono']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password"><i class="bi bi-lock"></i> Contrasena</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" minlength="8" required placeholder="Minimo 8 caracteres">
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                        <i class="bi bi-eye" id="icon-password"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password"><i class="bi bi-lock-fill"></i> Confirmar Contrasena</label>
                <div class="password-container">
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Repite tu contrasena">
                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                        <i class="bi bi-eye" id="icon-confirm_password"></i>
                    </button>
                </div>
            </div>

            <div class="button-group">
                <a href="index.php?modulo=auth&accion=login" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Registrarse</button>
            </div>
        </form>

        <div class="text-link" style="margin-top: 20px;">
            Ya tienes cuenta? <a href="index.php?modulo=auth&accion=login">Inicia sesion aqui</a>
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
