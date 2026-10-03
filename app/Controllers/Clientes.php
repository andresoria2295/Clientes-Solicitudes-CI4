<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\SolicitudModel;
use App\Entities\Cliente;
use CodeIgniter\Exceptions\PageNotFoundException;

class Clientes extends BaseController
{

/*
 * GET /api/clientes
 *
 * Obtener todos los clientes utilizando Entities.
 */


    /*
    * GET /clientes
    *
    * Listar todos los clientes utilizando Entities.
    */
    public function index()
    {
        // 1. Instanciamos nuestro Model.
        $clienteModel = new ClienteModel();

        // 2. Recuperamos todos los registros como Entities Cliente.
        $clientes = $clienteModel
            ->asObject(Cliente::class)
            ->findAll();

        // 3. Enviamos las Entities a nuestra vista HTML.
        return view('clientes/index', [
            'clientes' => $clientes
        ]);
    }

    public function nuevo()
    {
        helper('form');

        return view('clientes/nuevo');
    }


    /*
    Obtener un cliente individual utilizando su Entity.
    */

    public function ver(int $id)
    {
        $clienteModel = new ClienteModel();

        // Recuperamos el registro utilizando nuestra Entity Cliente.
        $cliente = $clienteModel
            ->asObject(Cliente::class)
            ->find($id);

        // Si el cliente no existe, devolvemos un error 404.
        if ($cliente === null) {
            throw PageNotFoundException::forPageNotFound(
                'El cliente solicitado no existe.'
            );
        }

        // Enviamos la Entity a nuestra vista HTML.
        return view('clientes/ver', [
            'cliente' => $cliente
        ]);
    }

