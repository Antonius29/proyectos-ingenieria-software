-- =====================================================
-- SCRIPT 5: TRIGGERS (DISPARADORES)
-- Sistema de Recursos Humanos
-- =====================================================
-- Descripción: Triggers para automatizar tareas y mantener
-- la integridad de los datos
-- =====================================================

-- =====================================================
-- TRIGGER: TRG_AUDITORIA_SALARIO
-- Descripción: Registra automáticamente en HISTORIAL_SALARIOS
--              cada vez que se actualiza el salario de un empleado
-- Tipo: AFTER UPDATE
-- Tabla: EMPLEADOS
-- =====================================================
CREATE OR REPLACE TRIGGER TRG_AUDITORIA_SALARIO
AFTER UPDATE OF SALARIO ON EMPLEADOS
FOR EACH ROW
WHEN (OLD.SALARIO != NEW.SALARIO)
DECLARE
    v_porcentaje NUMBER;
BEGIN
    -- Calcular porcentaje de cambio
    v_porcentaje := ROUND(((:NEW.SALARIO - :OLD.SALARIO) / :OLD.SALARIO) * 100, 2);
    
    -- Insertar registro en historial
    INSERT INTO HISTORIAL_SALARIOS (
        ID_EMPLEADO,
        SALARIO_ANTERIOR,
        SALARIO_NUEVO,
        PORCENTAJE_CAMBIO,
        FECHA_CAMBIO,
        MOTIVO,
        USUARIO_MODIFICACION
    ) VALUES (
        :NEW.ID_EMPLEADO,
        :OLD.SALARIO,
        :NEW.SALARIO,
        v_porcentaje,
        SYSDATE,
        'Actualización automática',
        USER
    );
END;
/

-- =====================================================
-- TRIGGER: TRG_VALIDAR_EMAIL
-- Descripción: Valida el formato del email antes de insertar/actualizar
-- Tipo: BEFORE INSERT OR UPDATE
-- Tabla: EMPLEADOS
-- =====================================================
CREATE OR REPLACE TRIGGER TRG_VALIDAR_EMAIL
BEFORE INSERT OR UPDATE OF EMAIL ON EMPLEADOS
FOR EACH ROW
DECLARE
    v_posicion NUMBER;
BEGIN
    -- Validar que contenga '@'
    v_posicion := INSTR(:NEW.EMAIL, '@');
    
    IF v_posicion = 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Email inválido: debe contener @');
    END IF;
    
    -- Convertir email a minúsculas
    :NEW.EMAIL := LOWER(:NEW.EMAIL);
END;
/

-- =====================================================
-- TRIGGER: TRG_FECHA_ACTUALIZACION
-- Descripción: Actualiza automáticamente FECHA_ACTUALIZACION
-- Tipo: BEFORE UPDATE
-- Tabla: EMPLEADOS
-- =====================================================
CREATE OR REPLACE TRIGGER TRG_FECHA_ACTUALIZACION
BEFORE UPDATE ON EMPLEADOS
FOR EACH ROW
BEGIN
    :NEW.FECHA_ACTUALIZACION := SYSDATE;
END;
/

-- =====================================================
-- TRIGGER: TRG_VALIDAR_SALARIO_RANGO
-- Descripción: Valida que el salario esté dentro del rango del cargo
-- Tipo: BEFORE INSERT OR UPDATE
-- Tabla: EMPLEADOS
-- =====================================================
CREATE OR REPLACE TRIGGER TRG_VALIDAR_SALARIO_RANGO
BEFORE INSERT OR UPDATE OF SALARIO, ID_CARGO ON EMPLEADOS
FOR EACH ROW
DECLARE
    v_salario_min NUMBER;
    v_salario_max NUMBER;
BEGIN
    -- Obtener rango de salario del cargo
    SELECT SALARIO_MINIMO, SALARIO_MAXIMO 
    INTO v_salario_min, v_salario_max
    FROM CARGOS 
    WHERE ID_CARGO = :NEW.ID_CARGO;
    
    -- Validar que el salario esté en el rango
    IF :NEW.SALARIO < v_salario_min OR :NEW.SALARIO > v_salario_max THEN
        RAISE_APPLICATION_ERROR(-20002, 
            'Salario fuera del rango permitido para el cargo (' || 
            v_salario_min || ' - ' || v_salario_max || ')');
    END IF;
END;
/

-- =====================================================
-- TRIGGER: TRG_IMPEDIR_ELIMINAR_DEPARTAMENTO
-- Descripción: Impide eliminar un departamento si tiene empleados activos
-- Tipo: BEFORE DELETE
-- Tabla: DEPARTAMENTOS
-- =====================================================
CREATE OR REPLACE TRIGGER TRG_IMPEDIR_ELIMINAR_DEPARTAMENTO
BEFORE DELETE ON DEPARTAMENTOS
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Contar empleados activos en el departamento
    SELECT COUNT(*) INTO v_count
    FROM EMPLEADOS
    WHERE ID_DEPARTAMENTO = :OLD.ID_DEPARTAMENTO
    AND ESTADO = 'ACTIVO';
    
    -- Si hay empleados activos, impedir eliminación
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20003, 
            'No se puede eliminar el departamento. Tiene ' || v_count || ' empleados activos.');
    END IF;
