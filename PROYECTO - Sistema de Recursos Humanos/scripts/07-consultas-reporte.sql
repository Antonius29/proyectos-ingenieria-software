-- =====================================================
-- SCRIPT 7: CONSULTAS Y REPORTES
-- Sistema de Recursos Humanos
-- =====================================================
-- Descripción: Consultas útiles para generar reportes
-- del sistema de recursos humanos
-- =====================================================

-- =====================================================
-- REPORTE 1: LISTA COMPLETA DE EMPLEADOS ACTIVOS
-- Descripción: Listado general de todos los empleados activos
-- =====================================================
SELECT 
    E.ID_EMPLEADO AS "ID",
    E.NOMBRE || ' ' || E.APELLIDO AS "NOMBRE COMPLETO",
    E.EMAIL AS "CORREO",
    E.TELEFONO AS "TELÉFONO",
    D.NOMBRE AS "DEPARTAMENTO",
    C.NOMBRE AS "CARGO",
    TO_CHAR(E.SALARIO, '$999,999.99') AS "SALARIO",
    ROUND((SYSDATE - E.FECHA_CONTRATACION) / 365.25, 1) AS "AÑOS ANTIGÜEDAD",
    E.ESTADO AS "ESTADO"
FROM EMPLEADOS E
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
INNER JOIN CARGOS C ON E.ID_CARGO = C.ID_CARGO
WHERE E.ESTADO = 'ACTIVO'
ORDER BY D.NOMBRE, E.APELLIDO;

-- =====================================================
-- REPORTE 2: NÓMINA POR DEPARTAMENTO
-- Descripción: Total de nómina mensual y anual por departamento
-- =====================================================
SELECT 
    D.NOMBRE AS "DEPARTAMENTO",
    COUNT(E.ID_EMPLEADO) AS "# EMPLEADOS",
    TO_CHAR(MIN(E.SALARIO), '$999,999.99') AS "SALARIO MÍN",
    TO_CHAR(MAX(E.SALARIO), '$999,999.99') AS "SALARIO MÁX",
    TO_CHAR(AVG(E.SALARIO), '$999,999.99') AS "SALARIO PROMEDIO",
    TO_CHAR(SUM(E.SALARIO), '$999,999.99') AS "NÓMINA MENSUAL",
    TO_CHAR(SUM(E.SALARIO) * 12, '$9,999,999.99') AS "NÓMINA ANUAL"
FROM DEPARTAMENTOS D
LEFT JOIN EMPLEADOS E ON D.ID_DEPARTAMENTO = E.ID_DEPARTAMENTO AND E.ESTADO = 'ACTIVO'
GROUP BY D.ID_DEPARTAMENTO, D.NOMBRE
ORDER BY SUM(E.SALARIO) DESC;

-- =====================================================
-- REPORTE 3: EMPLEADOS CON MAYOR ANTIGÜEDAD
-- Descripción: Top 10 empleados con más años en la empresa
-- =====================================================
SELECT 
    E.NOMBRE || ' ' || E.APELLIDO AS "EMPLEADO",
    D.NOMBRE AS "DEPARTAMENTO",
    C.NOMBRE AS "CARGO",
    TO_CHAR(E.FECHA_CONTRATACION, 'DD/MM/YYYY') AS "FECHA INGRESO",
    FLOOR((SYSDATE - E.FECHA_CONTRATACION) / 365.25) AS "AÑOS",
    MOD(FLOOR((SYSDATE - E.FECHA_CONTRATACION)), 365) AS "DÍAS ADICIONALES"
FROM EMPLEADOS E
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
INNER JOIN CARGOS C ON E.ID_CARGO = C.ID_CARGO
WHERE E.ESTADO = 'ACTIVO'
ORDER BY E.FECHA_CONTRATACION ASC
FETCH FIRST 10 ROWS ONLY;

-- =====================================================
-- REPORTE 4: ANÁLISIS SALARIAL POR CARGO
-- Descripción: Comparativa de salarios por tipo de cargo
-- =====================================================
SELECT 
    C.NIVEL AS "NIVEL",
    C.NOMBRE AS "CARGO",
    COUNT(E.ID_EMPLEADO) AS "EMPLEADOS",
    TO_CHAR(C.SALARIO_MINIMO, '$999,999.99') AS "RANGO MÍNIMO",
    TO_CHAR(C.SALARIO_MAXIMO, '$999,999.99') AS "RANGO MÁXIMO",
    TO_CHAR(AVG(E.SALARIO), '$999,999.99') AS "SALARIO PROMEDIO REAL",
    ROUND(AVG(E.SALARIO) / C.SALARIO_MAXIMO * 100, 1) AS "% DEL MÁXIMO"
