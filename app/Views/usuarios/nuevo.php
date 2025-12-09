<div class="card">
    <div class="card-header">
        <h3 class="card-title">Crear Nuevo Usuario</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url(); ?>public/usuarios/guardar" method="post">
            
            <div class="form-group">
                <label class="form-label" for="nombre">Nombre Completo</label>
                <input class="form-control" type="text" name="nombre" id="nombre" 
                       placeholder="Ingrese nombre completo" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="usuario">Nombre de Usuario</label>
                <input class="form-control" type="text" name="usuario" id="usuario" 
                       placeholder="Ingrese un nombre de usuario" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input class="form-control" type="password" name="password" id="password" 
                       placeholder="Ingrese una contraseña" required>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a class="btn btn-danger" href="<?php echo base_url(); ?>public/usuarios">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>