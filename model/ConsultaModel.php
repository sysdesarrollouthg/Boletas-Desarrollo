<?php
/**
 * ╔═══════════════════════════════════════════════════════════════════╗
 * ║  ConsultaModel.php — Modelo de búsqueda de empresas por CUIT    ║
 * ╠═══════════════════════════════════════════════════════════════════╣
 * ║  Consulta la tabla "empresas" junto con sus establecimientos,    ║
 * ║  tipos y convenios mediante JOINs.                               ║
 * ║                                                                  ║
 * ║  Método principal:                                               ║
 * ║    buscarPorCuit($cuit)                                          ║
 * ║      → Devuelve array con todos los registros de esa empresa     ║
 * ║      → Devuelve array vacío si no existe el CUIT                 ║
 * ║                                                                  ║
 * ║  Usado por: ConsultaController::buscar()                        ║
 * ╚═══════════════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
include_once __DIR__ . "/../config/mainModel.php";

class ConsultaModel extends mainModel
{
    /**
     * Busca una empresa por CUIT y trae sus establecimientos asociados.
     *
     * @param  string $cuit  CUIT de 11 dígitos (solo números)
     * @return array         Registros encontrados o array vacío
     */
    protected function buscarPorCuit($cuit)
    {
        $db  = self::conectar();
        $sql = $db->prepare("
            SELECT 
                e.id             AS empresa_id,
                e.cuit,
                e.razon_social   AS nombre_empresa,
                est.cod_est      AS establecimiento_id,
                est.razon_social AS nombre_establecimiento,
                est.calle,
                est.numero,
                est.cod_pos,
                t.nombre         AS tipo,
                c.nombre         AS convenio
            FROM empresas e
            LEFT JOIN establecimientos est ON e.id = est.id_empresa
            LEFT JOIN tipos t              ON est.id_tipo = t.id
            LEFT JOIN convenios c          ON est.id_convenio = c.id
            WHERE e.cuit = :cuit
        ");
        $sql->bindParam(':cuit', $cuit, PDO::PARAM_STR);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
