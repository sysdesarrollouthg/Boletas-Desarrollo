<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/api_errors.log');

error_log("genbotonmp response: " . print_r($response, true));
error_log("genbotonmp data: " . print_r($data_response, true));
*/


$boleta = $_SESSION['boleta'];

$meses = [
    "Enero"         => "01",
    "Febrero"       => "02",
    "Marzo"         => "03",
    "Abril"         => "04",
    "Mayo"          => "05",
    "Junio"         => "06",
    "Julio"         => "07",
    "Agosto"        => "08",
    "Septiembre"    => "09",
    "Octubre"       => "10",
    "Noviembre"     => "11",
    "Diciembre"     => "12",
];

//$mp_mes = $meses[$boleta["periodo_mes"]];
$curl = curl_init();

// Obtener mes y hora actuales
date_default_timezone_set('America/Argentina/Buenos_Aires');
$cMes  = date('m');   // mes 01-12
$cHora = date('H');   // hora 00-23

// 1. Definimos el Payload como un array asociativo de PHP
$payload = [
    "items" => [
        [
            "title"       => $boleta["detalle"],
            "quantity"    => 1,
            "currency_id" => "ARS",
            "unitPrice"   => $boleta["total_mp"]
        ]
    ],
    "payer" => [
        "name"    => $_SESSION["empresa_cuit"] . '_' . $_SESSION["empresa_nombre"],
        "surname" => $_SESSION["est_razon_social"],
        "email"   => ""
    ],
    "additional_info"   => "",
    "auto_return"       => "approved",
    "ExternalReference" => $boleta["codigo_barra"]["codebar_mp_sin_importe"] . $boleta["codigo_barra"]["digito_mp"],
    "metadata" => [
        "id_metadata" => $boleta["codigo_barra"]["codebar_mp"]
    ],
    "back_urls" => [
        "success" => "https://mi.uthgra.org.ar:8161/Home/Redirect?msg=ok",
        "pending" => "https://mi.uthgra.org.ar:8161/Home/Redirect?msg=pend",
        "failure" => "https://mi.uthgra.org.ar:8161/Home/Redirect?msg=error"
    ],
    "PaymentMethods" => [
        "ExcludedPaymentMethods" => [
            [],
        ],
        "ExcludedPaymentTypes" => [
            ["id" => "ticket"]
        ]
    ]
];

// 2. Configuramos cURL de forma limpia
curl_setopt_array($curl, [
    CURLOPT_URL            => 'https://mi.uthgra.org.ar:8161/api/Preference/CreatePreferenceAsync',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING       => '',
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 30, // Evitá usar 0 (infinito) en producción
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST  => 'POST',
    CURLOPT_POSTFIELDS     => json_encode($payload), // Convertimos el array a JSON automáticamente
    CURLOPT_HTTPHEADER     => [
        'CuentaMP: ' . $boleta['pv_key'],
        'AuthorizationWsp: uthgraMP' . $cMes . $cHora,
        'Content-Type: application/json'
    ],
]);

$verbose = fopen('php://temp', 'w+');

curl_setopt($curl, CURLOPT_VERBOSE, true);
curl_setopt($curl, CURLOPT_STDERR, $verbose);

$response = curl_exec($curl);

// Rebobinamos el log
rewind($verbose);
$verboseLog = stream_get_contents($verbose);
echo "<!--";
echo "<pre>";

echo "===== CURL CMD =====\n";
echo "curl -X POST 'https://mi.uthgra.org.ar:8161/api/Preference/CreatePreferenceAsync' \n";
echo "-H 'CuentaMP: {$boleta['pv_key']}' \n";
echo "-H 'AuthorizationWsp: uthgraMP{$cMes}{$cHora}' \n";
echo "-H 'Content-Type: application/json' \n";
echo "-d '" . json_encode($payload, JSON_PRETTY_PRINT) . "'\n\n";

echo "===== RESPONSE =====\n";
print_r($response);

echo "\n\n===== CURL VERBOSE =====\n";
echo $verboseLog;

echo "\n\n===== ERROR =====\n";
if (curl_errno($curl)) {
    echo curl_error($curl);
} else {
    echo "Sin errores";
}

echo "</pre>";
echo "-->";

// Verificar errores de cURL
if ($response === false) {
    curl_close($curl);
    return null; // o manejar error
}

curl_close($curl);

// Decodificar JSON
$data_response = json_decode($response, true);

// Verificar JSON válido
if (json_last_error() !== JSON_ERROR_NONE) {
    return null;
}

// Verificar estructura antes de acceder
if (
    isset($data_response['Result']) &&
    isset($data_response['Result']['Id'])
) {
    return $data_response['Result']['Id'];
}

return null;
?>