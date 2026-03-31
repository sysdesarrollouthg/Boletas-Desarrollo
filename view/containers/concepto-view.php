<?php

unset($_SESSION['actas']);
unset($_SESSION['boleta']);
unset($_SESSION['codigo_barra']);

$sesion = [
    'empresa_cuit'   => $_SESSION['empresa_cuit']   ?? '',
    'empresa_id'     => (int) ($_SESSION['empresa_id'] ?? 0),
    'empresa_nombre' => $_SESSION['empresa_nombre'] ?? '',
    'est_cod_est'    => $_SESSION['est_cod_est']    ?? '',
    'est_nombre'     => $_SESSION['est_nombre']     ?? '',
    'est_calle'      => $_SESSION['est_calle']      ?? '',
    'est_numero'     => $_SESSION['est_numero']     ?? '',
    'est_seccional'  => $_SESSION['est_seccional']  ?? '',
];

?>
<script src="../js/functions.js"></script>

<style>
.cv-wrapper { max-width:1100px; margin:0; padding:0.5rem 1.5rem 2rem; }
.cv-select {
    background:#f7f8fc; border:2px solid #e2e8f0; border-radius:10px;
    padding:0 36px 0 14px; height:44px; font-size:.92rem; font-weight:600;
    color:#2d3748; outline:none; transition:border-color .2s,box-shadow .2s;
    appearance:none; width:100%;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234e73df' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 14px center;
}
.cv-select:focus { border-color:#4e73df; box-shadow:0 0 0 3px rgba(78,115,223,0.15); background-color:#fff; }
.cv-label, .pt-label {
    font-size:.72rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.07em; color:#718096; margin-top:10px; display:block;
}
.pt-select {
    width:100%; background:#f7f8fc; border:2px solid #e2e8f0; border-radius:10px;
    padding:0 36px 0 14px; height:44px; font-size:.92rem; font-weight:600;
    color:#2d3748; outline:none; transition:border-color .2s,box-shadow .2s;
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234e73df' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 14px center;
}
.pt-select:focus { border-color:#4e73df; box-shadow:0 0 0 3px rgba(78,115,223,0.15); background-color:#fff; }
.pt-input {
    width:100%; background:#f7f8fc; border:2px solid #e2e8f0; border-radius:10px;
    padding:0 14px; height:44px; font-size:.92rem; font-weight:600; color:#2d3748;
    outline:none; transition:border-color .2s,box-shadow .2s,background .2s;
}
.pt-input:focus { border-color:#4e73df; box-shadow:0 0 0 3px rgba(78,115,223,0.15); background:#fff; }
.pt-input::placeholder { color:#cbd5e0; font-weight:400; }
.pt-prefix-wrap {
    display:flex; align-items:center; background:#f7f8fc;
    border:2px solid #e2e8f0; border-radius:10px; overflow:hidden;
    transition:border-color .2s,box-shadow .2s;
}
.pt-prefix-wrap:focus-within { border-color:#4e73df; box-shadow:0 0 0 3px rgba(78,115,223,0.15); background:#fff; }
.pt-prefix {
    padding:0 12px; font-size:.78rem; font-weight:800; color:#4e73df;
    height:44px; display:flex; align-items:center;
    background:#eef1fb; border-right:2px solid #e2e8f0; white-space:nowrap;
}
.pt-prefix-wrap:focus-within .pt-prefix { border-right-color:#4e73df; }
.pt-input-prefixed {
    flex:1; border:none; background:transparent; padding:0 14px;
    height:44px; font-size:.92rem; font-weight:600; color:#2d3748; outline:none;
}
.pt-input-prefixed::placeholder { color:#cbd5e0; font-weight:400; }
.pt-btn-outline {
    border:2px solid #4e73df; background:#fff; color:#4e73df;
    border-radius:10px; padding:0 14px; height:44px; font-size:.8rem;
    font-weight:700; cursor:pointer; white-space:nowrap;
    transition:background .2s,color .2s; display:inline-flex; align-items:center; flex-shrink:0;
}
.pt-btn-outline:hover { background:#4e73df; color:#fff; }
.pt-btn-gray { border-color:#a0aec0; color:#718096; }
.pt-btn-gray:hover { background:#718096; color:#fff; border-color:#718096; }
.pt-btn-primary {
    background:linear-gradient(135deg,#4e73df,#224abe); border:none; color:#fff;
    border-radius:10px; padding:0 20px; height:44px; font-size:.88rem; font-weight:700;
    cursor:pointer; white-space:nowrap; box-shadow:0 4px 14px rgba(78,115,223,0.35);
    transition:opacity .2s,box-shadow .2s; display:inline-flex; align-items:center; flex-shrink:0;
}
.pt-btn-primary:hover { opacity:.92; box-shadow:0 6px 20px rgba(78,115,223,0.45); }
.pt-badge-wrap { display:flex; align-items:center; gap:6px; margin-top:8px; }
.pt-badge-fecha {
    background:linear-gradient(135deg,#ff6b6b,#ee5a24); color:#fff;
    font-weight:700; font-size:.82rem; padding:3px 12px; border-radius:20px; white-space:nowrap;
}
.cv-divider { height:1px; background:#e2e8f0; margin:2rem 0; }
.ar-th {
    font-size:.72rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.06em; color:#718096; padding:8px 10px;
    background:#f8f9fc; border-bottom:2px solid #e2e8f0;
}
.ar-td { font-size:.88rem; padding:8px 10px; color:#4a5568; border-bottom:1px solid #f0f4ff; }
.ar-td-mono { font-family:monospace; font-weight:700; color:#2d3748; }
</style>

<div class="cv-wrapper">

    <!-- ══════════════════════════════════════
         CARD CONTEXTO — Empresa / Establecimiento
         ══════════════════════════════════════ -->
    <div class="card border-0 mb-3" style="border-radius:14px;background:linear-gradient(135deg,#4e73df,#224abe);overflow:hidden;">
        <div class="card-body px-5 py-4">
            <div class="row align-items-center g-4">

                <!-- Empresa -->
                <div class="col-md-6 d-flex align-items-center" style="gap:18px;">
                    <div style="width:50px;height:50px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-building" style="color:#fff;font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,0.65);">Empresa</div>
                        <div id="ctx_emp_nombre" style="font-size:1rem;font-weight:800;color:#fff;line-height:1.2;">
                            <?php echo htmlspecialchars($sesion['empresa_nombre'] ?: '—'); ?>
                        </div>
                        <div id="ctx_emp_cuit" style="font-size:.78rem;font-weight:600;color:rgba(255,255,255,0.75);margin-top:2px;">
                            CUIT: <?php echo htmlspecialchars($sesion['empresa_cuit'] ?: '—'); ?>
                        </div>
                    </div>
                </div>

                <!-- Establecimiento -->
                <div class="col-md-6 d-flex align-items-center" id="head_est" style="gap:18px;">
                    <div style="width:50px;height:50px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-store" style="color:#fff;font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,0.65);">Establecimiento</div>
                        <div id="ctx_est_nombre" style="font-size:1rem;font-weight:800;color:#fff;line-height:1.2;">
                            <?php echo htmlspecialchars($sesion['est_nombre'] ?: '—'); ?>
                        </div>
                        <div id="ctx_est_detalle" style="font-size:.78rem;font-weight:600;color:rgba(255,255,255,0.75);margin-top:2px;">
                            <?php
                            $dir = trim(($sesion['est_calle'] ?? '') . ' ' . ($sesion['est_numero'] ?? ''));
                            echo htmlspecialchars($dir ?: '—');
                            if ($sesion['est_seccional']) echo ' &nbsp;·&nbsp; Sec. ' . htmlspecialchars($sesion['est_seccional']);
                            ?>
                        </div>
                    </div>
                </div>

                <style>
                    @media (max-width: 800px) {
    
                       .cv-wrapper{
                        padding:0px 0px 10px 0px !important;
                       }
                    
                       #head_est{
                        margin-top:20px;
                       } 
                    
                       .selector_btns{
                        flex-direction:column;
                        align-items:flex-start !important;
                       }
                    
                       #cambiarEstablecimientoBtn{
                        margin-left: 0 !important;
                       }
                    
                       .container, 
                       .container-fluid, 
                       .container-lg, 
                       .container-md, 
                       .container-sm, 
                       .container-xl {
                            padding-left: 0rem !important;
                            padding-right: 0rem !important;
                       }
                    
                       .p-5{
                            padding: 1.5rem !important;
                       }
                    
                       #btnCalcularImporte, 
                       #btnCalcularRecargos, 
                       #btnCalcularTotal{
                            display:flex;
                            flex-direction:column-reverse;
                            gap:6px;
                       }
                    }
                </style>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════
         SELECTOR TIPO DE PAGO
         ══════════════════════════════════════ -->
    <div class="mb-3 mt-2 selector_btns" style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:10px 14px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
        <span style="font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.09em;color:#a0aec0;margin-right:4px;white-space:nowrap;">Tipo de pago</span>

        <!-- Conceptos — activo (esta vista) -->
        <button type="button" disabled
            style="display:inline-flex;align-items:center;gap:7px;
                   background:linear-gradient(135deg,#4e73df,#224abe);color:#fff;
                   border:none;border-radius:9px;padding:0 16px;height:36px;
                   font-size:.82rem;font-weight:700;cursor:default;
                   box-shadow:0 3px 10px rgba(78,115,223,0.3);">
            <i class="fas fa-layer-group" style="font-size:.8rem;"></i>
            Pago Período
        </button>

        <!-- Actas -->
        <a href="actas"
            style="display:inline-flex;align-items:center;gap:7px;
                   background:#f1f3f8;color:#718096;
                   border:2px solid #e2e8f0;border-radius:9px;padding:0 16px;height:36px;
                   font-size:.82rem;font-weight:700;cursor:pointer;text-decoration:none;
                   transition:background .2s,color .2s,border-color .2s;"
            onmouseover="this.style.background='#e2e8f0';this.style.color='#4a5568';"
            onmouseout="this.style.background='#f1f3f8';this.style.color='#718096';">
            <i class="fas fa-file-signature" style="font-size:.8rem;"></i>
            Actas
        </a>

        <!-- Acuerdos -->
        <a href="acuerdos"
            style="display:inline-flex;align-items:center;gap:7px;
                   background:#f1f3f8;color:#718096;
                   border:2px solid #e2e8f0;border-radius:9px;padding:0 16px;height:36px;
                   font-size:.82rem;font-weight:700;cursor:pointer;text-decoration:none;
                   transition:background .2s,color .2s,border-color .2s;"
            onmouseover="this.style.background='#e2e8f0';this.style.color='#4a5568';"
            onmouseout="this.style.background='#f1f3f8';this.style.color='#718096';">
            <i class="fas fa-handshake" style="font-size:.8rem;"></i>
            Acuerdos
        </a>

        <!-- Cambiar establecimiento -->
        <a href="establecimiento" id="cambiarEstablecimientoBtn"
            style="display:inline-flex;align-items:center;gap:7px;margin-left:auto;
                   background:#fff5f5;color:#e53e3e;
                   border:2px solid #fed7d7;border-radius:9px;padding:0 14px;height:36px;
                   font-size:.82rem;font-weight:700;text-decoration:none;
                   transition:background .2s,color .2s;"
            onmouseover="this.style.background='#fed7d7';this.style.borderColor='#fc8181';"
            onmouseout="this.style.background='#fff5f5';this.style.borderColor='#fed7d7';">
            <i class="fas fa-store-alt" style="font-size:.8rem;"></i>
            Cambiar establecimiento
        </a>
    </div>

    <!-- ══════════════════════════════════════
         CARD PRINCIPAL — Cálculo de boleta
         ══════════════════════════════════════ -->
    <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
        <div style="height:4px; background:linear-gradient(135deg,#4e73df,#224abe);"></div>
        <div class="card-body p-5">

            <!-- ── Concepto + Detalle + Porcentaje en una línea ── -->
            <div class="row g-4 align-items-end mb-1">
                <div class="col-md-4">
                    <label class="cv-label"><i class="fas fa-layer-group mr-1"></i> Concepto</label>
                    <div id="cont_conceptos"></div>
                </div>
                <div class="col-md-5">
                    <label class="cv-label"><i class="fas fa-list-alt mr-1"></i> Detalle</label>
                    <select class="cv-select" id="concepto_detalle" onchange="selDetalle(this)">
                        <option value="">Seleccione un concepto primero</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div id="tipoPagoInfo" style="display:none; flex-direction:column;">
                        <label class="cv-label"><i class="fas fa-percent mr-1"></i> Porcentaje</label>
                        <div style="height:44px;background:#f0f4ff;border:2px solid #c7d4f7;border-radius:10px;display:flex;align-items:center;gap:10px;padding:0 14px;">
                            <div style="width:28px;height:28px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-percent" style="color:#fff;font-size:.7rem;"></i>
                            </div>
                            <span id="lblInfoTipPagoValor" style="font-size:1.05rem;font-weight:800;color:#4e73df;"></span>
                            <span id="lblInfoTipPago" style="display:none;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cv-divider"></div>

            <!-- ── Período ── -->
            <div class="row g-4 align-items-end">
                <div class="col-md-4">
                    <label class="pt-label"><i class="fas fa-calendar-alt mr-1"></i> Mes</label>
                    <select id="cmbMes" class="pt-select" onchange="selPeriodo()">
                        <option value="0">Seleccione un mes</option>
                        <?php
                        $meses = ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio',
								  '07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];

						for ($i = 1; $i <= 12; $i++) {
							$mes = sprintf('%02d', $i);
							echo "<option value=\"$mes\">{$meses[$mes]}</option>";
						}
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="pt-label"><i class="fas fa-calendar mr-1"></i> Año</label>
                    <select id="cmbAnio" class="pt-select" onchange="selPeriodo()">
                        <option value="0">Seleccione un año</option>
                        <?php
                        $anio_actual = date('Y');
                        for ($i = $anio_actual; $i >= $anio_actual - 10; $i--) echo "<option value=\"$i\">$i</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <div id="fecvencimiento-wrap" style="display:none;">
                        <label class="pt-label"><i class="fas fa-calendar-check mr-1"></i> Vencimiento</label>
                        <div style="height:44px;display:flex;align-items:center;">
                            <span id="fecvencimiento" class="pt-badge-fecha" style="font-size:.95rem;padding:6px 18px;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cv-divider"></div>

            <!-- ── Importes ── -->
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="pt-label"><i class="fas fa-users mr-1"></i> Cantidad de Empleados</label>
                    <input type="text" id="cantempleados" class="pt-input" placeholder="Ej: 10" autocomplete="off" oninput="this.value = this.value.replace(/[^\d]/g, '').slice(0, 4);">
                </div>

                <div class="col-md-6">
                    <label class="pt-label"><i class="fas fa-money-bill-wave mr-1"></i> Total Remuneraciones</label>
                    <div class="pt-prefix-wrap">
                        <span class="pt-prefix">$</span>
                        <input  type="text" 
                                id="totalremuneracion1" 
                                class="pt-input-prefixed" 
                                placeholder="123456789.12" 
                                autocomplete="off" 
                                inputmode="numeric" 
                                oninput="this.value = this.value
                                        .replace(/[^\d.]/g, '')           // Solo números y punto
                                        .replace(/(\..*?)\./g, '$1')      // Solo un punto decimal
                                        .replace(/^\./, '')                // No permite punto al inicio
                                        .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')  // CORREGIDO: permite 0-2 decimales
                                        .slice(0,12)">
                    </div>
                </div>

                <div class="col-12" style="margin-top:25px;">
                    <label class="pt-label"><i class="fas fa-coins mr-1"></i> Importe</label>
                    <div class="d-flex gap-2" id="btnCalcularImporte">
                        <div class="pt-prefix-wrap" style="flex:1;">
                            <span class="pt-prefix">$</span>
                            <input  type="text" 
                                    id="importe1" 
                                    class="pt-input-prefixed" 
                                    placeholder="0.00" 
                                    autocomplete="off" 
                                    inputmode="numeric"
                                    oninput="this.value = this.value
                                        .replace(/[^\d.]/g, '')           // Solo números y punto
                                        .replace(/(\..*?)\./g, '$1')      // Solo un punto decimal
                                        .replace(/^\./, '')                // No permite punto al inicio
                                        .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')  // CORREGIDO: permite 0-2 decimales
                                        .slice(0,12)"
                            >
                        </div>
                        <button type="button" onclick="calcularImporte(1)" class="pt-btn-outline" >
                            <i class="fas fa-calculator mr-1"></i> Calcular Importe
                        </button>
                    </div>
                </div>

                <div class="col-12" style="margin-top:25px;">
                    <label class="pt-label"><i class="fas fa-percentage mr-1"></i> Total Recargos</label>
                    <div class="d-flex gap-2" id="btnCalcularRecargos">
                        <div class="pt-prefix-wrap" style="flex:1;">
                            <span class="pt-prefix">$</span>
                            <input  type="text" 
                                    id="recargos1" 
                                    class="pt-input-prefixed" 
                                    placeholder="00" 
                                    inputmode="numeric" 
                                    oninput="this.value = this.value
                                        .replace(/[^\d.]/g, '')           // Solo números y punto
                                        .replace(/(\..*?)\./g, '$1')      // Solo un punto decimal
                                        .replace(/^\./, '')                // No permite punto al inicio
                                        .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')  // CORREGIDO: permite 0-2 decimales
                                        .slice(0,12)"
                                    style="background:#f8f9fc;color:#718096;">
                            <input type="hidden" id="recargos1_aux">
                        </div>
                        <button type="button" id="ayudarecargos1" class="pt-btn-outline pt-btn-gray"
                                onclick="abrirAyudaRecargos()">
                            <i class="fas fa-table mr-1"></i> Ayuda Recargos
                        </button>
                    </div>
                </div>

                <div class="col-12" style="margin-top:25px;">
                    <div style="background:linear-gradient(135deg,#f0f4ff,#e8f0fe);border:2px solid #c7d4f7;border-radius:14px;padding:1.1rem 1.25rem;">
                        <label class="pt-label" style="color:#4e73df;">
                            <i class="fas fa-check-circle mr-1"></i> Total Depositado
                        </label>
                        <div class="d-flex gap-2 align-items-center" id="btnCalcularTotal">
                            <div class="pt-prefix-wrap" style="flex:1;">
                                <span class="pt-prefix" style="background:#4e73df;color:#fff;border-right-color:#4e73df;">$</span>
                                <input type="text" id="totaldepositado1" class="pt-input-prefixed" placeholder="00" inputmode="numeric" oninput="this.value = this.value.replace(/[^\d]/g, '').slice(0,14)"
                                    style="font-size:1.1rem;font-weight:800;color:#2d3748;background:transparent;">
                            </div>
                            <button type="button" onclick="calcularTotal(1)" class="pt-btn-primary">
                                <i class="fas fa-calculator mr-1"></i> Calcular Total
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="boleta-payment" style="display:none"></div>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════
     MODAL — Ayuda Recargos
     ════════════════════════════════════ -->
<div class="modal fade" id="modalAyudaRecargos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.15);">

            <div class="modal-header" style="background:linear-gradient(135deg,#4e73df,#224abe);border-radius:16px 16px 0 0;border:none;padding:1.25rem 1.75rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-table" style="color:#fff;font-size:1rem;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="color:#fff;font-weight:700;">Detalle de Intereses Resarcitorios</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.8;font-size:1.5rem;">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding:1.5rem;">

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="pt-label"><i class="fas fa-calendar-times mr-1"></i> Fecha de Vencimiento</label>
                        <input type="text" id="ar_fecvencimiento" class="pt-input" placeholder="DD/MM/YYYY" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label class="pt-label"><i class="fas fa-calendar-check mr-1"></i> Fecha de Pago</label>
                        <input type="text" id="ar_fecpagocap" class="pt-input" placeholder="DD/MM/YYYY" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label class="pt-label"><i class="fas fa-coins mr-1"></i> Importe Capital</label>
                        <div class="pt-prefix-wrap">
                            <span class="pt-prefix">$</span>
                            <input type="text" id="ar_importecap" class="pt-input-prefixed" placeholder="0.00" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="button" onclick="calcularRecargos()" class="pt-btn-primary">
                            <i class="fas fa-calculator mr-1"></i> Calcular Intereses
                        </button>
                    </div>
                </div>

                <div id="ar_resultado" style="display:none;">
                    <div style="background:#f0f4ff;border-radius:10px;padding:.75rem 1rem;margin-bottom:1rem;display:flex;gap:2rem;flex-wrap:wrap;">
                        <div>
                            <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#718096;">Fecha Vencimiento</span>
                            <div style="font-weight:700;color:#2d3748;" id="ar_resumen_venc"></div>
                        </div>
                        <div>
                            <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#718096;">Fecha Pago</span>
                            <div style="font-weight:700;color:#2d3748;" id="ar_resumen_pago"></div>
                        </div>
                        <div>
                            <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#718096;">Total Intereses</span>
                            <div style="font-weight:700;color:#e63946;font-size:1.05rem;" id="ar_resumen_total"></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0" style="border-radius:10px;overflow:hidden;">
                            <thead>
                                <tr>
                                    <th class="ar-th">Desde</th>
                                    <th class="ar-th">Hasta</th>
                                    <th class="ar-th text-right">Importe</th>
                                    <th class="ar-th text-center">Tasa %</th>
                                    <th class="ar-th text-center">Días</th>
                                    <th class="ar-th text-right">Intereses</th>
                                </tr>
                            </thead>
                            <tbody id="ar_tbody"></tbody>
                        </table>
                    </div>
                </div>

                <div id="ar_spinner" style="display:none;text-align:center;padding:2rem;">
                    <i class="fas fa-spinner fa-spin fa-2x" style="color:#4e73df;"></i>
                </div>

            </div>

            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.75rem;border-radius:0 0 16px 16px;">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"
                        style="border-radius:10px;font-weight:600;">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>

        </div>
    </div>

  
</div>

<script>
$(document).ready(function () {

    const CSRF_TOKEN = "<?php echo csrf_token(); ?>";
    const API_URL    = "<?php echo SERVERURL; ?>api.php";

    window._CSRF           = CSRF_TOKEN;
    window._API            = API_URL;
    window.gFecVencimiento = '';
    
    // ── Contexto empresa/establecimiento desde BD ──
    $.ajax({
        url: API_URL, type: 'POST',
        data: { modulo: 'concepto', action: 'contexto', csrf_token: CSRF_TOKEN },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            if (!res.ok || !res.data) return;
            var d = res.data;
            if (d.empresa_nombre) $('#ctx_emp_nombre').text(d.empresa_nombre);
            if (d.empresa_cuit)   $('#ctx_emp_cuit').text('CUIT: ' + d.empresa_cuit);
            var nombre  = d.razon_social || '—';
            var detalle = [d.calle, d.numero].filter(Boolean).join(' ');
            if (d.seccional_nombre) detalle += (detalle ? '  ·  ' : '') + 'Sec. ' + d.seccional_nombre;
            $('#ctx_est_nombre').text(nombre);
            $('#ctx_est_detalle').text(detalle || '—');
        }
    });

    // ── Cargar conceptos ──
    var fila = '';
    $.ajax({
        url: API_URL, type: 'POST',
        data: { modulo: 'concepto', action: 'listar', csrf_token: CSRF_TOKEN },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            if (!res.ok) return;
            $.each(res.data, function (i, row) {
                fila += '<option value="' + row.id + '">' + row.concepto + '</option>';
            });
            $('#cont_conceptos').html('<select required id="cmbConcepto" class="cv-select" onchange="selConcepto(this)"><option required value="0" selected>Seleccionar</option>' + fila + '</select>');

        },
        error: function (e) { Swal.fire('Error', 'No se pudo cargar los conceptos.', 'error'); console.log(e);}
    });

});

function selConcepto(o) {

    let valorPago = 0
    let cuentadet = 0

    $('#concepto_detalle').html('<option value="">Cargando...</option>');
    $('#tipoPagoInfo').hide();

    $.ajax({
        url: window._API, type: 'POST',
        data: { modulo: 'concepto', action: 'detalle', concepto: o.value, csrf_token: window._CSRF },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            if (!res.ok) {
                $('#concepto_detalle').html('<option value="">Sin detalles disponibles</option>');
                return;
            }
            if(res.data.length > 1)
                $('#concepto_detalle').html('<option value="">Seleccione un detalle</option>');
            else 
                $('#concepto_detalle').html("");
            
            $.each(res.data, function (i, row) {
                
                cuentadet++
                
                if(row.tipCalculo == "P"){
                    valorPago = row.porCalculo
                }
                
                $('#concepto_detalle').append(
                    $('<option>', { 
						value: row.codConcBoleta, 
						text: row.desBoleta, 
						tippago: row.tipCalculo, 
						codente: row.codEnte, 
						tippagoval: valorPago, 
						desBanco: row.desBanco, 
						ctabanco: row.ctabanco_des,
						pb_key: row.public_key,
						pv_key: row.access_token
					})
                );
            });
            
            if(cuentadet == 1){
                selDetalle( $('#concepto_detalle') )
            }
        },
        error: function () { Swal.fire('Error', 'No se pudo cargar el detalle.', 'error'); }
    });
}

function selDetalle(o) {
    var sel = $('#concepto_detalle option:selected');
    $('#lblInfoTipPago').text('PORCENTAJE');
    $('#lblInfoTipPagoValor').text(sel.attr('tippagoval'));
    $('#tipoPagoInfo').css('display', 'flex');
}

function selPeriodo() {
    var anio = document.getElementById('cmbAnio').value;
    var mes  = document.getElementById('cmbMes').value;
    if (anio == 0 || mes == 0) return;

    var concepto = document.getElementById('cmbConcepto') ? document.getElementById('cmbConcepto').value : '';

    let est_cod_est = '<?php echo $_SESSION['est_cod_est']; ?>';
    let est_id_convenio = '<?php echo $_SESSION['est_id_convenio']; ?>';
    let conc_boleta = document.getElementById('concepto_detalle').value;
    let id_sec = '<?php echo $_SESSION['id_sec']; ?>';

    var $btnCalc = $('button[onclick="calcularTotal(1)"]');
    $btnCalc.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Calculando...');

    $.ajax({
        url: window._API, type: 'POST',
        data: { modulo: 'concepto', action: 'vencimiento', csrf_token: window._CSRF, concepto: concepto, anio: anio, mes: mes },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            console.log('res', res)
            console.log('res', res['vencimiento'])
            if (!res.ok || !res['vencimiento']) {
                $('#fecvencimiento-wrap').hide();
                window.gFecVencimiento = '';
                return;
            }
            window.gFecVencimiento = res['vencimiento']; //res.data[0];
            $('#fecvencimiento').text(res['vencimiento']); //$('#fecvencimiento').text(res.data[0]);
            $('#fecvencimiento-wrap').show();

            
        },
        error: function () { Swal.fire('Error', 'No se pudo obtener el vencimiento.', 'error'); },
        complete: function () {
            $btnCalc.prop('disabled', false).html('<i class="fas fa-calculator mr-1"></i> Calcular Total');
        }
    });
}


function calcularImporte(iOpcion) {

	let concepto = $('#cmbConcepto').val();
	let detalle = $('#concepto_detalle').val();


	if (!concepto || concepto === '' || concepto === '0' || !detalle || detalle === '' || detalle === '0') {
		Swal.fire({ 
			title: 'Campos requeridos', 
			text: 'Seleccioná el Concepto y el Detalle antes de continuar.', 
			icon: 'warning', 
			confirmButtonColor: '#4e73df' 
		});
		return;
	}
    let tippago     = $('#lblInfoTipPago').text();
    let tippagoval  = parseFloat( document.getElementById('lblInfoTipPagoValor').innerHTML ) || 0;
	
	let valor = document.getElementById('lblInfoTipPagoValor').innerHTML;
	let valorConvertido = valor.replace(',', '.');
	
    let totremunera = parseFloat($('#totalremuneracion' + iOpcion).val()) || 0;
		
	console.log(concepto + " " + detalle)
	console.log('totremunera', totremunera + " / ", 'tippagoval', valorConvertido)


    if (tippago === 'PORCENTAJE') {
		$('#importe' + iOpcion).val((totremunera * valorConvertido / 100).toFixed(2));
    }
}

function calcularTotal(iOpcion) {
    let importe        = document.getElementById('importe1').value;
    let fecvencimiento = window.gFecVencimiento || $('#fecvencimiento').text().trim();
    let remuneracion = document.getElementById('totalremuneracion1').value;

    if (!importe || !fecvencimiento) {
        Swal.fire({ title: 'Datos incompletos', text: 'Completá el importe y seleccioná un período con fecha de vencimiento.', icon: 'warning', confirmButtonColor: '#4e73df' });
        return;
    }

    $.ajax({
        url: window._API, type: 'POST',
        data: { modulo: 'concepto', action: 'calcularTotal', csrf_token: window._CSRF, importe: importe, fecvencimiento: fecvencimiento },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            if (!res.ok) { Swal.fire('Error', res.mensaje || 'Error al calcular.', 'error'); return; }
            let recargos = parseFloat(res.total_recargo) || 0;
            let imp = parseFloat(importe) || 0;

            if (document.getElementById('recargos1_aux').value.trim() === '' || document.getElementById('recargos1_aux').value.trim() != recargos) {
                document.getElementById('recargos1').value = recargos.toFixed(2);
                document.getElementById('recargos1_aux').value = recargos.toFixed(2);
            }

            // Obtener el valor actual de recargos (ya sea del if o del campo existente)
            recargos = parseFloat(document.getElementById('recargos1').value) || 0;

            console.log('recargos', recargos);

            let total = (recargos + imp).toFixed(2);

            console.log('suma importe + recargos', total);
            document.getElementById('totaldepositado1').value = total;

            let totalrecargos = document.getElementById('recargos1').value;

            let objResRemunera = validaImportes(remuneracion, 9, 2, "remuneracion", MAXIMO_PERMITIDO_REMUNERACION)
            console.log('remuneracion', remuneracion, objResRemunera.success)
            let objResInteres = validaImportes(totalrecargos, 5, 2, "interes", MAXIMO_PERMITIDO_INTERESES)
            console.log('remuneracion', recargos, objResInteres.success)
            let objResTotalDep = validaImportes(total, 7, 2, "totaldepositado", MAXIMO_PERMITIDO_TOTALDEPOSITADO)

            console.log('remuneracion', total, objResTotalDep.success)
            console.log('objResRemunera', objResRemunera)
            console.log('objResInteres', objResInteres)
            console.log('objResTotalDep', objResTotalDep)

            //let lRemuneraciones = validaImportes = (dValor, iMaxCantInt, iMaxCantDec, cLabel, dMaxValPermitido) => {
            // Guardar boleta en sesión y mostrar botón
            var meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio',
                         'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

            let codebaroverflow = (objResInteres.success && objResTotalDep.success ? 0 : 1)

			let data = {
                modulo:                 'concepto',
                action:                 'guardarBoleta',
                csrf_token:             window._CSRF,
                est_nombre:             $('#ctx_est_nombre').text().trim(),
                est_direccion:          $('#ctx_est_detalle').text().trim(),
                des_convenio_banco:     $('#concepto_detalle option:selected').attr('desbanco') ,
                ctabanco:               $('#concepto_detalle option:selected').attr('ctabanco') ,
                codente:                $('#concepto_detalle option:selected').attr('codente') ,
                concepto:               $('#cmbConcepto option:selected').text().trim(),
                concepto_id:            $('#cmbConcepto').val(),
                detalle:                $('#concepto_detalle option:selected').text().trim(),
                detalle_id:             $('#concepto_detalle').val(),
                porcentaje:             $('#lblInfoTipPagoValor').text().trim(),
                periodo_mes:            meses[parseInt($('#cmbMes').val())] || $('#cmbMes').val(),
                periodo_mes_value:      $('#cmbMes').val().padStart(2, '0'),
                periodo_anio:           $('#cmbAnio').val(),
                fec_vencimiento:        window.gFecVencimiento,
                cant_empleados:         $('#cantempleados').val(),
                total_remuneraciones:   Number($('#totalremuneracion1').val()).toFixed(2),
                importe:                importe,
                recargos:               recargos.toFixed(2),
                total:                  (recargos + imp).toFixed(2),
				pv_key:                 $('#concepto_detalle option:selected').attr('pv_key'),
				pb_key:                 $('#concepto_detalle option:selected').attr('pb_key'),
				con_decimales:          (objResRemunera.success && objResInteres.success && objResTotalDep.success ? 1 : 0),
				codebaroverflow:        codebaroverflow,
				
                importe_mp:             Math.round(importe),
                total_remuneraciones_mp: Math.round($('#totalremuneracion1').val()),
                recargos_mp:            Math.round(recargos), //recargos.toFixed(2),
                total_mp:               Math.round(recargos + imp) //(recargos + imp).toFixed(2),
            }

            $.ajax({
                url: window._API, type: 'POST',
                data,   //en ecs6 esto llama a la variable data de arriba
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(r) {
                    console.log('res', r)
                    $.post('helpers/boleta-pay-message-selector.php', {
                            codebaroverflow: codebaroverflow,
                        }, function(html){
                            $('#boleta-payment').html(html).slideDown(300);
                    });
                    console.log('resqqq')
                    
                    if (!r.ok) Swal.fire('Error', 'No se pudieron guardar los datos de la boleta.', 'error');
                    console.log('resqqq')
                },
                error: function(e) { Swal.fire('Error', 'Error al guardar la boleta.', 'error'); console.log('e', e)}
            });
        },
        error: function (error) { 
            console.log('error', error)
            Swal.fire('Error', 'No se pudo calcular el total.', 'error');
        }
    });
}

function abrirAyudaRecargos() {
    var fecvenc = window.gFecVencimiento || $('#fecvencimiento').text().trim();
    var imp     = $('#importe1').val().trim(); 
    if (fecvenc) $('#ar_fecvencimiento').val(fecvenc);
    if (imp)     $('#ar_importecap').val(imp);
    $('#ar_resultado').hide();
    $('#ar_tbody').html('');
    $('#modalAyudaRecargos').modal('show');
}

function calcularRecargos() {
    var fecvenc = $('#ar_fecvencimiento').val().trim();
    var fecpago = $('#ar_fecpagocap').val().trim();
    var importe = $('#ar_importecap').val().trim();

    if (!fecvenc || !fecpago || !importe) {
        Swal.fire({ title: 'Datos incompletos', text: 'Completá los tres campos para calcular.', icon: 'warning', confirmButtonColor: '#4e73df' });
        return;
    }

    $('#ar_spinner').show();
    $('#ar_resultado').hide();

    let objData = { modulo: 'concepto', action: 'calcularTotal', csrf_token: window._CSRF, importe: importe, fecvencimiento: fecvenc, fechapago: fecpago }
    console.log('recargos', objData)
    $.ajax({
        url: window._API, type: 'POST',
        data: objData,
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            $('#ar_spinner').hide();
            if (!res.ok) { Swal.fire('Error', res.mensaje || 'Error al calcular.', 'error'); return; }

            $('#ar_resumen_venc').text(fecvenc);
            $('#ar_resumen_pago').text(fecpago);
            $('#ar_resumen_total').text('$ ' + res.total_recargo);

            var html = '';
            $.each(res.detalle, function (i, d) {
                html +=
                    '<tr>' +
                    '<td class="ar-td ar-td-mono">' + d.fecha_desde + '</td>' +
                    '<td class="ar-td ar-td-mono">' + d.fecha_hasta + '</td>' +
                    '<td class="ar-td text-right">$ ' + d.importe + '</td>' +
                    '<td class="ar-td text-center">' + d.porcentaje + '%</td>' +
                    '<td class="ar-td text-center">' + d.dias + ' días</td>' +
                    '<td class="ar-td text-right" style="font-weight:700;color:#e63946;">$ ' + d.intereses + '</td>' +
                    '</tr>';
            });

            if (!html) html = '<tr><td colspan="6" class="ar-td text-center text-muted">Sin recargos para el período indicado.</td></tr>';

            $('#ar_tbody').html(html);
            $('#ar_resultado').show();
        },
        error: function () {
            $('#ar_spinner').hide();
            Swal.fire('Error', 'No se pudo calcular los recargos.', 'error');
        }
    });
}
</script>