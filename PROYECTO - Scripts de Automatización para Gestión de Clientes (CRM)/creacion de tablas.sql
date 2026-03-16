-- 1. CONFIGURACION INICIAL
DROP DATABASE IF EXISTS bd_clientes;
CREATE DATABASE IF NOT EXISTS bd_clientes ;
USE bd_clientes;

-- 2. TABLAS DEL SISTEMA Y CONTROL DE ACCESO

-- niveles de permiso para los usuarios del sistema
CREATE TABLE Rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    estado ENUM ('Activo', 'Inactivo') DEFAULT 'Activo' 
) ENGINE=InnoDB;

-- datos de empleados, incluye control de seguridad y bloqueos por intentos
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contra VARCHAR(255) NOT NULL, -- guardara la clave encriptada (hash)
    rol_id INT NOT NULL,
    estado ENUM ('Activo', 'Inactivo') DEFAULT 'Activo' ,
    intentos_fallidos INT DEFAULT 0, -- cuenta errores de login para seguridad
    bloqueado BOOLEAN DEFAULT FALSE, -- impide el acceso si hay sospecha de ataque
    fecha_bloqueo TIMESTAMP NULL,
    CONSTRAINT fk_usuario_rol FOREIGN KEY (rol_id) REFERENCES Rol(id_rol) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3. TABLAS DE CLIENTES

-- categorias para agrupar clientes (ej: corporativo, minorista)
CREATE TABLE TipoCliente (
    id_tcliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    estado ENUM ('Activo', 'Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB;

-- informacion general de los clientes con validacion de cedula
CREATE TABLE Cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(10) NOT NULL UNIQUE,
    CONSTRAINT validar_cedula CHECK (cedula REGEXP '^[0-9]{10}$'), -- solo permite 10 digitos
    nombre VARCHAR(200) NOT NULL,
    tipo_cliente_id INT NOT NULL,
    telefono VARCHAR(10) NOT NULL,
    CONSTRAINT validar_telefono CHECK (telefono REGEXP '^[0-9]{10}$'),
    direccion TEXT,
    estado ENUM ('Activo', 'Inactivo') DEFAULT 'Activo' ,
    fecha_alta DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cliente_tipo FOREIGN KEY (tipo_cliente_id) REFERENCES TipoCliente(id_tcliente) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- personas que trabajan para el cliente, permite contacto directo
CREATE TABLE Contacto (
    id_contacto INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    cargo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    estado ENUM ('Activo', 'Inactivo') DEFAULT 'Activo',
    CONSTRAINT fk_contacto_cliente FOREIGN KEY (cliente_id) REFERENCES Cliente(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 4. TABLAS DE NEGOCIO Y PRODUCTOS

-- catalogo de productos con validacion de precio mayor a cero
CREATE TABLE Producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(15, 2) DEFAULT 0, -- decimal para evitar perdida de centavos
    CONSTRAINT validar_precio CHECK (precio > 0),
    estado ENUM ('Activo', 'Inactivo') DEFAULT 'Activo' 
) ENGINE=InnoDB;

-- lista de fases de una venta (ej: cotizacion, ganado, perdido)
CREATE TABLE EstadoOportunidad (
    id_estado_opt INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    estado ENUM ('Activo', 'Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB;

-- cabecera de la negociacion o venta con un cliente
CREATE TABLE Oportunidad (
    id_oportunidad INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    estado_oportunidad_id INT NOT NULL,
    monto DECIMAL(15, 2) DEFAULT 0,
    CONSTRAINT validar_monto CHECK (monto >= 0),
    descripcion TEXT,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_opt_cliente FOREIGN KEY (cliente_id) REFERENCES Cliente(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_opt_estado FOREIGN KEY (estado_oportunidad_id) REFERENCES EstadoOportunidad(id_estado_opt) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- detalle de que productos se venden, guarda el precio del momento (historico)
CREATE TABLE OportunidadProducto (
    id_opt_prod INT AUTO_INCREMENT PRIMARY KEY,
    oportunidad_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    CONSTRAINT validar_cantidad CHECK (cantidad > 0),
    precio_unitario_historico DECIMAL(15, 2) NOT NULL, -- protege la venta de cambios de precio futuros
    CONSTRAINT fk_opprod_opt FOREIGN KEY (oportunidad_id) REFERENCES Oportunidad(id_oportunidad) ON DELETE CASCADE,
    CONSTRAINT fk_opprod_prod FOREIGN KEY (producto_id) REFERENCES Producto(id_producto) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. SEGUIMIENTO Y GESTION

-- guarda la ruta o link de archivos como pdf o imagenes de la venta
CREATE TABLE Documento (
    id_documento INT AUTO_INCREMENT PRIMARY KEY,
    oportunidad_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    url TEXT NOT NULL,
    tipo VARCHAR(50),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_doc_opt FOREIGN KEY (oportunidad_id) REFERENCES Oportunidad(id_oportunidad) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- catalogo de acciones (ej: llamada, visita, reunion virtual)
CREATE TABLE TipoActividad (
    id_tactividad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    estado ENUM ('Activo', 'Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB;

-- registro de quien hizo que cosa en cada proceso de venta
CREATE TABLE Actividad (
    id_actividad INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL, -- vendedor responsable
    oportunidad_id INT NOT NULL,
    tipo_actividad_id INT NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT,
    CONSTRAINT fk_act_opt FOREIGN KEY (oportunidad_id) REFERENCES Oportunidad(id_oportunidad) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_act_tipo FOREIGN KEY (tipo_actividad_id) REFERENCES TipoActividad(id_tactividad) ON UPDATE CASCADE,
    CONSTRAINT fk_act_usuario FOREIGN KEY (usuario_id) REFERENCES Usuario(id_usuario) ON UPDATE CASCADE
) ENGINE=InnoDB;