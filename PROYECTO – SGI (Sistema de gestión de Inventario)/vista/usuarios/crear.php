<?php 
$titulo = "Agregar Usuario";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=usuarios&accion=lista">Usuarios</a> / 
            Agregar Usuario
        </div>

        <div class="card">
            <h1><i class="bi bi-person-plus"></i> Agregar Nuevo Usuario</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Complete el formulario para registrar un nuevo usuario en el sistema</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=usuarios&accion=guardar" method="post">
                <div class="form-group">
                    <label for="nombre"><i class="bi bi-person"></i> Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre completo" value="<?php echo htmlspecialchars($datos['nombre'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="rol"><i class="bi bi-shield"></i> Rol <span class="required">*</span></label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccione un rol</option>
                        <option value="administrador" <?php echo (isset($datos['rol']) && $datos['rol'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="empleado" <?php echo (isset($datos['rol']) && $datos['rol'] == 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email"><i class="bi bi-envelope"></i> Correo Electronico <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com" value="<?php echo htmlspecialchars($datos['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="telefono"><i class="bi bi-telephone"></i> Telefono <span class="required">*</span></label>
                    <input type="tel" id="telefono" name="telefono" required placeholder="+593 99 999 9999" value="<?php echo htmlspecialchars($datos['telefono'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="password"><i class="bi bi-lock"></i> Contrasena <span class="required">*</span></label>
                    <input type="password" id="password" name="password" required placeholder="Minimo 8 caracteres" minlength="8">
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=usuarios&accion=lista" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
