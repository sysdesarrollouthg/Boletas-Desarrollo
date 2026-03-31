<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  ConsultaController.php — Búsqueda de empresas por CUIT   ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  Recibe el CUIT desde api.php, lo valida, consulta        ║
 * ║  la base de datos y guarda la empresa en sesión           ║
 * ║  para que las vistas protegidas (establecimientos,        ║
 * ║  boletas) puedan acceder a sus datos.                     ║
 * ║                                                           ║
 * ║  Acciones expuestas en api.php:                           ║
 * ║    modulo: "consulta"  →  action: "buscar"                ║
 * ╚═══════════════════════════════════════════════════════════╝
 */


require_once __DIR__ . "/../config/guard.php";
require_once __DIR__ . "/../config/app.php";
include_once __DIR__ . "/../model/ConsultaModel.php";

class ConsultaController extends ConsultaModel
{
    /**
     * Busca una empresa por CUIT.
     * Si la encuentra, guarda empresa_id, empresa_cuit y empresa_nombre
     * en $_SESSION para autorizar el acceso a vistas protegidas.
     */
    public function buscar()
    {
        $cuit = preg_replace('/[^0-9]/', '', $_POST['cuit'] ?? '');
        $cuit = $this->limpiar_cadena($cuit);

        if (empty($cuit)) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "Campo vacío",
                "mensaje" => "Ingresá un CUIT para buscar.",
                "icono"   => "warning"
            ]);
            return;
        }

        if (strlen($cuit) !== 11) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "CUIT inválido",
                "mensaje" => "El CUIT debe tener 11 dígitos.",
                "icono"   => "warning"
            ]);
            return;
        }

        $resultado = $this->buscarPorCuit($cuit);

        if (empty($resultado)) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "Sin resultados",
                "mensaje" => "No se encontró ninguna empresa con ese CUIT.",
                "icono"   => "info"
            ]);
            return;
        }

        // ── Guardar empresa en sesión ──
        $_SESSION['empresa_cuit']   = $cuit;
        $_SESSION['empresa_id']     = $resultado[0]['empresa_id'];
        $_SESSION['empresa_nombre'] = $resultado[0]['nombre_empresa'];

        echo json_encode([
            "ok"   => true,
            "data" => $resultado
        ]);
    }
}