    /*
    * Registrar un cliente utilizando su Entity.
    */
    public function guardar()
    {
        // 1. Recuperamos los datos del formulario.

        $datos = $this->request->getPost([
            'nombre',
            'apellido',
            'email',
            'telefono'
        ]);


        // 2. Definimos las reglas de validación.

        $reglas = [
            'nombre'   => 'required|string|max_length[100]',
            'apellido' => 'permit_empty|string|max_length[100]',
            'email'    => 'required|string|valid_email|max_length[190]|is_unique[clientes.email]',
            'telefono' => 'permit_empty|string|max_length[30]'
        ];


        // 3. Validamos los datos.

        if (! $this->validateData($datos, $reglas)) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());

        }


        // 4. Recuperamos únicamente los datos validados.

        $datosValidados = $this->validator->getValidated();


        // 5. Construimos nuestra Entity Cliente.

        $cliente = new Cliente($datosValidados);


        // 6. Instanciamos el Model.

        $clienteModel = new ClienteModel();


        // 7. Guardamos la Entity en MySQL.

        $resultado = $clienteModel->insert($cliente);


        // 8. Controlamos un posible error de inserción.

        if ($resultado === false) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $clienteModel->errors());

        }


        // 9. Regresamos al listado.

        return redirect()->to(site_url('clientes'))
            ->with('success', 'Cliente registrado correctamente.');
    }


    /*
    * Mostrar el formulario de edición utilizando una Entity.
    */
    public function editar(int $id)
    {
        // 1. Instanciamos nuestro Model.

        $clienteModel = new ClienteModel();


        // 2. Recuperamos el cliente como Entity.

        $cliente = $clienteModel
            ->asObject(Cliente::class)
            ->find($id);


        // 3. Verificamos que exista.

        if ($cliente === null) {

            throw PageNotFoundException::forPageNotFound(
                'El cliente solicitado no existe.'
            );

        }


        // 4. Cargamos el helper utilizado por los formularios.

        helper('form');


        // 5. Enviamos nuestra Entity a la vista HTML.

        return view('clientes/editar', [
            'cliente' => $cliente
        ]);
    }


    /*
    * Actualizar un cliente utilizando su Entity.
    */
    public function actualizar(int $id)
    {
        // 1. Instanciamos nuestro Model.

        $clienteModel = new ClienteModel();


        // 2. Recuperamos el cliente como Entity.

        $cliente = $clienteModel
            ->asObject(Cliente::class)
            ->find($id);


        // 3. Comprobamos que el registro exista.

        if ($cliente === null) {

            throw PageNotFoundException::forPageNotFound(
                'El cliente solicitado no existe.'
            );

        }


        // 4. Recuperamos los datos enviados desde el formulario.

        $datos = $this->request->getPost([
            'nombre',
            'apellido',
            'email',
            'telefono'
        ]);


        // 5. Definimos las reglas de validación.

        $reglas = [
            'nombre'   => 'required|string|max_length[100]',
            'apellido' => 'permit_empty|string|max_length[100]',

            'email' => 'required|string|valid_email|max_length[190]|is_unique[clientes.email,id,' . $id . ']',

            'telefono' => 'permit_empty|string|max_length[30]'
        ];


        // 6. Validamos los datos recibidos.

        if (! $this->validateData($datos, $reglas)) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());

        }


        // 7. Recuperamos únicamente los datos validados.

        $datosValidados = $this->validator->getValidated();


        // 8. Aplicamos los nuevos valores a nuestra Entity Cliente.

        $cliente->fill($datosValidados);


        // 9. Comprobamos si existen modificaciones.

        if (! $cliente->hasChanged()) {

            return redirect()->to(site_url('clientes'))
                ->with('success', 'No se detectaron cambios en el cliente.');

        }


        // 10. Actualizamos la Entity mediante nuestro Model.

        $resultado = $clienteModel->update($id, $cliente);


        // 11. Comprobamos si el Model pudo completar la operación.

        if ($resultado === false) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $clienteModel->errors());

        }


        // 12. Regresamos al listado con un mensaje de confirmación.

        return redirect()->to(site_url('clientes'))
            ->with('success', 'Cliente actualizado correctamente.');
    }


    /*
    * Eliminar un cliente respetando sus solicitudes asociadas.
    */
    public function eliminar(int $id)
    {
        // 1. Instanciamos los Models.

        $clienteModel = new ClienteModel();

        $solicitudModel = new SolicitudModel();


        // 2. Recuperamos el cliente como Entity.

        $cliente = $clienteModel
            ->asObject(Cliente::class)
            ->find($id);


        // 3. Verificamos que el cliente exista.

        if ($cliente === null) {

            throw PageNotFoundException::forPageNotFound(
                'El cliente solicitado no existe.'
            );

        }


        // 4. Comprobamos si tiene solicitudes asociadas.

        $cantidadSolicitudes = $solicitudModel
            ->where('cliente_id', $cliente->id)
            ->countAllResults();


        // 5. Si tiene solicitudes, impedimos su eliminación.

        if ($cantidadSolicitudes > 0) {

            return redirect()->to(site_url('clientes'))
                ->with(
                    'error',
                    'No se puede eliminar el cliente porque tiene solicitudes asociadas.'
                );

        }


        // 6. Intentamos eliminar el registro mediante el Model.

        try {

            $resultado = $clienteModel->delete($cliente->id);

        } catch (DatabaseException $e) {

            // Guardamos el error técnico en los logs.

            log_message(
                'error',
                'Error al eliminar el cliente ' . $id . ': ' . $e->getMessage()
            );

            return redirect()->to(site_url('clientes'))
                ->with(
                    'error',
                    'No fue posible eliminar el cliente. Verificá que no tenga solicitudes asociadas.'
                );

        }


        // 7. Comprobamos el resultado del Model.

        if ($resultado === false) {

            return redirect()->to(site_url('clientes'))
                ->with(
                    'error',
                    'No fue posible eliminar el cliente.'
                );

        }


        // 8. Volvemos al listado con un mensaje de éxito.

        return redirect()->to(site_url('clientes'))
            ->with(
                'success',
                'Cliente eliminado correctamente.'
            );
    }


}