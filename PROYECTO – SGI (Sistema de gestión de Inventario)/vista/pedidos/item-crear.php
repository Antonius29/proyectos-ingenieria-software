<?php 
$titulo = "Agregar Item al Pedido";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=pedidos&accion=lista">Pedidos</a> / 
            <a href="index.php?modulo=pedidos&accion=detalle&id=<?php echo $pedido['id']; ?>">Detalle del Pedido</a> / 
            Agregar Item
        </div>

        <div class="card">
            <h1><i class="bi bi-plus-circle"></i> Agregar Item al Pedido</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Seleccione un producto y cantidad para agregar al pedido</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Pedido:</strong> <?php echo htmlspecialchars($pedido['numero_pedido']); ?>
            </div>

            <form action="index.php?modulo=pedidos&accion=guardar_item" method="post">
                <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">

                <div class="form-group">
                    <label for="producto_id"><i class="bi bi-box"></i> Producto <span class="required">*</span></label>
                    <select id="producto_id" name="producto_id" required onchange="actualizarPrecio()">
                        <option value="">Seleccione un producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?php echo $producto['id']; ?>" data-precio="<?php echo $producto['precio']; ?>">
                                <?php echo htmlspecialchars($producto['nombre']); ?> - $<?php echo number_format($producto['precio'], 2); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cantidad"><i class="bi bi-123"></i> Cantidad <span class="required">*</span></label>
                    <input type="number" id="cantidad" name="cantidad" required min="1" step="1" value="1" onchange="calcularSubtotal()">
                </div>

                <div class="form-group">
                    <label for="precio_unitario"><i class="bi bi-currency-dollar"></i> Precio Unitario <span class="required">*</span></label>
                    <input type="number" id="precio_unitario" name="precio_unitario" required step="0.01" min="0.01" value="0.00" onchange="calcularSubtotal()">
                </div>

                <div class="form-group">
                    <label><i class="bi bi-receipt"></i> Subtotal</label>
                    <input type="text" id="subtotal" readonly placeholder="$0.00" style="background-color: var(--color-gray-light);">
                </div>

                <div class="form-group">
                    <label for="notas"><i class="bi bi-journal-text"></i> Notas</label>
                    <textarea id="notas" name="notas" placeholder="Notas adicionales del item..."></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=pedidos&accion=detalle&id=<?php echo $pedido['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Agregar Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function actualizarPrecio() {
            const select = document.getElementById('producto_id');
            const precioInput = document.getElementById('precio_unitario');
            const precio = select.options[select.selectedIndex].dataset.precio;
            precioInput.value = precio || '0.00';
            calcularSubtotal();
        }

        function calcularSubtotal() {
            const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
            const precio = parseFloat(document.getElementById('precio_unitario').value) || 0;
            const subtotal = cantidad * precio;
            document.getElementById('subtotal').value = '$' + subtotal.toFixed(2);
        }
    </script>

<?php include 'vista/layout/footer.php'; ?>
