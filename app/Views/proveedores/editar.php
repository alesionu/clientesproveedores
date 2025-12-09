<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Proveedor</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url(); ?>public/proveedores/actualizar/<?php echo $proveedor['id']; ?>" method="post">

            <div class="form-group">
                <label class="form-label" for="nombre">Razón social</label>
                <input class="form-control" type="text" name="razon_social" id="nombre" 
                       placeholder="Ej: Arcon S.A" 
                       value="<?php echo $proveedor['razon_social']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="usuario">CUIT</label>
                <input class="form-control" type="number" name="cuit" id="usuario" 
                       placeholder="Ej: 30123456789 (sin espacios ni guiones)"  
                       value="<?php echo $proveedor['cuit']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Teléfono</label>
                <input class="form-control" type="number" name="telefono" id="password" 
                       placeholder="ejem: 5491112345678 (sin espacios ni guiones)"
                        value="<?php echo $proveedor['telefono']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Email</label>
                <input class="form-control" type="text" name="email" id="password" 
                       placeholder="ejem: cliente@gmail.com"
                    value="<?php echo $proveedor['email']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Dirección</label>
                <input class="form-control" type="text" name="direccion" id="password" 
                       placeholder="ejem: Ezpeleta 1234, CABA"
                    value="<?php echo $proveedor['direccion']; ?>" required>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a class="btn btn-danger" href="<?php echo base_url(); ?>public/proveedores">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>