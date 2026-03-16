<?php 
$titulo = "Editar Usuario";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=usuarios&accion=lista">Usuarios</a> / 
            Editar Usuario
        </div>

        <div class="card">
            <h1><i class="bi bi-pencil-square"></i> Editar Usuario</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Actualice la informacion del usuario en el sistema</p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">ID del Usuario:</strong> U<?php echo str_pad($usuario['id'], 3, '0', STR_PAD_LEFT); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=usuarios&accion=actualizar" method="post">
                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                
                <div class="form-group">
                    <label for="nombre"><i class="bi bi-person"></i> Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                </div>

                <div class="form-group">
                    <label for="rol"><i class="bi bi-shield"></i> Rol <span class="required">*</span></label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccione un rol</option>
                        <option value="administrador" <?php echo $usuario['rol'] == 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                        <option value="empleado" <?php echo $usuario['rol'] == 'empleado' ? 'selected' : ''; ?>>Empleado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email"><i class="bi bi-envelope"></i> Correo Electronico <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($usuario['email']); ?>">
                </div>

                <div class="form-group">
                    <label for="telefono"><i class="bi bi-telephone"></i> Telefono <span class="required">*</span></label>
                    <input type="tel" id="telefono" name="telefono" required value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
                </div>

                <div class="form-group">
                    <label for="estado"><i class="bi bi-toggle-on"></i> Estado <span class="required">*</span></label>
                    <select id="estado" name="estado" required>
                        <option value="activo" <?php echo $usuario['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo $usuario['estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password"><i class="bi bi-lock"></i> Nueva Contrasena (dejar en blanco para mantener la actual)</label>
                    <input type="password" id="password" name="password" placeholder="Minimo 8 caracteres">
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=usuarios&accion=detalle&id=<?php echo $usuario['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
