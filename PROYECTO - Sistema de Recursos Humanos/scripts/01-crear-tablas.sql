-- =====================================================
-- SCRIPT 1: CREACIÓN DE TABLAS
-- Sistema de Recursos Humanos
-- =====================================================
-- Descripción: Este script crea todas las tablas necesarias
-- para el sistema de gestión de recursos humanos con sus
-- respectivas constraints, índices y relaciones.
-- =====================================================

-- =====================================================
-- TABLA: DEPARTAMENTOS
-- Descripción: Almacena información de los departamentos de la empresa
-- =====================================================
CREATE TABLE DEPARTAMENTOS (
    ID_DEPARTAMENTO NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100) NOT NULL UNIQUE,
    DESCRIPCION VARCHAR2(500),
    PRESUPUESTO NUMBER(12,2) DEFAULT 0,
    FECHA_CREACION DATE DEFAULT SYSDATE,
    ACTIVO CHAR(1) DEFAULT 'S' CHECK (ACTIVO IN ('S', 'N'))
);

-- Comentarios en las columnas para documentación
COMMENT ON TABLE DEPARTAMENTOS IS 'Tabla que almacena los departamentos de la empresa';
COMMENT ON COLUMN DEPARTAMENTOS.ID_DEPARTAMENTO IS 'Identificador único del departamento';
COMMENT ON COLUMN DEPARTAMENTOS.NOMBRE IS 'Nombre del departamento (único)';
COMMENT ON COLUMN DEPARTAMENTOS.PRESUPUESTO IS 'Presupuesto anual del departamento';

-- =====================================================
-- TABLA: CARGOS
-- Descripción: Define los diferentes cargos disponibles en la empresa
-- =====================================================
CREATE TABLE CARGOS (
    ID_CARGO NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100) NOT NULL UNIQUE,
    NIVEL VARCHAR2(50) CHECK (NIVEL IN ('JUNIOR', 'SEMI-SENIOR', 'SENIOR', 'GERENTE', 'DIRECTOR')),
    DESCRIPCION VARCHAR2(500),
    SALARIO_MINIMO NUMBER(10,2) NOT NULL,
    SALARIO_MAXIMO NUMBER(10,2) NOT NULL,
    FECHA_CREACION DATE DEFAULT SYSDATE,
    CONSTRAINT CHK_SALARIO_RANGO CHECK (SALARIO_MAXIMO > SALARIO_MINIMO)
);

COMMENT ON TABLE CARGOS IS 'Catálogo de cargos o puestos de trabajo';
COMMENT ON COLUMN CARGOS.NIVEL IS 'Nivel jerárquico del cargo';
COMMENT ON COLUMN CARGOS.SALARIO_MINIMO IS 'Salario mínimo para el cargo';
COMMENT ON COLUMN CARGOS.SALARIO_MAXIMO IS 'Salario máximo para el cargo';

-- =====================================================
-- TABLA: FUNCIONES
-- Descripción: Define las funciones o responsabilidades que puede tener un cargo
-- =====================================================
CREATE TABLE FUNCIONES (
    ID_FUNCION NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100) NOT NULL UNIQUE,
    DESCRIPCION VARCHAR2(500),
    CATEGORIA VARCHAR2(50) CHECK (CATEGORIA IN ('OPERATIVA', 'ADMINISTRATIVA', 'GERENCIAL', 'ESTRATEGICA')),
    FECHA_CREACION DATE DEFAULT SYSDATE
);

COMMENT ON TABLE FUNCIONES IS 'Catálogo de funciones o responsabilidades laborales';
COMMENT ON COLUMN FUNCIONES.CATEGORIA IS 'Categoría de la función según su nivel';

-- =====================================================
-- TABLA: EMPLEADOS
-- Descripción: Almacena la información principal de los empleados
-- =====================================================
CREATE TABLE EMPLEADOS (
    ID_EMPLEADO NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100) NOT NULL,
    APELLIDO VARCHAR2(100) NOT NULL,
    EMAIL VARCHAR2(150) NOT NULL UNIQUE,
    TELEFONO VARCHAR2(20),
    FECHA_NACIMIENTO DATE NOT NULL,
    FECHA_CONTRATACION DATE DEFAULT SYSDATE,
    ID_DEPARTAMENTO NUMBER NOT NULL,
    ID_CARGO NUMBER NOT NULL,
    SALARIO NUMBER(10,2) NOT NULL,
    ESTADO VARCHAR2(20) DEFAULT 'ACTIVO' CHECK (ESTADO IN ('ACTIVO', 'INACTIVO', 'VACACIONES', 'LICENCIA')),
    FECHA_ACTUALIZACION DATE DEFAULT SYSDATE,
    -- Claves foráneas
    CONSTRAINT FK_EMP_DEPTO FOREIGN KEY (ID_DEPARTAMENTO) 
        REFERENCES DEPARTAMENTOS(ID_DEPARTAMENTO),
    CONSTRAINT FK_EMP_CARGO FOREIGN KEY (ID_CARGO) 
        REFERENCES CARGOS(ID_CARGO),
    -- Validaciones adicionales
    CONSTRAINT CHK_EDAD CHECK (FECHA_NACIMIENTO < SYSDATE - INTERVAL '18' YEAR),
    CONSTRAINT CHK_FECHA_CONTRATACION CHECK (FECHA_CONTRATACION >= FECHA_NACIMIENTO)
);

