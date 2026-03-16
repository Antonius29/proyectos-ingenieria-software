-- =====================================================
-- SCRIPT 3: PROCEDIMIENTOS ALMACENADOS (STORED PROCEDURES)
-- Sistema de Recursos Humanos
-- =====================================================
-- Descripción: Procedimientos para operaciones CRUD
-- en las principales tablas del sistema
-- =====================================================

-- =====================================================
-- PROCEDIMIENTO: SP_CREAR_EMPLEADO
-- Descripción: Inserta un nuevo empleado con validaciones
-- Parámetros: Todos los datos del empleado
-- =====================================================
CREATE OR REPLACE PROCEDURE SP_CREAR_EMPLEADO (
    p_nombre IN VARCHAR2,
    p_apellido IN VARCHAR2,
    p_email IN VARCHAR2,
    p_telefono IN VARCHAR2,
    p_fecha_nacimiento IN DATE,
    p_id_departamento IN NUMBER,
    p_id_cargo IN NUMBER,
    p_salario IN NUMBER,
    p_resultado OUT VARCHAR2
) AS
    v_salario_min NUMBER;
    v_salario_max NUMBER;
    v_count NUMBER;
BEGIN
    -- Validar que el email no exista
    SELECT COUNT(*) INTO v_count FROM EMPLEADOS WHERE EMAIL = p_email;
    IF v_count > 0 THEN
        p_resultado := 'ERROR: El email ya existe';
        RETURN;
    END IF;
    
    -- Validar que el salario esté dentro del rango del cargo
    SELECT SALARIO_MINIMO, SALARIO_MAXIMO INTO v_salario_min, v_salario_max
    FROM CARGOS WHERE ID_CARGO = p_id_cargo;
    
    IF p_salario < v_salario_min OR p_salario > v_salario_max THEN
        p_resultado := 'ERROR: Salario fuera del rango permitido (' || v_salario_min || ' - ' || v_salario_max || ')';
        RETURN;
    END IF;
    
    -- Insertar el empleado
    INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, 
                          ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO)
    VALUES (p_nombre, p_apellido, p_email, p_telefono, p_fecha_nacimiento,
            p_id_departamento, p_id_cargo, p_salario, 'ACTIVO');
    
    COMMIT;
    p_resultado := 'OK: Empleado creado exitosamente';
    
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        p_resultado := 'ERROR: ' || SQLERRM;
END;
/

-- =====================================================
-- PROCEDIMIENTO: SP_ACTUALIZAR_EMPLEADO
-- Descripción: Actualiza los datos de un empleado existente
-- Parámetros: ID del empleado y nuevos datos
-- =====================================================
CREATE OR REPLACE PROCEDURE SP_ACTUALIZAR_EMPLEADO (
    p_id_empleado IN NUMBER,
    p_nombre IN VARCHAR2,
    p_apellido IN VARCHAR2,
    p_telefono IN VARCHAR2,
    p_id_departamento IN NUMBER,
    p_id_cargo IN NUMBER,
    p_estado IN VARCHAR2,
    p_resultado OUT VARCHAR2
) AS
    v_count NUMBER;
BEGIN
    -- Validar que el empleado existe
    SELECT COUNT(*) INTO v_count FROM EMPLEADOS WHERE ID_EMPLEADO = p_id_empleado;
    IF v_count = 0 THEN
        p_resultado := 'ERROR: Empleado no encontrado';
        RETURN;
    END IF;
    
    -- Actualizar empleado
    UPDATE EMPLEADOS 
    SET NOMBRE = p_nombre,
        APELLIDO = p_apellido,
        TELEFONO = p_telefono,
        ID_DEPARTAMENTO = p_id_departamento,
        ID_CARGO = p_id_cargo,
        ESTADO = p_estado,
        FECHA_ACTUALIZACION = SYSDATE
    WHERE ID_EMPLEADO = p_id_empleado;
    
    COMMIT;
    p_resultado := 'OK: Empleado actualizado exitosamente';
    
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        p_resultado := 'ERROR: ' || SQLERRM;
END;
/

