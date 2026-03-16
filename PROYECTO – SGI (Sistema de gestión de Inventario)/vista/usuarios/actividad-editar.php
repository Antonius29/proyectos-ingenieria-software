<?php 
$titulo = "Editar Actividad";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=usuarios&accion=lista">Usuarios</a> / 
            <a href="index.php?modulo=usuarios&accion=detalle&id=<?php echo $usuario['id']; ?>">Detalle del Usuario</a> / 
            Editar Actividad
        </div>

        <div class="card">
            <h1><i class="bi bi-pencil-square"></i> Editar Actividad</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Actualice la informacion de la actividad</p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Usuario:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?> - <?php echo htmlspecialchars($usuario['email']); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=usuarios&accion=actividad_actualizar" method="post">
                <input type="hidden" name="id" value="<?php echo $actividad['id']; ?>">
                <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                
                <div class="form-group">
                    <label for="fecha"><i class="bi bi-calendar"></i> Fecha de la Actividad <span class="required">*</span></label>
                    <input type="date" id="fecha" name="fecha" required value="<?php echo $actividad['fecha']; ?>">
                </div>

                <div class="form-group">
                    <label for="tipo_actividad"><i class="bi bi-tag"></i> Tipo de Actividad <span class="required">*</span></label>
                    <select id="tipo_actividad" name="tipo_actividad" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="Tarea" <?php echo $actividad['tipo_actividad'] == 'Tarea' ? 'selected' : ''; ?>>Tarea</option>
                        <option value="Reunion" <?php echo $actividad['tipo_actividad'] == 'Reunion' ? 'selected' : ''; ?>>Reunion</option>
                        <option value="Llamada" <?php echo $actividad['tipo_actividad'] == 'Llamada' ? 'selected' : ''; ?>>Llamada</option>
                        <option value="Capacitacion" <?php echo $actividad['tipo_actividad'] == 'Capacitacion' ? 'selected' : ''; ?>>Capacitacion</option>
                        <option value="Otro" <?php echo $actividad['tipo_actividad'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion"><i class="bi bi-journal-text"></i> Descripcion <span class="required">*</span></label>
                    <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($actividad['descripcion']); ?></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=usuarios&accion=detalle&id=<?php echo $usuario['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Actividad</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
