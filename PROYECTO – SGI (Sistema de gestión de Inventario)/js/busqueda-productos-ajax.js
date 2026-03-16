/**
 * Búsqueda de Productos en Tiempo Real con AJAX
 * Implementa comunicación asincrónica con el servidor usando fetch y JSON
 * Actualiza la tabla dinámicamente sin recargar la página
 */

// Variable global para gestionar timeout de búsqueda
let timeoutBusquedaProductos = null;

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
            clearTimeout(timeoutBusquedaProductos);
            
            // Ejecutar búsqueda después de 500ms (mejora rendimiento)
            timeoutBusquedaProductos = setTimeout(function() {
                realizarBusquedaProductosAjax(campoBusqueda.value);
            }, 500);
        });
    }
});

/**
 * Realiza la búsqueda de productos mediante AJAX
 * @param {string} termino - Término de búsqueda
 */
function realizarBusquedaProductosAjax(termino) {
    // Mostrar indicador de carga
    mostrarCargando();
    
    // Preparar datos para enviar
    const datos = new URLSearchParams();
    datos.append('buscar', termino.trim());
    datos.append('ajax', '1'); // Indicador para respuesta JSON
    
    // Realizar petición AJAX con fetch
    fetch('index.php?modulo=inventario&accion=buscar_ajax', {
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
        actualizarTablaProductos(datos);
    })
    // Manejar errores
    .catch(error => {
        console.error('Error en la búsqueda:', error);
        mostrarMensajeError('Error al buscar productos. Intente nuevamente.');
    });
}

/**
 * Actualiza dinámicamente la tabla de productos con los resultados
 * @param {Object} datos - Datos JSON recibidos del servidor
 */
function actualizarTablaProductos(datos) {
    // Obtener el tbody de la tabla
    const tbody = document.querySelector('.table-container tbody');
    
    if (!tbody) {
        console.error('Tabla no encontrada');
        return;
    }
    
    // Validar si la respuesta contiene productos
    if (!datos.productos || datos.productos.length === 0) {
        // Mostrar mensaje de sin resultados
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; color: var(--color-gray-medium);">
                    No se encontraron productos
                </td>
            </tr>
        `;
        return;
    }
    
    // Construir las filas de la tabla dinámicamente
    let html = '';
    datos.productos.forEach(producto => {
        // Determinar clase de estado
        let estadoBadge = 'badge-success';
        let estadoIcono = 'bi-check-circle';
        if (producto.estado == 'agotado') {
            estadoBadge = 'badge-danger';
            estadoIcono = 'bi-exclamation-circle';
        } else if (producto.estado == 'descontinuado') {
            estadoBadge = 'badge-warning';
            estadoIcono = 'bi-x-circle';
        }
        
        // Indicador visual para cantidad de stock
        let stockColor = 'color: var(--color-success)';
        if (producto.cantidad_stock <= 0) {
            stockColor = 'color: var(--color-danger);';
        } else if (producto.cantidad_stock <= producto.stock_minimo) {
            stockColor = 'color: var(--color-warning);';
        }
        
        html += `
            <tr>
                <td>INV${String(producto.numero).padStart(3, '0')}</td>
                <td>${producto.nombre}</td>
                <td>${producto.categoria_nombre || 'Sin categoría'}</td>
                <td style="font-weight: 600; ${stockColor}">
                    ${producto.cantidad_stock}
                </td>
                <td style="font-weight: 700; color: var(--color-primary);">$${parseFloat(producto.precio).toFixed(2)}</td>
                <td>${producto.proveedor_nombre || 'Sin proveedor'}</td>
                <td>
                    <span class="badge ${estadoBadge}">
                        <i class="bi ${estadoIcono}"></i> ${producto.estado.charAt(0).toUpperCase() + producto.estado.slice(1)}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="index.php?modulo=inventario&accion=detalle&id=${producto.id}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Ver</a>
                        <a href="index.php?modulo=inventario&accion=editar&id=${producto.id}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                        <a href="#" onclick="abrirModalEliminacion('inventario', ${producto.id}, '${producto.nombre}')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
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
                <td colspan="8" style="text-align: center; color: var(--color-gray-medium);">
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
                <td colspan="8" style="text-align: center; color: var(--color-danger);">
                    <i class="bi bi-exclamation-circle" style="margin-right: 8px;"></i> ${mensaje}
                </td>
            </tr>
        `;
    }
}
