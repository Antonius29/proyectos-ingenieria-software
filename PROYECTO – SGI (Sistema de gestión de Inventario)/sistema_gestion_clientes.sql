-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sistema_gestion_clientes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_gestion_clientes;

-- TABLA: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    rol ENUM('administrador', 'empleado') DEFAULT 'empleado',
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- TABLA: clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    empresa VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    direccion TEXT,
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- TABLA: interacciones_cliente
CREATE TABLE IF NOT EXISTS interacciones_cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo ENUM('llamada', 'email', 'reunion', 'visita', 'videollamada', 'mensaje') NOT NULL,
    descripcion TEXT NOT NULL,
    fecha DATE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- TABLA: proveedores
CREATE TABLE IF NOT EXISTS proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    empresa VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    direccion TEXT,
    notas TEXT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- TABLA: suministros_proveedor
CREATE TABLE IF NOT EXISTS suministros_proveedor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proveedor_id INT NOT NULL,
    nombre_producto VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),
    precio DECIMAL(10, 2) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- TABLA: categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- TABLA: productos (Inventario)
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    categoria_id INT,
    cantidad_stock INT DEFAULT 0,
    stock_minimo INT DEFAULT 5,
    precio DECIMAL(10, 2) NOT NULL,
    proveedor_id INT,
    estado ENUM('disponible', 'agotado', 'descontinuado') DEFAULT 'disponible',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- TABLA: movimientos_inventario
CREATE TABLE IF NOT EXISTS movimientos_inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo ENUM('entrada', 'salida', 'ajuste') NOT NULL,
    cantidad INT NOT NULL,
    motivo TEXT,
    fecha DATE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- TABLA: actividades_usuario
CREATE TABLE IF NOT EXISTS actividades_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_actividad VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha DATE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- TABLA: pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_pedido VARCHAR(50) NOT NULL UNIQUE,
    cliente_id INT NOT NULL,
    usuario_id INT,
    fecha_pedido DATE NOT NULL,
    estado ENUM('pendiente', 'proceso', 'completado', 'cancelado') DEFAULT 'pendiente',
    metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
    direccion_envio TEXT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    notas TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- TABLA: items_pedido
CREATE TABLE IF NOT EXISTS items_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    notas TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- DATOS DE EJEMPLO - 5 FILAS POR MODULO

-- ========== USUARIOS (5 filas) ==========
INSERT INTO usuarios (nombre, email, password, telefono, rol, estado) VALUES
('Admin Principal', 'admin@sistema.com', 'admin123', '+593 99 111 2222', 'administrador', 'activo'),
('Maria Lopez', 'maria.lopez@sistema.com', 'maria123', '+593 98 222 3333', 'empleado', 'activo'),
('Carlos Gomez', 'carlos.gomez@sistema.com', 'carlos123', '+593 97 333 4444', 'empleado', 'activo'),
('Ana Fernandez', 'ana.fernandez@sistema.com', 'ana123', '+593 96 444 5555', 'administrador', 'activo'),
('Juan Martinez', 'juan.martinez@sistema.com', 'juan123', '+593 95 555 6666', 'empleado', 'activo');

-- ========== CLIENTES (5 filas) ==========
INSERT INTO clientes (nombre, empresa, email, telefono, direccion, notas) VALUES
('Carlos Mendoza', 'TechSolutions S.A.', 'carlos.mendoza@techsolutions.com', '+593 99 123 4567', 'Av. Principal 123, Guayaquil', 'Cliente prioritario. Interesado en soluciones empresariales.'),
('Maria Rodriguez', 'InnovaGroup', 'maria.rodriguez@innovagroup.com', '+593 98 234 5678', 'Calle 10 de Agosto, Quito', 'Cliente frecuente con compras mensuales'),
('Juan Perez', 'Comercial del Pacifico', 'juan.perez@pacifico.com', '+593 97 345 6789', 'Malecon 2000, Guayaquil', 'Distribuidor mayorista regional'),
('Ana Gomez', 'Servicios Globales', 'ana.gomez@globales.com', '+593 96 456 7890', 'Av. Amazonas, Quito', 'Empresa de outsourcing y consultoría'),
('Roberto Silva', 'Distribuidora Nacional', 'roberto.silva@nacional.com', '+593 95 567 8901', 'Centro Comercial Mall del Sol', 'Cadena de tiendas con 8 sucursales');

