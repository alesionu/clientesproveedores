<div class="card">
    <div class="card-header">
        <h3 class="card-title">Crear Nuevo Cliente</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url(); ?>public/clientes/guardar" method="post">

            <div class="form-group">
                <label class="form-label" for="nombre">Razón Social</label>
                <input class="form-control" type="text" name="razon_social" id="nombre" placeholder="Ej: Arcon S.A"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="cuit">CUIT / DNI</label>
                <input class="form-control" type="text" maxlength="11" name="cuit" id="cuit"
                    placeholder="Ej: 20345678901" pattern="[0-9]{8,11}" title="Ingrese solo números (8 a 11 dígitos)"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Teléfono</label>
                <input class="form-control" type="text" maxlength="10" name="telefono" id="telefono"
                    placeholder="" pattern="[0-9]{8,11}" title="Ingrese solo números"
                    >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Email</label>
                <input class="form-control" type="text" name="email" id="password" placeholder="Ingrese una contraseña"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Provincia</label>
                <input class="form-control" type="text" name="provincia" id="password"
                    placeholder="Ingrese una contraseña" required>

            </div>

            <div class="form-group">
                <label class="form-label" for="password">Ciudad</label>
                <input class="form-control" type="text" name="ciudad" id="password"
                    placeholder="Ingrese una contraseña" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Código Postal</label>
                <input class="form-control" type="text" name="codigo_postal" id="password"
                    placeholder="Ingrese una contraseña" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Dirección</label>
                <input class="form-control" type="text" name="direccion" id="password"
                    placeholder="Ingrese una contraseña" required>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a class="btn btn-danger" href="<?php echo base_url(); ?>public/clientes">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>