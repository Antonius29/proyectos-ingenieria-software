<?php
/**
 * ProductoDAO
 * Data Access Object para la entidad Producto
 */
require_once 'config/Conexion.php';
require_once 'modelo/Producto.php';

class ProductoDAO {
    
    private $conexion;
    
    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }
    
    /**
     * Crear un nuevo producto
     */
    public function crear(Producto $producto) {
        try {
            $sql = "INSERT INTO productos (nombre, descripcion, categoria_id, cantidad_stock, stock_minimo, precio, proveedor_id, estado) 
                    VALUES (:nombre, :descripcion, :categoria_id, :cantidad_stock, :stock_minimo, :precio, :proveedor_id, :estado)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $producto->getNombre());
            $stmt->bindValue(':descripcion', $producto->getDescripcion());
            $stmt->bindValue(':categoria_id', $producto->getCategoriaId() ?: null, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad_stock', $producto->getCantidadStock(), PDO::PARAM_INT);
            $stmt->bindValue(':stock_minimo', $producto->getStockMinimo(), PDO::PARAM_INT);
            $stmt->bindValue(':precio', $producto->getPrecio());
            $stmt->bindValue(':proveedor_id', $producto->getProveedorId() ?: null, PDO::PARAM_INT);
            $stmt->bindValue(':estado', $producto->getEstado());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('ProductoDAO crear error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener todos los productos
     */
    public function obtenerTodos() {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.empresa as proveedor_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    LEFT JOIN proveedores pr ON p.proveedor_id = pr.id 
                    ORDER BY p.id ASC";
            $stmt = $this->conexion->query($sql);
            $productos = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($productos as $key => $producto) {
                $productos[$key]['numero'] = $key + 1;
            }
            
            return $productos;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener productos disponibles
     */
    public function obtenerDisponibles() {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.empresa as proveedor_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    LEFT JOIN proveedores pr ON p.proveedor_id = pr.id 
                    WHERE p.estado = 'disponible' AND p.cantidad_stock > 0
                    ORDER BY p.nombre ASC";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener producto por ID
     */
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.empresa as proveedor_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    LEFT JOIN proveedores pr ON p.proveedor_id = pr.id 
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
     * Actualizar producto
     */
    public function actualizar(Producto $producto) {
        try {
            $sql = "UPDATE productos SET 
                    nombre = :nombre, 
                    descripcion = :descripcion, 
                    categoria_id = :categoria_id, 
                    cantidad_stock = :cantidad_stock, 
                    stock_minimo = :stock_minimo, 
                    precio = :precio, 
                    proveedor_id = :proveedor_id, 
                    estado = :estado 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $producto->getNombre());
            $stmt->bindValue(':descripcion', $producto->getDescripcion());
            $stmt->bindValue(':categoria_id', $producto->getCategoriaId() ?: null, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad_stock', $producto->getCantidadStock(), PDO::PARAM_INT);
            $stmt->bindValue(':stock_minimo', $producto->getStockMinimo(), PDO::PARAM_INT);
            $stmt->bindValue(':precio', $producto->getPrecio());
            $stmt->bindValue(':proveedor_id', $producto->getProveedorId() ?: null, PDO::PARAM_INT);
            $stmt->bindValue(':estado', $producto->getEstado());
            $stmt->bindValue(':id', $producto->getId(), PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log('ProductoDAO actualizar error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar producto
     */
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Buscar productos
     */
    public function buscar($termino) {
        try {
            $busquedaTermino = "%{$termino}%";
            $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.empresa as proveedor_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    LEFT JOIN proveedores pr ON p.proveedor_id = pr.id 
                    WHERE p.nombre LIKE ? OR 
                          c.nombre LIKE ? OR 
                          pr.empresa LIKE ? 
                    ORDER BY p.id ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$busquedaTermino, $busquedaTermino, $busquedaTermino]);
            
            $productos = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($productos as $key => $producto) {
                $productos[$key]['numero'] = $key + 1;
            }
            
            return $productos;
        } catch (PDOException $e) {
            error_log('Error en buscar productos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener categorias
     */
    public function obtenerCategorias() {
        try {
            $sql = "SELECT * FROM categorias ORDER BY nombre ASC";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener movimientos de un producto
     */
    public function obtenerMovimientos($productoId) {
        try {
            $sql = "SELECT m.*, u.nombre as usuario_nombre 
                    FROM movimientos_inventario m 
                    LEFT JOIN usuarios u ON m.usuario_id = u.id 
                    WHERE m.producto_id = :producto_id 
                    ORDER BY m.fecha DESC, m.id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':producto_id', $productoId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Crear movimiento de inventario
     */
    public function crearMovimiento($productoId, $usuarioId, $tipo, $cantidad, $motivo, $fecha) {
        try {
            // Iniciar transaccion
            $this->conexion->beginTransaction();
            
            // Obtener producto actual
            $producto = $this->obtenerPorId($productoId);
            if (!$producto) {
                $this->conexion->rollBack();
                return false;
            }
            
            // Calcular nuevo stock
            $nuevoStock = $producto['cantidad_stock'];
            if ($tipo == 'entrada') {
                $nuevoStock += $cantidad;
            } elseif ($tipo == 'salida') {
                $nuevoStock -= $cantidad;
                if ($nuevoStock < 0) {
                    $this->conexion->rollBack();
                    return false;
                }
            } else { // ajuste
                $nuevoStock = $cantidad;
            }
            
            // Determinar nuevo estado automáticamente (solo si no está descontinuado)
            $nuevoEstado = $producto['estado'];
            if ($producto['estado'] != 'descontinuado') {
                $nuevoEstado = ($nuevoStock > 0) ? 'disponible' : 'agotado';
            }
            
            // Actualizar stock y estado
            $sqlStock = "UPDATE productos SET cantidad_stock = :stock, estado = :estado WHERE id = :id";
            $stmtStock = $this->conexion->prepare($sqlStock);
            $stmtStock->bindValue(':stock', $nuevoStock, PDO::PARAM_INT);
            $stmtStock->bindValue(':estado', $nuevoEstado);
            $stmtStock->bindValue(':id', $productoId, PDO::PARAM_INT);
            $stmtStock->execute();
            
            // Registrar movimiento
            $sql = "INSERT INTO movimientos_inventario (producto_id, usuario_id, tipo, cantidad, motivo, fecha) 
                    VALUES (:producto_id, :usuario_id, :tipo, :cantidad, :motivo, :fecha)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':producto_id', $productoId, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindValue(':motivo', $motivo);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->execute();
            
            $this->conexion->commit();
            return true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return false;
        }
    }
    
    /**
     * Obtener movimiento por ID
     */
    public function obtenerMovimientoPorId($id) {
        try {
            $sql = "SELECT m.*, u.nombre as usuario_nombre 
                    FROM movimientos_inventario m 
                    LEFT JOIN usuarios u ON m.usuario_id = u.id 
                    WHERE m.id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualizar movimiento
     */
    public function actualizarMovimiento($id, $tipo, $cantidad, $motivo, $fecha) {
        try {
            $sql = "UPDATE movimientos_inventario SET 
                    tipo = :tipo, 
                    cantidad = :cantidad, 
                    motivo = :motivo, 
                    fecha = :fecha 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindValue(':motivo', $motivo);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar movimiento
     */
    public function eliminarMovimiento($id) {
        try {
            $sql = "DELETE FROM movimientos_inventario WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar stock de producto
     */
    public function actualizarStock($productoId, $cantidad) {
        try {
            $sql = "UPDATE productos SET cantidad_stock = cantidad_stock + :cantidad WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindValue(':id', $productoId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar estado automáticamente basado en cantidad de stock
     * El estado 'descontinuado' no se modifica automáticamente
     */
    public function actualizarEstadoAutomatico($productoId) {
        try {
            $producto = $this->obtenerPorId($productoId);
            if (!$producto) {
                return false;
            }
            
            // Solo actualizar si no está descontinuado
            if ($producto['estado'] != 'descontinuado') {
                $nuevoEstado = ($producto['cantidad_stock'] > 0) ? 'disponible' : 'agotado';
                $sql = "UPDATE productos SET estado = :estado WHERE id = :id";
                $stmt = $this->conexion->prepare($sql);
                $stmt->bindValue(':estado', $nuevoEstado);
                $stmt->bindValue(':id', $productoId, PDO::PARAM_INT);
                return $stmt->execute();
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
