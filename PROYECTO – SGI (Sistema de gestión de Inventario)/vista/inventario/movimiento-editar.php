<?php 
$titulo = "Editar Movimiento";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=inventario&accion=lista">Inventario</a> / 
            <a href="index.php?modulo=inventario&accion=detalle&id=<?php echo $producto['id']; ?>">Detalle del Producto</a> / 
            Editar Movimiento
        </div>

        <div class="card">
            <h1><i class="bi bi-pencil-square"></i> Editar Movimiento</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Actualice la informacion del movimiento de inventario</p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Producto:</strong> <?php echo htmlspecialchars($producto['nombre']); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=inventario&accion=movimiento_actualizar" method="post">
                <input type="hidden" name="id" value="<?php echo $movimiento['id']; ?>">
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                
                <div class="form-group">
                    <label for="fecha"><i class="bi bi-calendar"></i> Fecha del Movimiento <span class="required">*</span></label>
                    <input type="date" id="fecha" name="fecha" required value="<?php echo $movimiento['fecha']; ?>">
                </div>

                <div class="form-group">
                    <label for="tipo"><i class="bi bi-arrow-left-right"></i> Tipo de Movimiento <span class="required">*</span></label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="entrada" <?php echo $movimiento['tipo'] == 'entrada' ? 'selected' : ''; ?>>Entrada (aumentar stock)</option>
                        <option value="salida" <?php echo $movimiento['tipo'] == 'salida' ? 'selected' : ''; ?>>Salida (disminuir stock)</option>
                        <option value="ajuste" <?php echo $movimiento['tipo'] == 'ajuste' ? 'selected' : ''; ?>>Ajuste (establecer stock)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cantidad"><i class="bi bi-123"></i> Cantidad <span class="required">*</span></label>
                    <input type="number" id="cantidad" name="cantidad" min="1" required value="<?php echo $movimiento['cantidad']; ?>">
                </div>

                <div class="form-group">
                    <label for="motivo"><i class="bi bi-journal-text"></i> Motivo</label>
                    <textarea id="motivo" name="motivo"><?php echo htmlspecialchars($movimiento['motivo']); ?></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=inventario&accion=detalle&id=<?php echo $producto['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Movimiento</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
