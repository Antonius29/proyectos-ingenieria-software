<?php
/**
 * PedidoDAO
 * Data Access Object para la entidad Pedido
 */
require_once 'config/Conexion.php';
require_once 'modelo/Pedido.php';

class PedidoDAO {
    
    private $conexion;
    
    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }
    
    /**
     * Crear un nuevo pedido
     */
    public function crear(Pedido $pedido) {
        try {
            $sql = "INSERT INTO pedidos (numero_pedido, cliente_id, usuario_id, fecha_pedido, estado, metodo_pago, direccion_envio, total, notas) 
                    VALUES (:numero_pedido, :cliente_id, :usuario_id, :fecha_pedido, :estado, :metodo_pago, :direccion_envio, :total, :notas)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':numero_pedido', $pedido->getNumeroPedido());
            $stmt->bindValue(':cliente_id', $pedido->getClienteId(), PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $pedido->getUsuarioId(), PDO::PARAM_INT);
            $stmt->bindValue(':fecha_pedido', $pedido->getFechaPedido());
            $stmt->bindValue(':estado', $pedido->getEstado());
            $stmt->bindValue(':metodo_pago', $pedido->getMetodoPago());
            $stmt->bindValue(':direccion_envio', $pedido->getDireccionEnvio());
            $stmt->bindValue(':total', $pedido->getTotal());
            $stmt->bindValue(':notas', $pedido->getNotas());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener todos los pedidos
     */
    public function obtenerTodos() {
        try {
            $sql = "SELECT p.id, p.numero_pedido, p.cliente_id, p.usuario_id, p.fecha_pedido, p.estado, p.metodo_pago, p.direccion_envio, p.total, p.notas, p.fecha_creacion, p.fecha_actualizacion, c.nombre as cliente_nombre 
                    FROM pedidos p 
                    LEFT JOIN clientes c ON p.cliente_id = c.id 
                    ORDER BY p.id DESC";
            $stmt = $this->conexion->query($sql);
            $pedidos = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($pedidos as $key => $pedido) {
                $pedidos[$key]['numero'] = $key + 1;
            }
            
            return $pedidos;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener pedido por ID
     */
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT p.*, c.nombre as cliente_nombre, u.nombre as usuario_nombre 
                    FROM pedidos p 
                    LEFT JOIN clientes c ON p.cliente_id = c.id 
                    LEFT JOIN usuarios u ON p.usuario_id = u.id 
                    WHERE p.id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Obtener pedido por número de pedido
     */
    public function obtenerPorNumeroPedido($numeroPedido) {
        try {
            $sql = "SELECT p.*, c.nombre as cliente_nombre, u.nombre as usuario_nombre 
                    FROM pedidos p 
                    LEFT JOIN clientes c ON p.cliente_id = c.id 
                    LEFT JOIN usuarios u ON p.usuario_id = u.id 
                    WHERE p.numero_pedido = :numero_pedido";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':numero_pedido', $numeroPedido);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualizar pedido
     */
    public function actualizar(Pedido $pedido) {
        try {
            $sql = "UPDATE pedidos SET 
                    cliente_id = :cliente_id, 
                    usuario_id = :usuario_id, 
                    fecha_pedido = :fecha_pedido, 
                    estado = :estado, 
                    metodo_pago = :metodo_pago, 
                    direccion_envio = :direccion_envio, 
                    total = :total, 
                    notas = :notas 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':cliente_id', $pedido->getClienteId(), PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $pedido->getUsuarioId(), PDO::PARAM_INT);
            $stmt->bindValue(':fecha_pedido', $pedido->getFechaPedido());
            $stmt->bindValue(':estado', $pedido->getEstado());
            $stmt->bindValue(':metodo_pago', $pedido->getMetodoPago());
            $stmt->bindValue(':direccion_envio', $pedido->getDireccionEnvio());
            $stmt->bindValue(':total', $pedido->getTotal());
            $stmt->bindValue(':notas', $pedido->getNotas());
            $stmt->bindValue(':id', $pedido->getId(), PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar pedido
     */
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM pedidos WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Buscar pedidos
     */
    public function buscar($termino) {
        try {
            $busquedaTermino = "%{$termino}%";
            $sql = "SELECT p.*, c.nombre as cliente_nombre 
                    FROM pedidos p 
                    LEFT JOIN clientes c ON p.cliente_id = c.id 
                    WHERE p.numero_pedido LIKE ? OR 
                          c.nombre LIKE ? OR 
                          p.estado LIKE ? 
                    ORDER BY p.id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$busquedaTermino, $busquedaTermino, $busquedaTermino]);
            
            $pedidos = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($pedidos as $key => $pedido) {
                $pedidos[$key]['numero'] = $key + 1;
            }
            
            return $pedidos;
        } catch (PDOException $e) {
            error_log('Error en buscar pedidos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener items de un pedido
     */
    public function obtenerItems($pedidoId) {
        try {
            $sql = "SELECT ip.id, ip.pedido_id, ip.producto_id, ip.cantidad, ip.precio_unitario, ip.subtotal, ip.notas, ip.fecha_creacion, p.nombre as producto_nombre, p.descripcion as producto_descripcion 
                    FROM items_pedido ip 
                    LEFT JOIN productos p ON ip.producto_id = p.id 
                    WHERE ip.pedido_id = :pedido_id 
                    ORDER BY ip.id ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':pedido_id', $pedidoId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Crear item de pedido
     */
    public function crearItem($pedidoId, $productoId, $cantidad, $precioUnitario, $subtotal, $notas = null) {
        try {
            $sql = "INSERT INTO items_pedido (pedido_id, producto_id, cantidad, precio_unitario, subtotal, notas) 
                    VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario, :subtotal, :notas)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':pedido_id', $pedidoId, PDO::PARAM_INT);
            $stmt->bindValue(':producto_id', $productoId, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindValue(':precio_unitario', $precioUnitario);
            $stmt->bindValue(':subtotal', $subtotal);
            $stmt->bindValue(':notas', $notas);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener item por ID
     */
    public function obtenerItemPorId($id) {
        try {
            $sql = "SELECT ip.id, ip.pedido_id, ip.producto_id, ip.cantidad, ip.precio_unitario, ip.subtotal, ip.notas, ip.fecha_creacion, p.nombre as producto_nombre 
                    FROM items_pedido ip 
                    LEFT JOIN productos p ON ip.producto_id = p.id 
                    WHERE ip.id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualizar item de pedido
     */
    public function actualizarItem($id, $cantidad, $precioUnitario, $subtotal, $notas = null) {
        try {
            $sql = "UPDATE items_pedido SET 
                    cantidad = :cantidad, 
                    precio_unitario = :precio_unitario, 
                    subtotal = :subtotal, 
                    notas = :notas 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindValue(':precio_unitario', $precioUnitario);
            $stmt->bindValue(':subtotal', $subtotal);
            $stmt->bindValue(':notas', $notas);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar item de pedido
     */
    public function eliminarItem($id) {
        try {
            $sql = "DELETE FROM items_pedido WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Cambiar estado del pedido
     */
    public function cambiarEstado($id, $nuevoEstado) {
        try {
            $sql = "UPDATE pedidos SET estado = :estado WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':estado', $nuevoEstado);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener pedidos por cliente
     */
    public function obtenerPorCliente($clienteId) {
        try {
            $sql = "SELECT * FROM pedidos WHERE cliente_id = :cliente_id ORDER BY fecha_pedido DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener pedidos por estado
     */
    public function obtenerPorEstado($estado) {
        try {
            $sql = "SELECT p.*, c.nombre as cliente_nombre 
                    FROM pedidos p 
                    LEFT JOIN clientes c ON p.cliente_id = c.id 
                    WHERE p.estado = :estado 
                    ORDER BY p.fecha_pedido DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':estado', $estado);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Generar próximo número de pedido
     */
    public function generarNumeroPedido() {
        try {
            $sql = "SELECT MAX(id) as max_id FROM pedidos";
            $stmt = $this->conexion->query($sql);
            $resultado = $stmt->fetch();
            $numero = ($resultado['max_id'] ?? 0) + 1;
            return "PED" . str_pad($numero, 4, '0', STR_PAD_LEFT);
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Recalcular el total del pedido basado en los items
     */
    public function recalcularTotal($pedidoId) {
        try {
            // Obtener la suma de los subtotales de los items
            $sql = "SELECT SUM(subtotal) as total_calculado FROM items_pedido WHERE pedido_id = :pedido_id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':pedido_id', $pedidoId, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch();
            
            $nuevoTotal = $resultado['total_calculado'] ?? 0;
            
            // Actualizar el total del pedido
            $sqlUpdate = "UPDATE pedidos SET total = :total WHERE id = :id";
            $stmtUpdate = $this->conexion->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':total', $nuevoTotal);
            $stmtUpdate->bindValue(':id', $pedidoId, PDO::PARAM_INT);
            
            return $stmtUpdate->execute();
        } catch (PDOException $e) {
            error_log('Error al recalcular total: ' . $e->getMessage());
            return false;
        }
    }
}
?>
