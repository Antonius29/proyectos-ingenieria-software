// =====================================================
// SERVICIO: REPORTES
// Capa de Negocio - Generación de reportes y estadísticas
// =====================================================

import * as EmpleadosDAO from "../capa-datos/empleados-dao.js"
import * as DepartamentosDAO from "../capa-datos/departamentos-dao.js"
import * as CargosDAO from "../capa-datos/cargos-dao.js"
import { DATABASE } from "../capa-datos/database-config.js"

/**
 * Genera dashboard con métricas principales
 */
export function generarDashboard() {
  try {
    const empleados = EmpleadosDAO.obtenerTodosEmpleados()
    const empleadosActivos = empleados.filter((e) => e.ESTADO === "ACTIVO")
    const departamentos = DepartamentosDAO.obtenerTodosDepartamentos()

    const salarios = empleadosActivos.map((e) => e.SALARIO)
    const totalNominaMensual = salarios.reduce((a, b) => a + b, 0)
    const salarioPromedio = totalNominaMensual / empleadosActivos.length

    // Calcular antigüedad promedio
    const antiguedades = empleadosActivos.map((e) => EmpleadosDAO.calcularAntiguedad(e.ID_EMPLEADO))
    const antiguedadPromedio = antiguedades.reduce((a, b) => a + b, 0) / antiguedades.length

    return {
      totalEmpleados: empleados.length,
      empleadosActivos: empleadosActivos.length,
      empleadosInactivos: empleados.filter((e) => e.ESTADO === "INACTIVO").length,
      empleadosVacaciones: empleados.filter((e) => e.ESTADO === "VACACIONES").length,
      totalDepartamentos: departamentos.length,
      nominaMensual: totalNominaMensual,
      nominaAnual: totalNominaMensual * 12,
      salarioPromedio: salarioPromedio,
      salarioMinimo: Math.min(...salarios),
      salarioMaximo: Math.max(...salarios),
      antiguedadPromedio: antiguedadPromedio,
      // Formateados
      nominaMensualFormato: formatearMoneda(totalNominaMensual),
      nominaAnualFormato: formatearMoneda(totalNominaMensual * 12),
      salarioPromedioFormato: formatearMoneda(salarioPromedio),
      salarioMinimoFormato: formatearMoneda(Math.min(...salarios)),
      salarioMaximoFormato: formatearMoneda(Math.max(...salarios)),
      antiguedadPromedioFormato: antiguedadPromedio.toFixed(1) + " años",
    }
  } catch (error) {
    console.error("[v0] Error al generar dashboard:", error)
    throw new Error("No se pudo generar el dashboard")
  }
}

/**
 * Genera reporte de distribución por departamento
 */
export function reporteDistribucionDepartamentos() {
  try {
    const resumen = DepartamentosDAO.obtenerResumenTodosDepartamentos()

    return resumen
      .map((dep) => ({
        departamento: dep.NOMBRE,
        empleados: dep.EMPLEADOS_ACTIVOS,
        salarioPromedio: dep.SALARIO_PROMEDIO,
        nominaMensual: dep.NOMINA_MENSUAL,
        presupuesto: dep.PRESUPUESTO,
        disponible: dep.PRESUPUESTO_DISPONIBLE,
        porcentajeUsado: ((dep.NOMINA_ANUAL / dep.PRESUPUESTO) * 100).toFixed(1),
        // Formateados
        salarioPromedioFormato: formatearMoneda(dep.SALARIO_PROMEDIO),
        nominaMensualFormato: formatearMoneda(dep.NOMINA_MENSUAL),
        presupuestoFormato: formatearMoneda(dep.PRESUPUESTO),
        disponibleFormato: formatearMoneda(dep.PRESUPUESTO_DISPONIBLE),
      }))
      .sort((a, b) => b.empleados - a.empleados)
  } catch (error) {
    console.error("[v0] Error al generar reporte de departamentos:", error)
    throw new Error("No se pudo generar el reporte")
  }
}

/**
 * Genera reporte de distribución por cargo
 */
export function reporteDistribucionCargos() {
  try {
    const resumen = CargosDAO.obtenerResumenTodosCargos()

    return resumen
      .map((cargo) => ({
        cargo: cargo.NOMBRE,
        nivel: cargo.NIVEL,
        empleados: cargo.TOTAL_EMPLEADOS,
        salarioPromedioReal: cargo.SALARIO_PROMEDIO_REAL,
        rangoMinimo: cargo.SALARIO_MINIMO,
        rangoMaximo: cargo.SALARIO_MAXIMO,
        // Formateados
        salarioPromedioRealFormato: formatearMoneda(cargo.SALARIO_PROMEDIO_REAL),
        rangoMinimoFormato: formatearMoneda(cargo.SALARIO_MINIMO),
        rangoMaximoFormato: formatearMoneda(cargo.SALARIO_MAXIMO),
      }))
      .sort((a, b) => {
        const ordenNiveles = {
          DIRECTOR: 1,
          GERENTE: 2,
          SENIOR: 3,
          "SEMI-SENIOR": 4,
          JUNIOR: 5,
        }
        return (ordenNiveles[a.nivel] || 99) - (ordenNiveles[b.nivel] || 99)
      })
  } catch (error) {
    console.error("[v0] Error al generar reporte de cargos:", error)
    throw new Error("No se pudo generar el reporte")
  }
}

