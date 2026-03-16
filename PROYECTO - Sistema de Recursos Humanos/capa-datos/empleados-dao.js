// =====================================================
// DAO: EMPLEADOS
// Capa de Datos - Acceso a datos de Empleados
// =====================================================
// Descripción: Operaciones CRUD para la tabla EMPLEADOS
// Simula las operaciones que se ejecutarían en Oracle SQL Developer
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
 * Obtiene todos los empleados
 * Simula: SELECT * FROM EMPLEADOS
 * @returns {Array} Lista de empleados
 */
export function obtenerTodosEmpleados() {
  return ejecutarSelect("empleados")
}

/**
 * Obtiene empleados activos solamente
 * Simula: SELECT * FROM EMPLEADOS WHERE ESTADO = 'ACTIVO'
 * @returns {Array} Lista de empleados activos
 */
export function obtenerEmpleadosActivos() {
  return ejecutarSelect("empleados", { ESTADO: "ACTIVO" })
}

/**
 * Obtiene un empleado por ID
 * Simula: SELECT * FROM EMPLEADOS WHERE ID_EMPLEADO = ?
 * @param {number} id - ID del empleado
 * @returns {object|null} Empleado o null si no existe
 */
export function obtenerEmpleadoPorId(id) {
  return ejecutarSelectPorId("empleados", "ID_EMPLEADO", id)
}

/**
 * Obtiene empleados por departamento
 * Simula: SELECT * FROM EMPLEADOS WHERE ID_DEPARTAMENTO = ?
 * @param {number} idDepartamento - ID del departamento
 * @returns {Array} Lista de empleados del departamento
 */
export function obtenerEmpleadosPorDepartamento(idDepartamento) {
  return ejecutarSelect("empleados", { ID_DEPARTAMENTO: idDepartamento })
}

/**
 * Obtiene empleados por cargo
 * Simula: SELECT * FROM EMPLEADOS WHERE ID_CARGO = ?
 * @param {number} idCargo - ID del cargo
 * @returns {Array} Lista de empleados con ese cargo
 */
export function obtenerEmpleadosPorCargo(idCargo) {
  return ejecutarSelect("empleados", { ID_CARGO: idCargo })
}

/**
 * Busca empleados por nombre o apellido
 * Simula: SELECT * FROM EMPLEADOS WHERE NOMBRE LIKE ? OR APELLIDO LIKE ?
 * @param {string} texto - Texto a buscar
 * @returns {Array} Lista de empleados que coinciden
 */
export function buscarEmpleados(texto) {
  const empleados = ejecutarSelect("empleados")
  const textoLower = texto.toLowerCase()

  return empleados.filter(
    (emp) =>
      emp.NOMBRE.toLowerCase().includes(textoLower) ||
      emp.APELLIDO.toLowerCase().includes(textoLower) ||
      emp.EMAIL.toLowerCase().includes(textoLower),
  )
}

/**
 * Crea un nuevo empleado
 * Simula: INSERT INTO EMPLEADOS (...)
 * @param {object} empleado - Datos del empleado
 * @returns {object} Empleado creado con ID
 */
export function crearEmpleado(empleado) {
  // Validar campos requeridos
  if (!empleado.NOMBRE || !empleado.APELLIDO || !empleado.EMAIL) {
    throw new Error("Nombre, apellido y email son obligatorios")
  }

  // Verificar email único
  const empleados = ejecutarSelect("empleados")
  const emailExiste = empleados.some(
    (e) => e.EMAIL.toLowerCase() === empleado.EMAIL.toLowerCase() && e.ID_EMPLEADO !== empleado.ID_EMPLEADO,
  )

  if (emailExiste) {
    throw new Error("El email ya está registrado")
  }

  // Establecer valores por defecto
  const nuevoEmpleado = {
    ...empleado,
    ESTADO: empleado.ESTADO || "ACTIVO",
    FECHA_CONTRATACION: empleado.FECHA_CONTRATACION || new Date(),
    FECHA_ACTUALIZACION: new Date(),
  }

  return ejecutarInsert("empleados", nuevoEmpleado)
}

/**
 * Actualiza un empleado existente
 * Simula: UPDATE EMPLEADOS SET ... WHERE ID_EMPLEADO = ?
 * @param {number} id - ID del empleado
 * @param {object} datos - Datos a actualizar
 * @returns {boolean} True si se actualizó
 */
export function actualizarEmpleado(id, datos) {
  // Si se está actualizando el email, verificar que sea único
  if (datos.EMAIL) {
    const empleados = ejecutarSelect("empleados")
    const emailExiste = empleados.some(
      (e) => e.EMAIL.toLowerCase() === datos.EMAIL.toLowerCase() && e.ID_EMPLEADO !== id,
    )

    if (emailExiste) {
      throw new Error("El email ya está registrado")
    }
  }

  return ejecutarUpdate("empleados", "ID_EMPLEADO", id, datos)
}

