<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransaccionesModel;
use App\Models\TransaccionesDetalleModel;
use App\Models\ClientesModel;
use App\Models\ProveedoresModel;
use App\Models\ProductosModel;

class Transacciones extends BaseController
{
    protected $transacciones;
    protected $transaccionesDetalle;
    protected $clientes;
    protected $proveedores;
    protected $productos;
    protected $sesion;

    public function __construct()
    {
        $this->transacciones = new TransaccionesModel();
        $this->transaccionesDetalle = new TransaccionesDetalleModel();
        $this->clientes = new ClientesModel();
        $this->proveedores = new ProveedoresModel();
        $this->productos = new ProductosModel();
        $this->sesion = session();
    }

    public function index()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $datos = $this->transacciones->getTransaccionesCompletas();

        $context = [
            'transacciones' => $datos,
            'titulo' => "Gestión de Comprobantes",
            'pagname' => "Comprobantes"
        ];

        echo view('panel/header', $context);
        echo view('transacciones/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo()
{
    if (!isset($this->sesion->id_usuario)) {
        return redirect()->to(base_url('login'));
    }

    $lista_clientes = $this->clientes->findAll();
    $lista_proveedores = $this->proveedores->findAll();
    $lista_productos = $this->productos->getProductosActivos();

    $context = [
        'clientes' => $lista_clientes,
        'proveedores' => $lista_proveedores,
        'productos' => $lista_productos,
        'titulo' => "Nuevo Comprobante",
        'pagname' => 'Comprobantes/Nuevo'
    ];

    echo view('panel/header', $context);
    echo view('transacciones/nuevo', $context);
    echo view('panel/footer');
}

    private function generarProximoNumero($tipo_comprobante)
    {
        $ultimo = $this->transacciones->where('tipo_comprobante', $tipo_comprobante)
                                      ->orderBy('id', 'DESC') 
                                      ->first();

        if ($ultimo) {
            $nuevo_numero = intval($ultimo['numero_comprobante']) + 1;
        } else {
            $nuevo_numero = 1;
        }

        return str_pad($nuevo_numero, 6, '0', STR_PAD_LEFT);
    }

    public function guardar()
{
    if (!isset($this->sesion->id_usuario)) {
        return redirect()->to(base_url('login'));
    }

    $tipo_entidad = $this->request->getPost('tipo_entidad');
    $id_entidad = $this->request->getPost('id_entidad');
    $tipo_comprobante = $this->request->getPost('tipo_comprobante');

    if (empty($id_entidad)) {
        return redirect()->back()->with('error', 'Debe seleccionar un cliente o proveedor');
    }

    $numero_generado = $this->generarProximoNumero($tipo_comprobante);

    $forma_pago = $this->request->getPost('forma_pago');
    if (empty($forma_pago)) {
        $forma_pago = 'efectivo'; 
    }

    $data = [
        'cuit' => $this->request->getPost('cuit'),
        'tipo_comprobante'   => $tipo_comprobante,
        'numero_comprobante' => $numero_generado,
        'fecha'              => $this->request->getPost('fecha'),
        'monto'              => $this->request->getPost('monto'),
        'observaciones'      => $this->request->getPost('observaciones'),
        'usuario_id'         => $this->sesion->id_usuario,
        'forma_pago'         => $forma_pago,  
        'pagado'             => 0
    ];

    if ($tipo_entidad == 'cliente') {
        $data['cliente_id'] = $id_entidad;
        $data['proveedor_id'] = null;
    } else {
        $data['proveedor_id'] = $id_entidad;
        $data['cliente_id'] = null;
    }

    if ($tipo_entidad == 'proveedor') {
        $archivo = $this->request->getFile('archivo_pdf');
        
        if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
            if ($archivo->getMimeType() !== 'application/pdf') {
                return redirect()->back()->with('error', 'Solo se permiten archivos PDF');
            }

            if ($archivo->getSize() > 5242880) {
                return redirect()->back()->with('error', 'El archivo no puede superar los 5MB');
            }

            $nuevoNombre = 'proveedor_' . $id_entidad . '_' . time() . '.pdf';
            
            $archivo->move(ROOTPATH . 'public/uploads/comprobantes', $nuevoNombre);
            
            $data['archivo_pdf'] = $nuevoNombre;
        }
    } else {
        $data['archivo_pdf'] = null;
    }

    $transaccion_id = $this->transacciones->insert($data);

    if ($tipo_entidad == 'cliente' && $tipo_comprobante == 'factura') {
        $productos_json = $this->request->getPost('productos_json');
        
        if (!empty($productos_json)) {
            $items = json_decode($productos_json, true);
            
            if (!empty($items)) {
                $this->transaccionesDetalle->guardarDetalle($transaccion_id, $items);
            }
        }
    }

    if ($transaccion_id) {
        $mensaje = $tipo_entidad == 'cliente' ? 
            'Comprobante emitido exitosamente' : 
            'Comprobante de proveedor registrado exitosamente';
            
        return redirect()->to(base_url('public/transacciones'))
                         ->with('success', $mensaje . ' - Nº ' . $numero_generado);
    } else {
        return redirect()->back()->with('error', 'Error al guardar el comprobante');
    }
}
    public function nuevo_pago()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }
        
        $lista_clientes = $this->clientes->findAll();
        $lista_proveedores = $this->proveedores->findAll();

        $context = [
            'clientes' => $lista_clientes,
            'proveedores' => $lista_proveedores,
            'titulo' => "Registrar Pago/Cobro",
            'pagname' => 'Pagos/Nuevo'
        ];

        echo view('panel/header', $context);
        echo view('transacciones/nuevo_pago', $context);
        echo view('panel/footer');
    }   

    public function guardar_pago()
{
    if (!isset($this->sesion->id_usuario)) {
        return redirect()->to(base_url('login'));
    }

    $tipo_operacion = $this->request->getPost('tipo_operacion');
    $id_entidad = $this->request->getPost('id_entidad');
    $numero_generado = $this->generarProximoNumero('pago');

    // Obtener los IDs de los comprobantes que se están pagando
    $comprobantes_ids = $this->request->getPost('comprobantes_ids');
    
    // Variable para almacenar la forma de pago - VALOR POR DEFECTO GARANTIZADO
    $forma_pago = 'efectivo';

    // Intentar obtener la forma_pago del primer comprobante
    if (!empty($comprobantes_ids)) {
        // Convertir a string si viene como array
        if (is_array($comprobantes_ids)) {
            $comprobantes_ids = implode(',', $comprobantes_ids);
        }
        
        if (is_string($comprobantes_ids) && trim($comprobantes_ids) !== '') {
            $ids_array = explode(',', trim($comprobantes_ids));
            $primer_id = trim($ids_array[0]);

            if (!empty($primer_id) && is_numeric($primer_id)) {
                $comprobante_original = $this->transacciones->find($primer_id);
                if ($comprobante_original && isset($comprobante_original['forma_pago']) && !empty($comprobante_original['forma_pago'])) {
                    $forma_pago = $comprobante_original['forma_pago'];
                }
            }
        }
    }

    $data = [
        'tipo_comprobante'   => 'pago',
        'numero_comprobante' => $numero_generado,
        'fecha'              => $this->request->getPost('fecha'),
        'monto'              => $this->request->getPost('monto'),
        'observaciones'      => $this->request->getPost('observaciones'),
        'usuario_id'         => $this->sesion->id_usuario,
        'forma_pago'         => $forma_pago,  
        'pagado'             => 1  
    ];

    if ($tipo_operacion == 'cobro') {
        $data['cliente_id'] = $id_entidad;
        $data['proveedor_id'] = null;
    } else {
        $data['proveedor_id'] = $id_entidad;
        $data['cliente_id'] = null;
    }

    // Insertar el registro de pago
    $pago_id = $this->transacciones->insert($data);

    if (!$pago_id) {
        return redirect()->back()->with('error', 'Error al registrar el pago');
    }

    // Marcar los comprobantes como pagados
    if (!empty($comprobantes_ids)) {
        // Convertir a string si es array
        if (is_array($comprobantes_ids)) {
            $comprobantes_ids = implode(',', $comprobantes_ids);
        }
        
        if (is_string($comprobantes_ids) && trim($comprobantes_ids) !== '') {
            $ids_array = explode(',', trim($comprobantes_ids));
            
            foreach ($ids_array as $id) {
                $id = trim($id); 
                if (!empty($id) && is_numeric($id)) {
                    $this->transacciones->update($id, ['pagado' => 1]);
                }
            }
        }
    }

    return redirect()->to(base_url('public/transacciones'))
                     ->with('success', 'Pago/Cobro Nº ' . $numero_generado . ' registrado exitosamente');
}

    public function ver_detalle($id)
{
    if (!isset($this->sesion->id_usuario)) {
        return redirect()->to(base_url('login'));
    }

    $transaccion = $this->transacciones->find($id);
    
    if (!$transaccion) {
        return redirect()->to(base_url('public/transacciones'))
                         ->with('error', 'Transacción no encontrada');
    }

    $detalle = $this->transaccionesDetalle->getDetalleTransaccion($id);

    $nombre_entidad = 'Consumidor Final';
    $direccion = 'Sin dirección registrada';
    $documento = '';

    if ($transaccion['cliente_id']) {
        $cliente = $this->clientes->find($transaccion['cliente_id']);
        if ($cliente) {
            $nombre_entidad = $cliente['razon_social'];
            $direccion = $cliente['direccion'] ?? 'Sin dirección registrada'; 
            $documento = $cliente['cuit'] ?? '';
        }

    } elseif ($transaccion['proveedor_id']) {
        $proveedor = $this->proveedores->find($transaccion['proveedor_id']);
        if ($proveedor) {
            $nombre_entidad = $proveedor['razon_social'];
            $direccion = $proveedor['direccion'] ?? 'Sin dirección registrada';
            $documento = $proveedor['cuit'] ?? '';
        }
    }

    $transaccion['nombre_razon_social'] = $nombre_entidad;
    $transaccion['direccion_entidad']   = $direccion;
    $transaccion['documento_entidad']   = $documento;

    $context = [
        'transaccion' => $transaccion,
        'detalle' => $detalle,
        'usuario_impresion' => $this->sesion->nombre ?? 'Usuario',
        'titulo' => "Detalle de Comprobante",
        'pagname' => 'Comprobantes/Detalle'
    ];

    echo view('panel/header', $context);
    echo view('transacciones/detalle', $context);
    echo view('panel/footer');
}

    public function totalesCaja()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }
        
        $model = new TransaccionesModel();
        $totales = $model->getTotalesCaja();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $totales
        ]);
    }


    public function obtener_deuda()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['status' => false, 'message' => 'Acceso no permitido']);
    }

    if (!isset($this->sesion->id_usuario)) {
        return $this->response->setJSON(['status' => false, 'message' => 'No autorizado']);
    }

    $tipo_operacion = $this->request->getPost('tipo_operacion');
    $id_entidad = $this->request->getPost('id_entidad');

    if (empty($tipo_operacion) || empty($id_entidad)) {
        return $this->response->setJSON(['status' => false, 'message' => 'Datos incompletos']);
    }

    $tipo_entidad = ($tipo_operacion == 'cobro') ? 'cliente' : 'proveedor';
    $pendientes = $this->transacciones->getPendientes($tipo_entidad, $id_entidad);

    return $this->response->setJSON([
        'status' => true,
        'data' => $pendientes
    ]);
}


}