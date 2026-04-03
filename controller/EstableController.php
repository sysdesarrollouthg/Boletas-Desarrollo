<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  EstableController.php — Establecimientos de una empresa  ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  Acciones expuestas en api.php:                           ║
 * ║    modulo: "establecimiento"                              ║
 * ║      → action: "listar"      (devuelve establecimientos)  ║
 * ║      → action: "limpiar"     (borra empresa de sesión)    ║
 * ║      → action: "formdata"    (tipos/convenios/secc.)      ║
 * ║      → action: "agregar"     (inserta nuevo estable.)     ║
 * ║      → action: "editar"      (actualiza estable.)         ║
 * ║      → action: "seleccionar" (setea sesión + redirige)    ║
 * ╚═══════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
require_once __DIR__ . "/../config/app.php";
include_once __DIR__ . "/../model/EstableModel.php";

class EstableController extends EstableModel
{
    public function listar()
    {
        if (empty($_SESSION['empresa_id'])) {
            echo json_encode(["ok" => false, "titulo" => "Sin autorización", "mensaje" => "No hay una empresa activa en la sesión.", "icono" => "warning"]);
            return;
        }
        $empresa_id = (int) $_SESSION['empresa_id'];
        $resultado  = $this->obtenerEstablecimientos($empresa_id);
        echo json_encode([
            "ok"           => true,
            "empresa"      => $_SESSION['empresa_nombre'],
            "empresa_cuit" => $_SESSION['empresa_cuit'],
            "data"         => $resultado
        ]);
    }

    public function formdata()
    {
        if (empty($_SESSION['empresa_id'])) {
            echo json_encode(["ok" => false, "mensaje" => "Sin autorización."]);
            return;
        }
        $data = $this->obtenerFormData();
        echo json_encode([
            "ok"          => true,
            "tipos"       => $data['tipos'],
            "convenios"   => $data['convenios'],
            "seccionales" => $data['seccionales'],
            "provincias" => $data['provincias']
        ]);
    }

    public function selprovincia()
    {

        if (empty($_POST['provincia'])) {
            echo json_encode(["ok" => false, "mensaje" => "Sin autorización PROVINCIA."]);
            return;
        }

        $prov_id = $_POST['provincia'];
        $data = $this->obtenerPartidos( $prov_id );
        echo json_encode([
            "ok"           => true,
            "provincia_id" => $prov_id,
            "partidos"     => $data
        ]);
    }

    public function selpartido()
    {

        if (empty($_POST['partido'])) {
            echo json_encode(["ok" => false, "mensaje" => "Sin autorización PARTIDO."]);
            return;
        }

        $part_id = $_POST['partido'];
        $data = $this->obtenerLocalidades( $part_id );
        echo json_encode([
            "ok"          => true,
            "partido_id"  => $part_id,
            "localidades" => $data
        ]);
    }

    public function ingcodpos()
    {
        if (empty($_POST['codpos'])) {
            echo json_encode(["ok" => false, "mensaje" => "Sin autorización codpos."]);
            return;
        }

        $pos_id = $_POST['codpos'];
        $data = $this->obtenerInfoCodPos( $pos_id );
        echo json_encode([
            "ok"          => true,
            "pos_id"  => $pos_id,
            "info" => $data
        ]);
    }


