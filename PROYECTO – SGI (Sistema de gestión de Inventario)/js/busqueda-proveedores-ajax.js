/**
 * Búsqueda de Proveedores en Tiempo Real con AJAX
 * Implementa comunicación asincrónica con el servidor usando fetch y JSON
 * Actualiza la tabla dinámicamente sin recargar la página
 */

// Variable global para gestionar timeout de búsqueda
let timeoutBusquedaProveedores = null;

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
            clearTimeout(timeoutBusquedaProveedores);
            
            // Ejecutar búsqueda después de 500ms (mejora rendimiento)
            timeoutBusquedaProveedores = setTimeout(function() {
                realizarBusquedaProveedoresAjax(campoBusqueda.value);
            }, 500);
        });
    }
});

/**
 * Realiza la búsqueda de proveedores mediante AJAX
 * @param {string} termino - Término de búsqueda
 */
function realizarBusquedaProveedoresAjax(termino) {
    // Mostrar indicador de carga
    mostrarCargando();
    
    // Preparar datos para enviar
    const datos = new URLSearchParams();
    datos.append('buscar', termino.trim());
    datos.append('ajax', '1'); // Indicador para respuesta JSON
    
    // Realizar petición AJAX con fetch
    fetch('index.php?modulo=proveedores&accion=buscar_ajax', {
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
        actualizarTablaProveedores(datos);
    })
    // Manejar errores
    .catch(error => {
        console.error('Error en la búsqueda:', error);
        mostrarMensajeError('Error al buscar proveedores. Intente nuevamente.');
    });
}

/**
 * Actualiza dinámicamente la tabla de proveedores con los resultados
 * @param {Object} datos - Datos JSON recibidos del servidor
 */
function actualizarTablaProveedores(datos) {
    // Obtener el tbody de la tabla
    const tbody = document.querySelector('.table-container tbody');
    
    if (!tbody) {
        console.error('Tabla no encontrada');
        return;
    }
    
    // Validar si la respuesta contiene proveedores
    if (!datos.proveedores || datos.proveedores.length === 0) {
        // Mostrar mensaje de sin resultados
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; color: var(--color-gray-medium);">
                    No se encontraron proveedores
                </td>
            </tr>
        `;
        return;
    }
    
    // Construir las filas de la tabla dinámicamente
    let html = '';
    datos.proveedores.forEach(proveedor => {
        html += `
            <tr>
                <td>P${String(proveedor.numero).padStart(3, '0')}</td>
                <td>${proveedor.nombre}</td>
                <td>${proveedor.empresa}</td>
                <td>${proveedor.email}</td>
                <td>${proveedor.telefono}</td>
                <td>
                    <span class="badge ${proveedor.estado == 'activo' ? 'badge-success' : 'badge-warning'}">
                        ${proveedor.estado.charAt(0).toUpperCase() + proveedor.estado.slice(1)}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="index.php?modulo=proveedores&accion=detalle&id=${proveedor.id}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Ver</a>
                        <a href="index.php?modulo=proveedores&accion=editar&id=${proveedor.id}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                        <a href="#" onclick="abrirModalEliminacion('proveedores', ${proveedor.id}, '${proveedor.nombre}')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
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
