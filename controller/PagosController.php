<?php
// controller/PagosController.php
require_once __DIR__ . "/../model/MercadoPagoModel.php";
require_once __DIR__ . "/../model/EfectivoModel.php";   // Ajusta los nombres a los reales
require_once __DIR__ . "/../model/TransferenciaModel.php";

class PagosController
{
    // --- ACCIONES PARA MERCADO PAGO ---
    public function crearPreferenciaMercadoPago()
    {
        $mpModel = new MercadoPagoModel();
        
        // Sanear y obtener datos del POST
        $boleta_id = $this->limpiarYValidar($_POST['boleta_id'] ?? null);
        $monto = $this->limpiarYValidar($_POST['monto'] ?? null);
        
        if (!$boleta_id || !$monto) {
            return $this->enviarRespuesta(false, "Faltan datos para crear la preferencia");
        }

        $resultado = $mpModel->crearPreferencia([
            'boleta_id' => $boleta_id,
            'monto' => $monto,
            // ... otros datos necesarios
        ]);

        if ($resultado && isset($resultado->init_point)) {
            $this->enviarRespuesta(true, "Preferencia creada", ['init_point' => $resultado->init_point]);
        } else {
            $this->enviarRespuesta(false, "Error al crear la preferencia en Mercado Pago");
        }
    }

    public function notificacionMercadoPago()
    {
        // Esta acción es llamada por el webhook de Mercado Pago (no por AJAX desde tu vista)
        $mpModel = new MercadoPagoModel();
        $input = json_decode(file_get_contents('php://input'), true);
        $mpModel->procesarNotificacion($input);
        
        // Siempre responder 200 OK a Mercado Pago
        http_response_code(200);
    }

    // --- ACCIONES PARA PAGO EN EFECTIVO ---
    public function generarPagoEfectivo()
    {
        $efectivoModel = new EfectivoModel();
        // Lógica para generar un cupón, número de referencia, etc.
        // $resultado = $efectivoModel->generarCupon($_POST['boleta_id']);
        
        $this->enviarRespuesta(true, "Pago en efectivo registrado", ['referencia' => 'REF-123']);
    }

    // --- ACCIONES PARA TRANSFERENCIA ---
    public function generarPagoTransferencia()
    {
        $transferenciaModel = new TransferenciaModel();
        // Lógica para generar datos de la cuenta para transferir
        // $datosCuenta = $transferenciaModel->obtenerDatosCuenta();
        
        $this->enviarRespuesta(true, "Datos para transferencia", ['cbu' => '0000000000', 'alias' => 'MI.ALIAS']);
    }

    // --- MÉTODOS DE UTILIDAD (pueden ir en un trait o clase base más adelante) ---
    private function limpiarYValidar($dato)
    {
        // Aquí puedes usar el método de mainModel si tienes acceso a una instancia,
        // o re-implementar una limpieza básica.
        return htmlspecialchars(trim($dato));
    }

    private function enviarRespuesta($ok, $mensaje, $datos = [])
    {
        header('Content-Type: application/json');
        echo json_encode(array_merge(['ok' => $ok, 'mensaje' => $mensaje], $datos));
        exit;
    }
    
    // RED LINK
    public function gendeudaRedLink()
    {
        $this->enviarRespuesta(true, "Pago en efectivo registrado", ['referencia' => 'REF-123']);
    }
}