    public function agregar()
    {
        if (empty($_SESSION['empresa_id'])) {
            echo json_encode(["ok" => false, "titulo" => "Sin autorización", "mensaje" => "No hay empresa activa.", "icono" => "warning"]);
            return;
        }

        $nom_fantasia = trim($_POST['razon_social'] ?? '');
        $id_tipo      = (int) ($_POST['id_tipo']     ?? 0);
        $cod_sec       = trim($_POST['cod_sec']        ?? '');
        $id_convenio  = (int) ($_POST['id_convenio'] ?? 0);
        $calle        = trim($_POST['calle']         ?? '');
        $numero       = trim($_POST['numero']        ?? '');
        $piso_dto     = trim($_POST['piso_dto']     ?? '');
        $cod_pos      = trim($_POST['cod_pos']       ?? '');
        
        $cod_loc      = (int) trim($_POST['localidad']  ?? '');
        $telefono      = trim($_POST['telefono']       ?? '');
        $fec_ini      = trim($_POST['fec_ini']       ?? '');
        

        $objEstable = [
            'id_empresa'   => (int) $_SESSION['empresa_id'],
            'nom_fantasia' => $nom_fantasia,
            'id_tipo'      => $id_tipo,
            'id_sec'       => $cod_sec,
            'id_convenio'  => $id_convenio,
            'calle'        => $calle,
            'numero'       => $numero,
            'piso_dto'     => $piso_dto,
            'telefono'      => $telefono,
            'cod_pos'      => $cod_pos,
            'cod_loc'      => $cod_loc,
            'fec_ini'      => $fec_ini
        ];

        if ($nom_fantasia === '' || $id_tipo === 0 || $id_convenio === 0) {
            echo json_encode(
                                [
                                "ok" => false, 
                                "titulo" => "Campos incompletos", 
                                "mensaje" => "Razón social, tipo, seccional y convenio son obligatorios.", 
                                "icono" => "warning", 
                                "estable" => $objEstable,
                                "nom_fantasia" => $nom_fantasia,
                                "id_tipo" => $id_tipo,
                                "cod_sec" => $cod_sec,
                                "id_convenio" => $id_convenio
                                ]);
            return;
        }

        $cod_est = $this->guardarEstablecimiento([
            'id_empresa'   => (int) $_SESSION['empresa_id'],
            'nom_fantasia' => $nom_fantasia,
            'id_tipo'      => $id_tipo,
            'id_sec'       => $cod_sec,
            'id_convenio'  => $id_convenio,
            'calle'        => $calle,
            'numero'       => $numero,
            'piso_dto'     => $piso_dto,

            'telefono'      => $telefono,
            'cod_pos'      => $cod_pos,
            'cod_loc'      => $cod_loc,
            'fec_ini'      => $fec_ini
        ]);

        echo json_encode(["ok" => true, "titulo" => "Establecimiento agregado", "mensaje" => "El establecimiento fue registrado con código $cod_est.", "icono" => "success", "cod_est" => $cod_est]);
    }

    public function editar()
    {
        if (empty($_SESSION['empresa_id'])) {
            echo json_encode(["ok" => false, "titulo" => "Sin autorización", "mensaje" => "No hay empresa activa.", "icono" => "warning"]);
            return;
        }

        $cod_est      = (int) ($_POST['cod_est']     ?? 0);
        $razon_social = trim($_POST['razon_social']  ?? '');
        $id_tipo      = (int) ($_POST['id_tipo']     ?? 0);
        $id_sec       = trim($_POST['id_sec']        ?? '');
        $id_convenio  = (int) ($_POST['id_convenio'] ?? 0);
        $calle        = trim($_POST['calle']         ?? '');
        $numero       = trim($_POST['numero']        ?? '');
        $cod_pos      = trim($_POST['cod_pos']       ?? '');


        $localidad      = trim($_POST['localidad'] ?? '');
        /*$partido      = trim($_POST['partido'] ?? '');
        $provincia      = trim($_POST['provincia'] ?? '');*/
        $telefono      = trim($_POST['telefono'] ?? '');
        $fec_ini      = trim($_POST['fec_ini'] ?? '');
        $cod_sec      = trim($_POST['cod_sec'] ?? '');
        $piso_dto       = trim($_POST['piso_dto'] ?? '');

        if ($cod_est === 0) {
            echo json_encode(["ok" => false, "titulo" => "Error", "mensaje" => "Código de establecimiento inválido.", "icono" => "error"]);
            return;
        }
        if ($razon_social === '' || $id_tipo === 0 || $cod_sec === '' || $id_convenio === 0) {
            echo json_encode(["ok" => false, "titulo" => "Campos incompletos", "mensaje" => "Razón social, tipo, seccional y convenio son obligatorios.", "icono" => "warning"]);
            return;
        }

        $ok = $this->actualizarEstablecimiento([
            'cod_est'      => $cod_est,
            'id_empresa'   => (int) $_SESSION['empresa_id'],
            'razon_social' => $razon_social,
            'id_tipo'      => $id_tipo,
            'id_sec'       => $cod_sec,
            'id_convenio'  => $id_convenio,
            'calle'        => $calle,
            'numero'       => $numero,
            'calle_numero'       => $numero,
            'piso_dto'   => $piso_dto,
            'cod_pos'      => $cod_pos,

            'localidad'  => $localidad,
            'telefono'   => $telefono,
            'fec_ini'    => $fec_ini,
            'cod_sec'    => $cod_sec,
            
        ]);

        if (!$ok) {
            echo json_encode(["ok" => false, "titulo" => "Sin cambios", "mensaje" => "No se modificó ningún dato.", "icono" => "info"]);
            return;
        }

        echo json_encode(["ok" => true, "titulo" => "Establecimiento actualizado", "mensaje" => "Los datos fueron guardados correctamente.", "icono" => "success"]);
    }

