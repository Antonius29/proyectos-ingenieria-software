USE bd_clientes;
-- 1. Transacciones y Savepoints  y triggers
-- TR1: Venta con 2 productos 
START TRANSACTION;
    INSERT INTO Oportunidad (cliente_id, estado_oportunidad_id, monto) VALUES (1, 3, 1500);
    SAVEPOINT sp_venta_cabecera;
    INSERT INTO OportunidadProducto (oportunidad_id, producto_id, cantidad, precio_unitario_historico) VALUES (LAST_INSERT_ID(), 1, 2, 500);
COMMIT;

-- TR2: Registro de Cliente con su primer Contacto
START TRANSACTION;
    INSERT INTO Cliente (cedula, nombre, tipo_cliente_id, telefono) VALUES ('1799887766', 'NUEVA CORP', 1, '0225554440');
    SAVEPOINT sp_cliente_creado;
    INSERT INTO Contacto (cliente_id, nombre, cargo, email, telefono) VALUES (LAST_INSERT_ID(), 'Juan Perez', 'Gerente', 'jp@nueva.com', '0999888777');
COMMIT;

-- TR3: Actualización de precios masiva por inflación
START TRANSACTION;
    SAVEPOINT sp_antes_ajuste;
    UPDATE Producto SET precio = precio * 1.05; -- Aumento del 5%
COMMIT;

-- TR4: Traspaso de cartera de un vendedor a otro
START TRANSACTION;
    SAVEPOINT sp_inicio_traspaso;
    UPDATE Actividad SET usuario_id = 2 WHERE usuario_id = 1;
COMMIT;

-- TR5: Cierre de negocio y desactivación de oportunidad
START TRANSACTION;
    UPDATE Oportunidad SET estado_oportunidad_id = 5 WHERE id_oportunidad = 1; -- 5 = Ganado
    SAVEPOINT sp_ganada;
    UPDATE Actividad SET descripcion = 'Cierre de contrato firmado' WHERE oportunidad_id = 1;
COMMIT;

-- TR6: Alta de Usuario con Rol
START TRANSACTION;
    INSERT INTO Usuario (nombre, email, contra, rol_id) VALUES ('Analista 1', 'a1@test.com', 'pass1', 3);
    SAVEPOINT sp_usuario_ok;
COMMIT;

-- TR7: Anulación de oportunidad por falta de pago
START TRANSACTION;
    UPDATE Oportunidad SET estado_oportunidad_id = 6 WHERE id_oportunidad = 2; -- 6 = Perdido
    SAVEPOINT sp_anulada;
COMMIT;

-- TR8: Actualización de dirección y teléfono de cliente
START TRANSACTION;
    UPDATE Cliente SET direccion = 'Nueva Sede Sur' WHERE id_cliente = 3;
    SAVEPOINT sp_dir_actualizada;
    UPDATE Cliente SET telefono = '0221112220' WHERE id_cliente = 3;
COMMIT;

-- TR9: Registro de Documento adjunto a Oportunidad
START TRANSACTION;
    INSERT INTO Documento (oportunidad_id, nombre, url) VALUES (1, 'Contrato_Final.pdf', 'http://files/1');
    SAVEPOINT sp_doc_registrado;
COMMIT;

-- TR10: Desactivación masiva de productos obsoletos
START TRANSACTION;
    UPDATE Producto SET estado = 'Inactivo' WHERE precio < 10;
    SAVEPOINT sp_limpieza_precios;
COMMIT;


# TR11: Proceso de Cotización con Registro de Contacto y Producto 
# Esta transacción intenta crear un contacto nuevo para un cliente 
# y asignarle un producto. Si el contacto falla, igual intenta guardar el producto.

START TRANSACTION;
-- Paso 1: Crear contacto incluyendo el teléfono para evitar el Error 1364
INSERT INTO Contacto (cliente_id, nombre, cargo, email, telefono) 
VALUES (1, 'Ing. Carlos Ruiz', 'Director Técnico', 'cruiz@ejemplo.com', '0991234567');

SAVEPOINT sp_contacto_nuevo;

-- Paso 2: Intentar agregar producto a la oportunidad
INSERT INTO OportunidadProducto (oportunidad_id, producto_id, cantidad, precio_unitario_historico)
VALUES (1, 1, 1, 1200.00);

-- Si falla el producto, podrías hacer: ROLLBACK TO sp_contacto_nuevo;
COMMIT;

# TR12: Reestructuración de Vendedor con Limpieza de Actividades
# Ideal para cuando un vendedor sale de la empresa y queremos reasignar sus tratos, pero manteniendo un punto de control.
START TRANSACTION;

-- Paso 1: Desactivar al usuario que sale
UPDATE Usuario SET estado = 'Inactivo' WHERE id_usuario = 3;
SAVEPOINT sp_usuario_out;

-- Paso 2: Marcar oportunidades para reasignación
UPDATE Oportunidad SET descripcion = CONCAT(descripcion, ' - REASIGNAR') 
WHERE id_oportunidad IN (SELECT oportunidad_id FROM Actividad WHERE usuario_id = 3);

COMMIT;