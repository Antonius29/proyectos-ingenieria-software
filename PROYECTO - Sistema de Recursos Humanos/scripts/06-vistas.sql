-- =====================================================
-- SCRIPT 6: VISTAS (VIEWS)
-- Sistema de Recursos Humanos
-- =====================================================
-- Descripción: Vistas para consultas frecuentes y reportes
-- =====================================================

-- =====================================================
-- VISTA: V_EMPLEADOS_COMPLETO
-- Descripción: Vista con información completa de empleados
--              incluyendo departamento, cargo y cálculos
-- =====================================================
CREATE OR REPLACE VIEW V_EMPLEADOS_COMPLETO AS
SELECT 
    E.ID_EMPLEADO,
    E.NOMBRE,
    E.APELLIDO,
    E.NOMBRE || ' ' || E.APELLIDO AS NOMBRE_COMPLETO,
    E.EMAIL,
    E.TELEFONO,
    E.FECHA_NACIMIENTO,
    FLOOR((SYSDATE - E.FECHA_NACIMIENTO) / 365.25) AS EDAD,
    E.FECHA_CONTRATACION,
    ROUND((SYSDATE - E.FECHA_CONTRATACION) / 365.25, 2) AS AÑOS_ANTIGUEDAD,
    E.SALARIO,
    E.ESTADO,
    D.ID_DEPARTAMENTO,
    D.NOMBRE AS DEPARTAMENTO,
    C.ID_CARGO,
    C.NOMBRE AS CARGO,
    C.NIVEL AS NIVEL_CARGO,
    C.SALARIO_MINIMO AS SALARIO_MIN_CARGO,
    C.SALARIO_MAXIMO AS SALARIO_MAX_CARGO
FROM EMPLEADOS E
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
INNER JOIN CARGOS C ON E.ID_CARGO = C.ID_CARGO;

COMMENT ON TABLE V_EMPLEADOS_COMPLETO IS 'Vista con información completa de empleados';

-- =====================================================
-- VISTA: V_RESUMEN_DEPARTAMENTOS
-- Descripción: Resumen estadístico por departamento
-- =====================================================
CREATE OR REPLACE VIEW V_RESUMEN_DEPARTAMENTOS AS
SELECT 
    D.ID_DEPARTAMENTO,
    D.NOMBRE AS DEPARTAMENTO,
    D.DESCRIPCION,
    D.PRESUPUESTO,
    COUNT(E.ID_EMPLEADO) AS TOTAL_EMPLEADOS,
    COUNT(CASE WHEN E.ESTADO = 'ACTIVO' THEN 1 END) AS EMPLEADOS_ACTIVOS,
    ROUND(AVG(E.SALARIO), 2) AS SALARIO_PROMEDIO,
    MIN(E.SALARIO) AS SALARIO_MINIMO,
    MAX(E.SALARIO) AS SALARIO_MAXIMO,
    SUM(E.SALARIO) AS TOTAL_NOMINA_MENSUAL,
    SUM(E.SALARIO) * 12 AS TOTAL_NOMINA_ANUAL,
    D.PRESUPUESTO - (SUM(E.SALARIO) * 12) AS PRESUPUESTO_DISPONIBLE
FROM DEPARTAMENTOS D
LEFT JOIN EMPLEADOS E ON D.ID_DEPARTAMENTO = E.ID_DEPARTAMENTO
GROUP BY D.ID_DEPARTAMENTO, D.NOMBRE, D.DESCRIPCION, D.PRESUPUESTO;

COMMENT ON TABLE V_RESUMEN_DEPARTAMENTOS IS 'Resumen estadístico y financiero por departamento';

-- =====================================================
-- VISTA: V_RESUMEN_CARGOS
-- Descripción: Resumen de cargos con cantidad de empleados
-- =====================================================
CREATE OR REPLACE VIEW V_RESUMEN_CARGOS AS
SELECT 
    C.ID_CARGO,
    C.NOMBRE AS CARGO,
    C.NIVEL,
    C.DESCRIPCION,
    C.SALARIO_MINIMO,
    C.SALARIO_MAXIMO,
    COUNT(E.ID_EMPLEADO) AS TOTAL_EMPLEADOS,
    ROUND(AVG(E.SALARIO), 2) AS SALARIO_PROMEDIO_REAL,
    STRING_AGG(F.NOMBRE, ', ') AS FUNCIONES_ASIGNADAS
