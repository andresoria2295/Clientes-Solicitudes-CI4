<?php

namespace App\Controllers;

use App\Models\SolicitudModel;
use App\Models\ClienteModel;
use App\Entities\Solicitud;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Solicitudes extends BaseController
{
    // Mostrar todas las solicitudes.
    public function index()
    {
        $solicitudModel = new SolicitudModel();

        $solicitudes = $solicitudModel->obtenerConClientes();

        return view('solicitudes/index', [
            'solicitudes' => $solicitudes
        ]);
    }

    // Mostrar el formulario de creación.
    public function nuevo()
    {
        helper('form');

        $clienteModel = new ClienteModel();

        $clientes = $clienteModel
            ->orderBy('nombre', 'ASC')
            ->findAll();

        return view('solicitudes/nuevo', [
            'clientes' => $clientes
        ]);
    }

    // Recibir y almacenar una nueva solicitud.
    public function guardar()
    {
        // 1. Recuperamos los campos enviados mediante POST.
        $datos = $this->request->getPost([
            'cliente_id',
            'asunto',
            'descripcion',
            'estado'
        ]);

        // 2. Definimos las reglas de validación.
        $reglas = [
            'cliente_id'   => 'required|is_natural_no_zero',
            'asunto'       => 'required|max_length[150]',
            'descripcion'  => 'required',
            'estado'       => 'required|in_list[Pendiente,En proceso,Resuelta]'
        ];

        // 3. Validamos los datos recibidos.
        if (! $this->validateData($datos, $reglas)) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $datosValidados = $this->validator->getValidated();

        // 4. Verificamos que el cliente seleccionado exista.
        $clienteModel = new ClienteModel();

        $clienteId = (int) $datosValidados['cliente_id'];

        $cliente = $clienteModel->find($clienteId);

        if (!$cliente) {

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'cliente_id' => 'El cliente seleccionado no existe.'
                ]);
        }

        // 5. Preparamos el identificador validado.
        $datosValidados['cliente_id'] = $clienteId;

        // 6. Instanciamos el modelo de solicitudes.
        $solicitudModel = new SolicitudModel();

        // 7. Construimos nuestra Entity con los datos validados.
        $solicitud = new Solicitud($datosValidados);

        // 8. Guardamos la Entity mediante nuestro Model.
        $resultado = $solicitudModel->insert($solicitud);

        if ($resultado === false) {

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'general' => 'No se pudo registrar la solicitud.'
                ]);
        }

        // 9. Redirigimos al listado.
        return redirect()->to(site_url('solicitudes'))
            ->with('success', 'Solicitud registrada correctamente.');
    }


    //Mostrar el detalle de una solicitud individual.

    // Mostrar el detalle individual utilizando una Entity.
    public function ver(int $id)
    {
        // 1. Instanciamos el modelo de solicitudes.
        $solicitudModel = new SolicitudModel();

        // 2. Recuperamos el registro como un objeto Entity.
        $solicitud = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($id);

        // 3. Comprobamos que exista.
        if ($solicitud === null) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'La solicitud solicitada no existe.'
            );
        }

        // 4. Recuperamos el cliente asociado.
        $clienteModel = new ClienteModel();

        $cliente = $clienteModel->find(
            (int) $solicitud->cliente_id
        );

        // 5. Enviamos ambos registros a la vista.
        return view('solicitudes/ver', [
            'solicitud' => $solicitud,
            'cliente'   => $cliente
        ]);
    }

    /*

    * Mostrar el formulario de edición utilizando una Entity.
    */
    public function editar(int $id)
    {
        // 1. Instanciamos los Models.
        $solicitudModel = new SolicitudModel();
        $clienteModel = new ClienteModel();

        // 2. Recuperamos la solicitud como Entity.
        $solicitud = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($id);

        // 3. Comprobamos si existe.
        if ($solicitud === null) {
            throw PageNotFoundException::forPageNotFound(
                'La solicitud indicada no existe.'
            );
        }

        // 4. Recuperamos los clientes para completar el SELECT.
        $clientes = $clienteModel
            ->orderBy('nombre', 'ASC')
            ->findAll();

        // 5. Cargamos el helper del formulario.
        helper('form');

        // 6. Enviamos los datos a nuestra vista.
        return view('solicitudes/editar', [
            'solicitud' => $solicitud,
            'clientes'  => $clientes
        ]);
    }

    /*
    * Actualizar una solicitud utilizando su Entity.
    */
    public function actualizar(int $id)
    {
        // 1. Instanciamos los Models.

        $solicitudModel = new SolicitudModel();
        $clienteModel = new ClienteModel();


        // 2. Recuperamos la solicitud como Entity.

        $solicitud = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($id);


        // 3. Verificamos que exista.

        if ($solicitud === null) {

            throw PageNotFoundException::forPageNotFound(
                'La solicitud indicada no existe.'
            );

        }


        // 4. Recuperamos los datos enviados por el formulario.

        $datos = $this->request->getPost([
            'cliente_id',
            'asunto',
            'descripcion',
            'estado'
        ]);


        // 5. Definimos las reglas de validación.

        $reglas = [
            'cliente_id'  => 'required|is_natural_no_zero',
            'asunto'      => 'required|max_length[150]',
            'descripcion' => 'required',
            'estado'      => 'required|in_list[Pendiente,En proceso,Resuelta]'
        ];


        // 6. Validamos los datos recibidos.

        if (! $this->validateData($datos, $reglas)) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());

        }


        // 7. Recuperamos únicamente los datos validados.

        $datosValidados = $this->validator->getValidated();


        // 8. Convertimos el identificador del cliente a entero.

        $clienteId = (int) $datosValidados['cliente_id'];

        $datosValidados['cliente_id'] = $clienteId;


        // 9. Comprobamos que el cliente asociado exista.

        $cliente = $clienteModel->find($clienteId);

        if ($cliente === null) {

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'cliente_id' => 'El cliente seleccionado no existe.'
                ]);

        }


        // 10. Aplicamos los nuevos datos a nuestra Entity.

        $solicitud->fill($datosValidados);


        // 11. Comprobamos si existen modificaciones.

        if (! $solicitud->hasChanged()) {

            return redirect()->to(site_url('solicitudes'))
                ->with(
                    'success',
                    'No se detectaron cambios en la solicitud.'
                );

        }


        // 12. Actualizamos la Entity mediante nuestro Model.

        try {

            $resultado = $solicitudModel->update(
                $id,
                $solicitud
            );

        } catch (DatabaseException $e) {

            log_message(
                'error',
                'Error al actualizar la solicitud ' . $id . ': ' . $e->getMessage()
            );

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'general' => 'No fue posible actualizar la solicitud. Verificá el cliente asociado.'
                ]);

        }


        // 13. Comprobamos el resultado del Model.

        if ($resultado === false) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $solicitudModel->errors());

        }


        // 14. Regresamos al listado.

        return redirect()->to(site_url('solicitudes'))
            ->with(
                'success',
                'Solicitud actualizada correctamente.'
            );
    }


    /*
    * Eliminar una solicitud utilizando su Entity.
    */
    public function eliminar(int $id)
    {
        // 1. Instanciamos el Model.

        $solicitudModel = new SolicitudModel();


        // 2. Recuperamos la solicitud como Entity.

        $solicitud = $solicitudModel
            ->asObject(Solicitud::class)
            ->find($id);


        // 3. Verificamos que la solicitud exista.

        if ($solicitud === null) {

            throw PageNotFoundException::forPageNotFound(
                'La solicitud indicada no existe.'
            );

        }


        // 4. Intentamos eliminar el registro mediante el Model.

        try {

            $resultado = $solicitudModel->delete($solicitud->id);

        } catch (DatabaseException $e) {

            // Registramos el detalle técnico en los logs.

            log_message(
                'error',
                'Error al eliminar la solicitud ' . $id . ': ' . $e->getMessage()
            );

            return redirect()->to(site_url('solicitudes'))
                ->with(
                    'error',
                    'Ocurrió un error al eliminar la solicitud.'
                );

        }


        // 5. Comprobamos el resultado del Model.

        if ($resultado === false) {

            return redirect()->to(site_url('solicitudes'))
                ->with(
                    'error',
                    'No fue posible eliminar la solicitud.'
                );

        }


        // 6. Regresamos al listado con un mensaje de éxito.

        return redirect()->to(site_url('solicitudes'))
            ->with(
                'success',
                'Solicitud eliminada correctamente.'
            );
    }


}

