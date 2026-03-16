<?php
/**
 * Controlador de Pedidos
 * Maneja todas las operaciones CRUD de pedidos
 */
require_once 'config/Conexion.php';
require_once 'modelo/Pedido.php';
require_once 'dao/PedidoDAO.php';
require_once 'dao/ClienteDAO.php';
require_once 'dao/ProductoDAO.php';
require_once 'dao/UsuarioDAO.php';

class PedidoControlador {
    
    private $pedidoDAO;
    private $clienteDAO;
    private $productoDAO;
    private $usuarioDAO;
    
    public function __construct() {
        $this->pedidoDAO = new PedidoDAO();
        $this->clienteDAO = new ClienteDAO();
        $this->productoDAO = new ProductoDAO();
        $this->usuarioDAO = new UsuarioDAO();
    }
    
    /**
     * Listar todos los pedidos
     */
    public function lista() {
        $busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        
        if (!empty($busqueda)) {
            $pedidos = $this->pedidoDAO->buscar($busqueda);
        } else {
            $pedidos = $this->pedidoDAO->obtenerTodos();
        }
        
        include 'vista/pedidos/lista.php';
    }
    
    /**
     * Mostrar formulario de crear pedido
     */
    public function crear() {
        $clientes = $this->clienteDAO->obtenerTodos();
        $productos = $this->productoDAO->obtenerTodos();
        $usuarios = $this->usuarioDAO->obtenerTodos();
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        $datos = isset($_SESSION['datos_pedido']) ? $_SESSION['datos_pedido'] : [];
        unset($_SESSION['error'], $_SESSION['datos_pedido']);
        
        include 'vista/pedidos/crear.php';
    }
    
