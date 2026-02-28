<link rel="stylesheet" href="<?php echo base_url(); ?>/public/asset/dist/css/panelestilo.css">

<div class="container-fluid">

    <div class="row">

        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-orange">
                        <i class="fas fa-users text-orange"></i> Entidades Registradas
                    </h6>
                </div>
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                        <div>
                            <div class="text-xs text-uppercase font-weight-bold text-orange">
                                <i class="fas fa-user-shield"></i> Usuarios
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-dark">
                                <?= $totalUsuarios ?>
                            </div>
                        </div>
                        <a href="<?= base_url('public/usuarios') ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                        <div>
                            <div class="text-xs text-uppercase font-weight-bold text-orange">
                                <i class="fas fa-user-check"></i> Clientes
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-dark">
                                <?= $totalClientes ?>
                            </div>
                        </div>
                        <a href="<?= base_url('public/clientes') ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <div>
                            <div class="text-xs text-uppercase font-weight-bold text-orange">
                                <i class="fas fa-truck"></i> Proveedores Activos
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-dark">
                                <?= $totalProveedores ?>
                            </div>
                        </div>
                        <a href="<?= base_url('public/proveedores') ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-orange">
                        <i class="fas fa-file-invoice-dollar text-orange"></i> Grafico de Caja
                    </h6>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                        <canvas id="grafico_circular" style="max-height: 300px;"></canvas>
                    </div>
                </div>



            </div>
        </div>


        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-orange">
                        <i class="fas fa-bolt text-orange"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">

                    <a href="<?= base_url('public/transacciones/nuevo') ?>" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-file-invoice"></i> Nueva Factura/Nota
                    </a>

                    <a href="<?= base_url('public/transacciones/nuevo-pago') ?>" class="btn btn-success btn-block mb-3">
                        <i class="fas fa-cash-register"></i> Registrar Pago/Cobro
                    </a>

                    <a href="<?= base_url('public/clientes/nuevo') ?>" class="btn btn-info btn-block mb-3">
                        <i class="fas fa-user-plus"></i> Nuevo Cliente
                    </a>

                    <a href="<?= base_url('public/proveedores/nuevo') ?>" class="btn btn-warning btn-block mb-3">
                        <i class="fas fa-truck-loading"></i> Nuevo Proveedor
                    </a>

                    <a href="<?= base_url('public/transacciones') ?>" class="btn btn-secondary btn-block">
                        <i class="fas fa-list"></i> Ver Todas las Transacciones
                    </a>

                </div>
            </div>
        </div>
    </div>

</div>