FROM CARGOS C
LEFT JOIN EMPLEADOS E ON C.ID_CARGO = E.ID_CARGO AND E.ESTADO = 'ACTIVO'
LEFT JOIN CARGO_FUNCIONES CF ON C.ID_CARGO = CF.ID_CARGO
LEFT JOIN FUNCIONES F ON CF.ID_FUNCION = F.ID_FUNCION
GROUP BY C.ID_CARGO, C.NOMBRE, C.NIVEL, C.DESCRIPCION, C.SALARIO_MINIMO, C.SALARIO_MAXIMO;

COMMENT ON TABLE V_RESUMEN_CARGOS IS 'Resumen de cargos con empleados y funciones';

-- =====================================================
-- VISTA: V_HISTORIAL_SALARIOS_DETALLE
-- Descripción: Historial de salarios con información del empleado
-- =====================================================
CREATE OR REPLACE VIEW V_HISTORIAL_SALARIOS_DETALLE AS
SELECT 
    HS.ID_HISTORIAL,
    HS.ID_EMPLEADO,
    E.NOMBRE || ' ' || E.APELLIDO AS EMPLEADO,
    D.NOMBRE AS DEPARTAMENTO,
    C.NOMBRE AS CARGO,
    HS.SALARIO_ANTERIOR,
    HS.SALARIO_NUEVO,
    HS.SALARIO_NUEVO - HS.SALARIO_ANTERIOR AS DIFERENCIA,
    HS.PORCENTAJE_CAMBIO,
    HS.FECHA_CAMBIO,
    HS.MOTIVO,
    HS.USUARIO_MODIFICACION
FROM HISTORIAL_SALARIOS HS
INNER JOIN EMPLEADOS E ON HS.ID_EMPLEADO = E.ID_EMPLEADO
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
INNER JOIN CARGOS C ON E.ID_CARGO = C.ID_CARGO
ORDER BY HS.FECHA_CAMBIO DESC;

COMMENT ON TABLE V_HISTORIAL_SALARIOS_DETALLE IS 'Historial detallado de cambios de salario';

-- =====================================================
-- VISTA: V_EMPLEADOS_ANIVERSARIO
-- Descripción: Empleados próximos a cumplir años en la empresa
-- =====================================================
CREATE OR REPLACE VIEW V_EMPLEADOS_ANIVERSARIO AS
SELECT 
    E.ID_EMPLEADO,
    E.NOMBRE || ' ' || E.APELLIDO AS EMPLEADO,
    E.EMAIL,
    D.NOMBRE AS DEPARTAMENTO,
    E.FECHA_CONTRATACION,
    FLOOR((SYSDATE - E.FECHA_CONTRATACION) / 365.25) AS AÑOS_COMPLETADOS,
    FLOOR((SYSDATE - E.FECHA_CONTRATACION) / 365.25) + 1 AS PROXIMO_ANIVERSARIO,
    ADD_MONTHS(E.FECHA_CONTRATACION, 
        (FLOOR((SYSDATE - E.FECHA_CONTRATACION) / 365.25) + 1) * 12) AS FECHA_PROXIMO_ANIVERSARIO
FROM EMPLEADOS E
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
WHERE E.ESTADO = 'ACTIVO'
ORDER BY ADD_MONTHS(E.FECHA_CONTRATACION, 
    (FLOOR((SYSDATE - E.FECHA_CONTRATACION) / 365.25) + 1) * 12);

COMMENT ON TABLE V_EMPLEADOS_ANIVERSARIO IS 'Empleados y sus próximos aniversarios laborales';

