<?php
/**
 * UsuarioDAO
 * Data Access Object para la entidad Usuario
 */
require_once 'config/Conexion.php';
require_once 'modelo/Usuario.php';

class UsuarioDAO {
    
    private $conexion;
    
    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }
    
    /**
     * Crear un nuevo usuario
     */
    public function crear(Usuario $usuario) {
        try {
            $sql = "INSERT INTO usuarios (nombre, email, password, telefono, rol, estado) 
                    VALUES (:nombre, :email, :password, :telefono, :rol, :estado)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $usuario->getNombre());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':password', $usuario->getPassword());
            $stmt->bindValue(':telefono', $usuario->getTelefono());
            $stmt->bindValue(':rol', $usuario->getRol());
            $stmt->bindValue(':estado', $usuario->getEstado());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM usuarios ORDER BY id ASC";
            $stmt = $this->conexion->query($sql);
            $usuarios = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($usuarios as $key => $usuario) {
                $usuarios[$key]['numero'] = $key + 1;
            }
            
            return $usuarios;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Obtener usuario por email
     */
    public function obtenerPorEmail($email) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualizar usuario
     */
    public function actualizar(Usuario $usuario) {
        try {
            $sql = "UPDATE usuarios SET 
                    nombre = :nombre, 
                    email = :email, 
                    telefono = :telefono, 
                    rol = :rol, 
                    estado = :estado 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':nombre', $usuario->getNombre());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':telefono', $usuario->getTelefono());
            $stmt->bindValue(':rol', $usuario->getRol());
            $stmt->bindValue(':estado', $usuario->getEstado());
            $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar contrasena
     */
    public function actualizarPassword($id, $password) {
        try {
            $sql = "UPDATE usuarios SET password = :password WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Buscar usuarios
     */
    public function buscar($termino) {
        try {
            $busquedaTermino = "%{$termino}%";
            $sql = "SELECT * FROM usuarios WHERE 
                    nombre LIKE ? OR 
                    email LIKE ? OR 
                    rol LIKE ? 
                    ORDER BY id ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$busquedaTermino, $busquedaTermino, $busquedaTermino]);
            
            $usuarios = $stmt->fetchAll();
            
            // Agregar número secuencial
            foreach ($usuarios as $key => $usuario) {
                $usuarios[$key]['numero'] = $key + 1;
            }
            
            return $usuarios;
        } catch (PDOException $e) {
            error_log('Error en buscar usuarios: ' . $e->getMessage());
            return [];
        }
    }
}
?>
