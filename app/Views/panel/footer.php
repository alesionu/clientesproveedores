</div><!-- /.container-fluid -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<footer class="main-footer">
    <strong>Copyright &copy; 2025</strong> - Alesio Nuñez
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
    </div>
</footer>
</div>
<!-- ./wrapper -->

<script src="<?= base_url('public/asset/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('public/asset/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('public/asset/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<script src="<?= base_url('public/asset/dist/js/adminlte.js') ?>"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- uso de liberia sweetalert 2 para implementar el 
 borrado en las diferentes entidades con js-->
<script src="<?= base_url('public/asset/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('public/asset/plugins/toastr/toastr.min.js') ?>"></script>

<script src="<?= base_url('public/asset/dist/js/eliminar_sweetalert.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
$(document).ready(function() {
    if ($('#id_data_table').length) {
        new DataTable('#id_data_table', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [
                [0, 'desc']
            ], 
            pageLength: 25,

            columnDefs: [{
                orderable: false,
                targets: [3] 
            }],

            layout: {
                topStart: {
                    buttons: [{
                            extend: 'csv',
                            className: 'btn btn-success btn-sm',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-primary btn-sm',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-danger btn-sm',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-info btn-sm',
                            text: '<i class="fas fa-print"></i> Imprimir',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            }
                        }
                    ]
                }
            }
        });
    }
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('graficoCaja');
    if (!canvas) {
        console.log('Canvas no encontrado en esta página');
        return;
    }

    fetch('<?= base_url("public/transacciones/totales-caja") ?>')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta: ' + response.status);
            }
            return response.json();
        })
        .then(res => {
            console.log('Datos recibidos:', res); 

            if (!res.status || !res.data) {
                throw new Error('Datos inválidos');
            }

            const ingresos = parseFloat(res.data.ingresos) || 0;
            const egresos = parseFloat(res.data.egresos) || 0;

            const ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Ingresos', 'Egresos'],
                    datasets: [{
                        data: [ingresos, egresos],
                        backgroundColor: [
                            '#28a745', // verde
                            '#fd7e14' // rojo
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#ffffff',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Caja',
                            color: '#ffffff',
                            font: {
                                size: 16, 
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    let total = context.dataset.data.reduce((a, b) => a + b,
                                        0);
                                    let percentage = total > 0 ? ((value / total) * 100)
                                        .toFixed(1) : 0;
                                    return context.label + ': $' + value.toLocaleString(
                                        'es-AR') + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

        })
        .catch(err => {
            console.error('Error cargando gráfico:', err);
            const ctx = canvas.getContext('2d');
            ctx.font = '16px Arial';
            ctx.fillStyle = '#dc3545';
            ctx.textAlign = 'center';
            ctx.fillText('Error al cargar datos', canvas.width / 2, canvas.height / 2);
        });
});
</script>

</body>

</html>