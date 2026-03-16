<?php 
$titulo = "Detalle del Producto";
include 'vista/layout/header.php'; 

$estadoBadge = 'badge-success';
if ($producto['estado'] == 'agotado') {
    $estadoBadge = 'badge-danger';
} elseif ($producto['estado'] == 'descontinuado') {
    $estadoBadge = 'badge-warning';
}
?>

    <div class="container-medium">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=inventario&accion=lista">Inventario</a> / 
            Detalle del Producto
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
                <h1><i class="bi bi-box"></i> <?php echo htmlspecialchars($producto['nombre']); ?></h1>
                <div class="action-buttons">
                    <a href="index.php?modulo=inventario&accion=editar&id=<?php echo $producto['id']; ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                    <a href="#" onclick="abrirModalEliminacion('inventario', <?php echo $producto['id']; ?>, '<?php echo htmlspecialchars($producto['nombre']); ?>')" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                    <a href="index.php?modulo=inventario&accion=lista" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                </div>
            </div>

            <h2 class="section-title">Informacion del Producto</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>ID Producto</label>
                    <div class="value">INV<?php echo str_pad($producto['id'], 3, '0', STR_PAD_LEFT); ?></div>
                </div>
                <div class="info-item">
                    <label>Categoria</label>
                    <div class="value"><?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoria'); ?></div>
                </div>
                <div class="info-item">
                    <label>Cantidad en Stock</label>
                    <div class="value" style="font-weight: 600; font-size: 18px;">
                        <?php
                            $stockColor = 'color: var(--color-success)';
                            if ($producto['cantidad_stock'] <= 0) {
                                $stockColor = 'color: var(--color-danger);';
                            } elseif ($producto['cantidad_stock'] <= $producto['stock_minimo']) {
                                $stockColor = 'color: var(--color-warning);';
                            }
                        ?>
                        <span style="<?php echo $stockColor; ?>">
                            <i class="bi bi-box"></i> <?php echo $producto['cantidad_stock']; ?> unidades
                        </span>
                        <?php if ($producto['cantidad_stock'] <= $producto['stock_minimo'] && $producto['cantidad_stock'] > 0): ?>
                            <span style="display: block; font-size: 12px; color: var(--color-warning); margin-top: 5px;">
                                <i class="bi bi-exclamation-triangle"></i> Stock bajo (Mínimo: <?php echo $producto['stock_minimo']; ?> unidades)
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <label>Stock Minimo</label>
                    <div class="value"><?php echo $producto['stock_minimo']; ?> unidades</div>
                </div>
                <div class="info-item">
                    <label>Precio</label>
                    <div class="value">$<?php echo number_format($producto['precio'], 2); ?></div>
                </div>
                <div class="info-item">
                    <label>Proveedor</label>
                    <div class="value"><?php echo htmlspecialchars($producto['proveedor_nombre'] ?? 'Sin proveedor'); ?></div>
                </div>
                <div class="info-item">
                    <label>Estado del Producto</label>
                    <div class="value">
                        <span class="badge <?php echo $estadoBadge; ?>" style="font-size: 13px; padding: 8px 12px;">
                            <?php 
                                $icono = 'bi-check-circle';
                                if ($producto['estado'] == 'agotado') {
                                    $icono = 'bi-exclamation-circle';
                                } elseif ($producto['estado'] == 'descontinuado') {
                                    $icono = 'bi-x-circle';
                                }
                            ?>
                            <i class="bi <?php echo $icono; ?>"></i> <?php echo ucfirst($producto['estado']); ?>
                        </span>
                        <?php if ($producto['estado'] == 'disponible'): ?>
                            <span style="font-size: 12px; color: var(--color-gray-medium); display: block; margin-top: 5px;">
                                ✓ Producto disponible para venta
                            </span>
                        <?php elseif ($producto['estado'] == 'agotado'): ?>
                            <span style="font-size: 12px; color: var(--color-danger); display: block; margin-top: 5px;">
                                ✗ Stock agotado - Se actualizará automáticamente cuando se registren entradas
                            </span>
                        <?php elseif ($producto['estado'] == 'descontinuado'): ?>
                            <span style="font-size: 12px; color: var(--color-warning); display: block; margin-top: 5px;">
                                ⚠ Producto descontinuado - No se considera para venta
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <label>Fecha de Registro</label>
                    <div class="value"><?php echo date('d/m/Y', strtotime($producto['fecha_creacion'])); ?></div>
                </div>
            </div>

            <?php if (!empty($producto['descripcion'])): ?>
            <div class="info-item" style="margin-bottom: 30px;">
                <label>Descripcion</label>
                <div class="value"><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></div>
            </div>
            <?php endif; ?>

            <h2 class="section-title">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Historial de Movimientos</span>
                    <a href="index.php?modulo=inventario&accion=movimiento_crear&producto_id=<?php echo $producto['id']; ?>" class="btn btn-primary" style="margin: 0;"><i class="bi bi-plus-circle"></i> Agregar Movimiento</a>
                </div>
            </h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Motivo</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($movimientos)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--color-gray-medium);">No hay movimientos registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($movimientos as $movimiento): ?>
                                <?php
                                    $tipoBadge = 'badge-success';
                                    $signo = '+';
                                    if ($movimiento['tipo'] == 'salida') {
                                        $tipoBadge = 'badge-danger';
                                        $signo = '-';
                                    } elseif ($movimiento['tipo'] == 'ajuste') {
                                        $tipoBadge = 'badge-warning';
                                        $signo = '=';
                                    }
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($movimiento['fecha'])); ?></td>
                                    <td><span class="badge <?php echo $tipoBadge; ?>"><?php echo ucfirst($movimiento['tipo']); ?></span></td>
                                    <td><?php echo $signo . $movimiento['cantidad']; ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['motivo'] ?: '-'); ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['usuario_nombre']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="index.php?modulo=inventario&accion=movimiento_editar&id=<?php echo $movimiento['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                            <a href="#" onclick="abrirModalEliminacion('inventario', <?php echo $movimiento['id']; ?>, 'este movimiento de inventario')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
