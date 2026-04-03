<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  ConceptoController.php                                   ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  modulo: "concepto"                                       ║
 * ║    → action: "listar"        → lista de conceptos         ║
 * ║    → action: "detalle"       → detalle de conceptos       ║
 * ║    → action: "vencimiento"   → fecha de vencimiento       ║
 * ║    → action: "calcularTotal" → total con recargos         ║
 * ╚═══════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
require_once __DIR__ . "/../config/app.php";
include_once __DIR__ . "/../model/ConceptoModel.php";

function format_mp($valor, $cantidadDigitos) {
    //$valor = $_POST['tipoPagoMp'] ?? 0;

    // 1. Validar y convertir a número
    $numero = floatval($valor);
    
    // 2. Redondear a entero
    $entero = round($numero);
    
    // 3. Rellenar con ceros a la izquierda (ej: 7 dígitos)
    $resultado = str_pad($entero, $cantidadDigitos, '0', STR_PAD_LEFT);
    
    return $resultado;
}

class ConceptoController extends ConceptoModel
{
    /* ─────────────────────────────────────────
       Lista todos los conceptos disponibles
    ───────────────────────────────────────── */
    public function listar()
    {
        $resultado = $this->obtenerConceptos();
        echo json_encode(["ok" => true, "data" => $resultado]);
    }

    /* ─────────────────────────────────────────
       Detalle de conceptos
    ───────────────────────────────────────── */
    public function detalle()
    {
        $concepto = (int) ($_POST['concepto'] ?? 0);

        if ($concepto === 0) {
            echo json_encode(["ok" => false, "mensaje" => "Falta el parametro concepto."]);
            return;
        }else{
            $resultado = $this->obtenerDetalle($concepto); //fondo de convenio
        }
                
        echo json_encode(["ok" => true, "data" => $resultado]);
    }

    /* ─────────────────────────────────────────
       Fecha de vencimiento para un concepto
       y período dado
       POST: concepto, anio, mes
    ───────────────────────────────────────── */
    public function vencimiento()
    {
        $concepto = trim($_POST['concepto'] ?? '');
        $anio     = trim($_POST['anio']     ?? '');
        $mes      = trim($_POST['mes']      ?? '');

        if ($concepto === '' || $anio === '' || $mes === '') {
            echo json_encode(["ok" => false, "mensaje" => "Faltan parámetros: concepto, anio y mes son obligatorios."]);
            return;
        }

        $periodo   = $anio . str_pad($mes, 2, '0', STR_PAD_LEFT);

        // Extraer año y mes del período
        $anioPeriodo = substr($periodo, 0, 4);
        $mesPeriodo = substr($periodo, 4, 2);
        
        // Fecha actual
        $fechaActual = new DateTime();
        
        // Crear fecha del 16 del mes del período
        $fecha16MismoMes = new DateTime("$anioPeriodo-$mesPeriodo-16");
        
        // Crear fecha del 16 del mes siguiente
        $fecha16MesSiguiente = clone $fecha16MismoMes;
        $fecha16MesSiguiente->modify('first day of next month')->setDate(
            $fecha16MesSiguiente->format('Y'),
            $fecha16MesSiguiente->format('m'),
            16
        );
        
        // Calcular diferencias en días
        $diferenciaMismoMes = abs($fechaActual->getTimestamp() - $fecha16MismoMes->getTimestamp());
        $diferenciaMesSiguiente = abs($fechaActual->getTimestamp() - $fecha16MesSiguiente->getTimestamp());
        
        // Elegir el 16 más cercano
        if ($diferenciaMismoMes <= $diferenciaMesSiguiente) {
            $vencimiento = $fecha16MismoMes;
        } else {
            $vencimiento = $fecha16MesSiguiente;
        }
        
        echo json_encode([
            "ok" => true, 
            "vencimiento" => $vencimiento->format('d/m/Y'),
            "periodo" => $periodo
        ]);
    }


