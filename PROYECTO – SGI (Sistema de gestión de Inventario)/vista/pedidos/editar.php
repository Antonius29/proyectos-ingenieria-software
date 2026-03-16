<?php 
$titulo = "Editar Pedido";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=pedidos&accion=lista">Pedidos</a> / 
            <a href="index.php?modulo=pedidos&accion=detalle&id=<?php echo $pedido['id']; ?>">Detalle del Pedido</a> / 
            Editar Pedido
        </div>

        <div class="card">
            <h1><i class="bi bi-pencil-square"></i> Editar Pedido</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Actualice la información del pedido en el sistema</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">ID del Pedido:</strong> <?php echo htmlspecialchars($pedido['numero_pedido']); ?>
            </div>

            <form action="index.php?modulo=pedidos&accion=actualizar" method="post">
                <input type="hidden" name="id" value="<?php echo $pedido['id']; ?>">

                <div class="form-group">
                    <label for="cliente_id"><i class="bi bi-person"></i> Cliente <span class="required">*</span></label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo $cliente['id']; ?>" <?php echo ($pedido['cliente_id'] == $cliente['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cliente['nombre'] . ' - ' . ($cliente['empresa'] ?? 'Sin empresa')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="usuario_id"><i class="bi bi-person-badge"></i> Responsable</label>
                    <select id="usuario_id" name="usuario_id">
                        <option value="">Seleccione un responsable</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id']; ?>" <?php echo ($pedido['usuario_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_pedido"><i class="bi bi-calendar"></i> Fecha del Pedido <span class="required">*</span></label>
                    <input type="date" id="fecha_pedido" name="fecha_pedido" required value="<?php echo htmlspecialchars($pedido['fecha_pedido']); ?>">
                </div>

                <div class="form-group">
                    <label for="estado"><i class="bi bi-flag"></i> Estado <span class="required">*</span></label>
                    <select id="estado" name="estado" required>
                        <option value="pendiente" <?php echo ($pedido['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="proceso" <?php echo ($pedido['estado'] == 'proceso') ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="completado" <?php echo ($pedido['estado'] == 'completado') ? 'selected' : ''; ?>>Completado</option>
                        <option value="cancelado" <?php echo ($pedido['estado'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="metodo_pago"><i class="bi bi-credit-card"></i> Método de Pago <span class="required">*</span></label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        <option value="">Seleccione un método</option>
                        <option value="efectivo" <?php echo ($pedido['metodo_pago'] == 'efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                        <option value="tarjeta" <?php echo ($pedido['metodo_pago'] == 'tarjeta') ? 'selected' : ''; ?>>Tarjeta de Crédito</option>
                        <option value="transferencia" <?php echo ($pedido['metodo_pago'] == 'transferencia') ? 'selected' : ''; ?>>Transferencia Bancaria</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="direccion_envio"><i class="bi bi-geo-alt"></i> Dirección de Envío <span class="required">*</span></label>
                    <input type="text" id="direccion_envio" name="direccion_envio" required value="<?php echo htmlspecialchars($pedido['direccion_envio']); ?>">
                </div>

                <div class="form-group">
                    <label for="total"><i class="bi bi-currency-dollar"></i> Total del Pedido <span class="required">*</span></label>
                    <input type="number" id="total" name="total" required value="<?php echo htmlspecialchars($pedido['total']); ?>" step="0.01" min="0.01">
                </div>

                <div class="form-group">
                    <label for="notas"><i class="bi bi-journal-text"></i> Notas</label>
                    <textarea id="notas" name="notas"><?php echo htmlspecialchars($pedido['notas'] ?? ''); ?></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=pedidos&accion=detalle&id=<?php echo $pedido['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Pedido</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmarActualizarPedido() {
            const estado = document.getElementById('estado').value;
            const total = document.getElementById('total').value;
            
            if (parseFloat(total) <= 0) {
                alert('⚠️ El total del pedido debe ser mayor a $0.00');
                return false;
            }
            
            return confirm(`¿Confirma que desea actualizar este pedido al estado "${estado}" con un total de $${parseFloat(total).toFixed(2)}?`);
        }
    </script>

<?php include 'vista/layout/footer.php'; ?>


