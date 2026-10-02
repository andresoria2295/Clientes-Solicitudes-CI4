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