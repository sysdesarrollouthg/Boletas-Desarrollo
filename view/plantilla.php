

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- ── Cache: forzar recarga para validar sesión ── -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <meta name="description" content="Sistema de Boletas de U.T.H.G.R.A">

    <title>U.T.H.G.R.A — Boletas TEST</title>

    <!-- ── CSS ── -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/dataTables.dataTables.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <script src="vendor/jquery/jquery.min.js"></script>
</head>

<body id="page-top">

<?php
    /* ═══════════════════════════════════════════
       Resolver qué vista cargar según ?views=
       ═══════════════════════════════════════════ */
    require_once "controller/vistasControlador.php";
    $vt  = new vistasControlador();
    $vtR = $vt->obtener_vistas_ctrl();
?>

    <!-- ══════════════════════════════════════════
         PAGE WRAPPER
         ══════════════════════════════════════════ -->
    <div id="wrapper">

        <!-- ── Sidebar ── -->
        <?php include "view/modules/sidebar_princ.php"; ?>

        <!-- ── Content Wrapper ── -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- ── Main Content ── -->
            <div id="content">

                <!-- ── Topbar ── -->
                <?php include "view/modules/head_nav.php"; ?>

                <!-- ── Contenido de la vista activa ── -->
                <div class="container-fluid">
                    <?php require_once $vtR; ?>
                </div>

            </div>
            <!-- /Main Content -->

            <!-- ── Footer ── -->
            <?php include "view/modules/footer_princ.php"; ?>

        </div>
        <!-- /Content Wrapper -->

    </div>
    <!-- /Page Wrapper -->

    <!-- ── Scroll to Top ── -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- ── Footer fijo ── -->
    <style>
        .sticky-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: .75rem 0;
            box-shadow: 0 -1px 4px rgba(0,0,0,0.08);
        }
        body {
            padding-bottom: 50px;
        }
    </style>

    <!-- ══════════════════════════════════════════
         SCRIPTS — se cargan al final del body
         ══════════════════════════════════════════ -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

  //PARA QUE EL MENU LATERAL APAREZCA CERRADO EN CELULARES
        $(document).ready(function () {
    if ($(window).width() < 768) {
        $('body').addClass('sidebar-toggled');
        $('.sidebar').addClass('toggled');
    }
});

        /* ═══════════════════════════════════════════
           Si el usuario vuelve con botón "atrás",
           forzar recarga para verificar sesión activa
           ═══════════════════════════════════════════ */
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                window.location.reload();
            }
        });
    </script>

</body>
</html>
