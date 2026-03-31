<?php
/*echo "<pre>";
print_r($_GET);
echo "</pre>";

echo $_GET['tipopago'];*/

// Redirigir si no hay boleta en sesión
if (empty($_SESSION['boleta'])) {
    echo '<script>window.location.href = "' . SERVERURL . 'consulta";</script>';
    exit;
}

$preferenceId = include './genbotonmp.php';

if (empty($preferenceId)) {
    echo '<div class="alert alert-danger">No se pudo generar el botón de pago. Intente nuevamente.</div>';
}

$b = $_SESSION['boleta'];
/*
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
die();
*/
?>
<script>
    function pagarMp() {
        const walletBtn = document.querySelector("#wallet_container button");

        if (!walletBtn) {
            console.error("Botón de MercadoPago no disponible");
            return;
        }

        walletBtn.click();
    };
</script>
<style>
    .vb-wrapper {
        max-width: 100%;
        margin: 0;
        padding: 0.5rem 1.5rem 2rem;
    }

    .vb-topbar {
        background: linear-gradient(135deg, #4e73df, #224abe);
        border-radius: 14px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .vb-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .vb-icon {
        width: 46px;
        height: 46px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .vb-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        border-radius: 10px;
        padding: 0 18px;
        height: 40px;
        font-size: .85rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: opacity .2s;
    }

    .vb-btn-white {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.35);
    }

    .vb-btn-white:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #fff;
        text-decoration: none;
    }

    .vb-btn-back {
        background: #fff;
        color: #4e73df;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .vb-btn-back:hover {
        background: #f0f4ff;
        color: #4e73df;
        text-decoration: none;
    }

    .vb-iframe-wrap {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    @media (max-width: 800px) {

        .cv-wrapper {
            padding: 0px 0px 10px 0px !important;
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

        .p-5 {
            padding: 1.5rem !important;
        }

    }

    /* ── Variante 1: Azul MP clásico con shimmer ── */
    .btn-mp {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: #009ee3;
        color: #fff;
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: .02em;
        padding: 0 28px 0 20px;
        height: 52px;
        border-radius: 14px;
        border: none;
        cursor: pointer;
        overflow: hidden;
        box-shadow:
            0 4px 15px rgba(0, 158, 227, 0.45),
            0 1px 3px rgba(0, 0, 0, 0.2);
        transition: transform .18s ease, box-shadow .18s ease;
        text-decoration: none;
    }

    .btn-mp::before {
        content: '';
        position: absolute;
        top: 0;
        left: -75%;
        width: 50%;
        height: 100%;
        background: linear-gradient(120deg,
                transparent 0%,
                rgba(255, 255, 255, 0.35) 50%,
                transparent 100%);
        animation: shimmer 2.8s infinite;
    }

    @keyframes shimmer {
        0% {
            left: -75%;
        }

        60% {
            left: 130%;
        }

        100% {
            left: 130%;
        }
    }

    .btn-mp:hover {
        transform: translateY(-2px);
        box-shadow:
            0 8px 24px rgba(0, 158, 227, 0.55),
            0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .btn-mp:active {
        transform: translateY(0px);
        box-shadow: 0 3px 10px rgba(0, 158, 227, 0.35);
    }

    .btn-mp .mp-logo-wrap {
        background: #fff;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .btn-mp .mp-logo-wrap img {
        height: 33px;
        width: auto;
        display: block;
    }

    .btn-mp .mp-text {
        display: flex;
        flex-direction: column;
        line-height: 1.15;
        text-align: left;
    }

    .btn-mp .mp-text span:first-child {
        font-size: .68rem;
        font-weight: 500;
        opacity: .85;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .btn-mp .mp-text span:last-child {
        font-size: 1rem;
        font-weight: 800;
    }
</style>

<div class="d-none">
    <div id="wallet_container"></div>
</div>

<div class="vb-wrapper">

    <!-- Titulo -->
    <!-- 
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:42px;height:42px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(78,115,223,0.35);">
                    <i class="fas fa-file-invoice-dollar" style="color:#fff;font-size:1.1rem;"></i>
                </div>
                <div>
                    <div style="font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#a0aec0;line-height:1;">Impresión de Boletas</div>
                    <h1 style="margin:0;font-size:1.6rem;font-weight:800;color:#2d3748;line-height:1.2;"><?php echo $b['titulo']; ?></span></h1>
                </div>
            </div>
        </div>
    </div>
    
    
    -->

    <!-- Barra superior -->
    <div class="vb-topbar">
        <div class="vb-info">
            <div class="vb-icon">
                <i class="fas fa-file-pdf" style="color:#fff;font-size:1.2rem;"></i>
            </div>
            <div>
                <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,0.65);">Boleta generada</div>
                <div style="font-size:1rem;font-weight:800;color:#fff;line-height:1.2;">
                    <?php echo htmlspecialchars($b['concepto']); ?> — <?php echo htmlspecialchars($b['detalle']); ?>
                </div>
                <div style="font-size:.78rem;color:rgba(255,255,255,0.75);margin-top:2px;">
                    <?php echo htmlspecialchars($b['empresa_nombre']); ?> &nbsp;·&nbsp;
                    <?php echo htmlspecialchars($b['periodo_mes'] . ' ' . $b['periodo_anio']); ?> &nbsp;·&nbsp;
                    Vto: <?php echo htmlspecialchars($b['fec_vencimiento']); ?>
                </div>
            </div>
        </div>
        <!--**************************************RED LINK************************************-->
        <div>
            <div onclick="pagoredlink();" style="background: #89f051; padding: 10px; border-radius: 90px; color: #949692; font-weight: bold; cursor: pointer;">
                <span>REDLINK</span>
            </div>
        </div>
        <!--**************************************FIN RED LINK*********************************-->
        <?php
        if ($_SESSION['boleta']['metodo_de_pago'] == 'mp') {
        ?>
            <div>
                <a href="#" class="btn-mp" onclick="pagarMp()" id="linkmp">
                    <div class="mp-logo-wrap">
                        <img src="img/mercadopago.webp" alt="MP">
                    </div>
                    <div class="mp-text">
                        <span>Pagar ahora con</span>
                        <span>MercadoPago</span>
                    </div>
                </a>
            </div>
        <?php
        }
        ?>
        <div class="d-flex gap-2 flex-wrap">
            <a href="concepto" class="vb-btn vb-btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="Generarboleta.php" target="_blank" class="vb-btn vb-btn-white">
                <i class="fas fa-download"></i> Descargar PDF
            </a>

        </div>
    </div>

    <!-- Visor PDF -->
    <div class="vb-iframe-wrap">
        <iframe
            src="Generarboleta.php"
            style="width:100%; height:780px; border:none; display:block;"
            title="Boleta de Pago">
        </iframe>
    </div>

</div>
<script>
    
    /* ═══════════════════════════════════════════
       Token CSRF y URL del gateway
       ═══════════════════════════════════════════ */
    const CSRF_TOKEN = "<?php echo csrf_token(); ?>";
    const API_URL    = "<?php echo SERVERURL; ?>api.php";
    
    
    (function() {

        const preferenceId = <?php echo json_encode($preferenceId); ?>;
        const publicKey = <?php echo json_encode($b['pb_key']); ?>;

        const walletContainerId = "wallet_container";
        //const btnMp = document.getElementById("btn-merpago");
        const btnMp = document.querySelector(".btn-mp");

        function cargarMercadoPago(callback) {
            if (window.MercadoPago) {
                callback();
                return;
            }

            const script = document.createElement("script");
            script.src = "https://sdk.mercadopago.com/js/v2";
            script.onload = callback;
            script.onerror = () => {
                console.error("No se pudo cargar el SDK de MercadoPago");
            };
            document.head.appendChild(script);
        }

        function inicializarMP() {

            if (!preferenceId) {
                console.error("Preference ID inválido");
                return;
            }

            const mp = new MercadoPago(publicKey, {
                locale: "es-AR"
            });

            mp.bricks().create("wallet", walletContainerId, {
                    initialization: {
                        preferenceId: preferenceId,
                        redirectMode: "blank"
                    },
                    customization: {
                        texts: {
                            valueProp: "smart_option"
                        }
                    }
                })
                .catch(err => {
                    console.error("Error creando brick MercadoPago:", err);
                });
        }

        cargarMercadoPago(inicializarMP);

    })();
    
    
    //**************************************RED LINK************************************
    const pagoredlink = () => {
        console.log(API_URL)
        console.log(CSRF_TOKEN)
        $.ajax({
            url: API_URL, 
            type: 'POST',
            data: { 
                modulo: 'concepto', 
                action: 'gendeudaRedLink', 
                csrf_token: CSRF_TOKEN
            },
            //dataType: 'json',
            /*headers: { 
                'X-Requested-With': 'XMLHttpRequest' 
            },*/
            success: function (res) {
                console.log('pp', res)
                if (!res.ok) { 
                    Swal.fire('Error', res.mensaje || 'Error al calcular.', 'error'); 
                    return; 
                }
                
                console.log('resqqq', res); // Corrección: cerrar correctamente el console.log
                
                // Aquí puedes procesar la respuesta exitosa
                // Ejemplo: mostrar código de pago
                if (res.codigo_pago) {
                    Swal.fire({
                        title: '¡Deuda generada!',
                        html: `
                            <p><strong>Código de pago:</strong> ${res.codigo_pago}</p>
                            <p><strong>CPE:</strong> ${res.cpe || 'N/A'}</p>
                            <hr>
                            <p><strong>¿Cómo pagar?</strong></p>
                            <ol style="text-align: left;">
                                <li>Ingresá a tu Home Banking</li>
                                <li>Buscá la entidad correspondiente</li>
                                <li>Ingresá el código: <strong>${res.codigo_pago}</strong></li>
                                <li>Confirmá el importe y pagá</li>
                            </ol>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Entendido'
                    });
                }
                
            },
            error: function (error) { 
                console.log('error', error);
                Swal.fire('Error', 'No se pudo calcular el total.', 'error');
            }
        });


    }
    //**************************************fin RED LINK************************************
</script>