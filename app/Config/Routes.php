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