<?php
// Si ya está logueado redirigir al home
if (isset($_SESSION['usuario_id'])) {
    header("Location: " . SERVERURL . "home");
    exit();
}

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Padrón OSUTHGRA — Ingresar</title>
    <link href="./vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="./css/sb-admin-2.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">

                        <!-- Logo y título -->
                        <div class="text-center mb-4">
                            <img src="./img/uthgra.jpg" alt="UTHGRA" style="height:70px;">
                            <h4 class="mt-3 font-weight-bold text-gray-800">Padrón OSUTHGRA</h4>
                            <p class="text-muted small">Ingresá con tu cuenta</p>
                        </div>

                        <form id="form-login">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="action"     value="login">

                            <!-- Email -->
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Email</label>
                                <input type="email" 
                                       class="form-control form-control-user" 
                                       id="email" 
                                       name="email"
                                       placeholder="tu@email.com"
                                       required autocomplete="email">
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-user" 
                                           id="password" 
                                           name="password"
                                           placeholder="••••••••"
                                           required autocomplete="current-password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePass" tabindex="-1">
                                            <i class="fas fa-eye" id="iconoOjo"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón -->
                            <button type="submit" class="btn btn-primary btn-user btn-block" id="btn-login">
                                <span id="btn-texto">Ingresar</span>
                                <span id="btn-loading" class="d-none">
                                    <i class="fas fa-spinner fa-spin"></i> Verificando...
                                </span>
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="./vendor/jquery/jquery.min.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./js/sb-admin-2.min.js"></script>
<script src="./js/sweetalert2@11.js"></script>

<script>
$(document).ready(function () {

    // Mostrar/ocultar password
    $('#togglePass').on('click', function () {
        const input  = $('#password');
        const icono  = $('#iconoOjo');
        const tipo   = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', tipo);
        icono.toggleClass('fa-eye fa-eye-slash');
    });

    // Submit login por AJAX
    $('#form-login').on('submit', function (e) {
        e.preventDefault();

        // Mostrar loading
        $('#btn-texto').addClass('d-none');
        $('#btn-loading').removeClass('d-none');
        $('#btn-login').prop('disabled', true);

        $.ajax({
            url:      './controller/LoginController.php',
            type:     'POST',
            data:     $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.ok) {
                    Swal.fire({
                        title:             res.titulo,
                        text:              res.mensaje,
                        icon:              res.icono,
                        showConfirmButton: false,
                        timer:             1200
                    }).then(function () {
                        window.location.href = res.redirect;
                    });
                } else {
                    Swal.fire({
                        title: res.titulo,
                        text:  res.mensaje,
                        icon:  res.icono,
                        confirmButtonText: 'Aceptar'
                    });
                    // Resetear botón
                    $('#btn-texto').removeClass('d-none');
                    $('#btn-loading').addClass('d-none');
                    $('#btn-login').prop('disabled', false);
                }
            },
            error: function () {
                Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                $('#btn-texto').removeClass('d-none');
                $('#btn-loading').addClass('d-none');
                $('#btn-login').prop('disabled', false);
            }
        });
    });

});
</script>

</body>
</html>