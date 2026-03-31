<?php
// Parámetros que envía MercadoPago en la URL
$payment_id = htmlspecialchars($_GET['payment_id']        ?? '');
$status     = htmlspecialchars($_GET['status']            ?? '');
$extref     = htmlspecialchars($_GET['external_reference'] ?? '');
?>

<div style="display:flex;justify-content:center;align-items:center;min-height:70vh;">
    <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;padding:48px 40px;max-width:480px;width:100%;text-align:center;box-shadow:0 4px 24px rgba(0,0,0,0.07);">

        <!-- Logo UTHGRA -->
        <img src="<?php echo SERVERURL; ?>img/uthgra.jpg" style="height:64px;width:auto;margin-bottom:24px;border-radius:8px;">

        <!-- Ícono éxito -->
        <div style="width:72px;height:72px;background:linear-gradient(135deg,#48bb78,#276749);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 4px 16px rgba(72,187,120,0.35);">
            <i class="fas fa-check" style="color:#fff;font-size:2rem;"></i>
        </div>

        <div style="font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#a0aec0;margin-bottom:6px;">Estado del pago</div>
        <h2 style="margin:0 0 8px;font-size:1.5rem;font-weight:800;color:#276749;">¡Pago realizado!</h2>
        <p style="color:#718096;font-size:.9rem;margin-bottom:28px;">Tu pago fue procesado correctamente por MercadoPago.</p>

        <?php if ($payment_id): ?>
        <div style="background:#f0fff4;border:1px solid #c6f6d5;border-radius:10px;padding:12px 16px;margin-bottom:24px;text-align:left;">
            <?php if ($payment_id): ?>
            <div style="font-size:.78rem;color:#4a5568;margin-bottom:4px;">
                <span style="font-weight:700;">N° de pago:</span> <?php echo $payment_id; ?>
            </div>
            <?php endif; ?>
            <?php if ($extref): ?>
            <div style="font-size:.78rem;color:#4a5568;">
                <span style="font-weight:700;">CUIT:</span> <?php echo $extref; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <p style="font-size:.78rem;color:#a0aec0;margin-bottom:28px;">
            Recibirás el comprobante por email desde MercadoPago. Podés imprimir o descargar la boleta generada.
        </p>

        <a href="<?php echo SERVERURL; ?>consulta"
           style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#4e73df,#224abe);color:#fff;border-radius:10px;padding:10px 24px;font-size:.85rem;font-weight:700;text-decoration:none;box-shadow:0 3px 10px rgba(78,115,223,0.3);">
            <i class="fas fa-home"></i> Volver al inicio
        </a>

    </div>
</div>