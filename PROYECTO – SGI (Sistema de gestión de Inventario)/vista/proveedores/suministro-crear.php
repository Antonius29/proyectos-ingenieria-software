<?php 
$titulo = "Agregar Suministro";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=proveedores&accion=lista">Proveedores</a> / 
            <a href="index.php?modulo=proveedores&accion=detalle&id=<?php echo $proveedor['id']; ?>">Detalle del Proveedor</a> / 
            Agregar Suministro
        </div>

        <div class="card">
            <h1><i class="bi bi-plus-circle"></i> Agregar Nuevo Suministro</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Registre un nuevo producto suministrado por <?php echo htmlspecialchars($proveedor['empresa']); ?></p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Proveedor:</strong> <?php echo htmlspecialchars($proveedor['empresa']); ?> - <?php echo htmlspecialchars($proveedor['nombre']); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=proveedores&accion=suministro_guardar" method="post">
                <input type="hidden" name="proveedor_id" value="<?php echo $proveedor['id']; ?>">
                
                <div class="form-group">
                    <label for="nombre_producto"><i class="bi bi-box"></i> Nombre del Producto <span class="required">*</span></label>
                    <input type="text" id="nombre_producto" name="nombre_producto" required placeholder="Nombre del producto">
                </div>

                <div class="form-group">
                    <label for="categoria"><i class="bi bi-tag"></i> Categoria <span class="required">*</span></label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Seleccione una categoria</option>
                        <option value="Electronica">Electronica</option>
                        <option value="Accesorios">Accesorios</option>
                        <option value="Muebles">Muebles</option>
                        <option value="Papeleria">Papeleria</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="precio"><i class="bi bi-currency-dollar"></i> Precio <span class="required">*</span></label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0.01" required placeholder="0.00">
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=proveedores&accion=detalle&id=<?php echo $proveedor['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Suministro</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
