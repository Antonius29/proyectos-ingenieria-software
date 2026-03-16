/**
 * Búsqueda de Pedidos en Tiempo Real con AJAX
 * Implementa comunicación asincrónica con el servidor usando fetch y JSON
 * Actualiza la tabla dinámicamente sin recargar la página
 */

// Variable global para gestionar timeout de búsqueda
let timeoutBusquedaPedidos = null;

/**
 * Inicializa los listeners de AJAX al cargar el documento
 */
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el campo de búsqueda
    const campoBusqueda = document.querySelector('input[name="buscar"]');
    
    if (campoBusqueda) {
        // Escuchar cambios en el campo de búsqueda
        campoBusqueda.addEventListener('input', function() {
            // Cancelar búsqueda anterior si existe
            clearTimeout(timeoutBusquedaPedidos);
            
            // Ejecutar búsqueda después de 500ms (mejora rendimiento)
            timeoutBusquedaPedidos = setTimeout(function() {
                realizarBusquedaPedidosAjax(campoBusqueda.value);
            }, 500);
        });
    }
});

/**
 * Realiza la búsqueda de pedidos mediante AJAX
 * @param {string} termino - Término de búsqueda
 */
function realizarBusquedaPedidosAjax(termino) {
    // Mostrar indicador de carga
    mostrarCargando();
    
    // Preparar datos para enviar
    const datos = new URLSearchParams();
    datos.append('buscar', termino.trim());
    datos.append('ajax', '1'); // Indicador para respuesta JSON
    
    // Realizar petición AJAX con fetch
    fetch('index.php?modulo=pedidos&accion=buscar_ajax', {
        method: 'POST',
        body: datos,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    // Convertir respuesta a JSON
    .then(respuesta => {
        if (!respuesta.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return respuesta.json();
    })
    // Procesar datos recibidos
    .then(datos => {
        actualizarTablaPedidos(datos);
    })
    // Manejar errores
    .catch(error => {
        console.error('Error en la búsqueda:', error);
        mostrarMensajeError('Error al buscar pedidos. Intente nuevamente.');
    });
}

/**
 * Actualiza dinámicamente la tabla de pedidos con los resultados
 * @param {Object} datos - Datos JSON recibidos del servidor
 */
function actualizarTablaPedidos(datos) {
    // Obtener el tbody de la tabla
    const tbody = document.querySelector('.table-container tbody');
    
    if (!tbody) {
        console.error('Tabla no encontrada');
        return;
    }
    
    // Validar si la respuesta contiene pedidos
    if (!datos.pedidos || datos.pedidos.length === 0) {
        // Mostrar mensaje de sin resultados
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; color: var(--color-gray-medium);">
                    No se encontraron pedidos
                </td>
            </tr>
        `;
        return;
    }
    
    // Construir las filas de la tabla dinámicamente
    let html = '';
    datos.pedidos.forEach(pedido => {
        // Determinar clase de estado
        const clases = {
            'pendiente': 'badge-warning',
            'proceso': 'badge-info',
            'completado': 'badge-success',
            'cancelado': 'badge-danger'
        };
        const clase = clases[pedido.estado] || 'badge-secondary';
        
        html += `
            <tr>
                <td>${pedido.numero_pedido}</td>
                <td>${pedido.cliente_nombre || 'N/A'}</td>
                <td>${pedido.fecha_pedido}</td>
                <td>
                    <span class="badge ${clase}">
                        ${pedido.estado.charAt(0).toUpperCase() + pedido.estado.slice(1)}
                    </span>
                </td>
                <td style="font-weight: 700; color: var(--color-primary);">$${parseFloat(pedido.total).toFixed(2)}</td>
                <td>${pedido.metodo_pago.charAt(0).toUpperCase() + pedido.metodo_pago.slice(1)}</td>
                <td>
                    <div class="action-buttons">
                        <a href="index.php?modulo=pedidos&accion=detalle&id=${pedido.id}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Ver</a>
                        <a href="index.php?modulo=pedidos&accion=editar&id=${pedido.id}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                        <a href="#" onclick="abrirModalEliminacion('pedidos', ${pedido.id}, 'Pedido #' + ${pedido.id})" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

/**
 * Muestra indicador de carga en la tabla
 */
function mostrarCargando() {
    const tbody = document.querySelector('.table-container tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; color: var(--color-gray-medium);">
                    <i class="bi bi-hourglass-split" style="margin-right: 8px;"></i> Buscando...
                </td>
            </tr>
        `;
    }
}

/**
 * Muestra mensaje de error en la tabla
 */
function mostrarMensajeError(mensaje) {
    const tbody = document.querySelector('.table-container tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; color: var(--color-danger);">
                    <i class="bi bi-exclamation-circle" style="margin-right: 8px;"></i> ${mensaje}
                </td>
            </tr>
        `;
    }
}
