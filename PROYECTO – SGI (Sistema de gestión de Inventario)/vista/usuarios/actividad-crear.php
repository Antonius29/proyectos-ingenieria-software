<?php 
$titulo = "Agregar Actividad";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=usuarios&accion=lista">Usuarios</a> / 
            <a href="index.php?modulo=usuarios&accion=detalle&id=<?php echo $usuario['id']; ?>">Detalle del Usuario</a> / 
            Agregar Actividad
        </div>

        <div class="card">
            <h1><i class="bi bi-plus-circle"></i> Agregar Nueva Actividad</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Registre una nueva actividad para el usuario <?php echo htmlspecialchars($usuario['nombre']); ?></p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Usuario:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?> - <?php echo htmlspecialchars($usuario['email']); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=usuarios&accion=actividad_guardar" method="post">
                <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                
                <div class="form-group">
                    <label for="fecha"><i class="bi bi-calendar"></i> Fecha de la Actividad <span class="required">*</span></label>
                    <input type="date" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="tipo_actividad"><i class="bi bi-tag"></i> Tipo de Actividad <span class="required">*</span></label>
                    <select id="tipo_actividad" name="tipo_actividad" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="Tarea">Tarea</option>
                        <option value="Reunion">Reunion</option>
                        <option value="Llamada">Llamada</option>
                        <option value="Capacitacion">Capacitacion</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion"><i class="bi bi-journal-text"></i> Descripcion <span class="required">*</span></label>
                    <textarea id="descripcion" name="descripcion" required placeholder="Detalle de la actividad..."></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=usuarios&accion=detalle&id=<?php echo $usuario['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Actividad</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
