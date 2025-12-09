<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;

class Productos extends BaseController
{
    protected $productos;
    protected $sesion;

    public function __construct()
    {
        $this->productos = new ProductosModel();
        $this->sesion = session();
    }

    public function index()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $datos = $this->productos->findAll();

        $context = [
            'productos' => $datos,
            'titulo' => "Gestión de Productos y Servicios",
            'pagname' => "Productos"
        ];

        echo view('panel/header', $context);
        echo view('productos/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $context = [
            'titulo' => "Nuevo Producto/Servicio",
            'pagname' => 'Productos/Nuevo'
        ];

        echo view('panel/header', $context);
        echo view('productos/nuevo', $context);
        echo view('panel/footer');
    }

    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'codigo'      => $this->request->getPost('codigo'),
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio'      => $this->request->getPost('precio'),
            'tipo'        => $this->request->getPost('tipo')
        ];

        if ($this->productos->save($data)) {
            return redirect()->to(base_url('public/productos'))
                           ->with('success', 'Producto guardado exitosamente');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->productos->errors());
        }
    }

    public function editar($id = null)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $producto = $this->productos->find($id);

        if (!$producto) {
            return redirect()->to(base_url('public/productos'))
                           ->with('error', 'Producto no encontrado');
        }

        $context = [
            'producto' => $producto,
            'titulo' => "Editar Producto/Servicio",
            'pagname' => 'Productos/Editar'
        ];

        echo view('panel/header', $context);
        echo view('productos/editar', $context);
        echo view('panel/footer');
    }

    public function actualizar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        $id = $this->request->getPost('id');

        $data = [
            'id'          => $id,
            'codigo'      => $this->request->getPost('codigo'),
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio'      => $this->request->getPost('precio'),
            'tipo'        => $this->request->getPost('tipo')
        ];

        if ($this->productos->save($data)) {
            return redirect()->to(base_url('public/productos'))
                           ->with('success', 'Producto actualizado exitosamente');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->productos->errors());
        }
    }

    public function eliminar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return $this->response->setJSON(['success' => false]);
        }

        if (!$this->productos->delete($id)) {
            return $this->response->setJSON(['success' => false]);
        }
        /*Se pasa la validación de usuario y devuelve true en success 
        */

        return $this->response->setJSON(['success' => true]);
    }

    
    public function obtenerProducto($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return $this->response->setJSON(['error' => 'No autorizado']);
        }

        $producto = $this->productos->find($id);

        if ($producto) {
            return $this->response->setJSON($producto);
        } else {
            return $this->response->setJSON(['error' => 'Producto no encontrado']);
        }
    }

    
    public function listarActivos()
    {
        if (!isset($this->sesion->id_usuario)) {
            return $this->response->setJSON(['error' => 'No autorizado']);
        }

        $productos = $this->productos->getProductosActivos();
        return $this->response->setJSON($productos);
    }
}