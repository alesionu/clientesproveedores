<link rel="stylesheet" href="<?php echo base_url(); ?>/public/asset/dist/css/transaccionesdetalle.css">
<div class="row">
    <div class="col-12">
        <div class="mb-3 no-print">
            <a href="<?= base_url('public/transacciones') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>

            <?php if (!empty($transaccion['archivo_pdf'])): ?>
            <a href="<?= base_url('public/uploads/comprobantes/' . $transaccion['archivo_pdf']) ?>" target="_blank"
                class="btn btn-info">
                <i class="fas fa-file-pdf"></i> Ver PDF Original
            </a>
            <?php endif; ?>

            <button onclick="window.print()" class="btn btn-primary float-right">
                <i class="fas fa-print"></i> Imprimir Resumen
            </button>
        </div>

        <div class="invoice p-3 mb-3 invoice-box">
            <div class="row">
                <div class="col-12">
                    <h4>
                        <div class="preloader flex-column justify-content-center align-items-center">
                            <img class="animation__snake"
                                src="<?php echo base_url(); ?>\public\favicon_io (5)\android-chrome-192x192.png"
                                alt="AdminLTELogo" height="60" width="60">
                        </div>
                        <i class="fas fa-file-invoice"></i> Comprobante N° <?= $transaccion['numero_comprobante'] ?>
                        <small class="float-right">Fecha: <?= date('d/m/Y', strtotime($transaccion['fecha'])) ?></small>
                    </h4>
                </div>
            </div>

            <div class="row invoice-info mt-4">
                <div class="col-sm-6 invoice-col">
                    <strong><?= $transaccion['cliente_id'] ? 'Cliente:' : 'Proveedor:' ?></strong>
                    <address>
                        <strong><?= $transaccion['nombre_razon_social'] ?? 'Sin especificar' ?></strong><br>
                        <?= $transaccion['direccion_entidad'] ?? '' ?><br>
                        <?php if(isset($transaccion['documento_entidad']) && !empty($transaccion['documento_entidad'])): ?>
                        CUIT: <?= $transaccion['documento_entidad'] ?><br>
                        <?php endif; ?>
                        <?php if($transaccion['cliente_id']): ?>
                        <span class="badge badge-primary"><i class="fas fa-user"></i> Cliente</span>
                        <?php else: ?>
                        <span class="badge badge-warning"><i class="fas fa-truck"></i> Proveedor</span>
                        <?php endif; ?>
                    </address>
                </div>

                <div class="col-sm-6 invoice-col">
                    <b>Comprobante #<?= $transaccion['numero_comprobante'] ?></b><br>
                    <br>
                    <b>Tipo:</b>
                    <?php
                        $tipos = [
                            'factura' => 'FACTURA',
                            'nota_debito' => 'NOTA DE DÉBITO',
                            'nota_credito' => 'NOTA DE CRÉDITO',
                            'pago' => 'PAGO/COBRO'
                        ];
                        echo $tipos[$transaccion['tipo_comprobante']] ?? strtoupper($transaccion['tipo_comprobante']);
                    ?><br>
                    
                    <b>Forma de Pago:</b>
                    <?php if(!empty($transaccion['forma_pago'])): ?>
                        <?php 
                        // Manejo correcto de crédito con días
                        if(strpos($transaccion['forma_pago'], 'credito_') === 0) {
                            $dias = str_replace('credito_', '', $transaccion['forma_pago']);
                            echo 'Crédito a ' . $dias . ' días';
                        } else {
                            echo ucfirst(str_replace('_', ' ', $transaccion['forma_pago']));
                        }
                        ?>
                    <?php else: ?>
                        <span class="text-muted">Sin especificar</span>
                    <?php endif; ?>
                    <br>
                    
                    <b>Estado:</b>
                    <?php if ($transaccion['pagado']): ?>
                    <span class="badge badge-success">Pagado</span>
                    <?php else: ?>
                    <span class="badge badge-danger">Pendiente</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Detalle de Productos (solo para facturas de clientes) -->
            <?php if ($transaccion['tipo_comprobante'] == 'factura' && $transaccion['cliente_id'] && !empty($detalle)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <h5><i class="fas fa-shopping-cart"></i> Detalle de Productos</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-right">Precio Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $subtotal_productos = 0;
                                foreach($detalle as $item): 
                                    $subtotal_item = $item['cantidad'] * $item['precio_unitario'];
                                    $subtotal_productos += $subtotal_item;
                                ?>
                                <tr>
                                    <td><?= $item['nombre_producto'] ?></td>
                                    <td class="text-center"><?= $item['cantidad'] ?></td>
                                    <td class="text-right">$ <?= number_format($item['precio_unitario'], 2, ',', '.') ?></td>
                                    <td class="text-right">$ <?= number_format($subtotal_item, 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                    <td class="text-right"><strong>$ <?= number_format($subtotal_productos, 2, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Monto Total -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-light border">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Monto Total del Comprobante:</strong></p>
                                <h2 class="text-primary mb-0">$ <?= number_format($transaccion['monto'], 2, ',', '.') ?></h2>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if (!empty($transaccion['archivo_pdf'])): ?>
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>PDF adjunto disponible</strong><br>
                                <small class="text-muted"><?= $transaccion['archivo_pdf'] ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            <?php if (!empty($transaccion['observaciones'])): ?>
            <div class="row mt-3">
                <div class="col-12">
                    <p class="lead mb-2"><i class="fas fa-comment"></i> Observaciones:</p>
                    <div class="card bg-light">
                        <div class="card-body">
                            <?= nl2br($transaccion['observaciones']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Visualización del PDF -->
            <?php if (!empty($transaccion['archivo_pdf'])): ?>
            <div class="row mt-4 no-print">
                <div class="col-12">
                    <h5><i class="fas fa-file-pdf text-danger"></i> Visualización del PDF</h5>
                    <div class="embed-responsive embed-responsive-16by9 border">
                        <iframe class="embed-responsive-item"
                            src="<?= base_url('public/uploads/comprobantes/' . $transaccion['archivo_pdf']) ?>#toolbar=1&navpanes=0&scrollbar=1"
                            type="application/pdf">
                        </iframe>
                    </div>
                    <p class="text-center mt-2">
                        <a href="<?= base_url('public/uploads/comprobantes/' . $transaccion['archivo_pdf']) ?>" download
                            class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Descargar PDF
                        </a>
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Pie de página para impresión -->
            <div class="row mt-5 d-none d-print-block text-center text-muted">
                <div class="col-12">
                    <hr>
                    <small>Documento generado por Sistema de Gestión - <?= date('d/m/Y H:i') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.embed-responsive-16by9 {
    height: 600px;
}
</style>