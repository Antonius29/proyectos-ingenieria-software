<?php
/**
 * ProveedorDAO
 * Data Access Object para la entidad Proveedor
 */
require_once 'config/Conexion.php';
require_once 'modelo/Proveedor.php';

class ProveedorDAO {
    
    private $conexion;
    
    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }
    
    /**
     * Crear un nuevo proveedor
     */
    public function crear(Proveedor $proveedor) {
        try {
            $sql = "INSERT INTO proveedores (nombre, empresa, email, telefono, direccion, notas, estado) 
                    VALUES (:nombre, :empresa, :email, :telefono, :direccion, :notas, :estado)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $proveedor->getNombre());
            $stmt->bindValue(':empresa', $proveedor->getEmpresa());
            $stmt->bindValue(':email', $proveedor->getEmail());
            $stmt->bindValue(':telefono', $proveedor->getTelefono());
            $stmt->bindValue(':direccion', $proveedor->getDireccion());
            $stmt->bindValue(':notas', $proveedor->getNotas());
            $stmt->bindValue(':estado', $proveedor->getEstado());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener todos los proveedores
     */
    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM proveedores ORDER BY id ASC";
            $stmt = $this->conexion->query($sql);
            $proveedores = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($proveedores as $key => $proveedor) {
                $proveedores[$key]['numero'] = $key + 1;
            }
            
            return $proveedores;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener proveedores activos
     */
    public function obtenerActivos() {
        try {
            $sql = "SELECT * FROM proveedores WHERE estado = 'activo' ORDER BY nombre ASC";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener proveedor por ID
     */
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM proveedores WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Obtener proveedor por email
     */
    public function obtenerPorEmail($email) {
        try {
            $sql = "SELECT * FROM proveedores WHERE email = :email";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualizar proveedor
     */
    public function actualizar(Proveedor $proveedor) {
        try {
            $sql = "UPDATE proveedores SET 
                    nombre = :nombre, 
                    empresa = :empresa, 
                    email = :email, 
                    telefono = :telefono, 
                    direccion = :direccion, 
                    notas = :notas,
                    estado = :estado 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $proveedor->getNombre());
            $stmt->bindValue(':empresa', $proveedor->getEmpresa());
            $stmt->bindValue(':email', $proveedor->getEmail());
            $stmt->bindValue(':telefono', $proveedor->getTelefono());
            $stmt->bindValue(':direccion', $proveedor->getDireccion());
            $stmt->bindValue(':notas', $proveedor->getNotas());
            $stmt->bindValue(':estado', $proveedor->getEstado());
            $stmt->bindValue(':id', $proveedor->getId(), PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar proveedor
     */
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM proveedores WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Buscar proveedores
     */
    public function buscar($termino) {
        try {
            $busquedaTermino = "%{$termino}%";
            $sql = "SELECT * FROM proveedores WHERE 
                    nombre LIKE ? OR 
                    empresa LIKE ? OR 
                    email LIKE ? 
                    ORDER BY id ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$busquedaTermino, $busquedaTermino, $busquedaTermino]);
            
            $proveedores = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($proveedores as $key => $proveedor) {
                $proveedores[$key]['numero'] = $key + 1;
            }
            
            return $proveedores;
        } catch (PDOException $e) {
            error_log('Error en buscar proveedores: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener suministros de un proveedor
     */
    public function obtenerSuministros($proveedorId) {
        try {
            $sql = "SELECT * FROM suministros_proveedor WHERE proveedor_id = :proveedor_id ORDER BY nombre_producto ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':proveedor_id', $proveedorId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Crear suministro
     */
    public function crearSuministro($proveedorId, $nombreProducto, $categoria, $precio) {
        try {
            $sql = "INSERT INTO suministros_proveedor (proveedor_id, nombre_producto, categoria, precio) 
                    VALUES (:proveedor_id, :nombre_producto, :categoria, :precio)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':proveedor_id', $proveedorId, PDO::PARAM_INT);
            $stmt->bindValue(':nombre_producto', $nombreProducto);
            $stmt->bindValue(':categoria', $categoria);
            $stmt->bindValue(':precio', $precio);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener suministro por ID
     */
    public function obtenerSuministroPorId($id) {
        try {
            $sql = "SELECT * FROM suministros_proveedor WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualizar suministro
     */
    public function actualizarSuministro($id, $nombreProducto, $categoria, $precio) {
        try {
            $sql = "UPDATE suministros_proveedor SET 
                    nombre_producto = :nombre_producto, 
                    categoria = :categoria, 
                    precio = :precio 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre_producto', $nombreProducto);
            $stmt->bindValue(':categoria', $categoria);
            $stmt->bindValue(':precio', $precio);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar suministro
     */
    public function eliminarSuministro($id) {
        try {
            $sql = "DELETE FROM suministros_proveedor WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
