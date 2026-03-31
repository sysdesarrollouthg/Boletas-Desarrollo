<?php
session_start();

require_once "../config/app.php";
include_once "../model/LoginModel.php";

class LoginController extends LoginModel
{
    public function login()
    {
        // Verificar token CSRF
       /* if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "Error de seguridad",
                "mensaje" => "Token inválido. Recargá la página.",
                "icono"   => "error"
            ]);
            return;
        }*/

        $email    = $this->limpiar_cadena($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "Campos requeridos",
                "mensaje" => "Completá el email y la contraseña.",
                "icono"   => "warning"
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "Email inválido",
                "mensaje" => "Ingresá un email válido.",
                "icono"   => "warning"
            ]);
            return;
        }

        $datos = [
            "email"    => $email,
            "password" => $password
        ];

        $resultado = $this->verificarLogin($datos);

        if ($resultado['ok']) {
            session_regenerate_id(true);

            $_SESSION['usuario_id']        = $resultado['id'];
            $_SESSION['usuario_nombre']    = $resultado['nombre'];
            $_SESSION['usuario_email']     = $resultado['email'];
            $_SESSION['usuario_rol']       = $resultado['rol'];
            $_SESSION['usuario_seccional'] = $resultado['seccional'];
            $_SESSION['ultimo_activity']   = time();

            echo json_encode([
                "ok"      => true,
                "titulo"  => "Bienvenido",
                "mensaje" => "Hola " . $resultado['nombre'],
                "icono"   => "success",
                "redirect" => SERVERURL . "home"
            ]);
        } else {
            echo json_encode($resultado);
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: " . SERVERURL);
        exit();
    }
}

// Manejar peticiones
if (isset($_POST['action'])) {
    $ctrl = new LoginController();
    switch ($_POST['action']) {
        case 'login':
            $ctrl->login();
            break;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $ctrl = new LoginController();
    $ctrl->logout();
}