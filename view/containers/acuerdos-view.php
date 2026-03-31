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
    border-radius:10px; padding:0 14px; height:47px; font-size:.8rem;
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

#ayudarecargos1{
    white-space: nowrap;
}

/* texto para pantallas medianas o grandes */
.text-recargos-lg{
    font-size: clamp(0.7rem, 0.9vw, 0.85rem);
}

/* texto para móviles */
.text-recargos-sm{
    font-size: clamp(0.65rem, 2.5vw, 0.8rem);
}
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
                <div class="col-md-6 d-flex align-items-center" style="gap:18px;" id="head_est">
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

                       .container, .container-fluid, .container-lg, .container-md, .container-sm, .container-xl {
                        padding-left: 0rem !important;
                        padding-right: 0rem !important;
          }
                        .p-5{
                            padding: 1.5rem !important;
                        }

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

    <!-- Conceptos -->
    <a href="concepto"
        style="display:inline-flex;align-items:center;gap:7px;background:#f1f3f8;color:#718096;border:2px solid #e2e8f0;border-radius:9px;padding:0 16px;height:36px;font-size:.82rem;font-weight:700;cursor:pointer;text-decoration:none;transition:background .2s,color .2s;"
        onmouseover="this.style.background='#e2e8f0';this.style.color='#4a5568';"
        onmouseout="this.style.background='#f1f3f8';this.style.color='#718096';">
        <i class="fas fa-layer-group" style="font-size:.8rem;"></i> Conceptos
    </a>

    <!-- Acuerdos -->
    <a href="actas"
        style="display:inline-flex;align-items:center;gap:7px;background:#f1f3f8;color:#718096;border:2px solid #e2e8f0;border-radius:9px;padding:0 16px;height:36px;font-size:.82rem;font-weight:700;cursor:pointer;text-decoration:none;transition:background .2s,color .2s;"
        onmouseover="this.style.background='#e2e8f0';this.style.color='#4a5568';"
        onmouseout="this.style.background='#f1f3f8';this.style.color='#718096';">
        <i class="fas fa-handshake" style="font-size:.8rem;"></i> Actas
    </a>

     <!-- Acuerdos — ACTIVO -->
    <button type="button" disabled
        style="display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#4e73df,#224abe);color:#fff;border:none;border-radius:9px;padding:0 16px;height:36px;font-size:.82rem;font-weight:700;cursor:default;box-shadow:0 3px 10px rgba(78,115,223,0.3);">
        <i class="fas fa-file-signature" style="font-size:.8rem;"></i> Acuerdos
    </button>

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
                <div class="col-md-6">
                    <label class="cv-label"><i class="fas fa-layer-group mr-1"></i> Concepto</label>
                    <div id="cont_conceptos"></div>
                </div>
                <div class="col-md-6">
                    <label class="cv-label"><i class="fas fa-list-alt mr-1"></i> Detalle</label>
                    <select class="cv-select" id="concepto_detalle" onchange="selDetalle(this)">
                        <option value="">Seleccione un concepto primero</option>
                    </select>
                </div>
              
            </div>

            <div class="cv-divider"></div>

            <!-- ── N° Acuerdo + Tipo de Pago ── -->
            <div class="row g-4 align-items-end">
                <div class="col-md-10">
                   
                    <label class="pt-label"><i class="fas fa-file-signature mr-1"></i> Nro Acuerdo</label>
                    <input type="text" id="acuerdo" class="pt-input" placeholder="Ej: 1234" autocomplete="off" maxlength="7"> 
               
                </div>
                <!--
                <div class="col-md-5">
                    <label class="pt-label"><i class="fas fa-file-signature mr-1"></i> N° Acta</label>
                    <input type="text" id="nroacta" class="pt-input" placeholder="Ej: 1234" autocomplete="off">
               
                </div>
                -->
                <div class="col-md-2">
                    <label class="pt-label"><i class="fas fa-exchange-alt mr-1"></i> Tipo de Pago</label>
                    <select id="tippago" class="pt-select">
                        <option value="total">Total</option>
                        <option value="parcial">Parcial</option>
                    </select>
                </div>
            </div>

            <div class="cv-divider"></div>

            <!-- ── N° Acta + Tipo de Pago ── -->
            <div class="row g-4 align-items-end">
                <div class="col-md-5">
                    <label class="pt-label"><i class="fas fa-coins mr-1"></i> cuota desde</label>
                    <input type="text" id="cuodesde" class="pt-input" placeholder="Ej: 1234" autocomplete="off" oninput="this.value = this.value.replace(/[^\d]/g, '').slice(0, 2);">  
                </div>

                <div class="col-md-6">
                    <label class="pt-label"><i class="fas fa-file-signature mr-1"></i> cuota hasta</label>
                    <input type="text" id="cuohasta" class="pt-input"   placeholder="Ej: 1234" autocomplete="off" oninput="this.value = this.value.replace(/[^\d]/g, '').slice(0, 2);">
                </div>

              <div class="col-md-6" style="display:flex; align-items:center; gap:8px;" id="varios">
                 <input type="checkbox" id="genbol" value="1">
                 <label class="pt-label" style="margin:0;">Genera boleta por cada cuota</label>
              </div>
                        </div>

            <div class="cv-divider"></div>

            <!-- ── Importes ── -->
            <div class="row g-3 align-items-end">

                <div class="col-md-5">
                    <label class="pt-label"><i class="fas fa-coins mr-1"></i> Importe</label>
                    <div class="pt-prefix-wrap">
                        <span class="pt-prefix">$</span>
                        <input type="text" id="importe" class="pt-input-prefixed" placeholder="0.00" autocomplete="off" inputmode="decimal" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^(\d*\.\d{2})\d+$/, '$1')" style="background:#f8f9fc;color:#718096;"   style="background:#f8f9fc;color:#718096;">
                    </div>
                </div>

                <div class="col-md-5">
                    <label class="pt-label"><i class="fas fa-percentage mr-1"></i> Recargos</label>
                    <div class="pt-prefix-wrap">
                        <span class="pt-prefix">$</span>
                      <input type="text" id="recargos" class="pt-input-prefixed" placeholder="0.00" autocomplete="off" inputmode="decimal" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^(\d*\.\d{2})\d+$/, '$1')" style="background:#f8f9fc;color:#718096;"   style="background:#f8f9fc;color:#718096;">
                    </div>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="ayudarecargos1"
                            onclick="abrirAyudaRecargos()"
                            class="pt-btn-outline pt-btn-gray w-100">
                        <i class="fas fa-table mr-1"></i>
                        <span class="text-recargos-lg d-none d-md-inline">Ayuda Recargos</span>
                        <span class="text-recargos-sm d-md-none">Recargos</span>
                    </button>
                </div>

                <div class="col-12" style="margin-top:25px;">
                    <div style="background:linear-gradient(135deg,#f0f4ff,#e8f0fe);border:2px solid #c7d4f7;border-radius:14px;padding:1.1rem 1.25rem;">
                        <label class="pt-label" style="color:#4e73df;">
                            <i class="fas fa-check-circle mr-1"></i> Total Depositado
                        </label>
                        <div class="d-flex gap-2 align-items-center" id="btnCalcularTotal">
                            <div class="pt-prefix-wrap" style="flex:1;">
                                <span class="pt-prefix" style="background:#4e73df;color:#fff;border-right-color:#4e73df;">$</span>
                                <input type="text" id="totaldepositado" class="pt-input-prefixed" placeholder="0.00" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       style="font-size:1.1rem;font-weight:800;color:#2d3748;background:transparent;">
                            </div>
                            <button type="button" onclick="calcularTotal(1)" class="pt-btn-primary">
                                <i class="fas fa-calculator mr-1"></i> Calcular Total
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ── Acción Ver Boleta (aparece tras calcular) ── -->
            <!--
            <div id="btnVerBoleta" class="mt-3" style="display:none;">
                <div style="background:linear-gradient(135deg,#1cc88a,#13855c);border-radius:14px;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-check-circle" style="color:#fff;font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,0.75);">Cálculo completado</div>
                            <div style="font-size:.95rem;font-weight:700;color:#fff;">Ya podés visualizar la boleta generada</div>
                        </div>
                    </div>
                    <a href="?views=visualizarboleta" class="pt-btn-primary" id="verBoletaCsv"
                       style="background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.4);box-shadow:none;text-decoration:none;">
                        <i class="fas fa-file-invoice mr-1"></i> Ver Boleta
                    </a>
                </div>
            </div>
            -->
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
<script src="../js/functions.js"></script>
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
                if(res.data.length > 1 && i == 0)
                    fila += '<option value="">Seleccione un concepto</option>';
                
                fila += '<option value="' + row.id + '">' + row.concepto + '</option>';
            });
            $('#cont_conceptos').html('<select id="cmbConcepto" class="cv-select" onchange="selConcepto(this)">' + fila + '</select>');
        
        },
        error: function () { Swal.fire('Error', 'No se pudo cargar los conceptos.', 'error'); }
    });

});

