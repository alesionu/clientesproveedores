<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientesModel;
use App\Models\TransaccionesModel;

class Clientes extends BaseController
{
    protected $clientes;
    protected $sesion;

    public function __construct()
    {
        $this->clientes = new ClientesModel();
        $this->sesion = session();
    }

    public function index()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }
        
        $datos = $this->clientes->findAll();
        
        $transaccionesModel = new TransaccionesModel();
        $saldos = $transaccionesModel->getSaldosClientes();

        $context = [
            'clientes' => $datos,
            'saldos' => $saldos,
            'titulo' => "Listado de Clientes",
            'pagname' => "Clientes"
        ];

        echo view('panel/header', $context);
        echo view('clientes/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $context = [
            'titulo' => "Nuevo Cliente",
            'pagname' => "Clientes/Nuevo"
        ];

        echo view('panel/header', $context);
        echo view('clientes/nuevo');
        echo view('panel/footer');
    }

    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $this->clientes->save([
            'razon_social' => $this->request->getPost('razon_social'),
            'cuit'         => $this->request->getPost('cuit'),
            'telefono'     => $this->request->getPost('telefono'),
            'email'        => $this->request->getPost('email'),
            'direccion'    => $this->request->getPost('direccion'),
            'provincia'    => $this->request->getPost('provincia'),
            'ciudad'       => $this->request->getPost('ciudad'),
            'codigo_postal'=> $this->request->getPost('codigo_postal')
        ]);

        return redirect()->to(base_url('public/clientes/'));
    }

    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $cliente = $this->clientes->where('id', $id)->first();

        $context = [
            'cliente' => $cliente,
            'titulo'  => "Editar Cliente",
            'pagname' => "Clientes/Editar"
        ];

        echo view('panel/header', $context);
        echo view('clientes/editar', $context);
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
            'codigo_postal'=> $this->request->getPost('codigo_postal')
        ];

        $this->clientes->update($id, $data);

        return redirect()->to(base_url('public/clientes'));
    }

        public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return $this->response->setJSON(['success' => false]);
        }

        if (!$this->clientes->delete($id)) {
            return $this->response->setJSON(['success' => false]);
        }
        /*Se pasa la validación de usuario y devuelve true en success 
        */

        return $this->response->setJSON(['success' => true]);
    }


        public function detalle($id){
        /*para mostrar el detalle
 de la cuenta corriente del cliente
        */

        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $cliente = $this->clientes->find($id);
        // find es para mostrar un array de datos
        //en este caso si el array es null redirige a clientes nuevamente
        if (!$cliente) {
            return redirect()->to(base_url('public/clientes'));
        }

        $transaccionesModel = new TransaccionesModel();
        $transacciones = $transaccionesModel->getTransaccionesCliente($id);
        
        $saldo_actual = $transaccionesModel->calcularSaldoCliente($id);

        $context = [
            'cliente' => $cliente,
            'transacciones' => $transacciones,
            'saldo_actual' => $saldo_actual,
            'titulo' => "Cuenta Corriente - " . $cliente['razon_social'],
            'pagname' => "Clientes / Cuenta Corriente"
        ];

        echo view('panel/header', $context);
        echo view('clientes/detalle', $context);
        echo view('panel/footer');
    }
}