FROM CARGOS C
LEFT JOIN EMPLEADOS E ON C.ID_CARGO = E.ID_CARGO AND E.ESTADO = 'ACTIVO'
GROUP BY C.ID_CARGO, C.NIVEL, C.NOMBRE, C.SALARIO_MINIMO, C.SALARIO_MAXIMO
ORDER BY C.NIVEL, C.NOMBRE;

-- =====================================================
-- REPORTE 5: HISTORIAL DE AUMENTOS POR EMPLEADO
-- Descripción: Histórico de cambios salariales
-- =====================================================
SELECT 
    E.NOMBRE || ' ' || E.APELLIDO AS "EMPLEADO",
    D.NOMBRE AS "DEPARTAMENTO",
    TO_CHAR(HS.SALARIO_ANTERIOR, '$999,999.99') AS "SALARIO ANTERIOR",
    TO_CHAR(HS.SALARIO_NUEVO, '$999,999.99') AS "SALARIO NUEVO",
    TO_CHAR(HS.SALARIO_NUEVO - HS.SALARIO_ANTERIOR, '$999,999.99') AS "DIFERENCIA",
    HS.PORCENTAJE_CAMBIO || '%' AS "% CAMBIO",
    TO_CHAR(HS.FECHA_CAMBIO, 'DD/MM/YYYY') AS "FECHA",
    HS.MOTIVO AS "MOTIVO"
FROM HISTORIAL_SALARIOS HS
INNER JOIN EMPLEADOS E ON HS.ID_EMPLEADO = E.ID_EMPLEADO
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
ORDER BY HS.FECHA_CAMBIO DESC;

-- =====================================================
-- REPORTE 6: CUMPLEAÑOS DEL MES
-- Descripción: Empleados que cumplen años este mes
-- =====================================================
SELECT 
    E.NOMBRE || ' ' || E.APELLIDO AS "EMPLEADO",
    D.NOMBRE AS "DEPARTAMENTO",
    TO_CHAR(E.FECHA_NACIMIENTO, 'DD/MM/YYYY') AS "FECHA NACIMIENTO",
    TO_CHAR(E.FECHA_NACIMIENTO, 'DD "de" Month') AS "DÍA CUMPLEAÑOS",
    FLOOR((SYSDATE - E.FECHA_NACIMIENTO) / 365.25) + 1 AS "EDAD A CUMPLIR",
    E.EMAIL AS "CORREO"
FROM EMPLEADOS E
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
WHERE TO_CHAR(E.FECHA_NACIMIENTO, 'MM') = TO_CHAR(SYSDATE, 'MM')
AND E.ESTADO = 'ACTIVO'
ORDER BY TO_CHAR(E.FECHA_NACIMIENTO, 'DD');

-- =====================================================
-- REPORTE 7: DISTRIBUCIÓN DE EMPLEADOS POR ESTADO
-- Descripción: Cantidad de empleados según su estado laboral
-- =====================================================
SELECT 
    E.ESTADO AS "ESTADO",
    COUNT(*) AS "CANTIDAD",
    ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 2) AS "PORCENTAJE"
FROM EMPLEADOS E
GROUP BY E.ESTADO
ORDER BY COUNT(*) DESC;

-- =====================================================
-- REPORTE 8: TOP 10 SALARIOS MÁS ALTOS
-- Descripción: Empleados con mayores salarios
-- =====================================================
SELECT 
    ROWNUM AS "RANKING",
    E.NOMBRE || ' ' || E.APELLIDO AS "EMPLEADO",
    D.NOMBRE AS "DEPARTAMENTO",
    C.NOMBRE AS "CARGO",
    TO_CHAR(E.SALARIO, '$999,999.99') AS "SALARIO MENSUAL",
    TO_CHAR(E.SALARIO * 12, '$9,999,999.99') AS "SALARIO ANUAL"
FROM (
    SELECT E.*, D.NOMBRE AS DEPTO_NOMBRE, C.NOMBRE AS CARGO_NOMBRE
    FROM EMPLEADOS E
    INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
    INNER JOIN CARGOS C ON E.ID_CARGO = C.ID_CARGO
    WHERE E.ESTADO = 'ACTIVO'
    ORDER BY E.SALARIO DESC
) E, DEPARTAMENTOS D, CARGOS C
WHERE ROWNUM <= 10;

-- =====================================================
-- REPORTE 9: FUNCIONES POR CARGO
-- Descripción: Listado de funciones asignadas a cada cargo
-- =====================================================
SELECT 
    C.NOMBRE AS "CARGO",
    C.NIVEL AS "NIVEL",
    F.NOMBRE AS "FUNCIÓN",
    F.CATEGORIA AS "CATEGORÍA",
    F.DESCRIPCION AS "DESCRIPCIÓN"