END;
/

-- =====================================================
-- TRIGGER: TRG_IMPEDIR_ELIMINAR_CARGO
-- Descripción: Impide eliminar un cargo si hay empleados asignados
-- Tipo: BEFORE DELETE
-- Tabla: CARGOS
-- =====================================================
CREATE OR REPLACE TRIGGER TRG_IMPEDIR_ELIMINAR_CARGO
BEFORE DELETE ON CARGOS
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    -- Contar empleados con este cargo
    SELECT COUNT(*) INTO v_count
    FROM EMPLEADOS
    WHERE ID_CARGO = :OLD.ID_CARGO
    AND ESTADO = 'ACTIVO';
    
    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20004, 
            'No se puede eliminar el cargo. Hay ' || v_count || ' empleados con este cargo.');
    END IF;
END;
/

-- =====================================================
-- TRIGGER: TRG_LOG_CAMBIOS_EMPLEADO
-- Descripción: Registra en una tabla de log los cambios en empleados
--              (Primero hay que crear la tabla de log)
-- =====================================================

-- Crear tabla de auditoría de cambios
CREATE TABLE LOG_CAMBIOS_EMPLEADOS (
    ID_LOG NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    ID_EMPLEADO NUMBER,
    OPERACION VARCHAR2(10),
    CAMPO_MODIFICADO VARCHAR2(50),
    VALOR_ANTERIOR VARCHAR2(500),
    VALOR_NUEVO VARCHAR2(500),
    USUARIO VARCHAR2(50),
    FECHA_CAMBIO DATE DEFAULT SYSDATE
);

-- Trigger para registrar cambios
CREATE OR REPLACE TRIGGER TRG_LOG_CAMBIOS_EMPLEADO
AFTER UPDATE ON EMPLEADOS
FOR EACH ROW
BEGIN
    -- Registrar cambio de nombre
    IF :OLD.NOMBRE != :NEW.NOMBRE THEN
        INSERT INTO LOG_CAMBIOS_EMPLEADOS 
        (ID_EMPLEADO, OPERACION, CAMPO_MODIFICADO, VALOR_ANTERIOR, VALOR_NUEVO, USUARIO)
        VALUES (:NEW.ID_EMPLEADO, 'UPDATE', 'NOMBRE', :OLD.NOMBRE, :NEW.NOMBRE, USER);
    END IF;
    
    -- Registrar cambio de departamento
    IF :OLD.ID_DEPARTAMENTO != :NEW.ID_DEPARTAMENTO THEN
        INSERT INTO LOG_CAMBIOS_EMPLEADOS 
        (ID_EMPLEADO, OPERACION, CAMPO_MODIFICADO, VALOR_ANTERIOR, VALOR_NUEVO, USUARIO)
        VALUES (:NEW.ID_EMPLEADO, 'UPDATE', 'DEPARTAMENTO', 
                TO_CHAR(:OLD.ID_DEPARTAMENTO), TO_CHAR(:NEW.ID_DEPARTAMENTO), USER);
    END IF;
    
    -- Registrar cambio de estado
    IF :OLD.ESTADO != :NEW.ESTADO THEN
        INSERT INTO LOG_CAMBIOS_EMPLEADOS 
        (ID_EMPLEADO, OPERACION, CAMPO_MODIFICADO, VALOR_ANTERIOR, VALOR_NUEVO, USUARIO)
        VALUES (:NEW.ID_EMPLEADO, 'UPDATE', 'ESTADO', :OLD.ESTADO, :NEW.ESTADO, USER);
    END IF;
END;
/

-- =====================================================
-- VERIFICACIÓN DE TRIGGERS CREADOS
-- =====================================================
SELECT 'Triggers creados exitosamente' AS RESULTADO FROM DUAL;

-- Listar triggers creados
SELECT TRIGGER_NAME, TABLE_NAME, STATUS, TRIGGERING_EVENT
FROM USER_TRIGGERS
WHERE TRIGGER_NAME LIKE 'TRG_%'
ORDER BY TRIGGER_NAME;

-- =====================================================
-- PRUEBA DE TRIGGERS
-- =====================================================

-- Prueba 1: Actualizar salario (debe crear registro en historial automáticamente)
DECLARE
    v_resultado VARCHAR2(200);
BEGIN
    SP_ACTUALIZAR_SALARIO(
        p_id_empleado => 5,
        p_salario_nuevo => 1750,
        p_motivo => 'Prueba de trigger',
        p_resultado => v_resultado
    );
    DBMS_OUTPUT.PUT_LINE(v_resultado);
END;
/

-- Verificar que se creó el registro en historial
SELECT * FROM HISTORIAL_SALARIOS 
WHERE ID_EMPLEADO = 5 
ORDER BY FECHA_CAMBIO DESC;
