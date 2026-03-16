-- Triggers 10 de automatización y validación
-- TG1: Bloqueo de usuario por intentos
USE bd_clientes;

-- TG1: Bloqueo de usuario por intentos
DELIMITER //
CREATE TRIGGER tg_auto_bloqueo BEFORE UPDATE ON Usuario FOR EACH ROW
BEGIN 
    IF NEW.intentos_fallidos >= 3 THEN SET NEW.bloqueado = TRUE; END IF; 
END //
DELIMITER ;

-- TG2: Mayúsculas automáticas en Clientes
DELIMITER //
CREATE TRIGGER tg_cliente_upper BEFORE INSERT ON Cliente FOR EACH ROW
BEGIN 
    SET NEW.nombre = UPPER(NEW.nombre); 
END //
DELIMITER ;

-- TG3: Evitar precios en cero
DELIMITER //
CREATE TRIGGER tg_no_precio_cero BEFORE INSERT ON Producto FOR EACH ROW
BEGIN 
    IF NEW.precio <= 0 THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Precio inválido'; END IF; 
END //
DELIMITER ;

-- TG4: Fecha de bloqueo automática
DELIMITER //
CREATE TRIGGER tg_fecha_bloqueo BEFORE UPDATE ON Usuario FOR EACH ROW
BEGIN 
    IF NEW.bloqueado = TRUE AND OLD.bloqueado = FALSE THEN SET NEW.fecha_bloqueo = NOW(); END IF; 
END //
DELIMITER ;

-- TG5: Validación de cantidad mínima en ventas
DELIMITER //
CREATE TRIGGER tg_cant_minima BEFORE INSERT ON OportunidadProducto FOR EACH ROW
BEGIN 
    IF NEW.cantidad < 1 THEN SET NEW.cantidad = 1; END IF; 
END //
DELIMITER ;

-- TG6: Reset de intentos al cambiar clave
DELIMITER //
CREATE TRIGGER tg_reset_pass BEFORE UPDATE ON Usuario FOR EACH ROW
BEGIN 
    IF NEW.contra <> OLD.contra THEN SET NEW.intentos_fallidos = 0; SET NEW.bloqueado = FALSE; END IF; 
END //
DELIMITER ;

-- TG7: No permitir cédulas de menos de 10 dígitos
DELIMITER //
CREATE TRIGGER tg_validar_cedula_len BEFORE INSERT ON Cliente FOR EACH ROW
BEGIN 
    IF LENGTH(NEW.cedula) < 10 THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cédula incompleta'; END IF; 
END //
DELIMITER ;

-- TG8: Correo en minúsculas automático
DELIMITER //
CREATE TRIGGER tg_email_lower BEFORE INSERT ON Usuario FOR EACH ROW
BEGIN 
    SET NEW.email = LOWER(NEW.email); 
END //
DELIMITER ;

-- TG9: Impedir montos negativos en Oportunidad
DELIMITER //
CREATE TRIGGER tg_monto_negativo BEFORE UPDATE ON Oportunidad FOR EACH ROW
BEGIN 
    IF NEW.monto < 0 THEN SET NEW.monto = OLD.monto; END IF; 
END //
DELIMITER ;

-- TG10: Aviso de actualización de contacto
DELIMITER //
CREATE TRIGGER tg_contacto_update BEFORE UPDATE ON Contacto FOR EACH ROW
BEGIN 
    SET NEW.cargo = CONCAT(NEW.cargo, ' (Actualizado)'); 
END //
DELIMITER ;