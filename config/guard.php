<?php
/**
 * ╔═══════════════════════════════════════════════════════════╗
 * ║  guard.php — Protección contra acceso directo por URL    ║
 * ╠═══════════════════════════════════════════════════════════╣
 * ║  Incluir al inicio de cada controller y model:           ║
 * ║    require_once __DIR__ . "/../config/guard.php";        ║
 * ║                                                          ║
 * ║  APP_INIT solo se define en index.php y api.php.         ║
 * ║  Si alguien accede directo a un .php interno por URL,    ║
 * ║  la constante no existe y se corta con 403.              ║
 * ╚═══════════════════════════════════════════════════════════╝
 */

if (!defined('APP_INIT')) {
    http_response_code(403);
    exit;
}
