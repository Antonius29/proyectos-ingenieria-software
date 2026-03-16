<?php 
$titulo = "Lista de Proveedores";
include 'vista/layout/header.php'; 
?>

    <div class="container">
        <div class="page-header">
            <h1><i class="bi bi-truck"></i> Lista de Proveedores</h1>
            <a href="index.php?modulo=proveedores&accion=crear" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Agregar Proveedor</a>
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
                    <input type="text" name="buscar" placeholder="Buscar proveedores por nombre, empresa o email..." value="<?php echo htmlspecialchars($busqueda ?? ''); ?>" style="flex: 1;">
                    <a href="index.php?modulo=proveedores&accion=lista" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</a>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($proveedores)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--color-gray-medium);">No se encontraron proveedores</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <tr data-registro-id="<?php echo $proveedor['id']; ?>">
                                <td>P<?php echo str_pad($proveedor['numero'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['empresa']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['email']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['telefono']); ?></td>
                                <td>
                                    <span class="badge <?php echo $proveedor['estado'] == 'activo' ? 'badge-success' : 'badge-warning'; ?>">
                                        <?php echo ucfirst($proveedor['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="index.php?modulo=proveedores&accion=detalle&id=<?php echo $proveedor['id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Ver</a>
                                        <a href="index.php?modulo=proveedores&accion=editar&id=<?php echo $proveedor['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                        <button type="button" onclick="abrirModalEliminacion('proveedores', <?php echo $proveedor['id']; ?>, '<?php echo htmlspecialchars($proveedor['nombre']); ?>')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
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
<script src="js/busqueda-proveedores-ajax.js"></script>
