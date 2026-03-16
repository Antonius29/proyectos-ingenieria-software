USE bd_clientes;
-- 1. Procedimientos para Tabla: Producto
-- Insertar Producto
DELIMITER //
CREATE PROCEDURE sp_producto_insertar(
    IN p_nombre VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(15,2)
)
BEGIN
    INSERT INTO Producto (nombre, descripcion, precio) 
    VALUES (p_nombre, p_descripcion, p_precio);
END //
DELIMITER ;

-- 2. Listar Productos Activos
DELIMITER //
CREATE PROCEDURE sp_producto_listar_activos()
BEGIN
    SELECT * FROM Producto WHERE estado = 'Activo';
END //
DELIMITER ;

-- 3. Actualizar Producto
DELIMITER //
CREATE PROCEDURE sp_producto_actualizar(
    IN p_id INT,
    IN p_nombre VARCHAR(200),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(15,2)
)
BEGIN
    UPDATE Producto 
    SET nombre = p_nombre, descripcion = p_descripcion, precio = p_precio
    WHERE id_producto = p_id;
END //
DELIMITER ;

-- 4. Eliminado Logico (Desactivar)
DELIMITER //
CREATE PROCEDURE sp_producto_desactivar(IN p_id INT)
BEGIN
    UPDATE Producto SET estado = 'Inactivo' WHERE id_producto = p_id;
END //
DELIMITER ;


