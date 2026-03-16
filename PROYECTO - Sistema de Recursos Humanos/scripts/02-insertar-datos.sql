-- =====================================================
-- SCRIPT 2: INSERCIÓN DE DATOS DE EJEMPLO
-- Sistema de Recursos Humanos
-- =====================================================
-- Descripción: Este script inserta datos de prueba realistas
-- para todas las tablas del sistema
-- =====================================================

-- =====================================================
-- INSERCIÓN EN TABLA: DEPARTAMENTOS
-- Descripción: Crear 6 departamentos principales de la empresa
-- =====================================================
INSERT INTO DEPARTAMENTOS (NOMBRE, DESCRIPCION, PRESUPUESTO, ACTIVO) 
VALUES ('Recursos Humanos', 'Gestión del talento humano y desarrollo organizacional', 250000, 'S');

INSERT INTO DEPARTAMENTOS (NOMBRE, DESCRIPCION, PRESUPUESTO, ACTIVO) 
VALUES ('Tecnología', 'Desarrollo de software y soporte técnico', 500000, 'S');

INSERT INTO DEPARTAMENTOS (NOMBRE, DESCRIPCION, PRESUPUESTO, ACTIVO) 
VALUES ('Ventas', 'Comercialización y atención al cliente', 350000, 'S');

INSERT INTO DEPARTAMENTOS (NOMBRE, DESCRIPCION, PRESUPUESTO, ACTIVO) 
VALUES ('Marketing', 'Estrategias de marketing y comunicación', 300000, 'S');

INSERT INTO DEPARTAMENTOS (NOMBRE, DESCRIPCION, PRESUPUESTO, ACTIVO) 
VALUES ('Finanzas', 'Contabilidad y gestión financiera', 280000, 'S');

INSERT INTO DEPARTAMENTOS (NOMBRE, DESCRIPCION, PRESUPUESTO, ACTIVO) 
VALUES ('Operaciones', 'Logística y gestión de operaciones', 400000, 'S');

-- =====================================================
-- INSERCIÓN EN TABLA: CARGOS
-- Descripción: Crear 10 cargos con diferentes niveles jerárquicos
-- =====================================================
INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Desarrollador Junior', 'JUNIOR', 'Desarrollo de software bajo supervisión', 1200, 2000);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Desarrollador Senior', 'SENIOR', 'Desarrollo y arquitectura de soluciones', 3000, 5000);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Gerente de Tecnología', 'GERENTE', 'Gestión del departamento de TI', 5000, 8000);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Analista de RRHH', 'SEMI-SENIOR', 'Gestión de procesos de recursos humanos', 1800, 2800);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Director de RRHH', 'DIRECTOR', 'Dirección estratégica de recursos humanos', 6000, 10000);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Ejecutivo de Ventas', 'JUNIOR', 'Prospección y cierre de ventas', 1500, 2500);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Gerente de Ventas', 'GERENTE', 'Gestión de equipo comercial', 4000, 7000);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Especialista en Marketing', 'SEMI-SENIOR', 'Campañas y estrategias de marketing', 2000, 3500);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Contador', 'SEMI-SENIOR', 'Gestión contable y financiera', 2200, 3800);

INSERT INTO CARGOS (NOMBRE, NIVEL, DESCRIPCION, SALARIO_MINIMO, SALARIO_MAXIMO) 
VALUES ('Coordinador de Operaciones', 'SENIOR', 'Coordinación de procesos operativos', 2800, 4500);

