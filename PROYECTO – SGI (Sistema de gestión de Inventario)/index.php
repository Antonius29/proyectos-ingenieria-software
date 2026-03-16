<?php
/**
 * SISTEMA DE GESTION DE INVENTARIO
 * Archivo principal - Router
 */

// Configurar tiempo de vida de sesión (30 minutos de inactividad)
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);

// Iniciar sesión
session_start();

// Detectar si el servidor se reinició comparando PID
$pidActual = getmypid();
if (isset($_SESSION['servidor_pid']) && $_SESSION['servidor_pid'] !== $pidActual) {
    // El PID cambió, significa que se reinició el servidor
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    session_start();
}

// Guardar PID del servidor en la sesión
$_SESSION['servidor_pid'] = $pidActual;

// Obtener la accion solicitada
$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : 'auth';
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'login';

// Rutas publicas (no requieren autenticacion)
$rutasPublicas = [
    'auth' => ['login', 'registro', 'procesar_login', 'procesar_registro', 'logout', 'renovar_sesion']
];

// Verificar si la ruta es publica
$esRutaPublica = isset($rutasPublicas[$modulo]) && in_array($accion, $rutasPublicas[$modulo]);

// Si intenta acceder a ruta protegida sin sesión
if (!$esRutaPublica && !isset($_SESSION['usuario_id'])) {
    header('Location: index.php?modulo=auth&accion=login');
    exit;
}

// Si accede sin parámetros
if (!isset($_GET['modulo']) && !isset($_GET['accion'])) {
    // Si está autenticado, ir al dashboard
    if (isset($_SESSION['usuario_id'])) {
        header('Location: index.php?modulo=dashboard&accion=index');
        exit;
    } else {
        // Si no está autenticado, ir al login
        header('Location: index.php?modulo=auth&accion=login');
        exit;
    }
}

// Cargar el controlador correspondiente
switch ($modulo) {
    case 'auth':
        require_once 'controlador/AuthControlador.php';
        $controlador = new AuthControlador();
        break;
    
    case 'dashboard':
        require_once 'controlador/DashboardControlador.php';
        $controlador = new DashboardControlador();
        break;
    
    case 'clientes':
        require_once 'controlador/ClienteControlador.php';
        $controlador = new ClienteControlador();
        break;
    
    case 'usuarios':
        require_once 'controlador/UsuarioControlador.php';
        $controlador = new UsuarioControlador();
        break;
    
    case 'proveedores':
        require_once 'controlador/ProveedorControlador.php';
        $controlador = new ProveedorControlador();
        break;
    
    case 'inventario':
        require_once 'controlador/ProductoControlador.php';
        $controlador = new ProductoControlador();
        break;
    
    case 'pedidos':
        require_once 'controlador/PedidoControlador.php';
        $controlador = new PedidoControlador();
        break;
    
    default:
        header('Location: index.php?modulo=auth&accion=login');
        exit;
}

// Ejecutar la accion
if (method_exists($controlador, $accion)) {
    $controlador->$accion();
} else {
    // Accion por defecto del controlador
    $controlador->index();
}
?>
