<?php 
$titulo = "Detalle del Pedido";
include 'vista/layout/header.php'; 
?>

    <div class="container-medium">
        <div class="breadcrumb">
            <a href="index.php?modulo=pedidos&accion=lista">Pedidos</a> / 
            Detalle del Pedido
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="page-header">
                <h1><i class="bi bi-receipt"></i> Pedido #<?php echo htmlspecialchars($pedido['numero_pedido']); ?></h1>
                <div class="action-buttons">
                    <a href="index.php?modulo=pedidos&accion=editar&id=<?php echo $pedido['id']; ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                    <a href="#" onclick="abrirModalEliminacion('pedidos', <?php echo $pedido['id']; ?>, 'Pedido #<?php echo $pedido["id"]; ?>')" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                    <a href="index.php?modulo=pedidos&accion=lista" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                </div>
            </div>

            <h2 class="section-title">Información del Pedido</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>ID Pedido</label>
                    <div class="value"><?php echo htmlspecialchars($pedido['numero_pedido']); ?></div>
                </div>
                <div class="info-item">
                    <label>Cliente</label>
                    <div class="value"><?php echo htmlspecialchars($pedido['cliente_nombre'] ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <label>Responsable</label>
                    <div class="value"><?php echo htmlspecialchars($pedido['usuario_nombre'] ?? 'Sin asignar'); ?></div>
                </div>
                <div class="info-item">
                    <label>Fecha del Pedido</label>
                    <div class="value"><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></div>
                </div>
                <div class="info-item">
                    <label>Estado</label>
                    <div class="value">
                        <?php 
                            $estado = $pedido['estado'];
                            $clases = [
                                'pendiente' => 'badge-warning',
                                'proceso' => 'badge-info',
                                'completado' => 'badge-success',
                                'cancelado' => 'badge-danger'
                            ];
                            $clase = $clases[$estado] ?? 'badge-secondary';
                        ?>
                        <span class="badge <?php echo $clase; ?>"><?php echo ucfirst($estado); ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <label>Método de Pago</label>
                    <div class="value"><?php echo htmlspecialchars(ucfirst($pedido['metodo_pago'])); ?></div>
                </div>
                <div class="info-item">
                    <label>Total del Pedido</label>
                    <div class="value" style="color: var(--color-primary); font-size: 20px; font-weight: 700;">$<?php echo number_format($pedido['total'], 2); ?></div>
                </div>
            </div>

            <div class="info-item" style="margin-bottom: 30px;">
                <label>Dirección de Envío</label>
                <div class="value"><?php echo htmlspecialchars($pedido['direccion_envio']); ?></div>
            </div>

            <?php if (!empty($pedido['notas'])): ?>
                <div class="info-item" style="margin-bottom: 30px;">
                    <label>Notas</label>
                    <div class="value"><?php echo htmlspecialchars($pedido['notas']); ?></div>
                </div>
            <?php endif; ?>

            <h2 class="section-title">Productos del Pedido</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--color-gray-medium);">No hay items en este pedido</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <tr data-item-id="<?php echo $item['id']; ?>">
                                    <td><?php echo htmlspecialchars($item['producto_nombre'] ?? 'Producto eliminado'); ?></td>
                                    <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                                    <td>$<?php echo number_format($item['precio_unitario'], 2); ?></td>
                                    <td style="font-weight: 700;">$<?php echo number_format($item['subtotal'], 2); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="index.php?modulo=pedidos&accion=editar_item&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                            <a href="#" onclick="abrirModalEliminacionItem(<?php echo $item['id']; ?>, <?php echo $pedido['id']; ?>, 'este item del pedido')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr style="background-color: var(--color-gray-light); font-weight: 700;">
                            <td colspan="3" style="text-align: right;">TOTAL CALCULADO:</td>
                            <td style="color: var(--color-primary); font-size: 18px;">$<?php 
                                $totalCalculado = array_reduce($items, function($suma, $item) {
                                    return $suma + $item['subtotal'];
                                }, 0);
                                echo number_format($totalCalculado, 2);
                            ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if ($pedido['total'] != $totalCalculado): ?>
                <div class="alert alert-warning" style="margin-top: 20px;">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Advertencia:</strong> El total del pedido ($<?php echo number_format($pedido['total'], 2); ?>) no coincide con el total calculado de productos ($<?php echo number_format($totalCalculado, 2); ?>). Por favor, revise los items agregados.
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px; text-align: right;">
                <a href="index.php?modulo=pedidos&accion=crear_item&pedido_id=<?php echo $pedido['id']; ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Agregar Item</a>
            </div>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
