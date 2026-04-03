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

<div class="row g-4 align-items-end">
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
        <input type="hidden" id="recargos1_aux">
    </div>
</div>

<div class="cv-divider"></div>