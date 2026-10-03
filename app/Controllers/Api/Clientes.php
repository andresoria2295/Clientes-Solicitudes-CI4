<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use CodeIgniter\API\ResponseTrait;
use App\Entities\Cliente;
use App\Models\SolicitudModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

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

        // 6. Construir la Entity Cliente.

        $cliente = new Cliente($datosValidados);


        // 7. Guardar la Entity utilizando nuestro Model.

        $clienteModel = new ClienteModel();

        $nuevoId = $clienteModel->insert($cliente);


        // Controlar un posible fallo de inserción del Model.

        if ($nuevoId === false) {

            return $this->respond([
                'mensaje' => 'No fue posible registrar el cliente.',
                'errores' => $clienteModel->errors()
            ], 422);

        }


        // 8. Recuperar el registro recién creado.

        $clienteCreado = $clienteModel->find($nuevoId);


        // 9. Devolver el cliente y el código HTTP 201 Created.

        return $this->respondCreated([
            'mensaje' => 'Cliente registrado correctamente.',
            'cliente' => $clienteCreado
        ]);
    }

    /*
    * Actualizar un cliente mediante JSON.
    */
    public function actualizar(int $id)
    {
        // 1. Instanciamos el Model.

        $clienteModel = new ClienteModel();


        // 2. Buscamos el cliente como Entity.

        $cliente = $clienteModel
            ->asObject(Cliente::class)
            ->find($id);


        // 3. Comprobamos si existe.

        if ($cliente === null) {
            return $this->failNotFound(
                'El cliente solicitado no existe.'
            );
        }


        // 4. Verificamos el tipo de contenido recibido.

        $contentType = $this->request->getHeaderLine('Content-Type');

        if (stripos($contentType, 'application/json') !== 0) {
            return $this->respond([
                'mensaje' => 'El contenido debe enviarse como application/json.'
            ], 415);
        }


        // 5. Recuperamos el JSON como array asociativo.

        try {
            $datos = $this->request->getJSON(true);
        } catch (\Exception $e) {
            return $this->respond([
                'mensaje' => 'El JSON enviado tiene un formato incorrecto.'
            ], 400);
        }

        if (!is_array($datos) || array_is_list($datos)) {
            return $this->respond([
                'mensaje' => 'Debés enviar un objeto JSON válido.'
            ], 400);
        }


        // 6. Definimos las reglas de validación.

        $reglas = [
            'nombre'   => 'required|string|max_length[100]',
            'apellido' => 'permit_empty|string|max_length[100]',

            'email' => 'required|string|valid_email|max_length[190]|is_unique[clientes.email,id,' . $id . ']',

            'telefono' => 'permit_empty|string|max_length[30]'
        ];


        // 7. Validamos los datos recibidos.

        if (!$this->validateData($datos, $reglas)) {
            return $this->respond([
                'mensaje' => 'Los datos enviados no son válidos.',
                'errores' => $this->validator->getErrors()
            ], 422);
        }


        // 8. Recuperamos únicamente los datos validados.

        $datosValidados = $this->validator->getValidated();


        // 9. Aplicamos los nuevos valores a nuestra Entity.

        $cliente->fill($datosValidados);


        // 10. Actualizamos solamente si existen cambios.

        if ($cliente->hasChanged()) {

            $resultado = $clienteModel->update($id, $cliente);

            if ($resultado === false) {
                return $this->respond([
                    'mensaje' => 'No fue posible actualizar el cliente.',
                    'errores' => $clienteModel->errors()
                ], 422);
            }
        }


        // 11. Recuperamos el registro actualizado como Entity.

        $clienteActualizado = $clienteModel
            ->asObject(Cliente::class)
            ->find($id);


        // 12. Devolvemos la respuesta JSON.

        return $this->respond([
            'mensaje' => 'Cliente actualizado correctamente.',
            'cliente' => $clienteActualizado->toArray()
        ], 200);
    }


    /*
    * Eliminar un cliente si no tiene solicitudes asociadas.
    */
    public function eliminar(int $id)
    {
        // 1. Instanciamos los Models.

        $clienteModel = new ClienteModel();

        $solicitudModel = new SolicitudModel();


        // 2. Buscamos el cliente como Entity.

        $cliente = $clienteModel
            ->asObject(Cliente::class)
            ->find($id);


        // 3. Verificamos si el cliente existe.

        if ($cliente === null) {

            return $this->failNotFound(
                'El cliente solicitado no existe.'
            );

        }


        // 4. Comprobamos si tiene solicitudes asociadas.

        $solicitudAsociada = $solicitudModel
            ->where('cliente_id', $id)
            ->first();


        // 5. Si tiene solicitudes, bloqueamos su eliminación.

        if ($solicitudAsociada !== null) {

            return $this->respond([
                'mensaje' => 'No se puede eliminar el cliente porque tiene solicitudes asociadas.'
            ], 409);

        }


        // 6. Intentamos eliminar el registro.

        try {

            $resultado = $clienteModel->delete($id);

        } catch (DatabaseException $e) {

            // Registramos el detalle técnico en los logs,
            // sin exponer información interna de MySQL en la API.

            log_message(
                'error',
                'Error al eliminar el cliente ' . $id . ': ' . $e->getMessage()
            );

            return $this->respond([
                'mensaje' => 'No fue posible eliminar el cliente. Verificá que no existan nuevas solicitudes asociadas.'
            ], 409);

        }


        // 7. Controlamos el resultado del Model.

        if ($resultado === false) {

            return $this->respond([
                'mensaje' => 'No fue posible eliminar el cliente.'
            ], 500);

        }

        // 8. Respondemos confirmando la eliminación.

        return $this->respond([
            'mensaje'    => 'Cliente eliminado correctamente.',
            'cliente_id' => $id
        ], 200);
    }

}
