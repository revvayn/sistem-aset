<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Route Utama / Default (Membutuhkan Login)
$routes->get('/', 'Asset::index', ['filter' => 'auth']);

// Auth Routes (Public)
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::processLogin');
$routes->match(['get', 'post'], 'logout', 'Auth::logout');
$routes->get('generate-admin', 'Auth::generate');

// Protected Routes (Wajib Login)
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // ==========================================
    // 1. AKSES SEMUA USER (Admin, Staf, Viewer)
    // ==========================================
    $routes->group('', ['filter' => 'role:admin,staff,viewer'], static function ($routes) {
        $routes->get('asset', 'Asset::index');
        $routes->get('asset/get-components/(:num)', 'Asset::getComponents/$1');
    });

    // ==========================================
    // 2. AKSES ADMIN & STAF (Input / Tambah Asset)
    // ==========================================
    $routes->group('', ['filter' => 'role:admin,staff'], static function ($routes) {
        $routes->get('asset/create', 'Asset::create');
        $routes->post('asset/store', 'Asset::store');
        $routes->get('asset/edit/(:num)', 'Asset::edit/$1');
        $routes->post('asset/update/(:num)', 'Asset::update/$1');
    });

    // ==========================================
    // 3. KHUSUS ADMIN (Edit, Update, Hapus & Master)
    // ==========================================
    $routes->group('', ['filter' => 'role:admin'], static function ($routes) {
        // Asset Edit & Delete
        $routes->get('asset/delete/(:num)', 'Asset::delete/$1');

        // Master Category Management
        $routes->get('master/categories', 'MasterCategory::index');
        $routes->get('master/categories/create', 'MasterCategory::create');
        $routes->post('master/categories/store', 'MasterCategory::store');
        $routes->get('master/categories/edit/(:num)', 'MasterCategory::edit/$1');
        $routes->post('master/categories/update/(:num)', 'MasterCategory::update/$1');
        $routes->get('master/categories/delete/(:num)', 'MasterCategory::delete/$1');

        // Master Component Management
        $routes->get('master/components', 'MasterComponent::index');
        $routes->get('master/components/create', 'MasterComponent::create');
        $routes->post('master/components/store', 'MasterComponent::store');
        $routes->get('master/components/edit/(:num)', 'MasterComponent::edit/$1');
        $routes->post('master/components/update/(:num)', 'MasterComponent::update/$1');
        $routes->get('master/components/delete/(:num)', 'MasterComponent::delete/$1');

        // User Management
        $routes->get('users', 'UserController::index');
        $routes->get('users/create', 'UserController::create');
        $routes->post('users/store', 'UserController::store');
        $routes->get('users/edit/(:num)', 'UserController::edit/$1');
        $routes->post('users/update/(:num)', 'UserController::update/$1');
        $routes->get('users/delete/(:num)', 'UserController::delete/$1');
    });

});