<?php 
$titulo = "Editar Item del Pedido";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=pedidos&accion=lista">Pedidos</a> / 
            <a href="index.php?modulo=pedidos&accion=detalle&id=<?php echo $item['pedido_id']; ?>">Detalle del Pedido</a> / 
            Editar Item
        </div>

        <div class="card">
            <h1><i class="bi bi-pencil-square"></i> Editar Item del Pedido</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Actualice la cantidad o información del producto en el pedido</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Producto:</strong> <?php echo htmlspecialchars($item['producto_nombre']); ?>
            </div>

            <form action="index.php?modulo=pedidos&accion=actualizar_item" method="post">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="pedido_id" value="<?php echo $item['pedido_id']; ?>">

                <div class="form-group">
                    <label><i class="bi bi-box"></i> Producto</label>
                    <input type="text" value="<?php echo htmlspecialchars($item['producto_nombre']); ?>" disabled style="background-color: var(--color-gray-light);">
                </div>

                <div class="form-group">
                    <label for="cantidad"><i class="bi bi-123"></i> Cantidad <span class="required">*</span></label>
                    <input type="number" id="cantidad" name="cantidad" required min="1" step="1" value="<?php echo htmlspecialchars($item['cantidad']); ?>" onchange="calcularSubtotal()">
                </div>

                <div class="form-group">
                    <label for="precio_unitario"><i class="bi bi-currency-dollar"></i> Precio Unitario <span class="required">*</span></label>
                    <input type="number" id="precio_unitario" name="precio_unitario" required step="0.01" min="0.01" value="<?php echo htmlspecialchars($item['precio_unitario']); ?>" onchange="calcularSubtotal()">
                </div>

                <div class="form-group">
                    <label><i class="bi bi-receipt"></i> Subtotal</label>
                    <input type="text" id="subtotal" readonly placeholder="$0.00" value="$<?php echo number_format($item['subtotal'], 2); ?>" style="background-color: var(--color-gray-light);">
                </div>

                <div class="form-group">
                    <label for="notas"><i class="bi bi-journal-text"></i> Notas</label>
                    <textarea id="notas" name="notas" placeholder="Notas adicionales del item..."><?php echo htmlspecialchars($item['notas'] ?? ''); ?></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=pedidos&accion=detalle&id=<?php echo $item['pedido_id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcularSubtotal() {
            const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
            const precio = parseFloat(document.getElementById('precio_unitario').value) || 0;
            const subtotal = cantidad * precio;
            document.getElementById('subtotal').value = '$' + subtotal.toFixed(2);
        }
    </script>

<?php include 'vista/layout/footer.php'; ?>
