<?php
/**
 * Modelo Usuario
 * Representa un usuario del sistema
 */
class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $password;
    private $telefono;
    private $rol;
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
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function getRol() {
        return $this->rol;
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
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
    
    public function setRol($rol) {
        $this->rol = $rol;
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
