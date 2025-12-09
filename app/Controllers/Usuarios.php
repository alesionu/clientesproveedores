<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class Usuarios extends BaseController
{
    protected $usuarios;
    protected $sesion;
    
    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
        $this->sesion = session();
    }

    public function index()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $usuarios = $this->usuarios->findAll();

        $context = [
            'usuarios' => $usuarios,
            'titulo' => "Usuarios",
            'pagname' => "Gestión/Usuarios"
        ];

        echo view('panel/header', $context);
        echo view('usuarios/listado', $context);
        echo view('panel/footer');
    }

    public function nuevo()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $context = [
            'titulo' => "Nuevo usuario",
            'pagname' => 'Gestión/Nuevo usuario'
        ];

        echo view('panel/header', $context);
        echo view('usuarios/nuevo');
        echo view('panel/footer');
    }

    public function guardar()
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $hash = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        
        $this->usuarios->save([
            'usuario' => $this->request->getPost('usuario'),  
            'nombre' => $this->request->getPost('nombre'),
            'password' => $hash,
        ]);
        
        return redirect()->to(base_url() . 'public/usuarios/');
    }

    public function borrar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return $this->response->setJSON(['success' => false, 'msg' => 'No tienes permiso.']);
        }

        try {
            if($this->usuarios->find($id)) {
                $this->usuarios->delete($id);
                return $this->response->setJSON(['success' => true, 'msg' => 'Usuario eliminado correctamente.']);
            } else {
                return $this->response->setJSON(['success' => false, 'msg' => 'El usuario no existe.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }
    public function editar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $usuario = $this->usuarios->where('id', $id)->first(); 

        if (!$usuario) {
            return redirect()->to(base_url() . 'public/usuarios/');
        }

        $context = [
            'usuario' => $usuario,
            'titulo'  => "Edición usuario",
            'pagname' => 'Gestión/Edición usuario'
        ];

        echo view('panel/header', $context);
        echo view('usuarios/editar', $context); 
        echo view('panel/footer');
    }

    public function actualizar($id)
    {
        if (!isset($this->sesion->id_usuario)) {
            return redirect()->to(base_url() . 'public/login');
        }

        $data = [
            'usuario' => $this->request->getPost('usuario'), 
            'nombre'  => $this->request->getPost('nombre'),
        ];

        $clave_nueva = $this->request->getPost('password');

        if (!empty($clave_nueva)) {
            $hash = password_hash($clave_nueva, PASSWORD_DEFAULT);
            $data['password'] = $hash; 
        }

        $this->usuarios->update($id, $data);

        return redirect()->to(base_url() . 'public/usuarios/');
    }
}