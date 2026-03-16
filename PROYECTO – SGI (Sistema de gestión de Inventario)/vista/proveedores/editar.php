<?php 
$titulo = "Editar Proveedor";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=proveedores&accion=lista">Proveedores</a> / 
            Editar Proveedor
        </div>

        <div class="card">
            <h1><i class="bi bi-pencil-square"></i> Editar Proveedor</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Actualice la informacion del proveedor en el sistema</p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">ID del Proveedor:</strong> P<?php echo str_pad($proveedor['id'], 3, '0', STR_PAD_LEFT); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=proveedores&accion=actualizar" method="post">
                <input type="hidden" name="id" value="<?php echo $proveedor['id']; ?>">
                
                <div class="form-group">
                    <label for="nombre"><i class="bi bi-person"></i> Nombre del Contacto <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($proveedor['nombre']); ?>">
                </div>

                <div class="form-group">
                    <label for="empresa"><i class="bi bi-building"></i> Empresa <span class="required">*</span></label>
                    <input type="text" id="empresa" name="empresa" required value="<?php echo htmlspecialchars($proveedor['empresa']); ?>">
                </div>

                <div class="form-group">
                    <label for="email"><i class="bi bi-envelope"></i> Correo Electronico <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($proveedor['email']); ?>">
                </div>

                <div class="form-group">
                    <label for="telefono"><i class="bi bi-telephone"></i> Telefono <span class="required">*</span></label>
                    <input type="tel" id="telefono" name="telefono" required value="<?php echo htmlspecialchars($proveedor['telefono']); ?>">
                </div>

                <div class="form-group">
                    <label for="direccion"><i class="bi bi-geo-alt"></i> Direccion</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($proveedor['direccion']); ?>">
                </div>

                <div class="form-group">
                    <label for="estado"><i class="bi bi-toggle-on"></i> Estado <span class="required">*</span></label>
                    <select id="estado" name="estado" required>
                        <option value="activo" <?php echo $proveedor['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo $proveedor['estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notas"><i class="bi bi-journal-text"></i> Notas Adicionales</label>
                    <textarea id="notas" name="notas"><?php echo htmlspecialchars($proveedor['notas']); ?></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=proveedores&accion=detalle&id=<?php echo $proveedor['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Proveedor</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