-- =====================================================
-- INSERCIÓN EN TABLA: FUNCIONES
-- Descripción: Crear 12 funciones laborales diversas
-- =====================================================
INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Desarrollo de Software', 'Programación y desarrollo de aplicaciones', 'OPERATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Revisión de Código', 'Revisión y aprobación de código fuente', 'OPERATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Gestión de Personal', 'Administración del personal y nóminas', 'ADMINISTRATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Reclutamiento', 'Búsqueda y selección de talento', 'ADMINISTRATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Planificación Estratégica', 'Definición de objetivos y estrategias', 'ESTRATEGICA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Gestión de Proyectos', 'Coordinación y seguimiento de proyectos', 'GERENCIAL');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Atención al Cliente', 'Soporte y atención a clientes', 'OPERATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Análisis Financiero', 'Análisis de estados financieros', 'ADMINISTRATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Marketing Digital', 'Gestión de campañas digitales', 'OPERATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Presupuestación', 'Elaboración y control de presupuestos', 'ADMINISTRATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Auditoría Interna', 'Revisión de procesos y controles', 'ADMINISTRATIVA');

INSERT INTO FUNCIONES (NOMBRE, DESCRIPCION, CATEGORIA) 
VALUES ('Toma de Decisiones', 'Decisiones estratégicas de alto nivel', 'ESTRATEGICA');

-- =====================================================
-- INSERCIÓN EN TABLA: CARGO_FUNCIONES
-- Descripción: Asignar funciones a cada cargo
-- =====================================================

-- Desarrollador Junior: Desarrollo de Software
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (1, 1);

-- Desarrollador Senior: Desarrollo + Revisión de Código
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (2, 1);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (2, 2);

-- Gerente de Tecnología: Gestión de Proyectos + Planificación
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (3, 5);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (3, 6);

-- Analista de RRHH: Gestión de Personal + Reclutamiento
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (4, 3);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (4, 4);

-- Director de RRHH: Todas las de RRHH + Estratégica
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (5, 3);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (5, 4);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (5, 5);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (5, 12);

-- Ejecutivo de Ventas: Atención al Cliente
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (6, 7);

-- Gerente de Ventas: Atención al Cliente + Gestión de Proyectos
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (7, 6);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (7, 7);

-- Especialista en Marketing: Marketing Digital
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (8, 9);

-- Contador: Análisis Financiero + Presupuestación + Auditoría
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (9, 8);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (9, 10);
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (9, 11);

-- Coordinador de Operaciones: Gestión de Proyectos
INSERT INTO CARGO_FUNCIONES (ID_CARGO, ID_FUNCION) VALUES (10, 6);

-- =====================================================
-- INSERCIÓN EN TABLA: EMPLEADOS
-- Descripción: Crear 18 empleados con datos realistas
-- =====================================================

-- DEPARTAMENTO DE RECURSOS HUMANOS (ID: 1)
INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('María', 'González López', 'maria.gonzalez@empresa.com', '555-0101', TO_DATE('1985-03-15', 'YYYY-MM-DD'), TO_DATE('2018-01-10', 'YYYY-MM-DD'), 1, 5, 7500, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Carlos', 'Rodríguez Pérez', 'carlos.rodriguez@empresa.com', '555-0102', TO_DATE('1990-07-22', 'YYYY-MM-DD'), TO_DATE('2020-03-15', 'YYYY-MM-DD'), 1, 4, 2300, 'ACTIVO');

-- DEPARTAMENTO DE TECNOLOGÍA (ID: 2)
INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Ana', 'Martínez Silva', 'ana.martinez@empresa.com', '555-0201', TO_DATE('1988-11-30', 'YYYY-MM-DD'), TO_DATE('2017-05-20', 'YYYY-MM-DD'), 2, 3, 6200, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Luis', 'Fernández Torres', 'luis.fernandez@empresa.com', '555-0202', TO_DATE('1992-04-18', 'YYYY-MM-DD'), TO_DATE('2019-08-01', 'YYYY-MM-DD'), 2, 2, 3800, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Patricia', 'López Morales', 'patricia.lopez@empresa.com', '555-0203', TO_DATE('1995-09-12', 'YYYY-MM-DD'), TO_DATE('2022-02-14', 'YYYY-MM-DD'), 2, 1, 1600, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Roberto', 'Sánchez Ruiz', 'roberto.sanchez@empresa.com', '555-0204', TO_DATE('1994-12-05', 'YYYY-MM-DD'), TO_DATE('2021-06-20', 'YYYY-MM-DD'), 2, 1, 1800, 'ACTIVO');

