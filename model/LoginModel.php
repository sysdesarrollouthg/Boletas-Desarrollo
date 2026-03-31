<?php

include_once "../config/mainModel.php";

class LoginModel extends mainModel
{

    // Busca el usuario por email y verifica password + estado
    protected function verificarLogin($datos)
    {
        $db = self::conectar();

        // Verificar si está bloqueado temporalmente
        $sqlBloqueo = $db->prepare("
            SELECT bloqueado_hasta 
            FROM users 
            WHERE email = :email
        ");
        $sqlBloqueo->bindParam(':email', $datos['email']);
        $sqlBloqueo->execute();
        $bloqueo = $sqlBloqueo->fetch(PDO::FETCH_ASSOC);

        if ($bloqueo && !empty($bloqueo['bloqueado_hasta'])) {
            $ahora        = new DateTime();
            $bloqueadoHasta = new DateTime($bloqueo['bloqueado_hasta']);
            if ($ahora < $bloqueadoHasta) {
                $minutos = $ahora->diff($bloqueadoHasta)->i + 1;
                return [
                    "ok"      => false,
                    "titulo"  => "Cuenta bloqueada",
                    "mensaje" => "Demasiados intentos fallidos. Intentá de nuevo en $minutos minuto(s).",
                    "icono"   => "warning"
                ];
            }
        }

        // Buscar usuario activo por email
        $sql = $db->prepare("
            SELECT id, nombre, email, password, rol, seccional, activo, intentos_fallidos
            FROM users 
            WHERE email = :email
            LIMIT 1
        ");
        $sql->bindParam(':email', $datos['email']);
        $sql->execute();
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        // Usuario no existe
        if (!$usuario) {
            return [
                "ok"      => false,
                "titulo"  => "Error",
                "mensaje" => "Email o contraseña incorrectos.",
                "icono"   => "error"
            ];
        }

        // Usuario desactivado por admin
        if ($usuario['activo'] == 0) {
            return [
                "ok"      => false,
                "titulo"  => "Acceso denegado",
                "mensaje" => "Tu cuenta está desactivada. Contactá al administrador.",
                "icono"   => "error"
            ];
        }

        // Verificar password con bcrypt
        if (!password_verify($datos['password'], $usuario['password'])) {
            $this->registrarIntentoFallido($db, $usuario['id'], $usuario['intentos_fallidos']);
            return [
                "ok"      => false,
                "titulo"  => "Error",
                "mensaje" => "Email o contraseña incorrectos.",
                "icono"   => "error"
            ];
        }

        // Login exitoso — resetear intentos y registrar último login
        $sqlOk = $db->prepare("
            UPDATE users 
            SET intentos_fallidos = 0,
                bloqueado_hasta   = NULL,
                ultimo_login      = NOW()
            WHERE id = :id
        ");
        $sqlOk->bindParam(':id', $usuario['id']);
        $sqlOk->execute();

        return [
            "ok"       => true,
            "id"       => $usuario['id'],
            "nombre"   => $usuario['nombre'],
            "email"    => $usuario['email'],
            "rol"      => $usuario['rol'],
            "seccional"=> $usuario['seccional']
        ];
    }

    // Incrementa intentos fallidos y bloquea si llega a 5
    private function registrarIntentoFallido($db, $id, $intentosActuales)
    {
        $nuevosIntentos = $intentosActuales + 1;

        if ($nuevosIntentos >= 5) {
            // Bloquear 15 minutos
            $sql = $db->prepare("
                UPDATE users 
                SET intentos_fallidos = :intentos,
                    ultimo_intento    = NOW(),
                    bloqueado_hasta   = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                WHERE id = :id
            ");
        } else {
            $sql = $db->prepare("
                UPDATE users 
                SET intentos_fallidos = :intentos,
                    ultimo_intento    = NOW()
                WHERE id = :id
            ");
        }

        $sql->bindParam(':intentos', $nuevosIntentos);
        $sql->bindParam(':id', $id);
        $sql->execute();
    }
}