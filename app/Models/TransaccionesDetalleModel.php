<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaccionesDetalleModel extends Model
{
    protected $table      = 'transacciones_detalle';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['transaccion_id','producto_id','cantidad','precio_unitario','subtotal','fecha_alta', 'descripcion_libre'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';

    
    public function getDetalleTransaccion($transaccion_id)
    {
        return $this->select('
                transacciones_detalle.*,
                productos.codigo,
                productos.nombre as nombre_producto,
                productos.tipo
            ')
            ->join('productos', 'productos.id = transacciones_detalle.producto_id')
            ->where('transacciones_detalle.transaccion_id', $transaccion_id)
            ->findAll();
    }

    
    public function guardarDetalle($transaccion_id, $items)
    {
        $this->db->transStart();
        
        foreach ($items as $item) {
            $data = [
                'transaccion_id'   => $transaccion_id,
                'producto_id'      => $item['producto_id'],
                'cantidad'         => $item['cantidad'],
                'precio_unitario'  => $item['precio_unitario'],
                'subtotal'         => $item['subtotal']
            ];
            
            $this->insert($data);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    
    public function eliminarDetalle($transaccion_id)
    {
        return $this->where('transaccion_id', $transaccion_id)->delete();
    }

    // Obtener detalle completo con información de productos
    public function getDetalleConProductos($transaccion_id)
    {
        return $this->select('
            transacciones_detalle.*,
            productos.nombre as producto_nombre,
            productos.codigo
        ')
        ->join('productos', 'productos.id = transacciones_detalle.producto_id', 'left')
        ->where('transacciones_detalle.transaccion_id', $transaccion_id)
        ->findAll();
    }

}