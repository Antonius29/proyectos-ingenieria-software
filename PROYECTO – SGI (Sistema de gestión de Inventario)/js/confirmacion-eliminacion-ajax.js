/**
 * Modal de Confirmación para Eliminación de Registros
 * Reemplaza el confirm() nativo con un modal elegante
 */

let datosEliminacion = {
    modulo: '',
    id: '',
    nombreRegistro: '',
    boton: null
};

/**
 * Abre el modal de confirmación para eliminación
 * @param {string} modulo - Nombre del módulo (clientes, productos, etc.)
 * @param {number} id - ID del registro
 * @param {string} nombreRegistro - Nombre del registro
 */
function abrirModalEliminacion(modulo, id, nombreRegistro) {
    datosEliminacion = {
        modulo: modulo,
        id: id,
        nombreRegistro: nombreRegistro || 'este registro',
        boton: event.target.closest('.btn-danger')
    };
    
    const modal = document.getElementById('modalConfirmacionEliminacion');
    const nombreSpan = modal.querySelector('.registro-nombre');
    nombreSpan.textContent = nombreRegistro || 'este registro';
    
    modal.classList.add('activo');
    
    // Cerrar al presionar ESC
    document.addEventListener('keydown', cerrarModalEliminacionConESC);
}

function cerrarModalEliminacionConESC(e) {
    if (e.key === 'Escape') {
        cerrarModalEliminacion();
    }
}

/**
 * Cierra el modal de confirmación
 */
function cerrarModalEliminacion() {
    const modal = document.getElementById('modalConfirmacionEliminacion');
    modal.classList.remove('activo');
    document.removeEventListener('keydown', cerrarModalEliminacionConESC);
}

/**
 * Confirma la eliminación y ejecuta la petición AJAX
 */
