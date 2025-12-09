<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProveedoresModel;

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
        ];

        $this->proveedores->update($id, $data);

        return redirect()->to(base_url('public/proveedores'));
    }

    public function borrar($id)
    {
        // 1. Verificar Sesión
        if (!isset($this->sesion->id_usuario)) {
            // Si no hay sesión, devolvemos error JSON en vez de redirect
            return $this->response->setJSON(['success' => false, 'msg' => 'No tienes permiso.']);
        }

        // 2. Intentar borrar
        try {
            // Verifica si existe antes de borrar (Opcional, pero recomendado)
            if($this->proveedores->find($id)) {
                $this->proveedores->delete($id);
                // ¡ÉXITO! Devolvemos JSON true
                return $this->response->setJSON(['success' => true, 'msg' => 'Proveedor eliminado correctamente.']);
            } else {
                return $this->response->setJSON(['success' => false, 'msg' => 'El proveedor no existe.']);
            }
        } catch (\Exception $e) {
            // ¡ERROR! Devolvemos JSON false
            return $this->response->setJSON(['success' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }
    public function detalle($id){

        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        // Obtener datos del proveedor
        $proveedor = $this->proveedores->find($id);
        
        if (!$proveedor) {
            return redirect()->to(base_url('public/proveedores'))->with('error', 'Proveedor no encontrado');
        }

        // Obtener transacciones del proveedor
        $transaccionesModel = new \App\Models\TransaccionesModel();
        $transacciones = $transaccionesModel->getTransaccionesProveedor($id);
        
        // Calcular saldo actual
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
}