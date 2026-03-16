<?php 
$titulo = "Editar Producto";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=inventario&accion=lista">Inventario</a> / 
            Editar Producto
        </div>

        <div class="card">
            <h1><i class="bi bi-pencil-square"></i> Editar Producto</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Actualice la informacion del producto en el inventario</p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">ID del Producto:</strong> INV<?php echo str_pad($producto['id'], 3, '0', STR_PAD_LEFT); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=inventario&accion=actualizar" method="post">
                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                
                <div class="form-group">
                    <label for="nombre"><i class="bi bi-box"></i> Nombre del Producto <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </div>

                <div class="form-group">
                    <label for="categoria_id"><i class="bi bi-tag"></i> Categoria</label>
                    <select id="categoria_id" name="categoria_id">
                        <option value="">Seleccione una categoria</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id']; ?>" <?php echo $producto['categoria_id'] == $categoria['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion"><i class="bi bi-journal-text"></i> Descripcion</label>
                    <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="cantidad_stock"><i class="bi bi-stack"></i> Cantidad en Stock</label>
                    <input type="number" id="cantidad_stock" name="cantidad_stock" min="0" value="<?php echo $producto['cantidad_stock']; ?>">
                </div>

                <div class="form-group">
                    <label for="stock_minimo"><i class="bi bi-exclamation-triangle"></i> Stock Minimo</label>
                    <input type="number" id="stock_minimo" name="stock_minimo" min="0" value="<?php echo $producto['stock_minimo']; ?>">
                </div>

                <div class="form-group">
                    <label for="precio"><i class="bi bi-currency-dollar"></i> Precio <span class="required">*</span></label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0.01" required value="<?php echo $producto['precio']; ?>">
                </div>

                <div class="form-group">
                    <label for="proveedor_id"><i class="bi bi-truck"></i> Proveedor</label>
                    <select id="proveedor_id" name="proveedor_id">
                        <option value="">Seleccione un proveedor</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <option value="<?php echo $proveedor['id']; ?>" <?php echo $producto['proveedor_id'] == $proveedor['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($proveedor['empresa']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" id="descontinuado" name="descontinuado" value="1" <?php echo $producto['estado'] == 'descontinuado' ? 'checked' : ''; ?>>
                        <i class="bi bi-exclamation-circle"></i> Marcar este producto como <strong>descontinuado</strong>
                    </label>
                    <p style="font-size: 12px; color: var(--color-gray-medium); margin-top: 5px;">
                        Si desactiva este checkbox, el estado se determinará automáticamente según la cantidad en stock.
                    </p>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=inventario&accion=detalle&id=<?php echo $producto['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
