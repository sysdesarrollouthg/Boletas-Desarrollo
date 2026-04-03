<?php
/**
 * generarboleta.php
 * ─────────────────────────────────────────────────────────────
 * Lee $_SESSION['boleta'] y genera el PDF con FPDF.
 * Se accede directo (no pasa por api.php ni plantilla.php).
 * Puede embeberse en un <iframe> o abrirse en pestaña nueva.
 * ─────────────────────────────────────────────────────────────
 */
 
error_reporting(E_ALL);
ini_set('display_errors', 1);  // ← cambiar de 0 a 1
ob_start();
session_start();

if (empty($_SESSION['boleta'])) {
    http_response_code(400);
    echo "No hay boleta en sesión. Volvé a calcular.";
    exit;
}

$b = $_SESSION['boleta'];
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
//die();
// ── Separar est_direccion (viene con seccional pegada: "CALLE 123  ·  Sec. XXX") ──
$dirRaw    = $b['est_direccion'] ?? '';
$dirPartes = explode('·', $dirRaw);
$dirLimpia = trim($dirPartes[0]);  // solo calle y número

// ── Tomar datos extra directamente de $_SESSION (fuera de boleta) ──
$cp        = $_SESSION['est_cod_pos'] ?? '';
$calle     = $_SESSION['est_calle']   ?? '';
$numero    = $_SESSION['est_numero']  ?? '';

if($_SESSION['boleta']['metodo_de_pago'] == "mp"){
    $codbar = $_SESSION['boleta']['codigo_barra']['codebar_mp'] ?? null;
    
    $remuneracion = number_format((float)($b['total_remuneraciones_mp'] ?? 0), 2, ',', '.');
    $importe = number_format((float)($b['importe_mp']              ?? 0), 2, ',', '.');
    $recargos = number_format((float)($b['recargos_mp']             ?? 0), 2, ',', '.');
    
    $total_depo = (float)($b['total_mp'] ?? 0);
    
}else{
    $remuneracion = number_format((float)($b['total_remuneraciones'] ?? 0), 2, ',', '.');
    $importe = number_format((float)($b['importe']              ?? 0), 2, ',', '.');
    $recargos = number_format((float)($b['recargos']             ?? 0), 2, ',', '.');
    
    $total_depo = (float)($b['total'] ?? 0);
    
    $codbar = $_SESSION['boleta']['codigo_barra']['codebar'] ?? null;
}

$direccion = $dirLimpia ?: trim("$calle $numero");

$str_nro_cuota = (!empty($b['cuota_desde']) && !empty($b['cuota_hasta']))
    ? $b['cuota_desde'] . ' / ' . $b['cuota_hasta']
    : '';

