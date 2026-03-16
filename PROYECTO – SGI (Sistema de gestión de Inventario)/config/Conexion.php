<?php
/**
 * Clase Conexion
 * Maneja la conexion a la base de datos MySQL usando PDO
 */
class Conexion {
    private static $instancia = null;
    private $conexion;
    
    // Configuracion de la base de datos
    private $host = "localhost";
    private $usuario = "root";
    private $password = "admin";
    private $baseDatos = "sistema_gestion_clientes";
    private $charset = "utf8mb4";
    
    /**
     * Constructor privado para patron Singleton
     */
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->baseDatos};charset={$this->charset}";
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conexion = new PDO($dsn, $this->usuario, $this->password, $opciones);
        } catch (PDOException $e) {
            die("Error de conexion: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener instancia unica de la conexion (Singleton)
     */
    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }
    
    /**
     * Obtener la conexion PDO
     */
    public function getConexion() {
        return $this->conexion;
    }
    
    /**
     * Evitar clonacion del objeto
     */
    private function __clone() {}
    
    /**
     * Evitar deserializacion del objeto
     */
    public function __wakeup() {
        throw new Exception("No se puede deserializar una instancia de Conexion");
    }
}
?>
