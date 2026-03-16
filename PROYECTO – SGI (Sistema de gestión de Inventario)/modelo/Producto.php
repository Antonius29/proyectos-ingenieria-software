<?php
/**
 * Modelo Producto
 * Representa un producto del inventario
 */
class Producto {
    private $id;
    private $nombre;
    private $descripcion;
    private $categoriaId;
    private $cantidadStock;
    private $stockMinimo;
    private $precio;
    private $proveedorId;
    private $estado;
    private $fechaCreacion;
    private $fechaActualizacion;
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getDescripcion() {
        return $this->descripcion;
    }
    
    public function getCategoriaId() {
        return $this->categoriaId;
    }
    
    public function getCantidadStock() {
        return $this->cantidadStock;
    }
    
    public function getStockMinimo() {
        return $this->stockMinimo;
    }
    
    public function getPrecio() {
        return $this->precio;
    }
    
    public function getProveedorId() {
        return $this->proveedorId;
    }
    
    public function getEstado() {
        return $this->estado;
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
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    
    public function setCategoriaId($categoriaId) {
        $this->categoriaId = $categoriaId;
    }
    
    public function setCantidadStock($cantidadStock) {
        $this->cantidadStock = $cantidadStock;
    }
    
    public function setStockMinimo($stockMinimo) {
        $this->stockMinimo = $stockMinimo;
    }
    
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    
    public function setProveedorId($proveedorId) {
        $this->proveedorId = $proveedorId;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }
    
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;
    }
}
?>
