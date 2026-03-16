<?php 
$titulo = "Agregar Movimiento";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=inventario&accion=lista">Inventario</a> / 
            <a href="index.php?modulo=inventario&accion=detalle&id=<?php echo $producto['id']; ?>">Detalle del Producto</a> / 
            Agregar Movimiento
        </div>

        <div class="card">
            <h1><i class="bi bi-plus-circle"></i> Agregar Nuevo Movimiento</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Registre un movimiento de inventario para <?php echo htmlspecialchars($producto['nombre']); ?></p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Producto:</strong> <?php echo htmlspecialchars($producto['nombre']); ?><br>
                <strong style="color: var(--color-primary);">Stock Actual:</strong> <?php echo $producto['cantidad_stock']; ?> unidades
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=inventario&accion=movimiento_guardar" method="post">
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                
                <div class="form-group">
                    <label for="fecha"><i class="bi bi-calendar"></i> Fecha del Movimiento <span class="required">*</span></label>
                    <input type="date" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="tipo"><i class="bi bi-arrow-left-right"></i> Tipo de Movimiento <span class="required">*</span></label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="entrada">Entrada (aumentar stock)</option>
                        <option value="salida">Salida (disminuir stock)</option>
                        <option value="ajuste">Ajuste (establecer stock)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cantidad"><i class="bi bi-123"></i> Cantidad <span class="required">*</span></label>
                    <input type="number" id="cantidad" name="cantidad" min="1" required placeholder="Cantidad">
                </div>

                <div class="form-group">
                    <label for="motivo"><i class="bi bi-journal-text"></i> Motivo</label>
                    <textarea id="motivo" name="motivo" placeholder="Razon del movimiento..."></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=inventario&accion=detalle&id=<?php echo $producto['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Movimiento</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
