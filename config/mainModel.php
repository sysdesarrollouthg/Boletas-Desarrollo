<?php
/**
 * ╔═══════════════════════════════════════════════════════════════════╗
 * ║  mainModel.php — Modelo base de la aplicación                    ║
 * ╠═══════════════════════════════════════════════════════════════════╣
 * ║  Clase padre de todos los modelos. Provee funcionalidades        ║
 * ║  comunes que heredan los demás:                                  ║
 * ║                                                                  ║
 * ║    • conectar()              → Conexión PDO a la base de datos   ║
 * ║    • ejecutar_consulta_simple() → Consulta directa sin params    ║
 * ║    • manejadorRespuesta()    → Ejecuta y devuelve JSON estándar  ║
 * ║    • limpiar_cadena()        → Sanitización de strings           ║
 * ║    • encryption/decryption() → Cifrado AES simétrico             ║
 * ║    • generar_codigo_aleatorio() → Código numérico random         ║
 * ║                                                                  ║
 * ║  Todos los controllers y models heredan de esta clase,           ║
 * ║  por lo que cualquier método agregado acá queda disponible       ║
 * ║  en toda la aplicación.                                          ║
 * ╚═══════════════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/guard.php";
include_once __DIR__ . "/app.php";
define('MP_API_URL', 'https://api.mercadopago.com/checkout/preferences');


class mainModel
{
    /* ═══════════════════════════════════════════
       CONEXIÓN A BASE DE DATOS
       Usa las constantes SGBD, USER y PASS
       definidas en config/app.php
       ═══════════════════════════════════════════ */
    protected function conectar()
    {
        $enlace = new PDO(SGBD, USER, PASS);
        $enlace->exec("SET NAMES utf8mb4");
        return $enlace;
    }

    /* ═══════════════════════════════════════════
       CONSULTA SIMPLE (sin parámetros bind)
       Útil para SELECTs estáticos como listados
       fijos o conteos generales.
       ═══════════════════════════════════════════ */
    protected function ejecutar_consulta_simple($consulta)
    {
        $respuesta = self::conectar()->prepare($consulta);
        $respuesta->execute();
        return $respuesta;
    }

    /* ═══════════════════════════════════════════
       MANEJADOR DE RESPUESTA ESTÁNDAR
       Ejecuta el statement y devuelve JSON con
       el formato que espera SweetAlert en el
       frontend (titulo, mensaje, icono).
       ═══════════════════════════════════════════ */
    protected function manejadorRespuesta($consulta, $mensajeExito, $mensajeError)
    {
        $respuesta = [];

        if ($consulta->execute()) {
            $respuesta = [
                "titulo"  => "Operación Exitosa",
                "mensaje" => $mensajeExito,
                "icono"   => "success"
            ];
        } else {
            $respuesta = [
                "titulo"  => "Operación Fallida",
                "mensaje" => $mensajeError,
                "icono"   => "error"
            ];
        }

        echo json_encode($respuesta);
    }

    /* ═══════════════════════════════════════════
       CIFRADO SIMÉTRICO (AES)
       Usa SECRET_KEY, SECRET_IV y METHOD
       definidos en config/app.php
       ═══════════════════════════════════════════ */
    public function encryption($string)
    {
        $output = false;
        $key    = hash('sha256', SECRET_KEY);
        $iv     = substr(hash('sha256', SECRET_IV), 0, 16);
        $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    protected function decryption($string)
    {
        $key    = hash('sha256', SECRET_KEY);
        $iv     = substr(hash('sha256', SECRET_IV), 0, 16);
        $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
        return $output;
    }

    /* ═══════════════════════════════════════════
       GENERADOR DE CÓDIGO ALEATORIO
       Produce un string tipo "A38271049" usado
       como identificador de cuenta.
       ═══════════════════════════════════════════ */
    protected function generar_codigo_aleatorio($letra, $longitud, $num)
    {
        for ($i = 1; $i <= $longitud; $i++) {
            $numero = rand(0, 9);
            $letra .= $numero;
        }
        return $letra . $num;
    }

    /* ═══════════════════════════════════════════
       SANITIZACIÓN DE CADENAS
       Limpia el input de scripts, SQL keywords
       y caracteres potencialmente peligrosos.
       Se usa en los controllers antes de procesar
       cualquier dato recibido por POST.
       ═══════════════════════════════════════════ */
    protected function limpiar_cadena($cadena)
    {
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);

        $cadena = str_ireplace("<script>",       "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("</script>",      "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("<script src",    "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("<script type=",  "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("SELECT * FROM",  "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("DELETE FROM",    "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("INSERT INTO",    "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("<",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace(">",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("--",             "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("{",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("}",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("==",             "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("[",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("]",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("^",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace(";",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("=",              "ALERTA-MALICIOSO", $cadena);
        $cadena = str_ireplace("",               "Sin datos",        $cadena);

        return $cadena;
    }

    /* ═══════════════════════════════════════════
       GESTIÓN DE CUENTAS DE USUARIO (legacy)
       Métodos genéricos para ABM de cuentas.
       ═══════════════════════════════════════════ */
    protected function agregar_cuenta($datos)
    {
        $sql = self::conectar()->prepare(
            "INSERT INTO cuenta_usuarios (dni_usuario, cu_ip, cu_activa)
             VALUES (:cu_dni, :ip, :activa)"
        );

        $sql->bindParam(":cu_dni", $datos['documento']);
        $sql->bindParam(":ip",     $datos['ip']);
        $sql->bindParam(":activa", $datos['activa']);
        $sql->execute();

        return $sql;
    }

    protected function eliminar_cuenta($tabla, $codigo)
    {
        $sql = self::conectar()->prepare("DELETE FROM usuarios WHERE id = :Cod");
        $sql->bindParam(":Cod", $codigo);
        $sql->execute();

        return $sql;
    }

    /******************************************************************************/
    protected function guardarPagoEnCSV($pago, $nombreArchivo = null) {
        
        // Crear directorio si no existe
        $directorio = 'logboletas'.DIRECTORY_SEPARATOR.getAnio();
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        // Nombre del archivo
        if ($nombreArchivo === null) {
            $nombreArchivo = 'logcodebar'.getAnio().getMes().'.csv';
        }
        
        $rutaCompleta = $directorio . DIRECTORY_SEPARATOR . $nombreArchivo;
        
        //echo "rutacompleta: ".$rutaCompleta."<br>";

        // Verificar si el archivo ya existe para decidir si escribir encabezados
        $esArchivoNuevo = !file_exists($rutaCompleta);
        
        // Abrir archivo en modo append ('a') para agregar líneas
        $handle = fopen($rutaCompleta, 'a');
        
        if ($handle === false) {
            throw new Exception("No se pudo abrir el archivo: $rutaCompleta");
        }
        
        // Si es archivo nuevo, escribir encabezados
        if ($esArchivoNuevo) {
            fputcsv($handle, ['CUIT', 'Código Establecimiento', 'Importe', 'Fecha']);
        }
        
        $fechaActual = date('d/m/Y');

        // Preparar la fila con fecha
        $fila = [
            $fechaActual,
            date('H:i:s'),
            //date('d/m/Y H:i:s'),
            $pago['cuit'],
            $pago['estable'],
            $pago['importe'],
            
        ];
        
        // ESCRIBIR usando el resource $handle
        fputcsv($handle, $fila);
        
        fclose($handle);
        
        return $rutaCompleta;
    }

    private function getAnio(){

        $fechaActual = date('d/m/Y'); // Formato: 2026-03-05
        $anioActual = date('Y'); // Solo el año

        $fechaManual = "25/12/2025";

        // Crear objeto DateTime especificando el formato exacto
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaManual);

        if ($fecha) {
            $anioManual = $fecha->format('Y');
            //$mesManual = $fecha->format('m');
            //$diaManual = $fecha->format('d');
            
            /*echo "Fecha original: $fechaManual<br>";
            echo "Año: $anioManual<br>";
            echo "Mes: $mesManual<br>";
            echo "Día: $diaManual<br>";*/
            
            // También puedes obtener el nombre del mes
            //echo "Mes nombre: " . $fecha->format('F') . "<br>";
        } else {
            //echo "Fecha inválida";
        }

        $anio = $anioManual;

        return $anio;
    }

    private function getMes(){
        $fechaActual = date('d/m/Y'); // Formato: 2026-03-05
        $mesActual = date('m'); // Solo el año
        
        $fechaManual = "25/12/2025";

        // Crear objeto DateTime especificando el formato exacto
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaManual);

        if ($fecha) {
            $mesManual = $fecha->format('m');
            /*
            echo "Fecha original: $fechaManual<br>";
            echo "Año: $anioManual<br>";
            echo "Mes: $mesManual<br>";
            echo "Día: $diaManual<br>";
            */
            
            // También puedes obtener el nombre del mes
            //echo "Mes nombre: " . $fecha->format('F') . "<br>";
        } else {
            //echo "Fecha inválida";
        }

        $mes = $mesActual;

        return $mes;
    }
    /******************************************************************************/



   public function crearPreferencia()
{
    // Verificar que haya boleta en sesión
    if (empty($_SESSION['boleta'])) {
        echo json_encode(["ok" => false, "mensaje" => "No hay boleta en sesión."]);
        return;
    }

    $b        = $_SESSION['boleta'];
    $cod_ente = $b['cod_ente'] ?? '';

    if (empty($cod_ente)) {
        echo json_encode(["ok" => false, "mensaje" => "No hay cod_ente en la boleta."]);
        return;
    }

    // Buscar el access_token en la tabla mercadopago según cod_ente
    $db   = self::conectar();
    $stmt = $db->prepare("SELECT access_token FROM mercadopago WHERE cod_ente = :cod_ente LIMIT 1");
    $stmt->bindParam(':cod_ente', $cod_ente, PDO::PARAM_STR);
    $stmt->execute();
    $row   = $stmt->fetch(PDO::FETCH_ASSOC);
    $token = $row['access_token'] ?? null;

    if (!$token) {
        echo json_encode(["ok" => false, "mensaje" => "No hay cuenta de MercadoPago configurada para el convenio $cod_ente."]);
        return;
    }

    // Armar el cuerpo de la preferencia para MP
    $body = [
        "items" => [[
            "title"      => ($b['concepto'] ?? '') . ' - ' . ($b['detalle'] ?? ''),
            "quantity"   => 1,
            "unit_price" => (float) ($b['total'] ?? 0),
            "currency_id"=> "ARS"
        ]],
        "payer" => [
            "name"  => $_SESSION['empresa_nombre'] ?? '',
            "email" => ""
        ],
        // URLs de retorno después del pago
        "back_urls" => [
            "success" => SERVERURL . "pago-exitoso",
            "pending" => SERVERURL . "pago-pendiente",
            "failure" => SERVERURL . "pago-fallido"
        ],
        "auto_return"        => "approved",
        // Referencia externa para identificar el pago (CUIT de la empresa)
        "external_reference" => $_SESSION['empresa_cuit'] ?? ''
    ];

    // Llamada a la API de MercadoPago
    $ch = curl_init(MP_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($body),
        CURLOPT_SSL_VERIFYPEER => false, // solo desarrollo local — quitar en producción
        CURLOPT_SSL_VERIFYHOST => false, // solo desarrollo local — quitar en producción
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Log de error cURL si falla la conexión
    if ($curlError) {
        error_log("MP cURL error: " . $curlError);
        echo json_encode(["ok" => false, "mensaje" => "Error de conexión con MercadoPago."]);
        return;
    }

    // MP devuelve 201 cuando la preferencia se creó correctamente
    if ($httpCode !== 201) {
        error_log("MP HTTP error: $httpCode — response: $response");
        echo json_encode(["ok" => false, "mensaje" => "Error al crear preferencia en MercadoPago. Código: $httpCode"]);
        return;
    }

    $data = json_decode($response, true);

    echo json_encode([
        "ok"         => true,
        "init_point" => $data['init_point']         ?? null, // producción
        "sandbox"    => $data['sandbox_init_point'] ?? null  // testing
    ]);
}
}
