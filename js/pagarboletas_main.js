const metodo = {
    activo: "",
};
document.addEventListener("DOMContentLoaded", async () => {
    //resetAllComponents();

    const menu_buttons = document.querySelectorAll(".menu-btn");
    const modulos = document.querySelectorAll('[id^="metodo_"]');

    menu_buttons.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();

            // resetear botones
            menu_buttons.forEach((b) => {
                b.classList.remove("active");
                b.disabled = false;
            });

            // ocultar TODOS los módulos
            modulos.forEach((m) => m.classList.add("d-none"));

            // activar botón
            btn.classList.add("active");
            btn.disabled = true;

            // mostrar SOLO el seleccionado
            const el = document.querySelector(`#metodo_${btn.dataset.module}`);
            if (el) el.classList.remove("d-none");

            metodo.activo = btn.dataset.module;

            if (metodo.activo != "concepto") {
                document.querySelector("#tipoPagoInfo").style.display = "none";
                document.querySelector("#btn_calcular_importe").style.display = "none";
            } else {
                document.querySelector("#btn_calcular_importe").style.display = "inline";
            }

            resetAllComponents();

            //console.log("Activado:", metodo.activo);
        });
    });

    document.querySelector('[data-module="concepto"]').click();

    // ── Contexto empresa/establecimiento ──
    const contextoData = await postAPI(window._API, window._CSRF, {
        modulo: "concepto",
        action: "contexto",
    });
    if (contextoData.ok && contextoData.data) {
        const d = JSON.parse(contextoData.data).data;
        if (d.empresa_nombre)
            document.querySelector("#ctx_emp_nombre").textContent = d.empresa_nombre;
        if (d.empresa_cuit)
            document.querySelector("#ctx_emp_cuit").textContent =
                "CUIT: " + d.empresa_cuit;

        const nombre = d.razon_social || "—";
        let detalle = [d.calle, d.numero].filter(Boolean).join(" ");
        if (d.seccional_nombre)
            detalle += (detalle ? "  ·  " : "") + "Sec. " + d.seccional_nombre;

        document.querySelector("#ctx_est_nombre").textContent = nombre;
        document.querySelector("#ctx_est_detalle").textContent = detalle || "—";
    }

    // ── Cargar conceptos ──
    const conceptosData = await postAPI(window._API, window._CSRF, {
        modulo: "concepto",
        action: "listar",
    });

    if (conceptosData.ok && conceptosData.data) {
        const fila = JSON.parse(conceptosData.data)
            .data.map((row) => `<option value="${row.id}">${row.concepto}</option>`)
            .join("");

        document.querySelector("#cont_conceptos").innerHTML = `
            <select id="cmbConcepto" class="cv-select" onchange="selConcepto(this)">
                <option required value="0" selected>Seleccionar</option>
                ${fila}
            </select>
        `;
    } else {
        Swal.fire("Error", "No se pudo cargar los conceptos.", "error");
    }

    const select_concepto_detalle = document.getElementById("concepto_detalle");
    if (select_concepto_detalle) {
        select_concepto_detalle.addEventListener("change", function () {
            selDetalle(this);
        });
    }

    const btnCalcularImporte = document.getElementById("btnCalcularImporte");
    if (btnCalcularImporte) {
        btnCalcularImporte.addEventListener("click", function () {
            calcularImporte(1);
        });
    }
});

function selDetalle(o) {
    const sel = $("#concepto_detalle option:selected");
    $("#lblInfoTipPago").text("PORCENTAJE");
    $("#lblInfoTipPagoValor").text(sel.attr("tippagoval"));
    if (metodo.activo == "concepto") {
        $("#tipoPagoInfo").css("display", "flex");
    }
}

