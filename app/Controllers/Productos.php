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

        $proximoCodigo = $this->generarProximoCodigo();

        $context = [
            'proximoCodigo' => $proximoCodigo,
            'titulo' => "Nuevo Producto/Servicio",
            'pagname' => 'Productos/Nuevo'
        ];

        echo view('panel/header', $context);
        echo view('productos/nuevo', $context);
        echo view('panel/footer');
    }

    private function generarProximoCodigo()
    {
        // Para buscar el último producto registrado
        //hacemos un orderBy descendente por id y tomamos el primero
        $ultimo = $this->productos->orderBy('id', 'DESC')->first();

        if ($ultimo) {
            // Extraemos el número del código existente y lo incrementamos
            preg_match('/\d+/', $ultimo['codigo'], $matches);
            $numero = isset($matches[0]) ? intval($matches[0]) + 1 : 1;
        } else {
            // Si no existe ninguno, empezamos en 1
            $numero = 1;
        }

        // Formato PROD-000001 (6 dígitos)
        return str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url('login'));
        }

        // Generamos el código automáticamente
        $codigoGenerado = $this->generarProximoCodigo();

        $data = [
            'codigo'      => $codigoGenerado,  // Usamos el código generado
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio'      => $this->request->getPost('precio'),
            'tipo'        => $this->request->getPost('tipo')
        ];

        if ($this->productos->save($data)) {
            return redirect()->to(base_url('public/productos'))
                           ->with('success', 'Producto guardado exitosamente con código: ' . $codigoGenerado);
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
            'codigo'      => $this->request->getPost('codigo'), // Mantenemos el código existente al editar
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