<?php 
$titulo = "Cambiar Estado del Pedido";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=pedidos&accion=lista">Pedidos</a> / 
            <a href="index.php?modulo=pedidos&accion=detalle&id=<?php echo $pedido['id']; ?>">Detalle del Pedido</a> / 
            Cambiar Estado
        </div>

        <div class="card">
            <h1><i class="bi bi-plus-circle"></i> Cambiar Estado del Pedido</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Registre un cambio de estado para el pedido</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">Pedido:</strong> <?php echo htmlspecialchars($pedido['numero_pedido'] . ' - ' . ($pedido['cliente_nombre'] ?? 'N/A')); ?> - <strong>Estado Actual:</strong> <?php echo ucfirst($pedido['estado']); ?>
            </div>

            <form action="index.php?modulo=pedidos&accion=actualizar" method="post">
                <input type="hidden" name="id" value="<?php echo $pedido['id']; ?>">

                <div class="form-group">
                    <label for="estado"><i class="bi bi-flag-fill"></i> Nuevo Estado <span class="required">*</span></label>
                    <select id="estado" name="estado" required>
                        <option value="">Seleccione un estado</option>
                        <?php 
                            $estados = [
                                'pendiente' => 'Pendiente',
                                'proceso' => 'En Proceso',
                                'completado' => 'Completado',
                                'cancelado' => 'Cancelado'
                            ];
                            foreach ($estados as $valor => $label):
                                if ($valor != $pedido['estado']):
                        ?>
                            <option value="<?php echo $valor; ?>"><?php echo $label; ?></option>
                        <?php 
                                endif;
                            endforeach; 
                        ?>
                    </select>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=pedidos&accion=detalle&id=<?php echo $pedido['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Cambiar Estado</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
