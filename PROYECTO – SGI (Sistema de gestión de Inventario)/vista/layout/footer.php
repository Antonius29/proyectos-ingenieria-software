    <!-- Modal de Mensajes (Éxito/Error/Info) -->
    <div id="messageModal" class="modal-mensaje">
        <div class="modal-mensaje-contenido" id="messageModalContent">
            <div id="messageIconContainer" class="icon-mensaje">
                <i id="messageIcon" class="bi bi-check-circle"></i>
            </div>
            <h2 id="messageModalTitle">Operación Exitosa</h2>
            <p id="messageText">Operación completada correctamente</p>
            <div class="modal-botones">
                <button type="button" class="btn btn-primary" onclick="cerrarMessageModal()">
                    <i class="bi bi-check"></i> Aceptar
                </button>
            </div>
        </div>
    </div>

    <script>

        function abrirMessageModal(titulo, mensaje, tipo = 'success') {
            const iconos = {
                success: 'check-circle',
                error: 'exclamation-triangle',
                warning: 'exclamation-circle',
                info: 'info-circle'
            };

            const colores = {
                success: '#27ae60',
                error: '#e74c3c',
                warning: '#f39c12',
                info: '#3498db'
            };

            // Actualizar icono y color
            const messageIcon = document.getElementById('messageIcon');
            messageIcon.className = `bi bi-${iconos[tipo]}`;
            
            const iconContainer = document.getElementById('messageIconContainer');
            iconContainer.style.color = colores[tipo];
            
            // Actualizar título y mensaje
            document.getElementById('messageModalTitle').textContent = titulo;
            document.getElementById('messageText').innerHTML = mensaje;
            
            // Mostrar modal
            document.getElementById('messageModal').style.display = 'flex';
        }

        function cerrarMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // Cerrar modales al hacer clic fuera de ellos
        window.onclick = function(event) {
            const messageModal = document.getElementById('messageModal');
            const alertaExpiracion = document.getElementById('alerta-expiracion-sesion');
            
            // No interferir con la alerta de expiración de sesión
            if (alertaExpiracion && alertaExpiracion.contains(event.target)) {
                return;
            }
            
            if (event.target === messageModal) {
                cerrarMessageModal();
            }
        }

        // Mostrar mensaje si viene en sesión
        document.addEventListener('DOMContentLoaded', function() {
            const mensaje = document.body.getAttribute('data-mensaje');
            const error = document.body.getAttribute('data-error');
            const info = document.body.getAttribute('data-info');

            if (mensaje) {
                abrirMessageModal('✓ Operación Exitosa', mensaje, 'success');
            } else if (error) {
                abrirMessageModal('✗ Error', error, 'error');
            } else if (info) {
                abrirMessageModal('ℹ Información', info, 'info');
            }
        });
    </script>

    <!-- Script para Mantener Sesión Activa -->
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <script src="js/mantener-sesion-activa.js"></script>
    <?php endif; ?>

    <!-- Script AJAX para búsqueda de clientes en tiempo real -->
    <script src="js/busqueda-clientes-ajax.js"></script>

    <!-- Script de Autocompletado para Pedidos -->
    <script src="js/autocompletado-pedidos-ajax.js"></script>

    <!-- Modal de Confirmación para Eliminación AJAX -->
    <?php include 'vista/modales/confirmacion-eliminacion.php'; ?>
    <script src="js/confirmacion-eliminacion-ajax.js"></script>

    <!-- Footer -->
    <footer class="footer-main">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Sistema de Gestión de Inventario. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>