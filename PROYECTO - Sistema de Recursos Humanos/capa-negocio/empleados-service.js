// =====================================================
// SERVICIO: EMPLEADOS
// Capa de Negocio - Lógica de negocio y validaciones
// =====================================================
// Descripción: Capa intermedia entre la presentación y los datos
// Contiene validaciones, reglas de negocio y transformaciones
// =====================================================

import * as EmpleadosDAO from "../capa-datos/empleados-dao.js"
import * as DepartamentosDAO from "../capa-datos/departamentos-dao.js"
import * as CargosDAO from "../capa-datos/cargos-dao.js"

/**
 * Obtiene todos los empleados con información completa
 * Aplica lógica de negocio y formatea datos
 */
export function obtenerEmpleados() {
  try {
    const empleados = EmpleadosDAO.obtenerEmpleadosCompleto()

    // Agregar cálculos adicionales
    return empleados.map((emp) => ({
      ...emp,
      EDAD: EmpleadosDAO.calcularEdad(emp.ID_EMPLEADO),
      ANTIGUEDAD: EmpleadosDAO.calcularAntiguedad(emp.ID_EMPLEADO),
      FECHA_NACIMIENTO_FORMATO: formatearFecha(emp.FECHA_NACIMIENTO),
      FECHA_CONTRATACION_FORMATO: formatearFecha(emp.FECHA_CONTRATACION),
      SALARIO_FORMATO: formatearMoneda(emp.SALARIO),
    }))
  } catch (error) {
    console.error("[v0] Error al obtener empleados:", error)
    throw new Error("No se pudieron cargar los empleados")
  }
}

/**
 * Obtiene solo empleados activos
 */
export function obtenerEmpleadosActivos() {
  try {
    const empleados = EmpleadosDAO.obtenerEmpleadosCompleto()
    return empleados
      .filter((emp) => emp.ESTADO === "ACTIVO")
      .map((emp) => ({
        ...emp,
        EDAD: EmpleadosDAO.calcularEdad(emp.ID_EMPLEADO),
        ANTIGUEDAD: EmpleadosDAO.calcularAntiguedad(emp.ID_EMPLEADO),
        SALARIO_FORMATO: formatearMoneda(emp.SALARIO),
      }))
  } catch (error) {
    console.error("[v0] Error al obtener empleados activos:", error)
    throw new Error("No se pudieron cargar los empleados activos")
  }
}

/**
 * Obtiene un empleado por ID con información completa
 */
export function obtenerEmpleadoPorId(id) {
  try {
    const empleado = EmpleadosDAO.obtenerEmpleadoPorId(id)
    if (!empleado) {
      throw new Error("Empleado no encontrado")
    }

    const departamento = DepartamentosDAO.obtenerDepartamentoPorId(empleado.ID_DEPARTAMENTO)
    const cargo = CargosDAO.obtenerCargoPorId(empleado.ID_CARGO)

    return {
      ...empleado,
      DEPARTAMENTO: departamento?.NOMBRE || "Sin asignar",
      CARGO: cargo?.NOMBRE || "Sin asignar",
      NIVEL: cargo?.NIVEL || null,
      EDAD: EmpleadosDAO.calcularEdad(id),
      ANTIGUEDAD: EmpleadosDAO.calcularAntiguedad(id),
      SALARIO_FORMATO: formatearMoneda(empleado.SALARIO),
    }
  } catch (error) {
    console.error("[v0] Error al obtener empleado:", error)
    throw error
  }
}

/**
 * Crea un nuevo empleado con validaciones de negocio
 */
