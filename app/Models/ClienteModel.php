<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'nombre',
        'apellido',
        'email',
        'telefono'
    ];
}