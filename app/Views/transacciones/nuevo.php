<div class="row">
    <div class="col-md-12">
        <div class="card card-success mt-2">
            <div class="card-header">
                <h3 class="card-title">Registrar Nuevo Comprobante</h3>
            </div>

            <form action="<?= base_url('public/transacciones/guardar') ?>" method="post" enctype="multipart/form-data"
                id="form_comprobante">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>¿A quién corresponde? *</label>
                                <select class="form-control" name="tipo_entidad" id="selector_tipo"
                                    onchange="cambiarLista()">
                                    <option value="cliente">Cliente (Emitimos comprobante)</option>
                                    <option value="proveedor">Proveedor (Cargamos su comprobante)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Seleccionar Persona/Empresa *</label>

                                <select class="form-control" name="id_entidad" id="lista_clientes">
                                    <option value="">-- Seleccione --</option>
                                    <?php foreach($clientes as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['razon_social'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <select class="form-control" name="id_entidad" id="lista_proveedores"
                                    style="display:none;" disabled>
                                    <option value="">-- Seleccione --</option>
                                    <?php foreach($proveedores as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['razon_social'] ?> - <?= $p['cuit'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <!-- Datos del Comprobante -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo de Comprobante *</label>
                                <select class="form-control" name="tipo_comprobante" id="tipo_comprobante"
                                    onchange="toggleProductos()">
                                    <option value="factura">Factura</option>
                                    <option value="nota_debito">Nota de Débito</option>
                                    <option value="nota_credito">Nota de Crédito</option>
                                    <option value="pago">Recibo de Pago / Cobro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Forma de pago *</label>
                                <select class="form-control" id="forma_pago_display" onchange="toggleDiasCredito()" required>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="debito">Débito</option>
                                    <option value="credito">Crédito</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="cuenta_corriente">Cuenta Corriente</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha *</label>
                                <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Campo para seleccionar días de crédito -->
                    <div class="row">
                        <div class="col-md-4" id="contenedor_dias_credito" style="display:none;">
                            <div class="form-group">
                                <label>Plazo de Crédito *</label>
                                <select class="form-control" id="select_dias_credito">
                                    <option value="">-- Seleccione --</option>
                                    <option value="30">30 días</option>
                                    <option value="60">60 días</option>
                                    <option value="90">90 días</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN PARA CLIENTES: Productos y emisión de factura -->
                    <div id="seccion_clientes">
                        <div id="seccion_productos">
                            <div class="dropdown-divider"></div>
                            <h5 class="mb-3">Productos / Servicios</h5>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Seleccionar Producto</label>
                                        <select class="form-control" id="select_producto">
                                            <option value="">-- Seleccione --</option>
                                            <?php foreach($productos as $prod): ?>
                                            <option value="<?= $prod['id'] ?>" data-nombre="<?= $prod['nombre'] ?>"
                                                data-precio="<?= $prod['precio'] ?>">
                                                <?= $prod['codigo'] ?> - <?= $prod['nombre'] ?> ($
                                                <?= number_format($prod['precio'], 2) ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="number" class="form-control" id="input_cantidad" value="1" min="0"
                                            step="1">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Precio Unit.</label>
                                        <input type="number" class="form-control" id="input_precio" step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block"
                                            onclick="agregarProducto()">
                                            <i class="fas fa-plus"></i> Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="tabla_items">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th width="120">Cantidad</th>
                                            <th width="150">Precio Unit.</th>
                                            <th width="150">Subtotal</th>
                                            <th width="80">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_items">
                                        <tr id="fila_vacia">
                                            <td colspan="5" class="text-center text-muted">No hay productos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                            <td><strong id="total_general">$ 0.00</strong></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="form-group" id="campo_monto_cliente" style="display:none;">
                            <label>Monto Total ($) *</label>
                            <input type="number" step="0.01" class="form-control" id="input_monto_cliente"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- SECCIÓN PARA PROVEEDORES: Carga simple de PDF y monto -->
                    <div id="seccion_proveedores" style="display:none;">
                        <div class="dropdown-divider"></div>

                        

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Monto Total del Comprobante ($) *</label>
                                    <input type="number" step="0.01" class="form-control" id="input_monto_proveedor" 
                                        placeholder="0.00" min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cargar PDF del Comprobante</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="archivo_pdf" name="archivo_pdf"
                                            accept=".pdf">
                                        <label class="custom-file-label" for="archivo_pdf">Seleccionar archivo
                                            PDF...</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Opcional. Formato PDF, máximo 5MB.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CAMPOS OCULTOS QUE SE ENVÍAN AL SERVIDOR -->
                    <input type="hidden" name="monto" id="monto_final">
                    <input type="hidden" name="forma_pago" id="forma_pago_final">
                    <input type="hidden" name="productos_json" id="productos_json">

                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3"
                            placeholder="Detalles adicionales..."></textarea>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Comprobante
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
let items = [];
let itemIndex = 0;

function cambiarLista() {
    var tipo = document.getElementById('selector_tipo').value;
    var selectClientes = document.getElementById('lista_clientes');
    var selectProveedores = document.getElementById('lista_proveedores');
    var seccionClientes = document.getElementById('seccion_clientes');
    var seccionProveedores = document.getElementById('seccion_proveedores');

    if (tipo === 'cliente') {
        selectClientes.style.display = 'block';
        selectClientes.disabled = false;
        selectProveedores.style.display = 'none';
        selectProveedores.disabled = true;
        
        seccionClientes.style.display = 'block';
        seccionProveedores.style.display = 'none';
        
        document.getElementById('input_monto_proveedor').value = '';
        document.getElementById('archivo_pdf').value = '';
    } else {
        selectProveedores.style.display = 'block';
        selectProveedores.disabled = false;
        selectClientes.style.display = 'none';
        selectClientes.disabled = true;
        
        seccionClientes.style.display = 'none';
        seccionProveedores.style.display = 'block';
        
        items = [];
        document.getElementById('tbody_items').innerHTML =
            '<tr id="fila_vacia"><td colspan="5" class="text-center text-muted">No hay productos agregados</td></tr>';
        actualizarTotal();
    }
}

function toggleProductos() {
    const tipo = document.getElementById('tipo_comprobante').value;
    const tipoEntidad = document.getElementById('selector_tipo').value;
    const seccionProductos = document.getElementById('seccion_productos');
    const campoMontoCliente = document.getElementById('campo_monto_cliente');

    if (tipoEntidad === 'cliente') {
        if (tipo === 'factura') {
            seccionProductos.style.display = 'block';
            campoMontoCliente.style.display = 'none';
        } else {
            seccionProductos.style.display = 'none';
            campoMontoCliente.style.display = 'block';
        }
    }
}

function toggleDiasCredito() {
    const formaPago = document.getElementById('forma_pago_display').value;
    const contenedorDias = document.getElementById('contenedor_dias_credito');
    const selectDias = document.getElementById('select_dias_credito');
    
    if (formaPago === 'credito') {
        contenedorDias.style.display = 'block';
        selectDias.setAttribute('required', 'required');
    } else {
        contenedorDias.style.display = 'none';
        selectDias.removeAttribute('required');
        selectDias.value = '';
    }
}

document.getElementById('archivo_pdf').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Seleccionar archivo PDF...';
    const label = document.querySelector('.custom-file-label');
    label.textContent = fileName;
});

document.getElementById('select_producto').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const precio = selected.getAttribute('data-precio');
    document.getElementById('input_precio').value = precio || '';
});

function agregarProducto() {
    const select = document.getElementById('select_producto');
    const selectedOption = select.options[select.selectedIndex];

    if (!select.value) {
        alert('Seleccione un producto');
        return;
    }

    const productoId = select.value;
    const nombre = selectedOption.getAttribute('data-nombre');
    const cantidad = parseFloat(document.getElementById('input_cantidad').value) || 1;
    const precio = parseFloat(document.getElementById('input_precio').value) || 0;
    const subtotal = cantidad * precio;

    items.push({
        index: itemIndex,
        producto_id: productoId,
        nombre: nombre,
        cantidad: cantidad,
        precio_unitario: precio,
        subtotal: subtotal
    });

    const tbody = document.getElementById('tbody_items');
    document.getElementById('fila_vacia').style.display = 'none';

    const row = tbody.insertRow();
    row.id = 'item_' + itemIndex;
    row.innerHTML = `
        <td>${nombre}</td>
        <td class="text-center">${cantidad}</td>
        <td class="text-right">$ ${precio.toFixed(2)}</td>
        <td class="text-right">$ ${subtotal.toFixed(2)}</td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarItem(${itemIndex})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    itemIndex++;
    actualizarTotal();

    select.value = '';
    document.getElementById('input_cantidad').value = 1;
    document.getElementById('input_precio').value = '';
}

function eliminarItem(index) {
    items = items.filter(item => item.index !== index);
    document.getElementById('item_' + index).remove();

    if (items.length === 0) {
        document.getElementById('fila_vacia').style.display = '';
    }

    actualizarTotal();
}

function actualizarTotal() {
    const total = items.reduce((sum, item) => sum + item.subtotal, 0);
    document.getElementById('total_general').textContent = '$ ' + total.toFixed(2);
}

// EVENT LISTENER PARA SUBMIT - CORREGIDO
document.getElementById('form_comprobante').addEventListener('submit', function(e) {
    const tipoComprobante = document.getElementById('tipo_comprobante').value;
    const tipoEntidad = document.getElementById('selector_tipo').value;
    const idEntidad = document.querySelector('select[name="id_entidad"]:not([disabled])').value;

    // Validar entidad seleccionada
    if (!idEntidad) {
        e.preventDefault();
        alert('Debe seleccionar un cliente o proveedor');
        return false;
    }

    // CALCULAR Y ASIGNAR FORMA DE PAGO FINAL
    const formaPagoDisplay = document.getElementById('forma_pago_display').value;
    let formaPagoFinal = formaPagoDisplay;
    
    if (formaPagoDisplay === 'credito') {
        const diasCredito = document.getElementById('select_dias_credito').value;
        if (!diasCredito) {
            e.preventDefault();
            alert('Debe seleccionar el plazo de crédito (30, 60 o 90 días)');
            return false;
        }
        formaPagoFinal = 'credito_' + diasCredito;
    }
    
    // Asignar al campo oculto que se enviará
    document.getElementById('forma_pago_final').value = formaPagoFinal;

    // Validaciones según tipo de entidad
    if (tipoEntidad === 'cliente') {
        if (tipoComprobante === 'factura') {
            if (items.length === 0) {
                e.preventDefault();
                alert('Debe agregar al menos un producto a la factura');
                return false;
            }

            const itemsParaEnviar = items.map(item => ({
                producto_id: item.producto_id,
                cantidad: item.cantidad,
                precio_unitario: item.precio_unitario,
                subtotal: item.subtotal
            }));

            document.getElementById('productos_json').value = JSON.stringify(itemsParaEnviar);
            
            // Calcular monto total de productos
            const total = items.reduce((sum, item) => sum + item.subtotal, 0);
            document.getElementById('monto_final').value = total.toFixed(2);
        } else {
            const montoCliente = document.getElementById('input_monto_cliente').value;
            if (!montoCliente || parseFloat(montoCliente) <= 0) {
                e.preventDefault();
                alert('Debe ingresar un monto válido');
                return false;
            }
            document.getElementById('monto_final').value = montoCliente;
        }
    } else {
        const montoProveedor = document.getElementById('input_monto_proveedor').value;
        if (!montoProveedor || parseFloat(montoProveedor) <= 0) {
            e.preventDefault();
            alert('Debe ingresar el monto del comprobante del proveedor');
            return false;
        }
        document.getElementById('monto_final').value = montoProveedor;
    }
});

// Inicializar al cargar la página
toggleProductos();
toggleDiasCredito();
</script>

<style>
.custom-file-label::after {
    content: "Buscar";
}
</style>