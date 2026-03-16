<?php
/**
 * Controlador de Proveedores
 * Maneja todas las operaciones CRUD de proveedores
 */
require_once 'config/Conexion.php';
require_once 'modelo/Proveedor.php';
require_once 'dao/ProveedorDAO.php';

class ProveedorControlador {
    
    private $proveedorDAO;
    
    public function __construct() {
        $this->proveedorDAO = new ProveedorDAO();
    }
    
    /**
     * Listar todos los proveedores
     */
    public function lista() {
        $busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        
        if (!empty($busqueda)) {
            $proveedores = $this->proveedorDAO->buscar($busqueda);
        } else {
            $proveedores = $this->proveedorDAO->obtenerTodos();
        }
        
        include 'vista/proveedores/lista.php';
    }
    
    /**
     * Mostrar formulario de crear proveedor
     */
    public function crear() {
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        $datos = isset($_SESSION['datos_proveedor']) ? $_SESSION['datos_proveedor'] : [];
        unset($_SESSION['error'], $_SESSION['datos_proveedor']);
        
        include 'vista/proveedores/crear.php';
    }
    
    /**
     * Guardar nuevo proveedor
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $empresa = trim($_POST['empresa'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $notas = trim($_POST['notas'] ?? '');
        
        // Guardar datos para mostrar en caso de error
        $_SESSION['datos_proveedor'] = compact('nombre', 'empresa', 'email', 'telefono', 'direccion', 'notas');
        
        // Validaciones
        if (empty($nombre) || empty($empresa) || empty($email) || empty($telefono)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header('Location: index.php?modulo=proveedores&accion=crear');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El correo electronico no es valido';
            header('Location: index.php?modulo=proveedores&accion=crear');
            exit;
        }
        
        // Verificar si el email ya existe
        if ($this->proveedorDAO->obtenerPorEmail($email)) {
            $_SESSION['error'] = 'El correo electronico ya esta registrado';
            header('Location: index.php?modulo=proveedores&accion=crear');
            exit;
        }
        
        // Crear proveedor
        $proveedor = new Proveedor();
        $proveedor->setNombre($nombre);
        $proveedor->setEmpresa($empresa);
        $proveedor->setEmail($email);
        $proveedor->setTelefono($telefono);
        $proveedor->setDireccion($direccion);
        $proveedor->setNotas($notas);
        $proveedor->setEstado('activo');
        
        if ($this->proveedorDAO->crear($proveedor)) {
            unset($_SESSION['datos_proveedor']);
            $_SESSION['mensaje'] = 'Proveedor registrado exitosamente';
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        } else {
            $_SESSION['error'] = 'Error al registrar el proveedor';
            header('Location: index.php?modulo=proveedores&accion=crear');
            exit;
        }
    }
    
    /**
     * Ver detalle de proveedor
     */
    public function detalle() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $proveedor = $this->proveedorDAO->obtenerPorId($id);
        
        if (!$proveedor) {
            $_SESSION['error'] = 'Proveedor no encontrado';
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        }
        
        $suministros = $this->proveedorDAO->obtenerSuministros($id);
        
