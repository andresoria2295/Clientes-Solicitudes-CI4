<?php

namespace App\Controllers;

use App\Models\ClienteModel;

class Clientes extends BaseController
{
    public function index()
    {
        // Creamos una instancia del modelo.
        $clienteModel = new ClienteModel();

        // Recuperamos todos los clientes desde MySQL.
        $clientes = $clienteModel->findAll();

        // Enviamos los resultados a nuestra vista.
        return view('clientes/index', [
            'clientes' => $clientes
        ]);
    }

    public function nuevo()
    {
        helper('form');

        return view('clientes/nuevo');
    }

    public function guardar()
    {
        // 1. Recuperamos los datos enviados por el formulario.
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

        // 3. Comprobamos si los datos cumplen las reglas.
        if (! $this->validateData($datos, $reglas)) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // 4. Obtenemos únicamente los datos validados.
        $datosValidados = $this->validator->getValidated();

        // 5. Guardamos el cliente mediante nuestro Model.
        $clienteModel = new ClienteModel();

        $clienteModel->insert($datosValidados);

        // 6. Volvemos al listado con un mensaje.
        return redirect()->to(site_url('clientes'))
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function ver($id)
    {
        // Instanciamos nuestro modelo.
        $clienteModel = new ClienteModel();

        // Buscamos exclusivamente el registro solicitado.
        $cliente = $clienteModel->find($id);

        // Comprobamos si existe.
        if (!$cliente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'El cliente solicitado no existe.'
            );
        }

        // Enviamos el cliente a nuestra vista.
        return view('clientes/ver', [
            'cliente' => $cliente
        ]);
    }

    public function editar(int $id)
    {
        // Instanciamos nuestro modelo.
        $clienteModel = new ClienteModel();

        // Buscamos al cliente por su identificador.
        $cliente = $clienteModel->find($id);

        // Si no existe, devolvemos un error 404.
        if (!$cliente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'El cliente solicitado no existe.'
            );
        }

        helper('form'); 
        
        // Enviamos sus datos al formulario de edición.
        return view('clientes/editar', [
            'cliente' => $cliente
        ]);
    }

    public function actualizar(int $id)
    {
        // 1. Instanciamos nuestro modelo.
        $clienteModel = new ClienteModel();

        // 2. Comprobamos que el cliente exista.
        $cliente = $clienteModel->find($id);

        if (!$cliente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'El cliente solicitado no existe.'
            );
        }

        // 3. Recuperamos la información del formulario.
        $datos = $this->request->getPost([
            'nombre',
            'apellido',
            'email',
            'telefono'
        ]);

        // 4. Definimos las reglas de validación.
        $reglas = [
            'nombre'   => 'required|string|max_length[100]',
            'apellido' => 'permit_empty|string|max_length[100]',
            'email'    => 'required|string|valid_email|max_length[190]|is_unique[clientes.email,id,' . $id . ']',
            'telefono' => 'permit_empty|string|max_length[30]'
        ];

        // 5. Validamos los datos recibidos.
        if (! $this->validateData($datos, $reglas)) {

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // 6. Recuperamos solamente los datos validados.
        $datosValidados = $this->validator->getValidated();

        // 7. Actualizamos el cliente existente.
        $clienteModel->update($id, $datosValidados);

        // 8. Redirigimos al listado.
        return redirect()->to(site_url('clientes'))
            ->with('success', 'Cliente actualizado correctamente.');
    }

    
    public function eliminar(int $id)
    {
        // 1. Instanciamos el modelo de clientes.
        $clienteModel = new ClienteModel();

        // 2. Comprobamos si el cliente existe.
        $cliente = $clienteModel->find($id);

        if (!$cliente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'El cliente solicitado no existe.'
            );
        }

        // 3. Eliminamos el registro mediante su ID.
        $resultado = $clienteModel->delete($id);

        // 4. Comprobamos si la operación se realizó.
        if (!$resultado) {
            return redirect()->to(site_url('clientes'))
                ->with('error', 'No se pudo eliminar el cliente.');
        }

        // 5. Regresamos al listado.
        return redirect()->to(site_url('clientes'))
            ->with('success', 'Cliente eliminado correctamente.');
    }

}