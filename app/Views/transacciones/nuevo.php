<div class="row">
    <div class="col-md-12">
        <div class="card card-success mt-2">
            <div class="card-header">
                <h3 class="card-title">Registrar Nuevo Comprobante</h3>
            </div>
            
            <form action="<?= base_url('public/transacciones/guardar') ?>" method="post" id="form_comprobante">
                <div class="card-body">
                    
                    <!-- Selección de Tipo de Entidad -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>¿A quién corresponde?</label>
                                <select class="form-control" name="tipo_entidad" id="selector_tipo" onchange="cambiarLista()">
                                    <option value="cliente">Cliente</option>
                                    <option value="proveedor">Proveedor</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Seleccionar Persona/Empresa</label>
                                
                                <select class="form-control" name="id_entidad" id="lista_clientes">
                                    <?php foreach($clientes as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= $c['razon_social'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <select class="form-control" name="id_entidad" id="lista_proveedores" style="display:none;" disabled>
                                    <?php foreach($proveedores as $p): ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['razon_social'] ?></option>
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
                                <label>Tipo de Comprobante</label>
                                <select class="form-control" name="tipo_comprobante" id="tipo_comprobante" onchange="toggleProductos()">
                                    <option value="factura">Factura</option>
                                    <option value="nota_debito">Nota de Débito</option>
                                    <option value="nota_credito">Nota de Crédito</option>
                                    <option value="pago">Recibo de Pago / Cobro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° Comprobante</label>
                                <input type="text" class="form-control" name="numero_comprobante" placeholder="Ej: 0001-00001234" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE PRODUCTOS (Solo para facturas) -->
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
                                            <option value="<?= $prod['id'] ?>" 
                                                    data-nombre="<?= $prod['nombre'] ?>"
                                                    data-precio="<?= $prod['precio'] ?>">
                                                <?= $prod['codigo'] ?> - <?= $prod['nombre'] ?> ($ <?= number_format($prod['precio'], 2) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" class="form-control" id="input_cantidad" value="1" min="0.01" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio Unit.</label>
                                    <input type="number" class="form-control" id="input_precio" step="0.01" min="0" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary btn-block" onclick="agregarProducto()">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Productos Agregados -->
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
                                        <td colspan="5" class="text-center text-muted">No hay productos agregados</td>
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

                    <!-- Campo de Monto (para otros comprobantes) -->
                    <div class="form-group" id="campo_monto" style="display:none;">
                        <label>Monto Total ($)</label>
                        <input type="number" step="0.01" class="form-control" name="monto" id="input_monto_manual" placeholder="0.00">
                    </div>

                    <!-- Campo oculto para enviar monto calculado -->
                    <input type="hidden" name="monto" id="monto_final">
                    
                    <!-- Campo oculto para enviar productos en JSON -->
                    <input type="hidden" name="productos_json" id="productos_json">

                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Detalles adicionales..."></textarea>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Guardar Comprobante</button>
                    <a href="<?= base_url('public/transacciones') ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let items = [];
let itemIndex = 0;

// Cambiar entre cliente/proveedor
function cambiarLista() {
    var tipo = document.getElementById('selector_tipo').value;
    var selectClientes = document.getElementById('lista_clientes');
    var selectProveedores = document.getElementById('lista_proveedores');

    if (tipo === 'cliente') {
        selectClientes.style.display = 'block';
        selectClientes.disabled = false;
        selectProveedores.style.display = 'none';
        selectProveedores.disabled = true;
    } else {
        selectProveedores.style.display = 'block';
        selectProveedores.disabled = false;
        selectClientes.style.display = 'none';
        selectClientes.disabled = true;
    }
}

// Mostrar/ocultar sección de productos según tipo de comprobante
function toggleProductos() {
    const tipo = document.getElementById('tipo_comprobante').value;
    const seccionProductos = document.getElementById('seccion_productos');
    const campoMonto = document.getElementById('campo_monto');
    
    if (tipo === 'factura') {
        seccionProductos.style.display = 'block';
        campoMonto.style.display = 'none';
    } else {
        seccionProductos.style.display = 'none';
        campoMonto.style.display = 'block';
    }
}

// Cargar precio cuando se selecciona un producto
document.getElementById('select_producto').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const precio = selected.getAttribute('data-precio');
    document.getElementById('input_precio').value = precio || '';
});

// Agregar producto a la tabla
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
    
    // Agregar al array
    items.push({
        index: itemIndex,
        producto_id: productoId,
        nombre: nombre,
        cantidad: cantidad,
        precio_unitario: precio,
        subtotal: subtotal
    });
    
    // Agregar a la tabla
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
    
    // Limpiar campos
    select.value = '';
    document.getElementById('input_cantidad').value = 1;
    document.getElementById('input_precio').value = '';
}

// Eliminar un item
function eliminarItem(index) {
    items = items.filter(item => item.index !== index);
    document.getElementById('item_' + index).remove();
    
    if (items.length === 0) {
        document.getElementById('fila_vacia').style.display = '';
    }
    
    actualizarTotal();
}

// Actualizar el total
function actualizarTotal() {
    const total = items.reduce((sum, item) => sum + item.subtotal, 0);
    document.getElementById('total_general').textContent = '$ ' + total.toFixed(2);
    document.getElementById('monto_final').value = total.toFixed(2);
}

// Al enviar el formulario
document.getElementById('form_comprobante').addEventListener('submit', function(e) {
    const tipoComprobante = document.getElementById('tipo_comprobante').value;
    
    if (tipoComprobante === 'factura') {
        if (items.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto a la factura');
            return false;
        }
        
        // Convertir items a JSON (sin el index que es solo para control interno)
        const itemsParaEnviar = items.map(item => ({
            producto_id: item.producto_id,
            cantidad: item.cantidad,
            precio_unitario: item.precio_unitario,
            subtotal: item.subtotal
        }));
        
        document.getElementById('productos_json').value = JSON.stringify(itemsParaEnviar);
    } else {
        // Para otros comprobantes, usar el monto manual
        const montoManual = document.getElementById('input_monto_manual').value;
        document.getElementById('monto_final').value = montoManual;
    }
});

// Inicializar
toggleProductos();
</script>