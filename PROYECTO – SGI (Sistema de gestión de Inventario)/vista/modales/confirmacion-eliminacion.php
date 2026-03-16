<!-- Modal de Confirmación para Eliminación de Registros -->
<div id="modalConfirmacionEliminacion" class="modal-confirmacion-eliminacion">
    <div class="modal-confirmacion-contenido">
        <div class="icon-warning">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <h2>¿Estás seguro?</h2>
        <p>¿Deseas eliminar:</p>
        <p><span class="registro-nombre">este registro</span></p>
        <div class="advertencia">
            <i class="bi bi-info-circle"></i> Esta acción no se puede deshacer.
        </div>
        <div class="modal-botones">
            <button type="button" class="btn btn-secondary" onclick="cerrarModalEliminacion()">
                <i class="bi bi-x"></i> Cancelar
            </button>
            <button type="button" id="btnConfirmarEliminacion" class="btn btn-danger" onclick="datosEliminacion.esItem ? confirmarEliminacionItem() : confirmarEliminacion()">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
    </div>
</div>
