<div class="row">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-header">
                <h3 class="card-title">Historial de Transacciones</h3>
                <div class="card-tools">
                    <a href="<?= base_url('public/transacciones/nuevo') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Nueva Operación
                    </a>
                    <a href="<?= base_url('public/transacciones/nuevo-pago') ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-money-bill-wave"></i> Registrar Pago/Cobro
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="id_data_table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Número de Comprobante</th>
                            <th>Entidad</th>
                            <th>Monto</th>
                            <th>Observaciones</th>

                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transacciones as $t): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($t['fecha'])) ?></td>
                            <td>
                                <!-- Etiqueta de color según tipo -->
                                <?php if($t['tipo_comprobante'] == 'factura'): ?>
                                <span class="badge badge-primary">Factura</span>
                                <?php elseif($t['tipo_comprobante'] == 'pago'): ?>
                                <span class="badge badge-success">Pago/Cobro</span>
                                <?php elseif($t['tipo_comprobante'] == 'nota_credito'): ?>
                                <span class="badge badge-warning">N. Crédito</span>
                                <?php else: ?>
                                <span class="badge badge-info">N. Débito</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $t['numero_comprobante'] ?></td>
                            <td>
                                <!-- Mostrar Cliente o Proveedor según corresponda -->
                                <?php if($t['cliente_id']): ?>
                                <i class="fas fa-user text-primary"></i> <?= $t['nombre_cliente'] ?>
                                <?php else: ?>
                                <i class="fas fa-truck text-warning"></i> <?= $t['nombre_proveedor'] ?>
                                <?php endif; ?>
                            </td>
                            <td>$ <?= number_format($t['monto'], 2, ',', '.') ?></td>
                            <td><?= $t['observaciones'] ?></td>

                    
                            
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                
                </div>
            </div>
        </div>
    </div>
</div>