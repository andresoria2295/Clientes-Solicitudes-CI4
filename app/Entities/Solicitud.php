<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Solicitud extends Entity
{
    // Atributos que representan a una solicitud.
    protected $attributes = [
        'id'          => null,
        'cliente_id'  => null,
        'asunto'      => null,
        'descripcion' => null,
        'estado'      => null,
    ];

    // Conversión automática de tipos de datos.
    protected $casts = [
        'id'         => 'integer',
        'cliente_id' => 'integer',
    ];
}
