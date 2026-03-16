<?php 
$titulo = "Agregar Producto";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=inventario&accion=lista">Inventario</a> / 
            Agregar Producto
        </div>

        <div class="card">
            <h1><i class="bi bi-box-seam"></i> Agregar Nuevo Producto</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Complete el formulario para registrar un nuevo producto en el inventario</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=inventario&accion=guardar" method="post">
                <div class="form-group">
                    <label for="nombre"><i class="bi bi-box"></i> Nombre del Producto <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Nombre del producto" value="<?php echo htmlspecialchars($datos['nombre'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="categoria_id"><i class="bi bi-tag"></i> Categoria</label>
                    <select id="categoria_id" name="categoria_id">
                        <option value="">Seleccione una categoria</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id']; ?>" <?php echo (isset($datos['categoriaId']) && $datos['categoriaId'] == $categoria['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion"><i class="bi bi-journal-text"></i> Descripcion</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Descripcion del producto..."><?php echo htmlspecialchars($datos['descripcion'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="cantidad_stock"><i class="bi bi-stack"></i> Cantidad en Stock</label>
                    <input type="number" id="cantidad_stock" name="cantidad_stock" min="0" value="<?php echo htmlspecialchars($datos['cantidadStock'] ?? '0'); ?>">
                </div>

                <div class="form-group">
                    <label for="stock_minimo"><i class="bi bi-exclamation-triangle"></i> Stock Minimo</label>
                    <input type="number" id="stock_minimo" name="stock_minimo" min="0" value="<?php echo htmlspecialchars($datos['stockMinimo'] ?? '5'); ?>">
                </div>

                <div class="form-group">
                    <label for="precio"><i class="bi bi-currency-dollar"></i> Precio <span class="required">*</span></label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0.01" required value="<?php echo htmlspecialchars($datos['precio'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="proveedor_id"><i class="bi bi-truck"></i> Proveedor</label>
                    <select id="proveedor_id" name="proveedor_id">
                        <option value="">Seleccione un proveedor</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <option value="<?php echo $proveedor['id']; ?>" <?php echo (isset($datos['proveedorId']) && $datos['proveedorId'] == $proveedor['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($proveedor['empresa']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=inventario&accion=lista" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
