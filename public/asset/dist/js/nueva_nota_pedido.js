// Espera a que el DOM esté completamente cargado
$(document).ready(function () {

    // Mensaje de depuración para confirmar que el script se cargó
    console.log('Script cargado correctamente');

    // Array donde se almacenan los productos agregados a la nota de pedido
    let productosAgregados = [];

    // Contador para asignar un ID único a cada fila/producto agregado
    let contadorFilas = 0;

    
    // Cuando cambia el producto seleccionado en el select
    $('#select_producto').on('change', function () {

        // Obtiene el precio desde el atributo data-precio del option seleccionado
        const precio = parseFloat($(this).find(':selected').data('precio')) || 0;

        // Carga el precio en el input correspondiente con dos decimales
        $('#input_precio').val(precio.toFixed(2));

        console.log('Precio cargado:', precio);
    });


    // Evento click del botón "Agregar producto"
    $('#btn_agregar_producto').on('click', function () {

        console.log('Botón agregar clickeado');

        // Referencia al select de productos
        const select = $('#select_producto');

        // Opción seleccionada del select
        const selectedOption = select.find(':selected');

        // ID del producto seleccionado
        const productoId = select.val();

        // Validación: debe seleccionar un producto
        if (!productoId) {
            Swal.fire('Atención', 'Seleccione un producto de la lista', 'warning');
            return;
        }

        // Obtiene datos del producto desde atributos data-*
        const codigo = selectedOption.data('codigo');
        const nombre = selectedOption.data('nombre');

        // Obtiene la cantidad ingresada
        const cantidad = parseFloat($('#input_cantidad').val());

        // Obtiene el precio unitario ingresado
        const precioUnitario = parseFloat($('#input_precio').val());

        // Validación de cantidad
        if (isNaN(cantidad) || cantidad <= 0) {
            Swal.fire('Error', 'La cantidad debe ser mayor a 0', 'error');
            return;
        }

        // Validación de precio
        if (isNaN(precioUnitario) || precioUnitario < 0) {
            Swal.fire('Error', 'El precio no es válido', 'error');
            return;
        }

        // Calcula el subtotal (cantidad x precio unitario)
        const subtotal = cantidad * precioUnitario;

        // Agrega el producto al array de productos
        productosAgregados.push({
            id: contadorFilas++,        // ID interno para control de filas
            producto_id: productoId,    // ID real del producto
            codigo: codigo,             // Código del producto
            nombre: nombre,             // Nombre del producto
            cantidad: cantidad,         // Cantidad solicitada
            precio_unitario: precioUnitario, // Precio unitario
            subtotal: subtotal          // Subtotal calculado
        });

        // Debug
        console.log('Producto agregado:', productosAgregados);

        // Actualiza la tabla HTML con los productos agregados
        actualizarTabla();

        // Resetea los campos del formulario para el próximo producto
        select.val('');
        $('#input_cantidad').val(1);
        $('#input_precio').val('');
        select.focus();
    });

    
    // Evento delegado para eliminar un producto de la lista
    $(document).on('click', '.btn-eliminar-producto', function () {

        // Obtiene el ID interno del producto a eliminar
        const id = $(this).data('id');

        // Filtra el array y elimina el producto seleccionado
        productosAgregados = productosAgregados.filter(p => p.id !== id);

        // Actualiza la tabla
        actualizarTabla();
    });

    // Función que dibuja la tabla con los productos agregados
    function actualizarTabla() {

        // Referencia al tbody de la tabla
        const tbody = $('#tbody_productos');

        // Limpia el contenido actual de la tabla
        tbody.empty();

        // Si no hay productos, muestra mensaje vacío
        if (productosAgregados.length === 0) {

            tbody.html(`
                <tr id="row_vacio">
                    <td colspan="6" class="text-center text-muted">
                        <i class="fas fa-inbox"></i> No hay productos agregados
                    </td>
                </tr>
            `);

            // Resetea el total general
            $('#total_general').text('$ 0,00');
            return;
        }

        // Variable para acumular el total general
        let total = 0;

        // Recorre todos los productos agregados
        productosAgregados.forEach(prod => {

            // Suma el subtotal al total general
            total += prod.subtotal;

            // Agrega una fila a la tabla
            tbody.append(`
                <tr>
                    <td>${prod.codigo}</td>
                    <td>${prod.nombre}</td>
                    <td class="text-center">${prod.cantidad}</td>
                    <td class="text-right">$ ${prod.precio_unitario.toFixed(2)}</td>
                    <td class="text-right">$ ${prod.subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button"
                                class="btn btn-danger btn-sm btn-eliminar-producto"
                                data-id="${prod.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        // Muestra el total general final
        $('#total_general').text('$ ' + total.toFixed(2));
    }

    $('#formNotaPedido').on('submit', function (e) {

        // Evita el envío tradicional del formulario
        e.preventDefault();

        console.log('Formulario enviado');

        // Validación: debe haber al menos un producto
        if (productosAgregados.length === 0) {
            Swal.fire('Error', 'Debe agregar al menos un producto a la lista', 'error');
            return;
        }

        // Crea un FormData con los datos del formulario
        const formData = new FormData(this);

        // Agrega el array de productos convertido a JSON
        formData.append('productos', JSON.stringify(productosAgregados));

        // Debug de datos enviados
        console.log('Datos a enviar:', {
            proveedor_id: formData.get('proveedor_id'),
            numero_comprobante: formData.get('numero_comprobante'),
            fecha: formData.get('fecha'),
            observaciones: formData.get('observaciones'),
            productos: productosAgregados
        });

        // Envío AJAX al backend
        $.ajax({
            url: BASE_URL + 'public/proveedores/guardarNotaPedido',
            type: 'POST',
            data: formData,
            processData: false, // Necesario para FormData
            contentType: false, // Necesario para FormData

            // Antes de enviar, deshabilita el botón
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            },

            // Respuesta exitosa del servidor
            success: function (response) {

                console.log('Respuesta del servidor:', response);

                // Si la respuesta viene como string, intenta parsearla a JSON
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        Swal.fire('Error', 'Respuesta inválida del servidor', 'error');
                        restaurarBoton();
                        return;
                    }
                }

                // Si el backend devuelve error
                if (!response.success) {
                    Swal.fire('Error', response.msg || 'Error desconocido', 'error');
                    restaurarBoton();
                    return;
                }

                // Mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: response.msg,
                    confirmButtonText: 'Aceptar'
                }).then(() => {

                    // Abre la nota de pedido generada en una nueva pestaña
                    if (response.transaccion_id) {
                        window.open(
                            BASE_URL + 'public/proveedores/verNotaPedido/' + response.transaccion_id,
                            '_blank'
                        );
                    }

                    // Redirige al detalle del proveedor
                    window.location.href =
                        BASE_URL + 'public/proveedores/detalle/' + PROVEEDOR_ID;
                });
            },

            // Error de AJAX
            error: function (xhr) {
                console.error('Error AJAX:', xhr.responseText);
                Swal.fire('Error', 'Error de servidor. Ver consola.', 'error');
                restaurarBoton();
            }
        });
    });

    
    function restaurarBoton() {
        $('button[type="submit"]')
            .prop('disabled', false)
            .html('<i class="fas fa-save"></i> Guardar Nota de Pedido');
    }
});
