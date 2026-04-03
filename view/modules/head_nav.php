<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <img src="img/uthgra.jpg" alt="Acceso a Casa Central" width="200" height="50">
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <div class="topbar-divider d-none d-sm-block"></div>
        <a href="https://drive.google.com/file/d/1W4MgxsjYZywrpd33qV2RwjBP7VWTcBaJ/view?usp=sharing" id="mp_head" target="_blank"
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
    </ul>

    <style>
        #mp_head {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #009ee3, #0077b6);
            border-radius: 12px;
            padding: 10px 14px;
            text-decoration: none;
            transition: opacity .2s;
            box-shadow: 0 4px 14px rgba(0, 158, 227, 0.4);
        }

        @media (min-width: 800px) {
            #mp_head {
                display: none;

            }
        }
    </style>

</nav>