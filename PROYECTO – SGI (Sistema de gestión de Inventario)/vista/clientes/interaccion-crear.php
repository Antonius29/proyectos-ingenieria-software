<?php 
$titulo = "Agregar Interaccion";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=clientes&accion=lista">Clientes</a> / 
            <a href="index.php?modulo=clientes&accion=detalle&id=<?php echo $cliente['id']; ?>">Detalle del Cliente</a> / 
            Agregar Interaccion
        </div>

        <div class="card">
            <h1><i class="bi bi-plus-circle"></i> Agregar Nueva Interaccion</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Registre una nueva interaccion con el cliente <?php echo htmlspecialchars($cliente['nombre']); ?></p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Cliente:</strong> <?php echo htmlspecialchars($cliente['nombre']); ?> - <?php echo htmlspecialchars($cliente['empresa']); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=clientes&accion=interaccion_guardar" method="post">
                <input type="hidden" name="cliente_id" value="<?php echo $cliente['id']; ?>">
                
                <div class="form-group">
                    <label for="fecha"><i class="bi bi-calendar"></i> Fecha de la Interaccion <span class="required">*</span></label>
                    <input type="date" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="tipo"><i class="bi bi-chat-dots"></i> Tipo de Interaccion <span class="required">*</span></label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="llamada">Llamada</option>
                        <option value="email">Email</option>
                        <option value="reunion">Reunion</option>
                        <option value="visita">Visita</option>
                        <option value="videollamada">Videollamada</option>
                        <option value="mensaje">Mensaje</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion"><i class="bi bi-journal-text"></i> Descripcion <span class="required">*</span></label>
                    <textarea id="descripcion" name="descripcion" required placeholder="Detalle de la interaccion..."></textarea>
                </div>

                <div class="form-group">
                    <label for="responsable"><i class="bi bi-person-badge"></i> Responsable <span class="required">*</span></label>
                    <select id="responsable" name="responsable" required>
                        <option value="">Seleccione un responsable</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id']; ?>" <?php echo (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=clientes&accion=detalle&id=<?php echo $cliente['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Interaccion</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
