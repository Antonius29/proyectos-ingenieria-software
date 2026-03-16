<?php
/**
 * Controlador de Productos (Inventario)
 * Maneja todas las operaciones CRUD de productos
 */
require_once 'config/Conexion.php';
require_once 'modelo/Producto.php';
require_once 'dao/ProductoDAO.php';
require_once 'dao/ProveedorDAO.php';
require_once 'dao/UsuarioDAO.php';

class ProductoControlador {
    
    private $productoDAO;
    private $proveedorDAO;
    private $usuarioDAO;
    
    public function __construct() {
        $this->productoDAO = new ProductoDAO();
        $this->proveedorDAO = new ProveedorDAO();
        $this->usuarioDAO = new UsuarioDAO();
    }
    
    /**
     * Listar todos los productos
     */
    public function lista() {
        $busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        
        if (!empty($busqueda)) {
            $productos = $this->productoDAO->buscar($busqueda);
        } else {
            $productos = $this->productoDAO->obtenerTodos();
        }
        
        include 'vista/inventario/lista.php';
    }
    
    /**
     * Mostrar formulario de crear producto
     */
    public function crear() {
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        $datos = isset($_SESSION['datos_producto']) ? $_SESSION['datos_producto'] : [];
        unset($_SESSION['error'], $_SESSION['datos_producto']);
        
        $categorias = $this->productoDAO->obtenerCategorias();
        $proveedores = $this->proveedorDAO->obtenerActivos();
        
        include 'vista/inventario/crear.php';
    }
    
