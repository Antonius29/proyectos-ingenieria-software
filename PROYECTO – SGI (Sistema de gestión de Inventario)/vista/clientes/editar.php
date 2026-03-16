<?php 
$titulo = "Editar Cliente";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=clientes&accion=lista">Clientes</a> / 
            Editar Cliente
        </div>

        <div class="card">
            <h1><i class="bi bi-pencil-square"></i> Editar Cliente</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Actualice la informacion del cliente en el sistema</p>

            <div style="background-color: var(--color-gray-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">ID del Cliente:</strong> <?php echo str_pad($cliente['id'], 3, '0', STR_PAD_LEFT); ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=clientes&accion=actualizar" method="post">
                <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                
                <div class="form-group">
                    <label for="nombre"><i class="bi bi-person"></i> Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($cliente['nombre']); ?>">
                </div>

                <div class="form-group">
                    <label for="empresa"><i class="bi bi-building"></i> Empresa <span class="required">*</span></label>
                    <input type="text" id="empresa" name="empresa" required value="<?php echo htmlspecialchars($cliente['empresa']); ?>">
                </div>

                <div class="form-group">
                    <label for="email"><i class="bi bi-envelope"></i> Correo Electronico <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($cliente['email']); ?>">
                </div>

                <div class="form-group">
                    <label for="telefono"><i class="bi bi-telephone"></i> Telefono <span class="required">*</span></label>
                    <input type="tel" id="telefono" name="telefono" required value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
                </div>

                <div class="form-group">
                    <label for="direccion"><i class="bi bi-geo-alt"></i> Direccion</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion']); ?>">
                </div>

                <div class="form-group">
                    <label for="notas"><i class="bi bi-journal-text"></i> Notas Adicionales</label>
                    <textarea id="notas" name="notas"><?php echo htmlspecialchars($cliente['notas']); ?></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=clientes&accion=detalle&id=<?php echo $cliente['id']; ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Cliente</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
