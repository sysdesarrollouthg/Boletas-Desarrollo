const MAXIMO_PERMITIDO = 999999999.99;

/********************************************************
 * VALIDACIÓN DE REMUNERACIÓN, INTERÉS Y TOTAL DEPOSITADO
 *******************************************************/
const valRemuneracion = (dRemunera) => {
    
    const input = dRemunera;
    const valor = input.value.trim();
    
    // Limpiar mensajes anteriores
    //ocultarMensajes();
    
    // Validar que no esté vacío
    if (valor === '') {
        console.log('Por favor, ingrese un número');
        return;
    }
    
    // Validar formato (números y punto decimal)
    const formatoValido = /^\d*\.?\d*$/.test(valor);
    if (!formatoValido) {
        console.log('Solo se permiten números y un punto decimal');
        return;
    }
    
    // Validar que no tenga múltiples puntos
    if ((valor.match(/\./g) || []).length > 1) {
        console.log('Solo se permite un punto decimal');
        return;
    }
    
    // Separar parte entera y decimal
    const partes = valor.split('.');
    const parteEntera = partes[0];
    const parteDecimal = partes[1] || '';
    
    // Validar longitud de parte entera
    if (parteEntera.length > 9) {
        console.log(`La parte entera no puede tener más de 9 dígitos. Actual: ${parteEntera.length} dígitos`);
        return;
    }
    
    // Validar longitud de parte decimal
    if (parteDecimal.length > 2) {
        console.log(`La parte decimal no puede tener más de 2 dígitos. Actual: ${parteDecimal.length} dígitos`);
        return;
    }
    
    // Convertir a número para validar el máximo
    const numero = parseFloat(valor);
    
    // Validar que sea un número válido
    if (isNaN(numero)) {
        console.log('El valor ingresado no es un número válido');
        return;
    }
    
    // Validar contra el máximo permitido
    if (numero > MAXIMO_PERMITIDO) {
        console.log(`El número no puede ser mayor a ${formatearNumero(MAXIMO_PERMITIDO)}. Valor ingresado: ${formatearNumero(numero)}`);
        return;
    }
    
    // Si pasa todas las validaciones
    console.log('¡Número válido!');
    //mostrarDetalles(numero, parteEntera, parteDecimal);
}