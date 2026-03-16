// =====================================================
// CONFIGURACIÓN DE BASE DE DATOS
// Capa de Datos - Configuración y Datos Simulados
// =====================================================
// Descripción: Este archivo simula la conexión a la base de datos
// Oracle SQL Developer y mantiene los datos en memoria para la demo
// =====================================================

// Simulación de datos en memoria (normalmente vendría de Oracle)
const DATABASE = {
  departamentos: [
    {
      ID_DEPARTAMENTO: 1,
      NOMBRE: "Recursos Humanos",
      DESCRIPCION: "Gestión del talento humano y desarrollo organizacional",
      PRESUPUESTO: 250000,
      ACTIVO: "S",
      FECHA_CREACION: new Date("2015-01-01"),
    },
    {
      ID_DEPARTAMENTO: 2,
      NOMBRE: "Tecnología",
      DESCRIPCION: "Desarrollo de software y soporte técnico",
      PRESUPUESTO: 500000,
      ACTIVO: "S",
      FECHA_CREACION: new Date("2015-01-01"),
    },
    {
      ID_DEPARTAMENTO: 3,
      NOMBRE: "Ventas",
      DESCRIPCION: "Comercialización y atención al cliente",
      PRESUPUESTO: 350000,
      ACTIVO: "S",
      FECHA_CREACION: new Date("2015-01-01"),
    },
    {
      ID_DEPARTAMENTO: 4,
      NOMBRE: "Marketing",
      DESCRIPCION: "Estrategias de marketing y comunicación",
      PRESUPUESTO: 300000,
      ACTIVO: "S",
      FECHA_CREACION: new Date("2015-01-01"),
    },
    {
      ID_DEPARTAMENTO: 5,
      NOMBRE: "Finanzas",
      DESCRIPCION: "Contabilidad y gestión financiera",
      PRESUPUESTO: 280000,
      ACTIVO: "S",
      FECHA_CREACION: new Date("2015-01-01"),
    },
    {
      ID_DEPARTAMENTO: 6,
      NOMBRE: "Operaciones",
      DESCRIPCION: "Logística y gestión de operaciones",
      PRESUPUESTO: 400000,
      ACTIVO: "S",
      FECHA_CREACION: new Date("2015-01-01"),
    },
  ],

  cargos: [
    {
      ID_CARGO: 1,
      NOMBRE: "Desarrollador Junior",
      NIVEL: "JUNIOR",
      DESCRIPCION: "Desarrollo de software bajo supervisión",
      SALARIO_MINIMO: 1200,
      SALARIO_MAXIMO: 2000,
    },
    {
      ID_CARGO: 2,
      NOMBRE: "Desarrollador Senior",
      NIVEL: "SENIOR",
      DESCRIPCION: "Desarrollo y arquitectura de soluciones",
      SALARIO_MINIMO: 3000,
      SALARIO_MAXIMO: 5000,
    },
    {
      ID_CARGO: 3,
      NOMBRE: "Gerente de Tecnología",
      NIVEL: "GERENTE",
      DESCRIPCION: "Gestión del departamento de TI",
      SALARIO_MINIMO: 5000,
      SALARIO_MAXIMO: 8000,
    },
    {
      ID_CARGO: 4,
      NOMBRE: "Analista de RRHH",
      NIVEL: "SEMI-SENIOR",
      DESCRIPCION: "Gestión de procesos de recursos humanos",
      SALARIO_MINIMO: 1800,
      SALARIO_MAXIMO: 2800,
    },
    {
      ID_CARGO: 5,
      NOMBRE: "Director de RRHH",
      NIVEL: "DIRECTOR",
      DESCRIPCION: "Dirección estratégica de recursos humanos",
      SALARIO_MINIMO: 6000,
      SALARIO_MAXIMO: 10000,
    },
    {
      ID_CARGO: 6,
      NOMBRE: "Ejecutivo de Ventas",
      NIVEL: "JUNIOR",
      DESCRIPCION: "Prospección y cierre de ventas",
      SALARIO_MINIMO: 1500,
      SALARIO_MAXIMO: 2500,
    },
    {
      ID_CARGO: 7,
      NOMBRE: "Gerente de Ventas",
      NIVEL: "GERENTE",
      DESCRIPCION: "Gestión de equipo comercial",
      SALARIO_MINIMO: 4000,
      SALARIO_MAXIMO: 7000,
    },
    {
      ID_CARGO: 8,
      NOMBRE: "Especialista en Marketing",
      NIVEL: "SEMI-SENIOR",
      DESCRIPCION: "Campañas y estrategias de marketing",
      SALARIO_MINIMO: 2000,
      SALARIO_MAXIMO: 3500,
    },
    {
      ID_CARGO: 9,
      NOMBRE: "Contador",
      NIVEL: "SEMI-SENIOR",
      DESCRIPCION: "Gestión contable y financiera",
      SALARIO_MINIMO: 2200,
      SALARIO_MAXIMO: 3800,
    },
    {
      ID_CARGO: 10,
      NOMBRE: "Coordinador de Operaciones",
      NIVEL: "SENIOR",
      DESCRIPCION: "Coordinación de procesos operativos",
      SALARIO_MINIMO: 2800,
      SALARIO_MAXIMO: 4500,
    },
  ],

  funciones: [
    {
      ID_FUNCION: 1,
      NOMBRE: "Desarrollo de Software",
      DESCRIPCION: "Programación y desarrollo de aplicaciones",
      CATEGORIA: "OPERATIVA",
    },
    {
      ID_FUNCION: 2,
      NOMBRE: "Revisión de Código",
      DESCRIPCION: "Revisión y aprobación de código fuente",
      CATEGORIA: "OPERATIVA",
    },
    {
      ID_FUNCION: 3,
      NOMBRE: "Gestión de Personal",
      DESCRIPCION: "Administración del personal y nóminas",
      CATEGORIA: "ADMINISTRATIVA",
    },
    {
      ID_FUNCION: 4,
      NOMBRE: "Reclutamiento",
      DESCRIPCION: "Búsqueda y selección de talento",
      CATEGORIA: "ADMINISTRATIVA",
    },
    {
      ID_FUNCION: 5,
      NOMBRE: "Planificación Estratégica",
      DESCRIPCION: "Definición de objetivos y estrategias",
      CATEGORIA: "ESTRATEGICA",
    },
    {
      ID_FUNCION: 6,
      NOMBRE: "Gestión de Proyectos",
      DESCRIPCION: "Coordinación y seguimiento de proyectos",
      CATEGORIA: "GERENCIAL",
    },
  ],

  empleados: [
    {
      ID_EMPLEADO: 1,
      NOMBRE: "María",
      APELLIDO: "González López",
      EMAIL: "maria.gonzalez@empresa.com",
      TELEFONO: "555-0101",
      FECHA_NACIMIENTO: new Date("1985-03-15"),
      FECHA_CONTRATACION: new Date("2018-01-10"),
      ID_DEPARTAMENTO: 1,
      ID_CARGO: 5,
      SALARIO: 7500,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 2,
      NOMBRE: "Carlos",
      APELLIDO: "Rodríguez Pérez",
      EMAIL: "carlos.rodriguez@empresa.com",
      TELEFONO: "555-0102",
      FECHA_NACIMIENTO: new Date("1990-07-22"),
      FECHA_CONTRATACION: new Date("2020-03-15"),
      ID_DEPARTAMENTO: 1,
      ID_CARGO: 4,
      SALARIO: 2300,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 3,
      NOMBRE: "Ana",
      APELLIDO: "Martínez Silva",
      EMAIL: "ana.martinez@empresa.com",
      TELEFONO: "555-0201",
      FECHA_NACIMIENTO: new Date("1988-11-30"),
      FECHA_CONTRATACION: new Date("2017-05-20"),
      ID_DEPARTAMENTO: 2,
      ID_CARGO: 3,
      SALARIO: 6200,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 4,
      NOMBRE: "Luis",
      APELLIDO: "Fernández Torres",
      EMAIL: "luis.fernandez@empresa.com",
      TELEFONO: "555-0202",
      FECHA_NACIMIENTO: new Date("1992-04-18"),
      FECHA_CONTRATACION: new Date("2019-08-01"),
      ID_DEPARTAMENTO: 2,
      ID_CARGO: 2,
      SALARIO: 3800,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 5,
      NOMBRE: "Patricia",
      APELLIDO: "López Morales",
      EMAIL: "patricia.lopez@empresa.com",
      TELEFONO: "555-0203",
      FECHA_NACIMIENTO: new Date("1995-09-12"),
      FECHA_CONTRATACION: new Date("2022-02-14"),
      ID_DEPARTAMENTO: 2,
      ID_CARGO: 1,
      SALARIO: 1600,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 6,
      NOMBRE: "Roberto",
      APELLIDO: "Sánchez Ruiz",
      EMAIL: "roberto.sanchez@empresa.com",
      TELEFONO: "555-0204",
      FECHA_NACIMIENTO: new Date("1994-12-05"),
      FECHA_CONTRATACION: new Date("2021-06-20"),
      ID_DEPARTAMENTO: 2,
      ID_CARGO: 1,
      SALARIO: 1800,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 7,
      NOMBRE: "Laura",
      APELLIDO: "Ramírez Castro",
      EMAIL: "laura.ramirez@empresa.com",
      TELEFONO: "555-0301",
      FECHA_NACIMIENTO: new Date("1987-06-25"),
      FECHA_CONTRATACION: new Date("2016-09-10"),
      ID_DEPARTAMENTO: 3,
      ID_CARGO: 7,
      SALARIO: 5500,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 8,
      NOMBRE: "Diego",
      APELLIDO: "Herrera Vega",
      EMAIL: "diego.herrera@empresa.com",
      TELEFONO: "555-0302",
      FECHA_NACIMIENTO: new Date("1993-02-14"),
      FECHA_CONTRATACION: new Date("2021-01-05"),
      ID_DEPARTAMENTO: 3,
      ID_CARGO: 6,
      SALARIO: 2000,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 9,
      NOMBRE: "Carmen",
      APELLIDO: "Jiménez Ortiz",
      EMAIL: "carmen.jimenez@empresa.com",
      TELEFONO: "555-0303",
      FECHA_NACIMIENTO: new Date("1996-08-30"),
      FECHA_CONTRATACION: new Date("2023-03-20"),
      ID_DEPARTAMENTO: 3,
      ID_CARGO: 6,
      SALARIO: 1700,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 10,
      NOMBRE: "Jorge",
      APELLIDO: "Vargas Mendoza",
      EMAIL: "jorge.vargas@empresa.com",
      TELEFONO: "555-0401",
      FECHA_NACIMIENTO: new Date("1991-05-17"),
      FECHA_CONTRATACION: new Date("2019-11-12"),
      ID_DEPARTAMENTO: 4,
      ID_CARGO: 8,
      SALARIO: 2800,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 11,
      NOMBRE: "Sofía",
      APELLIDO: "Moreno Guzmán",
      EMAIL: "sofia.moreno@empresa.com",
      TELEFONO: "555-0402",
      FECHA_NACIMIENTO: new Date("1994-10-08"),
      FECHA_CONTRATACION: new Date("2021-07-18"),
      ID_DEPARTAMENTO: 4,
      ID_CARGO: 8,
      SALARIO: 2400,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 12,
      NOMBRE: "Andrés",
      APELLIDO: "Castro Flores",
      EMAIL: "andres.castro@empresa.com",
      TELEFONO: "555-0501",
      FECHA_NACIMIENTO: new Date("1986-01-20"),
      FECHA_CONTRATACION: new Date("2015-04-22"),
      ID_DEPARTAMENTO: 5,
      ID_CARGO: 9,
      SALARIO: 3200,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 13,
      NOMBRE: "Isabella",
      APELLIDO: "Reyes Navarro",
      EMAIL: "isabella.reyes@empresa.com",
      TELEFONO: "555-0502",
      FECHA_NACIMIENTO: new Date("1989-12-11"),
      FECHA_CONTRATACION: new Date("2018-10-05"),
      ID_DEPARTAMENTO: 5,
      ID_CARGO: 9,
      SALARIO: 2900,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 14,
      NOMBRE: "Miguel",
      APELLIDO: "Delgado Romero",
      EMAIL: "miguel.delgado@empresa.com",
      TELEFONO: "555-0601",
      FECHA_NACIMIENTO: new Date("1990-03-28"),
      FECHA_CONTRATACION: new Date("2019-02-14"),
      ID_DEPARTAMENTO: 6,
      ID_CARGO: 10,
      SALARIO: 3500,
      ESTADO: "ACTIVO",
    },
    {
      ID_EMPLEADO: 15,
      NOMBRE: "Valentina",
      APELLIDO: "Cruz Medina",
      EMAIL: "valentina.cruz@empresa.com",
      TELEFONO: "555-0602",
      FECHA_NACIMIENTO: new Date("1992-07-16"),
      FECHA_CONTRATACION: new Date("2020-09-08"),
      ID_DEPARTAMENTO: 6,
      ID_CARGO: 10,
      SALARIO: 3100,
      ESTADO: "ACTIVO",
    },
  ],

  historialSalarios: [
    {
      ID_HISTORIAL: 1,
      ID_EMPLEADO: 1,
      SALARIO_ANTERIOR: 6500,
      SALARIO_NUEVO: 7000,
      PORCENTAJE_CAMBIO: 7.69,
      FECHA_CAMBIO: new Date("2022-01-15"),
      MOTIVO: "Aumento por desempeño excepcional",
    },
    {
      ID_HISTORIAL: 2,
      ID_EMPLEADO: 1,
      SALARIO_ANTERIOR: 7000,
      SALARIO_NUEVO: 7500,
      PORCENTAJE_CAMBIO: 7.14,
      FECHA_CAMBIO: new Date("2023-01-15"),
      MOTIVO: "Aumento anual programado",
    },
    {
      ID_HISTORIAL: 3,
      ID_EMPLEADO: 3,
      SALARIO_ANTERIOR: 5500,
      SALARIO_NUEVO: 6000,
      PORCENTAJE_CAMBIO: 9.09,
      FECHA_CAMBIO: new Date("2021-06-01"),
      MOTIVO: "Promoción a Gerente",
    },
    {
      ID_HISTORIAL: 4,
      ID_EMPLEADO: 3,
      SALARIO_ANTERIOR: 6000,
      SALARIO_NUEVO: 6200,
      PORCENTAJE_CAMBIO: 3.33,
      FECHA_CAMBIO: new Date("2023-06-01"),
      MOTIVO: "Ajuste por inflación",
    },
  ],
}

