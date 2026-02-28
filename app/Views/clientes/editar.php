<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Cliente</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url(); ?>public/clientes/actualizar/<?php echo $cliente['id']; ?>" method="post">

            <div class="form-group">
                <label class="form-label" for="nombre">Razón social</label>
                <input class="form-control" type="text" name="razon_social" id="nombre" 
                       placeholder="Ej: Arcon S.A" 
                       value="<?php echo $cliente['razon_social']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="usuario">CUIT</label>
                <input class="form-control" type="number" name="cuit" id="usuario" 
                       placeholder="Ej: 30123456789 (sin espacios ni guiones)"  
                       value="<?php echo $cliente['cuit']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Teléfono</label>
                <input class="form-control" type="number" name="telefono" id="password" 
                       placeholder="ejem: 5491112345678 (sin espacios ni guiones)"
                        value="<?php echo $cliente['telefono']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Email</label>
                <input class="form-control" type="text" name="email" id="password" 
                       placeholder="ejem: cliente@gmail.com"
                    value="<?php echo $cliente['email']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Provincia</label>
                <input class="form-control" type="text" name="provincia" id="password" 
                       placeholder="ejem: Ezpeleta 1234, CABA"
                    value="<?php echo $cliente['provincia']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Ciudad</label>
                <input class="form-control" type="text" name="ciudad" id="password" 
                       placeholder="ejem: Ezpeleta 1234, CABA"
                    value="<?php echo $cliente['ciudad']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Código Postal</label>
                <input class="form-control" type="text" name="codigo_postal" id="password" 
                       placeholder="3153"
                    value="<?php echo $cliente['codigo_postal']; ?>" required>
            </div>

            

            <div class="form-group">
                <label class="form-label" for="password">Dirección</label>
                <input class="form-control" type="text" name="direccion" id="password" 
                       placeholder="ejem: Ezpeleta 1234, CABA"
                    value="<?php echo $cliente['direccion']; ?>" required>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a class="btn btn-danger" href="<?php echo base_url(); ?>public/clientes">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>