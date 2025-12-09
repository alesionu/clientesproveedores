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

    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $tipo_entidad = $this->request->getPost('tipo_entidad');
        $id_entidad = $this->request->getPost('id_entidad');
        $tipo_comprobante = $this->request->getPost('tipo_comprobante');

        // Guardamos los datos del comprobante
        $data = [
            'tipo_comprobante'   => $tipo_comprobante,
            'numero_comprobante' => $this->request->getPost('numero_comprobante'),
            'fecha'              => $this->request->getPost('fecha'),
            'monto'              => $this->request->getPost('monto'),
            'observaciones'      => $this->request->getPost('observaciones'),
        ];

        // Lógica: Llenar solo el ID que corresponda
        if ($tipo_entidad == 'cliente') {
            $data['cliente_id'] = $id_entidad;
            $data['proveedor_id'] = null;
        } else {
            $data['proveedor_id'] = $id_entidad;
            $data['cliente_id'] = null;
        }

        // Guardar transacción
        $transaccion_id = $this->transacciones->insert($data);

        // Solo guardamos detalle si es una factura y si hay productos
        if ($tipo_comprobante == 'factura') {
            $productos_json = $this->request->getPost('productos_json');
            
            if (!empty($productos_json)) {
                $items = json_decode($productos_json, true);
                
                if (!empty($items)) {
                    $this->transaccionesDetalle->guardarDetalle($transaccion_id, $items);
                }
            }
        }

        return redirect()->to(base_url('public/transacciones'))
                       ->with('success', 'Comprobante guardado exitosamente');
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

        $data = [
            'tipo_comprobante'   => 'pago',
            'numero_comprobante' => $this->request->getPost('numero_comprobante'),
            'fecha'              => $this->request->getPost('fecha'),
            'monto'              => $this->request->getPost('monto'),
            'observaciones'      => $this->request->getPost('observaciones'),
        ];

        if ($tipo_operacion == 'cobro') {
            $data['cliente_id'] = $id_entidad;
            $data['proveedor_id'] = null;
        } else {
            $data['proveedor_id'] = $id_entidad;
            $data['cliente_id'] = null;
        }

        $this->transacciones->save($data);

        return redirect()->to(base_url('public/transacciones'))
                       ->with('success', 'Pago/Cobro registrado exitosamente');
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

        $context = [
            'transaccion' => $transaccion,
            'detalle' => $detalle,
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
}