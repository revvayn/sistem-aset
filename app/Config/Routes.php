<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Route Utama / Default (Redirect ke login atau asset)
$routes->get('/', 'Asset::index', ['filter' => 'auth']);

// Auth Routes
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::processLogin');
$routes->match(['get', 'post'], 'logout', 'Auth::logout');
$routes->get('generate-admin', 'Auth::generate');

// Protected Routes (Butuh Login)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Assets Management (Disesuaikan dengan Controller & View Asset)
    $routes->get('asset', 'Asset::index');
    $routes->get('asset/create', 'Asset::create');
    $routes->get('asset/get-components/(:num)', 'Asset::getComponents/$1');
    $routes->post('asset/store', 'Asset::store');
    $routes->get('asset/edit/(:num)', 'Asset::edit/$1');
    $routes->post('asset/update/(:num)', 'Asset::update/$1');
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