<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  AcuerdosController.php                                   ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  modulo: "acuerdos"                                       ║
 * ║    → action: "listar"        → lista de conceptos         ║
 * ║    → action: "detalle"       → detalle de conceptos       ║
 * ║    → action: "vencimiento"   → fecha de vencimiento       ║
 * ║    → action: "calcularTotal" → total con recargos         ║
 * ╚═══════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
require_once __DIR__ . "/../config/app.php";
include_once __DIR__ . "/../model/AcuerdosModel.php";

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
class AcuerdosController extends AcuerdosModel
{

  /* ─────────────────────────────────────────
       Guarda los datos de la boleta calculada
       en $_SESSION para la vista de visualización
       POST: todos los campos del formulario
    ───────────────────────────────────────── */
    public function guardarBoleta()
    {
        $campos = ['empresa_nombre','est_direccion',
           'concepto','detalle','numero_acuerdo', 'cuota_desde', 'cuota_hasta',
           'numero_acta','importe','recargos','total','tipopago'];


        foreach ($campos as $campo) {
            if (!isset($_POST[$campo])) {
                echo json_encode(["ok" => false, "mensaje" => "Falta el campo: $campo"]);
                return;
            }
        }

        $_SESSION['boleta'] = [
            'titulo'              => "BOLETA DE ACUERDOS",
            'tipodepago'          => 3,
            'desconvenio'         => trim($_POST['desconvenio'] ?? ''),
            'des_convenio_banco'  => trim($_POST['desconvenio'] ?? ''),

            'empresa_nombre'      => $_SESSION['empresa_nombre'] ?? '',
            'empresa_cuit'        => $_SESSION['empresa_cuit']   ?? '',
            'est_nombre'          => trim($_POST['est_nombre'] ?? ''),
            'est_direccion'       => trim($_POST['est_direccion'] ?? ''),
            'est_seccional'       => $_SESSION['est_seccional']   ?? '',
            'cod_ente'            => trim($_POST['codente'] ?? ''),
            'concepto'            => trim($_POST['concepto'] ?? ''),
            'concepto_id'         => trim($_POST['concepto_id'] ?? ''),
            'detalle'             => trim($_POST['detalle'] ?? ''),
            'detalle_id'          => trim($_POST['detalle_id'] ?? ''),
            'ctabanco'            => trim($_POST['ctabanco'] ?? ''),
            'porcentaje'          => '',
            'periodo_mes'         => '',
            'periodo_anio'        => '',
            'fec_vencimiento'     => '',
            'cant_empleados'      => '',
            'total_remuneraciones'=> '',
            'importe'             => trim($_POST['importe'] ?? ''),
            'recargos'            => trim($_POST['recargos'] ?? ''),
            'total'               => trim($_POST['total'] ?? ''),
            'tipopago'            => trim($_POST['tipopago'] ?? ''),
            'numero_acta'         => trim($_POST['numero_acta'] ?? ''),
            'acuerdo_numero'      => trim($_POST['numero_acuerdo'] ?? ''),
            'cuota_desde'         => trim($_POST['cuota_desde'] ?? ''),
            'cuota_hasta'         => trim($_POST['cuota_hasta'] ?? '') ,
            'cuota_numero'        => '',
            'genboletaauto'       => trim($_POST['gen_bol_x_cuota'] ?? false),
            'fecha_calculo'       => date('d/m/Y H:i'),
            'codigo_barra'        => $this->genCodBarra(),
			'pb_key'        => trim($_POST['pb_key'] ?? ''),
			'pv_key'        => trim($_POST['pv_key'] ?? ''),
			
			'codebaroverflow'     => trim($_POST['codebaroverflow'] ?? ''),
			
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
        $cuit            = isset($_POST['empresa_cuit']) ? trim($_POST['empresa_cuit']) : '';
        $establecimiento = isset($_SESSION['est_cod_est']) ? trim($_SESSION['est_cod_est']) : '';
        $concepto        = isset($_POST['concepto_id']) ? trim($_POST['concepto_id']) : '';
        
        // Periodo acta acuerdo
        $anio = isset($_POST['periodo_anio']) ? trim($_POST['periodo_anio']) : '';
        $mes = isset($_POST['periodo_mes_value']) ? trim($_POST['periodo_mes_value']) : '';
        $per_acta_acu = $anio . trim(str_pad(preg_replace('/\D/', '', $mes), 2, "0", STR_PAD_LEFT))."0";
        
        $numero_acuerdo     = isset($_POST['numero_acuerdo']) ? trim($_POST['numero_acuerdo']) : '';
        $numero_acta        = isset($_POST['numero_acta']) ? trim($_POST['numero_acta']) : '';
        $tipopago           = isset($_POST['tipopago']) ? trim($_POST['tipopago']) : '';
        $cuota_desde        = isset($_POST['cuota_desde']) ? trim($_POST['cuota_desde']) : '';
        $cuota_hasta        = isset($_POST['cuota_hasta']) ? trim($_POST['cuota_hasta']) : '';
        $gen_bol_x_cuota    = isset($_POST['gen_bol_x_cuota']) ? trim($_POST['gen_bol_x_cuota']) : '';

        $importe    = isset($_POST['importe']) ? trim($_POST['importe']) : '';
        $recargos    = isset($_POST['recargos']) ? trim($_POST['recargos']) : '';
        $total    = isset($_POST['total']) ? trim($_POST['total']) : '';

        $imp_total_mp = format_mp($_POST['total_mp'], 9);
        $intereses_mp = format_mp($_POST['recargos_mp'], 7);
        $tipo_pago_mp = 7;

        // ===== LIMPIEZA DE CAMPOS =====
        // Eliminar caracteres no numéricos y asegurar longitudes
        $per_acta_acu = $this->limpiarYFormatear($per_acta_acu, 7);
        
        // NOTA: Quitaste un cero aquí, verifica si es correcto
        // Original: "0".$conc_boleta (longitud 5+1=6)
        // Ahora: $conc_boleta (longitud 5)
        $conc_boleta = $this->limpiarYFormatear($conc_boleta, 3);
        
        $establecimiento = $this->limpiarYFormatear($establecimiento, 7);
        
        $numero_acuerdo = $this->limpiarYFormatear($numero_acuerdo, 7);
        $cuota_desde = $this->limpiarYFormatear($cuota_desde, 3);
        $cuota_hasta = $this->limpiarYFormatear($cuota_hasta, 3);

        $numero_acta = $this->limpiarYFormatear($numero_acta, 6);
        
        $intereses = $this->limpiarYFormatear($recargos, 7);
        $total = $this->limpiarYFormatear($total, 9);
        
        // Asegurar que los códigos tengan longitud correcta
        $cod_ente = trim(str_pad(preg_replace('/\D/', '', $cod_ente), 5, "0", STR_PAD_LEFT));
        $cuit = str_pad(preg_replace('/\D/', '', $cuit), 11, "0", STR_PAD_LEFT);
        $concepto = str_pad(preg_replace('/\D/', '', $concepto), 1, "0", STR_PAD_LEFT);

        /*
        ASSIGN cAux = STRING (INTEGER (icNroAcu), '9999999')
              cAux2 = STRING (INTEGER (icCuotDde), '999') +
                      STRING (INTEGER (icCuotHta), '999') +
               (IF icTotParc = 'Total' THEN '0' ELSE '1') + 
                        "000" + STRING ( SUBSTRING ( STRING (INT (icNroAct),'999999'),1,1 ),'9' )
              cAux3 =  STRING ( SUBSTRING ( STRING (INT (icNroAct),'999999'),2,5),'99999').
        */

        $aux =  $numero_acuerdo . 
                $cuota_desde . 
                $cuota_hasta .
                ($tipopago == "Total"?"0":"1") .
                "000".$numero_acta;


        // ===== CONSTRUCCIÓN DE CÓDIGOS =====
        // Código con importe (para dígito verificador)
        $codebar_con_importe = 
            $cod_cliente .
            $cod_ente .
            $conc_boleta . 
            "3".
            $cuit .
            $establecimiento .
            $concepto .
            $aux.
            /*
            $per_acta_acu .
            $remuneracion .
            $cant_personal .*/
            $intereses .
            $total;

        // Código sin importe
        $codebar_sin_importe = 
            $cod_cliente /*.
            $cod_ente .
            $conc_boleta .
            $tipo_pago .
            $cuit .
            $establecimiento .
            $concepto .
            $per_acta_acu .
            $remuneracion .
            $cant_personal .
            $intereses*/ ;

        $codebar_mp =
            $cod_cliente .
            $cod_ente .
            $conc_boleta . 
            $tipo_pago_mp.
            $cuit .
            $establecimiento .
            $concepto .
            $aux.
            $intereses_mp .
            $imp_total_mp;

        $codebar_mp_sin_importe =
            $cod_cliente .
            $cod_ente .
            $conc_boleta . 
            $tipo_pago_mp.
            $cuit .
            $establecimiento .
            $concepto .
            $aux.
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
            "codebar_sin_importe" => $codebar_sin_importe,
            "codebar_con_importe" => $codebar_con_importe,
            "digito" => $digito_verificador,
            "longitud" => strlen($codebar_final),

            "codebar_overflow" => $codebar_overflow.$digito_verificador_overflow,
            "intereses_original" => $intereses_original,
            "imp_total_original" => $imp_total_original,
            "digito_overflow" => strlen($digito_verificador_overflow),
            
            "codebar_mp" => $codebar_mp.$digito_verificador_mp,
            "codebar_mp_sin_importe" => $codebar_mp_sin_importe,
            "digito_mp" => $digito_verificador_mp
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
}