USE bd_clientes;
DELIMITER //

-- TG11: Protección de Oportunidades Ganadas (No permite modificar montos si ya se cerró)
CREATE TRIGGER tg_bloquear_edicion_ganada
BEFORE UPDATE ON Oportunidad
FOR EACH ROW
BEGIN
    -- Si el estado antiguo era 'Ganada' (asumiendo ID 5), no permite cambios en el monto
    IF OLD.estado_oportunidad_id = 5 AND NEW.monto <> OLD.monto THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error: No se puede modificar el monto de un negocio ya cerrado y ganado';
    END IF;
END //

-- TG12: Validación de Stock Teórico (Evita vender productos inactivos en el detalle)
CREATE TRIGGER tg_validar_producto_disponible
BEFORE INSERT ON OportunidadProducto
FOR EACH ROW
BEGIN
    DECLARE v_estado VARCHAR(20);
    -- Buscamos el estado del producto en la tabla Producto
    SELECT estado INTO v_estado FROM Producto WHERE id_producto = NEW.producto_id;
    
    IF v_estado = 'Inactivo' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error: El producto seleccionado no está disponible para la venta';
    END IF;
END //

-- TG13: Auto-asignación de Seguimiento (Crea una actividad automática al crear oportunidad)
CREATE TRIGGER tg_crear_seguimiento_auto
AFTER INSERT ON Oportunidad
FOR EACH ROW
BEGIN
    -- Inserta automáticamente un recordatorio de llamada inicial para el dueño del registro
    INSERT INTO Actividad (oportunidad_id, usuario_id, tipo_actividad_id, fecha_hora, descripcion)
    VALUES (NEW.id_oportunidad, 1, 1, NOW(), 'Llamada de cortesía inicial programada automáticamente');
END //

DELIMITER ;