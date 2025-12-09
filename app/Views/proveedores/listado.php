<div class="row">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-header">
                <h3 class="card-title">Listado de Proveedores</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#modalInfoSaldosProveedores">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <a href="<?= base_url('public/proveedores/nuevo') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Proveedor
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="id_data_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Razón Social</th>
                            <th>CUIT</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Saldo</th>
                            <th class="no-export">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($proveedores as $proveedor): ?>
                        <?php 
                            // Obtener el saldo del proveedor
                            $saldo = isset($saldos[$proveedor['id']]) ? $saldos[$proveedor['id']] : 0;
                        ?>
                        <tr>
                            <td><?= $proveedor['id'] ?></td>
                            <td>
                                <strong><?= $proveedor['razon_social'] ?></strong>
                            </td>
                            <td><?= $proveedor['cuit'] ?></td>
                            <td><?= $proveedor['telefono'] ?></td>
                            <td><?= $proveedor['email'] ?></td>
                            <td><?= $proveedor['direccion'] ?></td>

                            <td>
                                <?php if ($saldo > 0): ?>
                                    <span class="badge badge-danger" style="font-size: 14px;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        $ <?= number_format($saldo, 2, ',', '.') ?>
                                    </span>
                                <?php elseif ($saldo < 0): ?>
                                    <span class="badge badge-info" style="font-size: 14px;">
                                        <i class="fas fa-check"></i>
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
                                
                                    <a href="<?= base_url('public/proveedores/detalle/'.$proveedor['id']) ?>" 
                                    class="btn btn-info btn-sm" title="Ver Cuenta Corriente">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </a>
                                    <a href="<?= base_url('public/proveedores/editar/'.$proveedor['id']) ?>" 
                                    class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-eliminar"
                                    data-url="<?= base_url('public/proveedores/borrar/'.$proveedor['id']) ?>"
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
<!-- Modal de Información de Saldos -->
<div class="modal fade" id="modalInfoSaldosProveedores" tabindex="-1" role="dialog" aria-labelledby="modalInfoSaldosLabel" aria-hidden="true">
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
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="font-size: 18px; font-weight: bold;">Saldo Positivo</span>
                                <span class="info-box-number" style="font-size: 16px;">Debemos dinero al proveedor</span>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="font-size: 18px; font-weight: bold;">Saldo en Cero</span>
                                <span class="info-box-number" style="font-size: 16px;">Cuenta saldada</span>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="font-size: 18px; font-weight: bold;">Saldo Negativo</span>
                                <span class="info-box-number" style="font-size: 16px;">Saldo a favor nuestro</span>
                                
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
