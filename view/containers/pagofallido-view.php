<?php
$payment_id = htmlspecialchars($_GET['payment_id']        ?? '');
$extref     = htmlspecialchars($_GET['external_reference'] ?? '');
?>

<div style="display:flex;justify-content:center;align-items:center;min-height:70vh;">
    <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;padding:48px 40px;max-width:480px;width:100%;text-align:center;box-shadow:0 4px 24px rgba(0,0,0,0.07);">

        <!-- Logo UTHGRA -->
        <img src="<?php echo SERVERURL; ?>img/uthgra.jpg" style="height:64px;width:auto;margin-bottom:24px;border-radius:8px;">

        <!-- Ícono error -->
        <div style="width:72px;height:72px;background:linear-gradient(135deg,#fc8181,#c53030);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 4px 16px rgba(197,48,48,0.3);">
            <i class="fas fa-times" style="color:#fff;font-size:2rem;"></i>
        </div>

        <div style="font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#a0aec0;margin-bottom:6px;">Estado del pago</div>
        <h2 style="margin:0 0 8px;font-size:1.5rem;font-weight:800;color:#c53030;">El pago no se completó</h2>
        <p style="color:#718096;font-size:.9rem;margin-bottom:28px;">Hubo un problema al procesar tu pago. Podés intentarlo nuevamente o pagar por ventanilla en el Banco Nación.</p>

        <?php if ($payment_id): ?>
        <div style="background:#fff5f5;border:1px solid #fed7d7;border-radius:10px;padding:12px 16px;margin-bottom:24px;text-align:left;">
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
            Si el problema persiste, contactá a UTHGRA para asistencia.
        </p>

        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="javascript:history.back()"
               style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#4e73df,#224abe);color:#fff;border-radius:10px;padding:10px 20px;font-size:.85rem;font-weight:700;text-decoration:none;box-shadow:0 3px 10px rgba(78,115,223,0.3);">
                <i class="fas fa-redo"></i> Reintentar pago
            </a>
            <a href="<?php echo SERVERURL; ?>consulta"
               style="display:inline-flex;align-items:center;gap:8px;background:#f1f3f8;color:#4a5568;border:2px solid #e2e8f0;border-radius:10px;padding:10px 20px;font-size:.85rem;font-weight:700;text-decoration:none;">
                <i class="fas fa-home"></i> Volver al inicio
            </a>
        </div>

    </div>
</div>