-- ========== PROVEEDORES (5 filas) ==========
INSERT INTO proveedores (nombre, empresa, email, telefono, direccion, notas, estado) VALUES
('Distribuidora Tech', 'TechSupply S.A.', 'contacto@techsupply.com', '+593 99 111 0001', 'Zona Industrial Norte, Guayaquil', 'Proveedor principal de electrónica y equipos', 'activo'),
('Importadora Global', 'Global Import Cia. Ltda.', 'ventas@globalimport.com', '+593 98 222 0002', 'Puerto Marítimo, Guayaquil', 'Importador directo desde Asia', 'activo'),
('Accesorios Plus', 'AccPlus S.A.', 'info@accplus.com', '+593 97 333 0003', 'Centro Empresarial, Quito', 'Especializado en accesorios informáticos', 'activo'),
('Muebles Office', 'Furniture Solutions S.A.', 'contacto@muebles.com', '+593 96 444 0004', 'Zona Industrial Sur, Guayaquil', 'Proveedor de mobiliario y equipamiento de oficina', 'activo'),
('Papeleria Profesional', 'PaperPro Ltda.', 'pedidos@paperpro.com', '+593 95 555 0005', 'Av. Pichincha 456, Ambato', 'Distribuidor de papeleria y útiles de oficina', 'activo');

-- ========== CATEGORIAS (4 filas) ==========
INSERT INTO categorias (nombre, descripcion) VALUES
('Electrónica', 'Dispositivos electrónicos y computadoras'),
('Accesorios', 'Accesorios para computadoras y periféricos'),
('Muebles', 'Mobiliario de oficina y hogar'),
('Papelería', 'Artículos de oficina y papelería');

-- ========== PRODUCTOS (5 filas) ==========
INSERT INTO productos (nombre, descripcion, categoria_id, cantidad_stock, stock_minimo, precio, proveedor_id, estado) VALUES
('Laptop Dell Inspiron 15', 'Laptop 15.6 pulgadas, Intel Core i5, 8GB RAM, SSD 256GB', 1, 45, 5, 500.00, 1, 'disponible'),
('Monitor Samsung 24" Full HD', 'Monitor LED 24 pulgadas resolución Full HD (1920x1080)', 1, 30, 5, 180.00, 1, 'disponible'),
('Mouse Inalámbrico Logitech', 'Mouse ergonómico inalámbrico con sensor óptico', 2, 120, 10, 25.00, 3, 'disponible'),
('Escritorio Ejecutivo Nogal', 'Escritorio de madera maciza con cajones y compartimentos', 3, 15, 3, 350.00, 4, 'disponible'),
('Papel A4 80gr (Resma 500 hojas)', 'Resma de papel A4 80 gramos, blanco neutro', 4, 150, 20, 5.50, 5, 'disponible');

-- ========== PROVEEDORES SUMINISTROS (5 filas) ==========
INSERT INTO suministros_proveedor (proveedor_id, nombre_producto, categoria, precio) VALUES
(1, 'Procesador Intel Core i5', 'Componentes', 200.00),
(1, 'Memoria RAM 8GB DDR4', 'Componentes', 60.00),
(2, 'Pantalla LED 15.6"', 'Pantallas', 120.00),
(3, 'Cable HDMI 2 metros', 'Cables', 8.00),
(4, 'Panel frontal escritorio', 'Accesorios', 45.00);

