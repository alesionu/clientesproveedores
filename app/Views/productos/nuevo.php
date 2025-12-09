<div class="row">
    <div class="col-md-12">
        <div class="card card-success mt-2">
            <div class="card-header">
                <h3 class="card-title">Registrar Nuevo Producto/Servicio</h3>
            </div>
            
            <?php if(session()->has('errors')): ?>
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        <?php foreach(session('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('public/productos/guardar') ?>" method="post">
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Código <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       name="codigo" 
                                       placeholder="Ej: PROD-001" 
                                       value="<?= old('codigo') ?>"
                                       required>
                                <small class="text-muted">Debe ser único</small>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nombre <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       name="nombre" 
                                       placeholder="Nombre del producto o servicio"
                                       value="<?= old('nombre') ?>"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo <span class="text-danger">*</span></label>
                                <select class="form-control" name="tipo" required>
                                    <option value="producto" <?= old('tipo') == 'producto' ? 'selected' : '' ?>>
                                        Producto
                                    </option>
                                    <option value="servicio" <?= old('tipo') == 'servicio' ? 'selected' : '' ?>>
                                        Servicio
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Precio ($) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control" 
                                       name="precio" 
                                       step="0.01" 
                                       min="0" 
                                       placeholder="0.00"
                                       value="<?= old('precio') ?>"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea class="form-control" 
                                          name="descripcion" 
                                          rows="3" 
                                          placeholder="Detalles del producto o servicio..."><?= old('descripcion') ?></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="<?= base_url('public/productos') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>