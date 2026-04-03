<!-- ── N° Acta + Tipo de Pago ── -->
<div class="row g-4 align-items-end">
    <div class="col-md-6">
        <label class="pt-label"><i class="fas fa-file-signature mr-1"></i> N° Acta</label>
        <input type="text" id="numeroacta" class="pt-input" placeholder="Ej: 1234" autocomplete="off" oninput="this.value = this.value.replace(/[.,]/g, '').slice(0,12)">
   
    </div>
    <div class="col-md-6">
        <label class="pt-label"><i class="fas fa-exchange-alt mr-1"></i> Tipo de Pago</label>
        <select id="tipopago" class="pt-select">
            <option value="total">Total</option>
            <option value="parcial">Parcial</option>
        </select>
    </div>
</div>

<div class="cv-divider"></div>