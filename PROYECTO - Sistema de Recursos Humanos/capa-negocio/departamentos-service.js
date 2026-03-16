// =====================================================
// SERVICIO: DEPARTAMENTOS
// Capa de Negocio - Lógica de negocio para Departamentos
// =====================================================

import * as DepartamentosDAO from "../capa-datos/departamentos-dao.js"

/**
 * Obtiene todos los departamentos
 */
export function obtenerDepartamentos() {
  try {
    return DepartamentosDAO.obtenerTodosDepartamentos()
  } catch (error) {
    console.error("[v0] Error al obtener departamentos:", error)
    throw new Error("No se pudieron cargar los departamentos")
  }
}

/**
 * Obtiene solo departamentos activos
 */
export function obtenerDepartamentosActivos() {
  try {
    return DepartamentosDAO.obtenerDepartamentosActivos()
  } catch (error) {
    console.error("[v0] Error al obtener departamentos activos:", error)
    throw new Error("No se pudieron cargar los departamentos activos")
  }
}

/**
 * Obtiene un departamento por ID
 */
export function obtenerDepartamentoPorId(id) {
  try {
    const departamento = DepartamentosDAO.obtenerDepartamentoPorId(id)
    if (!departamento) {
      throw new Error("Departamento no encontrado")
    }
    return departamento
  } catch (error) {
    console.error("[v0] Error al obtener departamento:", error)
    throw error
  }
}

/**
 * Crea un nuevo departamento
 */
export function crearDepartamento(datosDepartamento) {
  try {
    // Validaciones
    validarDatosDepartamento(datosDepartamento)

    const nuevoDepartamento = DepartamentosDAO.crearDepartamento(datosDepartamento)

    return {
      success: true,
      message: "Departamento creado exitosamente",
      data: nuevoDepartamento,
    }
  } catch (error) {
    console.error("[v0] Error al crear departamento:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Actualiza un departamento
 */
export function actualizarDepartamento(id, datosActualizados) {
  try {
    const departamento = DepartamentosDAO.obtenerDepartamentoPorId(id)
    if (!departamento) {
      throw new Error("Departamento no encontrado")
    }

    // Validar datos
    if (datosActualizados.NOMBRE) {
      validarNombre(datosActualizados.NOMBRE)
    }

    if (datosActualizados.PRESUPUESTO !== undefined) {
      validarPresupuesto(datosActualizados.PRESUPUESTO)
    }

    const actualizado = DepartamentosDAO.actualizarDepartamento(id, datosActualizados)

    if (!actualizado) {
      throw new Error("No se pudo actualizar el departamento")
    }

    return {
      success: true,
      message: "Departamento actualizado exitosamente",
      data: DepartamentosDAO.obtenerDepartamentoPorId(id),
    }
  } catch (error) {
    console.error("[v0] Error al actualizar departamento:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Elimina un departamento
 */
export function eliminarDepartamento(id) {
  try {
    const departamento = DepartamentosDAO.obtenerDepartamentoPorId(id)
    if (!departamento) {
      throw new Error("Departamento no encontrado")
    }

    DepartamentosDAO.eliminarDepartamento(id)

    return {
      success: true,
      message: "Departamento eliminado exitosamente",
      data: null,
    }
  } catch (error) {
    console.error("[v0] Error al eliminar departamento:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Obtiene resumen estadístico de departamentos
 */
export function obtenerResumenDepartamentos() {
  try {
    const resumen = DepartamentosDAO.obtenerResumenTodosDepartamentos()

    return resumen.map((dep) => ({
      ...dep,
      SALARIO_PROMEDIO_FORMATO: formatearMoneda(dep.SALARIO_PROMEDIO),
      NOMINA_MENSUAL_FORMATO: formatearMoneda(dep.NOMINA_MENSUAL),
      NOMINA_ANUAL_FORMATO: formatearMoneda(dep.NOMINA_ANUAL),
      PRESUPUESTO_FORMATO: formatearMoneda(dep.PRESUPUESTO),
      PRESUPUESTO_DISPONIBLE_FORMATO: formatearMoneda(dep.PRESUPUESTO_DISPONIBLE),
      PORCENTAJE_USADO: ((dep.NOMINA_ANUAL / dep.PRESUPUESTO) * 100).toFixed(1),
    }))
  } catch (error) {
    console.error("[v0] Error al obtener resumen de departamentos:", error)
    throw new Error("No se pudo generar el resumen")
  }
}

// Validaciones
function validarDatosDepartamento(datos) {
  if (!datos.NOMBRE || datos.NOMBRE.trim().length < 3) {
    throw new Error("El nombre debe tener al menos 3 caracteres")
  }

  if (datos.PRESUPUESTO !== undefined) {
    validarPresupuesto(datos.PRESUPUESTO)
  }
}

function validarNombre(nombre) {
  if (!nombre || nombre.trim().length < 3) {
    throw new Error("El nombre debe tener al menos 3 caracteres")
  }
}

function validarPresupuesto(presupuesto) {
  if (presupuesto < 0) {
    throw new Error("El presupuesto no puede ser negativo")
  }
}

function formatearMoneda(valor) {
  return new Intl.NumberFormat("es-MX", {
    style: "currency",
    currency: "USD",
  }).format(valor)
}
