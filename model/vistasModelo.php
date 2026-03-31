<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  vistasModelo.php — Whitelist de vistas permitidas        ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  Valida que la vista solicitada exista en la lista        ║
 * ║  blanca y que el archivo .php correspondiente exista.     ║
 * ║                                                           ║
 * ║  Las vistas protegidas requieren empresa en sesión;       ║
 * ║  si no la tienen, devuelven consulta-view.php.            ║
 * ╚═══════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";

class vistasModelo
{
    protected function obtener_vistas_mdl($vistas)
    {
        // ── Vistas habilitadas ──
        $listaBlanca = ["home", "consulta", "establecimiento", "crearempresa", "concepto", "404", "visualizarboleta", "actas", "acuerdos", "pagoexitoso", "pagopendiente", "pagofallido", "pagarboletas"];

        // ── Vistas que necesitan empresa activa en sesión ──
        $vistasProtegidas = ["establecimiento"];

        if (in_array($vistas, $listaBlanca)) {

            if (in_array($vistas, $vistasProtegidas) && empty($_SESSION['empresa_cuit'])) {
                return "./view/containers/consulta-view.php";
            }

            if (is_file("./view/containers/" . $vistas . "-view.php")) {
                return "./view/containers/" . $vistas . "-view.php";
            }
        }

        return "./view/containers/404-view.php";
    }
}
