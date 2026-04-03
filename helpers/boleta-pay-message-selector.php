<?php
$codebaroverflow = $_POST['codebaroverflow'] ?? 0;
?>
<!-- ══════════════════════════════════════
CARD PRINCIPAL — Cálculo de boleta
══════════════════════════════════════ -->
<!-- ── Acción Ver Boleta (aparece tras calcular) ── -->
<div id="btnVerBoleta" class="mt-3" style="display:block;">
    <div style="background:linear-gradient(135deg,#1cc88a,#13855c);border-radius:14px;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
        <div class="d-flex align-items-center gap-3" style="gap:5px;">
            <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:10px;">
                <i class="fas fa-check-circle" style="color:#fff;font-size:1.2rem;"></i>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,0.75);">Cálculo Finalizado</div>
                <?php if (!$codebaroverflow): ?>
                    <div style="font-size:.95rem;font-weight:700;color:#fff;">Ya podés visualizar la boleta generada</div>
                <?php endif; ?>
                <?php if ($codebaroverflow): ?>
                    <div style="font-size:.95rem;font-weight:700;color:#ffda00;">Ésta boleta sólo se podrá pagar por Mercado Pago</div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!$codebaroverflow): ?>
            <a href="#!" class="pt-btn-primary" id="pagarBoletaMPCsv"
               style="background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.4);box-shadow:none;text-decoration:none;">
                <i class="fas fa-file-invoice mr-1"></i>
                <span>Pagar ahora con</span>
                <span>MercadoPago</span>
            </a>
            <a href="#!" class="pt-btn-primary" id="mostrarBoletaCsv"
               style="background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.4);box-shadow:none;text-decoration:none;">
                <i class="fas fa-file-invoice mr-1"></i> Ver Boleta
            </a>
        <?php endif; ?>

        <?php if ($codebaroverflow): ?>
            <a href="#!" class="pt-btn-primary" id="pagarBoletaCsv"
               style="background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.4);box-shadow:none;text-decoration:none;">
                <i class="fas fa-file-invoice mr-1"></i> Pagar Boleta
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- ── Aviso DDJJ ── -->
<div id="msgDDJJlink" class="mt-3" style="display:none;">
    <div style="background:#fff0f3;border:2px solid #ffb3c1;border-radius:12px;padding:1rem 1.25rem;display:flex;align-items:flex-start;gap:.75rem;">
        <i class="fas fa-exclamation-triangle" style="color:#e63946;margin-top:2px;flex-shrink:0;"></i>
        <p style="margin:0;font-size:.88rem;color:#6b1a2a;line-height:1.6;">
            La declaración jurada para el período seleccionado no ha sido ingresada.<br>
            Para poder cargar la DDJJ debe acceder
            <a href="http://www.boletasuthgra.org.ar/cgi-bin/wspd_cgi.sh/WService=wsuthgra/declajurada.htm"
               target="_blank" style="color:#e63946;font-weight:700;">AQUÍ</a>
        </p>
    </div>
</div>
<script>
    $('#mostrarBoletaCsv').on('click', function (e) {
        e.preventDefault();
        const $btn = $(this);
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
    $('#pagarBoletaCsv').on('click', function (e) {
        e.preventDefault();
        const $btn = $(this);
        $btn.css('pointer-events', 'none')
            .html('<i class="fas fa-spinner fa-spin mr-1"></i> Generando...');
        
        $.ajax({
            url: 'Generarcsv.php',
            type: 'POST',
            data: {
                metodo_de_pago: 'mp'
            },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            complete: function () {
                window.location.href = 'visualizarboleta';
            }
        });
    });
    $('#pagarBoletaMPCsv').on('click', function (e) {
        e.preventDefault();
        const $btn = $(this);
        $btn.css('pointer-events', 'none')
            .html('<i class="fas fa-spinner fa-spin mr-1"></i> Generando...');
        $.ajax({
            url: 'Generarcsv.php',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: {
                metodo_de_pago: 'mp'
            },
            complete: function (response) {
                //console.log(response.responseText);
                window.location.href = 'visualizarboleta';
            }
        });
    });
</script>
