<!-- ── N° Acuerdo + Tipo de Pago ── -->
<div class="row g-4 align-items-end">
    <div class="col-md-2">
        <label class="pt-label"><i class="fas fa-file-signature mr-1"></i> Nro Acuerdo</label>
        <input type="text" id="acuerdo" class="pt-input" placeholder="Ej: 1234" autocomplete="off" maxlength="7"> 
    </div>
    <div class="col-md-2">
        <label class="pt-label"><i class="fas fa-exchange-alt mr-1"></i> Tipo de Pago</label>
        <select id="tippago" class="pt-select">
            <option value="total">Total</option>
            <option value="parcial">Parcial</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="pt-label"><i class="fas fa-coins mr-1"></i> cuota desde</label>
        <input type="text" id="cuodesde" class="pt-input" placeholder="Ej: 1234" autocomplete="off" oninput="this.value = this.value.replace(/[^\d]/g, '').slice(0, 2);">  
    </div>
    <div class="col-md-3">
        <label class="pt-label"><i class="fas fa-file-signature mr-1"></i> cuota hasta</label>
        <input type="text" id="cuohasta" class="pt-input"   placeholder="Ej: 1234" autocomplete="off" oninput="this.value = this.value.replace(/[^\d]/g, '').slice(0, 2);">
    </div>
  <div class="col-md-2" style="display:flex; align-items:center; gap:8px;" id="varios">
     <input type="checkbox" id="genbol" value="1">
     <label class="pt-label" style="margin:0;">Genera boleta por cada cuota</label>
  </div>
</div>

<div class="cv-divider"></div>