    /**
     * Setea en sesión el establecimiento seleccionado y redirige a concepto.
     *
     * POST esperado:
     *   cod_est     → int
     *   id_convenio → int
     *   id_tipo     → int
     */
    public function seleccionar()
    {
        if (empty($_SESSION['empresa_id'])) {
            echo json_encode(["ok" => false, "mensaje" => "Sin autorización."]);
            return;
        }

        $cod_est     = (int) ($_POST['cod_est']     ?? 0);
        $id_convenio = (int) ($_POST['id_convenio'] ?? 0);
        $id_tipo     = (int) ($_POST['id_tipo']     ?? 0);

        if ($id_convenio === 0 || $id_tipo === 0) {
            echo json_encode(["ok" => false, "mensaje" => "Datos del establecimiento incompletos."]);
            return;
        }

        $convenio_nombre    = trim($_POST['convenio_nombre']    ?? '');
        $tipo_nombre        = trim($_POST['tipo_nombre']        ?? '');
        $razon_social       = trim($_POST['razon_social']       ?? '');
        $calle              = trim($_POST['calle']              ?? '');
        $numero             = trim($_POST['numero']             ?? '');
        $cod_pos            = trim($_POST['cod_pos']            ?? '');
        $seccional_nombre   = trim($_POST['seccional_nombre']   ?? ''); 
        $id_sec             = trim($_POST['id_sec']             ?? '');


        $_SESSION['est_cod_est']          = $cod_est;
        $_SESSION['est_id_convenio']      = $id_convenio;
        $_SESSION['est_id_tipo']          = $id_tipo;
        $_SESSION['est_convenio_nombre']  = $convenio_nombre;
        $_SESSION['est_tipo_nombre']      = $tipo_nombre;
        $_SESSION['est_razon_social']     = $razon_social;
        $_SESSION['est_calle']            = $calle;
        $_SESSION['est_numero']           = $numero;
        $_SESSION['est_cod_pos']          = $cod_pos;
        $_SESSION['est_seccional']        = $seccional_nombre;
        $_SESSION['id_sec']               = $id_sec;


        echo json_encode([
            "ok"       => true,
            "redirect" => SERVERURL . "concepto"
        ]);
    }

    public function limpiar()
    {
        unset(
            $_SESSION['empresa_cuit'],
            $_SESSION['empresa_id'],
            $_SESSION['empresa_nombre'],
            $_SESSION['est_cod_est'],
            $_SESSION['est_id_convenio'],
            $_SESSION['est_id_tipo'],
            $_SESSION['est_convenio_nombre'],
            $_SESSION['est_tipo_nombre'],
            $_SESSION['est_razon_social'],
            $_SESSION['est_calle'],
            $_SESSION['est_numero'],
            $_SESSION['est_cod_pos'],
            $_SESSION['id_sec']
        );
        echo json_encode(["ok" => true]);
    }
}