// Contadores para IDs autoincrementales
const contadores = {
  empleado: 16,
  departamento: 7,
  cargo: 11,
  funcion: 7,
  historial: 5,
}

// =====================================================
// FUNCIONES DE ACCESO A DATOS
// =====================================================

/**
 * Simula una consulta SELECT a la base de datos
 * @param {string} tabla - Nombre de la tabla
 * @param {object} filtros - Objeto con filtros opcionales
 * @returns {Array} Resultados de la consulta
 */
function ejecutarSelect(tabla, filtros = {}) {
  let datos = DATABASE[tabla] || []

  // Aplicar filtros si existen
  if (Object.keys(filtros).length > 0) {
    datos = datos.filter((registro) => {
      return Object.keys(filtros).every((key) => {
        return registro[key] === filtros[key]
      })
    })
  }

  // Retornar copia de los datos para evitar modificaciones
  return JSON.parse(JSON.stringify(datos))
}

/**
 * Simula una consulta SELECT por ID
 * @param {string} tabla - Nombre de la tabla
 * @param {string} campoId - Nombre del campo ID
 * @param {number} id - Valor del ID
 * @returns {object|null} Registro encontrado o null
 */
function ejecutarSelectPorId(tabla, campoId, id) {
  const datos = DATABASE[tabla] || []
  const registro = datos.find((r) => r[campoId] === id)
  return registro ? JSON.parse(JSON.stringify(registro)) : null
}