-- =====================================================
-- PROCEDIMIENTO: SP_ACTUALIZAR_SALARIO
-- Descripción: Actualiza el salario de un empleado y registra en historial
-- Parámetros: ID empleado, nuevo salario, motivo
-- =====================================================
CREATE OR REPLACE PROCEDURE SP_ACTUALIZAR_SALARIO (
    p_id_empleado IN NUMBER,
    p_salario_nuevo IN NUMBER,
    p_motivo IN VARCHAR2,
    p_resultado OUT VARCHAR2
) AS
    v_salario_actual NUMBER;
    v_porcentaje NUMBER;
    v_salario_min NUMBER;
    v_salario_max NUMBER;
    v_id_cargo NUMBER;
BEGIN
    -- Obtener salario actual y cargo
    SELECT SALARIO, ID_CARGO INTO v_salario_actual, v_id_cargo
    FROM EMPLEADOS WHERE ID_EMPLEADO = p_id_empleado;
    
    -- Validar rango de salario según el cargo
    SELECT SALARIO_MINIMO, SALARIO_MAXIMO INTO v_salario_min, v_salario_max
    FROM CARGOS WHERE ID_CARGO = v_id_cargo;
    
    IF p_salario_nuevo < v_salario_min OR p_salario_nuevo > v_salario_max THEN
        p_resultado := 'ERROR: Salario fuera del rango (' || v_salario_min || ' - ' || v_salario_max || ')';
        RETURN;
    END IF;
    
    -- Calcular porcentaje de cambio
    v_porcentaje := ROUND(((p_salario_nuevo - v_salario_actual) / v_salario_actual) * 100, 2);
    
    -- Actualizar salario
    UPDATE EMPLEADOS SET SALARIO = p_salario_nuevo, FECHA_ACTUALIZACION = SYSDATE
    WHERE ID_EMPLEADO = p_id_empleado;
    
    -- Registrar en historial (el trigger lo hará automáticamente)
    
    COMMIT;
    p_resultado := 'OK: Salario actualizado. Cambio: ' || v_porcentaje || '%';
    
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        p_resultado := 'ERROR: Empleado no encontrado';
    WHEN OTHERS THEN
        ROLLBACK;
        p_resultado := 'ERROR: ' || SQLERRM;
END;
/

-- =====================================================
-- PROCEDIMIENTO: SP_ELIMINAR_EMPLEADO
-- Descripción: Cambia el estado de un empleado a INACTIVO (borrado lógico)
-- Parámetros: ID del empleado
-- =====================================================
CREATE OR REPLACE PROCEDURE SP_ELIMINAR_EMPLEADO (
    p_id_empleado IN NUMBER,
    p_resultado OUT VARCHAR2
) AS
    v_count NUMBER;
BEGIN
    -- Validar que el empleado existe
    SELECT COUNT(*) INTO v_count FROM EMPLEADOS WHERE ID_EMPLEADO = p_id_empleado;
    IF v_count = 0 THEN
        p_resultado := 'ERROR: Empleado no encontrado';
        RETURN;
    END IF;
    
    -- Cambiar estado a INACTIVO (borrado lógico)
    UPDATE EMPLEADOS 
    SET ESTADO = 'INACTIVO', FECHA_ACTUALIZACION = SYSDATE
    WHERE ID_EMPLEADO = p_id_empleado;
    
    COMMIT;
    p_resultado := 'OK: Empleado dado de baja exitosamente';
    
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        p_resultado := 'ERROR: ' || SQLERRM;
END;
/

-- =====================================================
-- PROCEDIMIENTO: SP_CREAR_DEPARTAMENTO
-- Descripción: Crea un nuevo departamento
-- Parámetros: Datos del departamento
-- =====================================================
CREATE OR REPLACE PROCEDURE SP_CREAR_DEPARTAMENTO (
    p_nombre IN VARCHAR2,
    p_descripcion IN VARCHAR2,
    p_presupuesto IN NUMBER,
    p_resultado OUT VARCHAR2
) AS
    v_count NUMBER;
