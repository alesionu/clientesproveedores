<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel; 

class Login extends BaseController
{
    protected $usuarios; 

    public function __construct() 
    {
        $this->usuarios = new UsuariosModel(); 
    }

    public function index()
    {
        $context = [
            'titulo' => "Login"
        ];
        
        if (session()->has('id_usuario')) {
            return redirect()->to(base_url() . 'public/panel');
        }

        echo view('login/login', $context);
    }

    public function login_validation()
    {
        $user = $this->request->getPost('usuario'); 
        $pass = $this->request->getPost('password');

        $datosUsuario = $this->usuarios->where('usuario', $user)->first();

        if ($datosUsuario != null) {
            if (password_verify($pass, $datosUsuario['password'])) {
                
                $data_session = [
                    'id_usuario' => $datosUsuario['id'],
                    'usuario'    => $datosUsuario['usuario'],
                    'nombre'     => $datosUsuario['nombre']
                ];

                $sesion = session();
                $sesion->set($data_session);

                return redirect()->to(base_url() . 'public/panel');
                
            } else {
                $session = session();
                //setFlashdata es para mostrar un mensaje de alerta
                //luego en el view se debe capturar ese mensaje
                $session->setFlashdata('msg', 'Contraseña incorrecta');
                return redirect()->to(base_url() . 'public/login');
            }
            
        } else {
            $session = session();
            $session->setFlashdata('msg', 'Usuario no encontrado');
            return redirect()->to(base_url() . 'public/login');
        }
    } 

    public function logout()
    {
        $sesion = session();
        $sesion->destroy();
        return redirect()->to(base_url() . 'public/login');
    }
}