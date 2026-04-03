<?php
/**
 * ╔═══════════════════════════════════════════════════════════════════╗
 * ║  CrearEmpresaModel.php — Modelo de alta de empresas              ║
 * ╠═══════════════════════════════════════════════════════════════════╣
 * ║  Gestiona la tabla "empresas" para el registro de nuevas         ║
 * ║  empresas en el sistema.                                         ║
 * ║                                                                  ║
 * ║  Tabla: empresas                                                 ║
 * ║    • id           INT (PK, auto_increment)                       ║
 * ║    • cuit         CHAR(11)                                       ║
 * ║    • razon_social VARCHAR(200)                                   ║
 * ║                                                                  ║
 * ║  Métodos:                                                        ║
 * ║    existeEmpresa($cuit)       → Verifica si ya está registrada   ║
 * ║    guardarEmpresa($datos)     → Inserta nueva empresa            ║
 * ║                                                                  ║
 * ║  Usado por: CrearEmpresaController::registrar()                 ║
 * ╚═══════════════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
include_once __DIR__ . "/../config/mainModel.php";

class CrearEmpresaModel extends mainModel
{
    /* ═══════════════════════════════════════════
       VERIFICAR SI EL CUIT YA EXISTE
       Devuelve true si ya hay una empresa con
       ese CUIT registrada en la base.
       ═══════════════════════════════════════════ */
    protected function existeEmpresa($cuit)
    {
        $db  = self::conectar();
        $sql = $db->prepare("SELECT id FROM empresas WHERE cuit = :cuit LIMIT 1");
        $sql->bindParam(':cuit', $cuit, PDO::PARAM_STR);
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    /* ═══════════════════════════════════════════
       GUARDAR NUEVA EMPRESA
       Inserta el CUIT y razón social.
       Devuelve el ID generado o false si falla.
       ═══════════════════════════════════════════ */
    protected function guardarEmpresa($datos)
    {
        $db  = self::conectar();
        $sql = $db->prepare("
            INSERT INTO empresas (cuit, razon_social)
            VALUES (:cuit, :razon_social)
        ");
        $sql->bindParam(':cuit',         $datos['cuit'],         PDO::PARAM_STR);
        $sql->bindParam(':razon_social', $datos['razon_social'], PDO::PARAM_STR);

        if ($sql->execute()) {
            return $db->lastInsertId();
        }

        return false;
    }
}