/**
 * Genera reporte de historial de salarios
 */
export function reporteHistorialSalarios(idEmpleado = null) {
  try {
    let historial = DATABASE.historialSalarios

    if (idEmpleado) {
      historial = historial.filter((h) => h.ID_EMPLEADO === idEmpleado)
    }

    return historial
      .map((h) => {
        const empleado = EmpleadosDAO.obtenerEmpleadoPorId(h.ID_EMPLEADO)
        const departamento = DepartamentosDAO.obtenerDepartamentoPorId(empleado.ID_DEPARTAMENTO)
        const cargo = CargosDAO.obtenerCargoPorId(empleado.ID_CARGO)

        return {
          empleado: `${empleado.NOMBRE} ${empleado.APELLIDO}`,
          departamento: departamento.NOMBRE,
          cargo: cargo.NOMBRE,
          salarioAnterior: h.SALARIO_ANTERIOR,
          salarioNuevo: h.SALARIO_NUEVO,
          diferencia: h.SALARIO_NUEVO - h.SALARIO_ANTERIOR,
          porcentajeCambio: h.PORCENTAJE_CAMBIO,
          fecha: h.FECHA_CAMBIO,
          motivo: h.MOTIVO,
          // Formateados
          salarioAnteriorFormato: formatearMoneda(h.SALARIO_ANTERIOR),
          salarioNuevoFormato: formatearMoneda(h.SALARIO_NUEVO),
          diferenciaFormato: formatearMoneda(h.SALARIO_NUEVO - h.SALARIO_ANTERIOR),
          fechaFormato: formatearFecha(h.FECHA_CAMBIO),
        }
      })
      .sort((a, b) => new Date(b.fecha) - new Date(a.fecha))
  } catch (error) {
    console.error("[v0] Error al generar reporte de historial:", error)
    throw new Error("No se pudo generar el reporte")
  }
}

/**
 * Genera reporte de empleados con mayor antigüedad
 */
export function reporteEmpleadosAntiguedad(limite = 10) {
  try {
    const empleados = EmpleadosDAO.obtenerEmpleadosCompleto().filter((e) => e.ESTADO === "ACTIVO")

    const empleadosConAntiguedad = empleados.map((emp) => ({
      ...emp,
      ANTIGUEDAD: EmpleadosDAO.calcularAntiguedad(emp.ID_EMPLEADO),
    }))

    return empleadosConAntiguedad
      .sort((a, b) => b.ANTIGUEDAD - a.ANTIGUEDAD)
      .slice(0, limite)
      .map((emp) => ({
        nombre: `${emp.NOMBRE} ${emp.APELLIDO}`,
        departamento: emp.DEPARTAMENTO,
        cargo: emp.CARGO,
        fechaContratacion: emp.FECHA_CONTRATACION,
        antiguedad: emp.ANTIGUEDAD,
        salario: emp.SALARIO,
        // Formateados
        fechaContratacionFormato: formatearFecha(emp.FECHA_CONTRATACION),
        antiguedadFormato: emp.ANTIGUEDAD.toFixed(1) + " años",
        salarioFormato: formatearMoneda(emp.SALARIO),
      }))
  } catch (error) {
    console.error("[v0] Error al generar reporte de antigüedad:", error)
    throw new Error("No se pudo generar el reporte")
  }
}

/**
 * Genera reporte de distribución por estado
 */
export function reporteDistribucionEstados() {
  try {
    const empleados = EmpleadosDAO.obtenerTodosEmpleados()
    const total = empleados.length

    const estados = ["ACTIVO", "INACTIVO", "VACACIONES", "LICENCIA"]

    return estados
      .map((estado) => {
        const cantidad = empleados.filter((e) => e.ESTADO === estado).length
        return {
          estado: estado,
          cantidad: cantidad,
          porcentaje: ((cantidad / total) * 100).toFixed(1),
        }
      })
      .filter((e) => e.cantidad > 0)
  } catch (error) {
    console.error("[v0] Error al generar reporte de estados:", error)
    throw new Error("No se pudo generar el reporte")
  }
}

// Funciones auxiliares
function formatearMoneda(valor) {
  return new Intl.NumberFormat("es-MX", {
    style: "currency",
    currency: "USD",
  }).format(valor)
}

function formatearFecha(fecha) {
  if (!fecha) return ""
  const d = new Date(fecha)
  const dia = String(d.getDate()).padStart(2, "0")
  const mes = String(d.getMonth() + 1).padStart(2, "0")
  const año = d.getFullYear()
  return `${dia}/${mes}/${año}`
}
