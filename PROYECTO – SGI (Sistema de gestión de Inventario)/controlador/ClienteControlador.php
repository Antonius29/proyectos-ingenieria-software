<?php
/**
 * Controlador de Clientes
 * Maneja todas las operaciones CRUD de clientes
 */
require_once 'config/Conexion.php';
require_once 'modelo/Cliente.php';
require_once 'dao/ClienteDAO.php';
require_once 'dao/UsuarioDAO.php';

class ClienteControlador {
    
    private $clienteDAO;
    private $usuarioDAO;
    
    public function __construct() {
        $this->clienteDAO = new ClienteDAO();
        $this->usuarioDAO = new UsuarioDAO();
    }
    
    /**
     * Listar todos los clientes
     */
    public function lista() {
        $busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        
        if (!empty($busqueda)) {
            $clientes = $this->clienteDAO->buscar($busqueda);
        } else {
            $clientes = $this->clienteDAO->obtenerTodos();
        }
        
        include 'vista/clientes/lista.php';
    }
    
    /**
     * Mostrar formulario de crear cliente
     */
    public function crear() {
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        $datos = isset($_SESSION['datos_cliente']) ? $_SESSION['datos_cliente'] : [];
        unset($_SESSION['error'], $_SESSION['datos_cliente']);
        
        include 'vista/clientes/crear.php';
    }
    
