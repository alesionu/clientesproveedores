<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($titulo) ? $titulo : 'Clientes y Proveedores'; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/asset/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>public/asset/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>public/asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/asset/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/asset/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>public/asset/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/asset/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/asset/plugins/summernote/summernote-bs4.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="icon" type="png" href="<?php echo base_url(); ?>\public\favicon_io (5)\android-chrome-192x192.png">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link rel="stylesheet"
        href="<?= base_url('public/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/plugins/toastr/toastr.min.css') ?>">
</head>


</head>

<body class="hold-transition sidebar-mini layout-fixed dark-mode">

    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__snake"
                src="<?php echo base_url(); ?>\public\favicon_io (5)\android-chrome-192x192.png" alt="AdminLTELogo"
                height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars text-orange"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">


                <!-- User Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="far fa-user text-orange"></i>
                        <span class="ml-2"><?php echo session()->get('nombre'); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User info header -->
                        <div class="dropdown-header bg-dark text-center">
                            <i class="fas fa-user-circle fa-3x"></i>
                            <p class="mt-2 mb-0">
                                <strong><?php echo session()->get('nombre'); ?></strong>
                            </p>
                        </div>
                        <div class="dropdown-divider"></div>



                        <div class="dropdown-divider"></div>

                        <!-- Logout button -->
                        <a href="#" data-toggle="modal" data-target="#logoutModal"
                            class="dropdown-item dropdown-footer bg-danger text-white text-center">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </a>

                    </div>
                </li>

                <!-- Fullscreen button -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt text-orange"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->

            <a href="<?php echo base_url(); ?>public/" class="brand-link">
                <img src="<?php echo base_url(); ?>public/favicon_io (5)/android-chrome-192x192.png" alt="Logo"
                    class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light"><b>C&P</b></span>
            </a>


            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (simplificado) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <i class="fas fa-user-circle fa-2x text-orange"></i>
                    </div>
                    <div class="info">
                        <a class="d-block"><?php echo session()->get('nombre'); ?></a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>public/" class="nav-link nav-orange">
                                <i class="nav-icon fas fa-tachometer-alt text-orange"></i>
                                <p>Tablero</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>public/usuarios" class="nav-link">
                                <i class="nav-icon fas fa-users text-orange"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>public/clientes" class="nav-link">
                                <i class="nav-icon fas fa-address-book text-orange"></i>
                                <p> Clientes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>public/proveedores" class="nav-link">
                                <i class="nav-icon fas fa-solid fa-box text-orange"></i>
                                <p>Proveedores</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>public/transacciones" class="nav-link">
                                <i class="nav-icon fas fa-exchange-alt text-orange"></i>
                                <p>Transacciones</p>
                            </a>
                        <li class="nav-item">
                            <a href="<?= base_url('public/productos') ?>" class="nav-link">
                                <i class="nav-icon fas fa-box text-orange"></i>
                                <p>Productos y Servicios</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?php echo isset($titulo) ? $titulo : 'Dashboard'; ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right bg-transparent">
                                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>public/">Inicio</a></li>
                                <?php if (isset($pagname)): ?>
                                <li class="breadcrumb-item active"><?php echo $pagname; ?></li>
                                <?php endif; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">¿Esta seguro de cerrar la sesión?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Seleccione 'Cerrar sesión' a continuación si está listo para finalizar
                            su sesión actual.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <a class="btn btn-danger" href="<?php echo base_url(); ?>public/login/logout">Cerrar
                                Sesión</a>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">