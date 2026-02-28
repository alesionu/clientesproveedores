<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


//panel
$routes->get('/panel', 'Panel::index');

//login
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');
$routes->post('/login/validar', 'Login::login_validation');
$routes->get('login/logout', 'Login::logout');

//usuarios
$routes->get('/usuarios', 'Usuarios::index');
$routes->get('/usuarios/nuevo', 'Usuarios::nuevo');
$routes->post('/usuarios/guardar', 'Usuarios::guardar');
$routes->get('/usuarios/borrar/(:num)', 'Usuarios::borrar/$1');
$routes->get('/usuarios/editar/(:num)', 'Usuarios::editar/$1');
$routes->post('/usuarios/actualizar/(:num)', 'Usuarios::actualizar/$1');

//clientes
$routes->get('/clientes', 'Clientes::index');
$routes->get('/clientes/nuevo', 'Clientes::nuevo');
$routes->post('/clientes/guardar', 'Clientes::guardar');
$routes->get('/clientes/borrar/(:num)', 'Clientes::borrar/$1');
$routes->get('/clientes/editar/(:num)', 'Clientes::editar/$1');
$routes->post('/clientes/actualizar/(:num)', 'Clientes::actualizar/$1');

//proveedores
$routes->get('/proveedores', 'Proveedores::index');
$routes->get('/proveedores/nuevo', 'Proveedores::nuevo');
$routes->post('/proveedores/guardar', 'Proveedores::guardar');
$routes->get('/proveedores/borrar/(:num)', 'Proveedores::borrar/$1');
$routes->get('/proveedores/editar/(:num)', 'Proveedores::editar/$1');
$routes->post('/proveedores/actualizar/(:num)', 'Proveedores::actualizar/$1');

//transacciones
$routes->get('/transacciones', 'Transacciones::index');
$routes->get('/transacciones/nuevo', 'Transacciones::nuevo');
$routes->post('/transacciones/guardar', 'Transacciones::guardar');

// Pagos/Cobros
$routes->get('/transacciones/nuevo-pago', 'Transacciones::nuevo_pago', ['as' => 'transacciones.nuevo_pago']);
$routes->post('/transacciones/guardar-pago', 'Transacciones::guardar_pago', ['as' => 'transacciones.guardar_pago']);
$routes->post('/transacciones/obtener_deuda', 'Transacciones::obtener_deuda'); // ← ESTA ES LA QUE FALTA

// Clientes - Detalle/Cuenta Corriente
$routes->get('/clientes/detalle/(:num)', 'Clientes::detalle/$1', ['as' => 'clientes.detalle']);

// Proveedores - Detalle/Cuenta Corriente
$routes->get('/proveedores/detalle/(:num)', 'Proveedores::detalle/$1', ['as' => 'proveedores.detalle']);

$routes->get('/transacciones/totales-caja', 'Transacciones::totalesCaja', ['as' => 'transacciones.totales_caja']);

// Rutas de Productos
$routes->get('productos', 'Productos::index');
$routes->get('productos/nuevo', 'Productos::nuevo');
$routes->post('productos/guardar', 'Productos::guardar');
$routes->get('productos/editar/(:num)', 'Productos::editar/$1');
$routes->post('productos/actualizar', 'Productos::actualizar');
$routes->get('productos/eliminar/(:num)', 'Productos::eliminar/$1');

// API para productos (AJAX)
$routes->get('productos/obtener/(:num)', 'Productos::obtenerProducto/$1');
$routes->get('productos/listar-activos', 'Productos::listarActivos');

// Ruta para ver detalle de transacciones
$routes->get('transacciones/detalle/(:num)', 'Transacciones::ver_detalle/$1');

$routes->get('transacciones/ver_detalle/(:num)', 'Transacciones::ver_detalle/$1');

$routes->post('transacciones/obtener-deuda', 'Transacciones::obtener_deuda');

$routes->get('proveedores/nuevaNotaPedido/(:num)', 'Proveedores::nuevaNotaPedido/$1');
$routes->post('proveedores/guardarNotaPedido', 'Proveedores::guardarNotaPedido');
$routes->get('proveedores/descargarNotaPedido/(:num)', 'Proveedores::descargarNotaPedido/$1');
// Agrega esto en tu archivo Routes.php
$routes->get('proveedores/verNotaPedido/(:num)', 'Proveedores::verNotaPedido/$1');


// Grupo de rutas con prefijo 'public' para compatibilidad
$routes->post('proveedores/subirListaPrecios', 'Proveedores::subirListaPrecios');