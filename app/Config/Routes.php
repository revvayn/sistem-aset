<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Route Utama / Default (Membutuhkan Login)
$routes->get('/', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Auth Routes (Public)
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::processLogin');
$routes->match(['get', 'post'], 'logout', 'Auth::logout');

// Scan QR (publik, tanpa login) - info diambil dari database
$routes->get('scan/(:any)', 'Scan::index/$1');

// Protected Routes (Wajib Login)
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // ==========================================
    // 1. AKSES SEMUA USER (Admin, Staf, Viewer)
    // ==========================================
    $routes->group('', ['filter' => 'role:admin,staff,viewer'], static function ($routes) {
        $routes->get('dashboard', 'Dashboard::index');
        $routes->get('asset', 'Asset::index');
        $routes->get('asset/detail/(:num)', 'Asset::show/$1');
        $routes->get('asset/get-components/(:num)', 'Asset::getComponents/$1');
        $routes->get('asset/export', 'Asset::export');
        $routes->get('profile', 'Profile::index');
        $routes->post('profile/update', 'Profile::update');
        $routes->get('asset/(:num)/maintenances', 'Maintenance::index/$1');
        $routes->get('asset/(:num)/locations', 'AssetLocation::index/$1');
        $routes->get('api/assets', 'Api::assets');
        $routes->get('api/assets/(:num)', 'Api::asset/$1');
    });

    // ==========================================
    // 2. AKSES ADMIN & STAF (Input / Tambah Asset)
    // ==========================================
    $routes->group('', ['filter' => 'role:admin,staff'], static function ($routes) {
        $routes->get('asset/create', 'Asset::create');
        $routes->post('asset/store', 'Asset::store');
        $routes->get('asset/edit/(:num)', 'Asset::edit/$1');
        $routes->post('asset/update/(:num)', 'Asset::update/$1');
        $routes->post('asset/bulk', 'Asset::bulk');
        $routes->post('asset/(:num)/maintenances/store', 'Maintenance::store/$1');
        $routes->post('asset/(:num)/locations/store', 'AssetLocation::store/$1');
        $routes->post('maintenance/delete/(:num)', 'Maintenance::delete/$1');
    });

    // ==========================================
    // 3. KHUSUS ADMIN (Edit, Update, Hapus & Master)
    // ==========================================
    $routes->group('', ['filter' => 'role:admin'], static function ($routes) {
        // Asset Edit & Delete
        $routes->post('asset/delete/(:num)', 'Asset::delete/$1');
        $routes->get('asset/trashed', 'Asset::trashed');
        $routes->post('asset/restore/(:num)', 'Asset::restore/$1');
        $routes->post('asset/force-delete/(:num)', 'Asset::forceDelete/$1');

        // Master Category Management
        $routes->get('master/categories', 'MasterCategory::index');
        $routes->get('master/categories/create', 'MasterCategory::create');
        $routes->post('master/categories/store', 'MasterCategory::store');
        $routes->get('master/categories/edit/(:num)', 'MasterCategory::edit/$1');
        $routes->post('master/categories/update/(:num)', 'MasterCategory::update/$1');
        $routes->post('master/categories/delete/(:num)', 'MasterCategory::delete/$1');

        // Master Component Management
        $routes->get('master/components', 'MasterComponent::index');
        $routes->get('master/components/create', 'MasterComponent::create');
        $routes->post('master/components/store', 'MasterComponent::store');
        $routes->get('master/components/edit/(:num)', 'MasterComponent::edit/$1');
        $routes->post('master/components/update/(:num)', 'MasterComponent::update/$1');
        $routes->post('master/components/delete/(:num)', 'MasterComponent::delete/$1');

        // User Management
        $routes->get('users', 'UserController::index');
        $routes->get('backup', 'Backup::index');
        $routes->get('backup/download', 'Backup::download');
        $routes->post('backup/restore', 'Backup::restore');
        $routes->get('users/create', 'UserController::create');
        $routes->post('users/store', 'UserController::store');
        $routes->get('users/edit/(:num)', 'UserController::edit/$1');
        $routes->post('users/update/(:num)', 'UserController::update/$1');
        $routes->post('users/delete/(:num)', 'UserController::delete/$1');
    });

});