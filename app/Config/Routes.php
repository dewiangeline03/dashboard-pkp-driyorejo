<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'PowerBIController::index', ['filter' => ['auth', 'verified']]);

$routes->get('/user/home', 'PowerBIController::index', ['filter' => ['auth', 'verified']]);


//Auth
$routes->get('/login', 'Auth\LoginController::login', ['filter' => 'noauth']);
$routes->post('/auth', 'Auth\LoginController::auth', ['filter' => 'noauth']);
$routes->get('/logout', 'Auth\LoginController::logout', ['filter' => 'auth']);
$routes->post('/validate-password', 'Auth\LoginController::validatePassword', ['filter' => ['auth', 'verified']]);
$routes->get('/validate-password', 'Auth\LoginController::changePassword', ['filter' => ['auth', 'verified']]);

//data-capaian
$routes->get('/data-capaian', 'PKP\DataCapaianController::index', ['filter' => ['auth', 'verified']]);
$routes->post('/data-capaian/update', 'PKP\DataCapaianController::update', ['filter' => ['auth', 'verified']]);
$routes->get('/data-capaian/download-excel', 'PKP\DataCapaianController::downloadExcel', ['filter' => ['auth', 'verified']]);
$routes->get('/data-capaian/print-pdf', 'PKP\DataCapaianController::printPdf', ['filter' => ['auth', 'verified']]);

//User Manajemen
$routes->get('/users', 'Admin\UserController::index', ['filter' => ['auth', 'checkPermission', 'verified']]);
$routes->get('/users/(:segment)', 'Admin\UserController::getData/$1', ['filter' => ['auth', 'checkPermission', 'verified']]);
$routes->post('/users/add', 'Admin\UserController::add', ['filter' => ['auth', 'checkPermission', 'verified']]);
$routes->delete('/users/(:segment)', 'Admin\UserController::delete/$1', ['filter' => ['auth', 'checkPermission', 'verified']]);
$routes->post('/users/update/(:segment)', 'Admin\UserController::update/$1', ['filter' => ['auth', 'checkPermission', 'verified']]);
$routes->post('/users/reset-password', 'Admin\UserController::resetPassword', ['filter' => ['auth', 'checkPermission', 'verified']]);

//data-manajer
$routes->get('/data-manajer', 'PKP\DataManajerController::index',['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/create', 'PKP\DataManajerController::create',['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/update', 'PKP\DataManajerController::update',['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/reauthDelete', 'PKP\DataManajerController::reauthDelete', ['filter' => ['auth','checkPermission','verified']]);

//instrumen
$routes->get('/data-manajer/instrumen', 'PKP\InstrumenController::index', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/instrumen/add', 'PKP\InstrumenController::add', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/instrumen/update', 'PKP\InstrumenController::update', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/instrumen/reauthDelete', 'PKP\InstrumenController::reauthDelete', ['filter' => ['auth','checkPermission','verified']]);

//program
$routes->get('/data-manajer/program', 'PKP\ProgramController::index', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/program/add', 'PKP\ProgramController::add', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/program/update', 'PKP\ProgramController::update', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/program/reauthDelete', 'PKP\ProgramController::reauthDelete', ['filter' => ['auth','checkPermission','verified']]);

//variabel
$routes->get('/data-manajer/variabel', 'PKP\VariabelController::index', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/variabel/add', 'PKP\VariabelController::add', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/variabel/update', 'PKP\VariabelController::update', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/variabel/reauthDelete', 'PKP\VariabelController::reauthDelete', ['filter' => ['auth','checkPermission','verified']]);

//sub-variabel
$routes->get('/data-manajer/sub-variabel', 'PKP\SubVariabelController::index', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/sub-variabel/add', 'PKP\SubVariabelController::add', ['filter'=>['auth','checkPermission','verified']]);
$routes->post('/data-manajer/sub-variabel/update', 'PKP\SubVariabelController::update', ['filter'=>['auth','checkPermission','verified']]);
$routes->delete('/data-manajer/sub-variabel/(:segment)', 'PKP\SubVariabelController::delete/$1',['filter'=>['auth','checkPermission','verified']]);