        include 'vista/proveedores/detalle.php';
    }
    
    /**
     * Mostrar formulario de editar proveedor
     */
    public function editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $proveedor = $this->proveedorDAO->obtenerPorId($id);
        
        if (!$proveedor) {
            $_SESSION['error'] = 'Proveedor no encontrado';
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        }
        
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/proveedores/editar.php';
    }
    
    /**
     * Actualizar proveedor
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $empresa = trim($_POST['empresa'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $notas = trim($_POST['notas'] ?? '');
        $estado = trim($_POST['estado'] ?? 'activo');
        
        // Validaciones
        if (empty($nombre) || empty($empresa) || empty($email) || empty($telefono)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=proveedores&accion=editar&id=$id");
            exit;
        }
        
        // Verificar si el email ya existe en otro proveedor
        $proveedorExistente = $this->proveedorDAO->obtenerPorEmail($email);
        if ($proveedorExistente && $proveedorExistente['id'] != $id) {
            $_SESSION['error'] = 'El correo electronico ya esta registrado en otro proveedor';
            header("Location: index.php?modulo=proveedores&accion=editar&id=$id");
            exit;
        }
        
        // Actualizar proveedor
        $proveedor = new Proveedor();
        $proveedor->setId($id);
        $proveedor->setNombre($nombre);
        $proveedor->setEmpresa($empresa);
        $proveedor->setEmail($email);
        $proveedor->setTelefono($telefono);
        $proveedor->setDireccion($direccion);
        $proveedor->setNotas($notas);
        $proveedor->setEstado($estado);
        
        if ($this->proveedorDAO->actualizar($proveedor)) {
            $_SESSION['mensaje'] = 'Proveedor actualizado exitosamente';
            header("Location: index.php?modulo=proveedores&accion=detalle&id=$id");
            exit;
        } else {
            $_SESSION['error'] = 'Error al actualizar el proveedor';
            header("Location: index.php?modulo=proveedores&accion=editar&id=$id");
            exit;
        }
    }
    
    /**
     * Eliminar proveedor
     */
    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($this->proveedorDAO->eliminar($id)) {
            $_SESSION['mensaje'] = 'Proveedor eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el proveedor';
        }
        
        header('Location: index.php?modulo=proveedores&accion=lista');
        exit;
    }
    
    /**
     * Mostrar formulario de crear suministro
     */
    public function suministro_crear() {
        $proveedorId = isset($_GET['proveedor_id']) ? (int)$_GET['proveedor_id'] : 0;
        
        $proveedor = $this->proveedorDAO->obtenerPorId($proveedorId);
        
        if (!$proveedor) {
            $_SESSION['error'] = 'Proveedor no encontrado';
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        }
        
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/proveedores/suministro-crear.php';
    }
    
    /**
     * Guardar suministro
     */
    public function suministro_guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        }
        
        $proveedorId = isset($_POST['proveedor_id']) ? (int)$_POST['proveedor_id'] : 0;
        $nombreProducto = trim($_POST['nombre_producto'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        
        // Validaciones
        if (empty($nombreProducto) || empty($categoria) || $precio <= 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=proveedores&accion=suministro_crear&proveedor_id=$proveedorId");
            exit;
        }
        
        if ($this->proveedorDAO->crearSuministro($proveedorId, $nombreProducto, $categoria, $precio)) {
            $_SESSION['mensaje'] = 'Suministro registrado exitosamente';
            header("Location: index.php?modulo=proveedores&accion=detalle&id=$proveedorId");
            exit;
        } else {
            $_SESSION['error'] = 'Error al registrar el suministro';
            header("Location: index.php?modulo=proveedores&accion=suministro_crear&proveedor_id=$proveedorId");
            exit;
        }
    }
    
    /**
     * Mostrar formulario de editar suministro
     */
    public function suministro_editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $suministro = $this->proveedorDAO->obtenerSuministroPorId($id);
        
        if (!$suministro) {
            $_SESSION['error'] = 'Suministro no encontrado';
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        }
        
        $proveedor = $this->proveedorDAO->obtenerPorId($suministro['proveedor_id']);
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/proveedores/suministro-editar.php';
    }
    
    /**
     * Actualizar suministro
     */
    public function suministro_actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=proveedores&accion=lista');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $proveedorId = isset($_POST['proveedor_id']) ? (int)$_POST['proveedor_id'] : 0;
        $nombreProducto = trim($_POST['nombre_producto'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        
        // Validaciones
        if (empty($nombreProducto) || empty($categoria) || $precio <= 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=proveedores&accion=suministro_editar&id=$id");
            exit;
        }
        
        if ($this->proveedorDAO->actualizarSuministro($id, $nombreProducto, $categoria, $precio)) {
            $_SESSION['mensaje'] = 'Suministro actualizado exitosamente';
            header("Location: index.php?modulo=proveedores&accion=detalle&id=$proveedorId");
            exit;
        } else {
            $_SESSION['error'] = 'Error al actualizar el suministro';
            header("Location: index.php?modulo=proveedores&accion=suministro_editar&id=$id");
            exit;
        }
    }
    
    /**
     * Eliminar suministro
     */
    public function suministro_eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $proveedorId = isset($_GET['proveedor_id']) ? (int)$_GET['proveedor_id'] : 0;
        
        if ($this->proveedorDAO->eliminarSuministro($id)) {
            $_SESSION['mensaje'] = 'Suministro eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el suministro';
        }
        
        header("Location: index.php?modulo=proveedores&accion=detalle&id=$proveedorId");
        exit;
    }
    
    /**
     * Eliminar proveedor mediante AJAX
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
            if ($this->proveedorDAO->eliminar($id)) {
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Proveedor eliminado exitosamente'
                ]);
            } else {
                echo json_encode(['error' => 'Error al eliminar el proveedor']);
            }
        } catch (Exception $e) {
            error_log('Error al eliminar proveedor: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al procesar la eliminación']);
        }
        exit;
    }
    
    /**
     * Retorna JSON con los resultados de la búsqueda de proveedores
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
        
        // Buscar proveedores según el término
        if (!empty($termino)) {
            $proveedores = $this->proveedorDAO->buscar($termino);
        } else {
            $proveedores = $this->proveedorDAO->obtenerTodos();
        }
        
        // Retornar respuesta en JSON
        echo json_encode(['proveedores' => $proveedores]);
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
