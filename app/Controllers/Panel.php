<?php

namespace App\Controllers;


use App\Models\UsuariosModel; 
use App\Models\ClientesModel;
use App\Models\ProveedoresModel;

class Panel extends BaseController
{
    protected $usuarios; 
    protected $clientes;
    protected $proveedores;
    protected $sesion;

    public function __construct() 
    {
        $this->sesion = session();
        $this->usuarios = new UsuariosModel();
        $this->clientes = new ClientesModel();
        $this->proveedores = new ProveedoresModel();
        
        $this->sesion = session();
        
        
    }

    public function index()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }
        //Esto seria una consulta,  "Select * from clientes"
        $usuarios = $this->usuarios->findAll();
        $clientes = $this->clientes->findAll();
        $proveedores = $this->proveedores->findAll();
        
        $totalUsuarios = $this->usuarios->countAllResults();
        $totalClientes = $this->clientes->countAllResults();
        $totalProveedores = $this->proveedores->countAllResults();

        $context = ['titulo' => 'Tablero'];

        echo view('panel/header', $context);
        echo view('panel/contenido', [
            
            'totalUsuarios' => $totalUsuarios,
            'totalClientes' => $totalClientes,
            'totalProveedores' => $totalProveedores
        ]);
        echo view('panel/footer');
    }
    
}