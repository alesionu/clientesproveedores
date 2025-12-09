<div class="row">
    <div class="col-md-12">
        <div class="card mt-2">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Detalle del Comprobante</h3>
                <a href="<?= base_url('public/transacciones') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <div class="card-body">
                <!-- Información del Comprobante -->
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Tipo:</th>
                                <td>
                                    <?php
                                    $tipos = [
                                        'factura' => '<span class="badge badge-success">Factura</span>',
                                        'nota_debito' => '<span class="badge badge-warning">Nota de Débito</span>',
                                        'nota_credito' => '<span class="badge badge-info">Nota de Crédito</span>',
                                        'pago' => '<span class="badge badge-primary">Pago/Cobro</span>'
                                    ];
                                    echo $tipos[$transaccion['tipo_comprobante']] ?? $transaccion['tipo_comprobante'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>N° Comprobante:</th>
                                <td><strong><?= $transaccion['numero_comprobante'] ?></strong></td>
                            </tr>
                            <tr>
                                <th>Fecha:</th>
                                <td><?= date('d/m/Y', strtotime($transaccion['fecha'])) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Cliente/Proveedor:</th>
                                <td>
                                    <?php
                                    if ($transaccion['cliente_id']) {
                                        echo '<i class="fas fa-user text-success"></i> Cliente';
                                    } else {
                                        echo '<i class="fas fa-building text-primary"></i> Proveedor';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Monto Total:</th>
                                <td><h4 class="mb-0">$ <?= number_format($transaccion['monto'], 2, ',', '.') ?></h4></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if (!empty($transaccion['observaciones'])): ?>
                    <div class="alert alert-secondary">
                        <strong>Observaciones:</strong><br>
                        <?= nl2br($transaccion['observaciones']) ?>
                    </div>
                <?php endif; ?>

                <!-- Detalle de Productos (solo si es factura y tiene detalle) -->
                <?php if ($transaccion['tipo_comprobante'] == 'factura' && !empty($detalle)): ?>
                    <hr>
                    <h5 class="mb-3">Detalle de Productos/Servicios</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Producto/Servicio</th>
                                    <th>Tipo</th>
                                    <th class="text-center" width="120">Cantidad</th>
                                    <th class="text-right" width="150">Precio Unit.</th>
                                    <th class="text-right" width="150">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($detalle as $item): ?>
                                    <tr>
                                        <td><?= $item['codigo'] ?></td>
                                        <td><?= $item['nombre_producto'] ?></td>
                                        <td>
                                            <?php if($item['tipo'] == 'producto'): ?>
                                                <span class="badge badge-primary">Producto</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Servicio</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= number_format($item['cantidad'], 2) ?></td>
                                        <td class="text-right">$ <?= number_format($item['precio_unitario'], 2, ',', '.') ?></td>
                                        <td class="text-right">$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
                                    <td class="text-right">
                                        <strong>$ <?= number_format($transaccion['monto'], 2, ',', '.') ?></strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php elseif($transaccion['tipo_comprobante'] == 'factura'): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> Esta factura no tiene detalle de productos registrado.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>