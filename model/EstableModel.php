<?php
/**
 * ╔═══════════════════════════════════════════════════════════════════╗
 * ║  EstableModel.php — Modelo de establecimientos                   ║
 * ╠═══════════════════════════════════════════════════════════════════╣
 * ║  Métodos:                                                        ║
 * ║    obtenerEstablecimientos($empresa_id) → array                  ║
 * ║    obtenerFormData()                    → tipos/convenios/secs   ║
 * ║    guardarEstablecimiento($datos)       → int cod_est            ║
 * ║    actualizarEstablecimiento($datos)    → bool                   ║
 * ║                                                                  ║
 * ║  Usado por: EstableController                                    ║
 * ╚═══════════════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
include_once __DIR__ . "/../config/mainModel.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

class EstableModel extends mainModel
{
    protected function obtenerEstablecimientos($empresa_id)
    {
        $db  = self::conectar();
        /*$sql = $db->prepare("
            SELECT 
                est.cod_est,
                est.razon_social,
                est.id_sec,
                est.id_tipo,
                est.id_convenio,
                est.calle,
                est.calle_nro,
                est.numero,
                est.cod_pos,
                t.nombre  AS tipo,
                c.nombre  AS convenio,
                s.nombre  AS seccional
            FROM establecimientos est
            LEFT JOIN tipos      t ON est.id_tipo     = t.id
            LEFT JOIN convenios  c ON est.id_convenio = c.id
            LEFT JOIN seccionales s ON est.id_sec     = s.id
            WHERE est.id_empresa = :empresa_id
            ORDER BY est.cod_est ASC
        ");
        */
        $sql = $db->prepare("
                            SELECT 
                                est.cod_est,
                                est.razon_social,
                                est.id_sec,
                                est.calle,
                                est.numero,
                                est.calle_nro,
                                est.calle_piso_dto,
                                est.cod_loc,
                                loc.`des-loc` AS des_loc,
                                loc.`cod-part` AS cod_part,
                                par.`des-part` AS des_part,
                                par.`cod-prov` AS cod_prov,
                                prov.`des-prov` AS des_prov,
                                est.cod_pos,
                                est.id_tipo,
                                t.nombre AS tipo,
                                est.id_convenio,
                                c.nombre AS convenio,
                                s.nombre AS seccional,
                                est.fecha_ini_act,
                                est.telefono 
                            FROM establecimientos est
                            LEFT JOIN tipos t ON est.id_tipo = t.id
                            LEFT JOIN convenios c ON est.id_convenio = c.id
                            LEFT JOIN seccionales s ON est.id_sec = s.id
                            LEFT JOIN localidad loc ON est.cod_loc = loc.`cod-loc`
                            LEFT JOIN partido par ON loc.`cod-part` = par.`cod-part`
                            LEFT JOIN provincia prov ON par.`cod-prov` = prov.`cod-prov`
                            WHERE est.id_empresa = :empresa_id
                            ORDER BY est.cod_est ASC
                        ");
        
        $sql->bindParam(':empresa_id', $empresa_id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function obtenerFormData()
    {
        $db = self::conectar();

        $tipos = $db->query("SELECT id, nombre FROM tipos ORDER BY nombre ASC")
                    ->fetchAll(PDO::FETCH_ASSOC);

        $convenios = $db->query("SELECT id, nombre FROM convenios WHERE mar_activo = true ORDER BY nombre ASC")
        //$convenios = $db->query("SELECT id, nombre FROM convenios WHERE id_conv  ORDER BY nombre ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);

        $seccionales = $db->query("SELECT id, nombre FROM seccionales ORDER BY id ASC")
                          ->fetchAll(PDO::FETCH_ASSOC);
                          
        /*$localidades = $db->query("SELECT 
                                            loc.`cod-loc` AS cod_loc, 
                                            loc.`cod-part` AS cod_part, 
                                            loc.`cod-sec` AS cod_sec, 
                                            loc.`des-loc` AS des_loc, 
                                            pos.`cod-pos` AS cod_pos,
                                            part.`des-part` AS des_part
                                        FROM `localidad` AS loc 
                                        LEFT JOIN `codpos` AS pos 
                                        ON loc.`cod-loc` = pos.`cod-loc`
                                        LEFT JOIN `partido` AS part
                                        ON loc.`cod-part` = part.`cod-part`")
                          ->fetchAll(PDO::FETCH_ASSOC);*/
                          
        $provincias = $db->query("SELECT `cod-prov` AS cod_prov, `des-prov` AS des_prov FROM provincia ORDER BY des_prov ASC")
                          ->fetchAll(PDO::FETCH_ASSOC);

        return compact('tipos', 'convenios', 'seccionales', 'provincias');
    }
    
    protected function obtenerPartidos( $id_prov )
    {
        $db = self::conectar();
        $sql = $db->prepare("SELECT `cod-part` AS cod_part, `des-part` AS des_part FROM `partido` WHERE `cod-prov` = :provincia_id ORDER BY des_part");
        $sql->bindParam(':provincia_id', $id_prov, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function obtenerLocalidades( $id_part )
    {
        $db = self::conectar();
        $sql = $db->prepare("SELECT 
                                    loc.`cod-loc` AS cod_loc, 
                                    loc.`des-loc` AS des_loc, 
                                    loc.`cod-sec` AS cod_sec,
                                    pos.`cod-pos` AS cod_pos
                                FROM `localidad` AS loc 
                                LEFT JOIN `codpos` AS pos 
                                    ON loc.`cod-loc` = pos.`cod-loc`
                                WHERE loc.`cod-part` = :id_partido");
        $sql->bindParam(':id_partido', $id_part, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function obtenerInfoCodPos( $id_pos )
    {
        $db = self::conectar();
        $sql = $db->prepare("SELECT 
                                    loc.`cod-loc` AS cod_loc, 
                                    loc.`des-loc` AS des_loc, 
                                    part.`cod-part` AS cod_part,
                                    part.`des-part` AS des_part,
                                    prov.`cod-prov` AS cod_prov,
                                    prov.`des-prov` AS des_prov,
                                    loc.`cod-sec` AS cod_sec
                                FROM `codpos` AS pos
                                LEFT JOIN `localidad` AS loc
                                    ON pos.`cod-loc` = loc.`cod-loc`
                                LEFT JOIN `partido` AS part
                                    ON part.`cod-part` = loc.`cod-part`
                                LEFT JOIN `provincia` AS prov
                                    ON prov.`cod-prov` = part.`cod-prov`
                                WHERE pos.`cod-pos` = :id_pos");
        $sql->bindParam(':id_pos', $id_pos, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function guardarEstablecimiento($datos)
    {
        try {
            $fechaConvertida = date('Y-m-d', strtotime(str_replace('/', '-', $datos['fec_ini'])));
    
            $db = self::conectar();
            $sql = $db->prepare("
                INSERT INTO establecimientos
                    (`id_empresa`, 
                     `razon_social`, 
                     `id_tipo`, 
                     `id_sec`, 
                     `id_convenio`, 
                     `calle`, 
                     `numero`, 
                     `calle_nro`, 
                     `calle_piso_dto`, 
                     `cod_loc`, 
                     `cod_pos`, 
                     `telefono`, 
                     `fecha_ini_act`)
                VALUES
                    (:id_empresa, 
                     :razon_social, 
                     :id_tipo, 
                     :id_sec, 
                     :id_convenio, 
                     :calle,
                     :calle_nro, 
                     :calle_nro, 
                     :calle_piso_dto, 
                     :cod_loc, 
                     :cod_pos,
                     :telefono, 
                     :fec_ini)");
            
            $sql->bindParam(':id_empresa',      $datos['id_empresa'],   PDO::PARAM_INT);
            $sql->bindParam(':razon_social',    $datos['nom_fantasia'], PDO::PARAM_STR);
            $sql->bindParam(':id_tipo',         $datos['id_tipo'],      PDO::PARAM_INT);
            $sql->bindParam(':id_sec',          $datos['id_sec'],       PDO::PARAM_STR);
            $sql->bindParam(':id_convenio',     $datos['id_convenio'],  PDO::PARAM_INT);
            $sql->bindParam(':calle',           $datos['calle'],        PDO::PARAM_STR);
            $sql->bindParam(':calle_nro',       $datos['numero'],       PDO::PARAM_STR);
            $sql->bindParam(':calle_piso_dto',  $datos['piso_dto'],     PDO::PARAM_STR);
            $sql->bindParam(':cod_loc',         $datos['cod_loc'],      PDO::PARAM_INT);
            $sql->bindParam(':cod_pos',         $datos['cod_pos'],      PDO::PARAM_STR);
            $sql->bindParam(':telefono',        $datos['telefono'],     PDO::PARAM_STR);
            $sql->bindParam(':fec_ini',         $fechaConvertida,       PDO::PARAM_STR);
            
            $sql->execute();
            return (int) $db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            throw $e;
        }
        
    }

    protected function actualizarEstablecimiento($datos)
    {
        try {
            $fechaConvertida = date('Y-m-d', strtotime(str_replace('/', '-', $datos['fec_ini'])));

            $db  = self::conectar();
            $sql = $db->prepare("
                UPDATE establecimientos SET
                    razon_social    = :razon_social,
                    id_tipo         = :id_tipo,
                    id_sec          = :id_sec,
                    id_convenio     = :id_convenio,
                    calle           = :calle,
                    numero          = :numero,
                    calle_nro       = :numero,
                    calle_piso_dto  = :calle_piso_dto,
                    cod_loc         = :cod_loc,
                    cod_pos         = :cod_pos,
                    telefono        = :telefono,
                    fecha_ini_act   = :fec_ini_act

                WHERE cod_est   = :cod_est
                  AND id_empresa = :id_empresa
            ");
            $sql->bindParam(':razon_social', $datos['razon_social'], PDO::PARAM_STR);
            $sql->bindParam(':id_tipo',      $datos['id_tipo'],      PDO::PARAM_INT);
            $sql->bindParam(':id_sec',       $datos['id_sec'],       PDO::PARAM_STR);
            $sql->bindParam(':id_convenio',  $datos['id_convenio'],  PDO::PARAM_INT);
            $sql->bindParam(':calle',        $datos['calle'],        PDO::PARAM_STR);
            $sql->bindParam(':numero',       $datos['numero'],       PDO::PARAM_STR);
            $sql->bindParam(':calle_nro',      $datos['calle_numero'],       PDO::PARAM_STR);
            $sql->bindParam(':calle_piso_dto', $datos['piso_dto'],       PDO::PARAM_STR);
            $sql->bindParam(':cod_loc',        $datos['localidad'],       PDO::PARAM_INT);
            $sql->bindParam(':cod_pos',        $datos['cod_pos'],      PDO::PARAM_STR);
            $sql->bindParam(':telefono',       $datos['telefono'],       PDO::PARAM_STR);
            $sql->bindParam(':fec_ini_act',    $fechaConvertida,       PDO::PARAM_STR);

            $sql->bindParam(':cod_est',      $datos['cod_est'],      PDO::PARAM_INT);
            $sql->bindParam(':id_empresa',   $datos['id_empresa'],   PDO::PARAM_INT);
            $sql->execute();

            return $sql->rowCount() > 0;
        }catch(Exception $e){
            error_log("Error: " . $e->getMessage());
            throw $e;
        }
    }
}