-- =====================================================
-- VISTA: V_CUMPLEAÑOS_MES_ACTUAL
-- Descripción: Empleados que cumplen años en el mes actual
-- =====================================================
CREATE OR REPLACE VIEW V_CUMPLEAÑOS_MES_ACTUAL AS
SELECT 
    E.ID_EMPLEADO,
    E.NOMBRE || ' ' || E.APELLIDO AS EMPLEADO,
    E.EMAIL,
    D.NOMBRE AS DEPARTAMENTO,
    E.FECHA_NACIMIENTO,
    TO_CHAR(E.FECHA_NACIMIENTO, 'DD/MM') AS DIA_CUMPLEAÑOS,
    FLOOR((SYSDATE - E.FECHA_NACIMIENTO) / 365.25) AS EDAD_ACTUAL
FROM EMPLEADOS E
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
WHERE TO_CHAR(E.FECHA_NACIMIENTO, 'MM') = TO_CHAR(SYSDATE, 'MM')
AND E.ESTADO = 'ACTIVO'
ORDER BY TO_CHAR(E.FECHA_NACIMIENTO, 'DD');

COMMENT ON TABLE V_CUMPLEAÑOS_MES_ACTUAL IS 'Empleados con cumpleaños en el mes actual';

-- =====================================================
-- VISTA: V_DISTRIBUCION_NIVELES
-- Descripción: Distribución de empleados por nivel jerárquico
-- =====================================================
CREATE OR REPLACE VIEW V_DISTRIBUCION_NIVELES AS
SELECT 
    C.NIVEL,
    COUNT(E.ID_EMPLEADO) AS TOTAL_EMPLEADOS,
    ROUND(AVG(E.SALARIO), 2) AS SALARIO_PROMEDIO,
    MIN(E.SALARIO) AS SALARIO_MINIMO,
    MAX(E.SALARIO) AS SALARIO_MAXIMO,
    SUM(E.SALARIO) AS NOMINA_TOTAL
FROM CARGOS C
LEFT JOIN EMPLEADOS E ON C.ID_CARGO = E.ID_CARGO AND E.ESTADO = 'ACTIVO'
GROUP BY C.NIVEL
ORDER BY 
    CASE C.NIVEL
        WHEN 'DIRECTOR' THEN 1
        WHEN 'GERENTE' THEN 2
        WHEN 'SENIOR' THEN 3
        WHEN 'SEMI-SENIOR' THEN 4
        WHEN 'JUNIOR' THEN 5
    END;

COMMENT ON TABLE V_DISTRIBUCION_NIVELES IS 'Distribución de empleados por nivel jerárquico';

-- =====================================================
-- VERIFICACIÓN DE VISTAS CREADAS
-- =====================================================
SELECT 'Vistas creadas exitosamente' AS RESULTADO FROM DUAL;

-- Listar vistas creadas
SELECT VIEW_NAME FROM USER_VIEWS 
WHERE VIEW_NAME LIKE 'V_%'
ORDER BY VIEW_NAME;

-- =====================================================
-- EJEMPLOS DE USO DE LAS VISTAS
-- =====================================================

-- Ejemplo 1: Consultar empleados completos
SELECT * FROM V_EMPLEADOS_COMPLETO 
WHERE ESTADO = 'ACTIVO'
ORDER BY DEPARTAMENTO, NOMBRE;

-- Ejemplo 2: Ver resumen de departamentos
SELECT * FROM V_RESUMEN_DEPARTAMENTOS
ORDER BY TOTAL_EMPLEADOS DESC;

-- Ejemplo 3: Ver cargos y sus empleados
SELECT * FROM V_RESUMEN_CARGOS
ORDER BY NIVEL, CARGO;

-- Ejemplo 4: Ver historial de salarios reciente
SELECT * FROM V_HISTORIAL_SALARIOS_DETALLE
WHERE ROWNUM <= 10;

-- Ejemplo 5: Ver cumpleaños del mes
SELECT * FROM V_CUMPLEAÑOS_MES_ACTUAL;

-- Ejemplo 6: Ver distribución por niveles
SELECT * FROM V_DISTRIBUCION_NIVELES;