function selConcepto(o) {
    $('#concepto_detalle').html('<option value="">Cargando...</option>');
    let valorPago = 0

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
        },
        error: function () { Swal.fire('Error', 'No se pudo cargar el detalle.', 'error'); }
    });
}

function selDetalle(o) {
    var sel = $('#concepto_detalle option:selected');
    $('#lblInfoTipPago').text('PORCENTAJE');
    $('#lblInfoTipPagoValor').text(sel.attr('tippagoval'));
   
}

function selPeriodo() {
    var anio = document.getElementById('cmbAnio').value;
    var mes  = document.getElementById('cmbMes').value;
    if (anio == 0 || mes == 0) return;

    var concepto = document.getElementById('cmbConcepto') ? document.getElementById('cmbConcepto').value : '';

    $.ajax({
        url: window._API, type: 'POST',
        data: { modulo: 'concepto', action: 'vencimiento', csrf_token: window._CSRF, concepto: concepto, anio: anio, mes: mes },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            if (!res.ok || !res.data[0]) {
                $('#fecvencimiento-wrap').hide();
                window.gFecVencimiento = '';
                return;
            }
            window.gFecVencimiento = res.data[0];
            $('#fecvencimiento').text(res.data[0]);
            $('#fecvencimiento-wrap').show();
        },
        error: function () { Swal.fire('Error', 'No se pudo obtener el vencimiento.', 'error'); }
    });
}


