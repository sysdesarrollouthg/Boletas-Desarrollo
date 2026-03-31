<!-- ╔═══════════════════════════════════════════════════════════════════╗
     ║  crearempresa-view.php — Alta de nueva empresa                    ║
     ╠═══════════════════════════════════════════════════════════════════╣
     ║  Formulario para registrar una empresa con CUIT y razón social.   ║
     ║  Al completar el alta, redirige a la vista de consulta.           ║
     ║                                                                   ║
     ║  IMPORTANTE: Este archivo es solo HTML + JS.                      ║
     ║  No lleva require_once, no incluye models ni controllers.         ║
     ║  Todo se carga desde index.php antes de llegar acá.               ║
     ║                                                                   ║
     ║  Fetch → api.php (modulo: crearempresa, action: registrar)        ║
     ╚═══════════════════════════════════════════════════════════════════╝ -->

<div class="d-sm-flex align-items-center justify-content-between mb-4" id="cabecera_crearEmpresa">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(78,115,223,0.35);">
                <i class="fas fa-building" style="color:#fff;font-size:1.1rem;"></i>
            </div>
            <div>
                <div style="font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#a0aec0;line-height:1;">Completá los datos para dar de alta tu empresa</div>
                <h1 style="margin:0;font-size:1.6rem;font-weight:800;color:#2d3748;line-height:1.2;">Registrar <span style="color:#4e73df;">Empresa</span></h1>
            </div>
        </div>
    </div>
</div>
    <a href="<?php echo SERVERURL; ?>consulta" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Volver a consulta
    </a>
</div>

