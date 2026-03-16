USE bd_clientes;
# 1. Vista de Clientes Activos
CREATE VIEW vista_clientes_activos AS
SELECT id_cliente, cedula, nombre, telefono, direccion 
FROM Cliente 
WHERE estado = 'Activo';

SELECT * FROM vista_clientes_activos;

# 2 Vista de Directorio de Contactos
CREATE VIEW vista_directorio_contactos AS
SELECT C.nombre AS empresa, CO.nombre AS contacto, CO.cargo, CO.email, CO.telefono
FROM Cliente C
INNER JOIN Contacto CO ON C.id_cliente = CO.cliente_id
WHERE CO.estado = 'Activo';

SELECT * FROM vista_directorio_contactos;

# 3 Vista de Resumen de Oportunidades
CREATE VIEW vista_resumen_oportunidades AS
SELECT O.id_oportunidad, C.nombre AS cliente, E.nombre AS etapa, O.monto, O.fecha_hora
FROM Oportunidad O
INNER JOIN Cliente C ON O.cliente_id = C.id_cliente
INNER JOIN EstadoOportunidad E ON O.estado_oportunidad_id = E.id_estado_opt;

SELECT * FROM vista_resumen_oportunidades;

# 4. Vista de Cartera por Vendedor
# ¿Qué negocios tiene asignados cada vendedor?
CREATE VIEW vista_cartera_vendedor AS
SELECT U.nombre AS vendedor, COUNT(O.id_oportunidad) AS total_tratos, SUM(O.monto) AS monto_total
FROM Usuario U
INNER JOIN Actividad A ON U.id_usuario = A.usuario_id
INNER JOIN Oportunidad O ON A.oportunidad_id = O.id_oportunidad
GROUP BY U.nombre;

SELECT * FROM vista_cartera_vendedor;

# 5. Vista de Productos por Oportunidad
# El detalle de qué se está vendiendo en cada trato.
CREATE VIEW vista_detalle_productos_venta AS
SELECT OP.oportunidad_id, P.nombre AS producto, OP.cantidad, OP.precio_unitario_historico
FROM OportunidadProducto OP
INNER JOIN Producto P ON OP.producto_id = P.id_producto;

SELECT * FROM vista_detalle_productos_venta;

# 6. Vista de Ventas Ganadas (KPI Real)
# Solo lo que ya se cerró y es dinero real para la empresa.
CREATE VIEW vista_ventas_ganadas AS
SELECT * FROM vista_resumen_oportunidades 
WHERE etapa = 'Ganado';

SELECT * FROM vista_ventas_ganadas;

# 7. Vista de Proximos Seguimientos (Ultimas Actividades)
# Para saber qué fue lo último que se hizo en el sistema.
CREATE VIEW vista_ultima_actividad AS
SELECT A.id_actividad, O.descripcion AS negocio, TA.nombre AS accion, A.fecha_hora, U.nombre AS usuario
FROM Actividad A
INNER JOIN Oportunidad O ON A.oportunidad_id = O.id_oportunidad
INNER JOIN TipoActividad TA ON A.tipo_actividad_id = TA.id_tactividad
INNER JOIN Usuario U ON A.usuario_id = U.id_usuario;

SELECT * FROM vista_ultima_actividad;

# 8. Vista de Inventario de Productos
# Lista de precios actual de lo que ofreces.
CREATE VIEW vista_catalogo_productos AS
SELECT nombre, descripcion, precio 
FROM Producto 
WHERE estado = 'Activo';

SELECT * FROM vista_catalogo_productos;

# 9. Vista de Clientes sin Tratos 
# Ideal para que el equipo de marketing sepa a quién llamar.
CREATE VIEW vista_clientes_sin_ventas AS
SELECT C.nombre, C.telefono 
FROM Cliente C
LEFT JOIN Oportunidad O ON C.id_cliente = O.cliente_id
WHERE O.id_oportunidad IS NULL;

SELECT * FROM vista_clientes_sin_ventas;

# 10. Vista de Usuarios Bloqueados
# Para que el administrador sepa a quién debe desbloquear.
CREATE VIEW vista_usuarios_bloqueados AS
SELECT nombre, email, fecha_bloqueo 
FROM Usuario 
WHERE bloqueado = TRUE;

SELECT * FROM vista_usuarios_bloqueados;

-- 11 El Reporte Maestro
CREATE VIEW vista_reporte_maestro AS
SELECT 
    vendedor, 
    total_tratos, 
    CONCAT('$ ', FORMAT(monto_total, 2)) AS monto_formateado
FROM vista_cartera_vendedor
ORDER BY monto_total DESC;

SELECT * FROM vista_reporte_maestro;

# 12 Reporte Detallado de una Oportunidad

-- Generar el "Detalle de Cotizacion" para la oportunidad 
CREATE VIEW vista_reporte_oportunidad AS SELECT 
    producto, 
    cantidad, 
    precio_unitario_historico,
    (cantidad * precio_unitario_historico) AS subtotal
FROM vista_detalle_productos_venta
WHERE oportunidad_id = 1;

SELECT * FROM vista_reporte_oportunidad;