<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

use App\Models\SolicitudModel;
use App\Models\ClienteModel;

use App\Entities\Solicitud;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Solicitudes extends BaseController
{
    use ResponseTrait;

    protected $format = 'json';


    /*
     * GET /api/solicitudes
     *
     * Obtener todas las solicitudes.
     */
    public function index()
    {
        $solicitudModel = new SolicitudModel();

        // Recuperamos las solicitudes como Entities.
        $solicitudes = $solicitudModel
            ->asObject(Solicitud::class)
            ->findAll();

        // Convertimos cada Entity en un array.
        $solicitudesArray = array_map(
            static fn (Solicitud $solicitud): array => $solicitud->toArray(),
            $solicitudes
        );

        return $this->respond($solicitudesArray, 200);
    }


    /*
     * GET /api/solicitudes/{id}
     *
     * Obtener una solicitud individual.
     */
    public function ver(int $id)
    {
        $solicitudModel = new SolicitudModel();

        $solicitud = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($id);

        if ($solicitud === null) {
            return $this->failNotFound(
                'La solicitud indicada no existe.'
            );
        }

        return $this->respond(
            $solicitud->toArray(),
            200
        );
    }


    /*
     * POST /api/solicitudes
     *
     * Registrar una solicitud mediante JSON.
     */
    public function guardar()
    {
        // 1. Verificamos el tipo de contenido recibido.

        $contentType = $this->request->getHeaderLine('Content-Type');

        if (stripos($contentType, 'application/json') !== 0) {

            return $this->respond([
                'mensaje' => 'El contenido debe enviarse como application/json.'
            ], 415);

        }


        // 2. Recuperamos los datos JSON como array asociativo.

        try {

            $datos = $this->request->getJSON(true);

        } catch (\Exception $e) {

            return $this->respond([
                'mensaje' => 'El JSON enviado tiene un formato incorrecto.'
            ], 400);

        }


        // 3. Comprobamos que recibimos un objeto JSON válido.

        if (!is_array($datos) || array_is_list($datos)) {

            return $this->respond([
                'mensaje' => 'Debés enviar un objeto JSON válido.'
            ], 400);

        }


        // 4. Definimos las reglas de validación.

        $reglas = [
            'cliente_id'  => 'required|is_natural_no_zero',
            'asunto'      => 'required|string|max_length[150]',
            'descripcion' => 'required|string',
            'estado'      => 'required|in_list[Pendiente,En proceso,Resuelta]'
        ];


        // 5. Validamos los datos recibidos.

        if (!$this->validateData($datos, $reglas)) {

            return $this->respond([
                'mensaje' => 'Los datos enviados no son válidos.',
                'errores' => $this->validator->getErrors()
            ], 422);

        }


        // 6. Recuperamos únicamente los datos validados.

        $datosValidados = $this->validator->getValidated();

        $datosValidados['cliente_id'] = (int) $datosValidados['cliente_id'];


        // 7. Comprobamos que el cliente asociado realmente existe.

        $clienteModel = new ClienteModel();

        $cliente = $clienteModel->find(
            $datosValidados['cliente_id']
        );

        if ($cliente === null) {

            return $this->respond([
                'mensaje' => 'El cliente indicado no existe.',
                'errores' => [
                    'cliente_id' => 'Debés indicar un cliente registrado.'
                ]
            ], 422);

        }


        // 8. Construimos nuestra Entity Solicitud.

        $solicitud = new Solicitud($datosValidados);


        // 9. Instanciamos el Model.

        $solicitudModel = new SolicitudModel();


        // 10. Insertamos la Entity en MySQL.

        try {

            $nuevoId = $solicitudModel->insert($solicitud);

        } catch (DatabaseException $e) {

            log_message(
                'error',
                'Error al registrar solicitud desde API: ' . $e->getMessage()
            );

            return $this->respond([
                'mensaje' => 'No fue posible registrar la solicitud. Verificá el cliente asociado.'
            ], 409);

        }


        // 11. Controlamos el resultado de la inserción.

        if ($nuevoId === false) {

            return $this->respond([
                'mensaje' => 'No fue posible registrar la solicitud.',
                'errores' => $solicitudModel->errors()
            ], 422);

        }


        // 12. Recuperamos la solicitud creada como Entity.

        $solicitudCreada = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($nuevoId);


        // 13. Devolvemos HTTP 201 Created.

        return $this->respondCreated([
            'mensaje' => 'Solicitud registrada correctamente.',
            'solicitud' => $solicitudCreada->toArray()
        ]);
    }

    
    /*
    * Actualizar una solicitud mediante JSON.
    */
    public function actualizar(int $id)
    {
        // 1. Instanciamos los Models.

        $solicitudModel = new SolicitudModel();

        $clienteModel = new ClienteModel();


        // 2. Buscamos la solicitud existente como Entity.

        $solicitud = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($id);


        // 3. Comprobamos si existe.

        if ($solicitud === null) {

            return $this->failNotFound(
                'La solicitud indicada no existe.'
            );

        }


        // 4. Verificamos que recibimos contenido JSON.

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


        // 6. Comprobamos que sea un objeto JSON válido.

        if (!is_array($datos) || array_is_list($datos)) {

            return $this->respond([
                'mensaje' => 'Debés enviar un objeto JSON válido.'
            ], 400);

        }


        // 7. Definimos las reglas de validación.

        $reglas = [
            'cliente_id'  => 'required|is_natural_no_zero',
            'asunto'      => 'required|string|max_length[150]',
            'descripcion' => 'required|string',
            'estado'      => 'required|in_list[Pendiente,En proceso,Resuelta]'
        ];


        // 8. Validamos los datos recibidos.

        if (!$this->validateData($datos, $reglas)) {

            return $this->respond([
                'mensaje' => 'Los datos enviados no son válidos.',
                'errores' => $this->validator->getErrors()
            ], 422);

        }


        // 9. Recuperamos únicamente los datos validados.

        $datosValidados = $this->validator->getValidated();

        $datosValidados['cliente_id'] = (int) $datosValidados['cliente_id'];


        // 10. Comprobamos que el cliente asociado exista.

        $cliente = $clienteModel->find(
            $datosValidados['cliente_id']
        );

        if ($cliente === null) {

            return $this->respond([
                'mensaje' => 'El cliente indicado no existe.',
                'errores' => [
                    'cliente_id' => 'Debés indicar un cliente registrado.'
                ]
            ], 422);

        }


        // 11. Aplicamos los nuevos valores a nuestra Entity.

        $solicitud->fill($datosValidados);


        // 12. Actualizamos únicamente si existen modificaciones.

        if ($solicitud->hasChanged()) {

            try {

                $resultado = $solicitudModel->update(
                    $id,
                    $solicitud
                );

            } catch (DatabaseException $e) {

                log_message(
                    'error',
                    'Error al actualizar solicitud ' . $id . ': ' . $e->getMessage()
                );

                return $this->respond([
                    'mensaje' => 'No fue posible actualizar la solicitud. Verificá el cliente asociado.'
                ], 409);

            }


            // Controlamos un posible fallo del Model.

            if ($resultado === false) {

                return $this->respond([
                    'mensaje' => 'No fue posible actualizar la solicitud.',
                    'errores' => $solicitudModel->errors()
                ], 422);

            }

        }


        // 13. Recuperamos el registro actualizado como Entity.

        $solicitudActualizada = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($id);


        // 14. Respondemos mediante JSON y HTTP 200.

        return $this->respond([
            'mensaje' => 'Solicitud actualizada correctamente.',
            'solicitud' => $solicitudActualizada->toArray()
        ], 200);
    }

    
    /*
    * Eliminar una solicitud mediante su identificador.
    */
    public function eliminar(int $id)
    {
        // 1. Instanciamos nuestro Model.

        $solicitudModel = new SolicitudModel();


        // 2. Recuperamos la solicitud como Entity.

        $solicitud = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($id);


        // 3. Verificamos que exista.

        if ($solicitud === null) {

            return $this->failNotFound(
                'La solicitud indicada no existe.'
            );

        }


        // 4. Intentamos eliminar el registro de MySQL.

        try {

            $resultado = $solicitudModel->delete($id);

        } catch (DatabaseException $e) {

            // Guardamos el detalle técnico en los logs,
            // sin exponer información interna en la API.

            log_message(
                'error',
                'Error al eliminar solicitud ' . $id . ': ' . $e->getMessage()
            );

            return $this->respond([
                'mensaje' => 'Ocurrió un error al eliminar la solicitud.'
            ], 500);

        }


        // 5. Comprobamos el resultado de la operación.

        if ($resultado === false) {

            return $this->respond([
                'mensaje' => 'No fue posible eliminar la solicitud.'
            ], 500);

        }


        // 6. Confirmamos que la eliminación se realizó correctamente.

        return $this->respond([
            'mensaje'      => 'Solicitud eliminada correctamente.',
            'solicitud_id' => $id
        ], 200);
    }

}