async function selPeriodo() {
    const anio = document.getElementById("cmbAnio").value;
    const mes = document.getElementById("cmbMes").value;
    if (anio == 0 || mes == 0) return;

    const concepto = document.getElementById("cmbConcepto")
        ? document.getElementById("cmbConcepto").value
        : "";

    try {
        const res = await postAPI(window._API, window._CSRF, {
            modulo: "concepto",
            action: "vencimiento",
            concepto: concepto,
            anio: anio,
            mes: mes,
        });

        if (!res.ok || !res.data || !res.data[0]) {
            $("#fecvencimiento-wrap").hide();
            window.gFecVencimiento = "";
            return;
        }

        //window.gFecVencimiento = res.data[0];
        //$("#fecvencimiento").text(res.data[0]);
        const resData = JSON.parse(res.data);
        window.gFecVencimiento = resData.vencimiento;
        $("#fecvencimiento").text(resData.vencimiento);
        $("#fecvencimiento-wrap").show();
    } catch (err) {
        console.error(err);
        Swal.fire("Error", "No se pudo obtener el vencimiento.", "error");
    }
}
function calcularTotal(iOpcion) {
    switch (metodo.activo) {
        case "actas":
            return calcularTotalActas(iOpcion);

        case "acuerdos":
            return calcularTotalAcuerdos(iOpcion);

        case "concepto":
            return calcularTotalConcepto(iOpcion);

        default:
            console.warn("Método no definido:", metodo.activo);
    }
}

function calcularTotalActas(iOpcion) {
    var imp      = parseFloat(document.getElementById('importe1').value)  || 0;
    var recargos = parseFloat(document.getElementById('recargos1').value) || 0;

    if (imp === 0) {
        Swal.fire({ title: 'Datos incompletos', text: 'Ingresá el importe antes de calcular.', icon: 'warning', confirmButtonColor: '#4e73df' });
        return;
    }
    
    if( document.getElementById('recargos1').value == '' ) document.getElementById('recargos1').value = 0
    
    var total = imp + recargos;
    document.getElementById('totaldepositado1').value = total.toFixed(2);
    console.log('_cmbConcepto_', $('#cmbConcepto').val())
    console.log('_concepto_detalle_', $('#concepto_detalle').val())
	
	recargos = recargos.toFixed(2)
	
    /********************************************************
     * VALIDACIÓN DE REMUNERACIÓN, INTERÉS Y TOTAL DEPOSITADO
     *******************************************************/
    let objResInteres = validaImportes(document.getElementById('recargos1').value, 5, 2, 'interes', MAXIMO_PERMITIDO_INTERESES)
    let objResTotDep = validaImportes(document.getElementById('totaldepositado1').value, 7, 2, 'total depositado', MAXIMO_PERMITIDO_TOTALDEPOSITADO)
	let codebaroverflow = (objResInteres.success && objResTotDep.success ? 0 : 1)
	
	console.log('objResInteres', objResInteres)
	console.log('objResTotDep', objResTotDep)
	
	if(objResInteres.success && objResTotDep.success){
	    //redondeo de los valores
	    //recargos = recargos.toFixed(0)
	    console.log('redondeo de recargos', recargos)
	}
	
    let import_mp = Math.round(document.getElementById('importe1').value)
    let recargos_mp = Math.round(document.getElementById('recargos1').value)
    let total_mp = import_mp + recargos_mp
	
	let objData = {
			modulo:         'actas',
			action:         'guardarBoleta',       // ← era 'guardarBoleta'
			csrf_token:     window._CSRF,
			est_nombre:     $('#ctx_est_nombre').text().trim(),
			est_direccion:  $('#ctx_est_detalle').text().trim(),
			concepto:       $('#cmbConcepto option:selected').text().trim(),
			detalle:        $('#concepto_detalle option:selected').text().trim(), 
			numero_acta:    $('#numeroacta').val(),
			tipopago:       $('#tipopago option:selected').text().trim(),
			importe:        $('#importe1').val(),
			recargos:       recargos,
			total:          $('#totaldepositado1').val(),
			ctabanco:             $('#concepto_detalle option:selected').attr('ctabanco') ,
			desconvenio:          $('#concepto_detalle option:selected').attr('desbanco') ,
			codente:              $('#concepto_detalle option:selected').attr('codente') ,
			concepto_id:          $('#cmbConcepto').val(),
			detalle:              $('#concepto_detalle option:selected').text().trim(),
			detalle_id:           $('#concepto_detalle').val(),
			pb_key: 			  $('#concepto_detalle option:selected').attr('pb_key'),
			pv_key: 			  $('#concepto_detalle option:selected').attr('pv_key'),
			codebaroverflow:      codebaroverflow,
			
            importe_mp:             import_mp,
            recargos_mp:            recargos_mp,
            total_mp:               total_mp
		}
	console.log(objData)
	//return false;
	
	$.ajax({
		url: window._API, type: 'POST',
		data: objData,
    // ...
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(r) {
			  console.log(r); // ← agregar esto
            if (r.ok){
                $.post('helpers/boleta-pay-message-selector.php', {
                    codebaroverflow: codebaroverflow,
                }, function(html){
                    $('#boleta-payment').html(html).slideDown(300);
                });
                //if (r.ok) $('#btnVerBoleta').slideDown(300);\
                if (!r.ok) Swal.fire('Error', 'No se pudieron guardar los datos de la boleta.', 'error');
                //$('#btnVerBoleta').slideDown(300);
            }else{
                Swal.fire('Error', 'No se pudieron guardar los datos de la boleta.', 'error');
            }
        },
        error: function(e) { Swal.fire('Error', 'Error al guardar el Acta.', 'error'); console.log(e) }
    });
}