// ── Mapeo SESSION → estructura que espera PDF_boletas ─────────
$datos = [
    'titulo'                => $b['titulo']   ?? '',
    'convenio'              => $b['detalle']   ?? '',
    'concepto'              => $b['concepto']  ?? '',
    'desconvenio'           => $b['desconvenio']  ?? '',
	'empresa_cuit'          => $b['empresa_cuit'] ?? '',
    'des_convenio_banco'    => $b['des_convenio_banco']  ?? '',
    'ctabanco'              => $b['ctabanco']  ?? '',
    'detalleconc'           => $b['detalle']  ?? '',
    'cuenta_numero'         => '',
    'tipo_pago' => [
        'desc'  => ($b['tipopago'] ?? ''),
    ],
    'pago_periodo' => [
        'mes'  => $b['periodo_mes']  ?? '',
        'anio' => $b['periodo_anio'] ?? '',
    ],
    'empresa' => [
        'razon_social'      => ($b['empresa_nombre'] ?? '') . ' - ' . ($b['empresa_cuit'] ?? ''),
        'establecimiento'   => $b['est_nombre']     ?? '',
        'direccion'         => $direccion,
        'seccional'         => $b['est_seccional'] ?? '',
        'cp'                => $cp,
        'tel'               => '',
    ],
    'cat_empleados'         => $b['cant_empleados']      ?? '',
    'remuneracion'          => $remuneracion,
    'importe'               => $importe,

    'recargos'              => $recargos,
    'tot_depo'              => $total_depo,
    'nro_acta'              => $b['numero_acta'] ?? '',
    'nro_acue'              => $b['acuerdo_numero'] ?? '',
    'nro_cuot'              => $str_nro_cuota,
    'nro_mora'              => '',
    'nro_cheque'            => '',
    'tipodepago'            => $b['tipodepago']  ?? '',
    'codigos_barras' => [
        substr($codbar, 0, 36),
        substr($codbar, 36, 36)
    ],
    'cuota_desde'           => $b['cuota_desde'] ?? '',   
    'cuota_hasta'           => $b['cuota_hasta'] ?? '',   
    'tipopago'              => $b['tipopago'] ?? '',      
    'genboletaauto'         => $b['genboletaauto'] ?? '' ,
    
    'codebaroverflow'       => $b['codebaroverflow'] ?? '',
    
    'metodo_de_pago'        => $b['metodo_de_pago'] ?? ''
];
//echo 'tipo_pago: '.$datos['tipo_pago']['desc'];
/*echo '<pre>';
print_r($_SESSION);
print_r($datos);
echo '</pre>';*/
//die();
// ── Librerías ─────────────────────────────
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/fpdf/fpdf.php';

// ── Clase PDF (copiada de genboletapdf.php, sin cambios) ──────
class PDF_boletas extends FPDF
{
    private $datos;
	private $infoCuotas;

    public function __construct($datos)
    {
        parent::__construct();
        $this->datos = $datos;
    }

    function setCodebar($codebar)
    {
        $rutaBarcode = $this->generarBarcodeTemporal($codebar);

        $anchoDisponible = $this->GetPageWidth() - $this->lMargin - $this->rMargin;

        list($widthPx, $heightPx) = getimagesize($rutaBarcode);

        $mmPorPixel  = 0.264583;

        // Factor deseado para ancho del código de barras (30% del ancho disponible)
        $factorDeseado = 0.45;
        $anchoImagen = $anchoDisponible * $factorDeseado;
        $altoImagen  = $heightPx * $mmPorPixel * ($anchoImagen / ($widthPx * $mmPorPixel));

        $x = $this->GetX(); 
        $y = $this->GetY();

        // Centrar horizontalmente
        $xImagen = $x + ($anchoDisponible - $anchoImagen) / 2;

        // Dibujar imagen
        $this->Image($rutaBarcode, $xImagen, $y, $anchoImagen);

        // Separación para los números debajo
        $espacioNumeros = 2; // mm
        $this->SetY($y + $altoImagen + $espacioNumeros);

        // Formatear y poner los números debajo
        $codigoFormateado = trim(chunk_split($codebar, 6, ' '));
        $this->SetFont('Arial', '', 9);
        $this->Cell($anchoDisponible, 5, $codigoFormateado, 0, 1, 'C');

        unlink($rutaBarcode);
        $this->Ln(2);
    }

    function generarBarcodeTemporal($codigo)
    {
        $generator   = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcodeData = $generator->getBarcode($codigo, $generator::TYPE_CODE_128);
        $rutaTemporal = sys_get_temp_dir() . '/barcode_' . uniqid() . '.png';
        file_put_contents($rutaTemporal, $barcodeData);
        return $rutaTemporal;
    }

