<?php 
$titulo = "Crear Pedido";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=pedidos&accion=lista">Pedidos</a> / 
            Crear Pedido
        </div>

        <div class="card">
            <h1><i class="bi bi-cart-plus"></i> Crear Nuevo Pedido</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Complete el formulario para registrar un nuevo pedido en el sistema</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=pedidos&accion=guardar" method="post">
                <div class="form-group">
                    <label for="cliente_id"><i class="bi bi-person"></i> Cliente <span class="required">*</span></label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo $cliente['id']; ?>" <?php echo (isset($datos['clienteId']) && $datos['clienteId'] == $cliente['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cliente['nombre']); ?><?php echo !empty($cliente['empresa']) ? ' - ' . htmlspecialchars($cliente['empresa']) : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="usuario_id"><i class="bi bi-person-badge"></i> Responsable</label>
                    <select id="usuario_id" name="usuario_id">
                        <option value="">Seleccione un responsable</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id']; ?>" <?php echo (isset($datos['usuarioId']) && $datos['usuarioId'] == $usuario['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_pedido"><i class="bi bi-calendar"></i> Fecha del Pedido <span class="required">*</span></label>
                    <input type="date" id="fecha_pedido" name="fecha_pedido" required value="<?php echo isset($datos['fechaPedido']) ? htmlspecialchars($datos['fechaPedido']) : date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="estado"><i class="bi bi-flag"></i> Estado <span class="required">*</span></label>
                    <select id="estado" name="estado" required>
                        <option value="pendiente" <?php echo (isset($datos['estado']) && $datos['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="proceso" <?php echo (isset($datos['estado']) && $datos['estado'] == 'proceso') ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="completado" <?php echo (isset($datos['estado']) && $datos['estado'] == 'completado') ? 'selected' : ''; ?>>Completado</option>
                        <option value="cancelado" <?php echo (isset($datos['estado']) && $datos['estado'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="metodo_pago"><i class="bi bi-credit-card"></i> Método de Pago <span class="required">*</span></label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        <option value="">Seleccione un método</option>
                        <option value="efectivo" <?php echo (isset($datos['metodoPago']) && $datos['metodoPago'] == 'efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                        <option value="tarjeta" <?php echo (isset($datos['metodoPago']) && $datos['metodoPago'] == 'tarjeta') ? 'selected' : ''; ?>>Tarjeta de Crédito</option>
                        <option value="transferencia" <?php echo (isset($datos['metodoPago']) && $datos['metodoPago'] == 'transferencia') ? 'selected' : ''; ?>>Transferencia Bancaria</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="direccion_envio"><i class="bi bi-geo-alt"></i> Dirección de Envío <span class="required">*</span></label>
                    <input type="text" id="direccion_envio" name="direccion_envio" required placeholder="Dirección completa de entrega" value="<?php echo isset($datos['direccionEnvio']) ? htmlspecialchars($datos['direccionEnvio']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="notas"><i class="bi bi-journal-text"></i> Notas</label>
                    <textarea id="notas" name="notas" placeholder="Notas adicionales del pedido..."><?php echo isset($datos['notas']) ? htmlspecialchars($datos['notas']) : ''; ?></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=pedidos&accion=lista" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Crear Pedido</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>

