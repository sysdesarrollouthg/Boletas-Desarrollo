<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  csrf.php — Helpers para protección CSRF                 ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  Genera y expone el token CSRF por sesión.               ║
 * ║                                                          ║
 * ║  Uso en las vistas:                                      ║
 * ║    const CSRF_TOKEN = "<?php echo csrf_token(); ?>";     ║
 * ║                                                          ║
 * ║  El token se valida en api.php contra $_SESSION.         ║
 * ╚═══════════════════════════════════════════════════════════╝
 */

function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}
