<?php 
$titulo = "Lista de Inventario";
include 'vista/layout/header.php'; 
?>

    <div class="container">
        <div class="page-header">
            <h1><i class="bi bi-box"></i> Lista de Inventario</h1>
            <a href="index.php?modulo=inventario&accion=crear" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Agregar Producto</a>
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

        <!-- Campo de búsqueda AJAX - Se actualiza en tiempo real sin recargar -->
        <div class="search-bar">
            <form id="searchForm" onsubmit="return false;">
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="buscar" placeholder="Buscar productos por nombre, categoría o proveedor..." value="<?php echo htmlspecialchars($busqueda ?? ''); ?>" style="flex: 1;">
                    <a href="index.php?modulo=inventario&accion=lista" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</a>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Producto</th>
                        <th>Categoria</th>
                        <th>Cantidad en Stock</th>
                        <th>Precio</th>
                        <th>Proveedor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--color-gray-medium);">No se encontraron productos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $producto): ?>
                            <?php
                                $estadoBadge = 'badge-success';
                                $estadoIcono = 'bi-check-circle';
                                if ($producto['estado'] == 'agotado') {
                                    $estadoBadge = 'badge-danger';
                                    $estadoIcono = 'bi-exclamation-circle';
                                } elseif ($producto['estado'] == 'descontinuado') {
                                    $estadoBadge = 'badge-warning';
                                    $estadoIcono = 'bi-x-circle';
                                }
                                
                                // Indicador visual para cantidad de stock
                                $stockColor = 'color: var(--color-success)';
                                if ($producto['cantidad_stock'] <= 0) {
                                    $stockColor = 'color: var(--color-danger);';
                                } elseif ($producto['cantidad_stock'] <= $producto['stock_minimo']) {
                                    $stockColor = 'color: var(--color-warning);';
                                }
                            ?>
                            <tr data-registro-id="<?php echo $producto['id']; ?>">
                                <td>INV<?php echo str_pad($producto['numero'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoria'); ?></td>
                                <td style="font-weight: 600; <?php echo $stockColor; ?>">
                                    <i class="bi bi-box"></i> <?php echo $producto['cantidad_stock']; ?>
                                    <?php if ($producto['cantidad_stock'] <= $producto['stock_minimo'] && $producto['cantidad_stock'] > 0): ?>
                                        <i class="bi bi-exclamation-triangle" style="color: var(--color-warning); font-size: 12px;" title="Stock bajo"></i>
                                    <?php endif; ?>
                                </td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td><?php echo htmlspecialchars($producto['proveedor_nombre'] ?? 'Sin proveedor'); ?></td>
                                <td>
                                    <span class="badge <?php echo $estadoBadge; ?>">
                                        <i class="bi <?php echo $estadoIcono; ?>" style="margin-right: 5px;"></i><?php echo ucfirst($producto['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="index.php?modulo=inventario&accion=detalle&id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Ver</a>
                                        <a href="index.php?modulo=inventario&accion=editar&id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                        <button type="button" onclick="abrirModalEliminacion('inventario', <?php echo $producto['id']; ?>, '<?php echo htmlspecialchars($producto['nombre']); ?>')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
<script src="js/busqueda-productos-ajax.js"></script>
