<?php 
$titulo = "Lista de Clientes";
include 'vista/layout/header.php'; 
?>

    <div class="container">
        <div class="page-header">
            <h1><i class="bi bi-people"></i> Lista de Clientes</h1>
            <a href="index.php?modulo=clientes&accion=crear" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Agregar Cliente</a>
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
                    <input type="text" name="buscar" placeholder="Buscar clientes por nombre, empresa o email..." value="<?php echo htmlspecialchars($busqueda ?? ''); ?>" style="flex: 1;">
                    <a href="index.php?modulo=clientes&accion=lista" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</a>
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--color-gray-medium);">No se encontraron clientes</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr data-registro-id="<?php echo $cliente['id']; ?>">
                                <td><?php echo str_pad($cliente['numero'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['empresa']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="index.php?modulo=clientes&accion=detalle&id=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Ver</a>
                                        <a href="index.php?modulo=clientes&accion=editar&id=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                        <button type="button" onclick="abrirModalEliminacion('clientes', <?php echo $cliente['id']; ?>, '<?php echo htmlspecialchars($cliente['nombre']); ?>')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
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
<script src="js/busqueda-clientes-ajax.js"></script>
