/**
 * Script para Mantener la Sesión Activa
 * Realiza un ping periódico al servidor para renovar la sesión
 * mientras el usuario esté en la página
 */

// Tiempos en milisegundos
const INTERVALO_RENOVACION = 15 * 60 * 1000; // 15 minutos
//const TIEMPO_EXPIRACION = 10 * 1000; // 10 segundos (para prueba)
//const TIEMPO_ADVERTENCIA = 5 * 1000; // 5 segundos - mostrar alerta 5 segundos antes
const TIEMPO_EXPIRACION = 5 * 60 * 1000;   // 5 minutos
const TIEMPO_ADVERTENCIA = 4 * 60 * 1000;  // alerta 1 minuto antes
let tiempoInicioSesion = Date.now();
let alertaMostrada = false;
let sesionRenovada = false;
let intervaloVerificacion = null; // 🔥 Variable para controlar el intervalo

/**
 * Función para renovar la sesión
 */
function renovarSesion() {
    fetch('index.php?modulo=auth&accion=renovar_sesion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(respuesta => {
        if (respuesta.status === 401) {
            // La sesión expiró, redirigir al login
            console.warn('Sesión expirada. Redirigiendo al login...');
            window.location.href = 'index.php?modulo=auth&accion=login';
        }
        return respuesta.json();
    })
    .then(datos => {
        if (datos.success) {
            // Reiniciar el contador de tiempo de sesión
            tiempoInicioSesion = Date.now();
            alertaMostrada = false;
            sesionRenovada = false;
            // NO cerrar la alerta automáticamente - solo el usuario puede hacerlo
        }
    })
    .catch(error => {
        console.error('Error al renovar sesión:', error);
    });
}

/**
 * Mostrar alerta de expiración de sesión
 */
