<?php

namespace App\Services;

use RuntimeException;
use Throwable;

class ClienteExternoService
{
    private string $baseUrl = 'https://jsonplaceholder.typicode.com';

    public function obtenerCliente(int $id): array
    {
        $http = service('curlrequest');

        try {

            $respuesta = $http->get(
            $this->baseUrl . '/users/' . $id,
            [
                'http_errors' => false,
                'timeout'     => 5,

                // Forzamos IPv4 porque cURL en este entorno presenta problemas al resolver el dominio mediante IPv6.
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
            ]
        );

        } catch (Throwable $e) {

            throw new RuntimeException(
                'No fue posible comunicarse con la API externa.',
                0,
                $e
            );

        }

        $statusCode = $respuesta->getStatusCode();

        if ($statusCode !== 200) {

            throw new RuntimeException(
                'La API externa respondió con HTTP ' . $statusCode . '.'
            );

        }

        $datos = json_decode(
            $respuesta->getBody(),
            true
        );

        if (!is_array($datos) || empty($datos)) {

            throw new RuntimeException(
                'La API externa devolvió una respuesta inválida.'
            );

        }

        return $datos;
    }
}