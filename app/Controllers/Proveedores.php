<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProveedoresModel;
use App\Models\ProductosModel;
use App\Models\TransaccionesDetalleModel;

class Proveedores extends BaseController
{
    protected $proveedores;
    protected $sesion;

    public function __construct()
    {
        $this->proveedores = new ProveedoresModel();
        $this->sesion = session();
    }

    public function index()
{
    if (!isset($this->sesion->id_usuario)) {
        return redirect()->to(base_url('login'));
    }

    $datos = $this->proveedores->findAll();
    
    $transaccionesModel = new \App\Models\TransaccionesModel();
    $saldos = $transaccionesModel->getSaldosProveedores();

    $context = [
        'proveedores' => $datos,
        'saldos' => $saldos,
        'titulo' => "Listado de Proveedores",
        'pagname' => "Proveedores"
    ];

    echo view('panel/header', $context);
    echo view('proveedores/listado', $context);
    echo view('panel/footer');
}

    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $context = [
            'titulo' => "Nuevo Proveedor",
            'pagname' => "Proveedores/Nuevo"
        ];

        echo view('panel/header', $context);
        echo view('proveedores/nuevo');
        echo view('panel/footer');
    }

    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        // Guardamos los datos del formulario
        $this->proveedores->save([
            'razon_social' => $this->request->getPost('razon_social'),
            'cuit'         => $this->request->getPost('cuit'),
            'telefono'     => $this->request->getPost('telefono'),
            'email'        => $this->request->getPost('email'),
            'direccion'    => $this->request->getPost('direccion'),
            'provincia'    => $this->request->getPost('provincia'),
            'ciudad'       => $this->request->getPost('ciudad'),
            'codigo_postal'=> $this->request->getPost('codigo_postal'),
            'categoria'     => $this->request->getPost('categoria')
        ]);

        return redirect()->to(base_url('public/proveedores/'));
    }

    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $proveedor = $this->proveedores->where('id', $id)->first();

        $context = [
            'proveedor' => $proveedor,
            'titulo'  => "Editar Proveedor",
            'pagname' => "Proveedores/Editar"
        ];

        echo view('panel/header', $context);
        echo view('proveedores/editar', $context);
        echo view('panel/footer');
    }

    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'razon_social' => $this->request->getPost('razon_social'),
            'cuit'         => $this->request->getPost('cuit'),
            'telefono'     => $this->request->getPost('telefono'),
            'email'        => $this->request->getPost('email'),
            'direccion'    => $this->request->getPost('direccion'),
            'provincia'    => $this->request->getPost('provincia'),
            'ciudad'       => $this->request->getPost('ciudad'),
            'codigo_postal'=> $this->request->getPost('codigo_postal'),
            'categoria'     => $this->request->getPost('categoria')
        ];

        $this->proveedores->update($id, $data);

        return redirect()->to(base_url('public/proveedores'));
    }

    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return $this->response->setJSON(['success' => false, 'msg' => 'No tienes permiso.']);
        }

        
            if($this->proveedores->find($id)) {
                $this->proveedores->delete($id);
                return $this->response->setJSON(['success' => true, 'msg' => 'Proveedor eliminado correctamente.']);
            } else {
                return $this->response->setJSON(['success' => false, 'msg' => 'El proveedor no existe.']);
            }
            return $this->response->setJSON(['success' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()]);
        
    }
    public function detalle($id){

        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $proveedor = $this->proveedores->find($id);
        
        if (!$proveedor) {
            return redirect()->to(base_url('public/proveedores'))->with('error', 'Proveedor no encontrado');
        }

        $transaccionesModel = new \App\Models\TransaccionesModel();
        $transacciones = $transaccionesModel->getTransaccionesProveedor($id);
        
        $saldo_actual = $transaccionesModel->calcularSaldoProveedor($id);

        $context = [
            'proveedor' => $proveedor,
            'transacciones' => $transacciones,
            'saldo_actual' => $saldo_actual,
            'titulo' => "Cuenta Corriente - " . $proveedor['razon_social'],
            'pagname' => "Proveedores / Cuenta Corriente"
        ];

        echo view('panel/header', $context);
        echo view('proveedores/detalle', $context);
        echo view('panel/footer');
    }


