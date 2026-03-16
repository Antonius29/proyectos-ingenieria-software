<?php 
$titulo = "Detalle del Usuario";
include 'vista/layout/header.php'; 
?>

    <div class="container-medium">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=usuarios&accion=lista">Usuarios</a> / 
            Detalle del Usuario
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
                <h1><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($usuario['nombre']); ?></h1>
                <div class="action-buttons">
                    <a href="index.php?modulo=usuarios&accion=editar&id=<?php echo $usuario['id']; ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
                    <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                        <a href="#" onclick="abrirModalEliminacion('usuarios', <?php echo $usuario['id']; ?>, '<?php echo htmlspecialchars($usuario['nombre']); ?>')" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                    <?php endif; ?>
                    <a href="index.php?modulo=usuarios&accion=lista" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                </div>
            </div>

            <h2 class="section-title">Informacion del Usuario</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>ID Usuario</label>
                    <div class="value">U<?php echo str_pad($usuario['id'], 3, '0', STR_PAD_LEFT); ?></div>
                </div>
                <div class="info-item">
                    <label>Rol</label>
                    <div class="value">
                        <span class="badge <?php echo $usuario['rol'] == 'administrador' ? 'badge-danger' : 'badge-info'; ?>">
                            <?php echo ucfirst($usuario['rol']); ?>
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <div class="value"><?php echo htmlspecialchars($usuario['email']); ?></div>
                </div>
                <div class="info-item">
                    <label>Telefono</label>
                    <div class="value"><?php echo htmlspecialchars($usuario['telefono']); ?></div>
                </div>
                <div class="info-item">
                    <label>Estado</label>
                    <div class="value">
                        <span class="badge <?php echo $usuario['estado'] == 'activo' ? 'badge-success' : 'badge-warning'; ?>">
                            <?php echo ucfirst($usuario['estado']); ?>
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <label>Fecha de Registro</label>
                    <div class="value"><?php echo date('d/m/Y', strtotime($usuario['fecha_creacion'])); ?></div>
                </div>
            </div>

            <h2 class="section-title">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Historial de Actividades</span>
                    <a href="index.php?modulo=usuarios&accion=actividad_crear&usuario_id=<?php echo $usuario['id']; ?>" class="btn btn-primary" style="margin: 0;"><i class="bi bi-plus-circle"></i> Agregar Actividad</a>
                </div>
            </h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($actividades)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--color-gray-medium);">No hay actividades registradas</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($actividades as $actividad): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($actividad['fecha'])); ?></td>
                                    <td><span class="badge badge-info"><?php echo htmlspecialchars($actividad['tipo_actividad']); ?></span></td>
                                    <td><?php echo htmlspecialchars($actividad['descripcion']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="index.php?modulo=usuarios&accion=actividad_editar&id=<?php echo $actividad['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                            <a href="#" onclick="abrirModalEliminacion('usuarios', <?php echo $actividad['id']; ?>, 'esta actividad')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
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
