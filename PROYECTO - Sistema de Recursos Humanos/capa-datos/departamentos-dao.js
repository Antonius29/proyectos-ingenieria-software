// =====================================================
// DAO: DEPARTAMENTOS
// Capa de Datos - Acceso a datos de Departamentos
// =====================================================

import {
  ejecutarSelect,
  ejecutarSelectPorId,
  ejecutarInsert,
  ejecutarUpdate,
  ejecutarDelete,
  DATABASE,
} from "./database-config.js"

/**
 * Obtiene todos los departamentos
 * Simula: SELECT * FROM DEPARTAMENTOS
 */
export function obtenerTodosDepartamentos() {
  return ejecutarSelect("departamentos")
}

/**
 * Obtiene departamentos activos
 * Simula: SELECT * FROM DEPARTAMENTOS WHERE ACTIVO = 'S'
 */
export function obtenerDepartamentosActivos() {
  return ejecutarSelect("departamentos", { ACTIVO: "S" })
}

/**
 * Obtiene un departamento por ID
 * Simula: SELECT * FROM DEPARTAMENTOS WHERE ID_DEPARTAMENTO = ?
 */
export function obtenerDepartamentoPorId(id) {
  return ejecutarSelectPorId("departamentos", "ID_DEPARTAMENTO", id)
}

/**
 * Crea un nuevo departamento
 * Simula: INSERT INTO DEPARTAMENTOS (...)
 */
export function crearDepartamento(departamento) {
  // Validar nombre único
  const departamentos = ejecutarSelect("departamentos")
  const nombreExiste = departamentos.some((d) => d.NOMBRE.toLowerCase() === departamento.NOMBRE.toLowerCase())

  if (nombreExiste) {
    throw new Error("Ya existe un departamento con ese nombre")
  }

  const nuevoDepartamento = {
    ...departamento,
    ACTIVO: departamento.ACTIVO || "S",
    FECHA_CREACION: new Date(),
  }

  return ejecutarInsert("departamentos", nuevoDepartamento)
}

/**
 * Actualiza un departamento
 * Simula: UPDATE DEPARTAMENTOS SET ... WHERE ID_DEPARTAMENTO = ?
 */
export function actualizarDepartamento(id, datos) {
  // Validar nombre único si se está actualizando
  if (datos.NOMBRE) {
    const departamentos = ejecutarSelect("departamentos")
    const nombreExiste = departamentos.some(
      (d) => d.NOMBRE.toLowerCase() === datos.NOMBRE.toLowerCase() && d.ID_DEPARTAMENTO !== id,
    )

    if (nombreExiste) {
      throw new Error("Ya existe un departamento con ese nombre")
    }
  }

  return ejecutarUpdate("departamentos", "ID_DEPARTAMENTO", id, datos)
}

/**
 * Elimina un departamento
 * Simula: DELETE FROM DEPARTAMENTOS WHERE ID_DEPARTAMENTO = ?
 */
export function eliminarDepartamento(id) {
  // Verificar que no tenga empleados activos
  const empleados = DATABASE.empleados.filter((e) => e.ID_DEPARTAMENTO === id && e.ESTADO === "ACTIVO")

  if (empleados.length > 0) {
    throw new Error(`No se puede eliminar. El departamento tiene ${empleados.length} empleados activos`)
  }

  return ejecutarDelete("departamentos", "ID_DEPARTAMENTO", id)
}

/**
 * Obtiene resumen estadístico de un departamento
 * Simula: Vista V_RESUMEN_DEPARTAMENTOS
 */
export function obtenerResumenDepartamento(id) {
  const departamento = obtenerDepartamentoPorId(id)
  if (!departamento) return null

  const empleados = DATABASE.empleados.filter((e) => e.ID_DEPARTAMENTO === id)
  const empleadosActivos = empleados.filter((e) => e.ESTADO === "ACTIVO")

  const salarios = empleadosActivos.map((e) => e.SALARIO)
  const salarioPromedio = salarios.length > 0 ? salarios.reduce((a, b) => a + b, 0) / salarios.length : 0
  const salarioMinimo = salarios.length > 0 ? Math.min(...salarios) : 0
  const salarioMaximo = salarios.length > 0 ? Math.max(...salarios) : 0
  const nominaMensual = salarios.reduce((a, b) => a + b, 0)

  return {
    ...departamento,
    TOTAL_EMPLEADOS: empleados.length,
    EMPLEADOS_ACTIVOS: empleadosActivos.length,
    SALARIO_PROMEDIO: Number.parseFloat(salarioPromedio.toFixed(2)),
    SALARIO_MINIMO: salarioMinimo,
    SALARIO_MAXIMO: salarioMaximo,
    NOMINA_MENSUAL: nominaMensual,
    NOMINA_ANUAL: nominaMensual * 12,
    PRESUPUESTO_DISPONIBLE: departamento.PRESUPUESTO - nominaMensual * 12,
  }
}

/**
 * Obtiene resumen de todos los departamentos
 */
export function obtenerResumenTodosDepartamentos() {
  const departamentos = obtenerTodosDepartamentos()
  return departamentos.map((d) => obtenerResumenDepartamento(d.ID_DEPARTAMENTO))
}