export function crearEmpleado(datosEmpleado) {
  try {
    // Validaciones de negocio
    validarDatosEmpleado(datosEmpleado)

    // Validar que el departamento existe
    const departamento = DepartamentosDAO.obtenerDepartamentoPorId(datosEmpleado.ID_DEPARTAMENTO)
    if (!departamento) {
      throw new Error("El departamento seleccionado no existe")
    }

    // Validar que el cargo existe
    const cargo = CargosDAO.obtenerCargoPorId(datosEmpleado.ID_CARGO)
    if (!cargo) {
      throw new Error("El cargo seleccionado no existe")
    }

    // Validar que el salario esté dentro del rango del cargo
    validarSalarioEnRango(datosEmpleado.SALARIO, cargo)

    // Validar edad mínima (18 años)
    validarEdadMinima(datosEmpleado.FECHA_NACIMIENTO)

    // Crear empleado
    const nuevoEmpleado = EmpleadosDAO.crearEmpleado(datosEmpleado)

    return {
      success: true,
      message: "Empleado creado exitosamente",
      data: nuevoEmpleado,
    }
  } catch (error) {
    console.error("[v0] Error al crear empleado:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Actualiza un empleado existente
 */
export function actualizarEmpleado(id, datosActualizados) {
  try {
    // Verificar que el empleado existe
    const empleadoExistente = EmpleadosDAO.obtenerEmpleadoPorId(id)
    if (!empleadoExistente) {
      throw new Error("Empleado no encontrado")
    }

    // Validar datos actualizados
    if (datosActualizados.EMAIL) {
      validarEmail(datosActualizados.EMAIL)
    }

    if (datosActualizados.TELEFONO) {
      validarTelefono(datosActualizados.TELEFONO)
    }

    if (datosActualizados.ID_DEPARTAMENTO) {
      const departamento = DepartamentosDAO.obtenerDepartamentoPorId(datosActualizados.ID_DEPARTAMENTO)
      if (!departamento) {
        throw new Error("El departamento seleccionado no existe")
      }
    }

    if (datosActualizados.ID_CARGO) {
      const cargo = CargosDAO.obtenerCargoPorId(datosActualizados.ID_CARGO)
      if (!cargo) {
        throw new Error("El cargo seleccionado no existe")
      }

      // Validar salario con el nuevo cargo
      const salario = datosActualizados.SALARIO || empleadoExistente.SALARIO
      validarSalarioEnRango(salario, cargo)
    }

    // Actualizar empleado
    const actualizado = EmpleadosDAO.actualizarEmpleado(id, datosActualizados)

    if (!actualizado) {
      throw new Error("No se pudo actualizar el empleado")
    }

    return {
      success: true,
      message: "Empleado actualizado exitosamente",
      data: EmpleadosDAO.obtenerEmpleadoPorId(id),
    }
  } catch (error) {
    console.error("[v0] Error al actualizar empleado:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Actualiza el salario de un empleado con validaciones
 */
export function actualizarSalario(id, nuevoSalario, motivo) {
  try {
    const empleado = EmpleadosDAO.obtenerEmpleadoPorId(id)
    if (!empleado) {
      throw new Error("Empleado no encontrado")
    }

    // Validar que el nuevo salario sea válido
    if (nuevoSalario <= 0) {
      throw new Error("El salario debe ser mayor a 0")
    }

    // Obtener cargo y validar rango
    const cargo = CargosDAO.obtenerCargoPorId(empleado.ID_CARGO)
    validarSalarioEnRango(nuevoSalario, cargo)

    // Calcular porcentaje de cambio
    const porcentajeCambio = (((nuevoSalario - empleado.SALARIO) / empleado.SALARIO) * 100).toFixed(2)

    // Validar que el cambio no sea demasiado drástico (más del 50%)
    if (Math.abs(porcentajeCambio) > 50) {
      throw new Error(`El cambio de salario es muy drástico (${porcentajeCambio}%). Máximo permitido: 50%`)
    }

    // Actualizar salario
    EmpleadosDAO.actualizarSalario(id, nuevoSalario)

    return {
      success: true,
      message: `Salario actualizado exitosamente. Cambio: ${porcentajeCambio}%`,
      data: {
        salarioAnterior: empleado.SALARIO,
        salarioNuevo: nuevoSalario,
        porcentajeCambio: Number.parseFloat(porcentajeCambio),
      },
    }
  } catch (error) {
    console.error("[v0] Error al actualizar salario:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Elimina un empleado (borrado lógico)
 */
export function eliminarEmpleado(id) {
  try {
    const empleado = EmpleadosDAO.obtenerEmpleadoPorId(id)
    if (!empleado) {
      throw new Error("Empleado no encontrado")
    }

    if (empleado.ESTADO === "INACTIVO") {
      throw new Error("El empleado ya está inactivo")
    }

    const eliminado = EmpleadosDAO.eliminarEmpleado(id)

    if (!eliminado) {
      throw new Error("No se pudo eliminar el empleado")
    }

    return {
      success: true,
      message: "Empleado dado de baja exitosamente",
      data: null,
    }
  } catch (error) {
    console.error("[v0] Error al eliminar empleado:", error)
    return {
      success: false,
      message: error.message,
      data: null,
    }
  }
}

/**
 * Busca empleados por texto
 */
export function buscarEmpleados(texto) {
  try {
    if (!texto || texto.trim().length < 2) {
      return obtenerEmpleados()
    }

    const empleados = EmpleadosDAO.buscarEmpleados(texto)

    return empleados.map((emp) => {
      const departamento = DepartamentosDAO.obtenerDepartamentoPorId(emp.ID_DEPARTAMENTO)
      const cargo = CargosDAO.obtenerCargoPorId(emp.ID_CARGO)

      return {
        ...emp,
        DEPARTAMENTO: departamento?.NOMBRE || "Sin asignar",
        CARGO: cargo?.NOMBRE || "Sin asignar",
        SALARIO_FORMATO: formatearMoneda(emp.SALARIO),
      }
    })
  } catch (error) {
    console.error("[v0] Error al buscar empleados:", error)
    throw new Error("Error en la búsqueda")
  }
}

/**
 * Obtiene empleados filtrados por departamento
 */
export function obtenerEmpleadosPorDepartamento(idDepartamento) {
  try {
    const empleados = EmpleadosDAO.obtenerEmpleadosPorDepartamento(idDepartamento)
    const departamento = DepartamentosDAO.obtenerDepartamentoPorId(idDepartamento)

    return empleados.map((emp) => {
      const cargo = CargosDAO.obtenerCargoPorId(emp.ID_CARGO)
      return {
        ...emp,
        DEPARTAMENTO: departamento?.NOMBRE || "Sin asignar",
        CARGO: cargo?.NOMBRE || "Sin asignar",
        SALARIO_FORMATO: formatearMoneda(emp.SALARIO),
      }
    })
  } catch (error) {
    console.error("[v0] Error al obtener empleados por departamento:", error)
    throw new Error("Error al filtrar por departamento")
  }
}

// =====================================================
// FUNCIONES DE VALIDACIÓN
// =====================================================

function validarDatosEmpleado(datos) {
  if (!datos.NOMBRE || datos.NOMBRE.trim().length < 2) {
    throw new Error("El nombre debe tener al menos 2 caracteres")
  }

  if (!datos.APELLIDO || datos.APELLIDO.trim().length < 2) {
    throw new Error("El apellido debe tener al menos 2 caracteres")
  }

  if (!datos.EMAIL) {
    throw new Error("El email es obligatorio")
  }

  validarEmail(datos.EMAIL)

  if (datos.TELEFONO) {
    validarTelefono(datos.TELEFONO)
  }

  if (!datos.FECHA_NACIMIENTO) {
    throw new Error("La fecha de nacimiento es obligatoria")
  }

  if (!datos.ID_DEPARTAMENTO) {
    throw new Error("Debe seleccionar un departamento")
  }

  if (!datos.ID_CARGO) {
    throw new Error("Debe seleccionar un cargo")
  }

  if (!datos.SALARIO || datos.SALARIO <= 0) {
    throw new Error("El salario debe ser mayor a 0")
  }
}

function validarEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!regex.test(email)) {
    throw new Error("El formato del email no es válido")
  }
}

function validarTelefono(telefono) {
  const regex = /^[0-9\-+$$$$\s]+$/
  if (!regex.test(telefono)) {
    throw new Error("El formato del teléfono no es válido")
  }
}

function validarSalarioEnRango(salario, cargo) {
  if (salario < cargo.SALARIO_MINIMO || salario > cargo.SALARIO_MAXIMO) {
    throw new Error(
      `El salario debe estar entre ${formatearMoneda(cargo.SALARIO_MINIMO)} y ${formatearMoneda(cargo.SALARIO_MAXIMO)} para el cargo ${cargo.NOMBRE}`,
    )
  }
}

function validarEdadMinima(fechaNacimiento) {
  const hoy = new Date()
  const nacimiento = new Date(fechaNacimiento)
  let edad = hoy.getFullYear() - nacimiento.getFullYear()
  const mes = hoy.getMonth() - nacimiento.getMonth()

  if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
    edad--
  }

  if (edad < 18) {
    throw new Error("El empleado debe ser mayor de 18 años")
  }
}

// =====================================================
// FUNCIONES DE FORMATO
// =====================================================

function formatearFecha(fecha) {
  if (!fecha) return ""
  const d = new Date(fecha)
  const dia = String(d.getDate()).padStart(2, "0")
  const mes = String(d.getMonth() + 1).padStart(2, "0")
  const año = d.getFullYear()
  return `${dia}/${mes}/${año}`
}

function formatearMoneda(valor) {
  return new Intl.NumberFormat("es-MX", {
    style: "currency",
    currency: "USD",
  }).format(valor)
}
