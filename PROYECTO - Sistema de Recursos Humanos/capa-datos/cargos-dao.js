// =====================================================
// DAO: CARGOS
// Capa de Datos - Acceso a datos de Cargos
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
 * Obtiene todos los cargos
 * Simula: SELECT * FROM CARGOS
 */
export function obtenerTodosCargos() {
  return ejecutarSelect("cargos")
}

/**
 * Obtiene un cargo por ID
 * Simula: SELECT * FROM CARGOS WHERE ID_CARGO = ?
 */
export function obtenerCargoPorId(id) {
  return ejecutarSelectPorId("cargos", "ID_CARGO", id)
}

/**
 * Obtiene cargos por nivel jerárquico
 * Simula: SELECT * FROM CARGOS WHERE NIVEL = ?
 */
export function obtenerCargosPorNivel(nivel) {
  return ejecutarSelect("cargos", { NIVEL: nivel })
}

/**
 * Crea un nuevo cargo
 * Simula: INSERT INTO CARGOS (...)
 */
export function crearCargo(cargo) {
  // Validar nombre único
  const cargos = ejecutarSelect("cargos")
  const nombreExiste = cargos.some((c) => c.NOMBRE.toLowerCase() === cargo.NOMBRE.toLowerCase())

  if (nombreExiste) {
    throw new Error("Ya existe un cargo con ese nombre")
  }

  // Validar rango de salarios
  if (cargo.SALARIO_MAXIMO <= cargo.SALARIO_MINIMO) {
    throw new Error("El salario máximo debe ser mayor que el mínimo")
  }

  const nuevoCargo = {
    ...cargo,
    FECHA_CREACION: new Date(),
  }

  return ejecutarInsert("cargos", nuevoCargo)
}

/**
 * Actualiza un cargo
 * Simula: UPDATE CARGOS SET ... WHERE ID_CARGO = ?
 */
export function actualizarCargo(id, datos) {
  // Validar nombre único
  if (datos.NOMBRE) {
    const cargos = ejecutarSelect("cargos")
    const nombreExiste = cargos.some((c) => c.NOMBRE.toLowerCase() === datos.NOMBRE.toLowerCase() && c.ID_CARGO !== id)

    if (nombreExiste) {
      throw new Error("Ya existe un cargo con ese nombre")
    }
  }

  // Validar rango de salarios
  if (datos.SALARIO_MINIMO && datos.SALARIO_MAXIMO) {
    if (datos.SALARIO_MAXIMO <= datos.SALARIO_MINIMO) {
      throw new Error("El salario máximo debe ser mayor que el mínimo")
    }
  }

  return ejecutarUpdate("cargos", "ID_CARGO", id, datos)
}

/**
 * Elimina un cargo
 * Simula: DELETE FROM CARGOS WHERE ID_CARGO = ?
 */
export function eliminarCargo(id) {
  // Verificar que no tenga empleados asignados
  const empleados = DATABASE.empleados.filter((e) => e.ID_CARGO === id && e.ESTADO === "ACTIVO")

  if (empleados.length > 0) {
    throw new Error(`No se puede eliminar. Hay ${empleados.length} empleados con este cargo`)
  }

  return ejecutarDelete("cargos", "ID_CARGO", id)
}

/**
 * Obtiene resumen de un cargo
 * Simula: Vista V_RESUMEN_CARGOS
 */
export function obtenerResumenCargo(id) {
  const cargo = obtenerCargoPorId(id)
  if (!cargo) return null

  const empleados = DATABASE.empleados.filter((e) => e.ID_CARGO === id && e.ESTADO === "ACTIVO")

  const salarios = empleados.map((e) => e.SALARIO)
  const salarioPromedioReal = salarios.length > 0 ? salarios.reduce((a, b) => a + b, 0) / salarios.length : 0

  return {
    ...cargo,
    TOTAL_EMPLEADOS: empleados.length,
    SALARIO_PROMEDIO_REAL: Number.parseFloat(salarioPromedioReal.toFixed(2)),
  }
}

/**
 * Obtiene resumen de todos los cargos
 */
export function obtenerResumenTodosCargos() {
  const cargos = obtenerTodosCargos()
  return cargos.map((c) => obtenerResumenCargo(c.ID_CARGO))
}
