USE bd_clientes;
-- 1 INNER JOIN Ventas con sus Productos
# ¿Que productos especificos se estan negociando en cada oportunidad y a que precio?
SELECT 
    O.id_oportunidad, 
    C.nombre AS cliente, 
    P.nombre AS producto, 
    OP.cantidad, 
    OP.precio_unitario_historico AS precio_venta
FROM Oportunidad O
INNER JOIN Cliente C ON O.cliente_id = C.id_cliente
INNER JOIN OportunidadProducto OP ON O.id_oportunidad = OP.oportunidad_id
INNER JOIN Producto P ON OP.producto_id = P.id_producto;
#Este JOIN solo muestra filas donde hay una coincidencia exacta. 
#Si una oportunidad no tiene productos cargados, no aparecera. 

-- 2. LEFT JOIN Clientes y sus Contactos
# me da una lista de TODOS los clientes y sus contactos, 
#incluyendo aquellos clientes que aun no tienen ningun contacto registrado.
SELECT 
    C.nombre AS cliente, 
    CO.nombre AS contacto, 
    CO.cargo
FROM Cliente C
LEFT JOIN Contacto CO ON C.id_cliente = CO.cliente_id;


#3. RIGHT JOIN Usuarios y Actividades
# muestra todas las actividades realizadas, asegurandonos de que aparezcan 
# incluso si el usuario que las registro fue borrado o hay algun error de asignacion 
SELECT 
    U.nombre AS vendedor, 
    A.descripcion AS accion, 
    A.fecha_hora
FROM Usuario U
RIGHT JOIN Actividad A ON U.id_usuario = A.usuario_id;


-- 4 Consultas para KPIs 
# Total de ventas ganadas por vendedor
SELECT 
    U.nombre AS vendedor, 
    SUM(O.monto) AS total_vendido
FROM Usuario U
INNER JOIN Actividad A ON U.id_usuario = A.usuario_id
INNER JOIN Oportunidad O ON A.oportunidad_id = O.id_oportunidad
INNER JOIN EstadoOportunidad E ON O.estado_oportunidad_id = E.id_estado_opt
WHERE E.nombre = 'Ganado'
GROUP BY U.nombre;

# 5 El Producto más vendido por cantidad
SELECT 
    P.nombre AS producto, 
    SUM(OP.cantidad) AS unidades_vendidas, 
    SUM(OP.cantidad * OP.precio_unitario_historico) AS total_ingresos
FROM Producto P
INNER JOIN OportunidadProducto OP ON P.id_producto = OP.producto_id
INNER JOIN Oportunidad O ON OP.oportunidad_id = O.id_oportunidad
INNER JOIN EstadoOportunidad E ON O.estado_oportunidad_id = E.id_estado_opt
WHERE E.nombre = 'Ganado' -- Solo contamos lo que ya se vendio de verdad
GROUP BY P.id_producto, P.nombre
ORDER BY unidades_vendidas DESC
LIMIT 1; -- Trae solo el primero de la lista


-- 6 ¿Que clientes tengo registrados pero nunca me han comprado nada?
SELECT 
    C.nombre AS cliente, 
    O.id_oportunidad
FROM Cliente C
LEFT JOIN Oportunidad O ON C.id_cliente = O.cliente_id
WHERE O.id_oportunidad IS NULL;
# Esto sirve para el equipo de ventas 
# para llamar a esos clientes y tratar de concretar un primer negocio.

# 7 Bitácora de Seguimiento por Cliente
SELECT 
    C.nombre AS cliente,
    A.fecha_hora AS fecha,
    TA.nombre AS tipo_accion,
    U.nombre AS responsable,
    A.descripcion AS detalle_de_la_actividad
FROM Cliente C
INNER JOIN Oportunidad O ON C.id_cliente = O.cliente_id
INNER JOIN Actividad A ON O.id_oportunidad = A.oportunidad_id
INNER JOIN TipoActividad TA ON A.tipo_actividad_id = TA.id_tactividad
INNER JOIN Usuario U ON A.usuario_id = U.id_usuario
WHERE C.nombre = 'Banco Pichincha'
ORDER BY A.fecha_hora DESC;

# Permite auditar si se le está dando el seguimiento correcto 
# al cliente o si ha pasado mucho tiempo desde el último contacto.


# 8 ¿Cuánto dinero hay "en el aire"?
SELECT 
    E.nombre AS etapa, 
    COUNT(O.id_oportunidad) AS cantidad_tratos,
    SUM(O.monto) AS dinero_proyectado
FROM Oportunidad O
INNER JOIN EstadoOportunidad E ON O.estado_oportunidad_id = E.id_estado_opt
WHERE E.nombre NOT IN ('Ganada', 'Perdida')
GROUP BY E.nombre;

# Esto le dice al dueño de la empresa cuánto dinero 
# podría ingresar en los próximos meses si el equipo de ventas hace bien su trabajo.