function calcularTotal(iOpcion) {
    // Verificar que los elementos existen
    var importeElement = document.getElementById('importe');
    var recargosElement = document.getElementById('recargos');
    var totalElement = document.getElementById('totaldepositado');
    
    if (!importeElement || !recargosElement || !totalElement) {
        console.error('Elementos no encontrados:', {
            importe1: !!importeElement,
            recargos1: !!recargosElement,
            totaldepositado: !!totalElement
        });
        Swal.fire({ 
            title: 'Error', 
            text: 'No se encontraron los campos necesarios', 
            icon: 'error', 
            confirmButtonColor: '#4e73df' 
        });
        return;
    }

    var imp = parseFloat(importeElement.value) || 0;
    var recargos = parseFloat(recargosElement.value) || 0;
    
    if( document.getElementById('recargos').value == '' ) document.getElementById('recargos').value = 0
    
    if (imp === 0) {
        Swal.fire({ 
            title: 'Datos incompletos', 
            text: 'Ingresá el importe antes de calcular.', 
            icon: 'warning', 
            confirmButtonColor: '#4e73df' 
        });
        return;
    }
	
	var valTotal = parseFloat(imp + recargos)
	recargos = recargos.toFixed(2)
	if (!isNaN(valTotal)) {
		valTotal.value = valTotal.toFixed(2);
	} else {
		valTotal.value = '0.00'; // Valor por defecto si no es válido
	}
	console.log(valTotal.toFixed(2))
    //var total = imp + recargos;
    totalElement.value = valTotal;

    /********************************************************
     * VALIDACIÓN DE REMUNERACIÓN, INTERÉS Y TOTAL DEPOSITADO
     *******************************************************/
    let objResInteres = validaImportes(document.getElementById('recargos').value, 5, 2, 'interes', MAXIMO_PERMITIDO_INTERESES)
    let objResTotDep = validaImportes(document.getElementById('totaldepositado').value, 7, 2, 'total depositado', MAXIMO_PERMITIDO_TOTALDEPOSITADO)
	let codebaroverflow = (objResInteres.success && objResTotDep.success ? 0 : 1)

    let import_mp = Math.round(document.getElementById('importe').value)
    let recargos_mp = Math.round(document.getElementById('recargos').value)
    let total_mp = import_mp + recargos_mp

    // Verificar que los elementos jQuery existen antes de usarlos
    if ($('#concepto_detalle option:selected').length === 0) {
        console.error('Elemento concepto_detalle no encontrado o sin opciones');
        Swal.fire('Error', 'Error en los datos del formulario', 'error');
        return;
    }
	
	let objData = {
            modulo: 'acuerdos',
            action: 'guardarBoleta',
            csrf_token: window._CSRF,
            empresa_nombre: $('#ctx_emp_nombre').text().trim(),
            empresa_cuit: $('#ctx_emp_cuit').text().replace('CUIT:', '').trim(),
            est_nombre: $('#ctx_est_nombre').text().trim(),
            est_direccion: $('#ctx_est_detalle').text().trim(),
            ctabanco: $('#concepto_detalle option:selected').attr('ctabanco') || '',
            desconvenio: $('#concepto_detalle option:selected').attr('desbanco') || '',
            codente: $('#concepto_detalle option:selected').attr('codente') || '',
            concepto: $('#cmbConcepto option:selected').text().trim(),
            concepto_id: $('#cmbConcepto').val() || '',
            detalle: $('#concepto_detalle option:selected').text().trim(),
            detalle_id: $('#concepto_detalle').val() || '',
            numero_acuerdo: $('#acuerdo').val() || '',
            numero_acta: $('#nroacta').val() || '',
            tipopago: $('#tippago option:selected').text().trim(),
            cuota_desde: $('#cuodesde').val() || '',
            cuota_hasta: $('#cuohasta').val() || '',
            gen_bol_x_cuota: $('#genbol').is(':checked') ? 1 : 0,
            importe: $('#importe').val() || '',
            recargos: recargos || '',
            total: valTotal.toFixed(2) || '', //$('#totaldepositado').val() || '',
			pb_key: $('#concepto_detalle option:selected').attr('pb_key'), //row.public_key,
			pv_key: $('#concepto_detalle option:selected').attr('pv_key'), //row.access_token
			
			codebaroverflow: codebaroverflow,
			
            importe_mp:             import_mp,
            recargos_mp:            recargos_mp,
            total_mp:               total_mp
        }
	console.log(objData)
	//return false;

    $.ajax({
        url: window._API, 
        type: 'POST',
        data: objData,
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(r) {
            if (r && r.ok) {
                //$('#btnVerBoleta').slideDown(300);
                
                $.post('helpers/boleta-pay-message-selector.php', {
                    codebaroverflow: codebaroverflow,
                }, function(html){
                    $('#boleta-payment').html(html).slideDown(300);
                });
                
            } else {
                Swal.fire('Error', 'No se pudieron guardar los datos de la boleta.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', error);
            Swal.fire('Error', 'Error al guardar el Acta.', 'error');
        }
    });
}

function guardarBoletaAcuerdos(imp, recargos) {
    try{
		let objData = {
				modulo:        'acuerdos',
				action:        'guardarBoleta',
				csrf_token:    window._CSRF,
				empresa_nombre: $('#ctx_emp_nombre').text().trim(),
				empresa_cuit:  $('#ctx_emp_cuit').text().replace('CUIT:','').trim(),
				est_nombre:    $('#ctx_est_nombre').text().trim(),
				est_direccion: $('#ctx_est_detalle').text().trim(),
				est_seccional: '',
				concepto:      $('#cmbConcepto option:selected').text().trim(),
				detalle:       $('#concepto_detalle option:selected').text().trim(),
				numero_acta:   $('#numeroacta').val(),
				tipopago:      $('#tipopago option:selected').text().trim(),
				importe:       imp.toFixed(2),
				recargos:      recargos.toFixed(2),
				total:         (imp + recargos).toFixed(2)
			}
		
		$.ajax({
			url: window._API, type: 'POST',
			data: objData,
			dataType: 'json',
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			success: function(r) {
				if (r.ok) $('#btnVerBoleta').slideDown(300);
				else Swal.fire('Error', 'No se pudieron guardar los datos de la boleta.', 'error');
			},
			error: function() { Swal.fire('Error', 'Error al guardar el Acuerdo.', 'error'); }
		});

	}catch(e){
		console.log(e)
	}
}

function abrirAyudaRecargos() {
    var fecvenc = window.gFecVencimiento || $('#fecvencimiento').text().trim();
    var imp     = $('#importe').val().trim();
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

    $.ajax({
        url: window._API, type: 'POST',
        data: { modulo: 'concepto', action: 'calcularTotal', csrf_token: window._CSRF, importe: importe, fecvencimiento: fecvenc, fechapago: fecpago },
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


// activa y desact. varios
$(document).on('change', '#tippago', function () {
    if ($(this).val() === 'total') {
        $('#varios').css('display', 'flex');
        $('#genbol').prop('checked', false);
    } else {
        $('#varios').css('display', 'none');
        $('#genbol').prop('checked', false);
    }
});

// Estado inicial
if ($('#tippago').val() !== 'total') {
    $('#varios').css('display', 'none');
    $('#genbol').prop('checked', false);
}


$('#verBoletaCsv').on('click', function (e) {
    e.preventDefault();
    var $btn = $(this);
    $btn.css('pointer-events', 'none')
        .html('<i class="fas fa-spinner fa-spin mr-1"></i> Generando...');
    
    $.ajax({
        url: 'Generarcsv.php',
        type: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        complete: function () {
            window.location.href = 'visualizarboleta';
        }
    });
});
</script>