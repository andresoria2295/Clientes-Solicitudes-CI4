<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ClienteExternoService;
use Throwable;

class ClientesExternos extends BaseController
{
    public function ver(int $id)
    {
        $servicio = new ClienteExternoService();

        try {

            $cliente = $servicio->obtenerCliente($id);

            return view('clientes_externos/ver', [
                'cliente' => $cliente
            ]);

        } catch (Throwable $e) {

            $detalle = $e->getMessage();

            if ($e->getPrevious() !== null) {
                $detalle .= ' | Causa original: ' . $e->getPrevious()->getMessage();
            }

            log_message(
                'error',
                'Error al consultar cliente externo ' . $id . ': ' . $detalle
            );

        }
    }
}