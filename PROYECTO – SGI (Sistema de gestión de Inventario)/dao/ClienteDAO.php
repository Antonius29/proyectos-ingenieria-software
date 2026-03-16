<?php
/**
 * ClienteDAO
 * Data Access Object para la entidad Cliente
 */
require_once 'config/Conexion.php';
require_once 'modelo/Cliente.php';

class ClienteDAO {
    
    private $conexion;
    
    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }
    
    /**
     * Crear un nuevo cliente
     */
    public function crear(Cliente $cliente) {
        try {
            $sql = "INSERT INTO clientes (nombre, empresa, email, telefono, direccion, notas) 
                    VALUES (:nombre, :empresa, :email, :telefono, :direccion, :notas)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $cliente->getNombre());
            $stmt->bindValue(':empresa', $cliente->getEmpresa());
            $stmt->bindValue(':email', $cliente->getEmail());
            $stmt->bindValue(':telefono', $cliente->getTelefono());
            $stmt->bindValue(':direccion', $cliente->getDireccion());
            $stmt->bindValue(':notas', $cliente->getNotas());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener todos los clientes
     */
    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM clientes ORDER BY id ASC";
            $stmt = $this->conexion->query($sql);
            $clientes = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($clientes as $key => $cliente) {
                $clientes[$key]['numero'] = $key + 1;
            }
            
            return $clientes;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener cliente por ID
     */
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM clientes WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Obtener cliente por email
     */
    public function obtenerPorEmail($email) {
        try {
            $sql = "SELECT * FROM clientes WHERE email = :email";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualizar cliente
     */
    public function actualizar(Cliente $cliente) {
        try {
            $sql = "UPDATE clientes SET 
                    nombre = :nombre, 
                    empresa = :empresa, 
                    email = :email, 
                    telefono = :telefono, 
                    direccion = :direccion, 
                    notas = :notas 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $cliente->getNombre());
            $stmt->bindValue(':empresa', $cliente->getEmpresa());
            $stmt->bindValue(':email', $cliente->getEmail());
            $stmt->bindValue(':telefono', $cliente->getTelefono());
            $stmt->bindValue(':direccion', $cliente->getDireccion());
            $stmt->bindValue(':notas', $cliente->getNotas());
            $stmt->bindValue(':id', $cliente->getId(), PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar cliente
     */
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM clientes WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Buscar clientes
     */
    public function buscar($termino) {
        try {
            $busquedaTermino = "%{$termino}%";
            $sql = "SELECT * FROM clientes WHERE 
                    nombre LIKE ? OR 
                    empresa LIKE ? OR 
                    email LIKE ? 
                    ORDER BY id ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$busquedaTermino, $busquedaTermino, $busquedaTermino]);
            
            $clientes = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($clientes as $key => $cliente) {
                $clientes[$key]['numero'] = $key + 1;
            }
            
            return $clientes;
        } catch (PDOException $e) {
            error_log('Error en buscar clientes: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener interacciones de un cliente
     */
    public function obtenerInteracciones($clienteId) {
        try {
            $sql = "SELECT ic.*, u.nombre as responsable_nombre 
                    FROM interacciones_cliente ic 
                    LEFT JOIN usuarios u ON ic.usuario_id = u.id 
                    WHERE ic.cliente_id = :cliente_id 
                    ORDER BY ic.fecha DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Crear interaccion
     */
    public function crearInteraccion($clienteId, $usuarioId, $tipo, $descripcion, $fecha) {
        try {
            $sql = "INSERT INTO interacciones_cliente (cliente_id, usuario_id, tipo, descripcion, fecha) 
                    VALUES (:cliente_id, :usuario_id, :tipo, :descripcion, :fecha)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':fecha', $fecha);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener interaccion por ID
     */
    public function obtenerInteraccionPorId($id) {
        try {
            $sql = "SELECT ic.*, u.nombre as responsable_nombre 
                    FROM interacciones_cliente ic 
                    LEFT JOIN usuarios u ON ic.usuario_id = u.id 
                    WHERE ic.id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualizar interaccion
     */
    public function actualizarInteraccion($id, $tipo, $descripcion, $fecha, $usuarioId) {
        try {
            $sql = "UPDATE interacciones_cliente SET 
                    tipo = :tipo, 
                    descripcion = :descripcion, 
                    fecha = :fecha, 
                    usuario_id = :usuario_id 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar interaccion
     */
    public function eliminarInteraccion($id) {
        try {
            $sql = "DELETE FROM interacciones_cliente WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
