/**
 * Búsqueda de Usuarios en Tiempo Real con AJAX
 * Implementa comunicación asincrónica con el servidor usando fetch y JSON
 * Actualiza la tabla dinámicamente sin recargar la página
 */

// Variable global para gestionar timeout de búsqueda
let timeoutBusquedaUsuarios = null;

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
            clearTimeout(timeoutBusquedaUsuarios);
            
            // Ejecutar búsqueda después de 500ms (mejora rendimiento)
            timeoutBusquedaUsuarios = setTimeout(function() {
                realizarBusquedaUsuariosAjax(campoBusqueda.value);
            }, 500);
        });
    }
});

/**
 * Realiza la búsqueda de usuarios mediante AJAX
 * @param {string} termino - Término de búsqueda
 */
function realizarBusquedaUsuariosAjax(termino) {
    // Mostrar indicador de carga
    mostrarCargando();
    
    // Preparar datos para enviar
    const datos = new URLSearchParams();
    datos.append('buscar', termino.trim());
    datos.append('ajax', '1'); // Indicador para respuesta JSON
    
    // Realizar petición AJAX con fetch
    fetch('index.php?modulo=usuarios&accion=buscar_ajax', {
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
        actualizarTablaUsuarios(datos);
    })
    // Manejar errores
    .catch(error => {
        console.error('Error en la búsqueda:', error);
        mostrarMensajeError('Error al buscar usuarios. Intente nuevamente.');
    });
}

/**
 * Actualiza dinámicamente la tabla de usuarios con los resultados
 * @param {Object} datos - Datos JSON recibido del servidor
 */
function actualizarTablaUsuarios(datos) {
    // Obtener el tbody de la tabla
    const tbody = document.querySelector('.table-container tbody');
    
    if (!tbody) {
        console.error('Tabla no encontrada');
        return;
    }
    
    // Validar si la respuesta contiene usuarios
    if (!datos.usuarios || datos.usuarios.length === 0) {
        // Mostrar mensaje de sin resultados
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; color: var(--color-gray-medium);">
                    No se encontraron usuarios
                </td>
            </tr>
        `;
        return;
    }
    
    // Construir las filas de la tabla dinámicamente
    let html = '';
    datos.usuarios.forEach(usuario => {
        html += `
            <tr>
                <td>U${String(usuario.numero).padStart(3, '0')}</td>
                <td>${usuario.nombre}</td>
                <td>
                    <span class="badge ${usuario.rol == 'administrador' ? 'badge-danger' : 'badge-info'}">
                        ${usuario.rol.charAt(0).toUpperCase() + usuario.rol.slice(1)}
                    </span>
                </td>
                <td>${usuario.email}</td>
                <td>${usuario.telefono}</td>
                <td>
                    <span class="badge ${usuario.estado == 'activo' ? 'badge-success' : 'badge-warning'}">
                        ${usuario.estado.charAt(0).toUpperCase() + usuario.estado.slice(1)}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="index.php?modulo=usuarios&accion=detalle&id=${usuario.id}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Ver</a>
                        <a href="index.php?modulo=usuarios&accion=editar&id=${usuario.id}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                        <a href="#" onclick="abrirModalEliminacion('usuarios', ${usuario.id}, '${usuario.nombre}')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
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
