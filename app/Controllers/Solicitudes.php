<?php

namespace App\Controllers;

use App\Models\SolicitudModel;
use App\Models\ClienteModel;
use App\Entities\Solicitud;

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

        // 7. Guardamos el registro.
        $resultado = $solicitudModel->insert($datosValidados);

        if ($resultado === false) {

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'general' => 'No se pudo registrar la solicitud.'
                ]);
        }

        // 8. Redirigimos al listado.
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



    // Mostrar el formulario de edición de una solicitud.
    public function editar(int $id)
    {
        helper('form');

        // 1. Instanciamos nuestros modelos.
        $solicitudModel = new SolicitudModel();
        $clienteModel = new ClienteModel();

        // 2. Recuperamos la solicitud existente.
        $solicitud = $solicitudModel->find($id);

        // 3. Comprobamos que exista.
        if (!$solicitud) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'La solicitud solicitada no existe.'
            );
        }

        // 4. Recuperamos los clientes disponibles.
        $clientes = $clienteModel
            ->orderBy('nombre', 'ASC')
            ->findAll();

        // 5. Enviamos ambos conjuntos de datos a nuestra vista.
        return view('solicitudes/editar', [
            'solicitud' => $solicitud,
            'clientes'  => $clientes
        ]);
    }


    // Actualizar una solicitud existente.
    public function actualizar(int $id)
    {
        // 1. Instanciamos el modelo.
        $solicitudModel = new SolicitudModel();

        // 2. Buscamos la solicitud que queremos modificar.
        $solicitud = $solicitudModel->find($id);

        // 3. Comprobamos que exista.
        if (!$solicitud) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'La solicitud solicitada no existe.'
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

        // 6. Verificamos que los datos sean válidos.
        if (!$this->validateData($datos, $reglas)) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Recuperamos únicamente los campos validados.
        $datosValidados = $this->validator->getValidated();

        // 7. Comprobamos que el cliente seleccionado exista.
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

        // 8. Preparamos el identificador validado.
        $datosValidados['cliente_id'] = $clienteId;

        // 9. Actualizamos el registro existente.
        try {

            $resultado = $solicitudModel->update($id, $datosValidados);

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {

            log_message('error', 'Error al actualizar solicitud: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'general' => 'No se pudo actualizar la solicitud.'
                ]);
        }

        // 10. Comprobamos el resultado.
        if ($resultado === false) {

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'general' => 'No se pudo guardar la modificación.'
                ]);
        }

        // 11. Volvemos al listado con un mensaje de éxito.
        return redirect()->to(site_url('solicitudes'))
            ->with('success', 'Solicitud actualizada correctamente.');
    }


    //Eliminar una solicitud existente.
    public function eliminar(int $id)
    {
        // 1. Instanciamos nuestro modelo.
        $solicitudModel = new SolicitudModel();

        // 2. Buscamos la solicitud por su ID.
        $solicitud = $solicitudModel->find($id);

        // 3. Si no existe, devolvemos un error 404.
        if (!$solicitud) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'La solicitud que intentás eliminar no existe.'
            );
        }

        // 4. Intentamos eliminar el registro.
        try {

            $resultado = $solicitudModel->delete($id);

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {

            // Registramos el error técnico en los logs.
            log_message(
                'error',
                'Error al eliminar solicitud ' . $id . ': ' . $e->getMessage()
            );

            // Mostramos un mensaje comprensible al usuario.
            return redirect()->to(site_url('solicitudes'))
                ->with('error', 'No se pudo eliminar la solicitud.');
        }

        // 5. Comprobamos el resultado.
        if ($resultado === false) {

            return redirect()->to(site_url('solicitudes'))
                ->with('error', 'La operación de eliminación no pudo completarse.');
        }

        // 6. Confirmamos la eliminación.
        return redirect()->to(site_url('solicitudes'))
            ->with('success', 'Solicitud eliminada correctamente.');
    }

}

