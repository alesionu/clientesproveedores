// Cambia el comportamiento del formulario según el tipo de operación (cobro o pago)
function cambiarOperacion() {

    // Obtiene el tipo de operación seleccionada
    var tipo = document.getElementById('selector_operacion').value;

    // Referencias a los selects y elementos del formulario
    var selectClientes = document.getElementById('lista_clientes');
    var selectProveedores = document.getElementById('lista_proveedores');
    var mensajeInicial = document.getElementById('mensaje_inicial');
    var labelEntidad = document.getElementById('label_entidad');

    // Limpia selecciones anteriores
    selectClientes.value = "";
    selectProveedores.value = "";

    // Oculta la sección de comprobantes pendientes
    document.getElementById('seccion_pendientes').style.display = 'none';

    // Limpia el monto ingresado
    document.getElementById('input_monto').value = '';

    // Oculta el mensaje inicial
    mensajeInicial.style.display = 'none';

    // Si la operación es un cobro
    if (tipo === 'cobro') {

        // Muestra y habilita el selector de clientes
        selectClientes.style.display = 'block';
        selectClientes.disabled = false;
        selectClientes.required = true;

        // Oculta y deshabilita el selector de proveedores
        selectProveedores.style.display = 'none';
        selectProveedores.disabled = true;
        selectProveedores.required = false;

        // Cambia el texto e ícono del label
        labelEntidad.innerHTML = '<i class="fas fa-user text-primary"></i> Seleccionar Cliente';

    // Si la operación es un pago
    } else if (tipo === 'pago') {

        // Muestra y habilita el selector de proveedores
        selectProveedores.style.display = 'block';
        selectProveedores.disabled = false;
        selectProveedores.required = true;

        // Oculta y deshabilita el selector de clientes
        selectClientes.style.display = 'none';
        selectClientes.disabled = true;
        selectClientes.required = false;

        // Cambia el texto e ícono del label
        labelEntidad.innerHTML = '<i class="fas fa-truck text-warning"></i> Seleccionar Proveedor';

    // Si no se seleccionó ninguna operación
    } else {
        mensajeInicial.style.display = 'block';

        selectClientes.style.display = 'none';
        selectClientes.disabled = true;

        selectProveedores.style.display = 'none';
        selectProveedores.disabled = true;
    }
}


// Busca comprobantes pendientes según cliente o proveedor
function buscarPendientes() {

    // Obtiene el tipo de operación actual
    const tipoOperacion = document.getElementById('selector_operacion').value;
    let idEntidad = '';

    // Obtiene el ID del cliente o proveedor según la operación
    if (tipoOperacion === 'cobro') {
        idEntidad = document.getElementById('lista_clientes').value;
    } else {
        idEntidad = document.getElementById('lista_proveedores').value;
    }

    console.log('Tipo:', tipoOperacion, 'Entidad:', idEntidad);

    // Si no hay entidad seleccionada, oculta la sección y corta la ejecución
    if (!idEntidad) {
        document.getElementById('seccion_pendientes').style.display = 'none';
        return;
    }

    // Obtiene la URL base desde el meta tag o el dominio actual
    const baseUrl = document.querySelector('meta[name="base-url"]')?.content || window.location.origin;

    // Endpoint para obtener deudas
    const url = baseUrl + 'public/transacciones/obtener-deuda';

    // Prepara los datos a enviar
    const formData = new FormData();
    formData.append('tipo_operacion', tipoOperacion);
    formData.append('id_entidad', idEntidad);

    console.log('Enviando petición a:', url);

    // Petición AJAX usando fetch
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);

        // Si la respuesta no es correcta, lanza un error
        if (!response.ok) {
            throw new Error("Error en la petición: " + response.statusText);
        }

        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data);

        // Cuerpo de la tabla de pendientes
        const tbody = document.getElementById('tbody_pendientes');
        tbody.innerHTML = '';

        // Si hay comprobantes pendientes
        if (data.status && data.data && data.data.length > 0) {

            // Recorre cada comprobante
            data.data.forEach(item => {

                // Formatea la fecha a dd/mm/yyyy
                const [anio, mes, dia] = item.fecha.split('-');
                const fechaFormateada = `${dia}/${mes}/${anio}`;

                // Define el tipo de comprobante
                const tipo = item.tipo_comprobante === 'factura'
                    ? '<span class="badge badge-primary">Factura</span>'
                    : '<span class="badge badge-info">N. Débito</span>';

                // Arma la fila de la tabla con checkbox
                const row = `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="check-pago"
                                value="${item.id}"
                                data-monto="${item.monto}"
                                data-numero="${item.numero_comprobante}"
                                onchange="calcularTotal()">
                        </td>
                        <td>${fechaFormateada}</td>
                        <td>${tipo}</td>
                        <td>${item.numero_comprobante}</td>
                        <td class="text-right font-weight-bold">$ ${parseFloat(item.monto).toFixed(2)}</td>
                    </tr>
                `;

                // Agrega la fila a la tabla
                tbody.innerHTML += row;
            });

            // Muestra la sección de pendientes
            document.getElementById('seccion_pendientes').style.display = 'block';

        } else {
            // Si no hay comprobantes
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No se encontraron comprobantes pendientes.
                    </td>
                </tr>
            `;
            document.getElementById('seccion_pendientes').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        alert('Error al cargar pendientes: ' + error.message);
    });
}


// Calcula el total según los comprobantes seleccionados
function calcularTotal() {

    // Obtiene todos los checkboxes tildados
    const checkboxes = document.querySelectorAll('.check-pago:checked');

    let total = 0;
    let referencias = [];
    let ids = [];

    // Recorre los checkboxes seleccionados
    checkboxes.forEach(chk => {
        total += parseFloat(chk.getAttribute('data-monto'));
        referencias.push(chk.getAttribute('data-numero'));
        ids.push(chk.value);
    });

    // Actualiza el monto total
    document.getElementById('input_monto').value = total.toFixed(2);

    // Guarda los IDs de los comprobantes seleccionados
    document.getElementById('hidden_comprobantes_ids').value = ids.join(',');

    // Completa automáticamente las observaciones
    if (referencias.length > 0) {
        document.getElementById('input_observaciones').value =
            "Pago de comprobantes: " + referencias.join(", ");
    } else {
        document.getElementById('input_observaciones').value = "";
    }
}
