<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  ConceptoController.php                                  ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  modulo: "concepto"                                      ║
 * ║    → action: "listar"        → lista de conceptos        ║
 * ║    → action: "detalle"       → detalle de conceptos      ║
 * ║    → action: "vencimiento"   → fecha de vencimiento      ║
 * ║    → action: "calcularTotal" → total con recargos        ║
 * ╚═══════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
require_once __DIR__ . "/../config/app.php";
include_once __DIR__ . "/../model/ActasModel.php";


class ActasController extends ActasModel
{

  /* ─────────────────────────────────────────
       Guarda los datos de la boleta calculada
       en $_SESSION para la vista de visualización
       POST: todos los campos del formulario
    ───────────────────────────────────────── */
    public function guardarBoleta()
    {
        $campos = ['empresa_nombre','empresa_cuit','est_nombre','est_direccion',
           'est_seccional','concepto','detalle',
           'numero_acta','importe','recargos','total','tipopago'];


        foreach ($campos as $campo) {
            if (!isset($_POST[$campo])) {
                echo json_encode(["ok" => false, "mensaje" => "Falta el campo: $campo"]);
                return;
            }
        }

     $_SESSION['boleta'] = [
    'titulo'              => "BOLETA DE ACTAS",
    'empresa_nombre'      => trim($_POST['empresa_nombre']),
    'empresa_cuit'        => trim($_POST['empresa_cuit']),
    'est_nombre'          => trim($_POST['est_nombre']),
    'est_direccion'       => trim($_POST['est_direccion']),
    'est_seccional'       => trim($_POST['est_seccional']),
    'cod_ente'            => '',                              // ← faltaba
    'concepto'            => trim($_POST['concepto']),
    'concepto_id'         => '',     // ← faltaba
    'detalle'             => trim($_POST['detalle']),
    'detalle_id'          => '',      // ← faltaba
    'porcentaje'          => '',
    'periodo_mes'         => '',
    'periodo_anio'        => '',
    'fec_vencimiento'     => '',
    'cant_empleados'      => '',
    'total_remuneraciones'=> '',
    'importe'             => trim($_POST['importe']),
    'recargos'            => trim($_POST['recargos']),
    'total'               => trim($_POST['total']),
    'tipopago'            => trim($_POST['tipopago']),
    'numero_acta'         => trim($_POST['numero_acta']),
    'acuerdo_numero'      => '',
    'cuota_numero'        => '',
    'codigo_barra'        => '',                              // ← faltaba (Actas no genera código de barras real)
    'fecha_calculo'       => date('d/m/Y H:i'),
];

        echo json_encode(["ok" => true]);
    }

}