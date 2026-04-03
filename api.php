<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * ╔═════════════════════════════════════════════════════════════╗
 * ║  api.php — Gateway único para peticiones AJAX               ║
 * ╠═════════════════════════════════════════════════════════════╣
 * ║  Todas las llamadas fetch/AJAX del frontend pasan por       ║
 * ║  este archivo. Ningún controller se invoca directo.         ║
 * ║                                                             ║
 * ║  Capas de seguridad:                                        ║
 * ║    1. Solo acepta método POST                               ║
 * ║    2. Verifica header X-Requested-With (AJAX)               ║
 * ║    3. Valida token CSRF contra la sesión                    ║
 * ║    4. Whitelist de módulos y acciones permitidas            ║
 * ║    5. Constante API_GATEWAY para doble verificación         ║
 * ║                                                             ║
 * ║  Uso desde JS:                                              ║
 * ║    fetch("api.php", {                                       ║
 * ║      method: "POST",                                        ║
 * ║      headers: {                                             ║
 * ║        "Content-Type": "application/x-www-form-urlencoded", ║
 * ║        "X-Requested-With": "XMLHttpRequest"                 ║
 * ║      },                                                     ║
 * ║      body: new URLSearchParams({                            ║
 * ║        modulo: "consulta",                                  ║
 * ║        action: "buscar",                                    ║
 * ║        csrf_token: CSRF_TOKEN,                              ║
 * ║        ...datos                                             ║
 * ║      })                                                     ║
 * ║    });                                                      ║
 * ╚═════════════════════════════════════════════════════════════╝
 */


ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 3600);
session_start();
define('APP_INIT', true);
define('API_GATEWAY', true);

header('Content-Type: application/json; charset=utf-8');


// ─── 1. Solo POST ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["ok" => false, "mensaje" => "Método no permitido."]);
    exit;
}

// ─── 2. Verificar que sea AJAX ───────────────────────────────
if (
    empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    http_response_code(403);
    echo json_encode(["ok" => false, "mensaje" => "Acceso denegado."]);
    exit;
}

// ─── 3. CSRF — generar o validar ─────────────────────────────
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$tokenEnviado = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

if (!hash_equals($_SESSION['csrf_token'], $tokenEnviado)) {
    http_response_code(403);
    echo json_encode(["ok" => false, "mensaje" => "Token de seguridad inválido."]);
    exit;
}

// ─── 4. Leer módulo y acción ─────────────────────────────────
$modulo = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['modulo'] ?? '');
$action = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['action'] ?? '');

if (empty($modulo) || empty($action)) {
    http_response_code(400);
    echo json_encode(["ok" => false, "mensaje" => "Parámetros incompletos."]);
    exit;
}

// ─── 5. Whitelist de módulos permitidos ──────────────────────
//   Cada módulo nuevo se registra acá con sus acciones válidas.
$controladores = [
    'consulta' => [
        'archivo'  => __DIR__ . '/controller/ConsultaController.php',
        'clase'    => 'ConsultaController',
        'acciones' => ['buscar'],
    ],
    'establecimiento' => [
        'archivo'  => __DIR__ . '/controller/EstableController.php',
        'clase'    => 'EstableController',
        'acciones' => ['listar', 'limpiar', 'formdata', 'agregar', 'editar', 'seleccionar', 'selprovincia', 'selpartido', 'ingcodpos']
    ],
    'crearempresa' => [
        'archivo'  => __DIR__ . '/controller/CrearEmpresa-controller.php',
        'clase'    => 'CrearEmpresaController',
        'acciones' => ['registrar'],
    ],
    'concepto' => [
        'archivo'  => __DIR__ . '/controller/ConceptoController.php', 
        'clase'    => 'ConceptoController',
        'acciones' => ['listar', 'detalle', 'vencimiento', 'calcularTotal', 'contexto', 'guardarBoleta', 'crearPreferencia', 'gendeudaRedLink'],
    ],
    'actas' => [
        'archivo'  => __DIR__ . '/controller/ActasController.php', 
        'clase'    => 'ActasController',
        'acciones' => ['guardarBoleta', 'crearPreferencia'],
    ],
    'acuerdos' => [
        'archivo'  => __DIR__ . '/controller/AcuerdosController.php', 
        'clase'    => 'AcuerdosController',
        'acciones' => ['guardarBoleta', 'crearPreferencia'],
    ],
    'pagos' => [
        'archivo'  => __DIR__ . '/controller/PagosController.php',
        'clase'    => 'PagosController',
        'acciones' => [
            'crearPreferenciaMercadoPago',
            'notificacionMercadoPago',
            'generarPagoEfectivo',
            'generarPagoTransferencia',
            'gendeudaRedLink'
        ],
    ],
    // 'login' => [
    //     'archivo'  => __DIR__ . '/controller/LoginController.php',
    //     'clase'    => 'LoginController',
    //     'acciones' => ['ingresar', 'cerrar'],
    // ],
];

if (!isset($controladores[$modulo])) {
    http_response_code(404);
    echo json_encode(["ok" => false, "mensaje" => "Módulo no encontrado."]);
    exit;
}

$cfg = $controladores[$modulo];

if (!in_array($action, $cfg['acciones'], true)) {
    http_response_code(403);
    echo json_encode(["ok" => false, "mensaje" => "Acción no permitida."]);
    exit;
}

// ─── 6. Cargar controller y ejecutar ─────────────────────────
require_once $cfg['archivo'];

$ctrl = new $cfg['clase']();

if (!method_exists($ctrl, $action)) {
    http_response_code(500);
    echo json_encode(["ok" => false, "mensaje" => "Método inexistente."]);
    exit;
}

$ctrl->$action();
