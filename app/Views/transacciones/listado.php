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
                            <th>N° Comp</th>
                            <th>CUIT/DNI</th>
                            <th>Razon Social</th>
                            <th>Entidad</th>
                            <th>Monto</th>
                            <th>Forma de Pago</th>
                            <th>Observaciones</th>
                            <th style="width: 50px;">Ver</th>
                        </tr>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transacciones as $t): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($t['fecha'])) ?></td>
                            <td>
                                <?php if($t['tipo_comprobante'] == 'factura'): ?>
                                <span href="" class="badge badge-primary">Factura</span>
                                <?php elseif($t['tipo_comprobante'] == 'pago'): ?>
                                <span class="badge badge-success">Pago/Cobro</span>
                                <?php elseif($t['tipo_comprobante'] == 'nota_credito'): ?>
                                <span class="badge badge-warning">N. Crédito</span>
                                <?php else: ?>
                                <span class="badge badge-info">N. Débito</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $t['numero_comprobante'] ?></td>



                            <td><?= !empty($t['cuit']) ? $t['cuit'] : '-' ?></td>

                            <td>
                                <?php if($t['cliente_id']): ?>
                                <i class="fas fa-user text-primary"></i> <?= $t['nombre_cliente'] ?>
                                <?php else: ?>
                                <i class="fas fa-truck text-warning"></i> <?= $t['nombre_proveedor'] ?>
                                <?php endif; ?>
                            </td>
                            <td><?php if($t['cliente_id']): ?>
                                <i>cliente</i>
                                <?php else: ?>
                                <i>proveedor</i>
                                <?php endif; ?>
                            </td>

                            <td>$ <?= number_format($t['monto'], 2, ',', '.') ?></td>
                            <td>
                                <?php if(!empty($t['forma_pago'])): ?>
                                <?php 
        // Extraer días si es crédito
        if(strpos($t['forma_pago'], 'credito_') === 0) {
            $dias = str_replace('credito_', '', $t['forma_pago']);
            echo '<span class="badge badge-warning">Crédito ' . $dias . ' días</span>';
        } elseif($t['forma_pago'] == 'efectivo') {
            echo '<span class="badge badge-success">Efectivo</span>';
        } elseif($t['forma_pago'] == 'transferencia') {
            echo '<span class="badge badge-info">Transferencia</span>';
        } elseif($t['forma_pago'] == 'debito') {
            echo '<span class="badge badge-primary">Débito</span>';
        } elseif($t['forma_pago'] == 'credito') {
            echo '<span class="badge badge-warning">Crédito</span>';
        } elseif($t['forma_pago'] == 'cuenta_corriente') {
            echo '<span class="badge badge-secondary">Cuenta Corriente</span>';
        } else {
            echo ucfirst($t['forma_pago']);
        }
        ?>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $t['observaciones'] ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('public/transacciones/ver_detalle/' . $t['id']) ?>"
                                    class="btn btn-primary btn-sm" title="Ver Detalle del Comprobante">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>



                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</div>
</div>