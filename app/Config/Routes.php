<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public TV Landing & Verification Routes
$routes->get('/', 'Display\LandingController::index');
$routes->post('display/verify-pin', 'Display\LandingController::verifyPin');

// TV Display & SSE Routes
$routes->get('display/(:segment)/playlist', 'Display\DisplayController::getPlaylistJson/$1');
$routes->get('display/(:segment)/events', 'Display\SseController::events/$1');
$routes->get('display/(:segment)', 'Display\DisplayController::index/$1', ['filter' => 'tv_pin']);

// Admin Auth Routes
$routes->get('login', 'Auth\AuthController::login');
$routes->post('login', 'Auth\AuthController::attemptLogin');
$routes->get('logout', 'Auth\AuthController::logout');
$routes->get('auth/logout', 'Auth\AuthController::logout');

// Admin Routes (Protected by AuthFilter)
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Category Routes (Admin & Superadmin)
    $routes->group('category', static function ($routes) {
        $routes->get('/', 'Admin\CategoryController::index');
        $routes->get('list', 'Admin\CategoryController::list');
        $routes->post('store', 'Admin\CategoryController::store');
        $routes->get('get/(:num)', 'Admin\CategoryController::getJson/$1');
        $routes->post('update/(:num)', 'Admin\CategoryController::update/$1');
        $routes->post('delete/(:num)', 'Admin\CategoryController::delete/$1');
    });

    // Content Management Routes (Admin & Superadmin)
    $routes->group('content', static function ($routes) {
        $routes->get('/', 'Admin\ContentController::index');
        $routes->get('list', 'Admin\ContentController::list');
        $routes->post('store-image', 'Admin\ContentController::storeImage');
        $routes->post('store-batch', 'Admin\ContentController::storeBatch');
        $routes->post('update-image/(:num)', 'Admin\ContentController::updateImage/$1');
        $routes->post('store-video', 'Admin\ContentController::storeVideo');
        $routes->post('update-video/(:num)', 'Admin\ContentController::updateVideo/$1');
        $routes->post('store-chart', 'Admin\ContentController::storeChart');
        $routes->post('update-chart/(:num)', 'Admin\ContentController::updateChart/$1');
        $routes->get('get/(:num)', 'Admin\ContentController::getJson/$1');
        $routes->post('delete/(:num)', 'Admin\ContentController::delete/$1');
        $routes->post('toggle-status/(:num)', 'Admin\ContentController::toggleStatus/$1');
    });

    // Playlist Reorder Routes (Admin & Superadmin)
    $routes->group('playlist', static function ($routes) {
        $routes->get('/', 'Admin\PlaylistController::index');
        $routes->get('manage/(:num)', 'Admin\PlaylistController::index/$1');
        $routes->get('get/(:num)', 'Admin\PlaylistController::getPlaylistJson/$1');
        $routes->post('reorder', 'Admin\PlaylistController::reorder');
    });

    // TV Management Routes (Superadmin Only)
    $routes->group('tv', ['filter' => 'superadmin'], static function ($routes) {
        $routes->get('/', 'Admin\TvController::index');
        $routes->get('list', 'Admin\TvController::list');
        $routes->post('store', 'Admin\TvController::store');
        $routes->get('get/(:num)', 'Admin\TvController::getJson/$1');
        $routes->post('update/(:num)', 'Admin\TvController::update/$1');
        $routes->post('delete/(:num)', 'Admin\TvController::delete/$1');
        $routes->post('regenerate-pin/(:num)', 'Admin\TvController::regeneratePin/$1');
    });

    // Superadmin Only Routes (User Management)
    $routes->group('users', ['filter' => 'superadmin'], static function ($routes) {
        $routes->get('/', 'Admin\UserController::index');
        $routes->get('list', 'Admin\UserController::list');
        $routes->post('store', 'Admin\UserController::store');
        $routes->get('get/(:num)', 'Admin\UserController::getJson/$1');
        $routes->post('update/(:num)', 'Admin\UserController::update/$1');
        $routes->post('delete/(:num)', 'Admin\UserController::delete/$1');
        $routes->post('toggle-status/(:num)', 'Admin\UserController::toggleStatus/$1');
    });
});
