<div class="row">
    <div class="col-md-12">
        <div class="card card-primary mt-2">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice"></i> Nueva Nota de Pedido (Manual)
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('public/proveedores/detalle/'.$proveedor['id']) ?>"
                        class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-truck"></i> Proveedor: <strong><?= $proveedor['razon_social'] ?></strong></h5>
                    <p class="mb-0">
                        <strong>CUIT:</strong> <?= $proveedor['cuit'] ?> |
                        <strong>Teléfono:</strong> <?= $proveedor['telefono'] ?> |
                        <strong>Email:</strong> <?= $proveedor['email'] ?>
                    </p>
                </div>

                <form id="formNotaPedido">
                    <input type="hidden" name="proveedor_id" value="<?= $proveedor['id'] ?>">
                    <input type="hidden" name="numero_comprobante" value="<?= $numero_nota ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número de Nota de Pedido</label>
                                <input type="text" class="form-control" value="<?= $numero_nota ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Carga de Ítems</h3>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <div class="form-group mb-0">
                                        <label>Descripción del Producto / Ítem</label>
                                        <input type="text" class="form-control" id="input_descripcion" 
                                               placeholder="Ej: Resma A4 80gr Ledesma" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label>Cantidad</label>
                                        <input type="number" class="form-control" id="input_cantidad" value="1" min="1" step="0.01">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label>Precio Unitario</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" class="form-control" id="input_precio" step="0.01" min="0" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success btn-block" id="btn_agregar_producto">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalle del Pedido</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover" id="tabla_productos">
                                <thead>
                                    <tr>
                                        <th>Descripción</th> <th class="text-center">Cantidad</th>
                                        <th class="text-right">Precio Unit.</th>
                                        <th class="text-right">Subtotal</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_productos">
                                    <tr id="row_vacio">
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-inbox"></i> Ingrese ítems en el formulario de arriba
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                        <td class="text-right"><strong id="total_general">$ 0,00</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3"
                            placeholder="Condiciones de entrega, forma de pago, etc."></textarea>
                    </div>

                    <div class="text-right mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Guardar Nota de Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   
    $(document).ready(function() {
        let productos = []; 

        $('#btn_agregar_producto').click(function() {
            const descripcion = $('#input_descripcion').val().trim();
            const cantidad = parseFloat($('#input_cantidad').val());
            const precio = parseFloat($('#input_precio').val());

            if (descripcion === '') {
                Swal.fire('Atención', 'Debe escribir una descripción del producto', 'warning');
                return;
            }
            if (isNaN(cantidad) || cantidad <= 0) {
                Swal.fire('Atención', 'La cantidad debe ser mayor a 0', 'warning');
                return;
            }
            if (isNaN(precio) || precio < 0) {
                Swal.fire('Atención', 'El precio no puede ser negativo o vacío', 'warning');
                return;
            }

            const subtotal = cantidad * precio;

            
           const item = {
                descripcion: descripcion,
                cantidad: cantidad,
                precio_unitario: precio,
                subtotal: subtotal,
                producto_id: null, 
                descripcion_libre: descripcion 
            };

            productos.push(item);

            renderizarTabla();
            limpiarInputs();
        });

        function renderizarTabla() {
            const tbody = $('#tbody_productos');
            tbody.empty();

            if (productos.length === 0) {
                tbody.html(`
                    <tr id="row_vacio">
                        <td colspan="5" class="text-center text-muted">
                            <i class="fas fa-inbox"></i> Ingrese ítems en el formulario de arriba
                        </td>
                    </tr>
                `);
                $('#total_general').text('$ 0,00');
                return;
            }

            let totalAcumulado = 0;

            productos.forEach((prod, index) => {
                totalAcumulado += prod.subtotal;

                const tr = `
                    <tr>
                        <td>${prod.descripcion}</td>
                        <td class="text-center">${prod.cantidad}</td>
                        <td class="text-right">$ ${prod.precio_unitario.toLocaleString('es-AR', {minimumFractionDigits: 2})}</td>
                        <td class="text-right font-weight-bold">$ ${prod.subtotal.toLocaleString('es-AR', {minimumFractionDigits: 2})}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-xs btn-eliminar" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(tr);
            });

            $('#total_general').text('$ ' + totalAcumulado.toLocaleString('es-AR', {minimumFractionDigits: 2}));
        }

        function limpiarInputs() {
            $('#input_descripcion').val('').focus();
            $('#input_cantidad').val(1);
            $('#input_precio').val('');
        }

        $(document).on('click', '.btn-eliminar', function() {
            const index = $(this).data('index');
            productos.splice(index, 1); 
            renderizarTabla(); 
        });

        $('#formNotaPedido').on('submit', function(e) {
            e.preventDefault();

            if (productos.length === 0) {
                Swal.fire('Error', 'Debe agregar al menos un producto al detalle', 'error');
                return;
            }

            const formData = new FormData(this);
            formData.append('productos', JSON.stringify(productos));

            $.ajax({
                url: '<?= base_url("public/proveedores/guardarNotaPedido") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Guardando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.msg,
                        }).then((result) => {
                            window.location.href = '<?= base_url("public/proveedores/detalle/".$proveedor["id"]) ?>';
                        });
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                        if(response.debug_sql) {
                            console.error(response.debug_sql);
                        }
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    Swal.fire('Error', 'Ocurrió un error en el servidor', 'error');
                }
            });
        });
    });
</script>