-- DEPARTAMENTO DE VENTAS (ID: 3)
INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Laura', 'Ramírez Castro', 'laura.ramirez@empresa.com', '555-0301', TO_DATE('1987-06-25', 'YYYY-MM-DD'), TO_DATE('2016-09-10', 'YYYY-MM-DD'), 3, 7, 5500, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Diego', 'Herrera Vega', 'diego.herrera@empresa.com', '555-0302', TO_DATE('1993-02-14', 'YYYY-MM-DD'), TO_DATE('2021-01-05', 'YYYY-MM-DD'), 3, 6, 2000, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Carmen', 'Jiménez Ortiz', 'carmen.jimenez@empresa.com', '555-0303', TO_DATE('1996-08-30', 'YYYY-MM-DD'), TO_DATE('2023-03-20', 'YYYY-MM-DD'), 3, 6, 1700, 'ACTIVO');

-- DEPARTAMENTO DE MARKETING (ID: 4)
INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Jorge', 'Vargas Mendoza', 'jorge.vargas@empresa.com', '555-0401', TO_DATE('1991-05-17', 'YYYY-MM-DD'), TO_DATE('2019-11-12', 'YYYY-MM-DD'), 4, 8, 2800, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Sofía', 'Moreno Guzmán', 'sofia.moreno@empresa.com', '555-0402', TO_DATE('1994-10-08', 'YYYY-MM-DD'), TO_DATE('2021-07-18', 'YYYY-MM-DD'), 4, 8, 2400, 'ACTIVO');

-- DEPARTAMENTO DE FINANZAS (ID: 5)
INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Andrés', 'Castro Flores', 'andres.castro@empresa.com', '555-0501', TO_DATE('1986-01-20', 'YYYY-MM-DD'), TO_DATE('2015-04-22', 'YYYY-MM-DD'), 5, 9, 3200, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Isabella', 'Reyes Navarro', 'isabella.reyes@empresa.com', '555-0502', TO_DATE('1989-12-11', 'YYYY-MM-DD'), TO_DATE('2018-10-05', 'YYYY-MM-DD'), 5, 9, 2900, 'ACTIVO');

-- DEPARTAMENTO DE OPERACIONES (ID: 6)
INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Miguel', 'Delgado Romero', 'miguel.delgado@empresa.com', '555-0601', TO_DATE('1990-03-28', 'YYYY-MM-DD'), TO_DATE('2019-02-14', 'YYYY-MM-DD'), 6, 10, 3500, 'ACTIVO');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Valentina', 'Cruz Medina', 'valentina.cruz@empresa.com', '555-0602', TO_DATE('1992-07-16', 'YYYY-MM-DD'), TO_DATE('2020-09-08', 'YYYY-MM-DD'), 6, 10, 3100, 'ACTIVO');

-- Empleados adicionales en diferentes estados
INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Gabriel', 'Paredes Luna', 'gabriel.paredes@empresa.com', '555-0701', TO_DATE('1993-11-22', 'YYYY-MM-DD'), TO_DATE('2021-05-30', 'YYYY-MM-DD'), 2, 2, 3600, 'VACACIONES');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Daniela', 'Vázquez Campos', 'daniela.vazquez@empresa.com', '555-0702', TO_DATE('1995-04-03', 'YYYY-MM-DD'), TO_DATE('2022-08-15', 'YYYY-MM-DD'), 3, 6, 1900, 'LICENCIA');

INSERT INTO EMPLEADOS (NOMBRE, APELLIDO, EMAIL, TELEFONO, FECHA_NACIMIENTO, FECHA_CONTRATACION, ID_DEPARTAMENTO, ID_CARGO, SALARIO, ESTADO) 
VALUES ('Fernando', 'Rios Aguilar', 'fernando.rios@empresa.com', '555-0703', TO_DATE('1988-09-19', 'YYYY-MM-DD'), TO_DATE('2017-12-01', 'YYYY-MM-DD'), 4, 8, 2700, 'ACTIVO');

