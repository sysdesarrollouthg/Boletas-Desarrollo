<?php
/**
 * Generarcsv.php
 * Lee $_SESSION['boleta'] y agrega un registro al CSV mensual
 * en docs/MMAAAA/MMAAAA.csv
 */

session_start();
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/
$_SESSION['boleta']['metodo_de_pago'] = $_POST['metodo_de_pago'];

if (empty($_SESSION['boleta'])) {
    http_response_code(400);
    echo json_encode(["ok" => false, "mensaje" => "No hay boleta en sesión."]);
    exit;
}

$b = $_SESSION['boleta'];
$s = $_SESSION;

// ── Carpeta y archivo ──────────────────────────────────────
$mes       = date('m');
$anio      = date('Y');
$carpeta   = __DIR__ . '/docs/' . $mes . $anio;
$archivo   = $carpeta . '/' . $mes . $anio . '.csv';

if (!is_dir($carpeta)) {
    mkdir($carpeta, 0755, true);
}

// ── Encabezado si el archivo no existe ────────────────────
$esNuevo = !file_exists($archivo);
$fp = fopen($archivo, 'a');

if ($fp === false) {
    echo json_encode(["ok" => false, "mensaje" => "No se pudo abrir el archivo CSV."]);
    exit;
}

if ($esNuevo) {
    $encabezado = [
        'FECHA_REGISTRO',
        'EMPRESA_NOMBRE',
        'EMPRESA_CUIT',
        'EST_NOMBRE',
        'EST_SECCIONAL',
        'EST_DIRECCION',
        'EST_COD_POS',
        'CONVENIO',
        'TIPO',
        'CONCEPTO',
        'CONCEPTO_ID',
        'DETALLE',
        'DETALLE_ID',
        'TIPO_PAGO',
        'NUMERO_ACTA',
        'ACUERDO_NUMERO',
        'REMUNERACION',
        'IMPORTE',
        'RECARGOS',
        'TOTAL',
        'CTA_BANCO',
        'CODIGO_BARRA',
        'FECHA_CALCULO',
        'VAL ORIGINALES',
    ];
    fputcsv($fp, $encabezado, ';');
}

// ── Dirección limpia ──────────────────────────────────────
$dirRaw   = $b['est_direccion'] ?? '';
$dirPartes = explode('·', $dirRaw);
$dirLimpia = trim($dirPartes[0]);

if($_SESSION['boleta']['metodo_de_pago'] == "mp"){
    $codbar = $b['codigo_barra']['codebar_mp'] ?? null;
    
    $remuneracion = number_format((float)($b['total_remuneraciones_mp'] ?? 0), 2, ',', '.');
    $importe = number_format((float)($b['importe_mp']              ?? 0), 2, ',', '.');
    $recargos = number_format((float)($b['recargos_mp']             ?? 0), 2, ',', '.');
    
    $total_depo = (float)($b['total_mp'] ?? 0);
    
}else{
    $remuneracion = number_format((float)($b['total_remuneraciones'] ?? 0), 2, ',', '.');
    $importe = number_format((float)($b['importe']              ?? 0), 2, ',', '.');
    $recargos = number_format((float)($b['recargos']             ?? 0), 2, ',', '.');
    
    $total_depo = (float)($b['total'] ?? 0);
    
    $codbar = $b['codigo_barra']['codebar'] ?? null;
}

// ── Fila de datos ─────────────────────────────────────────
$fila = [
    date('d/m/Y H:i:s'),
    $s['empresa_nombre']       ?? '',
    $s['empresa_cuit']         ?? '',
    $b['est_nombre']           ?? '',
    $s['est_seccional']        ?? '',
    $dirLimpia ?: trim(($s['est_calle'] ?? '') . ' ' . ($s['est_numero'] ?? '')),
    $s['est_cod_pos']          ?? '',
    $s['est_convenio_nombre']  ?? '',
    $s['est_tipo_nombre']      ?? '',
    $b['concepto']             ?? '',
    $b['concepto_id']          ?? '',
    $b['detalle']              ?? '',
    $b['detalle_id']           ?? '',
    $b['tipopago']             ?? '',
    $b['numero_acta']          ?? '',
    $b['acuerdo_numero']       ?? '',
    $remuneracion,
    $importe              ?? '',
    $recargos             ?? '',
    $total_depo                ?? '',
    $b['ctabanco']             ?? '',
    $codbar ?? '',
    $b['metodo_de_pago'] ?? '',
    
    $b['codigo_barra']['remuneracion_original'] ?? '',
    $b['codigo_barra']['intereses_original'] ?? '',
    $b['codigo_barra']['imp_total_original'] ?? '',
];

fputcsv($fp, $fila, ';');
fclose($fp);

echo json_encode(["ok" => true, "archivo" => $mes . $anio . '.csv']);