    /* ─────────────────────────────────────────
       Calcula el total con recargos
       POST: importe, fecvencimiento, fechapago (opcional, default hoy)
    ───────────────────────────────────────── */
    public function calcularTotal()
    {
        // Obtener y limpiar parámetros
        $importe          = isset($_POST['importe']) ? trim($_POST['importe']) : '';
        $fechavencimiento = isset($_POST['fecvencimiento']) ? trim($_POST['fecvencimiento']) : '';
        $fechapago        = isset($_POST['fechapago']) ? trim($_POST['fechapago']) : date('d/m/Y');

        // Validar parámetros obligatorios
        if ($importe === '' || $fechavencimiento === '') {
            echo json_encode([
                "ok" => false, 
                "mensaje" => "Faltan parámetros: importe y fecvencimiento son obligatorios."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Limpiar y convertir importe (manejar correctamente formato argentino)
        //$importe_limpio = str_replace(['.', ','], ['', '.'], $importe);
        
        // Detectar si usa punto o coma como decimal
        if (preg_match('/,\d{1,2}$/', $importe)) {
            // Formato argentino: 1.234.567,89
            $importe_limpio = str_replace('.', '', $importe);  // quita puntos de miles
            $importe_limpio = str_replace(',', '.', $importe_limpio); // coma → punto decimal
        } else {
            // Formato internacional: 1234567.89
            $importe_limpio = str_replace(',', '', $importe); // quita comas si las hay
            // NO eliminar el punto decimal
        }
        $importe = floatval($importe_limpio);

        if ($importe <= 0) {
            echo json_encode([
                "ok" => false, 
                "mensaje" => "El importe debe ser mayor a cero."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Validar fechas
        $dtVenc = DateTime::createFromFormat('d/m/Y', $fechavencimiento);
        $dtPago = DateTime::createFromFormat('d/m/Y', $fechapago);

        if (!$dtVenc) {
            echo json_encode([
                "ok" => false, 
                "mensaje" => "Formato de fecha de vencimiento inválido. Usar DD/MM/YYYY."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        if (!$dtPago) {
            echo json_encode([
                "ok" => false, 
                "mensaje" => "Formato de fecha de pago inválido. Usar DD/MM/YYYY."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // Si fecha de pago es anterior a fecha de vencimiento, no hay recargo
        if ($dtPago <= $dtVenc) {
            echo json_encode([
                "ok"            => true,
                "importe"       => number_format($importe, 2, '.', ''),
                "fecha_venc"    => $fechavencimiento,
                "fecha_pago"    => $fechapago,
                "total_recargo" => "0.00",
                "total_final"   => number_format($importe, 2, '.', ''),
                "detalle"       => [],
                "mensaje"       => "Pago realizado antes del vencimiento. Sin recargos."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // Obtener recargos
        $resultado = $this->obtenerRecargos($fechavencimiento, $fechapago);
        
        // Verificar si hay recargos
        if (empty($resultado)) {
            echo json_encode([
                "ok"            => true,
                "importe"       => number_format($importe, 2, '.', ''),
                "fecha_venc"    => $fechavencimiento,
                "fecha_pago"    => $fechapago,
                "total_recargo" => "0.00",
                "total_final"   => number_format($importe, 2, '.', ''),
                "detalle"       => [],
                "mensaje"       => "No se encontraron tramos de recargo para este período."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $hoy       = new DateTime();
        $hoy->setTime(0, 0, 0); // Normalizar a inicio del día
        $resarc    = 0.0;
        $detalle   = [];

        foreach ($resultado as $tramo) {
            // Validar que el tramo tenga los campos necesarios
            if (!isset($tramo['fecha-desde']) || !isset($tramo['fecha-hasta']) || !isset($tramo['porcentaje'])) {
                continue;
            }
            
            $cDesde = DateTime::createFromFormat('d/m/Y', $tramo['fecha-desde']);
            $cHasta = DateTime::createFromFormat('d/m/Y', $tramo['fecha-hasta']);

            if (!$cDesde || !$cHasta) {
                error_log("Fechas inválidas en tramo: " . print_r($tramo, true));
                continue;
            }
            
            // Normalizar fechas a inicio del día
            $cDesde->setTime(0, 0, 0);
            $cHasta->setTime(0, 0, 0);

            // Determinar fecha final para el cálculo
            $FH = ($cHasta > $hoy) ? clone $dtPago : clone $cHasta;
            $FH->setTime(0, 0, 0);
            
            // Determinar fecha inicial para el cálculo
            $FD = ($cDesde < $dtVenc) ? (clone $dtVenc)->modify('+1 day') : clone $cDesde;
            $FD->setTime(0, 0, 0);

            // Validar que el rango sea válido
            if ($FD > $FH) {
                continue;
            }

            // Calcular días de diferencia
            $interval = $FD->diff($FH);
            $dias = $interval->days + 1; // Incluir ambos extremos
            
            // Validar días
            if ($dias <= 0) {
                continue;
            }
            
            // CONVERTIR PORCENTAJE: Limpiar y convertir (varchar con coma)
            $porcentaje_limpio = $this->limpiarNumeroDecimal($tramo['porcentaje']);
            $porcentaje = floatval($porcentaje_limpio);
            
            // Calcular interés
            $interes = $importe * ($porcentaje * $dias / 100);
            $resarc += $interes;

            $detalle[] = [
                'fecha_desde'  => $FD->format('d/m/Y'),
                'fecha_hasta'  => $FH->format('d/m/Y'),
                'importe'      => number_format($importe, 2, '.', ''),
                'porcentaje_original' => $tramo['porcentaje'], // Valor original con coma
                'porcentaje'   => number_format($porcentaje, 5, '.', ''), // Valor convertido
                'dias'         => $dias,
                'intereses'    => number_format($interes, 2, '.', ''),
            ];
        }

        // Calcular total final
        $total_final = $importe + $resarc;

        echo json_encode([
            "ok"            => true,
            "importe_limpio" => $importe_limpio,
            "importe_original" => $importe,
            "importe"       => number_format($importe, 2, '.', ''),
            "fecha_venc"    => $fechavencimiento,
            "fecha_pago"    => $fechapago,
            "total_recargo" => number_format($resarc, 2, '.', ''),
            "total_final"   => number_format($total_final, 2, '.', ''),

            "total_recargo2" => $resarc,
            "total_final2"   => $total_final,

            
            "detalle"       => $detalle,
        ], JSON_UNESCAPED_UNICODE);
    }

    /********************************************************************
     * Limpia un número decimal que puede venir con coma
     * Ejemplos: "2,5" → "2.5", "1.234,56" → "1234.56", "0,197" → "0.197"
    ********************************************************************/
    private function limpiarNumeroDecimal($valor)
    {
        if (empty($valor)) {
            return '0';
        }
        
        // Si ya es numérico, devolverlo
        if (is_numeric($valor)) {
            return (string)$valor;
        }
        
        // Convertir a string si no lo es
        $valor = (string)$valor;
        
        // Remover puntos de miles (si tienen 3 dígitos después)
        // Ej: "1.234,56" → "1234,56"
        if (preg_match('/\.\d{3}[,.]/', $valor)) {
            $valor = str_replace('.', '', $valor);
        }
        
        // Reemplazar coma decimal por punto
        $valor = str_replace(',', '.', $valor);
        
        // Remover cualquier caracter que no sea número, punto o signo negativo
        $valor = preg_replace('/[^0-9\.\-]/', '', $valor);
        
        // Si después de limpiar queda vacío, retornar 0
        if ($valor === '' || $valor === '-') {
            return '0';
        }
        
        return $valor;
    }

    /*********************************************************
     * 
     * 
     * ******************************************************/

    /* ─────────────────────────────────────────
   Trae todos los datos del establecimiento
   activo en sesión junto con su empresa
───────────────────────────────────────── */
    public function contexto()
    {
        $cod_est    = (int) ($_SESSION['est_cod_est'] ?? 0);
        $empresa_id = (int) ($_SESSION['empresa_id']  ?? 0);

        if ($cod_est === 0 || $empresa_id === 0) {
            echo json_encode(["ok" => false, "mensaje" => "No hay establecimiento activo en la sesión."]);
            return;
        }

        $datos = $this->obtenerEstablecimientoCompleto($cod_est, $empresa_id);

        if (!$datos) {
            echo json_encode(["ok" => false, "mensaje" => "No se encontró el establecimiento."]);
            return;
        }

        echo json_encode(["ok" => true, "data" => $datos]);
    }



     /* ─────────────────────────────────────────
       Guarda los datos de la boleta calculada
       en $_SESSION para la vista de visualización
       POST: todos los campos del formulario
    ───────────────────────────────────────── */
    public function guardarBoleta()
    {
        $campos = ['est_nombre','est_direccion',
                   'concepto','detalle','porcentaje',
                   'periodo_mes','periodo_anio','fec_vencimiento',
                   'cant_empleados','total_remuneraciones',
                   'importe','recargos','total'];

        foreach ($campos as $campo) {
            if (!isset($_POST[$campo])) {
                echo json_encode(["ok" => false, "mensaje" => "Falta el campo: $campo"]);
                return;
            }
        }

        $_SESSION['boleta'] = [
            'titulo'              => "BOLETA DE PERÍODO",
            'empresa_nombre'      => $_SESSION['empresa_nombre'] ?? '',
            'empresa_cuit'        => $_SESSION['empresa_cuit']   ?? '',
            'est_nombre'          => trim($_POST['est_nombre'] ?? ''),
            'est_direccion'       => trim($_POST['est_direccion'] ?? ''),
            'est_seccional'       => $_SESSION['est_seccional']   ?? '',
            'cod_ente'            => trim($_POST['codente'] ?? ''),
            'des_convenio_banco'  => trim($_POST['des_convenio_banco'] ?? ''),
            'ctabanco'            => trim($_POST['ctabanco'] ?? ''),
            'concepto'            => trim($_POST['concepto'] ?? ''),
            'concepto_id'         => trim($_POST['concepto_id'] ?? ''),
            'detalle'             => trim($_POST['detalle'] ?? ''),
            'detalle_id'          => trim($_POST['detalle_id'] ?? ''),
            'porcentaje'          => trim($_POST['porcentaje'] ?? ''),
            'periodo_mes'         => trim($_POST['periodo_mes'] ?? ''),
            'periodo_anio'        => trim($_POST['periodo_anio'] ?? ''),
            'fec_vencimiento'     => trim($_POST['fec_vencimiento'] ?? ''),
            'cant_empleados'      => trim($_POST['cant_empleados'] ?? ''),
            'total_remuneraciones'=> trim($_POST['total_remuneraciones'] ?? ''),
            'importe'             => trim($_POST['importe'] ?? ''),
            'recargos'            => trim($_POST['recargos'] ?? ''),
            'total'               => trim($_POST['total'] ?? ''),
            // 'codigo_barra'        => trim($_POST['codigo_barra']),
            'fecha_calculo'       => date('d/m/Y H:i'),
			'pv_key'              => trim($_POST['pv_key'] ?? ''),
            'pb_key'              => trim($_POST['pb_key'] ?? ''),
            'chk_nuevo_conv'      => trim($_POST['chk_nuevo_conv'] ?? ''),
            'con_decimales'       => trim($_POST['con_decimales'] ?? ''),
            'uso_decimales'       => trim($_POST['uso_decimales'] ?? ''),
            'codebaroverflow'     => trim($_POST['codebaroverflow'] ?? ''),
            'codigo_barra'        => $this->genCodBarra(),
            
            'importe_mp' => trim($_POST['importe_mp'] ?? ''),
            'total_remuneraciones_mp' => trim($_POST['total_remuneraciones_mp'] ?? ''),
            'recargos_mp' => trim($_POST['recargos_mp'] ?? '') ,
            'total_mp' => trim($_POST['total_mp'] ?? '')            
            

        ];

        echo json_encode(["ok" => true]);
    }

    private function genCodBarra() {
        // Validar que existan los campos necesarios
        $cod_cliente = "0294";
        
        // Obtener valores con operador de fusión null para evitar errores
        $cod_ente        = isset($_POST['codente']) ? trim($_POST['codente']) : '';
        $conc_boleta     = isset($_POST['detalle_id']) ? trim($_POST['detalle_id']) : '';
        $tipo_pago       = 1;
        $cuit            = isset($_SESSION['empresa_cuit']) ? trim($_SESSION['empresa_cuit']) : '';
        $establecimiento = isset($_SESSION['est_cod_est']) ? trim($_SESSION['est_cod_est']) : '';
        $concepto        = isset($_POST['concepto_id']) ? trim($_POST['concepto_id']) : '';
        
        // Periodo acta acuerdo
        $anio = isset($_POST['periodo_anio']) ? trim($_POST['periodo_anio']) : '';
        $mes = isset($_POST['periodo_mes_value']) ? trim($_POST['periodo_mes_value']) : '';
        $per_acta_acu = $anio ."q". $mes."q0";
        
        $remuneracion    = isset($_POST['total_remuneraciones']) ? trim($_POST['total_remuneraciones']) : '';
        $cant_personal   = isset($_POST['cant_empleados']) ? trim($_POST['cant_empleados']) : '';
        $intereses       = isset($_POST['recargos']) ? trim($_POST['recargos']) : '';
        $imp_total       = isset($_POST['total']) ? trim($_POST['total']) : '';
        
        $intereses_original     = $intereses;
        $imp_total_original     = $imp_total;
        $remuneracion_original  = $remuneracion;
        
        $flag_mp = false;

        if($intereses > 99999.99){
            $flag_mp = true;
        }

        if($imp_total > 9999999.99){
            $flag_mp = true;
        }

        if($remuneracion > 999999999.99) {
            $flag_mp = true;
        }
        
        if(isset($_SESSION['boleta']['metodo_de_pago']) == "mp"){
            $flag_mp = true;
        }
        
        //if($flag_mp) {
            $remuneracion_mp = format_mp($_POST['total_remuneraciones_mp'], 11);
            
            $imp_total_mp = format_mp($_POST['total_mp'], 9);
            
            $intereses_mp = format_mp($_POST['recargos_mp'], 7);
            $tipo_pago_mp = 5;
        //}
        
        // ===== LIMPIEZA DE CAMPOS =====
        // Eliminar caracteres no numéricos y asegurar longitudes
        $per_acta_acu = $this->limpiarYFormatear($per_acta_acu, 7);

        // NOTA: Quitaste un cero aquí, verifica si es correcto
        // Original: "0".$conc_boleta (longitud 5+1=6)
        // Ahora: $conc_boleta (longitud 5)
        $conc_boleta = $this->limpiarYFormatear($conc_boleta, 3);

        $establecimiento = $this->limpiarYFormatear($establecimiento, 7);
        $remuneracion = $this->limpiarYFormatear($remuneracion, 11);
        $cant_personal = $this->limpiarYFormatear($cant_personal, 5);
        $intereses = $this->limpiarYFormatear($intereses, 7);
        $imp_total = $this->limpiarYFormatear($imp_total, 9);

        // Asegurar que los códigos tengan longitud correcta
        $cod_ente = trim(str_pad(preg_replace('/\D/', '', $cod_ente), 5, "0", STR_PAD_LEFT));
        $cuit = str_pad(preg_replace('/\D/', '', $cuit), 11, "0", STR_PAD_LEFT);
        $concepto = str_pad(preg_replace('/\D/', '', $concepto), 1, "0", STR_PAD_LEFT);

        // ===== CONSTRUCCIÓN DE CÓDIGOS =====
        // Código con importe (para dígito verificador)
        $codebar_con_importe = 
            $cod_cliente .
            $cod_ente .
            $conc_boleta . 
            $tipo_pago .
            $cuit .
            $establecimiento .
            $concepto .
            $per_acta_acu .
            $remuneracion .
            $cant_personal .
            $intereses .
            $imp_total;

        $codebar_sin_decimales = 
            $cod_cliente .
            $cod_ente .
            $conc_boleta . 
            $tipo_pago .
            $cuit .
            $establecimiento .
            $concepto .
            $per_acta_acu .
            $remuneracion .
            $cant_personal .
            $intereses .
            $imp_total;


        // Código sin importe
        $codebar_sin_importe = 
            $cod_cliente .
            $cod_ente .
            $conc_boleta .
            $tipo_pago .
            $cuit .
            $establecimiento .
            $concepto .
            $per_acta_acu .
            $remuneracion .
            $cant_personal .
            $intereses;

        $codebar_overflow =
            $cod_cliente .
            $cod_ente .
            $conc_boleta . 
            $tipo_pago .
            $cuit .
            $establecimiento .
            $concepto .
            $per_acta_acu .
            $remuneracion .
            $cant_personal .
            $intereses .
            $imp_total; //
            
        $codebar_mp =
            $cod_cliente .
            $cod_ente .
            $conc_boleta . 
            $tipo_pago_mp .
            $cuit .
            $establecimiento .
            $concepto .
            $per_acta_acu .
            $remuneracion_mp .
            $cant_personal .
            $intereses_mp .
            $imp_total_mp;

        $codebar_mp_sin_importe =
            $cod_cliente .
            $cod_ente .
            $conc_boleta . 
            $tipo_pago_mp .
            $cuit .
            $establecimiento .
            $concepto .
            $per_acta_acu .
            $remuneracion_mp .
            $cant_personal .
            $intereses_mp;

        // Calcular dígito verificador
        $digito_verificador = $this->calcDigitoVerificador($codebar_con_importe);
        $digito_verificador_overflow = $this->calcDigitoVerificador($codebar_overflow);
        $digito_verificador_mp = $this->calcDigitoVerificador($codebar_mp);
        
        // Código final
        $codebar_final = $codebar_con_importe . $digito_verificador;

        // Log para depuración
        error_log("Código de barras generado - Longitud: " . strlen($codebar_final));
        error_log("Código: " . $codebar_final);

        return [
            "codebar" => $codebar_final,
            "digito_verificador" => $digito_verificador,
            "codebar_sin_importe" => $codebar_sin_importe,
            "codebar_con_importe" => $codebar_con_importe,
            "digito" => $digito_verificador,
            "longitud" => strlen($codebar_final),

            "codebar_overflow" => $codebar_overflow.$digito_verificador_overflow,
            "intereses_original" => $intereses_original,
            "imp_total_original" => $imp_total_original,
            "digito_overflow" => strlen($digito_verificador_overflow),
            "remuneracion_original" => $remuneracion_original ,
            
            "codebar_mp" => $codebar_mp.$digito_verificador_mp,
            "codebar_mp_sin_importe" => $codebar_mp_sin_importe,
            "digito_mp" => $digito_verificador_mp,
        ];
    }

    /**
     * Función auxiliar para limpiar y formatear campos numéricos
     */
    private function limpiarYFormatear($valor, $longitud) {
        // Eliminar cualquier caracter que no sea número
        $limpio = preg_replace('/\D/', '', $valor);
        // Rellenar con ceros a la izquierda
        return str_pad($limpio, $longitud, "0", STR_PAD_LEFT);
    }

    private function calcDigitoVerificador($icString) {
        // Serie de factores fijos: 7, 9, 3, 5 (se repiten cíclicamente)
        $factores = [7, 9, 3, 5];
        
        // Inicializar suma con el primer dígito (sin factor)
        $suma = (int) substr($icString, 0, 1);
        $longitud = strlen($icString);
        
        // Recorrer desde el segundo carácter hasta el final
        for ($i = 2; $i <= $longitud; $i++) {
            // Obtener el dígito actual (posición $i en Progress es 1-indexada)
            $digito = (int) substr($icString, $i - 1, 1);
            
            // Determinar el factor correspondiente: el índice en el array es $i % 4
            // (Esto equivale a ENTRY(i MODULO 4 + 1, cSerie) en Progress)
            $factor = $factores[$i % 4];
            
            // Acumular producto
            $suma += $digito * $factor;
        }
        
        // Calcular el dígito final: truncar (suma / 2) y luego módulo 10
        $resultado = intdiv($suma, 2) % 10;  // intdiv requiere PHP 7+, si no, usar (int)($suma / 2)
        
        return (string) $resultado;
    }
    
        // RED LINK
    public function gendeudaRedLink()
    {
        header('Content-Type: application/json');
        echo json_encode(array_merge([
            'ok' => true,
            'codigo_pago' => "hola",
            'cpe' => '1234567890'
            ]));
        exit;
    }
}