function mostrarAlertaExpiracion() {
    if (alertaMostrada) return;
    alertaMostrada = true;
    
    // 🔥 DETENER VERIFICACIÓN MIENTRAS MUESTRA LA ALERTA
    clearInterval(intervaloVerificacion);
    console.log('✓ Intervalo de verificación detenido');
    console.log('✓ Mostrando alerta de expiración');
    
    const alerta = document.createElement('div');
    alerta.id = 'alerta-expiracion-sesion';
    alerta.className = 'alerta-expiracion-sesion';
    alerta.innerHTML = `
        <div class="alerta-expiracion-contenido">
            <div class="alerta-icono">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="alerta-texto">
                <h3>¡Tu sesión está por expirar!</h3>
                <p>Tu sesión ha estado inactiva demasiado tiempo.</p>
                <p class="alerta-subtexto">Elige una opción para continuar o cerrar tu sesión.</p>
            </div>
            <div class="alerta-botones">
                <button id="btnCerrarSesion" type="button" class="btn btn-secondary" onclick="cerrarSesionDesdeAlerta(event);">
                    <i class="bi bi-x"></i> Cerrar sesión
                </button>
                <button id="btnContinuarTrabajando" type="button" class="btn btn-primary" onclick="renovarSesionDesdeAlerta(event);">
                    <i class="bi bi-arrow-repeat"></i> Continuar trabajando
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(alerta);
    
    console.log('✓ Modal agregado al DOM');
    
    // Animar entrada
    setTimeout(() => {
        alerta.classList.add('activa');
        console.log('✓ Clase "activa" agregada a la alerta');
    }, 10);
}

/**
 * Cerrar alerta de expiración
 */
function cerrarAlertaExpiracion() {
    const alerta = document.getElementById('alerta-expiracion-sesion');
    if (alerta) {
        alerta.classList.remove('activa');
        setTimeout(() => {
            alerta.remove();
        }, 300);
    }
}

/**
 * Cerrar sesión desde la alerta
 */
function cerrarSesionDesdeAlerta(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    console.log('✓ Botón cerrar sesión clickeado');
    
    // 🔥 DETENER INTERVALO ANTES DE REDIRIGIR
    clearInterval(intervaloVerificacion);
    console.log('✓ Intervalo de verificación detenido DEFINITIVAMENTE');
    
    cerrarAlertaExpiracion();
    window.location.href = 'index.php?modulo=auth&accion=logout';
}

/**
 * Renovar sesión desde la alerta
 */
function renovarSesionDesdeAlerta(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    console.log('✓ Botón continuar trabajando clickeado');
    
    // 🔥 DETENER INTERVALO MIENTRAS PROCESA LA RENOVACIÓN
    clearInterval(intervaloVerificacion);
    console.log('✓ Intervalo detenido temporalmente');
    
    sesionRenovada = true;
    cerrarAlertaExpiracion();
    renovarSesion();
    
    // 🔥 REINICIAR INTERVALO DESPUÉS DE RENOVAR
    console.log('✓ Reiniciando intervalo de verificación...');
    intervaloVerificacion = setInterval(verificarEstadoSesion, 1000);
}

/**
 * Verificar estado de la sesión periódicamente
 */
function verificarEstadoSesion() {
    const tiempoTranscurrido = Date.now() - tiempoInicioSesion;
    
    // Si pasó el tiempo de advertencia, mostrar alerta
    if (tiempoTranscurrido >= TIEMPO_ADVERTENCIA && !alertaMostrada) {
        mostrarAlertaExpiracion();
    }
    
    // Si pasó el tiempo de expiración y no renovó desde la alerta, redirigir al login
    if (tiempoTranscurrido >= TIEMPO_EXPIRACION && !sesionRenovada) {
        console.warn('Sesión expirada por inactividad.');
        window.location.href = 'index.php?modulo=auth&accion=login';
    }
}

/**
 * Inicializar renovación automática de sesión
 */
document.addEventListener('DOMContentLoaded', function() {
    // Renovar sesión al cargar la página
    renovarSesion();
    
    // Configurar renovación periódica cada 15 minutos
    setInterval(renovarSesion, INTERVALO_RENOVACION);
    
    // 🔥 GUARDAR EL INTERVALO EN VARIABLE PARA PODER CONTROLARLO
    intervaloVerificacion = setInterval(verificarEstadoSesion, 1000);
    
    console.log('✓ Sistema de renovación de sesión activo');
    console.log('✓ Alerta de expiración (prueba: 10 segundos)');
});

/**
 * Estilos para la alerta de expiración
 */
const styleAlert = document.createElement('style');
styleAlert.textContent = `
    .alerta-expiracion-sesion {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    .alerta-expiracion-sesion.activa {
        opacity: 1;
        pointer-events: auto;
    }
    
    .alerta-expiracion-contenido {
        background-color: white;
        border-radius: 12px;
        padding: 40px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        text-align: center;
        position: relative;
        z-index: 100000;
        pointer-events: auto;
    }
    
    .alerta-icono {
        font-size: 60px;
        color: #ff9800;
        margin-bottom: 20px;
    }
    
    .alerta-texto h3 {
        color: #333;
        margin: 0 0 15px 0;
        font-size: 22px;
        font-weight: 700;
    }
    
    .alerta-texto p {
        color: #666;
        margin: 10px 0;
        font-size: 15px;
        line-height: 1.6;
    }
    
    .alerta-subtexto {
        color: #999;
        font-size: 13px !important;
        margin-top: 15px !important;
    }
    
    .alerta-botones {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        justify-content: center;
    }
    
    .alerta-botones .btn {
        padding: 12px 24px;
        border-radius: 6px;
        border: none;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        pointer-events: auto;
        position: relative;
        z-index: 100001;
    }
    
    .alerta-botones .btn-secondary {
        background-color: #f0f0f0;
        color: #333;
    }
    
    .alerta-botones .btn-secondary:hover {
        background-color: #e0e0e0;
    }
    
    .alerta-botones .btn-primary {
        background-color: #36baac;
        color: white;
    }
    
    .alerta-botones .btn-primary:hover {
        background-color: #2a9a8f;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(54, 186, 172, 0.3);
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(styleAlert);
