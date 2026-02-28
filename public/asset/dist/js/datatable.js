
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
