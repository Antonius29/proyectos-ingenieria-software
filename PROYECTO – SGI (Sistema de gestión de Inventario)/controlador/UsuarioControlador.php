<?php
/**
 * Controlador de Usuarios
 * Maneja todas las operaciones CRUD de usuarios
 */
require_once 'config/Conexion.php';
require_once 'modelo/Usuario.php';
require_once 'dao/UsuarioDAO.php';

class UsuarioControlador {
    
    private $usuarioDAO;
    
    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }
    
    /**
     * Listar todos los usuarios
     */
    public function lista() {
        $busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        
        if (!empty($busqueda)) {
            $usuarios = $this->usuarioDAO->buscar($busqueda);
        } else {
            $usuarios = $this->usuarioDAO->obtenerTodos();
        }
        
        include 'vista/usuarios/lista.php';
    }
    
    /**
     * Mostrar formulario de crear usuario
     */
    public function crear() {
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        $datos = isset($_SESSION['datos_usuario']) ? $_SESSION['datos_usuario'] : [];
        unset($_SESSION['error'], $_SESSION['datos_usuario']);
        
        include 'vista/usuarios/crear.php';
    }
    
    /**
     * Guardar nuevo usuario
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $rol = trim($_POST['rol'] ?? 'empleado');
        $password = $_POST['password'] ?? '';
        
        // Guardar datos para mostrar en caso de error
        $_SESSION['datos_usuario'] = compact('nombre', 'email', 'telefono', 'rol');
        
        // Validaciones
        if (empty($nombre) || empty($email) || empty($telefono) || empty($password)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header('Location: index.php?modulo=usuarios&accion=crear');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El correo electronico no es valido';
            header('Location: index.php?modulo=usuarios&accion=crear');
            exit;
        }
        
        if (strlen($password) < 8) {
            $_SESSION['error'] = 'La contrasena debe tener al menos 8 caracteres';
            header('Location: index.php?modulo=usuarios&accion=crear');
            exit;
        }
        
        // Verificar si el email ya existe
        if ($this->usuarioDAO->obtenerPorEmail($email)) {
            $_SESSION['error'] = 'El correo electronico ya esta registrado';
            header('Location: index.php?modulo=usuarios&accion=crear');
            exit;
        }
        
        // Crear usuario
        $usuario = new Usuario();
        $usuario->setNombre($nombre);
        $usuario->setEmail($email);
        $usuario->setTelefono($telefono);
        $usuario->setRol($rol);
        $usuario->setPassword($password);
        $usuario->setEstado('activo');
        
        if ($this->usuarioDAO->crear($usuario)) {
            unset($_SESSION['datos_usuario']);
            $_SESSION['mensaje'] = 'Usuario registrado exitosamente';
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        } else {
            $_SESSION['error'] = 'Error al registrar el usuario';
            header('Location: index.php?modulo=usuarios&accion=crear');
            exit;
        }
    }
    
    /**
     * Ver detalle de usuario
     */
    public function detalle() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $usuario = $this->usuarioDAO->obtenerPorId($id);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
        
        // Obtener actividades del usuario
        $actividades = $this->obtenerActividades($id);
        
        include 'vista/usuarios/detalle.php';
    }
    
    /**
     * Mostrar formulario de editar usuario
     */
    public function editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $usuario = $this->usuarioDAO->obtenerPorId($id);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
        
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/usuarios/editar.php';
    }
    
    /**
     * Actualizar usuario
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $rol = trim($_POST['rol'] ?? 'empleado');
        $estado = trim($_POST['estado'] ?? 'activo');
        $password = $_POST['password'] ?? '';
        
        // Validaciones
        if (empty($nombre) || empty($email) || empty($telefono)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=usuarios&accion=editar&id=$id");
            exit;
        }
        
        // Verificar si el email ya existe en otro usuario
        $usuarioExistente = $this->usuarioDAO->obtenerPorEmail($email);
        if ($usuarioExistente && $usuarioExistente['id'] != $id) {
            $_SESSION['error'] = 'El correo electronico ya esta registrado en otro usuario';
            header("Location: index.php?modulo=usuarios&accion=editar&id=$id");
            exit;
        }
        
        // Actualizar usuario
        $usuario = new Usuario();
        $usuario->setId($id);
        $usuario->setNombre($nombre);
        $usuario->setEmail($email);
        $usuario->setTelefono($telefono);
        $usuario->setRol($rol);
        $usuario->setEstado($estado);
        
        if ($this->usuarioDAO->actualizar($usuario)) {
            // Actualizar contrasena si se proporciona
            if (!empty($password)) {
                if (strlen($password) >= 8) {
                    $this->usuarioDAO->actualizarPassword($id, $password);
                }
            }
            
            $_SESSION['mensaje'] = 'Usuario actualizado exitosamente';
            header("Location: index.php?modulo=usuarios&accion=detalle&id=$id");
            exit;
        } else {
            $_SESSION['error'] = 'Error al actualizar el usuario';
            header("Location: index.php?modulo=usuarios&accion=editar&id=$id");
            exit;
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // No permitir eliminar el usuario actual
        if ($id == $_SESSION['usuario_id']) {
            $_SESSION['error'] = 'No puede eliminar su propio usuario';
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
        
        if ($this->usuarioDAO->eliminar($id)) {
            $_SESSION['mensaje'] = 'Usuario eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario';
        }
        
        header('Location: index.php?modulo=usuarios&accion=lista');
        exit;
    }
    
    /**
     * Obtener actividades de un usuario
     */
    private function obtenerActividades($usuarioId) {
        try {
            $conexion = Conexion::getInstancia()->getConexion();
            $sql = "SELECT * FROM actividades_usuario WHERE usuario_id = :usuario_id ORDER BY fecha DESC LIMIT 10";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Mostrar formulario de crear actividad
     */
    public function actividad_crear() {
        $usuarioId = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 0;
        
        $usuario = $this->usuarioDAO->obtenerPorId($usuarioId);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
        
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/usuarios/actividad-crear.php';
    }
    
    /**
     * Guardar actividad
     */
    public function actividad_guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
        
        $usuarioId = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
        $tipo = trim($_POST['tipo_actividad'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        
        // Validaciones
        if (empty($tipo) || empty($descripcion) || empty($fecha)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=usuarios&accion=actividad_crear&usuario_id=$usuarioId");
            exit;
        }
        
        try {
            $conexion = Conexion::getInstancia()->getConexion();
            $sql = "INSERT INTO actividades_usuario (usuario_id, tipo_actividad, descripcion, fecha) VALUES (:usuario_id, :tipo, :descripcion, :fecha)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->execute();
            
            $_SESSION['mensaje'] = 'Actividad registrada exitosamente';
            header("Location: index.php?modulo=usuarios&accion=detalle&id=$usuarioId");
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al registrar la actividad';
            header("Location: index.php?modulo=usuarios&accion=actividad_crear&usuario_id=$usuarioId");
            exit;
        }
    }
    
    /**
     * Mostrar formulario de editar actividad
     */
    public function actividad_editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        try {
            $conexion = Conexion::getInstancia()->getConexion();
            $sql = "SELECT * FROM actividades_usuario WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $actividad = $stmt->fetch();
            
            if (!$actividad) {
                $_SESSION['error'] = 'Actividad no encontrada';
                header('Location: index.php?modulo=usuarios&accion=lista');
                exit;
            }
            
            $usuario = $this->usuarioDAO->obtenerPorId($actividad['usuario_id']);
            $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
            unset($_SESSION['error']);
            
            include 'vista/usuarios/actividad-editar.php';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al cargar la actividad';
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
    }
    
    /**
     * Actualizar actividad
     */
    public function actividad_actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=usuarios&accion=lista');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $usuarioId = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
        $tipo = trim($_POST['tipo_actividad'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        
        // Validaciones
        if (empty($tipo) || empty($descripcion) || empty($fecha)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=usuarios&accion=actividad_editar&id=$id");
            exit;
        }
        
        try {
            $conexion = Conexion::getInstancia()->getConexion();
            $sql = "UPDATE actividades_usuario SET tipo_actividad = :tipo, descripcion = :descripcion, fecha = :fecha WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $_SESSION['mensaje'] = 'Actividad actualizada exitosamente';
            header("Location: index.php?modulo=usuarios&accion=detalle&id=$usuarioId");
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al actualizar la actividad';
            header("Location: index.php?modulo=usuarios&accion=actividad_editar&id=$id");
            exit;
        }
    }
    
    /**
     * Eliminar actividad
     */
    public function actividad_eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $usuarioId = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 0;
        
        try {
            $conexion = Conexion::getInstancia()->getConexion();
            $sql = "DELETE FROM actividades_usuario WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $_SESSION['mensaje'] = 'Actividad eliminada exitosamente';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al eliminar la actividad';
        }
        
        header("Location: index.php?modulo=usuarios&accion=detalle&id=$usuarioId");
        exit;
    }
    
    /**
     * Eliminar usuario mediante AJAX
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
        
        // No permitir eliminar el usuario logueado
        if ($id === $_SESSION['usuario_id']) {
            echo json_encode(['error' => 'No puede eliminar su propia cuenta']);
            exit;
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($this->usuarioDAO->eliminar($id)) {
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Usuario eliminado exitosamente'
                ]);
            } else {
                echo json_encode(['error' => 'Error al eliminar el usuario']);
            }
        } catch (Exception $e) {
            error_log('Error al eliminar usuario: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al procesar la eliminación']);
        }
        exit;
    }
    
    /**
     * Retorna JSON con los resultados de la búsqueda de usuarios
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
        
        // Buscar usuarios según el término
        if (!empty($termino)) {
            $usuarios = $this->usuarioDAO->buscar($termino);
        } else {
            $usuarios = $this->usuarioDAO->obtenerTodos();
        }
        
        // Retornar respuesta en JSON
        echo json_encode(['usuarios' => $usuarios]);
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