    /**
     * Guardar nuevo producto
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $categoriaId = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 0;
        $cantidadStock = isset($_POST['cantidad_stock']) ? (int)$_POST['cantidad_stock'] : 0;
        $stockMinimo = isset($_POST['stock_minimo']) ? (int)$_POST['stock_minimo'] : 5;
        $precio = floatval($_POST['precio'] ?? 0);
        $proveedorId = isset($_POST['proveedor_id']) ? (int)$_POST['proveedor_id'] : null;
        
        // Guardar datos para mostrar en caso de error
        $_SESSION['datos_producto'] = compact('nombre', 'descripcion', 'categoriaId', 'cantidadStock', 'stockMinimo', 'precio', 'proveedorId');
        
        // Validaciones
        if (empty($nombre) || $precio <= 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header('Location: index.php?modulo=inventario&accion=crear');
            exit;
        }
        
        // Determinar estado
        $estado = 'disponible';
        if ($cantidadStock <= 0) {
            $estado = 'agotado';
        }
        
        // Crear producto
        $producto = new Producto();
        $producto->setNombre($nombre);
        $producto->setDescripcion($descripcion);
        $producto->setCategoriaId($categoriaId > 0 ? $categoriaId : null);
        $producto->setCantidadStock($cantidadStock);
        $producto->setStockMinimo($stockMinimo);
        $producto->setPrecio($precio);
        $producto->setProveedorId($proveedorId > 0 ? $proveedorId : null);
        $producto->setEstado($estado);
        
        if ($this->productoDAO->crear($producto)) {
            unset($_SESSION['datos_producto']);
            $_SESSION['mensaje'] = 'Producto registrado exitosamente';
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        } else {
            $_SESSION['error'] = 'Error al registrar el producto';
            header('Location: index.php?modulo=inventario&accion=crear');
            exit;
        }
    }
    
    /**
     * Ver detalle de producto
     */
    public function detalle() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $producto = $this->productoDAO->obtenerPorId($id);
        
        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado';
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        }
        
        $movimientos = $this->productoDAO->obtenerMovimientos($id);
        
        include 'vista/inventario/detalle.php';
    }
    
    /**
     * Mostrar formulario de editar producto
     */
    public function editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $producto = $this->productoDAO->obtenerPorId($id);
        
        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado';
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        }
        
        $categorias = $this->productoDAO->obtenerCategorias();
        $proveedores = $this->proveedorDAO->obtenerActivos();
        
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/inventario/editar.php';
    }
    
    /**
     * Actualizar producto
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $categoriaId = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 0;
        $cantidadStock = isset($_POST['cantidad_stock']) ? (int)$_POST['cantidad_stock'] : 0;
        $stockMinimo = isset($_POST['stock_minimo']) ? (int)$_POST['stock_minimo'] : 5;
        $precio = floatval($_POST['precio'] ?? 0);
        $proveedorId = isset($_POST['proveedor_id']) ? (int)$_POST['proveedor_id'] : null;
        $descontinuado = isset($_POST['descontinuado']) ? true : false;
        
        // Validaciones
        if (empty($nombre) || $precio <= 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=inventario&accion=editar&id=$id");
            exit;
        }
        
        // Determinar estado automáticamente
        if ($descontinuado) {
            $estado = 'descontinuado';
        } elseif ($cantidadStock <= 0) {
            $estado = 'agotado';
        } else {
            $estado = 'disponible';
        }
        
        // Actualizar producto
        $producto = new Producto();
        $producto->setId($id);
        $producto->setNombre($nombre);
        $producto->setDescripcion($descripcion);
        $producto->setCategoriaId($categoriaId > 0 ? $categoriaId : null);
        $producto->setCantidadStock($cantidadStock);
        $producto->setStockMinimo($stockMinimo);
        $producto->setPrecio($precio);
        $producto->setProveedorId($proveedorId > 0 ? $proveedorId : null);
        $producto->setEstado($estado);
        
        if ($this->productoDAO->actualizar($producto)) {
            $_SESSION['mensaje'] = 'Producto actualizado exitosamente';
            header("Location: index.php?modulo=inventario&accion=detalle&id=$id");
            exit;
        } else {
            $_SESSION['error'] = 'Error al actualizar el producto';
            header("Location: index.php?modulo=inventario&accion=editar&id=$id");
            exit;
        }
    }
    
    /**
     * Eliminar producto
     */
    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($this->productoDAO->eliminar($id)) {
            $_SESSION['mensaje'] = 'Producto eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el producto';
        }
        
        header('Location: index.php?modulo=inventario&accion=lista');
        exit;
    }
    
    /**
     * Mostrar formulario de crear movimiento
     */
    public function movimiento_crear() {
        $productoId = isset($_GET['producto_id']) ? (int)$_GET['producto_id'] : 0;
        
        $producto = $this->productoDAO->obtenerPorId($productoId);
        
        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado';
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        }
        
        $usuarios = $this->usuarioDAO->obtenerTodos();
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/inventario/movimiento-crear.php';
    }
    
    /**
     * Guardar movimiento
     */
    public function movimiento_guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        }
        
        $productoId = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
        $tipo = trim($_POST['tipo'] ?? '');
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
        $motivo = trim($_POST['motivo'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $usuarioId = $_SESSION['usuario_id'];
        
        // Validaciones
        if (empty($tipo) || $cantidad <= 0 || empty($fecha)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=inventario&accion=movimiento_crear&producto_id=$productoId");
            exit;
        }
        
        if ($this->productoDAO->crearMovimiento($productoId, $usuarioId, $tipo, $cantidad, $motivo, $fecha)) {
            $_SESSION['mensaje'] = 'Movimiento registrado exitosamente';
            header("Location: index.php?modulo=inventario&accion=detalle&id=$productoId");
            exit;
        } else {
            $_SESSION['error'] = 'Error al registrar el movimiento. Verifique que haya stock suficiente.';
            header("Location: index.php?modulo=inventario&accion=movimiento_crear&producto_id=$productoId");
            exit;
        }
    }
    
    /**
     * Mostrar formulario de editar movimiento
     */
    public function movimiento_editar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $movimiento = $this->productoDAO->obtenerMovimientoPorId($id);
        
        if (!$movimiento) {
            $_SESSION['error'] = 'Movimiento no encontrado';
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        }
        
        $producto = $this->productoDAO->obtenerPorId($movimiento['producto_id']);
        $usuarios = $this->usuarioDAO->obtenerTodos();
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/inventario/movimiento-editar.php';
    }
    
    /**
     * Actualizar movimiento
     */
    public function movimiento_actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=inventario&accion=lista');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $productoId = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
        $tipo = trim($_POST['tipo'] ?? '');
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
        $motivo = trim($_POST['motivo'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        
        // Validaciones
        if (empty($tipo) || $cantidad <= 0 || empty($fecha)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header("Location: index.php?modulo=inventario&accion=movimiento_editar&id=$id");
            exit;
        }
        
        if ($this->productoDAO->actualizarMovimiento($id, $tipo, $cantidad, $motivo, $fecha)) {
            $_SESSION['mensaje'] = 'Movimiento actualizado exitosamente';
            header("Location: index.php?modulo=inventario&accion=detalle&id=$productoId");
            exit;
        } else {
            $_SESSION['error'] = 'Error al actualizar el movimiento';
            header("Location: index.php?modulo=inventario&accion=movimiento_editar&id=$id");
            exit;
        }
    }
    
    /**
     * Eliminar movimiento
     */
    public function movimiento_eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $productoId = isset($_GET['producto_id']) ? (int)$_GET['producto_id'] : 0;
        
        if ($this->productoDAO->eliminarMovimiento($id)) {
            $_SESSION['mensaje'] = 'Movimiento eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el movimiento';
        }
        
        header("Location: index.php?modulo=inventario&accion=detalle&id=$productoId");
        exit;
    }
    
    /**
     * Eliminar producto mediante AJAX
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
            if ($this->productoDAO->eliminar($id)) {
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Producto eliminado exitosamente'
                ]);
            } else {
                echo json_encode(['error' => 'Error al eliminar el producto']);
            }
        } catch (Exception $e) {
            error_log('Error al eliminar producto: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al procesar la eliminación']);
        }
        exit;
    }
    
    /**
     * Retorna JSON con los resultados de la búsqueda de productos
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
        
        // Buscar productos según el término
        if (!empty($termino)) {
            $productos = $this->productoDAO->buscar($termino);
        } else {
            $productos = $this->productoDAO->obtenerTodos();
        }
        
        // Retornar respuesta en JSON
        echo json_encode(['productos' => $productos]);
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
