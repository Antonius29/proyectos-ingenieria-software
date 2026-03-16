<?php 
$titulo = "Agregar Cliente";
include 'vista/layout/header.php'; 
?>

    <div class="container-small">
        <div class="breadcrumb">
            <a href="index.php?modulo=dashboard&accion=index">Inicio</a> / 
            <a href="index.php?modulo=clientes&accion=lista">Clientes</a> / 
            Agregar Cliente
        </div>

        <div class="card">
            <h1><i class="bi bi-person-plus"></i> Agregar Nuevo Cliente</h1>
            <p style="color: var(--color-gray-medium); margin-bottom: 30px;">Complete el formulario para registrar un nuevo cliente en el sistema</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?modulo=clientes&accion=guardar" method="post">
                <div class="form-group">
                    <label for="nombre"><i class="bi bi-person"></i> Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre completo" value="<?php echo htmlspecialchars($datos['nombre'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="empresa"><i class="bi bi-building"></i> Empresa <span class="required">*</span></label>
                    <input type="text" id="empresa" name="empresa" required placeholder="Nombre de la empresa" value="<?php echo htmlspecialchars($datos['empresa'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email"><i class="bi bi-envelope"></i> Correo Electronico <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com" value="<?php echo htmlspecialchars($datos['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="telefono"><i class="bi bi-telephone"></i> Telefono <span class="required">*</span></label>
                    <input type="tel" id="telefono" name="telefono" required placeholder="+593 99 999 9999" value="<?php echo htmlspecialchars($datos['telefono'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="direccion"><i class="bi bi-geo-alt"></i> Direccion</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Direccion completa" value="<?php echo htmlspecialchars($datos['direccion'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="notas"><i class="bi bi-journal-text"></i> Notas Adicionales</label>
                    <textarea id="notas" name="notas" placeholder="Informacion adicional sobre el cliente..."><?php echo htmlspecialchars($datos['notas'] ?? ''); ?></textarea>
                </div>

                <div class="button-group">
                    <a href="index.php?modulo=clientes&accion=lista" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>

<?php include 'vista/layout/footer.php'; ?>
