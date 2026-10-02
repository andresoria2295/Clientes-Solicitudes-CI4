<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Cliente extends Entity
{
    // Atributos que representan a un cliente.
    protected $attributes = [
        'id'       => null,
        'nombre'   => null,
        'apellido' => null,
        'email'    => null,
        'telefono' => null,
    ];

    // Conversión automática de tipos de datos.
    protected $casts = [
        'id' => 'integer',
    ];
}