<style>
.crear-empresa-wrapper {
    min-height: 60vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 2rem 1rem;
}
.crear-empresa-card {
    width: 100%;
    max-width: 540px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 40px rgba(78,115,223,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
    padding: 2.5rem;
    border: 1px solid rgba(78,115,223,0.08);
    position: relative;
    overflow: hidden;
}
.crear-empresa-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(135deg, #4e73df, #224abe);
}
.crear-empresa-card .card-icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, #4e73df, #224abe);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 6px 20px rgba(78,115,223,0.30);
}
.crear-empresa-card .card-icon i { font-size: 1.5rem; color: #fff; }
.crear-empresa-card h2 {
    font-size: 1.2rem; font-weight: 700; color: #2d3748;
    text-align: center; margin-bottom: .35rem;
}
.crear-empresa-card .subtitle {
    text-align: center; color: #a0aec0; font-size: .85rem; margin-bottom: 2rem;
}
.crear-empresa-card label {
    font-size: .75rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: #718096; margin-bottom: .5rem; display: block;
}
.ce-input-wrap {
    display: flex; align-items: center;
    background: #f7f8fc; border: 2px solid #e2e8f0;
    border-radius: 12px; overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
    margin-bottom: .35rem;
}
.ce-input-wrap:focus-within {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78,115,223,0.15);
    background: #fff;
}
.ce-input-wrap .ce-prefix {
    padding: 0 14px; font-size: .75rem; font-weight: 800; color: #4e73df;
    letter-spacing: .08em; white-space: nowrap; border-right: 2px solid #e2e8f0;
    height: 48px; display: flex; align-items: center; background: #eef1fb;
    transition: border-color .2s;
}
.ce-input-wrap:focus-within .ce-prefix { border-right-color: #4e73df; }
.ce-input-wrap input {
    flex: 1; border: none; background: transparent; padding: 0 16px;
    height: 48px; font-size: 1rem; font-weight: 600; color: #2d3748;
    letter-spacing: .05em; outline: none;
}
.ce-input-wrap input::placeholder { color: #cbd5e0; font-weight: 400; letter-spacing: .02em; }
.ce-input-simple {
    width: 100%;
    background: #f7f8fc; border: 2px solid #e2e8f0;
    border-radius: 12px; padding: 0 16px;
    height: 48px; font-size: 1rem; font-weight: 600; color: #2d3748;
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
    margin-bottom: .35rem;
}
.ce-input-simple:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78,115,223,0.15);
    background: #fff;
}
.ce-input-simple::placeholder { color: #cbd5e0; font-weight: 400; }
.ce-hint { font-size: .75rem; color: #a0aec0; margin-bottom: 1.5rem; }
.ce-btn {
    width: 100%; margin-top: .5rem; padding: .85rem;
    background: linear-gradient(135deg, #4e73df, #224abe);
    border: none; border-radius: 12px; color: #fff;
    font-size: .95rem; font-weight: 700; letter-spacing: .02em; cursor: pointer;
    box-shadow: 0 4px 16px rgba(78,115,223,0.4);
    transition: opacity .2s, transform .15s, box-shadow .2s;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.ce-btn:hover  { opacity: .92; box-shadow: 0 6px 22px rgba(78,115,223,0.5); }
.ce-btn:active { transform: scale(.98); }
.ce-btn:disabled { opacity: .6; cursor: not-allowed; }
.ce-divider { height: 1px; background: #e2e8f0; margin: 1.75rem 0; }
.ce-info-box {
    background: #f0f4ff; border-radius: 12px; padding: 1rem 1.25rem;
    display: flex; align-items: flex-start; gap: .75rem;
    margin-top: 1.5rem;
}
.ce-info-box i { color: #4e73df; margin-top: 2px; flex-shrink: 0; }
.ce-info-box p { font-size: .82rem; color: #4a5568; margin: 0; line-height: 1.55; }



 @media (max-width: 800px) {
                        
     .cv-wrapper{
      padding:0px 0px 10px 0px !important;
     }

      .container, .container-fluid, .container-lg, .container-md, .container-sm, .container-xl {
      padding-left: 0rem;
      padding-right: 0rem;
     }

     #cabecera_crearEmpresa{
        padding:8px !important;
     }
      .p-5{
          padding: 1.5rem !important;
      }

 }
       


</style>

<div class="crear-empresa-wrapper">
    <div class="crear-empresa-card">

        <div class="card-icon">
            <i class="fas fa-building"></i>
        </div>
        <h2>Nueva Empresa</h2>
        <p class="subtitle">Ingresá el CUIT y la razón social para registrar</p>

        <!-- ── Campo CUIT ── -->
        <div>
            <label for="ceCuit">CUIT de la empresa</label>
            <div class="ce-input-wrap">
                <span class="ce-prefix">CUIT</span>
                <input type="text" id="ceCuit" placeholder="30-12345678-9"
                       maxlength="13" autocomplete="off" inputmode="numeric">
            </div>
            <div class="ce-hint">Formato: XX-XXXXXXXX-X &nbsp;·&nbsp; 11 dígitos</div>
        </div>

        <div class="ce-divider"></div>

        <!-- ── Campo Razón Social ── -->
        <div>
            <label for="ceRazonSocial">Razón Social</label>
            <input type="text" id="ceRazonSocial" class="ce-input-simple"
                   placeholder="Ej: EMPRESA EJEMPLO S.A." maxlength="200" autocomplete="off">
            <div class="ce-hint">Tal como figura en ARCA</div>
        </div>

        <!-- ── Botón Registrar ── -->
        <button class="ce-btn" id="btnRegistrarEmpresa">
            <i class="fas fa-plus-circle"></i> Registrar empresa
        </button>

        <!-- ── Info complementaria ── -->
        <div class="ce-info-box">
            <i class="fas fa-info-circle"></i>
            <p>Una vez registrada la empresa, podrás agregar sus <strong>establecimientos</strong>
               y generar <strong>boletas de pago</strong> desde la sección de consulta.</p>
        </div>

    </div>
</div>




<script>
$(document).ready(function () {

    /* ═══════════════════════════════════════════
       Token CSRF y URL del gateway
       ═══════════════════════════════════════════ */
    var CSRF_TOKEN = "<?php echo csrf_token(); ?>";
    var API_URL    = "<?php echo SERVERURL; ?>api.php";

    /* ═══════════════════════════════════════════
       Formateo automático del CUIT (XX-XXXXXXXX-X)
       ═══════════════════════════════════════════ */
    $('#ceCuit').on('input', function () {
        var v = $(this).val().replace(/\D/g, '').substring(0, 11);
        var f = v;
        if (v.length > 2)  f = v.substring(0, 2) + '-' + v.substring(2);
        if (v.length > 10) f = v.substring(0, 2) + '-' + v.substring(2, 10) + '-' + v.substring(10);
        $(this).val(f);
    });

    /* ═══════════════════════════════════════════
       Enter en cualquier campo → disparar registro
       ═══════════════════════════════════════════ */
    $('#ceCuit, #ceRazonSocial').on('keypress', function (e) {
        if (e.which === 13) $('#btnRegistrarEmpresa').trigger('click');
    });

    /* ═══════════════════════════════════════════
       Botón registrar → POST a api.php
       ═══════════════════════════════════════════ */
    $('#btnRegistrarEmpresa').on('click', function () {

        var cuit        = $('#ceCuit').val().trim();
        var soloNum     = cuit.replace(/\D/g, '');
        var razonSocial = $('#ceRazonSocial').val().trim();

        /* ── Validaciones frontend ── */
        if (!cuit || !razonSocial) {
            Swal.fire({ title: 'Campos incompletos', text: 'Completá el CUIT y la razón social.', icon: 'warning', confirmButtonColor: '#4e73df' });
            return;
        }
        if (soloNum.length !== 11) {
            Swal.fire({ title: 'CUIT inválido', text: 'El CUIT debe tener 11 dígitos.', icon: 'error', confirmButtonColor: '#4e73df' });
            return;
        }
        if(!validarCuit(cuit)) {
            Swal.fire({ title: 'CUIT inválido', text: 'Ingrese un CUIT con un formato válido y real.', icon: 'error', confirmButtonColor: '#4e73df' });
            return;
        }

        /* ── Loading state ── */
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Registrando...');

        $.ajax({
            url:  API_URL,
            type: 'POST',
            data: {
                modulo:       'crearempresa',
                action:       'registrar',
                cuit:         soloNum,
                razon_social: razonSocial,
                csrf_token:   CSRF_TOKEN
            },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                $btn.prop('disabled', false).html('<i class="fas fa-plus-circle"></i> Registrar empresa');

                Swal.fire({
                    title:              res.titulo,
                    text:               res.mensaje,
                    icon:               res.icono,
                    confirmButtonColor: '#4e73df'
                }).then(function () {
                    if (res.ok) {
                        $('#ceCuit').val('');
                        $('#ceRazonSocial').val('');
                        window.location.href = '<?= SERVERURL; ?>consulta';
                    }
                });
            },
            error: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-plus-circle"></i> Registrar empresa');
                Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
            }
        });
    });

    function validarCuit(cuit) {
        // Eliminar guiones
        cuit = cuit.replace(/-/g, '');

        // Verificar que tenga 11 dígitos
        if (cuit.length !== 11 || !/^\d+$/.test(cuit)) {
            return false;
        }

        // Pesos para calcular el dígito verificador
        const pesos = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];

        // Calcular suma ponderada
        let suma = 0;
        for (let i = 0; i < 10; i++) {
            suma += parseInt(cuit[i], 10) * pesos[i];
        }

        // Calcular dígito verificador
        let resto = suma % 11;
        let digitoVerificador = 11 - resto;

        if (digitoVerificador === 11) digitoVerificador = 0;
        else if (digitoVerificador === 10) digitoVerificador = 9;

        // Comparar con el último dígito
        return digitoVerificador === parseInt(cuit[10], 10);
    }

});
</script>
