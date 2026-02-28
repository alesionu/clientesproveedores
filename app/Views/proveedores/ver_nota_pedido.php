<div class="row">
    <div class="col-md-12">
        <div class="card mt-2">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice"></i> Nota de Pedido - <?= htmlspecialchars($transaccion['numero_comprobante']) ?>
                </h3>
                <div class="card-tools">
                    <button onclick="imprimirNota()" class="btn btn-sm btn-light">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                    <a href="<?= base_url('public/proveedores/detalle/'.$proveedor['id']) ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Información General -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-primary"><i class="fas fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Fecha</span>
                                <span class="info-box-number"><?= date('d/m/Y', strtotime($transaccion['fecha'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total</span>
                                <span class="info-box-number">$ <?= number_format($transaccion['monto'], 2, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos del Proveedor -->
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-truck"></i> Datos del Proveedor</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Razón Social:</strong> <?= htmlspecialchars($proveedor['razon_social']) ?></p>
                                <p><strong>CUIT:</strong> <?= htmlspecialchars($proveedor['cuit']) ?></p>
                                <p><strong>Teléfono:</strong> <?= htmlspecialchars($proveedor['telefono']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Email:</strong> <?= htmlspecialchars($proveedor['email']) ?></p>
                                <p><strong>Dirección:</strong> <?= htmlspecialchars($proveedor['direccion']) ?></p>
                                <p><strong>Localidad:</strong> <?= htmlspecialchars($proveedor['ciudad']) ?>, <?= htmlspecialchars($proveedor['provincia']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalle del Pedido -->
                <div class="card card-outline card-secondary mt-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list"></i> Detalle del Pedido</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped">
                            <thead class="bg-dark">
                                <tr>
                                    <th style="width: 10%;">Código</th>
                                    <th style="width: 45%;">Producto / Descripción</th>
                                    <th class="text-center" style="width: 10%;">Cantidad</th>
                                    <th class="text-right" style="width: 15%;">Precio Unit.</th>
                                    <th class="text-right" style="width: 20%;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($detalle)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-inbox"></i> No hay productos en esta nota de pedido
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($detalle as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['codigo'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php if (!empty($item['descripcion_libre'])): ?>
                                                <span class="badge badge-info mr-1">Libre</span>
                                                <?= htmlspecialchars($item['descripcion_libre']) ?>
                                            <?php elseif (!empty($item['producto_nombre'])): ?>
                                                <?= htmlspecialchars($item['producto_nombre']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sin descripción</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= number_format($item['cantidad'], 2, ',', '.') ?></td>
                                        <td class="text-right">$ <?= number_format($item['precio_unitario'], 2, ',', '.') ?></td>
                                        <td class="text-right font-weight-bold">$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="4" class="text-right"><strong>TOTAL:</strong></td>
                                    <td class="text-right"><strong class="text-success" style="font-size: 1.2em;">$ <?= number_format($transaccion['monto'], 2, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Observaciones -->
                <?php if (!empty($transaccion['observaciones'])): ?>
                <div class="card card-outline card-warning mt-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-comment"></i> Observaciones</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?= nl2br(htmlspecialchars($transaccion['observaciones'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Impresión -->
<div id="ventanaImpresion" style="display:none;"></div>

<script>
function imprimirNota() {
    const contenido = `
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Nota de Pedido - <?= $transaccion['numero_comprobante'] ?></title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
            .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #333; padding-bottom: 15px; }
            .header h1 { font-size: 28px; color: #333; margin-bottom: 5px; }
            .header h2 { font-size: 18px; color: #666; font-weight: normal; }
            .info-section { margin-bottom: 20px; }
            .info-section h3 { font-size: 14px; background-color: #f0f0f0; padding: 8px 10px; border-left: 4px solid #007bff; margin-bottom: 10px; }
            .info-row { padding: 5px 0; }
            .info-label { font-weight: bold; display: inline-block; width: 150px; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            table thead { background-color: #343a40; color: white; }
            table th { padding: 12px; text-align: left; font-weight: bold; }
            table td { padding: 10px 12px; border-bottom: 1px solid #ddd; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .total-row { font-size: 18px; font-weight: bold; background-color: #f8f9fa; padding: 15px; border: 2px solid #333; text-align: right; margin-top: 20px; }
            .observaciones { margin-top: 30px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffc107; }
            .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #666; padding-top: 20px; border-top: 1px solid #ddd; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>NOTA DE PEDIDO</h1>
            <h2>N° <?= htmlspecialchars($transaccion['numero_comprobante']) ?></h2>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Fecha:</span>
                <span><?= date('d/m/Y', strtotime($transaccion['fecha'])) ?></span>
            </div>
        </div>

        <div class="info-section">
            <h3>DATOS DEL PROVEEDOR</h3>
            <div class="info-row"><span class="info-label">Razón Social:</span> <?= htmlspecialchars($proveedor['razon_social']) ?></div>
            <div class="info-row"><span class="info-label">CUIT:</span> <?= htmlspecialchars($proveedor['cuit']) ?></div>
            <div class="info-row"><span class="info-label">Dirección:</span> <?= htmlspecialchars($proveedor['direccion']) ?></div>
            <div class="info-row"><span class="info-label">Localidad:</span> <?= htmlspecialchars($proveedor['ciudad']) ?>, <?= htmlspecialchars($proveedor['provincia']) ?></div>
            <div class="info-row"><span class="info-label">Teléfono:</span> <?= htmlspecialchars($proveedor['telefono']) ?></div>
            <div class="info-row"><span class="info-label">Email:</span> <?= htmlspecialchars($proveedor['email']) ?></div>
        </div>

        <div class="info-section">
            <h3>DETALLE DEL PEDIDO</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">Código</th>
                        <th style="width: 45%;">Producto</th>
                        <th class="text-center" style="width: 10%;">Cantidad</th>
                        <th class="text-right" style="width: 15%;">Precio Unit.</th>
                        <th class="text-right" style="width: 20%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['codigo'] ?? 'N/A') ?></td>
                        <td>
                            <?php if (!empty($item['descripcion_libre'])): ?>
                                <?= htmlspecialchars($item['descripcion_libre']) ?>
                            <?php elseif (!empty($item['producto_nombre'])): ?>
                                <?= htmlspecialchars($item['producto_nombre']) ?>
                            <?php else: ?>
                                Sin descripción
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?= number_format($item['cantidad'], 2, ',', '.') ?></td>
                        <td class="text-right">$ <?= number_format($item['precio_unitario'], 2, ',', '.') ?></td>
                        <td class="text-right">$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="total-row">
            TOTAL: $ <?= number_format($transaccion['monto'], 2, ',', '.') ?>
        </div>

        <?php if (!empty($transaccion['observaciones'])): ?>
        <div class="observaciones">
            <h4>Observaciones:</h4>
            <p><?= nl2br(htmlspecialchars($transaccion['observaciones'])) ?></p>
        </div>
        <?php endif; ?>

        <div class="footer">
            <p>Documento generado el <?= date('d/m/Y H:i:s') ?></p>
        </div>
    </body>
    </html>
    `;

    const ventana = window.open('', 'PRINT', 'height=600,width=800');
    ventana.document.write(contenido);
    ventana.document.close();
    ventana.focus();
    ventana.print();
    ventana.close();
}
</script>