-- ========== MOVIMIENTOS INVENTARIO (5 filas) ==========
INSERT INTO movimientos_inventario (producto_id, usuario_id, tipo, cantidad, motivo, fecha) VALUES
(1, 2, 'entrada', 20, 'Compra inicial a TechSupply', '2025-01-15'),
(2, 3, 'salida', 5, 'Venta a cliente TechSolutions', '2025-01-18'),
(3, 2, 'entrada', 50, 'Reorden a AccPlus', '2025-01-16'),
(4, 4, 'ajuste', -2, 'Ajuste por daño en transporte', '2025-01-20'),
(5, 3, 'entrada', 100, 'Compra masiva a PaperPro', '2025-01-21');

-- ========== ACTIVIDADES USUARIO (5 filas) ==========
INSERT INTO actividades_usuario (usuario_id, tipo_actividad, descripcion, fecha) VALUES
(1, 'login', 'Acceso al sistema administrativo', '2025-01-22'),
(2, 'crear_pedido', 'Creación de pedido PED0001', '2025-01-20'),
(3, 'crear_cliente', 'Registro de nuevo cliente - Roberto Silva', '2025-01-21'),
(4, 'editar_producto', 'Actualización de stock - Monitor Samsung 24"', '2025-01-19'),
(2, 'crear_proveedor', 'Registro de nuevo proveedor - PaperPro', '2025-01-18');

-- ========== INTERACCIONES CLIENTE (5 filas) ==========
INSERT INTO interacciones_cliente (cliente_id, usuario_id, tipo, descripcion, fecha) VALUES
(1, 2, 'llamada', 'Seguimiento de propuesta comercial y proyectos futuros', '2025-01-22'),
(2, 3, 'email', 'Envío de cotización para compra de 10 laptops', '2025-01-21'),
(3, 2, 'reunión', 'Primera reunión exploratoria de relación comercial', '2025-01-20'),
(4, 4, 'videollamada', 'Presentación de nuevos productos y servicios', '2025-01-19'),
(5, 3, 'mensaje', 'Consulta sobre disponibilidad de productos en stock', '2025-01-22');

-- ========== PEDIDOS (5 filas) ==========
INSERT INTO pedidos (numero_pedido, cliente_id, usuario_id, fecha_pedido, estado, metodo_pago, direccion_envio, total, notas) VALUES
('PED0001', 1, 2, '2025-01-10', 'completado', 'transferencia', 'Av. Principal 123, Guayaquil', 1000.00, 'Pedido completado y entregado exitosamente'),
('PED0002', 2, 3, '2025-01-12', 'proceso', 'tarjeta', 'Calle 10 de Agosto, Quito', 625.00, 'En proceso de empaque y preparación'),
('PED0003', 3, 2, '2025-01-15', 'completado', 'efectivo', 'Malecon 2000, Guayaquil', 700.00, 'Entregado al almacén del cliente'),
('PED0004', 4, 4, '2025-01-16', 'pendiente', 'transferencia', 'Av. Amazonas, Quito', 473.00, 'Pendiente confirmación de pago del cliente'),
('PED0005', 5, 1, '2025-01-18', 'completado', 'tarjeta', 'Centro Comercial Mall del Sol', 2050.00, 'Envío a múltiples sucursales - realizado correctamente');

-- ========== ITEMS PEDIDOS (5 filas - uno por pedido) ==========
INSERT INTO items_pedido (pedido_id, producto_id, cantidad, precio_unitario, subtotal, notas) VALUES
(1, 1, 2, 500.00, 1000.00, 'Laptops para área administrativa'),
(2, 3, 25, 25.00, 625.00, 'Mouses para dotación de oficina'),
(3, 4, 2, 350.00, 700.00, 'Escritorios ejecutivos para directiva'),
(4, 5, 86, 5.50, 473.00, 'Papel para impresora multifunción'),
(5, 2, 10, 180.00, 1800.00, 'Monitores para renovación de equipos'),
(5, 1, 1, 250.00, 250.00, 'Laptop adicional para gerencia');
