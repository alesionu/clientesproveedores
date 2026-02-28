<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'codigo',
        'nombre', 
        'descripcion',
        'precio',
        'tipo',
        'fecha_alta',
        'fecha_edicion',
        'fecha_borrado'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false; 
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edicion';
    protected $deletedField  = 'fecha_borrado';

    protected $validationRules = [
        'id'     => 'permit_empty|is_natural_no_zero',        
        'codigo' => 'required|min_length[3]|max_length[50]|is_unique[productos.codigo,id,{id}]',
        'nombre' => 'required|min_length[3]|max_length[150]',
        'precio' => 'required|decimal',
        'tipo'   => 'required|in_list[producto,servicio]'
    ];
//prueba de mensajes predeterminados
//requesta de cambio de mensajes
    protected $validationMessages = [
        'codigo' => [
            'required' => 'El código es obligatorio',
            'is_unique' => 'Este código ya está registrado'
        ],
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres'
        ],
        'precio' => [
            'required' => 'El precio es obligatorio',
            'decimal' => 'El precio debe ser un número válido'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    
    public function getProductosActivos()
    {
        return $this->where('fecha_borrado', null)
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    
    public function getProductosPorTipo($tipo)
    {
        return $this->where('tipo', $tipo)
                    ->where('fecha_borrado', null)
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    
    public function getProductoPorCodigo($codigo)
    {
        return $this->where('codigo', $codigo)
                    ->where('fecha_borrado', null)
                    ->first();
    }
}