COMMENT ON TABLE EMPLEADOS IS 'Tabla principal con información de todos los empleados';
COMMENT ON COLUMN EMPLEADOS.ESTADO IS 'Estado laboral actual del empleado';
COMMENT ON COLUMN EMPLEADOS.SALARIO IS 'Salario mensual actual del empleado';

-- =====================================================
-- TABLA: CARGO_FUNCIONES (Relación Muchos a Muchos)
-- Descripción: Relaciona los cargos con sus funciones asignadas
-- =====================================================
CREATE TABLE CARGO_FUNCIONES (
    ID_CARGO NUMBER NOT NULL,
    ID_FUNCION NUMBER NOT NULL,
    FECHA_ASIGNACION DATE DEFAULT SYSDATE,
    -- Clave primaria compuesta
    CONSTRAINT PK_CARGO_FUNCIONES PRIMARY KEY (ID_CARGO, ID_FUNCION),
    -- Claves foráneas
    CONSTRAINT FK_CF_CARGO FOREIGN KEY (ID_CARGO) 
        REFERENCES CARGOS(ID_CARGO) ON DELETE CASCADE,
    CONSTRAINT FK_CF_FUNCION FOREIGN KEY (ID_FUNCION) 
        REFERENCES FUNCIONES(ID_FUNCION) ON DELETE CASCADE
);

COMMENT ON TABLE CARGO_FUNCIONES IS 'Tabla de relación entre cargos y sus funciones';

-- =====================================================
-- TABLA: HISTORIAL_SALARIOS
-- Descripción: Mantiene un registro histórico de todos los cambios de salario
-- =====================================================
CREATE TABLE HISTORIAL_SALARIOS (
    ID_HISTORIAL NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    ID_EMPLEADO NUMBER NOT NULL,
    SALARIO_ANTERIOR NUMBER(10,2) NOT NULL,
    SALARIO_NUEVO NUMBER(10,2) NOT NULL,
    PORCENTAJE_CAMBIO NUMBER(5,2),
    FECHA_CAMBIO DATE DEFAULT SYSDATE,
    MOTIVO VARCHAR2(500),
    USUARIO_MODIFICACION VARCHAR2(50) DEFAULT USER,
    -- Clave foránea
    CONSTRAINT FK_HS_EMPLEADO FOREIGN KEY (ID_EMPLEADO) 
        REFERENCES EMPLEADOS(ID_EMPLEADO) ON DELETE CASCADE
);

COMMENT ON TABLE HISTORIAL_SALARIOS IS 'Auditoría de cambios de salario de empleados';
COMMENT ON COLUMN HISTORIAL_SALARIOS.PORCENTAJE_CAMBIO IS 'Porcentaje de aumento o disminución';
COMMENT ON COLUMN HISTORIAL_SALARIOS.USUARIO_MODIFICACION IS 'Usuario que realizó el cambio';

-- =====================================================
-- ÍNDICES PARA OPTIMIZACIÓN DE CONSULTAS
-- Descripción: Índices en columnas frecuentemente consultadas
-- =====================================================

-- Índice para búsquedas por nombre de empleado
CREATE INDEX IDX_EMP_NOMBRE ON EMPLEADOS(NOMBRE, APELLIDO);

-- Índice para búsquedas por departamento
CREATE INDEX IDX_EMP_DEPTO ON EMPLEADOS(ID_DEPARTAMENTO);

-- Índice para búsquedas por cargo
CREATE INDEX IDX_EMP_CARGO ON EMPLEADOS(ID_CARGO);

-- Índice para búsquedas por estado
CREATE INDEX IDX_EMP_ESTADO ON EMPLEADOS(ESTADO);

-- Índice para búsquedas por email (aunque ya es único, mejora búsquedas)
CREATE INDEX IDX_EMP_EMAIL ON EMPLEADOS(EMAIL);

-- Índice para historial por empleado
CREATE INDEX IDX_HS_EMPLEADO ON HISTORIAL_SALARIOS(ID_EMPLEADO);

-- Índice para historial por fecha
CREATE INDEX IDX_HS_FECHA ON HISTORIAL_SALARIOS(FECHA_CAMBIO);

-- =====================================================
-- SECUENCIAS (Opcional - para control manual de IDs)
-- Descripción: Secuencias adicionales si se necesita control manual
-- =====================================================

-- Nota: Las tablas ya usan GENERATED ALWAYS AS IDENTITY
-- pero estas secuencias pueden usarse para otros propósitos

CREATE SEQUENCE SEQ_EMPLEADO_CODIGO START WITH 1000 INCREMENT BY 1;
COMMENT ON SEQUENCE SEQ_EMPLEADO_CODIGO IS 'Secuencia para códigos de empleado alternativos';

-- =====================================================
-- COMMIT Y MENSAJE DE FINALIZACIÓN
-- =====================================================
COMMIT;

-- Mensaje de confirmación
SELECT 'Tablas creadas exitosamente' AS RESULTADO FROM DUAL;
