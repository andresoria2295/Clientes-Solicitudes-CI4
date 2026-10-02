<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudModel extends Model
{
    // Tabla que administra este modelo.
    protected $table = 'solicitudes';

    // Identificador principal.
    protected $primaryKey = 'id';

    // Devolveremos los resultados como arrays.
    protected $returnType = 'array';

    // Campos permitidos para insertar y actualizar.
    protected $allowedFields = [
        'cliente_id',
        'asunto',
        'descripcion',
        'estado'
    ];


    //Obtener las solicitudes junto con los datos del cliente.
    public function obtenerConClientes(): array
    {
        return $this
            ->select('
                solicitudes.id,
                solicitudes.cliente_id,
                solicitudes.asunto,
                solicitudes.descripcion,
                solicitudes.estado,
                clientes.nombre AS cliente_nombre,
                clientes.apellido AS cliente_apellido
            ')
            ->join(
                'clientes',
                'clientes.id = solicitudes.cliente_id'
            )
            ->orderBy('solicitudes.id', 'DESC')
            ->findAll();
    }


    //Obtener una solicitud específica con los datos del cliente.
    public function obtenerDetalleConCliente(int $id): ?array
    {
        return $this->builder()
            ->select('
                solicitudes.id,
                solicitudes.cliente_id,
                solicitudes.asunto,
                solicitudes.descripcion,
                solicitudes.estado,
                clientes.nombre AS cliente_nombre,
                clientes.apellido AS cliente_apellido
            ')
            ->join(
                'clientes',
                'clientes.id = solicitudes.cliente_id',
                'inner'
            )
            ->where('solicitudes.id', $id)
            ->get()
            ->getRowArray();
    }

}
