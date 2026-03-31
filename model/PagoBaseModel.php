<?php
// model/PagoBaseModel.php
require_once "mainModel.php";

class PagoBaseModel extends mainModel
{
    // Propiedades comunes
    protected $conexion;
    protected $tablaBoletas = 'boletas';
    protected $tablaDetalles = 'detalle_boleta';
    protected $tablaPagos = 'pagos';

    public function __construct()
    {
        $this->conexion = $this->conectar();
    }

    /**
     * Obtiene los datos de una boleta por su ID
     */
    public function obtenerBoleta($boletaId)
    {
        $sql = $this->conexion->prepare("SELECT * FROM {$this->tablaBoletas} WHERE id = :id");
        $sql->bindParam(":id", $boletaId);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los conceptos/detalles de una boleta
     */
    public function obtenerDetallesBoleta($boletaId)
    {
        $sql = $this->conexion->prepare("SELECT * FROM {$this->tablaDetalles} WHERE boleta_id = :boleta_id");
        $sql->bindParam(":boleta_id", $boletaId);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Registra un pago en la base de datos (genérico)
     */
    public function registrarPago($datosPago)
    {
        // $datosPago debe incluir: boleta_id, monto, metodo, estado, transaccion_id, etc.
        $sql = $this->conexion->prepare(
            "INSERT INTO {$this->tablaPagos} 
            (boleta_id, monto, metodo_pago, estado, transaccion_id, fecha_pago) 
            VALUES (:boleta_id, :monto, :metodo_pago, :estado, :transaccion_id, NOW())"
        );

        $sql->bindParam(":boleta_id", $datosPago['boleta_id']);
        $sql->bindParam(":monto", $datosPago['monto']);
        $sql->bindParam(":metodo_pago", $datosPago['metodo_pago']);
        $sql->bindParam(":estado", $datosPago['estado']);
        $sql->bindParam(":transaccion_id", $datosPago['transaccion_id']);
        
        return $sql->execute();
    }

    /**
     * Actualiza el estado de una boleta
     */
    public function actualizarEstadoBoleta($boletaId, $estado)
    {
        $sql = $this->conexion->prepare("UPDATE {$this->tablaBoletas} SET estado = :estado WHERE id = :id");
        $sql->bindParam(":estado", $estado);
        $sql->bindParam(":id", $boletaId);
        return $sql->execute();
    }

    // Puedes añadir aquí más métodos que sean comunes a todos los pagos
    // Por ejemplo: validarMonto(), generarNumeroOperacion(), etc.
}