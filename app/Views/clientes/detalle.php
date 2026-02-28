<div class="row">
    <div class="col-md-12">
        <div class="card card-primary mt-2">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i>
                    <?= $cliente['razon_social'] ?>
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('public/clientes') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong><i class="fas fa-id-card"></i> CUIT:</strong><br>
                        <?= $cliente['cuit'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-envelope"></i> Email:</strong><br>
                        <?= $cliente['email'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-phone"></i> Teléfono:</strong><br>
                        <?= $cliente['telefono'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-phone"></i> Provincia:</strong><br>
                        <?= $cliente['provincia'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-phone"></i> Ciudad:</strong><br>
                        <?= $cliente['ciudad'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-phone"></i> CP:</strong><br>
                        <?= $cliente['codigo_postal'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong><br>
                        <?= $cliente['direccion'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div
                    class="info-box <?= $saldo_actual > 0 ? 'bg-warning' : ($saldo_actual < 0 ? 'bg-info' : 'bg-success') ?>">
                    <span class="info-box-icon">
                        <i class="fas fa-balance-scale"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Saldo Actual</span>
                        <span class="info-box-number" style="font-size: 2rem;">
                            $ <?= number_format(abs($saldo_actual), 2, ',', '.') ?>
                        </span>
                        <span class="progress-description">
                            <?php if ($saldo_actual > 0): ?>
                            El cliente nos debe dinero
                            <?php elseif ($saldo_actual < 0): ?>
                            Saldo a favor del cliente
                            <?php else: ?>
                            Cuenta saldada
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i> Historial
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover" id="id_data_table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th style="width: 50px;">Ver</th>
                            <th>N° Comprobante</th>
                            <th>Observaciones</th>
                            <th class="text-right">Debe</th>
                            <th class="text-right">Haber</th>
                            <th class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $saldo_acumulado = 0;
                        foreach($transacciones as $t): 
                            // Calcular debe y haber
                            $debe = 0;
                            $haber = 0;
                            
                            if ($t['tipo_comprobante'] == 'factura' || $t['tipo_comprobante'] == 'nota_debito') {
                                $debe = $t['monto'];
                                $saldo_acumulado += $t['monto'];
                            } else {
                                $haber = $t['monto'];
                                $saldo_acumulado -= $t['monto'];
                            }
                        ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($t['fecha'])) ?></td>
                            <td>
                                <?php if($t['tipo_comprobante'] == 'factura'): ?>
                                <span class="badge badge-primary">Factura</span>
                                <?php elseif($t['tipo_comprobante'] == 'pago'): ?>
                                <span class="badge badge-success">Pago</span>
                                <?php elseif($t['tipo_comprobante'] == 'nota_credito'): ?>
                                <span class="badge badge-warning">N. Crédito</span>
                                <?php else: ?>
                                <span class="badge badge-info">N. Débito</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('public/transacciones/ver_detalle/' . $t['id']) ?>"
                                    class="btn btn-primary btn-sm" title="Ver Detalle del Comprobante">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                            <td>
                                <code><?= $t['numero_comprobante'] ?></code>
                            </td>
                            <td>
                                <small><?= $t['observaciones'] ?: '-' ?></small>
                            </td>
                            <td class="text-right">
                                <?php if ($debe > 0): ?>
                                <strong class="text-danger">
                                    $ <?= number_format($debe, 2, ',', '.') ?>
                                </strong>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <?php if ($haber > 0): ?>
                                <strong class="text-success">
                                    $ <?= number_format($haber, 2, ',', '.') ?>
                                </strong>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <strong
                                    class="<?= $saldo_acumulado > 0 ? 'text-warning' : ($saldo_acumulado < 0 ? 'text-info' : 'text-success') ?>">
                                    $ <?= number_format($saldo_acumulado, 2, ',', '.') ?>
                                </strong>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($transacciones)): ?>
                        <tr>
                            <td class="text-center text-muted">-</td>
                            <td class="text-center text-muted">-</td>
                            <td class="text-center text-muted">-</td>
                            <td class="text-center text-muted">
                                <i class="fas fa-inbox"></i> No hay movimientos
                            </td>
                            <td class="text-center text-muted">-</td>
                            <td class="text-center text-muted">-</td>
                            <td class="text-center text-muted">-</td>
                        </tr>
                        <?php endif; ?>

                    </tbody>
                    <?php if (!empty($transacciones)): ?>
                    <tfoot>
                        <tr class="bg-light">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><strong>SALDO FINAL:</strong></td>
                            <td class="text-right">
                                <strong
                                    class="<?= $saldo_actual > 0 ? 'text-warning' : ($saldo_actual < 0 ? 'text-info' : 'text-success') ?>"
                                    style="font-size: 1.2rem;">
                                    $ <?= number_format($saldo_actual, 2, ',', '.') ?>
                                </strong>
                            </td>
                        </tr>
                    </tfoot>

                    <?php endif; ?>
                </table>
            </div>

        </div>
    </div>
</div>
</div>