-- =====================================================
-- INSERCIÓN EN TABLA: HISTORIAL_SALARIOS
-- Descripción: Crear historial de cambios de salario para algunos empleados
-- =====================================================

-- Historial de María González (Directora RRHH)
INSERT INTO HISTORIAL_SALARIOS (ID_EMPLEADO, SALARIO_ANTERIOR, SALARIO_NUEVO, PORCENTAJE_CAMBIO, FECHA_CAMBIO, MOTIVO) 
VALUES (1, 6500, 7000, 7.69, TO_DATE('2022-01-15', 'YYYY-MM-DD'), 'Aumento por desempeño excepcional');

INSERT INTO HISTORIAL_SALARIOS (ID_EMPLEADO, SALARIO_ANTERIOR, SALARIO_NUEVO, PORCENTAJE_CAMBIO, FECHA_CAMBIO, MOTIVO) 
VALUES (1, 7000, 7500, 7.14, TO_DATE('2023-01-15', 'YYYY-MM-DD'), 'Aumento anual programado');

-- Historial de Ana Martínez (Gerente de Tecnología)
INSERT INTO HISTORIAL_SALARIOS (ID_EMPLEADO, SALARIO_ANTERIOR, SALARIO_NUEVO, PORCENTAJE_CAMBIO, FECHA_CAMBIO, MOTIVO) 
VALUES (3, 5500, 6000, 9.09, TO_DATE('2021-06-01', 'YYYY-MM-DD'), 'Promoción a Gerente');

INSERT INTO HISTORIAL_SALARIOS (ID_EMPLEADO, SALARIO_ANTERIOR, SALARIO_NUEVO, PORCENTAJE_CAMBIO, FECHA_CAMBIO, MOTIVO) 
VALUES (3, 6000, 6200, 3.33, TO_DATE('2023-06-01', 'YYYY-MM-DD'), 'Ajuste por inflación');

-- Historial de Luis Fernández (Desarrollador Senior)
INSERT INTO HISTORIAL_SALARIOS (ID_EMPLEADO, SALARIO_ANTERIOR, SALARIO_NUEVO, PORCENTAJE_CAMBIO, FECHA_CAMBIO, MOTIVO) 
VALUES (4, 3200, 3500, 9.38, TO_DATE('2022-08-01', 'YYYY-MM-DD'), 'Aumento por certificación');

INSERT INTO HISTORIAL_SALARIOS (ID_EMPLEADO, SALARIO_ANTERIOR, SALARIO_NUEVO, PORCENTAJE_CAMBIO, FECHA_CAMBIO, MOTIVO) 
VALUES (4, 3500, 3800, 8.57, TO_DATE('2023-08-01', 'YYYY-MM-DD'), 'Aumento por antigüedad');

-- =====================================================
-- COMMIT Y VERIFICACIÓN
-- =====================================================
COMMIT;

-- Consultas de verificación
SELECT 'DEPARTAMENTOS: ' || COUNT(*) || ' registros insertados' AS RESULTADO FROM DEPARTAMENTOS;
SELECT 'CARGOS: ' || COUNT(*) || ' registros insertados' AS RESULTADO FROM CARGOS;
SELECT 'FUNCIONES: ' || COUNT(*) || ' registros insertados' AS RESULTADO FROM FUNCIONES;
SELECT 'EMPLEADOS: ' || COUNT(*) || ' registros insertados' AS RESULTADO FROM EMPLEADOS;
SELECT 'CARGO_FUNCIONES: ' || COUNT(*) || ' registros insertados' AS RESULTADO FROM CARGO_FUNCIONES;
SELECT 'HISTORIAL_SALARIOS: ' || COUNT(*) || ' registros insertados' AS RESULTADO FROM HISTORIAL_SALARIOS;
