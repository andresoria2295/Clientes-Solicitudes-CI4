<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('saludo', 'Inicio::index');
#Cuando alguien visite /saludo mediante una petición GET, ejecutá el método index() del controlador Inicio.
$routes->get('clientes', 'Clientes::index');
$routes->get('clientes/nuevo', 'Clientes::nuevo');
$routes->post('clientes', 'Clientes::guardar', ['filter' => 'csrf']);
$routes->get('clientes/(:num)', 'Clientes::ver/$1');
$routes->get('clientes/(:num)/editar', 'Clientes::editar/$1');
$routes->post('clientes/(:num)/actualizar', 'Clientes::actualizar/$1', ['filter' => 'csrf']);
$routes->post('clientes/(:num)/eliminar', 'Clientes::eliminar/$1', ['filter' => 'csrf']);

// Módulo de solicitudes

//Consultas
$routes->get('solicitudes', 'Solicitudes::index');
$routes->get('solicitudes/nuevo', 'Solicitudes::nuevo');
$routes->get('solicitudes/(:num)', 'Solicitudes::ver/$1');
$routes->get('solicitudes/(:num)/editar', 'Solicitudes::editar/$1');
$routes->post('solicitudes', 'Solicitudes::guardar', ['filter' => 'csrf']);

//Crear solicitud
$routes->post(
    'solicitudes',
    'Solicitudes::guardar',
    ['filter' => 'csrf']
);

//Actualizar solicitud
$routes->post(
    'solicitudes/(:num)/actualizar',
    'Solicitudes::actualizar/$1',
    ['filter' => 'csrf']
);

//Eliminar solicitud
$routes->post(
    'solicitudes/(:num)/eliminar',
    'Solicitudes::eliminar/$1',
    ['filter' => 'csrf']
);


/* ==========================================
   API REST - CLIENTES
========================================== */

// Obtener todos los clientes.
$routes->get('api/clientes', 'Api\Clientes::index');

// Obtener un cliente por ID.
$routes->get('api/clientes/(:num)', 'Api\Clientes::ver/$1');

// Registrar un cliente mediante JSON.
$routes->post('api/clientes', 'Api\Clientes::guardar');

// Actualizar un cliente mediante JSON.
$routes->put(
    'api/clientes/(:num)',
    'Api\Clientes::actualizar/$1'
);

// Eliminar un cliente mediante la API.
$routes->delete(
    'api/clientes/(:num)',
    'Api\Clientes::eliminar/$1'
);




/* ==========================================
   API REST - SOLICITUDES
========================================== */

// Obtener todas las solicitudes.
$routes->get(
    'api/solicitudes',
    'Api\Solicitudes::index'
);

// Obtener una solicitud por ID.
$routes->get(
    'api/solicitudes/(:num)',
    'Api\Solicitudes::ver/$1'
);

// Registrar una solicitud mediante JSON.
$routes->post(
    'api/solicitudes',
    'Api\Solicitudes::guardar'
);


// Actualizar una solicitud mediante JSON.
$routes->put(
    'api/solicitudes/(:num)',
    'Api\Solicitudes::actualizar/$1'
);


// Eliminar una solicitud mediante la API REST.
$routes->delete(
    'api/solicitudes/(:num)',
    'Api\Solicitudes::eliminar/$1'
);
