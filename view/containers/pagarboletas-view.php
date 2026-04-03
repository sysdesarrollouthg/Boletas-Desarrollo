<?php

include  dirname(__DIR__, 2)  . '/css/pagarboletas_css.php';

unset($_SESSION['actas']);
unset($_SESSION['boleta']);
unset($_SESSION['codigo_barra']);

$sesion = [
    'empresa_cuit'   => $_SESSION['empresa_cuit']       ?? '',
    'empresa_id'     => (int) ($_SESSION['empresa_id']  ?? 0),
    'empresa_nombre' => $_SESSION['empresa_nombre']     ?? '',
    'est_cod_est'    => $_SESSION['est_cod_est']        ?? '',
    'est_nombre'     => $_SESSION['est_nombre']         ?? '',
    'est_calle'      => $_SESSION['est_calle']          ?? '',
    'est_numero'     => $_SESSION['est_numero']         ?? '',
    'est_seccional'  => $_SESSION['est_seccional']      ?? '',
];
?>

<div class="cv-wrapper">
    <!--
    ══════════════════════════════════════════════════════
            CARD CONTEXTO — Empresa / Establecimiento
    ══════════════════════════════════════════════════════
    -->

    <div class="card border-0 mb-3"
        style="border-radius:14px;background:linear-gradient(135deg,#4e73df,#224abe);overflow:hidden;">
        <div class="card-body px-5 py-4">
            <div class="row align-items-center g-4">

                <!-- Empresa -->
                <div class="col-md-6 d-flex align-items-center" style="gap:18px;">
                    <div
                        style="width:50px;height:50px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-building" style="color:#fff;font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <div
                            style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,0.65);">
                            Empresa</div>
                        <div id="ctx_emp_nombre" style="font-size:1rem;font-weight:800;color:#fff;line-height:1.2;">
                            <?php echo htmlspecialchars($_SESSION['empresa_nombre'] ?: '—'); ?>
                        </div>
                        <div id="ctx_emp_cuit"
                            style="font-size:.78rem;font-weight:600;color:rgba(255,255,255,0.75);margin-top:2px;">
                            CUIT:
                            <?php echo htmlspecialchars($_SESSION['empresa_cuit'] ?: '—'); ?>
                        </div>
                    </div>
                </div>

                <!-- Establecimiento -->
                <div class="col-md-6 d-flex align-items-center" id="head_est" style="gap:18px;">
                    <div
                        style="width:50px;height:50px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-store" style="color:#fff;font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <div
                            style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,0.65);">
                            Establecimiento</div>
                        <div id="ctx_est_nombre" style="font-size:1rem;font-weight:800;color:#fff;line-height:1.2;">
                            <?php echo htmlspecialchars($_SESSION['est_razon_social'] ?: '—'); ?>
                        </div>
                        <div id="ctx_est_detalle"
                            style="font-size:.78rem;font-weight:600;color:rgba(255,255,255,0.75);margin-top:2px;">
                            <?php
                            $dir = trim(($_SESSION['est_calle'] ?? '') . ' ' . ($_SESSION['est_numero'] ?? ''));
                            echo htmlspecialchars($dir ?: '—');
                            if ($_SESSION['est_seccional']) echo ' &nbsp;·&nbsp; Sec. ' . htmlspecialchars($_SESSION['est_seccional']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--
        ══════════════════════════════════════
                SELECTOR TIPO DE PAGO
        ══════════════════════════════════════
    -->
    <div class="mb-3 mt-2 selector_btns"
        style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:10px 14px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
        <span
            style="font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.09em;color:#a0aec0;margin-right:4px;white-space:nowrap;">Tipo
            de pago</span>

        <!-- Conceptos -->
        <button type="button" class="menu-btn" data-module="concepto">
            <i class="fas fa-layer-group"></i> Pago Período
        </button>

        <button type="button" class="menu-btn" data-module="actas">
            <i class="fas fa-file-signature"></i> Actas
        </button>

        <button type="button" class="menu-btn" data-module="acuerdos">
            <i class="fas fa-handshake"></i> Acuerdos
        </button>

        <!-- Cambiar establecimiento -->
        <a href="establecimiento" id="cambiarEstablecimientoBtn" style="display:inline-flex;align-items:center;gap:7px;margin-left:auto;
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

    <div class="card shadow-sm border-0 mb-2" style="border-radius:16px; overflow:hidden;">
        <div class="card-body">
            <!-- ── Concepto + Detalle + Porcentaje en una línea ── -->
            <div class="row g-4 align-items-end mb-1">
                <div class="col-md-4">
                    <label class="cv-label"><i class="fas fa-layer-group mr-1"></i> Concepto</label>
                    <div id="cont_conceptos"></div>
                </div>
                <div class="col-md-4">
                    <label class="cv-label"><i class="fas fa-list-alt mr-1"></i> Detalle</label>
                    <select class="cv-select" id="concepto_detalle">
                        <option value="">Seleccione un concepto primero</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div id="tipoPagoInfo" style="display:none; flex-direction:column;">
                        <label class="cv-label"><i class="fas fa-percent mr-1"></i> Porcentaje</label>
                        <div
                            style="height:44px;background:#f0f4ff;border:2px solid #c7d4f7;border-radius:10px;display:flex;align-items:center;gap:10px;padding:0 14px;">
                            <div
                                style="width:28px;height:28px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-percent" style="color:#fff;font-size:.7rem;"></i>
                            </div>
                            <span id="lblInfoTipPagoValor"
                                style="font-size:1.05rem;font-weight:800;color:#4e73df;"></span>
                            <span id="lblInfoTipPago" style="display:none;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--
        ══════════════════════════════════════════
            CARD PRINCIPAL — Cálculo de boleta
        ══════════════════════════════════════════
    -->

    <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
        <div style="height:4px; background:linear-gradient(135deg,#4e73df,#224abe);"></div>
        <div class="card-body p-5">
            <div class="d-none" id="metodo_concepto">
                <?php
                    include dirname(__DIR__, 1).'/modules/metodo_concepto.php';
                    ?>
            </div>
            <div class="d-none" id="metodo_actas">
                <?php
                    include dirname(__DIR__, 1).'/modules/metodo_actas.php';
                    ?>
            </div>
            <div class="d-none" id="metodo_acuerdos">
                <?php
                    include dirname(__DIR__, 1).'/modules/metodo_acuerdos.php';
                    ?>
            </div>

            <!-- ── Importes ── -->
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="pt-label"><i class="fas fa-coins mr-1"></i> Importe</label>
                    <div class="pt-prefix-wrap">
                        <span class="pt-prefix">$</span>
                        <input type="text" id="importe1" class="pt-input-prefixed" placeholder="0.00" autocomplete="off"
                            inputmode="numeric" oninput="this.value = this.value
                                    .replace(/[^\d.]/g, '')
                                    .replace(/(\..*?)\./g, '$1')
                                    .replace(/^\./, '')
                                    .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" onclick="calcularImporte(1)" class="pt-btn-outline" id="btn_calcular_importe">
                        <i class="fas fa-calculator mr-1"></i> Calcular Importe
                    </button>
                </div>
                <div class="col-md-4">
                    <label class="pt-label"><i class="fas fa-percentage mr-1"></i> Recargos</label>
                    <div class="pt-prefix-wrap">
                        <span class="pt-prefix">$</span>
                        <input type="text" id="recargos1" class="pt-input-prefixed" placeholder="0.00"
                            autocomplete="off" inputmode="numeric" oninput="this.value = this.value
                                    .replace(/[^\d.]/g, '')
                                    .replace(/(\..*?)\./g, '$1')
                                    .replace(/^\./, '')
                                    .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="ayudarecargos1" onclick="abrirAyudaRecargos()"
                        class="pt-btn-outline pt-btn-gray w-100" style="height:40px;font-size:.78rem;">
                        <i class="fas fa-table mr-1"></i> Ayuda Recargos
                    </button>
                </div>

                <div class="col-12" style="margin-top:25px;">
                    <div
                        style="background:linear-gradient(135deg,#f0f4ff,#e8f0fe);border:2px solid #c7d4f7;border-radius:14px;padding:1.1rem 1.25rem;">

                        <label class="pt-label" style="color:#4e73df;">
                            <i class="fas fa-check-circle mr-1"></i> Total Depositado
                        </label>

                        <div class="row align-items-center" id="btnCalcularTotal">

                            <div class="col-md-9">
                                <div class="pt-prefix-wrap" style="width:100%;">
                                    <span class="pt-prefix"
                                        style="background:#4e73df;color:#fff;border-right-color:#4e73df;">$</span>
                                    <input type="text" id="totaldepositado1" class="pt-input-prefixed" placeholder="00"
                                        inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^\d]/g, '').slice(0,13)"
                                        style="font-size:1.1rem;font-weight:800;color:#2d3748;background:transparent;width:100%;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="button" onclick="calcularTotal(1)"
                                    class="pt-btn-primary w-100 d-flex justify-content-center align-items-center">
                                    <i class="fas fa-calculator mr-1"></i> Calcular Total
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div id="boleta-payment" style="display:none"></div>
        </div>
    </div>