/**
 * Actualiza el salario de un empleado
 * Simula: UPDATE EMPLEADOS SET SALARIO = ? WHERE ID_EMPLEADO = ?
 * @param {number} id - ID del empleado
 * @param {number} nuevoSalario - Nuevo salario
 * @returns {boolean} True si se actualizó
 */
export function actualizarSalario(id, nuevoSalario) {
  const empleado = obtenerEmpleadoPorId(id)
  if (!empleado) {
    throw new Error("Empleado no encontrado")
  }

  // Registrar en historial antes de actualizar
  const porcentajeCambio = (((nuevoSalario - empleado.SALARIO) / empleado.SALARIO) * 100).toFixed(2)

  ejecutarInsert("historialSalarios", {
    ID_EMPLEADO: id,
    SALARIO_ANTERIOR: empleado.SALARIO,
    SALARIO_NUEVO: nuevoSalario,
    PORCENTAJE_CAMBIO: Number.parseFloat(porcentajeCambio),
    FECHA_CAMBIO: new Date(),
    MOTIVO: "Actualización de salario",
    USUARIO_MODIFICACION: "SISTEMA",
  })

  return ejecutarUpdate("empleados", "ID_EMPLEADO", id, { SALARIO: nuevoSalario })
}

/**
 * Elimina un empleado (borrado lógico)
 * Simula: UPDATE EMPLEADOS SET ESTADO = 'INACTIVO' WHERE ID_EMPLEADO = ?
 * @param {number} id - ID del empleado
 * @returns {boolean} True si se eliminó
 */
export function eliminarEmpleado(id) {
  return ejecutarUpdate("empleados", "ID_EMPLEADO", id, { ESTADO: "INACTIVO" })
}

/**
 * Elimina un empleado físicamente (eliminar del sistema)
 * Simula: DELETE FROM EMPLEADOS WHERE ID_EMPLEADO = ?
 * @param {number} id - ID del empleado
 * @returns {boolean} True si se eliminó
 */
export function eliminarEmpleadoFisico(id) {
  return ejecutarDelete("empleados", "ID_EMPLEADO", id)
}

/**
 * Obtiene vista completa de empleados con joins
 * Simula: SELECT E.*, D.NOMBRE AS DEPARTAMENTO, C.NOMBRE AS CARGO
 *         FROM EMPLEADOS E
 *         INNER JOIN DEPARTAMENTOS D ON E.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
 *         INNER JOIN CARGOS C ON E.ID_CARGO = C.ID_CARGO
 * @returns {Array} Empleados con información de departamento y cargo
 */
export function obtenerEmpleadosCompleto() {
  const empleados = ejecutarSelect("empleados")
  const departamentos = DATABASE.departamentos
  const cargos = DATABASE.cargos

  return empleados.map((emp) => {
    const depto = departamentos.find((d) => d.ID_DEPARTAMENTO === emp.ID_DEPARTAMENTO)
    const cargo = cargos.find((c) => c.ID_CARGO === emp.ID_CARGO)

    return {
      ...emp,
      NOMBRE_COMPLETO: `${emp.NOMBRE} ${emp.APELLIDO}`,
      DEPARTAMENTO: depto ? depto.NOMBRE : "Sin asignar",
      CARGO: cargo ? cargo.NOMBRE : "Sin asignar",
      NIVEL: cargo ? cargo.NIVEL : null,
    }
  })
}

/**
 * Calcula la antigüedad de un empleado en años
 * Simula: FN_CALCULAR_ANTIGUEDAD(?)
 * @param {number} id - ID del empleado
 * @returns {number} Años de antigüedad
 */
export function calcularAntiguedad(id) {
  const empleado = obtenerEmpleadoPorId(id)
  if (!empleado) return 0

  const hoy = new Date()
  const fechaContratacion = new Date(empleado.FECHA_CONTRATACION)
  const diffTime = Math.abs(hoy - fechaContratacion)
  const diffYears = diffTime / (1000 * 60 * 60 * 24 * 365.25)

  return Number.parseFloat(diffYears.toFixed(2))
}

/**
 * Calcula la edad de un empleado
 * Simula: FN_CALCULAR_EDAD(?)
 * @param {number} id - ID del empleado
 * @returns {number} Edad en años
 */
export function calcularEdad(id) {
  const empleado = obtenerEmpleadoPorId(id)
  if (!empleado) return 0

  const hoy = new Date()
  const fechaNacimiento = new Date(empleado.FECHA_NACIMIENTO)
  let edad = hoy.getFullYear() - fechaNacimiento.getFullYear()
  const mes = hoy.getMonth() - fechaNacimiento.getMonth()

  if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
    edad--
  }

  return edad
}
