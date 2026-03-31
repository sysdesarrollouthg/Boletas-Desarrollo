<?php
/**
 * ╔═══════════════════════════════════════════════════════════════════╗
 * ║  CrearEmpresaController.php — Alta de empresas                    ║
 * ╠═══════════════════════════════════════════════════════════════════╣
 * ║  Acciones expuestas en api.php:                                   ║
 * ║    modulo: "crearempresa"                                         ║
 * ║      → action: "registrar"  (valida e inserta la empresa)         ║
 * ╚═══════════════════════════════════════════════════════════════════╝
 */

require_once __DIR__ . "/../config/guard.php";
require_once __DIR__ . "/../config/app.php";
include_once __DIR__ . "/../model/CrearEmpresaModel.php";

class CrearEmpresaController extends CrearEmpresaModel
{
    public function registrar()
    {
        $cuit         = trim($_POST['cuit']         ?? '');
        $razon_social = trim($_POST['razon_social'] ?? '');

        /* ── Validaciones backend ── */
        if ($cuit === '' || $razon_social === '') {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "Campos incompletos",
                "mensaje" => "El CUIT y la razón social son obligatorios.",
                "icono"   => "warning"
            ]);
            return;
        }

        if (!preg_match('/^\d{11}$/', $cuit)) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "CUIT inválido",
                "mensaje" => "El CUIT debe contener exactamente 11 dígitos.",
                "icono"   => "error"
            ]);
            return;
        }

        if (strlen($razon_social) > 200) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "Razón social inválida",
                "mensaje" => "La razón social no puede superar los 200 caracteres.",
                "icono"   => "error"
            ]);
            return;
        }

        /* ── Verificar CUIT duplicado ── */
        if ($this->existeEmpresa($cuit)) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "CUIT ya registrado",
                "mensaje" => "Ya existe una empresa con el CUIT $cuit.",
                "icono"   => "warning"
            ]);
            return;
        }

        /* ── Insertar ── */
        $nuevo_id = $this->guardarEmpresa([
            'cuit'         => $cuit,
            'razon_social' => $razon_social
        ]);

        if (!$nuevo_id) {
            echo json_encode([
                "ok"      => false,
                "titulo"  => "Error al guardar",
                "mensaje" => "No se pudo registrar la empresa. Intentá nuevamente.",
                "icono"   => "error"
            ]);
            return;
        }

        echo json_encode([
            "ok"         => true,
            "titulo"     => "Empresa registrada",
            "mensaje"    => "La empresa fue dada de alta correctamente.",
            "icono"      => "success",
            "empresa_id" => $nuevo_id
        ]);
    }
}