FROM CARGOS C
INNER JOIN CARGO_FUNCIONES CF ON C.ID_CARGO = CF.ID_CARGO
INNER JOIN FUNCIONES F ON CF.ID_FUNCION = F.ID_FUNCION
ORDER BY C.NIVEL, C.NOMBRE, F.CATEGORIA;

-- =====================================================
-- REPORTE 10: PROYECCIÓN DE COSTOS
-- Descripción: Proyección de costos de nómina con aumentos hipotéticos
-- =====================================================
SELECT 
    D.NOMBRE AS "DEPARTAMENTO",
    COUNT(E.ID_EMPLEADO) AS "EMPLEADOS",
    TO_CHAR(SUM(E.SALARIO), '$999,999.99') AS "NÓMINA ACTUAL",
    TO_CHAR(SUM(E.SALARIO * 1.05), '$999,999.99') AS "CON AUMENTO 5%",
    TO_CHAR(SUM(E.SALARIO * 1.10), '$999,999.99') AS "CON AUMENTO 10%",
    TO_CHAR(SUM(E.SALARIO * 1.15), '$999,999.99') AS "CON AUMENTO 15%",
    TO_CHAR(SUM(E.SALARIO * 0.15), '$999,999.99') AS "COSTO AUMENTO 15%"
FROM DEPARTAMENTOS D
LEFT JOIN EMPLEADOS E ON D.ID_DEPARTAMENTO = E.ID_DEPARTAMENTO AND E.ESTADO = 'ACTIVO'
GROUP BY D.ID_DEPARTAMENTO, D.NOMBRE
ORDER BY SUM(E.SALARIO) DESC;

-- =====================================================
-- REPORTE 11: ANÁLISIS DE ROTACIÓN (Empleados inactivos)
-- Descripción: Empleados que ya no están activos
-- =====================================================
SELECT 
    E.NOMBRE || ' ' || E.APELLIDO AS "EMPLEADO",
    D.NOMBRE AS "DEPARTAMENTO",
    C.NOMBRE AS "CARGO",
    TO_CHAR(E.FECHA_CONTRATACION, 'DD/MM/YYYY') AS "FECHA INGRESO",
    ROUND((SYSDATE - E.FECHA_CONTRATACION) / 365.25, 1) AS "AÑOS EN EMPRESA",
    E.ESTADO AS "ESTADO ACTUAL"
FROM EMPLEADOS E
INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
INNER JOIN CARGOS C ON E.ID_CARGO = C.ID_CARGO
WHERE E.ESTADO != 'ACTIVO'
ORDER BY E.FECHA_ACTUALIZACION DESC;

-- =====================================================
-- REPORTE 12: RESUMEN EJECUTIVO GENERAL
-- Descripción: Dashboard con métricas clave del sistema
-- =====================================================
SELECT 
    'TOTAL EMPLEADOS' AS "MÉTRICA",
    TO_CHAR(COUNT(*)) AS "VALOR"
FROM EMPLEADOS
UNION ALL
SELECT 
    'EMPLEADOS ACTIVOS',
    TO_CHAR(COUNT(*))
FROM EMPLEADOS WHERE ESTADO = 'ACTIVO'
UNION ALL
SELECT 
    'TOTAL DEPARTAMENTOS',
    TO_CHAR(COUNT(*))
FROM DEPARTAMENTOS
UNION ALL
SELECT 
    'NÓMINA MENSUAL TOTAL',
    TO_CHAR(SUM(SALARIO), '$999,999,999.99')
FROM EMPLEADOS WHERE ESTADO = 'ACTIVO'
UNION ALL
SELECT 
    'NÓMINA ANUAL TOTAL',
    TO_CHAR(SUM(SALARIO) * 12, '$999,999,999.99')
FROM EMPLEADOS WHERE ESTADO = 'ACTIVO'
UNION ALL
SELECT 
    'SALARIO PROMEDIO',
    TO_CHAR(AVG(SALARIO), '$999,999.99')
FROM EMPLEADOS WHERE ESTADO = 'ACTIVO'
UNION ALL
SELECT 
    'ANTIGÜEDAD PROMEDIO (AÑOS)',
    TO_CHAR(ROUND(AVG((SYSDATE - FECHA_CONTRATACION) / 365.25), 1))
FROM EMPLEADOS WHERE ESTADO = 'ACTIVO';

-- =====================================================
-- FIN DEL SCRIPT DE REPORTES
-- =====================================================
SELECT 'Consultas de reporte listas para usar' AS RESULTADO FROM DUAL;
