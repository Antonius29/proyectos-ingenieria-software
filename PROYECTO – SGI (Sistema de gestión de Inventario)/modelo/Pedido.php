<?php
/**
 * Modelo Pedido
 * Representa un pedido del sistema
 */
class Pedido {
    private $id;
    private $numeroPedido;
    private $clienteId;
    private $usuarioId;
    private $fechaPedido;
    private $estado;
    private $metodoPago;
    private $direccionEnvio;
    private $total;
    private $notas;
    private $fechaCreacion;
    private $fechaActualizacion;
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getNumeroPedido() {
        return $this->numeroPedido;
    }
    
    public function getClienteId() {
        return $this->clienteId;
    }
    
    public function getUsuarioId() {
        return $this->usuarioId;
    }
    
    public function getFechaPedido() {
        return $this->fechaPedido;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function getMetodoPago() {
        return $this->metodoPago;
    }
    
    public function getDireccionEnvio() {
        return $this->direccionEnvio;
    }
    
    public function getTotal() {
        return $this->total;
    }
    
    public function getNotas() {
        return $this->notas;
    }
    
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }
    
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setNumeroPedido($numeroPedido) {
        $this->numeroPedido = $numeroPedido;
    }
    
    public function setClienteId($clienteId) {
        $this->clienteId = $clienteId;
    }
    
    public function setUsuarioId($usuarioId) {
        $this->usuarioId = $usuarioId;
    }
    
    public function setFechaPedido($fechaPedido) {
        $this->fechaPedido = $fechaPedido;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function setMetodoPago($metodoPago) {
        $this->metodoPago = $metodoPago;
    }
    
    public function setDireccionEnvio($direccionEnvio) {
        $this->direccionEnvio = $direccionEnvio;
    }
    
    public function setTotal($total) {
        $this->total = $total;
    }
    
    public function setNotas($notas) {
        $this->notas = $notas;
    }
    
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }
    
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;
    }
}
?>
