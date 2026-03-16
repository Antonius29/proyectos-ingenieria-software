// =====================================================
// DAO: FUNCIONES
// Capa de Datos - Acceso a datos de Funciones
// =====================================================

import {
  ejecutarSelect,
  ejecutarSelectPorId,
  ejecutarInsert,
  ejecutarUpdate,
  ejecutarDelete,
} from "./database-config.js"

/**
 * Obtiene todas las funciones
 * Simula: SELECT * FROM FUNCIONES
 */
export function obtenerTodasFunciones() {
  return ejecutarSelect("funciones")
}

/**
 * Obtiene una función por ID
 * Simula: SELECT * FROM FUNCIONES WHERE ID_FUNCION = ?
 */
export function obtenerFuncionPorId(id) {
  return ejecutarSelectPorId("funciones", "ID_FUNCION", id)
}

/**
 * Obtiene funciones por categoría
 * Simula: SELECT * FROM FUNCIONES WHERE CATEGORIA = ?
 */
export function obtenerFuncionesPorCategoria(categoria) {
  return ejecutarSelect("funciones", { CATEGORIA: categoria })
}

/**
 * Crea una nueva función
 * Simula: INSERT INTO FUNCIONES (...)
 */
export function crearFuncion(funcion) {
  // Validar nombre único
  const funciones = ejecutarSelect("funciones")
  const nombreExiste = funciones.some((f) => f.NOMBRE.toLowerCase() === funcion.NOMBRE.toLowerCase())

  if (nombreExiste) {
    throw new Error("Ya existe una función con ese nombre")
  }

  const nuevaFuncion = {
    ...funcion,
    FECHA_CREACION: new Date(),
  }

  return ejecutarInsert("funciones", nuevaFuncion)
}

/**
 * Actualiza una función
 * Simula: UPDATE FUNCIONES SET ... WHERE ID_FUNCION = ?
 */
export function actualizarFuncion(id, datos) {
  if (datos.NOMBRE) {
    const funciones = ejecutarSelect("funciones")
    const nombreExiste = funciones.some(
      (f) => f.NOMBRE.toLowerCase() === datos.NOMBRE.toLowerCase() && f.ID_FUNCION !== id,
    )

    if (nombreExiste) {
      throw new Error("Ya existe una función con ese nombre")
    }
  }

  return ejecutarUpdate("funciones", "ID_FUNCION", id, datos)
}

/**
 * Elimina una función
 * Simula: DELETE FROM FUNCIONES WHERE ID_FUNCION = ?
 */
export function eliminarFuncion(id) {
  return ejecutarDelete("funciones", "ID_FUNCION", id)
}
