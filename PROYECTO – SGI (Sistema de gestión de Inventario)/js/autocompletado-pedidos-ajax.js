/**
 * Autocompletado de Clientes en Crear Pedido
 * Busca clientes mientras escribes en el input
 */

let clientesDatalist = [];
let productoDatalist = [];

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar autocompletado de clientes
    const inputCliente = document.getElementById('cliente_input');
    const selectCliente = document.getElementById('cliente_id');
    const datalistClientes = document.getElementById('clientes_list');
    
    if (inputCliente) {
        // Cargar lista completa de clientes al cargar
        cargarClientesAjax();
        
        // Buscar mientras escribe
        inputCliente.addEventListener('input', function(e) {
            filtrarClientes(this.value);
        });
        
        // Manejar selección de opción del datalist
        inputCliente.addEventListener('change', function() {
            // Buscar el cliente por nombre en el array
            const clienteSeleccionado = clientesDatalist.find(cliente => 
                `${cliente.nombre}${cliente.empresa ? ' - ' + cliente.empresa : ''}` === this.value
            );
            
            if (clienteSeleccionado) {
                selectCliente.value = clienteSeleccionado.id;
            }
        });
        
        // También capturar con evento blur en caso de que el datalist no dispare change
        inputCliente.addEventListener('blur', function() {
            if (selectCliente.value === '') {
                const clienteSeleccionado = clientesDatalist.find(cliente => 
                    `${cliente.nombre}${cliente.empresa ? ' - ' + cliente.empresa : ''}` === this.value
                );
                
                if (clienteSeleccionado) {
                    selectCliente.value = clienteSeleccionado.id;
                }
            }
        });
    }
    
    // Inicializar autocompletado de productos
    const inputProducto = document.getElementById('producto_input');
    const datalistProductos = document.getElementById('productos_list');
    
    if (inputProducto) {
        cargarProductosAjax();
        
        inputProducto.addEventListener('input', function(e) {
            filtrarProductos(this.value);
        });
    }
});

/**
 * Cargar lista de clientes mediante AJAX
 */
function cargarClientesAjax() {
    fetch('index.php?modulo=clientes&accion=buscar_ajax', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'buscar=&ajax=1'
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        clientesDatalist = datos.clientes || [];
        actualizarDatalistClientes('');
    })
    .catch(error => console.error('Error cargando clientes:', error));
}

/**
 * Cargar lista de productos mediante AJAX
 */
function cargarProductosAjax() {
    fetch('index.php?modulo=inventario&accion=buscar_ajax', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'buscar=&ajax=1'
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        productoDatalist = datos.productos || [];
        actualizarDatalistProductos('');
    })
    .catch(error => console.error('Error cargando productos:', error));
}

/**
 * Filtrar clientes según el texto ingresado
 */
function filtrarClientes(texto) {
    actualizarDatalistClientes(texto.toLowerCase());
}

/**
 * Filtrar productos según el texto ingresado
 */
function filtrarProductos(texto) {
    actualizarDatalistProductos(texto.toLowerCase());
}

/**
 * Actualizar datalist de clientes
 */
function actualizarDatalistClientes(filtro) {
    const datalist = document.getElementById('clientes_list');
    if (!datalist) return;
    
    datalist.innerHTML = '';
    
    const filtrados = clientesDatalist.filter(cliente => {
        const nombre = cliente.nombre.toLowerCase();
        const empresa = (cliente.empresa || '').toLowerCase();
        return nombre.includes(filtro) || empresa.includes(filtro);
    });
    
    // Mostrar máximo 10 opciones
    filtrados.slice(0, 10).forEach(cliente => {
        const option = document.createElement('option');
        option.setAttribute('data-id', cliente.id);
        option.value = `${cliente.nombre}${cliente.empresa ? ' - ' + cliente.empresa : ''}`;
        option.textContent = `${cliente.nombre}${cliente.empresa ? ' - ' + cliente.empresa : ''}`;
        datalist.appendChild(option);
    });
}

/**
 * Actualizar datalist de productos
 */
function actualizarDatalistProductos(filtro) {
    const datalist = document.getElementById('productos_list');
    if (!datalist) return;
    
    datalist.innerHTML = '';
    
    const filtrados = productoDatalist.filter(producto => {
        const nombre = producto.nombre.toLowerCase();
        const referencia = (producto.referencia || '').toLowerCase();
        return nombre.includes(filtro) || referencia.includes(filtro);
    });
    
    // Mostrar máximo 10 opciones
    filtrados.slice(0, 10).forEach(producto => {
        const option = document.createElement('option');
        option.setAttribute('data-id', producto.id);
        option.value = `${producto.nombre}${producto.referencia ? ' (' + producto.referencia + ')' : ''}`;
        option.textContent = `${producto.nombre}${producto.referencia ? ' (' + producto.referencia + ')' : ''}`;
        datalist.appendChild(option);
    });
}

/**
 * Seleccionar cliente del datalist
 */
function seleccionarCliente(clienteId) {
    document.getElementById('cliente_id').value = clienteId;
}

/**
 * Seleccionar producto del datalist
 */
function seleccionarProducto(productoId) {
    return productoId;
}
