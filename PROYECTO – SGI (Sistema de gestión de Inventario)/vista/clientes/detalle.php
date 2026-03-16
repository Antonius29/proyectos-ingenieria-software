<?php 
$titulo = "Detalle del Cliente";
include 'vista/layout/header.php'; 
?>

    <div class="container-medium">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=clientes&accion=lista">Clientes</a> / 
            Detalle del Cliente
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
                <h1><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($cliente['nombre']); ?></h1>
                <div class="action-buttons">
                    <a href="index.php?modulo=clientes&accion=editar&id=<?php echo $cliente['id']; ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                    <a href="#" onclick="abrirModalEliminacion('clientes', <?php echo $cliente['id']; ?>, '<?php echo htmlspecialchars($cliente['nombre']); ?>')" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                    <a href="index.php?modulo=clientes&accion=lista" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                </div>
            </div>

            <h2 class="section-title">Informacion del Cliente</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>ID Cliente</label>
                    <div class="value"><?php echo str_pad($cliente['id'], 3, '0', STR_PAD_LEFT); ?></div>
                </div>
                <div class="info-item">
                    <label>Empresa</label>
                    <div class="value"><?php echo htmlspecialchars($cliente['empresa']); ?></div>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <div class="value"><?php echo htmlspecialchars($cliente['email']); ?></div>
                </div>
                <div class="info-item">
                    <label>Telefono</label>
                    <div class="value"><?php echo htmlspecialchars($cliente['telefono']); ?></div>
                </div>
                <div class="info-item">
                    <label>Direccion</label>
                    <div class="value"><?php echo htmlspecialchars($cliente['direccion'] ?: 'No especificada'); ?></div>
                </div>
                <div class="info-item">
                    <label>Fecha de Registro</label>
                    <div class="value"><?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></div>
                </div>
            </div>

            <?php if (!empty($cliente['notas'])): ?>
            <div class="info-item" style="margin-bottom: 30px;">
                <label>Notas</label>
                <div class="value"><?php echo nl2br(htmlspecialchars($cliente['notas'])); ?></div>
            </div>
            <?php endif; ?>

            <h2 class="section-title">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Historial de Interacciones</span>
                    <a href="index.php?modulo=clientes&accion=interaccion_crear&cliente_id=<?php echo $cliente['id']; ?>" class="btn btn-primary" style="margin: 0;"><i class="bi bi-plus-circle"></i> Agregar Interaccion</a>
                </div>
            </h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Responsable</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($interacciones)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--color-gray-medium);">No hay interacciones registradas</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($interacciones as $interaccion): ?>
                                <?php
                                    $badgeClass = 'badge-info';
                                    switch ($interaccion['tipo']) {
                                        case 'llamada': $badgeClass = 'badge-info'; break;
                                        case 'email': $badgeClass = 'badge-primary'; break;
                                        case 'reunion': $badgeClass = 'badge-success'; break;
                                        case 'visita': $badgeClass = 'badge-warning'; break;
                                        case 'videollamada': $badgeClass = 'badge-danger'; break;
                                    }
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($interaccion['fecha'])); ?></td>
                                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($interaccion['tipo']); ?></span></td>
                                    <td><?php echo htmlspecialchars($interaccion['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($interaccion['responsable_nombre']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="index.php?modulo=clientes&accion=interaccion_editar&id=<?php echo $interaccion['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                            <a href="#" onclick="abrirModalEliminacion('clientes', <?php echo $interaccion['id']; ?>, 'esta interacción')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
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
