<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use CodeIgniter\API\ResponseTrait;

class Clientes extends BaseController
{
    use ResponseTrait;

    protected $format = 'json';


    /*
     * GET /api/clientes
     *
     * Obtener todos los clientes.
     */
    public function index()
    {
        $clienteModel = new ClienteModel();

        $clientes = $clienteModel->findAll();

        return $this->respond($clientes, 200);
    }


    /*
     * GET /api/clientes/{id}
     *
     * Obtener un cliente individual.
     */
    public function ver(int $id)
    {
        $clienteModel = new ClienteModel();

        $cliente = $clienteModel->find($id);

        if ($cliente === null) {
            return $this->failNotFound(
                'El cliente solicitado no existe.'
            );
        }

        return $this->respond($cliente, 200);
    }


    /*
     * POST /api/clientes
     *
     * Registrar un nuevo cliente mediante JSON.
     */
    public function guardar()
    {
        // 1. Verificar que se envíe contenido JSON.

        $contentType = $this->request->getHeaderLine('Content-Type');

        if (stripos($contentType, 'application/json') !== 0) {
            return $this->respond([
                'mensaje' => 'El contenido debe enviarse como application/json.'
            ], 415);
        }


        // 2. Obtener el JSON recibido como array asociativo.

        try {
            $datos = $this->request->getJSON(true);
        } catch (\Exception $e) {
            return $this->respond([
                'mensaje' => 'El JSON enviado tiene un formato incorrecto.'
            ], 400);
        }

        // Esperamos un objeto JSON, no un texto, número o listado.

        if (!is_array($datos) || array_is_list($datos)) {
            return $this->respond([
                'mensaje' => 'Debés enviar un objeto JSON válido.'
            ], 400);
        }


        // 3. Definir las reglas de validación.

        $reglas = [
            'nombre'   => 'required|string|max_length[100]',
            'apellido' => 'permit_empty|string|max_length[100]',
            'email'    => 'required|string|valid_email|max_length[190]|is_unique[clientes.email]',
            'telefono' => 'permit_empty|string|max_length[30]'
        ];


        // 4. Validar los datos recibidos.

        if (!$this->validateData($datos, $reglas)) {

            return $this->respond([
                'mensaje' => 'Los datos enviados no son válidos.',
                'errores' => $this->validator->getErrors()
            ], 422);

        }


        // 5. Recuperar únicamente los datos validados.

        $datosValidados = $this->validator->getValidated();


        // 6. Guardar el cliente utilizando nuestro Model.

        $clienteModel = new ClienteModel();

        $nuevoId = $clienteModel->insert($datosValidados);


        // Controlar un posible fallo de inserción del Model.

        if ($nuevoId === false) {

            return $this->respond([
                'mensaje' => 'No fue posible registrar el cliente.',
                'errores' => $clienteModel->errors()
            ], 422);

        }


        // 7. Recuperar el registro recién creado.

        $clienteCreado = $clienteModel->find($nuevoId);


        // 8. Devolver el cliente y el código HTTP 201 Created.

        return $this->respondCreated([
            'mensaje' => 'Cliente registrado correctamente.',
            'cliente' => $clienteCreado
        ]);
    }
}