</div>
</div>

<!--
    ════════════════════════════════════
                MODAL — Ayuda Recargos
    ════════════════════════════════════
-->

<div class="modal fade" id="modalAyudaRecargos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.15);">

            <div class="modal-header"
                style="background:linear-gradient(135deg,#4e73df,#224abe);border-radius:16px 16px 0 0;border:none;padding:1.25rem 1.75rem;">
                <div class="d-flex align-items-center gap-3">
                    <div
                        style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-table" style="color:#fff;font-size:1rem;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="color:#fff;font-weight:700;">Detalle de Intereses Resarcitorios
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal"
                    style="color:#fff;opacity:.8;font-size:1.5rem;">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="pt-label"><i class="fas fa-calendar-times mr-1"></i> Fecha de Vencimiento</label>
                        <input type="text" id="ar_fecvencimiento" class="pt-input" placeholder="DD/MM/YYYY"
                            autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label class="pt-label"><i class="fas fa-calendar-check mr-1"></i> Fecha de Pago</label>
                        <input type="text" id="ar_fecpagocap" class="pt-input" placeholder="DD/MM/YYYY"
                            autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label class="pt-label"><i class="fas fa-coins mr-1"></i> Importe Capital</label>
                        <div class="pt-prefix-wrap">
                            <span class="pt-prefix">$</span>
                            <input type="text" id="ar_importecap" class="pt-input-prefixed" placeholder="0.00"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="button" onclick="calcularRecargos()" class="pt-btn-primary">
                            <i class="fas fa-calculator mr-1"></i> Calcular Intereses
                        </button>
                    </div>
                </div>
                <div id="ar_resultado" style="display:none;">
                    <div
                        style="background:#f0f4ff;border-radius:10px;padding:.75rem 1rem;margin-bottom:1rem;display:flex;gap:2rem;flex-wrap:wrap;">
                        <div>
                            <span
                                style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#718096;">Fecha
                                Vencimiento</span>
                            <div style="font-weight:700;color:#2d3748;" id="ar_resumen_venc"></div>
                        </div>
                        <div>
                            <span
                                style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#718096;">Fecha
                                Pago</span>
                            <div style="font-weight:700;color:#2d3748;" id="ar_resumen_pago"></div>
                        </div>
                        <div>
                            <span
                                style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#718096;">Total
                                Intereses</span>
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
            <div class="modal-footer"
                style="border-top:1px solid #e2e8f0;padding:1rem 1.75rem;border-radius:0 0 16px 16px;">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"
                    style="border-radius:10px;font-weight:600;">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<script defer>
    const CSRF_TOKEN = "<?= csrf_token() ?>";
    const API_URL = "<?= SERVERURL ?>api.php";
    window._CSRF = CSRF_TOKEN;
    window._API = API_URL;
</script>
<script defer src="../js/functions.js"></script>
<script defer src="../js/pagarboletas_main.js"></script>