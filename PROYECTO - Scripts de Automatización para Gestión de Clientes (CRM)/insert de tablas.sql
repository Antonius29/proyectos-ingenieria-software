USE bd_clientes;
-- 1. ROLES
INSERT INTO Rol (nombre) VALUES 
('Admin'), ('Gerente Ventas'), ('Vendedor Senior'), ('Vendedor Junior'), ('Soporte');

-- 2. USUARIOS (Personal de la empresa)
INSERT INTO Usuario (nombre, email, contra, rol_id) VALUES 
('Carlos Andrade', 'candrade@proyect.com', 'hash123', 1),
('Laura Meza', 'lmeza@proyect.com', 'hash456', 2),
('Pedro Gomez', 'pgomez@proyect.com', 'hash789', 3),
('Ana Silvia', 'asilva@proyect.com', 'hash012', 3),
('Roberto Vera', 'rvera@proyect.com', 'hash345', 4),
('Elena Ponce', 'eponce@proyect.com', 'hash678', 4),
('Miguel Luna', 'mluna@proyect.com', 'hash901', 5);

-- 3. TIPOS DE CLIENTE
INSERT INTO TipoCliente (nombre) VALUES 
('Corporativo'), ('Gobierno'), ('PYME'), ('Persona Natural'), ('Distribuidor');

-- 4. CLIENTES (Datos reales con 10 digitos exactos)
INSERT INTO Cliente (cedula, nombre, tipo_cliente_id, telefono, direccion) VALUES 
('1712345678', 'Banco Pichincha', 1, '0225554440', 'Amazonas y Colon'),
('1798765432', 'Ministerio de Salud', 2, '0239998880', 'Av. Republica'),
('0911223344', 'Tiendas Industriales', 1, '0421112220', 'Guayaquil Centro'),
('1722334455', 'Soluciones Tech S.A.', 3, '0223334440', 'Cumbaya'),
('1010101010', 'Juan Fernando Lopez', 4, '0995556660', 'Valle de los chillos'),
('1744556677', 'Constructora Delta', 1, '0224445550', 'Av. Occidental'),
('0955443322', 'Municipio de Guayaquil', 2, '0429990000', 'Malecon 2000'),
('1788990011', 'Farmacias del Ahorro', 3, '0228887770', 'Sector La Mariscal'),
('1313131313', 'Exportadora Banano', 1, '0526667770', 'Manta Puerto'),
('1700112233', 'Universidad Catolica', 2, '0229911000', 'Av. 12 de Octubre'),
('1818181818', 'Textiles Ambato', 3, '0324445550', 'Parque Industrial'),
('1755667788', 'Seguros Equinoccial', 1, '0221110000', 'Eloy Alfaro');

-- 5. CONTACTOS (Relacionados a los clientes anteriores)
INSERT INTO Contacto (cliente_id, nombre, cargo, email, telefono) VALUES 
(1, 'Ing. Luis Sosa', 'Jefe Sistemas', 'lsosa@pichincha.com', '0998887771'),
(1, 'Ma. Jose Riva', 'Compras', 'mriva@pichincha.com', '0998887772'),
(2, 'Dr. Jorge Mera', 'Director', 'jmera@salud.gob', '0991112223'),
(3, 'Lic. Ana Vaca', 'Recursos Humanos', 'avaca@tisu.com', '0994445556'),
(4, 'Ing. Raul Gil', 'Gerente General', 'rgil@stech.com', '0987776665'),
(6, 'Arq. Ivan Paz', 'Residente', 'ipaz@delta.com', '0990001112'),
(7, 'Ab. Carla Ruiz', 'Secretaria', 'cruiz@guayaquil.gob', '0955554443'),
(8, 'Dra. Betty Sol', 'Propietaria', 'bsol@farmas.com', '0944443332'),
(9, 'Sr. Mario Luz', 'Logistica', 'mluz@banano.com', '0933332221'),
(10, 'Dr. Alex Paez', 'Decano', 'apaez@puce.edu', '0922221110'),
(11, 'Ing. Hugo Rey', 'Produccion', 'hrey@ambato.com', '0911110009'),
(12, 'Lcda. Rosa Gil', 'Siniestros', 'rgil@equi.com', '0900009998');

-- 6. PRODUCTOS
INSERT INTO Producto (nombre, descripcion, precio) VALUES 
('Laptop Dell Vostro', 'i5, 8GB RAM, 256GB SSD', 850.00),
('Licencia Office 365', 'Suscripcion anual Business', 120.00),
('Servidor HP ProLiant', 'Xeon 16GB RAM', 3200.00),
('Antivirus Kaspersky', 'Licencia 1 año 10 usuarios', 450.00),
('Switch Cisco 24p', 'Administrable Capa 2', 980.00),
('Mantenimiento PC', 'Limpieza y optimizacion', 45.00),
('Disco Solido 1TB', 'Marca Samsung EVO', 110.00),
('Router WiFi 6', 'Alta velocidad corporativo', 180.00),
('Monitor LG 27"', '4K IPS Profesional', 350.00),
('Soporte Remoto', 'Bono de 10 horas mensuales', 250.00),
('Teclado Mecanico', 'Ergonomico para oficina', 65.00),
('Cable Red Cat6', 'Rollo de 305 metros', 140.00);

-- 7. ESTADOS Y TIPOS DE ACTIVIDAD
INSERT INTO EstadoOportunidad (nombre) VALUES ('Nuevo'), ('Calificando'), ('Propuesta'), ('Negociacion'), ('Ganado'), ('Perdido');
INSERT INTO TipoActividad (nombre) VALUES ('Llamada'), ('Correo'), ('Visita'), ('Demo'), ('Reunion');

-- 8. OPORTUNIDADES (Ventas en proceso)
INSERT INTO Oportunidad (cliente_id, estado_oportunidad_id, monto, descripcion) VALUES 
(1, 3, 5000.00, 'Renovacion laptops departamento legal'),
(2, 1, 12000.00, 'Equipamiento centro de salud norte'),
(3, 5, 950.00, 'Venta de switch para bodega'),
(4, 2, 450.00, 'Renovacion de antivirus corporativo'),
(6, 4, 3200.00, 'Servidor para base de datos obra'),
(8, 6, 1500.00, 'Puntos de red locales nuevos'),
(10, 3, 2500.00, 'Soporte tecnico anual facultades'),
(12, 5, 850.00, 'Compra laptop para gerencia');

-- 9. PRODUCTOS POR OPORTUNIDAD
INSERT INTO OportunidadProducto (oportunidad_id, producto_id, cantidad, precio_unitario_historico) VALUES 
(1, 1, 5, 850.00), (1, 2, 5, 120.00),
(3, 5, 1, 950.00),
(5, 3, 1, 3200.00),
(8, 1, 1, 850.00);

-- 10. ACTIVIDADES (Bitacora)
INSERT INTO Actividad (usuario_id, oportunidad_id, tipo_actividad_id, descripcion) VALUES 
(3, 1, 2, 'Envio de cotizacion formal por correo'),
(4, 3, 1, 'Llamada para confirmar recepcion de equipo'),
(5, 5, 3, 'Visita en obra para dimensionar el servidor'),
(3, 1, 5, 'Reunion de ajuste de precios final');