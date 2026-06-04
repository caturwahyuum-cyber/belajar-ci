<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// CRUD Produk
$routes->group('produk', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
});
$routes->get('keranjang', 'KeranjangController::index', ['filter' => 'auth']);
$routes->get('pemasukan', 'PemasukanPengeluaranController::pemasukan', ['filter' => 'auth']);
$routes->get('pengeluaran', 'PemasukanPengeluaranController::pengeluaran', ['filter' => 'auth']);
$routes->get('stok', 'StokBarangController::index', ['filter' => 'auth']);
$routes->get('user', 'UserController::index', ['filter' => 'auth']);
