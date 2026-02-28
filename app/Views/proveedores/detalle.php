<div class="row">
    <div class="col-md-12">
        <div class="card card-warning mt-2">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-truck"></i>
                    <?= $proveedor['razon_social'] ?>
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('proveedores') ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="card-tools mb-3">
                    <a href="<?= base_url('public/proveedores/nuevaNotaPedido/'.$proveedor['id']) ?>"
                        class="btn btn-sm btn-success">
                        <i class="fas fa-file-invoice"></i> Nueva Nota de Pedido
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <strong><i class="fas fa-id-card"></i> CUIT:</strong><br>
                        <?= $proveedor['cuit'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-envelope"></i> Email:</strong><br>
                        <?= $proveedor['email'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-phone"></i> Teléfono:</strong><br>
                        <?= $proveedor['telefono'] ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-map"></i> Provincia:</strong><br>
                        <?= $proveedor['provincia'] ?>
                    </div>
                    <div class="col-md-3 mt-2">
                        <strong><i class="fas fa-building"></i> Ciudad:</strong><br>
                        <?= $proveedor['ciudad'] ?>
                    </div>
                    <div class="col-md-3 mt-2">
                        <strong><i class="fas fa-city"></i> CP:</strong><br>
                        <?= $proveedor['codigo_postal'] ?>
                    </div>
                    <div class="col-md-3 mt-2">
                        <strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong><br>
                        <?= $proveedor['direccion'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div
                    class="info-box <?= $saldo_actual > 0 ? 'bg-danger' : ($saldo_actual < 0 ? 'bg-info' : 'bg-success') ?>">
                    <span class="info-box-icon">
                        <i class="fas fa-balance-scale"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Saldo Actual</span>
                        <span class="info-box-number" style="font-size: 2rem;">
                            $ <?= number_format(abs($saldo_actual), 2, ',', '.') ?>
                        </span>
                        <span class="progress-description">
                            <?php if ($saldo_actual > 0): ?>
                            Debemos dinero al proveedor
                            <?php elseif ($saldo_actual < 0): ?>
                            Saldo a favor nuestro
                            <?php else: ?>
                            Cuenta saldada
                            <?php endif; ?>
                        </span>
                    </div>



                </div>
            </div>
        </div>
    </div><!-- Sección Lista de Precios - Reemplazar en detalle.php -->
    <div class="col-md-12 mt-3">
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-pdf"></i> Lista de Precios Vigente
                </h3>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Columna 1: Estado actual -->
                    <div class="col-md-4">
                        <?php if (!empty($proveedor['lista_precios_pdf'])): ?>
                        <div class="text-center">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0"><strong>Lista Cargada</strong></p>
                            <small class="text-muted">Archivo disponible</small>
                        </div>
                        <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0"><strong>Sin Lista</strong></p>
                            <small class="text-muted">No hay archivo cargado</small>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Columna 2: Botón de visualización -->
                    <div class="col-md-4 text-center">
                        <?php if (!empty($proveedor['lista_precios_pdf'])): ?>
                        <a href="<?= base_url('public/uploads/listas_precios/' . $proveedor['lista_precios_pdf']) ?>"
                            target="_blank" class="btn btn-danger btn-lg">
                            <i class="fas fa-file-pdf"></i> Ver Lista Actual
                        </a>
                        <br>
                        <a href="<?= base_url('public/uploads/listas_precios/' . $proveedor['lista_precios_pdf']) ?>" download
                            class="btn btn-outline-danger btn-sm mt-2">
                            <i class="fas fa-download"></i> Descargar
                        </a>
                        <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-ban"></i> Sin archivo disponible
                        </button>
                        <br>
                        <small class="text-muted d-block mt-2">Cargue un PDF para visualizar</small>
                        <?php endif; ?>
                    </div>

                    <!-- Columna 3: Formulario de carga -->
                    <div class="col-md-4">
                        <form action="<?= base_url('public/proveedores/subirListaPrecios') ?>" method="post"
                            enctype="multipart/form-data" id="formListaPrecios">

                            <input type="hidden" name="id_proveedor" value="<?= $proveedor['id'] ?>">

                            <div class="form-group">
                                <label for="archivo_lista">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <?= !empty($proveedor['lista_precios_pdf']) ? 'Actualizar Lista de Precios' : 'Cargar Lista de Precios' ?>
                                </label>

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="archivo_lista" id="archivo_lista"
                                        accept="application/pdf" required>
                                    <label class="custom-file-label" for="archivo_lista" id="labelArchivo">
                                        Seleccionar PDF...
                                    </label>
                                </div>

                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Solo archivos PDF, máximo 10MB
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" id="btnSubir">
                                <i class="fas fa-upload"></i>
                                <?= !empty($proveedor['lista_precios_pdf']) ? 'Actualizar PDF' : 'Subir PDF' ?>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Barra de progreso (oculta por defecto) -->
                <div class="row mt-3" id="progressContainer" style="display: none;">
                    <div class="col-md-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: 0%" id="progressBar">0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para mejorar la UX -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Actualizar label del input file cuando se selecciona archivo
        const inputArchivo = document.getElementById('archivo_lista');
        const labelArchivo = document.getElementById('labelArchivo');

        inputArchivo.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Seleccionar PDF...';
            labelArchivo.textContent = fileName;

            // Validar tamaño (10MB máximo)
            const maxSize = 10 * 1024 * 1024; // 10MB en bytes
            if (e.target.files[0] && e.target.files[0].size > maxSize) {
                alert('El archivo es demasiado grande. Máximo 10MB');
                inputArchivo.value = '';
                labelArchivo.textContent = 'Seleccionar PDF...';
            }
        });

        // Mostrar progreso al enviar formulario
        const form = document.getElementById('formListaPrecios');
        const btnSubir = document.getElementById('btnSubir');
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.getElementById('progressBar');

        form.addEventListener('submit', function(e) {
            // Validar que hay archivo seleccionado
            if (!inputArchivo.files.length) {
                e.preventDefault();
                alert('Debe seleccionar un archivo PDF');
                return;
            }

            // Mostrar progreso
            btnSubir.disabled = true;
            btnSubir.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subiendo...';
            progressContainer.style.display = 'block';

            // Simular progreso (ya que el formulario se envía normalmente)
            let progress = 0;
            const interval = setInterval(function() {
                progress += 10;
                progressBar.style.width = progress + '%';
                progressBar.textContent = progress + '%';

                if (progress >= 90) {
                    clearInterval(interval);
                }
            }, 200);
        });
    });
    </script>

    <style>
    /* Estilos adicionales para mejorar la apariencia */
    .custom-file-label::after {
        content: "Examinar";
    }

    #progressContainer {
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .btn-lg {
        padding: 12px 30px;
        font-size: 1.1rem;
    }
    </style>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i> Historial de Movimientos
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover" id="id_data_table">
                    <thead>
                        <tr>
                            <th>Ver</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>N° Comprobante</th>
                            <th>Observaciones</th>
                            <th class="text-right">Debe</th>
                            <th class="text-right">Haber</th>
                            <th class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $saldo_acumulado = 0;
                        foreach($transacciones as $t): 
                            $debe = 0;
                            $haber = 0;
                            
                            // LOGICA CORREGIDA: Excluir 'nota_pedido' de la matemática financiera
                            if($t['tipo_comprobante'] != 'nota_pedido') {
                                if ($t['tipo_comprobante'] == 'factura' || $t['tipo_comprobante'] == 'nota_debito') {
                                    $debe = $t['monto'];
                                    $saldo_acumulado += $t['monto'];
                                } else {
                                    $haber = $t['monto'];
                                    $saldo_acumulado -= $t['monto'];
                                }
                            }
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if($t['tipo_comprobante'] == 'nota_pedido'): ?>
                                <a href="<?= base_url('public/proveedores/verNotaPedido/'.$t['id']) ?>"
                                    class="btn btn-sm btn-info" target="_blank" title="Ver Nota de Pedido">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($t['fecha'])) ?></td>
                            <td>
                                <?php if($t['tipo_comprobante'] == 'factura'): ?>
                                <span class="badge badge-primary">Factura</span>
                                <?php elseif($t['tipo_comprobante'] == 'pago'): ?>
                                <span class="badge badge-success">Pago</span>
                                <?php elseif($t['tipo_comprobante'] == 'nota_credito'): ?>
                                <span class="badge badge-warning">N. Crédito</span>
                                <?php elseif($t['tipo_comprobante'] == 'nota_pedido'): ?>
                                <span class="badge badge-secondary">Nota Pedido</span>
                                <?php else: ?>
                                <span class="badge badge-info">N. Débito</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code><?= $t['numero_comprobante'] ?></code>
                            </td>
                            <td>
                                <small><?= $t['observaciones'] ?: '-' ?></small>
                            </td>
                            <td class="text-right">
                                <?php if ($debe > 0): ?>
                                <strong class="text-danger">$ <?= number_format($debe, 2, ',', '.') ?></strong>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <?php if ($haber > 0): ?>
                                <strong class="text-success">$ <?= number_format($haber, 2, ',', '.') ?></strong>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <?php if($t['tipo_comprobante'] != 'nota_pedido'): ?>
                                <strong
                                    class="<?= $saldo_acumulado > 0 ? 'text-danger' : ($saldo_acumulado < 0 ? 'text-info' : 'text-success') ?>">
                                    $ <?= number_format($saldo_acumulado, 2, ',', '.') ?>
                                </strong>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($transacciones)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="fas fa-inbox"></i> No hay movimientos registrados para este proveedor
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>

                    <?php if (!empty($transacciones)): ?>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="7" class="text-right"><strong>SALDO FINAL:</strong></td>
                            <td class="text-right">
                                <strong
                                    class="<?= $saldo_actual > 0 ? 'text-danger' : ($saldo_actual < 0 ? 'text-info' : 'text-success') ?>"
                                    style="font-size: 1.2rem;">
                                    $ <?= number_format($saldo_actual, 2, ',', '.') ?>
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>