    function convertir($texto)
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $texto);
    }

    function Header()
    {   /*
        $this->SetFont('Arial', '', 8);
        $this->Cell(160, 6, $this->convertir( $this->datos['des_convenio_banco'] ), 0, 0, 'L');
        $this->Cell(30,  6, $this->convertir($this->datos['empresa_cuit']), 1, 1, 'C');
        $this->Ln(3);
        */
        $this->SetFont('Arial', '', 6);
        
        //$this->Cell(0, 2, $this->convertir( date("d/m/Y H:i:s") ), 0, 1, 'R');
        $this->Cell(0, 2, $this->convertir( "fecha: ".date("d/m/Y") ), 0, 1, 'R');
        $this->Ln(3);
        
        $this->SetFont('Arial', '', 8);
        $this->Cell(160, 6, $this->convertir( $this->datos['des_convenio_banco'] ), 0, 0, 'L');
        $this->Cell(30,  6, $this->convertir($this->datos['empresa_cuit']), 1, 1, 'C');
        $this->Ln(3);
    }

    function numeroALetras2($numero)
    {
        $formatter = new NumberFormatter("es_AR", NumberFormatter::SPELLOUT);
        $entero    = floor($numero);
        $centavos  = round(($numero - $entero) * 100);
        $texto     = strtoupper($formatter->format($entero));
        return "SON PESOS $texto CON " . str_pad($centavos, 2, "0", STR_PAD_LEFT) . "/100";
    }

    function numeroALetras($numero)
    {
        $entero   = (int) floor($numero);
        $centavos = round(($numero - $entero) * 100);

        $unidades = ['','UNO','DOS','TRES','CUATRO','CINCO','SEIS','SIETE','OCHO','NUEVE',
                     'DIEZ','ONCE','DOCE','TRECE','CATORCE','QUINCE','DIECISÉIS','DIECISIETE',
                     'DIECIOCHO','DIECINUEVE'];
        $decenas  = ['','','VEINTE','TREINTA','CUARENTA','CINCUENTA','SESENTA','SETENTA','OCHENTA','NOVENTA'];
        $centenas = ['','CIENTO','DOSCIENTOS','TRESCIENTOS','CUATROCIENTOS','QUINIENTOS',
                     'SEISCIENTOS','SETECIENTOS','OCHOCIENTOS','NOVECIENTOS'];

        $convertir = function($n) use ($unidades, $decenas, $centenas, &$convertir) {
            if ($n == 0)   return 'CERO';
            if ($n == 100) return 'CIEN';
            if ($n < 20)   return $unidades[$n];
            if ($n < 100) {
                $d = intdiv($n, 10);
                $u = $n % 10;
                return $decenas[$d] . ($u ? ' Y ' . $unidades[$u] : '');
            }
            if ($n < 1000) {
                $c = intdiv($n, 100);
                $r = $n % 100;
                return $centenas[$c] . ($r ? ' ' . $convertir($r) : '');
            }
            if ($n < 1000000) {
                $miles = intdiv($n, 1000);
                $r     = $n % 1000;
                $txt   = ($miles == 1 ? 'MIL' : $convertir($miles) . ' MIL');
                return $txt . ($r ? ' ' . $convertir($r) : '');
            }
            $mill = intdiv($n, 1000000);
            $r    = $n % 1000000;
            $txt  = ($mill == 1 ? 'UN MILLÓN' : $convertir($mill) . ' MILLONES');
            return $txt . ($r ? ' ' . $convertir($r) : '');
        };
    
        $texto = $convertir($entero);
        return "SON PESOS $texto CON " . str_pad($centavos, 2, "0", STR_PAD_LEFT) . "/100";
    }

    function boleta($codigoBarra1, $codigoBarra2, $copia)
    {
        if ($copia == 1) {
            $this->Header();
        }
        $positionX = 10;

        $this->Cell(60, $positionX, '', 0, 0, 'L');

        $x = $this->GetX();
        $y = $this->GetY();

        $this->Cell(70, $positionX, '', 1, 1, 'C');

        $ruta = __DIR__ . '/img/uthgra.jpg';
        list($widthPx, $heightPx) = getimagesize($ruta);

        $mmPorPixel = 0.264583;
        $widthMm    = $widthPx  * $mmPorPixel;
        $heightMm   = $heightPx * $mmPorPixel;

        $maxWidth  = 70 - 4;
        $maxHeight = $positionX - 4;

        $scale     = min($maxWidth / $widthMm, $maxHeight / $heightMm);
        $newWidth  = $widthMm  * $scale;
        $newHeight = $heightMm * $scale;

        $xImagen = $x + (70 - $newWidth) / 2;
        $yImagen = $y + (($positionX - $newHeight) / 2) - (($positionX - $newHeight) / 16);

        $this->Image($ruta, $xImagen, $yImagen, $newWidth, $newHeight);

        $this->Cell(60, $positionX, '', 0, 0, 'R');
        $this->Ln(1);

        $this->SetFont('Arial', 'b', 6);
        $this->Cell(0, $positionX, $this->convertir("UNIÓN DE TRABAJADORES DEL TURISMO, HOTELEROS Y GASTRONÓMICOS DE LA REPÚBLICA ARGENTINA"), 0, 0, 'C');
        $this->Ln(3);

        $this->SetFont('Arial', 'b', 9);
        $this->Cell(0, $positionX, $this->convertir($this->datos['detalleconc']), 0, 0, 'L');
        $this->Ln(4);

        $this->SetFont('Arial', 'b', 7);
        $this->Cell(60, $positionX, 'Cta. Nro: ' . $this->datos['ctabanco'], 0, 0, 'L');
        $this->Cell(70, $positionX, $this->datos['titulo'], 0, 0, 'C');
        $this->Cell(60, $positionX, $this->convertir('Mes: ' . $this->datos['pago_periodo']['mes'] . ' / Año: ' . $this->datos['pago_periodo']['anio']), 0, 0, 'R');
        $this->Ln(7);

        $this->SetFont('Arial', 'B', 7);

        $this->Cell(50, 5, $this->convertir('Razón Social: '),           'LT', 0, 'R');
        $this->Cell(45, 5, $this->convertir($this->datos['empresa']['razon_social']), 'T',  0, 'L');
        $this->Cell(45, 5, '', 'T',  0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, '', 'TR', 1, 'R');

        $this->Cell(50, 5, $this->convertir('Establecimiento: '), 'L', 0, 'R');
        $this->Cell(45, 5, $this->convertir($this->datos['empresa']['establecimiento']), '', 0, 'L');
        $this->Cell(45, 5, '', '', 0, 'R');
        $this->Cell(50, 5, '', 'R', 1, 'R');

        $this->Cell(50, 5, $this->convertir('Dirección: '),    'L', 0, 'R');
        $this->Cell(45, 5, $this->convertir($this->datos['empresa']['direccion']), '', 0, 'L');
        $this->Cell(45, 5, 'C.P. ' . $this->convertir($this->datos['empresa']['cp'] ?? ''), '', 0, 'R');
        $this->Cell(50, 5, '', 'R', 1, 'R');

        $this->Cell(50, 5, $this->convertir('Seccional: '),    'L', 0, 'R');
        $this->Cell(45, 5, $this->convertir($this->datos['empresa']['seccional']), '', 0, 'L');
        $this->Cell(45, 5, 'TEL. ' . $this->convertir($this->datos['empresa']['tel'] ?? ''), '', 0, 'R');
        $this->Cell(50, 5, '', 'R', 1, 'R');

        //$this->Cell(45, 5, '', 'B', 0, 'R'); //comenté porque rompe la tablita
        //$this->Cell(50, 5, '', 'RB', 1, 'R'); //comenté porque rompe la tablita

        $this->Cell(50, 4, $this->convertir('EMPLEADOS'),    'LRT', 0, 'C');
        $this->Cell(45, 4, $this->convertir('REMUNERACIÓN'), 'RT',  0, 'C');
        $this->Cell(45, 4, 'IMPORTE',                        'RT',  0, 'C');
        $this->Cell(50, 4, 'TOTAL/PARCIAL',                  'RT',  1, 'C');

        $this->Cell(50, 4, $this->convertir($this->datos['cat_empleados']),       'BLR', 0, 'C');
        $this->Cell(45, 4, $this->convertir($this->datos['remuneracion']),        'BR',  0, 'C');
        $this->Cell(45, 4, $this->convertir($this->datos['importe']        ?? ''), 'BR', 0, 'C');
        $this->Cell(50, 4, $this->convertir($this->datos['tipo_pago']['desc']  ?? ''), 'BR', 1, 'C');

        $this->Cell(50, 4, $this->convertir('Acta Nº'),     'LR', 0, 'C');
        $this->Cell(45, 4, $this->convertir('Acuerdo Nº'),  'R',  0, 'C');
        $this->Cell(45, 4, $this->convertir('Cuota Nº'),    'R',  0, 'C');
        $this->Cell(50, 4, $this->convertir('MORATORIA Nº'),'R',  1, 'C');

        $this->Cell(50, 4, $this->convertir($this->datos['nro_acta'] ?? ''), 'BLR', 0, 'C');
        $this->Cell(45, 4, $this->convertir($this->datos['nro_acue'] ?? ''), 'BR',  0, 'C');

        if ($this->infoCuotas !== null) {
            $this->Cell(45, 4, $this->infoCuotas->numero.'/'.$this->infoCuotas->numero, 'BR',  0, 'C');
        } else{
            //$this->Cell(45, 4, $this->convertir($this->datos['nro_cuot'] ?? ''), 'BR',  0, 'C');
            $this->Cell(45, 4, $this->convertir($this->datos['cuota_desde'] ?? ''), 'BR',  0, 'C');    
        }

        $this->Cell(50, 4, $this->convertir($this->datos['nro_mora'] ?? ''), 'BR',  1, 'C');

        $this->Cell(50, 4, $this->convertir('RECARGOS'), 'LR', 0, 'C');
        $this->Cell(45, 4, '', '',   0, 'C');
        $this->Cell(45, 4, '', 'R',  0, 'C');
        $this->Cell(50, 4, 'TOTAL DEPOSITADO', 'R', 1, 'C');

        $this->Cell(50, 4, $this->convertir($this->datos['recargos']    ?? ''), 'BLR', 0, 'C');
        $this->Cell(45, 4, '', '',   0, 'C');
        $this->Cell(45, 4, '', 'R',  0, 'C');
        $this->Cell(50, 4, $this->convertir(number_format($this->datos['tot_depo'], 2, ',', '.')), 'BR', 1, 'C');

        $this->Cell(95, 4, $this->convertir($this->numeroALetras($this->datos['tot_depo'])), '', 0, 'L');
        $this->Cell(95, 4, '', '', 1, 'R');

        // $this->Cell(95, 4, $this->convertir('Cheque Nº ' . ($this->datos['nro_cheque'] ?? '')), '', 0, 'L');
        $this->Cell(95, 4, $this->convertir('Cheque Nº '), '', 0, 'L');
        $this->Cell(95, 4, '', '', 1, 'R');

        $anchoUtil = $this->GetPageWidth() - $this->lMargin - $this->rMargin;

        $this->Cell($anchoUtil, 3, 'Banco', 0, 1, 'C');

        $this->setCodebar($codigoBarra1);
        $this->setCodebar($codigoBarra2);

        $xActual   = $this->GetX();
        $yActual   = $this->GetY();
        /*
        $logoPath  = __DIR__ . '/img/mercadopago.png';

        if (file_exists($logoPath)) {
            list($wpx, $hpx) = getimagesize($logoPath);
            $altoLogo  = 6;
            $anchoLogo = ($wpx / $hpx) * $altoLogo;
            $this->Image($logoPath, $xActual, $yActual + 0.5, $anchoLogo, $altoLogo);
            $this->SetXY($xActual + $anchoLogo + 2, $yActual);
            $this->Cell(($anchoUtil / 2) - $anchoLogo - 2, 7, $this->convertir('Pagar con MercadoPago'), 0, 0, 'L');
        } else {
            $this->Cell($anchoUtil / 2, 7, $this->convertir('Leer códigos de arriba hacia abajo'), 0, 0, 'L');
        }*/
        $this->Cell($anchoUtil / 2, 7, $this->convertir('Leer códigos de arriba hacia abajo'), 0, 0, 'L');

        if ($copia == 0) {
            $this->Cell($anchoUtil / 2, 7, $this->convertir('Talón para el Contribuyente'), 0, 1, 'R');
        } else {
            $this->Cell($anchoUtil / 2, 7, $this->convertir('Talón para el Banco'), 0, 1, 'R');
        }
    }

    function BoletaSinCodBarras()
    {
        //if ($copia == 1) {
            //$this->Header();
        //}
        $positionX = 10;

        $this->Cell(60, $positionX, '', 0, 0, 'L');

        $x = $this->GetX();
        $y = $this->GetY();

        $this->Cell(70, $positionX, '', 1, 1, 'C');

        $ruta = __DIR__ . '/img/uthgra.jpg';
        list($widthPx, $heightPx) = getimagesize($ruta);

        $mmPorPixel = 0.264583;
        $widthMm    = $widthPx  * $mmPorPixel;
        $heightMm   = $heightPx * $mmPorPixel;

        $maxWidth  = 70 - 4;
        $maxHeight = $positionX - 4;

        $scale     = min($maxWidth / $widthMm, $maxHeight / $heightMm);
        $newWidth  = $widthMm  * $scale;
        $newHeight = $heightMm * $scale;

        $xImagen = $x + (70 - $newWidth) / 2;
        $yImagen = $y + (($positionX - $newHeight) / 2) - (($positionX - $newHeight) / 16);

        $this->Image($ruta, $xImagen, $yImagen, $newWidth, $newHeight);

        $this->Cell(60, $positionX, '', 0, 0, 'R');
        $this->Ln(1);

        $this->SetFont('Arial', 'b', 6);
        $this->Cell(0, $positionX, $this->convertir("UNIÓN DE TRABAJADORES DEL TURISMO, HOTELEROS Y GASTRONÓMICOS DE LA REPÚBLICA ARGENTINA"), 0, 0, 'C');
        $this->Ln(3);

        $this->SetFont('Arial', 'b', 9);
        $this->Cell(0, $positionX, $this->convertir($this->datos['detalleconc']), 0, 0, 'L');
        $this->Ln(4);

        $this->SetFont('Arial', 'b', 7);
        $this->Cell(60, $positionX, 'Cta. Nro: ' . $this->datos['ctabanco'], 0, 0, 'L');
        $this->Cell(70, $positionX, $this->datos['titulo'], 0, 0, 'C');
        $this->Cell(60, $positionX, $this->convertir('Mes: ' . $this->datos['pago_periodo']['mes'] . ' / Año: ' . $this->datos['pago_periodo']['anio']), 0, 0, 'R');
        $this->Ln(7);

        $this->SetFont('Arial', 'B', 7);

        $this->Cell(50, 5, $this->convertir('Razón Social: '),           'LT', 0, 'R');
        $this->Cell(45, 5, $this->convertir($this->datos['empresa']['razon_social']), 'T',  0, 'L');
        $this->Cell(45, 5, '', 'T',  0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, '', 'TR', 1, 'R');

        $this->Cell(50, 5, $this->convertir('Establecimiento: '), 'L', 0, 'R');
        $this->Cell(45, 5, $this->convertir($this->datos['empresa']['establecimiento']), '', 0, 'L');
        $this->Cell(45, 5, '', '', 0, 'R');
        $this->Cell(50, 5, '', 'R', 1, 'R');

        $this->Cell(50, 5, $this->convertir('Dirección: '),    'L', 0, 'R');
        $this->Cell(45, 5, $this->convertir($this->datos['empresa']['direccion']), '', 0, 'L');
        $this->Cell(45, 5, 'C.P. ' . $this->convertir($this->datos['empresa']['cp'] ?? ''), '', 0, 'R');
        $this->Cell(50, 5, '', 'R', 1, 'R');

        $this->Cell(50, 5, $this->convertir('Seccional: '),    'L', 0, 'R');
        $this->Cell(45, 5, $this->convertir($this->datos['empresa']['seccional']), '', 0, 'L');
        $this->Cell(45, 5, 'TEL. ' . $this->convertir($this->datos['empresa']['tel'] ?? ''), '', 0, 'R');
        $this->Cell(50, 5, '', 'R', 1, 'R');

        //$this->Cell(45, 5, '', 'B', 0, 'R'); //comenté porque rompe la tablita
        //$this->Cell(50, 5, '', 'RB', 1, 'R'); //comenté porque rompe la tablita

        $this->Cell(50, 4, $this->convertir('EMPLEADOS'),    'LRT', 0, 'C');
        $this->Cell(45, 4, $this->convertir('REMUNERACIÓN'), 'RT',  0, 'C');
        $this->Cell(45, 4, 'IMPORTE',                        'RT',  0, 'C');
        $this->Cell(50, 4, 'TOTAL/PARCIAL',                  'RT',  1, 'C');

        $this->Cell(50, 4, $this->convertir($this->datos['cat_empleados']),       'BLR', 0, 'C');
        $this->Cell(45, 4, $this->convertir($this->datos['remuneracion']),        'BR',  0, 'C');
        $this->Cell(45, 4, $this->convertir($this->datos['importe']        ?? ''), 'BR', 0, 'C');
        $this->Cell(50, 4, $this->convertir($this->datos['tipo_pago']['desc']  ?? ''), 'BR', 1, 'C');

        $this->Cell(50, 4, $this->convertir('Acta Nº'),     'LR', 0, 'C');
        $this->Cell(45, 4, $this->convertir('Acuerdo Nº'),  'R',  0, 'C');
        $this->Cell(45, 4, $this->convertir('Cuota Nº'),    'R',  0, 'C');
        $this->Cell(50, 4, $this->convertir('MORATORIA Nº'),'R',  1, 'C');

        $this->Cell(50, 4, $this->convertir($this->datos['nro_acta'] ?? ''), 'BLR', 0, 'C');
        $this->Cell(45, 4, $this->convertir($this->datos['nro_acue'] ?? ''), 'BR',  0, 'C');

        if ($this->infoCuotas !== null) {
            $this->Cell(45, 4, $this->infoCuotas->numero.'/'.$this->infoCuotas->numero, 'BR',  0, 'C');
        } else{
            //$this->Cell(45, 4, $this->convertir($this->datos['nro_cuot'] ?? ''), 'BR',  0, 'C');
            $this->Cell(45, 4, $this->convertir($this->datos['cuota_desde'] ?? ''), 'BR',  0, 'C');    
        }

        $this->Cell(50, 4, $this->convertir($this->datos['nro_mora'] ?? ''), 'BR',  1, 'C');

        $this->Cell(50, 4, $this->convertir('RECARGOS'), 'LR', 0, 'C');
        $this->Cell(45, 4, '', '',   0, 'C');
        $this->Cell(45, 4, '', 'R',  0, 'C');
        $this->Cell(50, 4, 'TOTAL DEPOSITADO', 'R', 1, 'C');

        $this->Cell(50, 4, $this->convertir($this->datos['recargos']    ?? ''), 'BLR', 0, 'C');
        $this->Cell(45, 4, '', '',   0, 'C');
        $this->Cell(45, 4, '', 'R',  0, 'C');
        $this->Cell(50, 4, $this->convertir(number_format($this->datos['tot_depo'], 2, ',', '.')), 'BR', 1, 'C');

        $this->Cell(95, 4, $this->convertir($this->numeroALetras($this->datos['tot_depo'])), '', 0, 'L');
        $this->Cell(95, 4, '', '', 1, 'R');

        // $this->Cell(95, 4, $this->convertir('Cheque Nº ' . ($this->datos['nro_cheque'] ?? '')), '', 0, 'L');
        //$this->Cell(95, 4, $this->convertir('Cheque Nº '), '', 0, 'L');
        //$this->Cell(95, 4, '', '', 1, 'R');

        $anchoUtil = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
        
        $this->SetFont('Arial', 'b', 10);
        $this->Cell(0, 13, $this->convertir("RECUERDE QUE ESTE COMPROBANTE SIRVE A MODO INFORMATIVO"), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 18, $this->convertir("ESTE COMPROBANTE NO PUEDE PAGARSE POR BANCO NACIÓN, SOLAMENTE POR MERCADO PAGO."), 0, 0, 'C');
        /*$this->Ln(18);
        $this->Cell(0, 0, $this->datos['codigos_barras'][0].$this->datos['codigos_barras'][1], 0, 0, 'C');*/
      }

    function Body($codigoBarra1, $codigoBarra2, $infoCuota = [])
    {
		$this->infoCuotas = $infoCuota;
        $this->boleta($codigoBarra1, $codigoBarra2, 0);
        $this->boleta($codigoBarra1, $codigoBarra2, 1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $this->convertir('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// ── Generar PDF ───────────────────────────────────────────────
$codigoBarra1 = $datos['codigos_barras'][0] ?? null;
$codigoBarra2 = $datos['codigos_barras'][1] ?? null;

if (!$codigoBarra1 || !$codigoBarra2) {
    http_response_code(400);
    echo "Faltan códigos de barras en el JSON";
    exit;
}

try {
    
    if( $datos['metodo_de_pago'] == 'mp' ){
        
        $pdf = new PDF_boletas($datos);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->BoletaSinCodBarras();
    
        ob_end_clean();
    
        $pdf->Output('I', 'boleta_' . date('Ymd_His') . '.pdf');
        $pdf->AddPage(); 
        
    }else{
        
        if($datos['tipodepago'] == 1 || $datos['tipodepago'] == 2 || ($datos['tipodepago'] == 3 && $datos['tipopago'] == "Parcial")){
            $pdf = new PDF_boletas($datos);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->Body($codigoBarra1, $codigoBarra2, null);
    
           ob_end_clean();
    
            $pdf->Output('I', 'boleta_' . date('Ymd_His') . '.pdf');
            $pdf->AddPage(); 
        }else{ //AGREGADO jorge
    
            //echo $datos['tipopago'] === "Total"; 
            $genboletaauto = $datos['genboletaauto'];
            $cuotadesde = $datos['cuota_desde'];
            $cuotahasta = ($genboletaauto==1?$datos['cuota_hasta']:$datos['cuota_desde']);
            /*
            echo "cuotadesde: ".$cuotadesde."<br>";
            echo "cuotahasta: ".$cuotahasta."<br>";
            echo "genboletaauto: ".$genboletaauto."<br>";
            die();*/
            $pdf = new PDF_boletas($datos);
            $pdf->AliasNbPages();
    
            for ($i = $cuotadesde; $i <= $cuotahasta; $i++) {
                $infoCuota = 
                    (object)[
                        'numero' => $i,
                        'desde' => $cuotadesde,
                        'hasta' => $cuotahasta,
                        'total' => ((int)$cuotahasta - (int)$cuotadesde + 1),
                        'es_primera' => ($i == $cuotadesde),
                        'es_ultima' => ($i == $cuotahasta)
                    ];
    
                $pdf->AddPage();
                $pdf->Body($codigoBarra1, $codigoBarra2, $infoCuota);
    
            }
    
            ob_clean();
    
            // Enviar el PDF
            $pdf->Output('I', 'boleta_' . date('Ymd_His') . '.pdf');
    	}
        
    }

    
} catch (Exception $e) {
    // En caso de error, limpiar buffer y mostrar error
    ob_clean();
    echo "Error al generar PDF: " . $e->getMessage();
}
