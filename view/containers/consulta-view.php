<!-- ╔═══════════════════════════════════════════════════════════╗
     ║  consulta-view.php — Búsqueda de empresa por CUIT         ║
     ╠═══════════════════════════════════════════════════════════╣
     ║  El usuario ingresa un CUIT, se busca vía api.php y       ║
     ║  si existe, se guarda en sesión y redirige a la vista     ║
     ║  de establecimientos.                                     ║
     ║                                                           ║
     ║  Fetch → api.php (modulo: consulta, action: buscar)       ║
     ╚═══════════════════════════════════════════════════════════╝ -->

<?php
unset($_SESSION['actas']);
unset($_SESSION['boleta']);
unset($_SESSION['codigo_barra']);
echo "hola gente esto es 8083 en test";

// Borrar solo los datos de la sesión de negocio
unset(
    $_SESSION['empresa_id'],
    $_SESSION['empresa_nombre'],
    $_SESSION['empresa_cuit'],
    $_SESSION['est_cod_est'],
    $_SESSION['est_id_convenio'],
    $_SESSION['est_id_tipo'],
    $_SESSION['est_convenio_nombre'],
    $_SESSION['est_tipo_nombre'],
    $_SESSION['est_razon_social'],
    $_SESSION['est_calle'],
    $_SESSION['est_numero'],
    $_SESSION['est_cod_pos'],
    $_SESSION['boleta'],
    $_SESSION['titulo']
);
// Regenerar CSRF para que el formulario funcione
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(78,115,223,0.35);">
                <i class="fas fa-search" style="color:#fff;font-size:1.1rem;"></i>
            </div>
            <div>
                <div style="font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#a0aec0;line-height:1;">Búsqueda de empresa</div>
                <h1 style="margin:0;font-size:1.6rem;font-weight:800;color:#2d3748;line-height:1.2;">Boletas de <span style="color:#4e73df;">Pago</span></h1>
            </div>
        </div>
    </div>
</div>

<style>
.empresa-cuit-wrapper {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
}
.empresa-cuit-layout {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    gap: 1.5rem;
    width: 100%;
    max-width: 900px;
}
@media (max-width: 768px) {
    .empresa-cuit-layout { flex-direction: column; align-items: center; }
}

