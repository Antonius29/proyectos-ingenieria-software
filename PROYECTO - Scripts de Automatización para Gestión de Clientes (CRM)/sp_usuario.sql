USE bd_clientes;
# Procedimientos para Tabla: Usuario 
-- 1. Insertar Usuario
DELIMITER //
CREATE PROCEDURE sp_usuario_insertar(
    IN p_nombre VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_contra VARCHAR(255),
    IN p_rol_id INT
)
BEGIN
    INSERT INTO Usuario (nombre, email, contra, rol_id) 
    VALUES (p_nombre, p_email, p_contra, p_rol_id);
END //
DELIMITER ;

-- 2. Listar Usuarios con su Rol 
DELIMITER //
CREATE PROCEDURE sp_usuario_listar_completo()
BEGIN
    SELECT U.id_usuario, U.nombre, U.email, R.nombre AS rol, U.estado 
    FROM Usuario U 
    INNER JOIN Rol R ON U.rol_id = R.id_rol;
END //
DELIMITER ;

-- 3. Bloquear Usuario 
DELIMITER //
CREATE PROCEDURE sp_usuario_bloquear(IN p_id INT)
BEGIN
    UPDATE Usuario 
    SET bloqueado = TRUE, fecha_bloqueo = CURRENT_TIMESTAMP 
    WHERE id_usuario = p_id;
END //
DELIMITER ;
-- 4. desbloquear Usuario 
DELIMITER //
CREATE PROCEDURE sp_usuario_desbloquear(IN p_id INT)
BEGIN
    UPDATE Usuario 
    SET bloqueado = FALSE, 
        intentos_fallidos = 0, 
        fecha_bloqueo = NULL 
    WHERE id_usuario = p_id;
END //
DELIMITER ;

# 5. Eliminado Logico de Usuario
DELIMITER //
CREATE PROCEDURE sp_usuario_desactivar(IN p_id INT)
BEGIN
    UPDATE Usuario 
    SET estado = 'Inactivo' 
    WHERE id_usuario = p_id;
END //
DELIMITER ;

# 4. Actualizar Datos de Usuario
DELIMITER //
CREATE PROCEDURE sp_usuario_actualizar(
    IN p_id INT,
    IN p_nombre VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_rol_id INT
)
BEGIN
    UPDATE Usuario 
    SET nombre = p_nombre, 
        email = p_email, 
        rol_id = p_rol_id 
    WHERE id_usuario = p_id;
END //
DELIMITER ;


-- 1. PROBAR INSERCION DE UN NUEVO USUARIO
CALL sp_usuario_insertar('Marcos Paz', 'mpaz@empresa.com', 'clave_segura_123', 3);

-- 2. PROBAR LISTADO COMPLETO DE USUARIOS
CALL sp_usuario_listar_completo();

-- 3. PROBAR ACTUALIZACION DE DATOS
CALL sp_usuario_actualizar(8, 'Marcos Paz Editado', 'marcos.paz@empresa.com', 2);

-- 4. PROBAR BLOQUEO DE SEGURIDAD
CALL sp_usuario_bloquear(8);

-- 5. PROBAR DESBLOQUEO DE USUARIO
CALL sp_usuario_desbloquear(8);


-- 6. PROBAR ELIMINADO LOGICO (DESACTIVAR)
CALL sp_usuario_desactivar(8);