/**
 * Simula una inserción INSERT
 * @param {string} tabla - Nombre de la tabla
 * @param {object} datos - Datos a insertar
 * @returns {object} Registro insertado con ID generado
 */
function ejecutarInsert(tabla, datos) {
  const tablaData = DATABASE[tabla]
  if (!tablaData) {
    throw new Error(`Tabla ${tabla} no existe`)
  }

  // Generar ID según la tabla
  let nuevoId
  let campoId

  switch (tabla) {
    case "empleados":
      nuevoId = contadores.empleado++
      campoId = "ID_EMPLEADO"
      break
    case "departamentos":
      nuevoId = contadores.departamento++
      campoId = "ID_DEPARTAMENTO"
      break
    case "cargos":
      nuevoId = contadores.cargo++
      campoId = "ID_CARGO"
      break
    case "funciones":
      nuevoId = contadores.funcion++
      campoId = "ID_FUNCION"
      break
    case "historialSalarios":
      nuevoId = contadores.historial++
      campoId = "ID_HISTORIAL"
      break
    default:
      throw new Error(`No se puede generar ID para tabla ${tabla}`)
  }

  const nuevoRegistro = {
    [campoId]: nuevoId,
    ...datos,
  }

  tablaData.push(nuevoRegistro)
  return JSON.parse(JSON.stringify(nuevoRegistro))
}

