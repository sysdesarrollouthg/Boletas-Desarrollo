<?php

ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 3600);
session_start();

define('APP_INIT', true);

require_once "./config/app.php";
require_once "./config/csrf.php";
require_once "./controller/vistasControlador.php";


$plantilla = new vistasControlador();
$plantilla->obtener_plantilla_ctrl();
