<div class="row">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Listado de Productos y Servicios</h3>
                <a href="<?= base_url('public/productos/nuevo') ?>" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nuevo Producto/Servicio
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="id_data_table" class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($productos)): ?>
                            <?php foreach($productos as $prod): ?>
                            <tr>
                                <td><strong><?= $prod['codigo'] ?></strong></td>
                                <td><?= $prod['nombre'] ?></td>
                                <td><?= $prod['descripcion'] ?? '-' ?></td>
                                <td>
                                    <?php if($prod['tipo'] == 'producto'): ?>
                                    <span class="badge badge-primary">Producto</span>
                                    <?php else: ?>
                                    <span class="badge badge-info">Servicio</span>
                                    <?php endif; ?>
                                </td>
                                <td>$ <?= number_format($prod['precio'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="<?= base_url('public/productos/editar/'.$prod['id']) ?>"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-eliminar"
                                        data-url="<?= base_url('public/productos/eliminar/'.$prod['id']) ?>"
                                        title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay productos registrados</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>