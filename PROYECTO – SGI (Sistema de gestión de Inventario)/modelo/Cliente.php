<?php
/**
 * Modelo Cliente
 * Representa un cliente del sistema
 */
class Cliente {
    private $id;
    private $nombre;
    private $empresa;
    private $email;
    private $telefono;
    private $direccion;
    private $notas;
    private $fechaRegistro;
    private $fechaActualizacion;
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getEmpresa() {
        return $this->empresa;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function getDireccion() {
        return $this->direccion;
    }
    
    public function getNotas() {
        return $this->notas;
    }
    
    public function getFechaRegistro() {
        return $this->fechaRegistro;
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
    
    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
    
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
    
    public function setNotas($notas) {
        $this->notas = $notas;
    }
    
    public function setFechaRegistro($fechaRegistro) {
        $this->fechaRegistro = $fechaRegistro;
    }
    
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;
    }
}
?>