function confirmarEliminacion() {
    const { modulo, id, boton } = datosEliminacion;
    
    // Deshabilitar botón de confirmación
    const btnConfirmar = document.getElementById('btnConfirmarEliminacion');
    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
    
    // Actualizar estado del botón original
    if (boton) {
        boton.disabled = true;
        boton.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
    }
    
    // Preparar datos
    const datos = new URLSearchParams();
    datos.append('id', id);
    datos.append('ajax', '1');
    
    // Realizar petición AJAX
    fetch(`index.php?modulo=${modulo}&accion=eliminar_ajax`, {
        method: 'POST',
        body: datos,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(respuesta => {
        if (!respuesta.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return respuesta.json();
    })
    .then(datos => {
        if (datos.success) {
            // Buscar la fila en la tabla y eliminarla con animación
            const fila = document.querySelector(`tr[data-registro-id="${id}"]`);
            if (fila) {
                fila.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => {
                    fila.remove();
                    mostrarToastEliminacion('success', datos.mensaje);
                }, 300);
            } else {
                mostrarToastEliminacion('success', datos.mensaje);
            }
        } else {
            mostrarToastEliminacion('error', datos.mensaje || 'Error al eliminar el registro');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarToastEliminacion('error', 'Error al eliminar el registro');
        
        // Restaurar botón original
        if (boton) {
            boton.disabled = false;
            boton.innerHTML = '<i class="bi bi-trash"></i> Eliminar';
        }
    })
    .finally(() => {
        // Cerrar modal
        cerrarModalEliminacion();
        
        // Restaurar botón de confirmación
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = '<i class="bi bi-check"></i> Eliminar';
    });
}

/**
 * Muestra notificación toast de eliminación
 * @param {string} tipo - 'success' o 'error'
 * @param {string} mensaje - Mensaje a mostrar
 */
function mostrarToastEliminacion(tipo, mensaje) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${tipo}`;
    
    const icono = tipo === 'success' 
        ? '<i class="bi bi-check-circle"></i>' 
        : '<i class="bi bi-exclamation-triangle"></i>';
    
    toast.innerHTML = `${icono} ${mensaje}`;
    document.body.appendChild(toast);
    
    // Animación de entrada
    setTimeout(() => {
        toast.style.animation = 'slideInRight 0.3s ease forwards';
    }, 10);
    
    // Remover después de 4 segundos
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease forwards';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 4000);
}

// Crear overlay para cerrar modal al hacer click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalConfirmacionEliminacion');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalEliminacion();
            }
        });
    }
});

// Estilos CSS para el modal
const style = document.createElement('style');
style.innerHTML = `
    .modal-confirmacion-eliminacion {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }
    
    .modal-confirmacion-eliminacion.activo {
        display: flex;
    }
    
    .modal-confirmacion-contenido {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        max-width: 400px;
        width: 90%;
        text-align: center;
    }
    
    .modal-confirmacion-contenido .icon-warning {
        font-size: 48px;
        color: #ef4444;
        margin-bottom: 15px;
    }
    
    .modal-confirmacion-contenido h2 {
        margin-top: 0;
        margin-bottom: 10px;
        color: #1f2937;
        font-size: 20px;
    }
    
    .modal-confirmacion-contenido p {
        margin: 10px 0;
        color: #6b7280;
        line-height: 1.5;
    }
    
    .modal-confirmacion-contenido .registro-nombre {
        font-weight: bold;
        color: #1f2937;
        word-break: break-word;
    }
    
    .modal-confirmacion-contenido .advertencia {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        padding: 12px;
        margin: 20px 0;
        border-radius: 4px;
        text-align: left;
        font-size: 13px;
        color: #7f1d1d;
    }
    
    .modal-confirmacion-contenido .modal-botones {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 25px;
    }
    
    .modal-confirmacion-contenido .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        min-width: 120px;
    }
    
    .modal-confirmacion-contenido .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .modal-confirmacion-contenido .btn-danger:hover:not(:disabled) {
        background: #dc2626;
    }
    
    .modal-confirmacion-contenido .btn-danger:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }
    
    .modal-confirmacion-contenido .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }
    
    .modal-confirmacion-contenido .btn-secondary:hover {
        background: #d1d5db;
    }
    
    /* Toasts */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 6px;
        font-weight: 500;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .toast-success {
        background: #10b981;
        color: white;
    }
    
    .toast-error {
        background: #ef4444;
        color: white;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

/**
 * Abre el modal para eliminar un item de pedido
 * @param {number} itemId - ID del item
 * @param {number} pedidoId - ID del pedido al que pertenece el item
 * @param {string} nombreRegistro - Nombre del registro
 */
function abrirModalEliminacionItem(itemId, pedidoId, nombreRegistro) {
    datosEliminacion = {
        modulo: 'pedidos',
        id: itemId,
        pedidoId: pedidoId,
        nombreRegistro: nombreRegistro || 'este item',
        boton: event.target.closest('.btn-danger'),
        esItem: true
    };
    
    const modal = document.getElementById('modalConfirmacionEliminacion');
    const nombreSpan = modal.querySelector('.registro-nombre');
    nombreSpan.textContent = nombreRegistro || 'este item';
    
    modal.classList.add('activo');
    
    // Cerrar al presionar ESC
    document.addEventListener('keydown', cerrarModalEliminacionConESC);
}

/**
 * Confirma la eliminación de un item con actualización de total sin recargar
 */
function confirmarEliminacionItem() {
    const { id, pedidoId, boton } = datosEliminacion;
    
    // Deshabilitar botón de confirmación
    const btnConfirmar = document.getElementById('btnConfirmarEliminacion');
    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
    
    // Actualizar estado del botón original
    if (boton) {
        boton.disabled = true;
        boton.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
    }
    
    // Preparar datos
    const datos = new URLSearchParams();
    datos.append('id', id);
    datos.append('pedido_id', pedidoId);
    datos.append('ajax', '1');
    
    // Realizar petición AJAX
    fetch(`index.php?modulo=pedidos&accion=eliminar_item_ajax`, {
        method: 'POST',
        body: datos,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(respuesta => {
        if (!respuesta.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return respuesta.json();
    })
    .then(datos => {
        if (datos.success) {
            // Buscar la fila en la tabla y eliminarla con animación
            const fila = document.querySelector(`tr[data-item-id="${id}"]`);
            if (fila) {
                fila.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => {
                    fila.remove();
                    mostrarToastEliminacion('success', datos.mensaje);
                    
                    // Recargar la página después de 1 segundo para que se actualice el total correctamente
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }, 300);
            } else {
                mostrarToastEliminacion('success', datos.mensaje);
                // Recargar la página después de 1 segundo
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } else {
            mostrarToastEliminacion('error', datos.mensaje || 'Error al eliminar el item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarToastEliminacion('error', 'Error al eliminar el item');
        
        // Restaurar botón original
        if (boton) {
            boton.disabled = false;
            boton.innerHTML = '<i class="bi bi-trash"></i> Eliminar';
        }
    })
    .finally(() => {
        // Cerrar modal
        cerrarModalEliminacion();
        
        // Restaurar botón de confirmación
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = '<i class="bi bi-check"></i> Eliminar';
    });
}
