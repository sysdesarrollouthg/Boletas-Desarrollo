const MAXIMO_PERMITIDO_REMUNERACION = 999999999.99;
const MAXIMO_PERMITIDO_INTERESES = 99999.99;
const MAXIMO_PERMITIDO_TOTALDEPOSITADO = 9999999.99;

/********************************************************
 * VALIDACIÓN DE REMUNERACIÓN, INTERÉS Y TOTAL DEPOSITADO
 * dValor: el valor que figura en el total depositado
 * iMaxCantInt: cantidad de dígitos en la parte entera
 * iMaxCantDec: cantidad de dígitos en la parte decimal
 * cLabel: es para console.log
 * dMaxValPermitido: número máximo que acepta el campo
 * 
 *******************************************************/

const validaImportes = (dValor, iMaxCantInt, iMaxCantDec, cLabel, dMaxValPermitido) => {
    const label = cLabel
    const valor = dValor;
    
    // Validar que no esté vacío
    if (valor === '') {
        //console.log(label, 'Por favor, ingrese un número');
        return {success: false, message: label + ' - Por favor, ingrese un número' };
    }
    
    // Validar formato (números y punto decimal)
    const formatoValido = /^\d*\.?\d*$/.test(valor);
    if (!formatoValido) {
        //console.log(label, 'Solo se permiten números y un punto decimal');
        return {success: false, message: label + ' - Solo se permiten números y un punto decimal' };
    }
    
    // Validar que no tenga múltiples puntos
    if ((valor.match(/\./g) || []).length > 1) {
        //console.log(label, 'Solo se permite un punto decimal');
        return {success: false, message: label + ' - Solo se permite un punto decimal' };
    }
    
    // Separar parte entera y decimal
    const partes = valor.split('.');
    const parteEntera = partes[0];
    const parteDecimal = partes[1] || '';
    
    // Validar longitud de parte entera
    if (parteEntera.length > iMaxCantInt) {
        //console.log(label, `La parte entera no puede tener más de ${iMaxCantInt} dígitos. Actual: ${parteEntera.length} dígitos`);
        //return false;
        return {success: false, message: `${label} - La parte entera no puede tener más de ${iMaxCantInt} dígitos. Actual: ${parteEntera.length} dígitos` };
    }
    
    // Validar longitud de parte decimal
    if (parteDecimal.length > iMaxCantDec) {
        //console.log(label, `La parte decimal no puede tener más de ${iMaxCantDec} dígitos. Actual: ${parteDecimal.length} dígitos`);
        return {success: false, message: `${label} - La parte decimal no puede tener más de ${iMaxCantDec} dígitos. Actual: ${parteDecimal.length} dígitos` }; //return false;
    }
    
    // Convertir a número para validar el máximo
    const numero = parseFloat(valor);
    
    // Validar que sea un número válido
    if (isNaN(numero)) {
        //console.log(label, 'El valor ingresado no es un número válido');
        return {success: false, message: label + ' - El valor ingresado no es un número válido' }; //return false;
    }
    
    // Validar contra el máximo permitido
    if (numero > dMaxValPermitido) {
        //console.log(label, `El número no puede ser mayor a ${formatearNumero(dMaxValPermitido)}. Valor ingresado: ${formatearNumero(numero)}`);
        return {success: false, message: `${label} - El número no puede ser mayor a ${formatearNumero(dMaxValPermitido)}. Valor ingresado: ${formatearNumero(numero)}` } //return false;
    }
    
    // Si pasa todas las validaciones
    //console.log(label, '¡Número válido!');
    return {success: true, message: `${label} - OK` }
}

const formatearNumero = (numero) => { 
    return new Intl.NumberFormat('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(numero);
}

/**
 * Realiza una petición POST a la API definida en `API_URL`.
 *
 * @async
 * @function postAPI
 * @param {Object} data - Objeto con los datos que se enviarán en el cuerpo de la solicitud.
 * @param {string} [data.<key>] - Cada propiedad del objeto `data` se convertirá en un parámetro de formulario.
 * @returns {Promise<Object>} Devuelve una promesa que se resuelve con la respuesta de la API en formato JSON.
 *                              En caso de error, retorna un objeto { ok: false, data: null }.
 *
 * @example
 * const response = await postAPI({ username: 'usuario', password: '1234' });
 * if (response.ok) {
 *     console.log('Datos recibidos:', response.data);
 * } else {
 *     console.log('Error al llamar a la API');
 * }
 *
 * @description
 * Esta función:
 * 1. Crea una petición HTTP POST usando fetch().
 * 2. Envía los datos como 'application/x-www-form-urlencoded', incluyendo un token CSRF.
 * 3. Maneja errores de red o de parsing de JSON.
 * 4. Retorna siempre un objeto con la respuesta de la API o un objeto de error consistente.
 */
async function postAPI(url, token, data) {
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({ ...data, csrf_token: token })
        });
        const contentType = res.headers.get('Content-Type') || '';
        let payload;

        if (contentType.includes('application/json')) {
            payload = await res.json();
        } else {
            payload = await res.text();
        }

        return {
            ok: res.ok,
            status: res.status,
            headers: res.headers,
            data: payload
        };
    } catch (err) {
        console.error('Error en postAPI:', err);
        return { ok: false, data: null };
    }
}