BEGIN
    -- Validar que el nombre no exista
    SELECT COUNT(*) INTO v_count FROM DEPARTAMENTOS WHERE NOMBRE = p_nombre;
    IF v_count > 0 THEN
        p_resultado := 'ERROR: Ya existe un departamento con ese nombre';
        RETURN;
    END IF;
    
    -- Insertar departamento
    INSERT INTO DEPARTAMENTOS (NOMBRE, DESCRIPCION, PRESUPUESTO, ACTIVO)
    VALUES (p_nombre, p_descripcion, p_presupuesto, 'S');
    
    COMMIT;
    p_resultado := 'OK: Departamento creado exitosamente';
    
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        p_resultado := 'ERROR: ' || SQLERRM;
END;
/

-- =====================================================
-- PROCEDIMIENTO: SP_ACTUALIZAR_DEPARTAMENTO
-- Descripción: Actualiza datos de un departamento
-- Parámetros: ID y nuevos datos del departamento
-- =====================================================
CREATE OR REPLACE PROCEDURE SP_ACTUALIZAR_DEPARTAMENTO (
    p_id_departamento IN NUMBER,
    p_nombre IN VARCHAR2,
    p_descripcion IN VARCHAR2,
    p_presupuesto IN NUMBER,
    p_resultado OUT VARCHAR2
) AS
    v_count NUMBER;
BEGIN
    -- Validar que existe
    SELECT COUNT(*) INTO v_count FROM DEPARTAMENTOS WHERE ID_DEPARTAMENTO = p_id_departamento;
    IF v_count = 0 THEN
        p_resultado := 'ERROR: Departamento no encontrado';
        RETURN;
    END IF;
    
    -- Actualizar
    UPDATE DEPARTAMENTOS 
    SET NOMBRE = p_nombre,
        DESCRIPCION = p_descripcion,
        PRESUPUESTO = p_presupuesto
    WHERE ID_DEPARTAMENTO = p_id_departamento;
    
    COMMIT;
    p_resultado := 'OK: Departamento actualizado exitosamente';
    
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        p_resultado := 'ERROR: ' || SQLERRM;
END;
/

-- =====================================================
-- PROCEDIMIENTO: SP_CREAR_CARGO
-- Descripción: Crea un nuevo cargo
-- Parámetros: Datos del cargo
-- =====================================================
CREATE OR REPLACE PROCEDURE SP_CREAR_CARGO (
    p_nombre IN VARCHAR2,
    p_nivel IN VARCHAR2,
    p_descripcion IN VARCHAR2,
    p_salario_min IN NUMBER,
    p_salario_max IN NUMBER,
    p_resultado OUT VARCHAR2
) AS
    v_count NUMBER;
BEGIN
    -- Validar nombre único
    SELECT COUNT(*) INTO v_count FROM CARGOS WHERE NOMBRE = p_nombre;
    IF v_count > 0 THEN
        p_resultado := 'ERROR: Ya existe un cargo con ese nombre';
        RETURN;
    END IF;
    
    -- Validar rango de salarios
    IF p_salario_max <= p_salario_min THEN
        p_resultado := 'ERROR: Salario máximo debe ser mayor que el mínimo';
        RETURN;
    END IF;
    
    -- Insertar cargo
    INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO)
    VALUES (p_nombre, p_nivel, p_descripcion, p_salario_min, p_salario_max);
    
    COMMIT;
    p_resultado := 'OK: Cargo creado exitosamente';
    
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        p_resultado := 'ERROR: ' || SQLERRM;
END;
/

-- =====================================================
-- VERIFICACIÓN DE PROCEDIMIENTOS CREADOS
-- =====================================================
SELECT 'Procedimientos almacenados creados exitosamente' AS RESULTADO FROM DUAL;

-- Listar procedimientos creados
SELECT OBJECT_NAME, STATUS FROM USER_OBJECTS 
WHERE OBJECT_TYPE = 'PROCEDURE' AND OBJECT_NAME LIKE 'SP_%'
ORDER BY OBJECT_NAME;
