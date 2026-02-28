<div class="row">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-header">
                <h3 class="card-title">Listado de Clientes</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#modalInfoSaldos">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <a href="<?= base_url('public/clientes/nuevo') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Cliente
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="id_data_table">
                    <thead>
                        <tr>
                            <th>Razón Social</th>
                            <th>CUIT / DNI</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Provincia</th>
                            <th>Ciudad</th>
                            <th>CP</th>
                            <th>Dirección</th>
                            <th>Saldo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($clientes as $cliente): ?>
                        <?php 
                            $saldo = isset($saldos[$cliente['id']]) ? $saldos[$cliente['id']] : 0;
                        ?>
                        <tr>
                            <td>
                                <strong><?= $cliente['razon_social'] ?></strong>
                            </td>
                            <td><?= $cliente['cuit'] ?></td>
                            <td><?= $cliente['telefono'] ?></td>
                            <td><?= $cliente['email'] ?></td>
                            <td><?= $cliente['provincia'] ?></td>
                            <td><?= $cliente['ciudad'] ?></td> 
                            <td><?= $cliente['codigo_postal'] ?></td>
                            <td><?= $cliente['direccion'] ?></td>
                            <td>
                                <?php if ($saldo > 0): ?>
                                <span class="badge badge-warning" style="font-size: 14px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    $ <?= number_format($saldo, 2, ',', '.') ?>
                                </span>
                                <?php elseif ($saldo < 0): ?>
                                <span class="badge badge-info" style="font-size: 14px;">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    $ <?= number_format(abs($saldo), 2, ',', '.') ?>
                                </span>
                                <?php else: ?>
                                <span class="badge badge-success" style="font-size: 14px;">
                                    <i class="fas fa-check-circle"></i>
                                    $ 0,00
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('public/clientes/detalle/'.$cliente['id']) ?>"
                                    class="btn btn-info btn-sm" title="Ver Cuenta Corriente">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </a>
                                <a href="<?= base_url('public/clientes/editar/'.$cliente['id']) ?>"
                                    class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-eliminar"
                                    data-url="<?= base_url('public/clientes/borrar/'.$cliente['id']) ?>"
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInfoSaldos" tabindex="-1" role="dialog" aria-labelledby="modalInfoSaldosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="modalInfoSaldosLabel">
                    <i class="fas fa-info-circle"></i> Información sobre Saldos
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="font-size: 18px; font-weight: bold;">Saldo Positivo</span>
                                <span class="info-box-number" style="font-size: 16px;">El cliente nos debe dinero</span>
                                <p class="mt-2 mb-0" style="font-size: 14px;">
                                    Cuando el saldo es positivo, significa que el cliente ha recibido más productos o servicios 
                                    de los que ha pagado. El monto representa la deuda pendiente del cliente.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="font-size: 18px; font-weight: bold;">Saldo en Cero</span>
                                <span class="info-box-number" style="font-size: 16px;">Cuenta saldada</span>
                                <p class="mt-2 mb-0" style="font-size: 14px;">
                                    Un saldo en cero indica que el cliente está al día con sus pagos. 
                                    No hay deudas pendientes ni saldos a favor.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="font-size: 18px; font-weight: bold;">Saldo Negativo</span>
                                <span class="info-box-number" style="font-size: 16px;">Saldo a favor del cliente</span>
                                <p class="mt-2 mb-0" style="font-size: 14px;">
                                    Cuando el saldo es negativo, el cliente ha pagado más de lo que ha recibido en productos o servicios. 
                                    Este monto representa un crédito a favor del cliente que puede usarse en futuras transacciones.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>