public function nuevaNotaPedido($proveedor_id)
{
    if (!isset($this->sesion->id_usuario)) {
        return redirect()->to(base_url('login'));
    }

    $proveedor = $this->proveedores->find($proveedor_id);
    
    if (!$proveedor) {
        return redirect()->to(base_url('public/proveedores'))->with('error', 'Proveedor no encontrado');
    }

    $productosModel = new ProductosModel();
    $productos = $productosModel->getProductosActivos();

    // Obtener el último número de nota de pedido
    $transaccionesModel = new \App\Models\TransaccionesModel();
    $ultima = $transaccionesModel
        ->where('tipo_comprobante', 'nota_pedido')
        ->orderBy('id', 'DESC')
        ->first();
    
    $siguiente_numero = 1;
    if ($ultima && $ultima['numero_comprobante']) {
        // Extraer el número del formato NP-0001
        $partes = explode('-', $ultima['numero_comprobante']);
        $siguiente_numero = intval($partes[1]) + 1;
    }
    
    $numero_nota = 'NP-' . str_pad($siguiente_numero, 4, '0', STR_PAD_LEFT);

    $context = [
        'proveedor' => $proveedor,
        'productos' => $productos,
        'numero_nota' => $numero_nota,
        'titulo' => "Nueva Nota de Pedido - " . $proveedor['razon_social'],
        'pagname' => "Proveedores / Nota de Pedido"
    ];

    echo view('panel/header', $context);
    echo view('proveedores/nueva_nota_pedido', $context);
    echo view('panel/footer');
}
public function guardarNotaPedido()
{
    $this->response->setContentType('application/json');
    
    if (!isset($this->sesion->id_usuario)) {
        return $this->response->setJSON(['success' => false, 'msg' => 'No autorizado']);
    }

    try {
        $proveedor_id = $this->request->getPost('proveedor_id');
        $productos_json = $this->request->getPost('productos');
        $fecha = $this->request->getPost('fecha');
        $observaciones = $this->request->getPost('observaciones');
        $numero_comprobante = $this->request->getPost('numero_comprobante');

        if (empty($productos_json)) {
            return $this->response->setJSON(['success' => false, 'msg' => 'No se recibieron productos']);
        }
        
        $productos = json_decode($productos_json, true);
        if (json_last_error() !== JSON_ERROR_NONE || empty($productos)) {
            return $this->response->setJSON(['success' => false, 'msg' => 'Error en los productos (JSON)']);
        }

        $total = 0;
        foreach ($productos as $prod) {
            $total += floatval($prod['subtotal']);
        }

        $db = \Config\Database::connect();
        $detalleModel = new TransaccionesDetalleModel();

        $db->transStart();

        $transaccion_data = [
            'proveedor_id'      => $proveedor_id,
            'cliente_id'        => null,
            'tipo_comprobante'  => 'nota_pedido',
            'numero_comprobante'=> $numero_comprobante,
            'fecha'             => $fecha,
            'monto'             => $total,
            'observaciones'     => $observaciones,
            'id_usuario'        => $this->sesion->id_usuario,
            'pagado'            => 0,
            'fecha_alta'        => date('Y-m-d H:i:s'),
            'fecha_edicion'     => date('Y-m-d H:i:s')
        ];

        $builder = $db->table('transacciones');
        $insert_ok = $builder->insert($transaccion_data);
        $transaccion_id = $db->insertID();

        if (!$insert_ok || !$transaccion_id) {
            $error_db = $db->error();
            $db->transRollback();
            
            return $this->response->setJSON([
                'success' => false, 
                'msg' => 'Error BD: ' . $error_db['message'] . ' (Code: ' . $error_db['code'] . ')',
                'debug_sql' => $error_db
            ]);
        }

        foreach ($productos as $index => $prod) {
            $detalle_data = [
                'transaccion_id' => $transaccion_id,
                'producto_id'    => $prod['producto_id'], 
                'cantidad'       => $prod['cantidad'],
                'precio_unitario'=> $prod['precio_unitario'],
                'subtotal'       => $prod['subtotal'],
                'descripcion_libre' => isset($prod['descripcion_libre']) ? $prod['descripcion_libre'] : null
            ];
            
            if (!$detalleModel->insert($detalle_data)) {
                $error_db = $detalleModel->db->error();
                $errores_mod = $detalleModel->errors();
                $db->transRollback();
                
                return $this->response->setJSON([
                    'success' => false, 
                    'msg' => 'Error al guardar producto #' . ($index + 1) . ': ' . json_encode($errores_mod),
                    'errors' => $errores_mod,
                    'debug_sql' => $error_db
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            $error_db = $db->error();
            return $this->response->setJSON(['success' => false, 'msg' => 'Error finalizando transacción: ' . $error_db['message']]);
        }

        return $this->response->setJSON([
            'success' => true, 
            'msg' => 'Nota de pedido guardada correctamente',
            'transaccion_id' => $transaccion_id
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON(['success' => false, 'msg' => 'Excepción: ' . $e->getMessage()]);
    }
}

public function verNotaPedido($transaccion_id)
{
    if (!isset($this->sesion->id_usuario)) {
        return redirect()->to(base_url('login'));
    }

    $transaccionesModel = new \App\Models\TransaccionesModel();
    $detalleModel = new TransaccionesDetalleModel();

    $transaccion = $transaccionesModel->find($transaccion_id);
    
    if (!$transaccion || $transaccion['tipo_comprobante'] != 'nota_pedido') {
        return redirect()->to(base_url('public/proveedores'))->with('error', 'Nota de pedido no encontrada');
    }

    $proveedor = $this->proveedores->find($transaccion['proveedor_id']);
    $detalle = $detalleModel->getDetalleConProductos($transaccion_id);

    $context = [
        'transaccion' => $transaccion,
        'proveedor' => $proveedor,
        'detalle' => $detalle,
        'titulo' => "Nota de Pedido - " . $transaccion['numero_comprobante'],
        'pagname' => "Nota de Pedido"
    ];
    echo view('panel/header', $context);
    echo view('proveedores/ver_nota_pedido', $context);
    echo view('panel/footer');
}

public function subirListaPrecios()
{
    if (!isset($this->sesion->id_usuario)) {
        return redirect()->to(base_url('login'));
    }

    $id_proveedor = $this->request->getPost('id_proveedor');
    $archivo = $this->request->getFile('archivo_lista');

    if (!$archivo->isValid()) {
        return redirect()->back()->with('error', $archivo->getErrorString());
    }

    if ($archivo->getMimeType() !== 'application/pdf') {
        return redirect()->back()->with('error', 'Solo se permiten archivos PDF.');
    }

    $nuevoNombre = 'lista_' . $id_proveedor . '_' . time() . '.pdf';

    
    $archivo->move(ROOTPATH . 'public/uploads/listas_precios', $nuevoNombre);

    $this->proveedores->update($id_proveedor, [
        'lista_precios_pdf' => $nuevoNombre
    ]);

    return redirect()->to(base_url('public/proveedores/detalle/' . $id_proveedor))
                     ->with('success', 'Lista de precios actualizada correctamente.');
}
}