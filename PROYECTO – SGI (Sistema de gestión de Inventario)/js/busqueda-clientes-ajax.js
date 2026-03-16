/**
 * Búsqueda de Clientes en Tiempo Real con AJAX
 * Implementa comunicación asincrónica con el servidor usando fetch y JSON
 * Actualiza la tabla dinámicamente sin recargar la página
 */

// Variable global para gestionar timeout de búsqueda
let timeoutBusqueda = null;

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
            clearTimeout(timeoutBusqueda);
            
            // Ejecutar búsqueda después de 500ms (mejora rendimiento)
            timeoutBusqueda = setTimeout(function() {
                realizarBusquedaAjax(campoBusqueda.value);
            }, 500);
        });
    }
});

/**
 * Realiza la búsqueda de clientes mediante AJAX
 * @param {string} termino - Término de búsqueda
 */
function realizarBusquedaAjax(termino) {
    // Mostrar indicador de carga
    mostrarCargando();
    
    // Preparar datos para enviar
    const datos = new URLSearchParams();
    datos.append('buscar', termino.trim());
    datos.append('ajax', '1'); // Indicador para respuesta JSON
    
    // Realizar petición AJAX con fetch
    fetch('index.php?modulo=clientes&accion=buscar_ajax', {
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
        actualizarTablaClientes(datos);
    })
    // Manejar errores
    .catch(error => {
        console.error('Error en la búsqueda:', error);
        mostrarMensajeError('Error al buscar clientes. Intente nuevamente.');
    });
}

/**
 * Actualiza dinámicamente la tabla de clientes con los resultados
 * @param {Object} datos - Datos JSON recibidos del servidor
 */
function actualizarTablaClientes(datos) {
    // Obtener el tbody de la tabla
    const tbody = document.querySelector('.table-container tbody');
    
    if (!tbody) {
        console.error('Tabla no encontrada');
        return;
    }
    
    // Validar si la respuesta contiene clientes
    if (!datos.clientes || datos.clientes.length === 0) {
        // Mostrar mensaje de sin resultados
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; color: var(--color-gray-medium);">
                    No se encontraron clientes
                </td>
            </tr>
        `;
        return;
    }
    
    // Construir las filas de la tabla dinámicamente
    let html = '';
    datos.clientes.forEach(cliente => {
        html += `
            <tr>
                <td>${String(cliente.numero).padStart(3, '0')}</td>
                <td>${escaparHTML(cliente.nombre)}</td>
                <td>${escaparHTML(cliente.empresa)}</td>
                <td>${escaparHTML(cliente.email)}</td>
                <td>${escaparHTML(cliente.telefono)}</td>
                <td>
                    <div class="action-buttons">
                        <a href="index.php?modulo=clientes&accion=detalle&id=${cliente.id}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                        <a href="index.php?modulo=clientes&accion=editar&id=${cliente.id}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="#" onclick="abrirModalEliminacion('clientes', ${cliente.id}, '${cliente.nombre}')" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
    
    // Inyectar HTML actualizado en la tabla
    tbody.innerHTML = html;
}

/**
 * Muestra un indicador de carga en la tabla
 */
function mostrarCargando() {
    const tbody = document.querySelector('.table-container tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">
                    <span style="color: var(--color-primary);">Buscando...</span>
                </td>
            </tr>
        `;
    }
}

/**
 * Muestra un mensaje de error en la tabla
 * @param {string} mensaje - Mensaje de error a mostrar
 */
function mostrarMensajeError(mensaje) {
    const tbody = document.querySelector('.table-container tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; color: var(--color-danger);">
                    ${escaparHTML(mensaje)}
                </td>
            </tr>
        `;
    }
}

/**
 * Escapa caracteres especiales HTML para evitar XSS
 * @param {string} texto - Texto a escapar
 * @returns {string} Texto escapado
 */
function escaparHTML(texto) {
    const mapa = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return texto.replace(/[&<>"']/g, m => mapa[m]);
}
