<?php
/**
 * ╔═══════════════════════════════════════════════════════════════════╗
 * ║  UsuariosModel.php — Modelo ABM de usuarios del sistema          ║
 * ╠═══════════════════════════════════════════════════════════════════╣
 * ║  Gestiona la tabla "users" con operaciones CRUD completas.       ║
 * ║  Las contraseñas se hashean con bcrypt antes de almacenar.       ║
 * ║                                                                  ║
 * ║  Métodos disponibles:                                            ║
 * ║    listarUsuarios()        → Todos los usuarios (DESC por fecha) ║
 * ║    obtenerUsuario($id)     → Un usuario por ID (para edición)    ║
 * ║    agregarUsuario($datos)  → Alta con validación de email único  ║
 * ║    editarUsuario($datos)   → Edición (password opcional)         ║
 * ║    eliminarUsuario($id)    → Baja por ID                         ║
 * ║                                                                  ║
 * ║  Usado por: UsuariosController (pendiente de migrar a api.php)  ║
 * ╚═══════════════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
include_once __DIR__ . "/../config/mainModel.php";

class UsuariosModel extends mainModel
{
    /* ═══════════════════════════════════════════
       LISTAR TODOS LOS USUARIOS
       Devuelve todos los campos necesarios para
       la tabla de administración, ordenados por
       fecha de creación (más recientes primero).
       ═══════════════════════════════════════════ */
    protected function listarUsuarios()
    {
        $db  = self::conectar();
        $sql = $db->prepare("
            SELECT id, nombre, email, rol, seccional, activo, ultimo_login, creado_en
            FROM users
            ORDER BY creado_en DESC
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ═══════════════════════════════════════════
       AGREGAR USUARIO
       Verifica que el email no exista antes de
       insertar. La contraseña se hashea con
       PASSWORD_BCRYPT.
       ═══════════════════════════════════════════ */
    protected function agregarUsuario($datos)
    {
        $db = self::conectar();

        // Verificar email duplicado
        $check = $db->prepare("SELECT id FROM users WHERE email = :email");
        $check->bindParam(':email', $datos['email']);
        $check->execute();

        if ($check->rowCount() > 0) {
            return [
                "titulo"  => "Email duplicado",
                "mensaje" => "Ya existe un usuario con ese email.",
                "icono"   => "warning"
            ];
        }

        $hash = password_hash($datos['password'], PASSWORD_BCRYPT);

        $sql = $db->prepare("
            INSERT INTO users (nombre, email, password, rol, seccional)
            VALUES (:nombre, :email, :password, :rol, :seccional)
        ");
        $sql->bindParam(':nombre',    $datos['nombre']);
        $sql->bindParam(':email',     $datos['email']);
        $sql->bindParam(':password',  $hash);
        $sql->bindParam(':rol',       $datos['rol']);
        $sql->bindParam(':seccional', $datos['seccional']);

        $this->manejadorRespuesta($sql, "Usuario creado correctamente.", "Error al crear el usuario.");
    }

    /* ═══════════════════════════════════════════
       EDITAR USUARIO
       Si se envía nueva password, la actualiza.
       Si viene vacía, mantiene la actual sin
       tocarla en el UPDATE.
       ═══════════════════════════════════════════ */
    protected function editarUsuario($datos)
    {
        $db = self::conectar();

        if (!empty($datos['password'])) {
            $hash = password_hash($datos['password'], PASSWORD_BCRYPT);
            $sql  = $db->prepare("
                UPDATE users 
                SET nombre = :nombre, email = :email, password = :password,
                    rol = :rol, seccional = :seccional, activo = :activo
                WHERE id = :id
            ");
            $sql->bindParam(':password', $hash);
        } else {
            $sql = $db->prepare("
                UPDATE users 
                SET nombre = :nombre, email = :email,
                    rol = :rol, seccional = :seccional, activo = :activo
                WHERE id = :id
            ");
        }

        $sql->bindParam(':id',        $datos['id']);
        $sql->bindParam(':nombre',    $datos['nombre']);
        $sql->bindParam(':email',     $datos['email']);
        $sql->bindParam(':rol',       $datos['rol']);
        $sql->bindParam(':seccional', $datos['seccional']);
        $sql->bindParam(':activo',    $datos['activo']);

        $this->manejadorRespuesta($sql, "Usuario actualizado correctamente.", "Error al actualizar el usuario.");
    }

    /* ═══════════════════════════════════════════
       ELIMINAR USUARIO
       Borra el registro por ID.
       ═══════════════════════════════════════════ */
    protected function eliminarUsuario($id)
    {
        $db  = self::conectar();
        $sql = $db->prepare("DELETE FROM users WHERE id = :id");
        $sql->bindParam(':id', $id);
        $this->manejadorRespuesta($sql, "Usuario eliminado correctamente.", "Error al eliminar el usuario.");
    }

    /* ═══════════════════════════════════════════
       OBTENER UN USUARIO POR ID
       Se usa para cargar los datos en el modal
       de edición en el frontend.
       ═══════════════════════════════════════════ */
    protected function obtenerUsuario($id)
    {
        $db  = self::conectar();
        $sql = $db->prepare("SELECT id, nombre, email, rol, seccional, activo FROM users WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
}
