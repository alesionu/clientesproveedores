<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url('public/asset/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/asset/dist/css/adminlte.min.css') ?>">

    <link rel="icon" type="png" href="<?php echo base_url(); ?>public/favicon_io (5)/favicon-32x32.png">

</head>

<body class="hold-transition login-page dark-mode">
    <div class="login-box">
        <div class="card card-outline card-warning">
            <div class="card-header text-center">
                <a class="h1"><b>Login</a>
                <img src="<?php echo base_url(); ?>\public\favicon_io (5)\android-chrome-192x192.png" alt="Logo"
                    style="height: 35px;">
                    
            </div>
            <div class="card-body">
                <p class="login-box-msg">Ingrese sus datos para comenzar</p>

                <?php if(session()->getFlashdata('msg')): ?>
                <div class="alert alert-danger text-center">
                    <?= session()->getFlashdata('msg') ?>
                </div>
                <?php endif; ?>

                <form action="<?php echo base_url( ); ?>public/login/validar" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning btn-block">Ingresar</button>
                        </div>

                    </div>

                </form>
            </div>

        </div>

    




    <script src="<?= base_url('public/asset/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('public/asset/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('public/asset/dist/js/adminlte.min.js') ?>"></script>
</body>

</html>