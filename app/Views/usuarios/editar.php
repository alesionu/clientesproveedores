<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Usuario</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url(); ?>public/usuarios/actualizar/<?php echo $usuario['id']; ?>" method="post">

            <div class="form-group">
                <label class="form-label" for="nombre">Nombre Completo</label>
                <input class="form-control" type="text" name="nombre" id="nombre" 
                       placeholder="Ingrese un nombre" 
                       value="<?php echo $usuario['nombre']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="usuario">Nombre de Usuario</label>
                <input class="form-control" type="text" name="usuario" id="usuario" 
                       placeholder="Ingrese el nombre de usuario" 
                       value="<?php echo $usuario['usuario']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Nueva Contraseña (opcional)</label>
                <input class="form-control" type="password" name="password" id="password" 
                       placeholder="Dejar en blanco para mantener la actual">
                <small class="form-text text-muted">
                    Solo completa este campo si deseas cambiar la contraseña
                </small>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a class="btn btn-danger" href="<?php echo base_url(); ?>public/usuarios">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>