    /**
     * Guardar nuevo pedido
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $clienteId = trim($_POST['cliente_id'] ?? '');
        $usuarioId = trim($_POST['usuario_id'] ?? '');
        $fechaPedido = trim($_POST['fecha_pedido'] ?? '');
        $estado = trim($_POST['estado'] ?? 'pendiente');
        $metodoPago = trim($_POST['metodo_pago'] ?? '');
        $direccionEnvio = trim($_POST['direccion_envio'] ?? '');
        $total = 0; // El total se calculará automáticamente al agregar productos
        $notas = trim($_POST['notas'] ?? '');
        
        $_SESSION['datos_pedido'] = compact('clienteId', 'usuarioId', 'fechaPedido', 'estado', 'metodoPago', 'direccionEnvio', 'total', 'notas');
        
        // Validaciones
        if (empty($clienteId) || empty($fechaPedido) || empty($metodoPago) || empty($direccionEnvio)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header('Location: index.php?modulo=pedidos&accion=crear');
            exit;
        }
        
        // Crear pedido
        $pedido = new Pedido();
        $pedido->setNumeroPedido($this->pedidoDAO->generarNumeroPedido());
        $pedido->setClienteId($clienteId);
        $pedido->setUsuarioId(!empty($usuarioId) ? $usuarioId : null);
        $pedido->setFechaPedido($fechaPedido);
        $pedido->setEstado($estado);
        $pedido->setMetodoPago($metodoPago);
        $pedido->setDireccionEnvio($direccionEnvio);
        $pedido->setTotal($total);
        $pedido->setNotas($notas);
        
        if ($this->pedidoDAO->crear($pedido)) {
            unset($_SESSION['datos_pedido']);
            $_SESSION['mensaje'] = '✓ Pedido ' . htmlspecialchars($pedido->getNumeroPedido()) . ' registrado exitosamente';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        } else {
            $_SESSION['error'] = '✗ Error al registrar el pedido. Verifique los datos e intente nuevamente';
            header('Location: index.php?modulo=pedidos&accion=crear');
            exit;
        }
    }
    
    /**
     * Mostrar detalle del pedido
     */
    public function detalle() {
        $id = intval($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de pedido inválido';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $pedido = $this->pedidoDAO->obtenerPorId($id);
        
        if (!$pedido) {
            $_SESSION['error'] = 'El pedido no existe';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $items = $this->pedidoDAO->obtenerItems($id);
        
        include 'vista/pedidos/detalle.php';
    }
    
    /**
     * Mostrar formulario de editar pedido
     */
    public function editar() {
        $id = intval($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de pedido inválido';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $pedido = $this->pedidoDAO->obtenerPorId($id);
        
        if (!$pedido) {
            $_SESSION['error'] = 'El pedido no existe';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $clientes = $this->clienteDAO->obtenerTodos();
        $usuarios = $this->usuarioDAO->obtenerTodos();
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/pedidos/editar.php';
    }
    
    /**
     * Actualizar pedido
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $id = intval($_POST['id'] ?? 0);
        $clienteId = trim($_POST['cliente_id'] ?? '');
        $usuarioId = trim($_POST['usuario_id'] ?? '');
        $fechaPedido = trim($_POST['fecha_pedido'] ?? '');
        $estado = trim($_POST['estado'] ?? 'pendiente');
        $metodoPago = trim($_POST['metodo_pago'] ?? '');
        $direccionEnvio = trim($_POST['direccion_envio'] ?? '');
        $total = floatval($_POST['total'] ?? 0);
        $notas = trim($_POST['notas'] ?? '');
        
        // Validaciones
        if ($id <= 0 || empty($clienteId) || empty($fechaPedido) || empty($metodoPago) || empty($direccionEnvio)) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header('Location: index.php?modulo=pedidos&accion=editar&id=' . $id);
            exit;
        }
        
        $pedido = new Pedido();
        $pedido->setId($id);
        $pedido->setClienteId($clienteId);
        $pedido->setUsuarioId(!empty($usuarioId) ? $usuarioId : null);
        $pedido->setFechaPedido($fechaPedido);
        $pedido->setEstado($estado);
        $pedido->setMetodoPago($metodoPago);
        $pedido->setDireccionEnvio($direccionEnvio);
        $pedido->setTotal($total);
        $pedido->setNotas($notas);
        
        if ($this->pedidoDAO->actualizar($pedido)) {
            $_SESSION['mensaje'] = 'Pedido actualizado exitosamente';
            header('Location: index.php?modulo=pedidos&accion=detalle&id=' . $id);
            exit;
        } else {
            $_SESSION['error'] = 'Error al actualizar el pedido';
            header('Location: index.php?modulo=pedidos&accion=editar&id=' . $id);
            exit;
        }
    }
    
    /**
     * Eliminar pedido
     */
    public function eliminar() {
        $id = intval($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de pedido inválido';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        if ($this->pedidoDAO->eliminar($id)) {
            $_SESSION['mensaje'] = 'Pedido eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el pedido';
        }
        
        header('Location: index.php?modulo=pedidos&accion=lista');
        exit;
    }
    
    /**
     * Formulario de crear item de pedido
     */
    public function crear_item() {
        $pedidoId = intval($_GET['pedido_id'] ?? 0);
        
        if ($pedidoId <= 0) {
            $_SESSION['error'] = 'ID de pedido inválido';
            header('Location: index.php?modulo=pedidos&accion=detalle&id=1');
            exit;
        }
        
        $pedido = $this->pedidoDAO->obtenerPorId($pedidoId);
        if (!$pedido) {
            $_SESSION['error'] = 'El pedido no existe';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $productos = $this->productoDAO->obtenerTodos();
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/pedidos/item-crear.php';
    }
    
    /**
     * Guardar item de pedido
     */
    public function guardar_item() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $pedidoId = intval($_POST['pedido_id'] ?? 0);
        $productoId = intval($_POST['producto_id'] ?? 0);
        $cantidad = intval($_POST['cantidad'] ?? 0);
        $precioUnitario = floatval($_POST['precio_unitario'] ?? 0);
        $notas = trim($_POST['notas'] ?? '');
        
        if ($pedidoId <= 0 || $productoId <= 0 || $cantidad <= 0 || $precioUnitario <= 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header('Location: index.php?modulo=pedidos&accion=crear_item&pedido_id=' . $pedidoId);
            exit;
        }
        
        $subtotal = $cantidad * $precioUnitario;
        
        if ($this->pedidoDAO->crearItem($pedidoId, $productoId, $cantidad, $precioUnitario, $subtotal, $notas)) {
            // Recalcular el total del pedido
            $this->pedidoDAO->recalcularTotal($pedidoId);
            $_SESSION['mensaje'] = '✓ Item agregado exitosamente al pedido';
            header('Location: index.php?modulo=pedidos&accion=detalle&id=' . $pedidoId);
            exit;
        } else {
            $_SESSION['error'] = '✗ Error al agregar el item. Intente nuevamente';
            header('Location: index.php?modulo=pedidos&accion=crear_item&pedido_id=' . $pedidoId);
            exit;
        }
    }
    
    /**
     * Formulario de editar item
     */
    public function editar_item() {
        $itemId = intval($_GET['id'] ?? 0);
        
        if ($itemId <= 0) {
            $_SESSION['error'] = 'ID de item inválido';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $item = $this->pedidoDAO->obtenerItemPorId($itemId);
        
        if (!$item) {
            $_SESSION['error'] = 'El item no existe';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/pedidos/item-editar.php';
    }
    
    /**
     * Actualizar item de pedido
     */
    public function actualizar_item() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $id = intval($_POST['id'] ?? 0);
        $pedidoId = intval($_POST['pedido_id'] ?? 0);
        $cantidad = intval($_POST['cantidad'] ?? 0);
        $precioUnitario = floatval($_POST['precio_unitario'] ?? 0);
        $notas = trim($_POST['notas'] ?? '');
        
        if ($id <= 0 || $cantidad <= 0 || $precioUnitario <= 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
            header('Location: index.php?modulo=pedidos&accion=editar_item&id=' . $id);
            exit;
        }
        
        $subtotal = $cantidad * $precioUnitario;
        
        if ($this->pedidoDAO->actualizarItem($id, $cantidad, $precioUnitario, $subtotal, $notas)) {
            // Recalcular el total del pedido
            $this->pedidoDAO->recalcularTotal($pedidoId);
            $_SESSION['mensaje'] = '✓ Item actualizado exitosamente';
            header('Location: index.php?modulo=pedidos&accion=detalle&id=' . $pedidoId);
            exit;
        } else {
            $_SESSION['error'] = '✗ Error al actualizar el item';
            header('Location: index.php?modulo=pedidos&accion=editar_item&id=' . $id);
            exit;
        }
    }
    
    /**
     * Eliminar item de pedido
     */
    public function eliminar_item() {
        $itemId = intval($_GET['id'] ?? 0);
        $pedidoId = intval($_GET['pedido_id'] ?? 0);
        
        if ($itemId <= 0) {
            $_SESSION['error'] = 'ID de item inválido';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        if ($this->pedidoDAO->eliminarItem($itemId)) {
            // Recalcular el total del pedido
            $this->pedidoDAO->recalcularTotal($pedidoId);
            $_SESSION['mensaje'] = '✓ Item eliminado exitosamente';
        } else {
            $_SESSION['error'] = '✗ Error al eliminar el item';
        }
        
        header('Location: index.php?modulo=pedidos&accion=detalle&id=' . $pedidoId);
        exit;
    }
    
    /**
     * Formulario de cambiar estado
     */
    public function cambiar_estado() {
        $pedidoId = intval($_GET['id'] ?? 0);
        
        if ($pedidoId <= 0) {
            $_SESSION['error'] = 'ID de pedido inválido';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $pedido = $this->pedidoDAO->obtenerPorId($pedidoId);
        
        if (!$pedido) {
            $_SESSION['error'] = 'El pedido no existe';
            header('Location: index.php?modulo=pedidos&accion=lista');
            exit;
        }
        
        $usuarios = $this->usuarioDAO->obtenerTodos();
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        
        include 'vista/pedidos/cambiar-estado.php';
    }
    
    /**
     * Retorna JSON con los resultados de la búsqueda de pedidos
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
        
        // Buscar pedidos según el término
        if (!empty($termino)) {
            $pedidos = $this->pedidoDAO->buscar($termino);
        } else {
            $pedidos = $this->pedidoDAO->obtenerTodos();
        }
        
        // Retornar respuesta en JSON
        echo json_encode(['pedidos' => $pedidos]);
        exit;
    }
    
    /**
     * Eliminar item de pedido mediante AJAX
     */
    public function eliminar_item_ajax() {
        // Verificar si es petición AJAX y POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['ajax'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Petición inválida']);
            exit;
        }
        
        // Obtener IDs
        $itemId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $pedidoId = isset($_POST['pedido_id']) ? (int)$_POST['pedido_id'] : 0;
        
        if (empty($itemId) || empty($pedidoId)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'mensaje' => 'Datos inválidos']);
            exit;
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($this->pedidoDAO->eliminarItem($itemId)) {
                // Recalcular el total del pedido
                $this->pedidoDAO->recalcularTotal($pedidoId);
                
                // Obtener el nuevo total
                $pedido = $this->pedidoDAO->obtenerPorId($pedidoId);
                
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Item eliminado exitosamente',
                    'nuevoTotal' => number_format($pedido['total'], 2)
                ]);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'Error al eliminar el item']);
            }
        } catch (Exception $e) {
            error_log('Error al eliminar item: ' . $e->getMessage());
            echo json_encode(['success' => false, 'mensaje' => 'Error al procesar la eliminación']);
        }
        exit;
    }
    
    /**
     * Eliminar pedido mediante AJAX
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
            if ($this->pedidoDAO->eliminar($id)) {
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Pedido eliminado exitosamente'
                ]);
            } else {
                echo json_encode(['error' => 'Error al eliminar el pedido']);
            }
        } catch (Exception $e) {
            error_log('Error al eliminar pedido: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al procesar la eliminación']);
        }
        exit;
    }
    
    /**
     * Método por defecto
     */
    public function index() {
        $this->lista();
    }
}
?>
