<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  vistasControlador.php — Enrutador de vistas              ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  Carga la plantilla principal y resuelve qué vista        ║
 * ║  mostrar según el parámetro ?views= de la URL.            ║
 * ║                                                           ║
 * ║  Las vistas protegidas (establecimiento, boletas, etc.)   ║
 * ║  requieren empresa en sesión; si no la tienen,            ║
 * ║  redirigen a la búsqueda por CUIT.                        ║
 * ╚═══════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
require_once "./model/vistasModelo.php";

class vistasControlador extends vistasModelo
{
    /**
     * Carga la plantilla general (layout).
     * Acá se puede agregar validación de login y expiración de sesión.
     */
    public function obtener_plantilla_ctrl()
    {
        /* if (!isset($_SESSION['usuario_id'])) {
            return require_once "./view/containers/login-view.php";
        }

        if (isset($_SESSION['ultimo_activity']) && (time() - $_SESSION['ultimo_activity'] > 1800)) {
            $_SESSION = [];
            session_destroy();
            return require_once "./view/containers/login-view.php";
        }

        $_SESSION['ultimo_activity'] = time(); */

        return require_once "./view/plantilla.php";
    }

    /**
     * Evalúa ?views= y devuelve la ruta del archivo de vista.
     * Si la vista es protegida y no hay empresa en sesión, redirige a consulta.
     */
    public function obtener_vistas_ctrl()
    {
        if (isset($_GET['views'])) {
            $ruta  = explode("/", $_GET['views']);
            $vista = $ruta[0];

            // ── Vistas que requieren empresa en sesión ──
            $vistas_protegidas = ['establecimiento', 'boletas', 'concepto', 'actas'];

            if (in_array($vista, $vistas_protegidas) && empty($_SESSION['empresa_cuit'])) {
                return "./view/containers/consulta-view.php";
            }

            $respuesta = vistasModelo::obtener_vistas_mdl($vista);
        } else {
            $respuesta = "./view/containers/home-view.php";
        }

        return $respuesta;
    }
}
