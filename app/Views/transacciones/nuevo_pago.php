<div class="row">
    <div class="col-md-12">
        <div class="card card-info mt-2">
            <div class="card-header">
                <h3 class="card-title">Registrar Pago o Cobro</h3>
            </div>
            
            <form action="<?= base_url('public/transacciones/guardar-pago') ?>" method="post">
                <div class="card-body">
                    
                    <!-- Tipo de Operación: Cobro o Pago -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de Operación</label>
                                <select class="form-control" name="tipo_operacion" id="selector_operacion" onchange="cambiarOperacion()" required>
                                    <option value="">-- Seleccionar --</option>
                                    <option value="cobro">Cobro (recibimos dinero de un cliente)</option>
                                    <option value="pago">Pago (pagamos a un proveedor)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="label_entidad">Seleccionar Persona/Empresa</label>
                                
                                <!-- Select de Clientes (para COBROS) -->
                                <select class="form-control" name="id_entidad" id="lista_clientes" style="display:none;" disabled>
                                    <option value="">-- Seleccionar Cliente --</option>
                                    <?php foreach($clientes as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= $c['razon_social'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <!-- Select de Proveedores (para PAGOS) -->
                                <select class="form-control" name="id_entidad" id="lista_proveedores" style="display:none;" disabled>
                                    <option value="">-- Seleccionar Proveedor --</option>
                                    <?php foreach($proveedores as $p): ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['razon_social'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <!-- Mensaje inicial -->
                                <div id="mensaje_inicial" class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> Primero seleccione el tipo de operación
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <!-- Datos del Pago/Cobro -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° de Recibo/Comprobante</label>
                                <input type="text" class="form-control" name="numero_comprobante" placeholder="Ej: REC-001234" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Operación</label>
                                <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Monto ($)</label>
                                <input min="0" type="number" step="0.01" class="form-control" name="monto" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Detalles del pago/cobro, forma de pago, etc..."></textarea>
                    </div>


                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save"></i> Guardar Pago/Cobro
                    </button>
                    <a href="<?= base_url('public/transacciones') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function cambiarOperacion() {
        var tipo = document.getElementById('selector_operacion').value;
        var selectClientes = document.getElementById('lista_clientes');
        var selectProveedores = document.getElementById('lista_proveedores');
        var mensajeInicial = document.getElementById('mensaje_inicial');
        var labelEntidad = document.getElementById('label_entidad');

        // Ocultar mensaje inicial
        mensajeInicial.style.display = 'none';

        if (tipo === 'cobro') {
            // Mostrar clientes (cobramos a clientes)
            selectClientes.style.display = 'block';
            selectClientes.disabled = false;
            selectClientes.required = true;
            
            selectProveedores.style.display = 'none';
            selectProveedores.disabled = true;
            selectProveedores.required = false;

            labelEntidad.innerHTML = '<i class="fas fa-user text-primary"></i> Seleccionar Cliente';
            
        } else if (tipo === 'pago') {
            // Mostrar proveedores (pagamos a proveedores)
            selectProveedores.style.display = 'block';
            selectProveedores.disabled = false;
            selectProveedores.required = true;
            
            selectClientes.style.display = 'none';
            selectClientes.disabled = true;
            selectClientes.required = false;

            labelEntidad.innerHTML = '<i class="fas fa-truck text-warning"></i> Seleccionar Proveedor';
            
        } else {
            // No seleccionó nada, mostrar mensaje
            mensajeInicial.style.display = 'block';
            selectClientes.style.display = 'none';
            selectClientes.disabled = true;
            selectProveedores.style.display = 'none';
            selectProveedores.disabled = true;
        }
    }
</script>