USE bd_clientes;
# Procedimientos para Tabla: Cliente

-- 1. Insertar Cliente
DELIMITER //
CREATE PROCEDURE sp_cliente_insertar(
    IN p_cedula VARCHAR(10),
    IN p_nombre VARCHAR(200),
    IN p_tipo_id INT,
    IN p_telefono VARCHAR(10),
    IN p_direccion TEXT
)
BEGIN
    INSERT INTO Cliente (cedula, nombre, tipo_cliente_id, telefono, direccion) 
    VALUES (p_cedula, p_nombre, p_tipo_id, p_telefono, p_direccion);
END //
DELIMITER ;

-- 2. Actualizar Cliente
DELIMITER //
CREATE PROCEDURE sp_cliente_actualizar(
    IN p_id INT,
    IN p_nombre VARCHAR(200),
    IN p_telefono VARCHAR(10),
    IN p_direccion TEXT
)
BEGIN
    UPDATE Cliente 
    SET nombre = p_nombre, telefono = p_telefono, direccion = p_direccion
    WHERE id_cliente = p_id;
END //
DELIMITER ;

-- 3. Eliminado Logico Cliente
DELIMITER //
CREATE PROCEDURE sp_cliente_desactivar(IN p_id INT)
BEGIN
    UPDATE Cliente SET estado = 'Inactivo' WHERE id_cliente = p_id;
END //
DELIMITER ;
-- 4 . Listar Cliente activos
DELIMITER //
CREATE PROCEDURE sp_cliente_listar_activos()
BEGIN
    SELECT * FROM Cliente WHERE estado = 'Activo';
END //
DELIMITER ;