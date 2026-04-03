<!-- MODAL -->
<div id="modalEmpleados" style="
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:9999;
">
    <div style="
        background:#fff;
        border-radius:10px;
        width:90%;
        max-width:900px;
        padding:20px;
    ">
        <h3 style="margin-bottom:15px;">Declaración Jurada</h3>

        <!-- TABLA -->
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th style="padding:8px;">Cuil</th>
                    <th style="padding:8px;">Apellido y Nombre</th>
                    <th style="padding:8px;">Afiliado</th>
                    <th style="padding:8px;">Remuneración</th>
                    <th style="padding:8px;">SAC</th>
                    <th style="padding:8px; text-align:center;">
                        <i class="fas fa-screwdriver-wrench"></i>
                    </th>
                </tr>
            </thead>
        
            <tbody id="tablaBody">
                <tr>
                    <td style="padding:6px;">
                        <input type="text" placeholder="30-12345678-9"
                            maxlength="13" autocomplete="off"
                            class="ce-input-simple"
                        >
                    </td>
        
                    <td style="padding:6px;">
                        <input type="text"
                            maxlength="200" autocomplete="off"
                            class="ce-input-simple"
                        >
                    </td>
        
                    <td style="text-align:center;">
                        <input type="checkbox">
                    </td>
        
                    <td style="padding:6px;">
                        <input type="text"
                            placeholder="0.00"
                            autocomplete="off"
                            inputmode="numeric"
                            class="pt-input-prefixed"
                            oninput="this.value = this.value
                                .replace(/[^\d.]/g, '')
                                .replace(/(\..*?)\./g, '$1')
                                .replace(/^\./, '')
                                .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')">
                    </td>
        
                    <td style="padding:6px;">
                        <input type="text"
                            placeholder="0.00"
                            autocomplete="off"
                            inputmode="numeric"
                            class="pt-input-prefixed"
                            oninput="this.value = this.value
                                .replace(/[^\d.]/g, '')
                                .replace(/(\..*?)\./g, '$1')
                                .replace(/^\./, '')
                                .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')">
                    </td>
        
                    <td style="text-align:center;">
                        <div style="display:flex; justify-content:center; gap:8px;">
                            <i class="fas fa-save" style="cursor:pointer;color:green;"></i>
                            <i class="fas fa-edit" style="cursor:pointer;color:#3182ce;"></i>
                            <i class="fas fa-trash" style="cursor:pointer;color:#e53e3e;"></i>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- BOTONES -->
        <div style="margin-top:15px;text-align:right;">
            <button onclick="agregarFila()" style="
                background:#f6e05e;
                border:none;
                padding:8px 14px;
                border-radius:6px;
                cursor:pointer;
                font-weight:bold;
            ">+ Agregar fila</button>

            <button onclick="cerrarModal()" style="
                margin-left:10px;
                background:#e53e3e;
                color:#fff;
                border:none;
                padding:8px 14px;
                border-radius:6px;
                cursor:pointer;
            ">Cerrar</button>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
    document.getElementById("declaracionJuradaBtn")
    .addEventListener("click", function(e) {
        e.preventDefault();
        abrirModal();
    });
    
    function abrirModal() {
        document.getElementById("modalEmpleados").style.display = "flex";
    }
    
    function cerrarModal() {
        document.getElementById("modalEmpleados").style.display = "none";
    }
    
    function agregarFila() {
        const tbody = document.getElementById("tablaBody");
    
        const fila = `
            <tr>
                <td style="padding:6px;">
                    <input type="text" placeholder="30-12345678-9"
                        maxlength="13" autocomplete="off"
                        class="ce-input-simple"
                    >
                </td>
    
                <td style="padding:6px;">
                    <input type="text"
                        maxlength="200" autocomplete="off"
                        class="ce-input-simple"
                    >
                </td>
    
                <td style="text-align:center;">
                    <input type="checkbox">
                </td>
    
                <td style="padding:6px;">
                    <input type="text"
                        placeholder="0.00"
                        autocomplete="off"
                        inputmode="numeric"
                        class="pt-input-prefixed"
                        oninput="this.value = this.value
                            .replace(/[^\d.]/g, '')
                            .replace(/(\..*?)\./g, '$1')
                            .replace(/^\./, '')
                            .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')">
                </td>
    
                <td style="padding:6px;">
                    <input type="text"
                        placeholder="0.00"
                        autocomplete="off"
                        inputmode="numeric"
                        class="pt-input-prefixed"
                        oninput="this.value = this.value
                            .replace(/[^\d.]/g, '')
                            .replace(/(\..*?)\./g, '$1')
                            .replace(/^\./, '')
                            .replace(/^(\d*)(\.\d{0,2})?.*$/, '$1$2')">
                </td>
    
                <td style="text-align:center;">
                    <div style="display:flex; justify-content:center; gap:8px;">
                        <i class="fas fa-save" style="cursor:pointer;color:green;"></i>
                        <i class="fas fa-edit" style="cursor:pointer;color:#3182ce;"></i>
                        <i class="fas fa-trash" style="cursor:pointer;color:#e53e3e;"></i>
                    </div>
                </td>
            </tr>
        `;
    
        tbody.insertAdjacentHTML("beforeend", fila);
    }
</script>
