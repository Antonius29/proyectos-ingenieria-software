<?php 
$titulo = "Detalle del Proveedor";
include 'vista/layout/header.php'; 
?>

    <div class="container-medium">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=proveedores&accion=lista">Proveedores</a> / 
            Detalle del Proveedor
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
                <h1><i class="bi bi-truck"></i> <?php echo htmlspecialchars($proveedor['empresa']); ?></h1>
                <div class="action-buttons">
                    <a href="index.php?modulo=proveedores&accion=editar&id=<?php echo $proveedor['id']; ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                    <a href="#" onclick="abrirModalEliminacion('proveedores', <?php echo $proveedor['id']; ?>, '<?php echo htmlspecialchars($proveedor['nombre']); ?>')" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                    <a href="index.php?modulo=proveedores&accion=lista" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                </div>
            </div>

            <h2 class="section-title">Informacion del Proveedor</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>ID Proveedor</label>
                    <div class="value">P<?php echo str_pad($proveedor['id'], 3, '0', STR_PAD_LEFT); ?></div>
                </div>
                <div class="info-item">
                    <label>Contacto</label>
                    <div class="value"><?php echo htmlspecialchars($proveedor['nombre']); ?></div>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <div class="value"><?php echo htmlspecialchars($proveedor['email']); ?></div>
                </div>
                <div class="info-item">
                    <label>Telefono</label>
                    <div class="value"><?php echo htmlspecialchars($proveedor['telefono']); ?></div>
                </div>
                <div class="info-item">
                    <label>Direccion</label>
                    <div class="value"><?php echo htmlspecialchars($proveedor['direccion'] ?: 'No especificada'); ?></div>
                </div>
                <div class="info-item">
                    <label>Estado</label>
                    <div class="value">
                        <span class="badge <?php echo $proveedor['estado'] == 'activo' ? 'badge-success' : 'badge-warning'; ?>">
                            <?php echo ucfirst($proveedor['estado']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <?php if (!empty($proveedor['notas'])): ?>
            <div class="info-item" style="margin-bottom: 30px;">
                <label>Notas</label>
                <div class="value"><?php echo nl2br(htmlspecialchars($proveedor['notas'])); ?></div>
            </div>
            <?php endif; ?>

            <h2 class="section-title">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Productos Suministrados</span>
                    <a href="index.php?modulo=proveedores&accion=suministro_crear&proveedor_id=<?php echo $proveedor['id']; ?>" class="btn btn-primary" style="margin: 0;"><i class="bi bi-plus-circle"></i> Agregar Suministro</a>
                </div>
            </h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoria</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($suministros)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--color-gray-medium);">No hay suministros registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($suministros as $suministro): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($suministro['nombre_producto']); ?></td>
                                    <td><span class="badge badge-info"><?php echo htmlspecialchars($suministro['categoria']); ?></span></td>
                                    <td>$<?php echo number_format($suministro['precio'], 2); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="index.php?modulo=proveedores&accion=suministro_editar&id=<?php echo $suministro['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                            <a href="#" onclick="abrirModalEliminacion('proveedores', <?php echo $suministro['id']; ?>, 'este suministro')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
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