    /**
     * Guardar nuevo cliente
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $empresa = trim($_POST['empresa'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $notas = trim($_POST['notas'] ?? '');
        
        // Guardar datos para mostrar en caso de error
        $_SESSION['datos_cliente'] = compact('nombre', 'empresa', 'email', 'telefono', 'direccion', 'notas');
        
        // Validaciones
        if (empty($nombre) || empty($empresa) || empty($email) || empty($telefono)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header('Location: index.php?modulo=clientes&accion=crear');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El correo electronico no es valido';
            header('Location: index.php?modulo=clientes&accion=crear');
            exit;
        }
        
        // Verificar si el email ya existe
        if ($this->clienteDAO->obtenerPorEmail($email)) {
            $_SESSION['error'] = 'El correo electronico ya esta registrado';
            header('Location: index.php?modulo=clientes&accion=crear');
            exit;
        }
        
        // Crear cliente
        $cliente = new Cliente();
        $cliente->setNombre($nombre);
        $cliente->setEmpresa($empresa);
        $cliente->setEmail($email);
        $cliente->setTelefono($telefono);
        $cliente->setDireccion($direccion);
        $cliente->setNotas($notas);
        
        if ($this->clienteDAO->crear($cliente)) {
            unset($_SESSION['datos_cliente']);
            $_SESSION['mensaje'] = 'Cliente registrado exitosamente';
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        } else {
            $_SESSION['error'] = 'Error al registrar el cliente';
            header('Location: index.php?modulo=clientes&accion=crear');
            exit;
        }
    }
    
    /**
     * Ver detalle de cliente
     */
    public function detalle() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $cliente = $this->clienteDAO->obtenerPorId($id);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        }
        
        $interacciones = $this->clienteDAO->obtenerInteracciones($id);
        
        include 'vista/clientes/detalle.php';
    }
    
    /**
     * Mostrar formulario de editar cliente
     */
    public function editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $cliente = $this->clienteDAO->obtenerPorId($id);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        }
        
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/clientes/editar.php';
    }
    
    /**
     * Actualizar cliente
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $empresa = trim($_POST['empresa'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $notas = trim($_POST['notas'] ?? '');
        
        // Validaciones
        if (empty($nombre) || empty($empresa) || empty($email) || empty($telefono)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=clientes&accion=editar&id=$id");
            exit;
        }
        
        // Verificar si el email ya existe en otro cliente
        $clienteExistente = $this->clienteDAO->obtenerPorEmail($email);
        if ($clienteExistente && $clienteExistente['id'] != $id) {
            $_SESSION['error'] = 'El correo electronico ya esta registrado en otro cliente';
            header("Location: index.php?modulo=clientes&accion=editar&id=$id");
            exit;
        }
        
        // Actualizar cliente
        $cliente = new Cliente();
        $cliente->setId($id);
        $cliente->setNombre($nombre);
        $cliente->setEmpresa($empresa);
        $cliente->setEmail($email);
        $cliente->setTelefono($telefono);
        $cliente->setDireccion($direccion);
        $cliente->setNotas($notas);
        
        if ($this->clienteDAO->actualizar($cliente)) {
            $_SESSION['mensaje'] = 'Cliente actualizado exitosamente';
            header("Location: index.php?modulo=clientes&accion=detalle&id=$id");
            exit;
        } else {
            $_SESSION['error'] = 'Error al actualizar el cliente';
            header("Location: index.php?modulo=clientes&accion=editar&id=$id");
            exit;
        }
    }
    
    /**
     * Eliminar cliente
     */
    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($this->clienteDAO->eliminar($id)) {
            $_SESSION['mensaje'] = 'Cliente eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el cliente';
        }
        
        header('Location: index.php?modulo=clientes&accion=lista');
        exit;
    }
    
    /**
     * Mostrar formulario de crear interaccion
     */
    public function interaccion_crear() {
        $clienteId = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
        
        $cliente = $this->clienteDAO->obtenerPorId($clienteId);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        }
        
        $usuarios = $this->usuarioDAO->obtenerTodos();
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/clientes/interaccion-crear.php';
    }
    
    /**
     * Guardar interaccion
     */
    public function interaccion_guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        }
        
        $clienteId = isset($_POST['cliente_id']) ? (int)$_POST['cliente_id'] : 0;
        $tipo = trim($_POST['tipo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $usuarioId = isset($_POST['responsable']) ? (int)$_POST['responsable'] : 0;
        
        // Validaciones
        if (empty($tipo) || empty($descripcion) || empty($fecha) || $usuarioId <= 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=clientes&accion=interaccion_crear&cliente_id=$clienteId");
            exit;
        }
        
        if ($this->clienteDAO->crearInteraccion($clienteId, $usuarioId, $tipo, $descripcion, $fecha)) {
            $_SESSION['mensaje'] = 'Interaccion registrada exitosamente';
            header("Location: index.php?modulo=clientes&accion=detalle&id=$clienteId");
            exit;
        } else {
            $_SESSION['error'] = 'Error al registrar la interaccion';
            header("Location: index.php?modulo=clientes&accion=interaccion_crear&cliente_id=$clienteId");
            exit;
        }
    }
    
    /**
     * Mostrar formulario de editar interaccion
     */
    public function interaccion_editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $interaccion = $this->clienteDAO->obtenerInteraccionPorId($id);
        
        if (!$interaccion) {
            $_SESSION['error'] = 'Interaccion no encontrada';
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        }
        
        $cliente = $this->clienteDAO->obtenerPorId($interaccion['cliente_id']);
        $usuarios = $this->usuarioDAO->obtenerTodos();
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/clientes/interaccion-editar.php';
    }
    
    /**
     * Actualizar interaccion
     */
    public function interaccion_actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=clientes&accion=lista');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $clienteId = isset($_POST['cliente_id']) ? (int)$_POST['cliente_id'] : 0;
        $tipo = trim($_POST['tipo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $usuarioId = isset($_POST['responsable']) ? (int)$_POST['responsable'] : 0;
        
        // Validaciones
        if (empty($tipo) || empty($descripcion) || empty($fecha) || $usuarioId <= 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=clientes&accion=interaccion_editar&id=$id");
            exit;
        }
        
        if ($this->clienteDAO->actualizarInteraccion($id, $tipo, $descripcion, $fecha, $usuarioId)) {
            $_SESSION['mensaje'] = 'Interaccion actualizada exitosamente';
            header("Location: index.php?modulo=clientes&accion=detalle&id=$clienteId");
            exit;
        } else {
            $_SESSION['error'] = 'Error al actualizar la interaccion';
            header("Location: index.php?modulo=clientes&accion=interaccion_editar&id=$id");
            exit;
        }
    }
    
    /**
     * Eliminar interaccion
     */
    public function interaccion_eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $clienteId = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
        
        if ($this->clienteDAO->eliminarInteraccion($id)) {
            $_SESSION['mensaje'] = 'Interaccion eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la interaccion';
        }
        
        header("Location: index.php?modulo=clientes&accion=detalle&id=$clienteId");
        exit;
    }
    
    /**
     * Búsqueda AJAX de clientes
     * Retorna JSON con los resultados de la búsqueda
     */
    public function buscar_ajax() {
        // Verificar si es una petición AJAX y POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['ajax'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Petición inválida']);
            exit;
        }
        
        // Obtener término de búsqueda
        $termino = trim($_POST['buscar'] ?? '');
        
        // Establecer header para respuesta JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // Buscar clientes según el término
        if (!empty($termino)) {
            $clientes = $this->clienteDAO->buscar($termino);
        } else {
            $clientes = $this->clienteDAO->obtenerTodos();
        }
        
        // Retornar respuesta en JSON
        echo json_encode(['clientes' => $clientes]);
        exit;
    }
    
    /**
     * Eliminar cliente mediante AJAX
     */
    public function eliminar_ajax() {
        // Verificar si es petición AJAX y POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['ajax'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Petición inválida']);
            exit;
        }
        
        // Obtener ID
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if (empty($id)) {
            echo json_encode(['error' => 'ID no válido']);
            exit;
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($this->clienteDAO->eliminar($id)) {
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Cliente eliminado exitosamente'
                ]);
            } else {
                echo json_encode(['error' => 'Error al eliminar el cliente']);
            }
        } catch (Exception $e) {
            error_log('Error al eliminar cliente: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al procesar la eliminación']);
        }
        exit;
    }
    
    /**
     * Metodo por defecto
     */
    public function index() {
        $this->lista();
    }
}
?>
