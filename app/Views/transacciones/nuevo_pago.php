<div class="row">
    <div class="col-md-12">
        <div class="card card-info mt-2">
            <div class="card-header">
                <h3 class="card-title">Registrar Pago o Cobro</h3>
            </div>

            <form action="<?= base_url('public/transacciones/guardar-pago') ?>" method="post">
                <div class="card-body">
                    <!-- Reemplaza el input hidden por este -->
                    <input type="hidden" name="comprobantes_ids" id="hidden_comprobantes_ids" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de Operación</label>
                                <select class="form-control" name="tipo_operacion" id="selector_operacion"
                                    onchange="cambiarOperacion()" required>
                                    <option value="">-- Seleccionar --</option>
                                    <option value="cobro">Cobro (recibimos dinero de un cliente)</option>
                                    <option value="pago">Pago (pagamos a un proveedor)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="label_entidad">Seleccionar Persona/Empresa</label>

                                <select class="form-control" name="id_entidad" id="lista_clientes" style="display:none;"
                                    disabled onchange="buscarPendientes()">
                                    <option value="">-- Seleccionar Cliente --</option>
                                    <?php foreach($clientes as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['razon_social'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <select class="form-control" name="id_entidad" id="lista_proveedores"
                                    style="display:none;" disabled onchange="buscarPendientes()">
                                    <option value="">-- Seleccionar Proveedor --</option>
                                    <?php foreach($proveedores as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['razon_social'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <div id="mensaje_inicial" class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> Primero seleccione el tipo de operación
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="seccion_pendientes" style="display: none;" class="mt-3 mb-3">
                        <h5 class="text-secondary">Documentos Pendientes</h5>
                        <div class="table-responsive"
                            style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd;">
                            <table class="table table-sm table-hover table-head-fixed">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">#</th>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>N° Comprobante</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_pendientes">
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">* Seleccione los comprobantes que desea cancelar con este
                            pago.</small>
                    </div>

                    <div class="dropdown-divider"></div>
<!-- Detalles del Pago/Cobro 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Forma de pago</label>
                                <select class="form-control" name="forma_pago" id="forma_pago">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                    <option value="debito">Tarjeta de Débito</option>
                                    <option value="credito">Tarjeta de Crédito</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="ctacte">Cuenta Corriente</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Operación</label>
                                <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Monto a Pagar/Cobrar ($)</label>
                                <input min="0" type="number" step="0.01" class="form-control font-weight-bold"
                                    style="font-size: 1.2rem;" name="monto" id="input_monto" placeholder="0.00"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="input_observaciones" rows="3"
                            placeholder="Detalles del pago/cobro..."></textarea>
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
<script src="<?= base_url('public/asset/dist/js/nuevopago.js') ?>"></script>