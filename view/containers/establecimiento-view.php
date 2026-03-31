<!-- ╔═══════════════════════════════════════════════════════════╗
     ║  establecimiento-view.php — Lista de establecimientos     ║
     ╠═══════════════════════════════════════════════════════════╣
     ║  Fetch → api.php (modulo: establecimiento)                ║
     ║    action: "listar"   → carga la tabla                    ║
     ║    action: "formdata" → pobla selects del modal           ║
     ║    action: "agregar"  → inserta nuevo establecimiento     ║
     ║    action: "editar"   → actualiza establecimiento         ║
     ║    action: "limpiar"  → limpia sesión y vuelve a CUIT     ║
     ╚═══════════════════════════════════════════════════════════╝ -->

<?php 
unset($_SESSION['actas']);
unset($_SESSION['boleta']);
unset($_SESSION['codigo_barra']);
?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    
  
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(78,115,223,0.35);">
                <i class="fas fa-file-invoice-dollar" style="color:#fff;font-size:1.1rem;"></i>
            </div>
            <div>
                <div style="font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#a0aec0;line-height:1;">Establecimientos</div>
                <h1 style="margin:0;font-size:1.2rem;font-weight:800;color:#2d3748;line-height:1.2;" id="subtituloEmpresa">Cargando...</h1>
            </div>
        </div>
    </div>
</div>
  
  
  
  
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-success" id="btnAgregarEstablecimiento">
            <i class="fas fa-plus mr-1"></i> Agregar Establecimiento
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="btnCambiarEmpresa">
            <i class="fas fa-arrow-left mr-1"></i> Cambiar empresa
        </button>
    </div>
</div>

<!-- ════════════════════════════════════════════
     MODAL — Agregar / Editar Establecimiento
     ════════════════════════════════════════════ -->
<div class="modal fade" id="modalEstable" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.15);">

            <!-- Header -->
            <div class="modal-header" style="background:linear-gradient(135deg,#4e73df,#224abe); border-radius:16px 16px 0 0; border:none; padding:1.25rem 1.75rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i id="modalIcono" class="fas fa-store" style="color:#fff;font-size:1rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="modalTitulo" style="color:#fff;font-weight:700; margin-left:10px;"> Nuevo Establecimiento</h5>
                        <small style="color:rgba(255,255,255,0.75); margin-left:10px;" id="modalSubtituloEmpresa"> Cargando empresa...</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.8;font-size:1.5rem;">
                    <span>&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding:1.75rem;">

                <!-- Campo oculto para modo edición -->
                <input type="hidden" id="aeCodEst" value="">

                <!-- Cod. Establecimiento (solo lectura, visible en edición) -->
                <div id="aeCodEstWrap" class="form-group mb-4" style="display:none;">
                    <label class="ae-label">Código de Establecimiento</label>
                    <div style="background:#f0f4ff;border:2px solid #c7d4f7;border-radius:10px;padding:0 14px;height:44px;display:flex;align-items:center;">
                        <span style="font-family:monospace;font-weight:700;color:#4e73df;font-size:1rem;" id="aeCodEstDisplay"></span>
                        <span style="font-size:.75rem;color:#718096;margin-left:8px;">(no editable)</span>
                    </div>
                </div>

                <!-- Razón Social -->
                <div class="form-group mb-4">
                    <label class="ae-label">Nombre Fantasía <span class="text-danger">*</span></label>
                    <input type="text" id="aeRazonSocial" class="ae-input"
                           placeholder="Nombre del establecimiento" maxlength="200" autocomplete="off">
                </div>

                <!-- Calle / Número -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Calle</label>
                            <input type="text" id="aeCalle" class="ae-input"
                                   placeholder="Ej: AV. CORRIENTES" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="ae-label">Número</label>
                            <input type="text" id="aeNumero" class="ae-input"
                                   placeholder="Ej: 1234" maxlength="20" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-4">
                            <label class="ae-label">Piso/Depto</label>
                            <input type="text" id="aePisoDepto" class="ae-input"
                                   placeholder="Ej: 3° B" maxlength="20" autocomplete="off">
                        </div>
                    </div>
                </div>

                <!-- Tipo / Convenio -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Actividad <span class="text-danger">*</span></label>
                            <select id="aeTipo" class="ae-select">
                                <option value="">Seleccioná un tipo...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Convenio <span class="text-danger">*</span></label>
                            <select id="aeConvenio" class="ae-select">
                                <option value="">Seleccioná un convenio...</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Provincia / Provincia -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-4" style="background: #edf6f7; padding: 10px 0px 1px 13px; border-radius: 9px;">
                            <p><span style="color: #f00;">IMPORTANTE: Por favor ingrese la Localidad para poder generar la boleta.</span><br>
                                La localidad puede seleccionarse desde Provincia, Partido, Localidad o, si conoce el código postal, ingrese el Código postal.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Provincia / Provincia -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Provincia <span class="text-danger">*</span></label>
                            <select id="aeProvincia" class="ae-select">
                                <option value="">Seleccioná...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Partido <span class="text-danger">*</span></label>
                            <select id="aePartido" class="ae-select">
                                <option value="">Seleccioná...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Localidad / Código Postal -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Localidad <span class="text-danger">*</span></label>
                            <select id="aeLocalidad" class="ae-select">
                                <option value="">Seleccioná...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Código Postal</label>
                            <input type="hidden" name="codposorig" id="codposorig" />
                            <input type="text"
                               id="aeCodPos"
                               class="ae-input"
                               placeholder="Ej: 1425"
                               maxlength="8"
                               autocomplete="off"
                               oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                        </div>
                    </div>
                </div>
                
                <!-- Telefono / Ini Act -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Teléfono</label>
                            <input type="text"
                               id="aeTelef"
                               class="ae-input"
                               placeholder="Ej: 1425"
                               maxlength="15"
                               autocomplete="off"
                               oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="ae-label">Fecha de Inicio Actividad</label>
                            <input type="text" id="aeIniAct" placeholder="dd/mm/yyyy" maxlength="10" class="ae-input">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer" style="border-top:1px solid #e2e8f0; padding:1rem 1.75rem; border-radius:0 0 16px 16px;">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"
                        style="border-radius:10px; font-weight:600; padding:.5rem 1.25rem;">
                    Cancelar
                </button>
                <button type="button" id="btnGuardarEstable"
                        style="background:linear-gradient(135deg,#4e73df,#224abe);border:none;border-radius:10px;color:#fff;font-weight:700;padding:.5rem 1.5rem;cursor:pointer;box-shadow:0 4px 14px rgba(78,115,223,0.4);transition:opacity .2s;">
                    <i class="fas fa-save mr-1"></i> <span id="btnGuardarTexto">Guardar establecimiento</span>
                </button>
            </div>

        </div>
    </div>

   
