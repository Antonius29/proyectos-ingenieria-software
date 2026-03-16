// =====================================================
// SERVICIO: CARGOS
// Capa de Negocio - Lógica de negocio para Cargos
// =====================================================

import * as CargosDAO from "../capa-datos/cargos-dao.js"

/**
 * Obtiene todos los cargos
 */
export function obtenerCargos() {
  try {
    return CargosDAO.obtenerTodosCargos()
  } catch (error) {
    console.error("[v0] Error al obtener cargos:", error)
    throw new Error("No se pudieron cargar los cargos")
  }
}

/**
 * Obtiene cargos ordenados por nivel
 */
export function obtenerCargosOrdenados() {
  try {
    const cargos = CargosDAO.obtenerTodosCargos()

    const ordenNiveles = {
      DIRECTOR: 1,
      GERENTE: 2,
      SENIOR: 3,
      "SEMI-SENIOR": 4,
      JUNIOR: 5,
    }

    return cargos.sort((a, b) => {
      return (ordenNiveles[a.NIVEL] || 99) - (ordenNiveles[b.NIVEL] || 99)
    })
  } catch (error) {
    console.error("[v0] Error al obtener cargos ordenados:", error)
    throw new Error("No se pudieron cargar los cargos")
  }
}

/**
 * Obtiene un cargo por ID
 */
export function obtenerCargoPorId(id) {
  try {
    const cargo = CargosDAO.obtenerCargoPorId(id)
    if (!cargo) {
      throw new Error("Cargo no encontrado")
    }
    return cargo
  } catch (error) {
    console.error("[v0] Error al obtener cargo:", error)
    throw error
  }
}

/**
 * Crea un nuevo cargo
 */
export function crearCargo(datosCargo) {
  try {
    validarDatosCargo(datosCargo)

    const nuevoCargo = CargosDAO.crearCargo(datosCargo)

    return {
      success: true,
      message: "Cargo creado exitosamente",
      data: nuevoCargo,
    }
  } catch (error) {
    console.error("[v0] Error al crear cargo:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Actualiza un cargo
 */
export function actualizarCargo(id, datosActualizados) {
  try {
    const cargo = CargosDAO.obtenerCargoPorId(id)
    if (!cargo) {
      throw new Error("Cargo no encontrado")
    }

    // Validar datos actualizados
    if (datosActualizados.NOMBRE) {
      validarNombre(datosActualizados.NOMBRE)
    }

    if (datosActualizados.SALARIO_MINIMO !== undefined && datosActualizados.SALARIO_MAXIMO !== undefined) {
      validarRangoSalarios(datosActualizados.SALARIO_MINIMO, datosActualizados.SALARIO_MAXIMO)
    }

    const actualizado = CargosDAO.actualizarCargo(id, datosActualizados)

    if (!actualizado) {
      throw new Error("No se pudo actualizar el cargo")
    }

    return {
      success: true,
      message: "Cargo actualizado exitosamente",
      data: CargosDAO.obtenerCargoPorId(id),
    }
  } catch (error) {
    console.error("[v0] Error al actualizar cargo:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Elimina un cargo
 */
export function eliminarCargo(id) {
  try {
    const cargo = CargosDAO.obtenerCargoPorId(id)
    if (!cargo) {
      throw new Error("Cargo no encontrado")
    }

    CargosDAO.eliminarCargo(id)

    return {
      success: true,
      message: "Cargo eliminado exitosamente",
      data: null,
    }
  } catch (error) {
    console.error("[v0] Error al eliminar cargo:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Obtiene resumen de cargos con estadísticas
 */
export function obtenerResumenCargos() {
  try {
    const resumen = CargosDAO.obtenerResumenTodosCargos()

    return resumen.map((cargo) => ({
      ...cargo,
      SALARIO_MINIMO_FORMATO: formatearMoneda(cargo.SALARIO_MINIMO),
      SALARIO_MAXIMO_FORMATO: formatearMoneda(cargo.SALARIO_MAXIMO),
      SALARIO_PROMEDIO_REAL_FORMATO: formatearMoneda(cargo.SALARIO_PROMEDIO_REAL),
      PORCENTAJE_OCUPACION:
        cargo.TOTAL_EMPLEADOS > 0 ? ((cargo.SALARIO_PROMEDIO_REAL / cargo.SALARIO_MAXIMO) * 100).toFixed(1) : 0,
    }))
  } catch (error) {
    console.error("[v0] Error al obtener resumen de cargos:", error)
    throw new Error("No se pudo generar el resumen")
  }
}

// Validaciones
function validarDatosCargo(datos) {
  if (!datos.NOMBRE || datos.NOMBRE.trim().length < 3) {
    throw new Error("El nombre debe tener al menos 3 caracteres")
  }

  if (!datos.NIVEL) {
    throw new Error("Debe seleccionar un nivel")
  }

  const nivelesValidos = ["JUNIOR", "SEMI-SENIOR", "SENIOR", "GERENTE", "DIRECTOR"]
  if (!nivelesValidos.includes(datos.NIVEL)) {
    throw new Error("El nivel seleccionado no es válido")
  }

  if (!datos.SALARIO_MINIMO || datos.SALARIO_MINIMO <= 0) {
    throw new Error("El salario mínimo debe ser mayor a 0")
  }

  if (!datos.SALARIO_MAXIMO || datos.SALARIO_MAXIMO <= 0) {
    throw new Error("El salario máximo debe ser mayor a 0")
  }

  validarRangoSalarios(datos.SALARIO_MINIMO, datos.SALARIO_MAXIMO)
}

function validarNombre(nombre) {
  if (!nombre || nombre.trim().length < 3) {
    throw new Error("El nombre debe tener al menos 3 caracteres")
  }
}

function validarRangoSalarios(minimo, maximo) {
  if (maximo <= minimo) {
    throw new Error("El salario máximo debe ser mayor que el mínimo")
  }

  if (maximo - minimo < 500) {
    throw new Error("El rango salarial debe ser de al menos $500")
  }
}

function formatearMoneda(valor) {
  return new Intl.NumberFormat("es-MX", {
    style: "currency",
    currency: "USD",
  }).format(valor)
}
