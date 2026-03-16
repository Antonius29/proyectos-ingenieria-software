-- =====================================================
-- SCRIPT 4: FUNCIONES (FUNCTIONS)
-- Sistema de Recursos Humanos
-- =====================================================
-- Descripción: Funciones para cálculos y consultas complejas
-- =====================================================

-- =====================================================
-- FUNCIÓN: FN_CALCULAR_ANTIGUEDAD
-- Descripción: Calcula los años de antigüedad de un empleado
-- Parámetro: ID del empleado
-- Retorna: Años de antigüedad (con decimales)
-- =====================================================
CREATE OR REPLACE FUNCTION FN_CALCULAR_ANTIGUEDAD (
    p_id_empleado IN NUMBER
) RETURN NUMBER AS
    v_fecha_contratacion DATE;
    v_antiguedad NUMBER;
BEGIN
    -- Obtener fecha de contratación
    SELECT FECHA_CONTRATACION INTO v_fecha_contratacion
    FROM EMPLEADOS WHERE ID_EMPLEADO = p_id_empleado;
    
    -- Calcular años de diferencia
    v_antiguedad := ROUND((SYSDATE - v_fecha_contratacion) / 365.25, 2);
    
    RETURN v_antiguedad;
    
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RETURN NULL;
END;
/

-- =====================================================
-- FUNCIÓN: FN_SALARIO_PROMEDIO_DEPARTAMENTO
-- Descripción: Calcula el salario promedio de un departamento
-- Parámetro: ID del departamento
-- Retorna: Salario promedio
-- =====================================================
CREATE OR REPLACE FUNCTION FN_SALARIO_PROMEDIO_DEPARTAMENTO (
    p_id_departamento IN NUMBER
) RETURN NUMBER AS
    v_promedio NUMBER;
BEGIN
    SELECT AVG(SALARIO) INTO v_promedio
    FROM EMPLEADOS 
    WHERE ID_DEPARTAMENTO = p_id_departamento 
    AND ESTADO = 'ACTIVO';
    
    RETURN ROUND(v_promedio, 2);
    
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN 0;
    WHEN OTHERS THEN
        RETURN 0;
END;
/

-- =====================================================
-- FUNCIÓN: FN_TOTAL_EMPLEADOS_DEPARTAMENTO
-- Descripción: Cuenta empleados activos de un departamento
-- Parámetro: ID del departamento
-- Retorna: Cantidad de empleados
-- =====================================================
CREATE OR REPLACE FUNCTION FN_TOTAL_EMPLEADOS_DEPARTAMENTO (
    p_id_departamento IN NUMBER
) RETURN NUMBER AS
    v_total NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_total
    FROM EMPLEADOS 
    WHERE ID_DEPARTAMENTO = p_id_departamento 
    AND ESTADO = 'ACTIVO';
    
    RETURN v_total;
    
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END;
/

-- =====================================================
-- FUNCIÓN: FN_OBTENER_NOMBRE_COMPLETO
-- Descripción: Retorna el nombre completo de un empleado
-- Parámetro: ID del empleado
-- Retorna: Nombre completo (Apellido, Nombre)
-- =====================================================
CREATE OR REPLACE FUNCTION FN_OBTENER_NOMBRE_COMPLETO (
    p_id_empleado IN NUMBER
) RETURN VARCHAR2 AS
    v_nombre_completo VARCHAR2(200);
BEGIN
    SELECT APELLIDO || ', ' || NOMBRE INTO v_nombre_completo
    FROM EMPLEADOS WHERE ID_EMPLEADO = p_id_empleado;
    
    RETURN v_nombre_completo;
    
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN 'Empleado no encontrado';
    WHEN OTHERS THEN
        RETURN 'Error';
END;
/

-- =====================================================
-- FUNCIÓN: FN_CALCULAR_EDAD
-- Descripción: Calcula la edad actual de un empleado
-- Parámetro: ID del empleado
-- Retorna: Edad en años
-- =====================================================
CREATE OR REPLACE FUNCTION FN_CALCULAR_EDAD (
    p_id_empleado IN NUMBER
) RETURN NUMBER AS
    v_fecha_nacimiento DATE;
    v_edad NUMBER;
BEGIN
    SELECT FECHA_NACIMIENTO INTO v_fecha_nacimiento
    FROM EMPLEADOS WHERE ID_EMPLEADO = p_id_empleado;
    
    v_edad := FLOOR((SYSDATE - v_fecha_nacimiento) / 365.25);
    
    RETURN v_edad;
    
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RETURN NULL;
END;
/