function calcularTotalAcuerdos(iOpcion) {
    // Verificar que los elementos existen
    var importeElement = document.getElementById('importe1');
    var recargosElement = document.getElementById('recargos1');
    var totalElement = document.getElementById('totaldepositado1');
    
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
    
    if( document.getElementById('recargos1').value == '' ) document.getElementById('recargos1').value = 0
    
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
    let objResInteres = validaImportes(document.getElementById('recargos1').value, 5, 2, 'interes', MAXIMO_PERMITIDO_INTERESES)
    let objResTotDep = validaImportes(document.getElementById('totaldepositado1').value, 7, 2, 'total depositado', MAXIMO_PERMITIDO_TOTALDEPOSITADO)
	let codebaroverflow = (objResInteres.success && objResTotDep.success ? 0 : 1)

    let import_mp = Math.round(document.getElementById('importe1').value)
    let recargos_mp = Math.round(document.getElementById('recargos1').value)
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
            importe: $('#importe1').val() || '',
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

function calcularTotalConcepto(iOpcion) {
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
    const fecvenc = window.gFecVencimiento || $("#fecvencimiento").text().trim();
    const imp = $("#importe1").val().trim();
    if (fecvenc) $("#ar_fecvencimiento").val(fecvenc);
    if (imp) $("#ar_importecap").val(imp);
    $("#ar_resultado").hide();
    $("#ar_tbody").html("");
    $("#modalAyudaRecargos").modal("show");
}

function calcularRecargos() {
    const fecvenc = $("#ar_fecvencimiento").val().trim();
    const fecpago = $("#ar_fecpagocap").val().trim();
    const importe = $("#ar_importecap").val().trim();

    if (!fecvenc || !fecpago || !importe) {
        Swal.fire({
            title: "Datos incompletos",
            text: "Completá los tres campos para calcular.",
            icon: "warning",
            confirmButtonColor: "#4e73df",
        });
        return;
    }

    $("#ar_spinner").show();
    $("#ar_resultado").hide();

    let dataObj = {
        modulo: "concepto",
        action: "calcularTotal",
        csrf_token: window._CSRF,
        importe: importe,
        fecvencimiento: fecvenc,
        fechapago: fecpago,
    };
    console.log("calcularRecargos", "dataObj", dataObj);
    $.ajax({
        url: window._API,
        type: "POST",
        data: dataObj,
        dataType: "json",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
        success: function (res) {
            $("#ar_spinner").hide();
            if (!res.ok) {
                Swal.fire("Error", res.mensaje || "Error al calcular.", "error");
                return;
            }

            $("#ar_resumen_venc").text(fecvenc);
            $("#ar_resumen_pago").text(fecpago);
            $("#ar_resumen_total").text("$ " + res.total_recargo);
            var html = "";
            $.each(res.detalle, function (i, d) {
                html +=
                    "<tr>" +
                    '<td class="ar-td ar-td-mono">' +
                    d.fecha_desde +
                    "</td>" +
                    '<td class="ar-td ar-td-mono">' +
                    d.fecha_hasta +
                    "</td>" +
                    '<td class="ar-td text-right">$ ' +
                    d.importe +
                    "</td>" +
                    '<td class="ar-td text-center">' +
                    d.porcentaje +
                    "%</td>" +
                    '<td class="ar-td text-center">' +
                    d.dias +
                    " días</td>" +
                    '<td class="ar-td text-right" style="font-weight:700;color:#e63946;">$ ' +
                    d.intereses +
                    "</td>" +
                    "</tr>";
            });

            if (!html)
                html =
                    '<tr><td colspan="6" class="ar-td text-center text-muted">Sin recargos para el período indicado.</td></tr>';

            $("#ar_tbody").html(html);
            $("#ar_resultado").show();
        },
        error: function () {
            $("#ar_spinner").hide();
            Swal.fire("Error", "No se pudo calcular los recargos.", "error");
        },
    });
}

$("#verBoletaCsv").on("click", function (e) {
    e.preventDefault();
    const $btn = $(this);
    $btn
        .css("pointer-events", "none")
        .html('<i class="fas fa-spinner fa-spin mr-1"></i> Generando...');

    $.ajax({
        url: "Generarcsv.php",
        type: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
        complete: function () {
            window.location.href = "visualizarboleta";
        },
    });
});

$("#pagarBoletaCsv").on("click", function (e) {
    e.preventDefault();
    const $btn = $(this);
    $btn
        .css("pointer-events", "none")
        .html('<i class="fas fa-spinner fa-spin mr-1"></i> Generando...');

    $.ajax({
        url: "Generarcsv.php",
        type: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
        complete: function () {
            window.location.href = "visualizarboleta";
        },
    });
});

function selConcepto(o) {
    let valorPago = 0;
    let cuentadet = 0;

    $("#concepto_detalle").html('<option value="">Cargando...</option>');
    if (metodo.activo == "concepto") {
        $("#tipoPagoInfo").hide();
    }

    $.ajax({
        url: window._API,
        type: "POST",
        data: {
            modulo: "concepto",
            action: "detalle",
            concepto: o.value,
            csrf_token: window._CSRF,
        },
        dataType: "json",
        headers: { "X-Requested-With": "XMLHttpRequest" },
        success: function (res) {
            if (!res.ok) {
                $("#concepto_detalle").html(
                    '<option value="">Sin detalles disponibles</option>',
                );
                return;
            }
            if (res.data.length > 1)
                $("#concepto_detalle").html(
                    '<option value="">Seleccione un detalle</option>',
                );
            else $("#concepto_detalle").html("");

            $.each(res.data, function (i, row) {
                cuentadet++;

                if (row.tipCalculo == "P") {
                    valorPago = row.porCalculo;
                }

                $("#concepto_detalle").append(
                    $("<option>", {
                        value: row.codConcBoleta,
                        text: row.desBoleta,
                        tippago: row.tipCalculo,
                        codente: row.codEnte,
                        tippagoval: valorPago,
                        desBanco: row.desBanco,
                        ctabanco: row.ctabanco_des,
                        pb_key: row.public_key,
                        pv_key: row.access_token,
                    }),
                );
            });

            if (cuentadet == 1) {
                selDetalle($("#concepto_detalle"));
            }
        },
        error: function () {
            Swal.fire("Error", "No se pudo cargar el detalle.", "error");
        },
    });
}

function resetAllComponents() {
    
    document.querySelector('#cmbMes').options[0].selected = true;
    document.querySelector('#cmbAnio').options[0].selected = true;
    
    document.querySelector('#numeroacta').value = "";
    document.querySelector('#tipopago').options[0].selected = true;
    
    document.querySelector('#acuerdo').value = "";
    document.querySelector('#tippago').options[0].selected = true;
    document.querySelector('#cuodesde').value = "";
    document.querySelector('#cuohasta').value = "";
    
    document.querySelector('#importe1').value = "";
    document.querySelector('#recargos1').value = "";
    document.querySelector('#totaldepositado1').value = "";

    $("#boleta-payment").hide();
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