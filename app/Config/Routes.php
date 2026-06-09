<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

<<<<<<< Updated upstream
$routes->get('produk', 'ProdukController::index', ['filter' => 'auth']);
$routes->get('keranjang', 'KeranjangController::index', ['filter' => 'auth']);
=======
// CRUD Produk
$routes->group('produk', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
});
$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});
>>>>>>> Stashed changes
$routes->get('pemasukan', 'PemasukanPengeluaranController::pemasukan', ['filter' => 'auth']);
$routes->get('pengeluaran', 'PemasukanPengeluaranController::pengeluaran', ['filter' => 'auth']);
$routes->get('stok', 'StokBarangController::index', ['filter' => 'auth']);
$routes->get('user', 'UserController::index', ['filter' => 'auth']);
$routes->get('download', 'ProdukController::download');