-- =====================================================
-- FUNCIÓN: FN_TOTAL_AUMENTOS_EMPLEADO
-- Descripción: Cuenta cuántos aumentos ha recibido un empleado
-- Parámetro: ID del empleado
-- Retorna: Cantidad de aumentos
-- =====================================================
CREATE OR REPLACE FUNCTION FN_TOTAL_AUMENTOS_EMPLEADO (
    p_id_empleado IN NUMBER
) RETURN NUMBER AS
    v_total NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_total
    FROM HISTORIAL_SALARIOS 
    WHERE ID_EMPLEADO = p_id_empleado;
    
    RETURN v_total;
    
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END;
/

-- =====================================================
-- FUNCIÓN: FN_PORCENTAJE_AUMENTO_TOTAL
-- Descripción: Calcula el porcentaje total de aumento desde contratación
-- Parámetro: ID del empleado
-- Retorna: Porcentaje total de aumento
-- =====================================================
CREATE OR REPLACE FUNCTION FN_PORCENTAJE_AUMENTO_TOTAL (
    p_id_empleado IN NUMBER
) RETURN NUMBER AS
    v_salario_actual NUMBER;
    v_salario_inicial NUMBER;
    v_porcentaje NUMBER;
BEGIN
    -- Obtener salario actual
    SELECT SALARIO INTO v_salario_actual
    FROM EMPLEADOS WHERE ID_EMPLEADO = p_id_empleado;
    
    -- Obtener salario inicial (primer registro en historial o actual si no hay historial)
    BEGIN
        SELECT SALARIO_ANTERIOR INTO v_salario_inicial
        FROM (
            SELECT SALARIO_ANTERIOR 
            FROM HISTORIAL_SALARIOS 
            WHERE ID_EMPLEADO = p_id_empleado
            ORDER BY FECHA_CAMBIO ASC
        ) WHERE ROWNUM = 1;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            -- Si no hay historial, el aumento es 0
            RETURN 0;
    END;
    
    -- Calcular porcentaje
    v_porcentaje := ROUND(((v_salario_actual - v_salario_inicial) / v_salario_inicial) * 100, 2);
    
    RETURN v_porcentaje;
    
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END;
/

-- =====================================================
-- VERIFICACIÓN DE FUNCIONES CREADAS
-- =====================================================
SELECT 'Funciones creadas exitosamente' AS RESULTADO FROM DUAL;

-- Listar funciones creadas
SELECT OBJECT_NAME, STATUS FROM USER_OBJECTS 
WHERE OBJECT_TYPE = 'FUNCTION' AND OBJECT_NAME LIKE 'FN_%'
ORDER BY OBJECT_NAME;

-- =====================================================
-- EJEMPLOS DE USO DE LAS FUNCIONES
-- =====================================================

-- Ejemplo 1: Obtener antigüedad de un empleado
SELECT 
    ID_EMPLEADO,
    NOMBRE || ' ' || APELLIDO AS EMPLEADO,
    FN_CALCULAR_ANTIGUEDAD(ID_EMPLEADO) AS AÑOS_ANTIGUEDAD
FROM EMPLEADOS
WHERE ID_EMPLEADO = 1;

-- Ejemplo 2: Salario promedio por departamento
SELECT 
    D.NOMBRE AS DEPARTAMENTO,
    FN_SALARIO_PROMEDIO_DEPARTAMENTO(D.ID_DEPARTAMENTO) AS SALARIO_PROMEDIO,
    FN_TOTAL_EMPLEADOS_DEPARTAMENTO(D.ID_DEPARTAMENTO) AS TOTAL_EMPLEADOS
FROM DEPARTAMENTOS D;

-- Ejemplo 3: Información completa de empleados con cálculos
SELECT 
    ID_EMPLEADO,
    FN_OBTENER_NOMBRE_COMPLETO(ID_EMPLEADO) AS NOMBRE_COMPLETO,
    FN_CALCULAR_EDAD(ID_EMPLEADO) AS EDAD,
    FN_CALCULAR_ANTIGUEDAD(ID_EMPLEADO) AS ANTIGUEDAD,
    FN_TOTAL_AUMENTOS_EMPLEADO(ID_EMPLEADO) AS TOTAL_AUMENTOS,
    FN_PORCENTAJE_AUMENTO_TOTAL(ID_EMPLEADO) AS PORCENTAJE_AUMENTO
FROM EMPLEADOS
WHERE ESTADO = 'ACTIVO'
ORDER BY ID_EMPLEADO;
