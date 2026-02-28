<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaccionesModel extends Model
{
    protected $table      = 'transacciones';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['cliente_id','proveedor_id','tipo_comprobante', 
    'numero_comprobante', 'fecha', 'monto', 'observaciones', 'fecha_alta', 
    'fecha_edicion', 'fecha_borrado', 'forma_pago', 'pagado', 'id_usuario', 'archivo_pdf'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edicion';
    protected $deletedField  = 'fecha_borrado';

    protected $validationRules      = [];
    protected $validationMessages   = [];
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

    



    
    public function getTransaccionesCompletas()
    {
        return $this->select('
                transacciones.*,
                clientes.razon_social as nombre_cliente,
                proveedores.razon_social as nombre_proveedor,
                COALESCE(clientes.cuit, proveedores.cuit) as cuit
            ')
            ->join('clientes', 'clientes.id = transacciones.cliente_id', 'left')
            ->join('proveedores', 'proveedores.id = transacciones.proveedor_id', 'left')
            ->orderBy('transacciones.fecha', 'DESC')
            ->orderBy('transacciones.id', 'DESC') // Agregado para ordenar por ID también
            ->findAll();
    }

    
    public function calcularSaldoCliente($cliente_id)
    {
        $cargos = $this->selectSum('monto')
            ->where('cliente_id', $cliente_id)
            ->whereIn('tipo_comprobante', ['factura', 'nota_debito'])
            ->get()
            ->getRowArray();

        $total_cargos = $cargos['monto'] ?? 0;

        $descargos = $this->selectSum('monto')
            ->where('cliente_id', $cliente_id)
            ->whereIn('tipo_comprobante', ['nota_credito', 'pago'])
            ->get()
            ->getRowArray();

        $total_descargos = $descargos['monto'] ?? 0;

        return $total_cargos - $total_descargos;
    }

    
    public function calcularSaldoProveedor($proveedor_id)
    {
        $cargos = $this->selectSum('monto')
            ->where('proveedor_id', $proveedor_id)
            ->whereIn('tipo_comprobante', ['factura', 'nota_debito'])
            ->get()
            ->getRowArray();

        $total_cargos = $cargos['monto'] ?? 0;

        $descargos = $this->selectSum('monto')
            ->where('proveedor_id', $proveedor_id)
            ->whereIn('tipo_comprobante', ['nota_credito', 'pago'])
            ->get()
            ->getRowArray();

        $total_descargos = $descargos['monto'] ?? 0;

        return $total_cargos - $total_descargos;
    }

    public function getSaldosClientes()
    {
        $clientes = $this->db->table('transacciones')
            ->select('cliente_id')
            ->where('cliente_id IS NOT NULL')
            ->groupBy('cliente_id')
            ->get()
            ->getResultArray();

        $saldos = [];
        foreach ($clientes as $cliente) {
            $saldos[$cliente['cliente_id']] = $this->calcularSaldoCliente($cliente['cliente_id']);
        }

        return $saldos;
    }

    public function getSaldosProveedores()
    {
        $proveedores = $this->db->table('transacciones')
            ->select('proveedor_id')
            ->where('proveedor_id IS NOT NULL')
            ->groupBy('proveedor_id')
            ->get()
            ->getResultArray();

        $saldos = [];
        foreach ($proveedores as $proveedor) {
            $saldos[$proveedor['proveedor_id']] = $this->calcularSaldoProveedor($proveedor['proveedor_id']);
        }

        return $saldos;
    }

    
    public function getTransaccionesCliente($cliente_id)
    {
        return $this->select('
                transacciones.*,
                clientes.razon_social as nombre_cliente
            ')
            ->join('clientes', 'clientes.id = transacciones.cliente_id', 'left')
            ->where('transacciones.cliente_id', $cliente_id)
            ->orderBy('transacciones.fecha', 'ASC')
            ->orderBy('transacciones.id', 'ASC')
            ->findAll();
            //asc para que queden en ascendente
    }

    
    public function getTransaccionesProveedor($proveedor_id)
    {
        return $this->select('
                transacciones.*,
                proveedores.razon_social as nombre_proveedor
            ')
            ->join('proveedores', 'proveedores.id = transacciones.proveedor_id', 'left')
            ->where('transacciones.proveedor_id', $proveedor_id)
            ->orderBy('transacciones.fecha', 'ASC')
            ->orderBy('transacciones.id', 'ASC')
            ->findAll();
    }

    public function getTotalesCaja()
{
    $ingresos = $this->db->table('transacciones')
        ->selectSum('monto', 'total')//suma todos los campos de la columna monto y se le asigna el alias total
        ->where('tipo_comprobante', 'pago')
        ->where('cliente_id IS NOT NULL')
        ->get()
        ->getRow()
        ->total ?? 0;

    $egresos = $this->db->table('transacciones')
        ->selectSum('monto', 'total')
        ->where('tipo_comprobante', 'pago')
        ->where('proveedor_id IS NOT NULL')
        ->get()
        ->getRow()
        ->total ?? 0;

    return [
        'ingresos' => $ingresos,
        'egresos'  => $egresos,
        'saldo'    => $ingresos - $egresos
    ];
}

public function getPendientes($tipo_entidad, $id_entidad)
{
    $columna = ($tipo_entidad == 'cliente') ? 'cliente_id' : 'proveedor_id';

    return $this->select('id, tipo_comprobante, numero_comprobante, fecha, monto, observaciones')
                ->where($columna, $id_entidad)
                ->where('fecha_borrado IS NULL')
                ->where('pagado', 0)
                ->whereIn('tipo_comprobante', ['factura', 'nota_debito'])
                ->orderBy('fecha', 'ASC')
                ->findAll();
}

}

