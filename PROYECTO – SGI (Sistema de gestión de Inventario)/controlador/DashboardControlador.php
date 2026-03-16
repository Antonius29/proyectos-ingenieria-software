<?php
/**
 * Controlador de Dashboard
 * Muestra el panel de control con estadisticas
 */
require_once 'config/Conexion.php';

class DashboardControlador {
    
    private $conexion;
    
    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }
    
    /**
     * Mostrar dashboard con estadisticas
     */
    public function index() {
        $estadisticas = $this->obtenerEstadisticas();
        include 'vista/dashboard.php';
    }
    
    /**
     * Obtener estadisticas para el dashboard
     */
    private function obtenerEstadisticas() {
        $stats = [
            'total_clientes' => 0,
            'usuarios_activos' => 0,
            'total_proveedores' => 0,
            'productos_stock' => 0
        ];
        
        try {
            // Total clientes
            $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM clientes");
            $stats['total_clientes'] = $stmt->fetch()['total'];
            
            // Usuarios activos
            $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 'activo'");
            $stats['usuarios_activos'] = $stmt->fetch()['total'];
            
            // Total proveedores
            $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM proveedores WHERE estado = 'activo'");
            $stats['total_proveedores'] = $stmt->fetch()['total'];
            
            // Productos en stock
            $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM productos WHERE cantidad_stock > 0");
            $stats['productos_stock'] = $stmt->fetch()['total'];
            
        } catch (PDOException $e) {
            // En caso de error, devolver valores por defecto
        }
        
        return $stats;
    }
}
?>