/**
 * Simula una actualización UPDATE
 * @param {string} tabla - Nombre de la tabla
 * @param {string} campoId - Nombre del campo ID
 * @param {number} id - ID del registro a actualizar
 * @param {object} datos - Datos a actualizar
 * @returns {boolean} True si se actualizó, false si no se encontró
 */
function ejecutarUpdate(tabla, campoId, id, datos) {
  const tablaData = DATABASE[tabla]
  if (!tablaData) {
    throw new Error(`Tabla ${tabla} no existe`)
  }

  const index = tablaData.findIndex((r) => r[campoId] === id)
  if (index === -1) {
    return false
  }

  // Actualizar el registro
  tablaData[index] = {
    ...tablaData[index],
    ...datos,
    FECHA_ACTUALIZACION: new Date(),
  }

  return true
}

/**
 * Simula una eliminación DELETE
 * @param {string} tabla - Nombre de la tabla
 * @param {string} campoId - Nombre del campo ID
 * @param {number} id - ID del registro a eliminar
 * @returns {boolean} True si se eliminó, false si no se encontró
 */
function ejecutarDelete(tabla, campoId, id) {
  const tablaData = DATABASE[tabla]
  if (!tablaData) {
    throw new Error(`Tabla ${tabla} no existe`)
  }

  const index = tablaData.findIndex((r) => r[campoId] === id)
  if (index === -1) {
    return false
  }

  tablaData.splice(index, 1)
  return true
}

/**
 * Obtiene el siguiente ID disponible para una tabla
 * @param {string} tipo - Tipo de entidad (empleado, departamento, etc)
 * @returns {number} Siguiente ID disponible
 */
function obtenerSiguienteId(tipo) {
  return contadores[tipo]
}

// Exportar funciones y datos
export {
  DATABASE,
  ejecutarSelect,
  ejecutarSelectPorId,
  ejecutarInsert,
  ejecutarUpdate,
  ejecutarDelete,
  obtenerSiguienteId,
}
