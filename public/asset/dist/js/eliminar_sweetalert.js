$(document).ready(function() {
    
    console.log("Script de borrado cargado correctamente");

    $('body').on('click', '.btn-eliminar', function(e) {
        
        e.preventDefault(); 
        
        var urlBorrar = $(this).data('url');
        var boton = $(this); 

        console.log("Intentando borrar: " + urlBorrar);

        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: urlBorrar,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                           
                            var fila = boton.closest('tr');
                            
                            var tabla = boton.closest('table').DataTable();
                            
                            if(tabla) {
                                tabla.row(fila).remove().draw();
                            } else {
                                fila.remove(); 
                            }

                            Swal.fire('Eliminado', response.msg, 'success');
                        } else {
                            Swal.fire('Error', response.msg, 'error');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        Swal.fire('Error', 'No se pudo conectar. Código: ' + xhr.status, 'error');
                        console.log(xhr.responseText); 
                    }
                });
            }
        });
    });
});