</div>


<style>
.ae-label {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: #718096; margin-bottom: .45rem; display: block;
}
.ae-input {
    width: 100%; background: #f7f8fc; border: 2px solid #e2e8f0;
    border-radius: 10px; padding: 0 14px; height: 44px;
    font-size: .95rem; font-weight: 600; color: #2d3748; outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
}
.ae-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78,115,223,0.15);
    background: #fff;
}
.ae-input::placeholder { color: #cbd5e0; font-weight: 400; }
.ae-select {
    width: 100%; background: #f7f8fc; border: 2px solid #e2e8f0;
    border-radius: 10px; padding: 0 14px; height: 44px;
    font-size: .95rem; font-weight: 600; color: #2d3748; outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234e73df' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
}
.ae-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78,115,223,0.15);
    background-color: #fff;
}
.btn-accion {
    width: 30px; height: 30px; border: none; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; transition: opacity .2s, transform .15s;
    font-size: .8rem;
}
.btn-accion:hover  { opacity: .85; }
.btn-accion:active { transform: scale(.93); }
.btn-add-row  { background: #d4edda; color: #1a7f37; }
.btn-edit-row { background: #fff3cd; color: #856404; }
</style>

<!-- Tabla -->
<div class="card shadow-sm border-0" style="border-radius:16px;">
    <div class="card-body px-4 pb-4 pt-3">
        <div class="table-responsive">
            <table id="tablaEstablecimientos" class="table table-hover" width="100%">
                <thead>
                    <tr style="background:#f8f9fc;">
                        <th style="border:none; color:#6c757d; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em;">Imprimir</th>
                        <th style="border:none; color:#6c757d; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em;">Nombre de Fantasía</th>
                        <th style="border:none; color:#6c757d; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em;">Domicilio</th>
                        <th style="border:none; color:#6c757d; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em;">Localidad</th>
                        <th style="border:none; color:#6c757d; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em;">Seccional</th>
                        <th style="border:none; color:#6c757d; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em;">Tipo</th>
                        <th style="border:none; color:#6c757d; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em;">Convenio</th>
                        <th style="border:none; color:#6c757d; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em; width:40px;"></th>
                    </tr>
                </thead>
                <tbody id="tbodyEstablecimientos"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    var CSRF_TOKEN = "<?php echo csrf_token(); ?>";
    var API_URL    = "<?php echo SERVERURL; ?>api.php";
    var tabla      = null;
    var modoEditar = false;   // false = agregar, true = editar
    var filaEditando = null;  // referencia a la fila del DataTable en edición

    /* ═══════════════════════════════════════════
       Helpers
       ═══════════════════════════════════════════ */
    function buildFila(row) {
        var domicilio = (row.calle || '-') + ' ' + (row.numero || '');
        console.log(row)
        return [
            /* 0 - btn + */
            '<button class="btn-accion btn-add-row btn-fila-seleccionar" title="Seleccionar establecimiento"' +
                
                ' data-id_sec="'        + (row.id_sec       || '') + '"' +
                ' data-convenio-id="'   + (row.id_convenio  || '') + '"' +
                ' data-tipo-id="'       + (row.id_tipo      || '') + '"' +
                ' data-convenio-txt="'  + (row.convenio     || '').replace(/"/g, '&quot;') + '"' +
                ' data-tipo-txt="'      + (row.tipo         || '').replace(/"/g, '&quot;') + '"' +
                ' data-razon="'         + (row.razon_social || '').replace(/"/g, '&quot;') + '"' +
                ' data-seccional-txt="' + (row.seccional    || '').replace(/"/g, '&quot;') + '"' +
                
                ' data-cod="'          + (row.cod_est      || '') + '"' +
                ' data-rs="'           + ((row.razon_social || '').replace(/"/g,'&quot;')) + '"' +
                ' data-tipo="'         + (row.id_tipo      || '') + '"' +
                ' data-convenio="'     + (row.id_convenio  || '') + '"' +
                ' data-sec="'          + (row.id_sec       || '') + '"' +
                ' data-calle="'        + ((row.calle  || '').replace(/"/g,'&quot;')) + '"' +
                ' data-numero="'       + (row.numero  || '') + '"' +
                ' data-codpos="'       + (row.cod_pos || '') + '"' +
                ' data-pisodto="'      + (row.calle_piso_dto || '') + '"' +
                ' data-telefono="'     + (row.telefono || '') + '"' +
                ' data-feciniact="'    + (row.fecha_ini_act || '') + '"' +
                ' data-codloc="'    + (row.cod_loc || '') + '"' +
                ' data-codpart="'    + (row.cod_part || '') + '"' +
                ' data-codprov="'    + (row.cod_prov || '') + '">' +
               
                '<i class="fas fa-print"></i>' +
            '</button>',
            /* 1 - razon_social */
            '<span style="font-family:monospace;font-weight:600;color:#4e73df;">' + (row.razon_social || '-') + '</span>',
            /* 2 - domicilio */
            '<span style="font-weight:600;color:#2d3748;">' + domicilio.trim() + '</span>',
            /* 3 - seccional */
            '<span style="color:#4a5568;">' + (row.cod_loc || '-') + '-' + (row.des_loc || '-') + '</span>',
            /* 4 - seccional */
            '<span style="color:#4a5568;">' + (row.seccional || '-') + '</span>',
            /* 5 - cod_pos */
            //'<span style="color:#4a5568;" ondblclick="window.location.href = '<?php echo SERVERURL; ?>"pagarboletas"'">' + (row.cod_pos || '-') + '</span>',
            '<span style="background:#e8f0fe;color:#4e73df;padding:3px 10px;border-radius:20px;font-size:.8rem;font-weight:600;">' + (row.tipo || '-') + '</span>',
            /* 6 - convenio */
            '<span style="color:#4a5568;">' + (row.convenio || '-') + '</span>',
            /* 7 - btn lápiz */
            '<button class="btn-accion btn-edit-row btn-fila-editar" title="Editar establecimiento"' +
                ' data-cod="'          + (row.cod_est      || '') + '"' +
                ' data-rs="'           + ((row.razon_social || '').replace(/"/g,'&quot;')) + '"' +
                ' data-tipo="'         + (row.id_tipo      || '') + '"' +
                ' data-convenio="'     + (row.id_convenio  || '') + '"' +
                ' data-sec="'          + (row.id_sec       || '') + '"' +
                ' data-calle="'        + ((row.calle  || '').replace(/"/g,'&quot;')) + '"' +
                ' data-numero="'       + (row.numero  || '') + '"' +
                ' data-codpos="'       + (row.cod_pos || '') + '"' +
                ' data-pisodto="'      + (row.calle_piso_dto || '') + '"' +
                ' data-telefono="'     + (row.telefono || '') + '"' +
                ' data-feciniact="'    + (row.fecha_ini_act || '') + '"' +
                ' data-codloc="'    + (row.cod_loc || '') + '">' +
                '<i class="fas fa-pencil-alt"></i>' +
            '</button>'
        ];
    }

    function abrirModalAgregar() {
        modoEditar   = false;
        filaEditando = null;
        
        $('#aeCodEst').val('');
        $('#aeCodEstWrap').hide();
        $('#aeRazonSocial, #aeCalle, #aeNumero, #aeCodPos').val('');
        $('#aeTipo, #aeConvenio, #aeSeccional').val('');
        $('#modalTitulo').text('Nuevo Establecimiento');
        $('#modalIcono').removeClass('fa-pencil-alt').addClass('fa-store');
        $('#btnGuardarTexto').text('Guardar establecimiento');
        $('#aeTelef').val('');
        $('#aeIniAct').val('');
        $('#aeProvincia').val('')
        $('#aePisoDepto').val('')
        $('#aeCodPos').val('')
        $('#aePartido').empty().append('<option value="">Seleccione un partido</option>');
        $('#aeLocalidad').empty().append('<option value="">Seleccione una localidad</option>');
    }

    const convertirFecha = (fecha) => {
        const [anio, mes, dia] = fecha.split('-');
        return `${dia}/${mes}/${anio}`;
    }

    function abrirModalEditar($btn) {
        modoEditar   = true;
        filaEditando = $btn.closest('tr');

        var cod  = $btn.data('cod');
        var sec  = String($btn.data('sec')).padStart(3,'0');
        
        let estableError = false
        
        /*console.log($btn.data('rs') + '**' + !$btn.data('rs'))
        console.log('codloc', $btn.data('codloc') + '**' + !$btn.data('codloc'))
        console.log('codpart', $btn.data('codpart') + '**' + !$btn.data('codpart'))
        console.log('codprov', $btn.data('codprov') + '**' + !$btn.data('codprov'))
        */
        if(!$btn.data('codloc')){
            console.log('error')
            
            estableError = true
        }
        
        let fechaOriginal = $btn.data('feciniact');
        //console.log('fechaOriginal', fechaOriginal)
        let fechaFormateada = ( fechaOriginal == '' ? '' : convertirFecha(fechaOriginal) )
        //console.log('fechaFormateada', fechaFormateada)
        $('#aeCodEst').val(cod);
        $('#aeCodEstDisplay').text(cod);
        $('#aeCodEstWrap').hide();
        $('#aeRazonSocial').val($btn.data('rs'));
        $('#aeTipo').val($btn.data('tipo'));
        $('#aeConvenio').val($btn.data('convenio'));
        $('#aeSeccional').val(sec);
        $('#aeCalle').val($btn.data('calle'));
        $('#aeNumero').val($btn.data('numero'));
        //$('#aeCodPos').val($btn.data('codpos'));
        $('#modalTitulo').text('Editar Establecimiento');
        $('#modalIcono').removeClass('fa-store').addClass('fa-pencil-alt');
        $('#aePisoDepto').val( $btn.data('pisodto') );
        $('#aeTelef').val( $btn.data('telefono') );
        $('#aeIniAct').val( fechaFormateada );
        $('#btnGuardarTexto').text('Guardar cambios');
        
        console.log('codigo localidad', $btn.data('codloc'))
        
        if($btn.data('codloc') == 852){
            $('#aeProvincia').val('0000')
            
            $('#aePartido').empty().append('<option value="000">CABA</option>');
            $('#aeLocalidad').empty().append('<option value="852" codpos="0" codsec="005">CABA</option>');
            $('#aeCodPos').val($btn.data('codpos'))
            
        }else{
            if(!estableError){
                $('#aeCodPos').val($btn.data('codpos'));
                if($('#aeCodPos').val().trim != '') ingcodpostal()
            }
        }
        
        $('#codposorig').val( $('#aeCodPos').val() )
        
    }

    /* ═══════════════════════════════════════════
       Cargar formdata (una sola vez)
       ═══════════════════════════════════════════ */
    function cargarFormData(callback) {
        if ($('#aeTipo option').length > 1) { callback(); return; }

        $.ajax({
            url: API_URL, type: 'POST',
            data: { modulo: 'establecimiento', action: 'formdata', csrf_token: CSRF_TOKEN },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                if (!res.ok) { Swal.fire('Error', res.mensaje, 'error'); return; }

                $.each(res.tipos, function (i, t) {
                    $('#aeTipo').append('<option value="' + t.id + '">' + t.nombre + '</option>');
                });
                $.each(res.convenios, function (i, c) {
                    $('#aeConvenio').append('<option value="' + c.id + '">' + c.nombre + '</option>');
                });
                $.each(res.seccionales, function (i, s) {
                    var id_f = String(s.id).padStart(3, '0');
                    $('#aeSeccional').append('<option value="' + id_f + '">' + id_f + ' - ' + s.nombre + '</option>');
                });
                console.log('provincias', res.provincias)
                $.each(res.provincias, function (i, p) {
                    var id_p = String(p.cod_prov);
                    $('#aeProvincia').append('<option value="' + id_p + '">' + p.des_prov + '</option>');
                });

                callback();
            },
            error: function () { Swal.fire('Error', 'No se pudo cargar el formulario.', 'error'); }
        });
    }

    /* ═══════════════════════════════════════════
       Carga automática de establecimientos
       ═══════════════════════════════════════════ */
    $.ajax({
        url: API_URL, type: 'POST',
        data: { modulo: 'establecimiento', action: 'listar', csrf_token: CSRF_TOKEN },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
										 
											  
            if (!res.ok) {
                Swal.fire({ title: res.titulo, text: res.mensaje, icon: res.icono, confirmButtonColor: '#4e73df' })
                    .then(function () { window.location.href = '<?php echo SERVERURL; ?>consulta'; });
                return;
            }

            $('#subtituloEmpresa').html('<strong>' + res.empresa + '</strong> &nbsp;-&nbsp;' + res.empresa_cuit);
            $('#modalSubtituloEmpresa').html(res.empresa + ' &nbsp;·&nbsp; CUIT: ' + res.empresa_cuit);

            var rows = [];
            $.each(res.data, function (i, row) { rows.push(buildFila(row)); });

            tabla = $('#tablaEstablecimientos').DataTable({
                language: { url: 'js/Spanish.json' },
                pageLength: 25,
                order: [[1, 'asc']],
                data: rows,
                columnDefs: [
                    { targets: [0, 7], orderable: false, searchable: false, className: 'text-center' }
                ]
            });
        },
        error: function () { Swal.fire('Error', 'No se pudo cargar los establecimientos.', 'error'); }
    });

    /* ═══════════════════════════════════════════
       Botón + del header
       ═══════════════════════════════════════════ */
    $('#btnAgregarEstablecimiento').on('click', function () {
        cargarFormData(function () {
            abrirModalAgregar();
            $('#modalEstable').modal('show');
        });
    });

    $('#aeProvincia').on('change', function () {
        var $btn = $(this);
        //$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        let objData = {
                            modulo:          'establecimiento',
                            action:          'selprovincia',
                            csrf_token:       CSRF_TOKEN,
                            provincia:       $(this).val()
                        }
        console.log('data', objData)
        //return false
        $.ajax({
            url: API_URL, type: 'POST',
            data: objData,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                
                console.log('res___', res)
                if (!res.ok) {
                    //$btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                    Swal.fire('Error', res.mensaje, 'error');
                    return;
                }
                $('#aePartido').empty().append('<option value="">Seleccione una provincia</option>');
                
                console.log('res.partidos', res.partidos)
                $.each(res.partidos, function (i, p) {
                    console.log('p', p.cod_part)
                    var id_p = String(p.cod_part).padStart(3, '0');
                    $('#aePartido').append('<option value="' + id_p + '">' + p.des_part + '</option>');
                });
            },
            error: function () {
                //$btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                Swal.fire('Error', 'No se pudo seleccionar el establecimiento.', 'error');
            }
        });
    });

    /* ═══════════════════════════════════════════
       Botón "sel partido"
       ═══════════════════════════════════════════ */
    $('#aePartido').on('change', function () {
        var $btn = $(this);
        //$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        let objData = {
                            modulo:          'establecimiento',
                            action:          'selpartido',
                            csrf_token:       CSRF_TOKEN,
                            partido:       $(this).val()
                        }
        console.log('data', objData)
        console.log('partido', $(this).val(), $(this).val() == '000')
        
        if( $(this).val() == '000' ){
            $('#aeLocalidad').empty().append('<option value="">Seleccione un localidad</option>');
            $('#aeLocalidad').append('<option value="852" codpos="0" codsec="005">CABA</option>');
            return false;
        }
        
        $.ajax({
            url: API_URL, type: 'POST',
            data: objData,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                
                console.log('res___', res)
                if (!res.ok) {
                    //$btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                    Swal.fire('Error', res.mensaje, 'error');
                    return;
                }
                $('#aeLocalidad').empty().append('<option value="">Seleccione un partido</option>');
                
                console.log('res.localidades', res.localidades)
                $.each(res.localidades, function (i, l) {
                    console.log('l', l.cod_loc)
                    var id_l = String(l.cod_loc).padStart(3, '0');
                    $('#aeLocalidad').append('<option value="' + id_l + '" codpos="' + l.cod_pos + '" codsec="' + l.cod_sec + '">' + id_l + ' - ' + l.des_loc + '</option>');
                });
            },
            error: function () {
                //$btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                Swal.fire('Error', 'No se pudo seleccionar el localidad.', 'error');
            }
        });
    });

     $('#aeLocalidad').on('change', function () {
        var selectedLoc = $(this).find('option:selected');
        var codpos = selectedLoc.attr('codpos');

        console.log('Código postal:', codpos);

        $('#aeCodPos').val(codpos)
     })

    /* ═══════════════════════════════════════════
       ingcodpostal:  a partir del cód postal saco 
       localidad, partido y provincia
       ═══════════════════════════════════════════ */
    const ingcodpostal3 = () => {
        //var $btn = $(this);
        //$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        let objData = {
                            modulo:          'establecimiento',
                            action:          'ingcodpos',
                            csrf_token:       CSRF_TOKEN,
                            codpos:       $('#aeCodPos').val()
                        }
        console.log('data', objData)
        
        let codposCABA = $('#aeCodPos').val() 
        console.log('localidad', $('#aeLocalidad').val(), $('#aeLocalidad').val() == '852')
        
        if( $('#aeLocalidad').val() == '852' ){
            if( codposCABA >= 1000 && codposCABA <= 1440 ){
                return false
            }else{
                $('#aeCodPos').val(0)
                Swal.fire('Error', 'El código postal de CABA debe estar entre 1000 y 1440.', 'error');
                return false
            }
        }
        
        $.ajax({
            url: API_URL, type: 'POST',
            data: objData,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                
                console.log('res___', res)
                if (!res.ok) {
                    //$btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                    Swal.fire('Error', res.mensaje, 'error');
                    return;
                }
                
                info = res.info[0]
                console.log('---',info.cod_prov)
                $('#aeProvincia').val(info.cod_prov)

                $('#aePartido').empty();
                $('#aePartido').append('<option value="' + info.cod_part + '">' + info.des_part + '</option>')
                
                $('#aeLocalidad').empty();
                $('#aeLocalidad').append('<option value="' + info.cod_loc + '" codsec="' + info.cod_sec + '">' + info.des_loc + '</option>')
            },
            error: function () {
                //$btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                Swal.fire('Error', 'No se pudo seleccionar el localidad.', 'error');
            }
        });
    }

    const ingcodpostal = async () => {
    try {
        let codposAnterior = $('#aeCodPos').val();
        let codposCABA = $('#aeCodPos').val();
        console.log('localidad', $('#aeLocalidad').val(), $('#aeLocalidad').val() == '852');
        
        // Validación para CABA
        if ($('#aeLocalidad').val() == '852') {
            if (codposCABA >= 1000 && codposCABA <= 1440) {
                return false;
            } else {
                // EN CASO DE QUE PONGA UN CÓDIGO POSTAL QUE NO CORRESPONDE A CABA
                const result = await Swal.fire({
                    title: 'Error',
                    text: 'Ud. ha ingresado un código postal que no es de CABA (1000 al 1440). En caso de querer cambiar de localidad presione *Continuar* y se procederá a buscar la nueva provincia, partido y localidad, en caso contrario presione *Cancelar* y deberá ingresar el código postal de CABA que corresponda a su establecimiento.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#4e73df',
                    cancelButtonColor: '#d33'
                });
                
                if (result.isConfirmed) {
                    // Usuario hizo clic en "Continuar"
                    console.log('El usuario eligió CONTINUAR');
                    
                    /****************************/
                    let objData = {
                        modulo: 'establecimiento',
                        action: 'ingcodpos',
                        csrf_token: CSRF_TOKEN,
                        codpos: $('#aeCodPos').val()
                    };
                    
                    console.log('data', objData);
                    
                    // Esperar la respuesta del AJAX
                    const res = await $.ajax({
                        url: API_URL,
                        type: 'POST',
                        data: objData,
                        dataType: 'json',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    
                    console.log('res___', res);
                    
                    if (!res.ok) {
                        await Swal.fire('Error', res.mensaje, 'error');
                        return;
                    }
                    
                    const info = res.info[0];
                    console.log('---', info.cod_prov);
                    
                    $('#aeProvincia').val(info.cod_prov);
                    $('#aePartido').empty();
                    $('#aePartido').append('<option value="' + info.cod_part + '">' + info.des_part + '</option>');
                    $('#aeLocalidad').empty();
                    $('#aeLocalidad').append('<option value="' + info.cod_loc + '" codsec="' + info.cod_sec + '">' + info.des_loc + '</option>');
                    /****************************/
                    
                    return true;
                    
                } else if (result.isDismissed) {
                    // Usuario hizo clic en "Cancelar" o cerró el modal
                    console.log('El usuario eligió NO CONTINUAR');
                    $('#aeCodPos').val( $('#codposorig').val() );
                    return false;
                }
            }
        } else {
            // Si no es CABA, ejecutar la búsqueda normal
            let objData = {
                modulo: 'establecimiento',
                action: 'ingcodpos',
                csrf_token: CSRF_TOKEN,
                codpos: $('#aeCodPos').val()
            };
            
            console.log('data', objData);
            
            // Esperar la respuesta del AJAX
            const res = await $.ajax({
                url: API_URL,
                type: 'POST',
                data: objData,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            console.log('res___', res);
            
            if (!res.ok) {
                await Swal.fire('Error', res.mensaje, 'error');
                return;
            }
            
            const info = res.info[0];
            console.log('---', info.cod_prov);
            
            $('#aeProvincia').val(info.cod_prov);
            $('#aePartido').empty();
            $('#aePartido').append('<option value="' + info.cod_part + '">' + info.des_part + '</option>');
            $('#aeLocalidad').empty();
            $('#aeLocalidad').append('<option value="' + info.cod_loc + '" codsec="' + info.cod_sec + '">' + info.des_loc + '</option>');
        }
        
    } catch (error) {
        console.error('Error en ingcodpostal:', error);
        await Swal.fire('Error', 'No se pudo seleccionar la localidad.', 'error');
    }
};


    /* ═══════════════════════════════════════════
       Si cambia el cód postal busco Partido y Provincia
       ═══════════════════════════════════════════ */
	$('#aeCodPos').on('change', ingcodpostal );

    /* ═══════════════════════════════════════════
       Botón + por fila → setea sesión y redirige a concepto
       ═══════════════════════════════════════════ */
    $('#tablaEstablecimientos').on('click', '.btn-fila-seleccionar', function () {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        /*console.log('loc', $btn.data('codloc'), !$btn.data('codloc'))
        console.log('part', $btn.data('codpart'), !$btn.data('codpart')) 
        console.log('prov', $btn.data('codprov'), !$btn.data('codprov')) 
        */
        
        var codloc = $btn.attr('data-codloc');
        //console.log( 'codloc', codloc, !codloc || codloc === '' ) 
        //return false
        if( !codloc || codloc === '' ){
            
            //var rowData = $('#tablaEstablecimientos').DataTable().row(filaSeleccionada).data();
            console.log('muestro el modal')
            cargarFormData(function () {
                abrirModalEditar($btn);
                $('#modalEstable').modal('show');
                $btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                
                
            });
            
            return false
        }
        //console.log('no muestro el modal')
        //return false
        let objData = {
                modulo:          'establecimiento',
                action:          'seleccionar',
                csrf_token:       CSRF_TOKEN,
                cod_est:          $btn.data('cod'),
                id_convenio:      $btn.data('convenio-id'),
                id_tipo:          $btn.data('tipo-id'),
                convenio_nombre:  $btn.data('convenio-txt'),
                tipo_nombre:      $btn.data('tipo-txt'),
                razon_social:     $btn.data('razon'),
                calle:            $btn.data('calle'),
                numero:           $btn.data('numero'),
                seccional_nombre: $btn.data('seccional-txt'),
                cod_pos:          $btn.data('codpos'),
                id_sec:           $btn.data('id_sec')
            }
        console.log('objData', objData)
        
        $.ajax({
            url: API_URL, type: 'POST',
            data: objData,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                if (!res.ok) {
                    $btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                    Swal.fire('Error', res.mensaje, 'error');
                    return;
                }
                //window.location.href = res.redirect;
                window.location.href = '<?php echo SERVERURL; ?>pagarboletas';
            },
            error: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-print"></i>');
                Swal.fire('Error', 'No se pudo seleccionar el establecimiento.', 'error');
            }
        });
    });

    /* ═══════════════════════════════════════════
       Botón lápiz por fila
       ═══════════════════════════════════════════ */
    $('#tablaEstablecimientos').on('click', '.btn-fila-editar', function () {
        var $btn = $(this);
        cargarFormData(function () {
            abrirModalEditar($btn);
            $('#modalEstable').modal('show');
        });
    });

    /**************************/
    async function guardarEstablecimiento() {
        try {
            const res = await $.ajax({
                url: API_URL,
                type: 'POST',
                data: postData,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
    
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> <span id="btnGuardarTexto">' + (modoEditar ? 'Guardar cambios' : 'Guardar establecimiento') + '</span>');
    
            // Esperar a que el usuario cierre el Swal
            await Swal.fire({
                title: res.titulo,
                text: res.mensaje,
                icon: res.icono,
                confirmButtonColor: '#4e73df'
            });
    
            console.log('respuesta grabacion', res);
            
            if (!res.ok) return;
            
            $('#modalEstable').modal('hide');
    
            var tipotxt = $('#aeTipo option:selected').text();
            var convtxt = $('#aeConvenio option:selected').text();
            var secVal = id_sec;
            var domicilio = (calle || '-') + ' ' + (numero || '');
    
            if (modoEditar) {
                /* Actualizar fila existente en DataTable */
                var $tr = $(filaEditando);
                var row = tabla.row($tr);
                var data = row.data();
                
                data[2] = '<span style="font-weight:600;color:#2d3748;">' + razon_social + '</span>';
                data[3] = '<span style="color:#4a5568;">' + secVal + '</span>';
                data[4] = '<span style="color:#4a5568;">' + domicilio.trim() + '</span>';
                data[5] = '<span style="color:#4a5568;">' + (cod_pos || '-') + '</span>';
                data[6] = '<span style="background:#e8f0fe;color:#4e73df;padding:3px 10px;border-radius:20px;font-size:.8rem;font-weight:600;">' + tipotxt + '</span>';
                data[7] = '<span style="color:#4a5568;">' + convtxt + '</span>';
    
                /* Actualizar data-* del botón lápiz */
                data[8] = data[8]
                    .replace(/data-rs="[^"]*"/, 'data-rs="' + razon_social.replace(/"/g, '&quot;') + '"')
                    .replace(/data-tipo="[^"]*"/, 'data-tipo="' + id_tipo + '"')
                    .replace(/data-convenio="[^"]*"/, 'data-convenio="' + id_convenio + '"')
                    .replace(/data-sec="[^"]*"/, 'data-sec="' + id_sec + '"')
                    .replace(/data-calle="[^"]*"/, 'data-calle="' + calle.replace(/"/g, '&quot;') + '"')
                    .replace(/data-numero="[^"]*"/, 'data-numero="' + numero + '"')
                    .replace(/data-codpos="[^"]*"/, 'data-codpos="' + cod_pos + '"');
    
                row.data(data).draw(false);
            } else {
                /* Agregar nueva fila */
                var newRow = {
                    cod_est: res.cod_est,
                    razon_social: razon_social,
                    id_sec: id_sec,
                    id_tipo: id_tipo,
                    id_convenio: id_convenio,
                    calle: calle,
                    numero: numero,
                    cod_pos: cod_pos,
                    tipo: tipotxt,
                    convenio: convtxt
                };
                tabla.row.add(buildFila(newRow)).draw(false);
            }
    
            // ✅ Aquí puedes hacer lo que necesites después del mensaje
            console.log('Todo completado exitosamente');
            
            // Por ejemplo: recargar la página
            location.reload();
            
            // O mostrar otro mensaje
            // await Swal.fire('Éxito', 'Proceso completado', 'success');
    
        } catch (xhr) {
            // Manejo de errores
            console.log('Status:', xhr.status);
            console.log('Error:', xhr.statusText);
            console.log('Response Text:', xhr.responseText);
            
            if (xhr.status === 500) {
                console.log('Error del servidor:', xhr.responseText);
            }
            
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> <span id="btnGuardarTexto">' + (modoEditar ? 'Guardar cambios' : 'Guardar establecimiento') + '</span>');
            await Swal.fire('Error', 'No se pudo guardar el establecimiento.', 'error');
        }
    }
    
    /*************************/



    /* ═══════════════════════════════════════════
   Guardar (agregar o editar) - Versión Async/Await
   ═══════════════════════════════════════════ */
    $('#btnGuardarEstable').on('click', async function () {
    
        let razon_social = $('#aeRazonSocial').val().trim();
        let id_tipo      = $('#aeTipo').val();
        var id_sec       = $('#aeLocalidad').find('option:selected').attr('codsec')
        let id_convenio  = $('#aeConvenio').val();
        let calle        = $('#aeCalle').val().trim();
        let numero       = $('#aeNumero').val().trim();
        let cod_pos      = $('#aeCodPos').val().trim();
        
        let cod_loc      = $('#aeLocalidad').val().trim();
        let cod_par      = $('#aePartido').val().trim();
        let cod_prv      = $('#aeProvincia').val().trim();
        let telefon      = $('#aeTelef').val().trim();
        let fec_ini      = $('#aeIniAct').val().trim();
        let cod_sec      = $('#aeLocalidad').find('option:selected').attr('codsec');
        let piso_dto     = $('#aePisoDepto').val().trim();
        
        if (!razon_social || !id_tipo || !id_convenio || !cod_loc || !cod_pos || cod_pos == 0) {
            Swal.fire({ title: 'Campos incompletos', text: 'Razón social, tipo, fecha de inicio de actividades, localidad y convenio son obligatorios.', icon: 'warning', confirmButtonColor: '#4e73df' });
            return;
        }
    
        var action   = modoEditar ? 'editar' : 'agregar';
        var postData = {
            modulo: 'establecimiento', 
            action: action, 
            csrf_token: CSRF_TOKEN,
            razon_social: razon_social, 
            id_tipo: id_tipo, 
            id_convenio: id_convenio, 
            calle: calle, 
            numero: numero, 
            cod_pos: cod_pos,
            localidad: cod_loc,
            partido: cod_par,
            provincia: cod_prv,
            telefono: telefon,
            fec_ini: fec_ini,
            cod_sec: cod_sec,
            piso_dto: piso_dto
        };
        
        console.log('postData', postData);
        
        if (modoEditar) postData.cod_est = $('#aeCodEst').val();
    
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');
    
        try {
            // Esperar la respuesta del AJAX
            const res = await $.ajax({
                url: API_URL,
                type: 'POST',
                data: postData,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
    
            // Restaurar el botón
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> <span id="btnGuardarTexto">' + (modoEditar ? 'Guardar cambios' : 'Guardar establecimiento') + '</span>');
    
            // Esperar a que el usuario cierre el Swal
            await Swal.fire({
                title: res.titulo,
                text: res.mensaje,
                icon: res.icono,
                confirmButtonColor: '#4e73df'
            });
    
            console.log('respuesta grabacion', res);
            
            if (!res.ok) return;
            
            $('#modalEstable').modal('hide');
    
            var tipotxt = $('#aeTipo option:selected').text();
            var convtxt = $('#aeConvenio option:selected').text();
            var secVal = id_sec;
            var domicilio = (calle || '-') + ' ' + (numero || '');
    
            if (modoEditar) {
                /* Actualizar fila existente en DataTable */
                var $tr = $(filaEditando);
                var row = tabla.row($tr);
                var data = row.data();
                
                data[1] = '<span style="font-family:monospace;font-weight:600;color:#4e73df;">' + razon_social + '</span>';
                data[2] = '<span style="font-weight:600;color:#2d3748;">' + domicilio.trim() + '</span>';
                data[3] = '<span style="color:#4a5568;">' + (cod_loc || '-') + '</span>';
                data[4] = '<span style="color:#4a5568;">' + (secVal || '-') + '</span>';
                data[5] = '<span style="background:#e8f0fe;color:#4e73df;padding:3px 10px;border-radius:20px;font-size:.8rem;font-weight:600;">' + tipotxt + '</span>';
                data[6] = '<span style="color:#4a5568;">' + convtxt + '</span>';
    
                /* Actualizar data-* del botón lápiz */
                data[7] = data[7]
                    .replace(/data-rs="[^"]*"/, 'data-rs="' + razon_social.replace(/"/g, '&quot;') + '"')
                    .replace(/data-tipo="[^"]*"/, 'data-tipo="' + id_tipo + '"')
                    .replace(/data-convenio="[^"]*"/, 'data-convenio="' + id_convenio + '"')
                    .replace(/data-sec="[^"]*"/, 'data-sec="' + id_sec + '"')
                    .replace(/data-calle="[^"]*"/, 'data-calle="' + calle.replace(/"/g, '&quot;') + '"')
                    .replace(/data-numero="[^"]*"/, 'data-numero="' + numero + '"')
                    .replace(/data-codpos="[^"]*"/, 'data-codpos="' + cod_pos + '"');
    
                row.data(data).draw(false);
            } else {
                /* Agregar nueva fila */
                let newRow = {
                    cod_est: res.cod_est,
                    razon_social: razon_social,
                    id_sec: id_sec,
                    id_tipo: id_tipo,
                    id_convenio: id_convenio,
                    calle: calle,
                    numero: numero,
                    cod_pos: cod_pos,
                    tipo: tipotxt,
                    convenio: convtxt
                };
                tabla.row.add(buildFila(newRow)).draw(false);
            }
    
            location.reload();
    
        } catch (xhr) {
            // Manejo de errores
            console.log('Status:', xhr.status);
            console.log('Error:', xhr.statusText);
            console.log('Response Text:', xhr.responseText);
            
            if (xhr.status === 500) {
                console.log('Error del servidor:', xhr.responseText);
            }
            
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> <span id="btnGuardarTexto">' + (modoEditar ? 'Guardar cambios' : 'Guardar establecimiento') + '</span>');
            await Swal.fire('Error', 'No se pudo guardar el establecimiento.', 'error');
        }
    });

    /* ═══════════════════════════════════════════
       Botón "Cambiar empresa"
       ═══════════════════════════════════════════ */
    $('#btnCambiarEmpresa').on('click', function () {
        $.ajax({
            url: API_URL, type: 'POST',
            data: { modulo: 'establecimiento', action: 'limpiar', csrf_token: CSRF_TOKEN },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            complete: function () { window.location.href = '<?php echo SERVERURL; ?>consulta'; }
        });
    });
    
    $('#tablaEstablecimientos').on('click', '#row_cod_pos', function() {
        let $btn = $('#tablaEstablecimientos .btn-fila-seleccionar');
        $.ajax({
            url: API_URL,
            type: 'POST',
            data: {
                modulo: 'establecimiento',
                action: 'seleccionar',
                csrf_token: CSRF_TOKEN,
                cod_est: $btn.data('cod'),
                id_convenio: $btn.data('convenio-id'),
                id_tipo: $btn.data('tipo-id'),
                convenio_nombre: $btn.data('convenio-txt'),
                tipo_nombre: $btn.data('tipo-txt'),
                razon_social: $btn.data('razon'),
                calle: $btn.data('calle'),
                numero: "269542",
                seccional_nombre: $btn.data('seccional-txt'),
                cod_pos: $btn.data('codpos'),
                id_sec: $btn.data('id_sec')
            },
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                if (!res.ok) {
                    Swal.fire('Error', res.mensaje, 'error');
                    return;
                }
                window.location.href = '<?php echo SERVERURL; ?>pagarboletas';
            },
            error: function() {
                Swal.fire('Error', 'No se pudo seleccionar el establecimiento.', 'error');
            }
        });
    });

    $('#aeIniAct').on('input', function() {
        let valor = $(this).val();
        let $input = $(this);
        let $mensaje = $('#mensajeFecha');
        
        let numeros = valor.replace(/[^\d]/g, '');
        
        if (numeros.length >= 2) {
            numeros = numeros.substring(0, 2) + '/' + numeros.substring(2);
        }
        if (numeros.length >= 5) {
            numeros = numeros.substring(0, 5) + '/' + numeros.substring(5);
        }
        
        if (numeros.length > 10) {
            numeros = numeros.substring(0, 10);
        }
        
        $input.val(numeros);
        
        if (numeros.length === 10) {
            if (validarFecha(numeros)) {
                $input.removeClass('invalido').addClass('valido');
                $mensaje.text('✓ Fecha válida').removeClass('error').addClass('ok').show();
            } else {
                $input.removeClass('valido').addClass('invalido');
                $mensaje.text('✗ Fecha inválida').removeClass('ok').addClass('error').show();
            }
        } else if (numeros.length > 0) {
            $input.removeClass('valido invalido');
            $mensaje.hide();
        } else {
            $input.removeClass('valido invalido');
            $mensaje.hide();
        }
    });

    function validarFecha(fecha) {
        let partes = fecha.split('/');
        if (partes.length !== 3) return false;
        
        let dia = parseInt(partes[0], 10);
        let mes = parseInt(partes[1], 10);
        let anio = parseInt(partes[2], 10);
        
        if (isNaN(dia) || isNaN(mes) || isNaN(anio)) return false;
        if (dia < 1 || dia > 31) return false;
        if (mes < 1 || mes > 12) return false;
        if (anio < 1900 || anio > 2100) return false;
        
        let diasPorMes = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        
        if (mes === 2) {
            let esBisiesto = (anio % 4 === 0 && anio % 100 !== 0) || (anio % 400 === 0);
            if (esBisiesto) diasPorMes[1] = 29;
        }
        
        return dia <= diasPorMes[mes - 1];
    }
});
</script>