/* Card CUIT */
.empresa-cuit-card {
    flex: 1;
    min-width: 0;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 40px rgba(78,115,223,0.13);
    padding: 2.5rem 2.5rem 2rem;
    border: 1px solid rgba(78,115,223,0.1);
}
.empresa-cuit-card .card-icon {
    width: 68px; height: 68px;
    background: linear-gradient(135deg, #4e73df, #224abe);
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 6px 20px rgba(78,115,223,0.35);
}
.empresa-cuit-card .card-icon i { font-size: 1.7rem; color: #fff; }
.empresa-cuit-card h2 { font-size: 1.25rem; font-weight: 700; color: #2d3748; text-align: center; margin-bottom: .35rem; }
.empresa-cuit-card .subtitle { text-align: center; color: #a0aec0; font-size: .875rem; margin-bottom: 2rem; }
.empresa-cuit-card label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #718096; margin-bottom: .5rem; display: block; }
.empresa-cuit-input-wrap {
    display: flex; align-items: center;
    background: #f7f8fc; border: 2px solid #e2e8f0;
    border-radius: 12px; overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
}
.empresa-cuit-input-wrap:focus-within { border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78,115,223,0.15); background: #fff; }
.empresa-cuit-input-wrap .cuit-prefix {
    padding: 0 14px; font-size: .75rem; font-weight: 800; color: #4e73df;
    letter-spacing: .08em; white-space: nowrap; border-right: 2px solid #e2e8f0;
    height: 50px; display: flex; align-items: center; background: #eef1fb; transition: border-color .2s;
}
.empresa-cuit-input-wrap:focus-within .cuit-prefix { border-right-color: #4e73df; }
.empresa-cuit-input-wrap input {
    flex: 1; border: none; background: transparent; padding: 0 16px;
    height: 50px; font-size: 1.05rem; font-weight: 600; color: #2d3748;
    letter-spacing: .1em; outline: none;
}
.empresa-cuit-input-wrap input::placeholder { color: #cbd5e0; font-weight: 400; letter-spacing: .04em; }
.empresa-cuit-hint { font-size: .75rem; color: #a0aec0; margin-top: .5rem; }
.empresa-cuit-btn {
    width: 100%; margin-top: 1.5rem; padding: .85rem;
    background: linear-gradient(135deg, #4e73df, #224abe);
    border: none; border-radius: 12px; color: #fff;
    font-size: .95rem; font-weight: 700; letter-spacing: .02em; cursor: pointer;
    box-shadow: 0 4px 16px rgba(78,115,223,0.4);
    transition: opacity .2s, transform .15s, box-shadow .2s;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.empresa-cuit-btn:hover  { opacity: .92; box-shadow: 0 6px 22px rgba(78,115,223,0.5); }
.empresa-cuit-btn:active { transform: scale(.98); }

/* Cartel aviso */
.empresa-no-registrada {
    flex: 1; min-width: 0;
    background: linear-gradient(145deg, #fffbeb, #fef9ee);
    border: 2px dashed #f6ad55;
    border-radius: 20px;
    padding: 2rem 2rem 1.75rem;
    display: flex; flex-direction: column; gap: 0;
}
.empresa-no-registrada .aviso-header { display: flex; align-items: center; gap: .9rem; margin-bottom: 1rem; }
.empresa-no-registrada .aviso-icon-grande {
    flex-shrink: 0; width: 52px; height: 52px;
    background: linear-gradient(135deg, #f6ad55, #d97706);
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(217,119,6,0.3);
}
.empresa-no-registrada .aviso-icon-grande i { font-size: 1.3rem; color: #fff; }
.empresa-no-registrada .aviso-header h5 { font-size: 1rem; font-weight: 700; color: #92400e; margin: 0; line-height: 1.35; }
.empresa-no-registrada > p { font-size: .85rem; color: #b45309; line-height: 1.65; margin-bottom: 1.25rem; }
.aviso-pasos { display: flex; flex-direction: column; gap: .6rem; margin-bottom: 1.5rem; }
.aviso-paso { display: flex; align-items: flex-start; gap: .75rem; font-size: .82rem; color: #92400e; line-height: 1.5; }
.aviso-paso .paso-num {
    flex-shrink: 0; width: 22px; height: 22px;
    background: #d97706; color: #fff; border-radius: 50%;
    font-size: .72rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center; margin-top: .1rem;
}
.aviso-btn {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    padding: .8rem 1.2rem;
    background: linear-gradient(135deg, #f6ad55, #d97706);
    color: #fff !important; text-decoration: none !important;
    border-radius: 12px; font-size: .875rem; font-weight: 700;
    box-shadow: 0 4px 14px rgba(217,119,6,0.35);
    transition: opacity .2s, box-shadow .2s, transform .15s;
    margin-top: auto;
}
.aviso-btn:hover  { opacity: .92; box-shadow: 0 6px 20px rgba(217,119,6,0.45); }
.aviso-btn:active { transform: scale(.98); }
</style>

<div class="empresa-cuit-wrapper">
    <div class="empresa-cuit-layout">

        <!-- Columna izquierda: ingreso CUIT -->
        <div class="empresa-cuit-card">
            <div class="card-icon">
                <i class="fas fa-building"></i>
            </div>
            <h2>Consulta de empresa</h2>
            <p class="subtitle">Ingresá el CUIT de la empresa para continuar</p>
            <div>
                <label for="cuitEmpresa">CUIT de la empresa</label>
                <div class="empresa-cuit-input-wrap">
                    <span class="cuit-prefix">CUIT</span>
                    <input type="text" id="cuitEmpresa" placeholder="30-12345678-9"
                        maxlength="13" autocomplete="off" inputmode="numeric">
                </div>
                <div class="empresa-cuit-hint">Formato: XX-XXXXXXXX-X</div>
            </div>
            <button class="empresa-cuit-btn" id="btnBuscarEmpresa">
                <i class="fas fa-search"></i> Buscar empresa
            </button>
        </div>

        <!-- Columna derecha: cartel registro -->
        <div class="empresa-no-registrada">
            <div class="aviso-header">
                <div class="aviso-icon-grande">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5>¿La empresa no está registrada en el sistema?</h5>
            </div>
            <p>
                Para poder realizar pagos, primero es necesario registrar la
                <strong>empresa</strong> y dar de alta sus <strong>establecimientos</strong>.
            </p>
            <p style="font-size:17px;">
                Realiza estos <strong>tres simples pasos</strong> 
            </p>
            <div class="aviso-pasos">
                <div class="aviso-paso">
                    <span class="paso-num">1</span>
                    <span>Registrá la <strong>empresa</strong> con su CUIT y razón social</span>
                </div> 
                <div class="aviso-paso">
                    <span class="paso-num">2</span>
                    <span>Volvé aquí para ingresar a tu <strong>panel de control</strong></span>
                </div>
                <div class="aviso-paso">
                    <span class="paso-num">3</span>
                    <span>Agregá los <strong>establecimientos</strong> asociados a la empresa</span>
                </div>
               
            </div>
            <a class="aviso-btn" href="<?php echo SERVERURL; ?>crearempresa">
                <i class="fas fa-plus-circle"></i> Registra tu empresa ahora
            </a>
        </div>

    </div>
</div>

<script>
$(document).ready(function () {

    /* ═══════════════════════════════════════════
       Token CSRF — se lee una vez desde PHP
       ═══════════════════════════════════════════ */
    const CSRF_TOKEN = "<?php echo csrf_token(); ?>";
    const API_URL    = "<?php echo SERVERURL; ?>api.php";

    /* ═══════════════════════════════════════════
       Formateo automático del CUIT (XX-XXXXXXXX-X)
       ═══════════════════════════════════════════ */
    $('#cuitEmpresa').on('input', function () {
        var v = $(this).val().replace(/\D/g, '').substring(0, 11);
        var f = v;
        if (v.length > 2)  f = v.substring(0,2) + '-' + v.substring(2);
        if (v.length > 10) f = v.substring(0,2) + '-' + v.substring(2,10) + '-' + v.substring(10);
        $(this).val(f);
    });

    /* ═══════════════════════════════════════════
       Enter para buscar
       ═══════════════════════════════════════════ */
    $('#cuitEmpresa').on('keypress', function (e) {
        if (e.which === 13) $('#btnBuscarEmpresa').trigger('click');
    });

    /* ═══════════════════════════════════════════
       Botón buscar — llama a api.php
       ═══════════════════════════════════════════ */
    $('#btnBuscarEmpresa').on('click', function () {
        var cuit    = $('#cuitEmpresa').val().trim();
        var soloNum = cuit.replace(/\D/g, '');

        if (!cuit) {
            Swal.fire({ title: 'Campo vacío', text: 'Ingresá el CUIT de la empresa.', icon: 'warning', confirmButtonColor: '#4e73df' });
            return;
        }
        if (soloNum.length !== 11) {
            Swal.fire({ title: 'CUIT inválido', text: 'El CUIT debe tener 11 dígitos. Verificá el formato.', icon: 'error', confirmButtonColor: '#4e73df' });
            return;
        }

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Buscando...');

        $.ajax({
            url:  API_URL,
            type: 'POST',
            data: {
                modulo:     'consulta',
                action:     'buscar',
                cuit:       soloNum,
                csrf_token: CSRF_TOKEN
            },
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (res) {
                $('#btnBuscarEmpresa').prop('disabled', false)
                    .html('<i class="fas fa-search"></i> Buscar empresa');

                if (!res.ok) {
                    Swal.fire({
                        title:              res.titulo  || 'No encontrado',
                        text:               res.mensaje || 'El CUIT ingresado no está registrado.',
                        icon:               res.icono   || 'warning',
                        confirmButtonColor: '#4e73df'
                    });
                    return;
                }

                // Empresa encontrada → redirigir a establecimientos
                window.location.href = '<?php echo SERVERURL; ?>establecimiento';
            },
            error: function (xhr) {
                $('#btnBuscarEmpresa').prop('disabled', false)
                    .html('<i class="fas fa-search"></i> Buscar empresa');
                Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
            }
        });
    });

});
</script>
