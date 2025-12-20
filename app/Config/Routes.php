<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ====================================================================================================== //
// ============================== Admin Panel Load View API's ROUTES Here =============================== //
// ====================================================================================================== //

// Admin Pages Load To View
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('', function () {
        return redirect()->to('/admin/login');
    });

    // Login View Routes
    $routes->get('login', 'LoginController::login_view');
    $routes->get('logout', 'LoginController::logout');

    // Auth Users View Routes
    $routes->get('auth-users-service', 'AuthUserController::auth_user_list_view');

    // Activity View Routes
    $routes->get('activity-service', 'AuthActivityController::auth_activity_list_view');

    // Banner View Routes
    $routes->get('banners-service', 'BannerController::banner_list_view');
});

// ====================================================================================================== //
// ================================ Admin Panel CRUD API's ROUTES Here ================================== //
// ====================================================================================================== //

// Admin Login APIs
$routes->post('submit-login', 'Admin\LoginController::submitLoginAJAX');

// Auth User APIs
$routes->post('add-auth-user', 'Admin\AuthUserController::addAuthUserAJAX');
$routes->get('fetch-auth-users', 'Admin\AuthUserController::getAllAuthUsersAJAX');
$routes->post('get-auth-user-details', 'Admin\AuthUserController::getAuthUserDetailsAJAX');
$routes->post('update-auth-user', 'Admin\AuthUserController::updateAuthUserAJAX');
$routes->post('delete-auth-user', 'Admin\AuthUserController::deleteAuthUserAJAX');

// Auth Activity APIs
$routes->get('fetch-auth-activity', 'Admin\AuthActivityController::getAllAuthActivityAJAX');

// Banner User APIs
$routes->post('add-banner', 'Admin\BannerController::addBannerAJAX');
$routes->get('fetch-banners', 'Admin\BannerController::getAllBannersAJAX');
$routes->post('delete-banner', 'Admin\BannerController::deleteBannerAJAX');
