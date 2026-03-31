<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <br>
    <div class="sidebar-heading">
        U.T.H.G.R.A
    </div>

    <!-- Sidebar - Brand -->
    <div class="sidebar-brand d-flex align-items-center justify-content-center">
        <p style="font-size: 12px;">Generar Boletas</p>
    </div>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="home">
            <i class="fas fa-fw fa-globe"></i>
            <span>Panel principal</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Registrar Empresa -->
   <li class="nav-item">
        <a class="nav-link" href="crearempresa">
            <i class="fas fa-building"></i>
            <span>Registrar Empresa</span>
        </a>
    </li>
    <!-- Divider -->
 <!--   <hr class="sidebar-divider"> -->

    <!-- Botón debug sesión -->
  
    <li class="nav-item text-center py-2">
        <button type="button" class="btn btn-sm btn-light mx-3"
                data-bs-toggle="modal" data-target="#modalDebugSession" onclick="$('#modalDebugSession').modal('show')">
            <i class="fas fa-bug mr-1"></i> Debug Sesión
        </button>
    </li> 
    
    <!-- Divider -->
 <!--   <hr class="sidebar-divider"> -->

    <!-- Botón debug sesión -->

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Botón Instructivo MP -->

<li class="nav-item text-center py-3 px-3">
    <a href="https://drive.google.com/file/d/1W4MgxsjYZywrpd33qV2RwjBP7VWTcBaJ/view?usp=sharing" id="mp" target="_blank" 
       
        onmouseover="this.style.opacity='.85'"
        onmouseout="this.style.opacity='1'">
       <div style="flex-shrink:0;">
    <img src="<?php echo SERVERURL; ?>img/mercadopago.png" style="height:40px;width:auto;">
</div>
        <div style="text-align:left;line-height:1.2;">
            <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,0.7);">Instructivo</div>
            <div style="font-size:.78rem;font-weight:800;color:#fff;">Pago por MercadoPago</div>
        </div>
    </a>
</li>
</ul>
<!-- ↑ ul cerrado correctamente ANTES del modal -->
<div class="modal fade" id="modalDebugSession" tabindex="-1" aria-labelledby="modalDebugSessionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="modalDebugSessionLabel">
                    <i class="fas fa-database mr-2"></i> $_SESSION activa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="background:white; color:#d4d4d4; font-family:monospace; font-size:16px; max-height:700px; overflow-y:auto;">
                <pre><?php var_dump($_SESSION); ?></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>

#mp{

 display:flex; align-items:center; gap:10px;
        
 background:linear-gradient(135deg,#009ee3,#0077b6);
 border-radius:12px; 
 padding:10px 14px;
 text-decoration:none; transition:opacity .2s;
 box-shadow:0 4px 14px rgba(0,158,227,0.4);"



}

@media (max-width: 800px) {
    #mp {
display:none;

    

    }
}


</style>







