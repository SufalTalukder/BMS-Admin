<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ====================================================================================================== //
// ============================== Admin Panel Load View API's ROUTES Here =============================== //
// ====================================================================================================== //

// Default URL
$routes->get('/', function () {
    return redirect()->to('/admin/login');
});

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

    // Users View Routes
    $routes->get('users-service', 'UserController::users_view');

    // Activity View Routes
    $routes->get('activity-service', 'AuthActivityController::auth_activity_list_view');

    // Banner View Routes
    $routes->get('banners-service', 'BannerController::banner_list_view');

    // Language Settings View Routes
    $routes->get('language-service', 'LanguageController::language_settings_view');

    // Auth Profile View Routes
    $routes->get('profile-service', 'AuthUserController::auth_profile_view');

    // Category View Routes
    $routes->get('category-service', 'CategoryController::category_view');

    // Sub-Category View Routes
    $routes->get('sub-category-service', 'SubCategoryController::sub_category_view');

    // Product View Routes
    $routes->get('product-service', 'ProductController::product_view');

    // Wishlist View Routes
    $routes->get('wishlist-service', 'WishlistController::wishlist_view');

    // Cart View Routes
    $routes->get('cart-service', 'CartController::cart_view');
});

// Custom 404 Route
$routes->setAutoRoute(true);
$routes->set404Override('\App\Controllers\ErrorsController::error404');

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

// User APIs
$routes->post('add-user', 'Admin\UserController::addUserAJAX');
$routes->get('fetch-users', 'Admin\UserController::getAllUsersAJAX');
$routes->post('get-user-details', 'Admin\UserController::getUserDetailsAJAX');
$routes->post('update-user', 'Admin\UserController::updateUserAJAX');
$routes->post('delete-user', 'Admin\UserController::deleteUserAJAX');

// Auth Activity APIs
$routes->get('fetch-auth-activity', 'Admin\AuthActivityController::getAllAuthActivityAJAX');

// Banner User APIs
$routes->post('add-banner', 'Admin\BannerController::addBannerAJAX');
$routes->get('fetch-banners', 'Admin\BannerController::getAllBannersAJAX');
$routes->post('delete-banners', 'Admin\BannerController::deleteBannerAJAX');

// Language Settings APIs
$routes->post('add-language', 'Admin\LanguageController::addLanguageAJAX');
$routes->get('fetch-languages', 'Admin\LanguageController::getLanguagesAJAX');
$routes->post('get-language-details', 'Admin\LanguageController::getLanguageDetailsAJAX');
$routes->post('update-language', 'Admin\LanguageController::updateLanguageAJAX');
$routes->post('delete-language', 'Admin\LanguageController::deleteLanguageAJAX');

// Category APIs
$routes->post('add-category', 'Admin\CategoryController::addCategoryAJAX');
$routes->get('fetch-categories', 'Admin\CategoryController::getAllCategoriesAJAX');
$routes->post('get-category-details', 'Admin\CategoryController::getCategoryDetailsAJAX');
$routes->post('update-category', 'Admin\CategoryController::updateCategoryAJAX');
$routes->post('delete-category', 'Admin\CategoryController::deleteCategoryAJAX');

// Sub-Category APIs
$routes->post('add-sub-category', 'Admin\SubCategoryController::addSubCategoryAJAX');
$routes->get('fetch-sub-categories', 'Admin\SubCategoryController::getAllSubCategoriesAJAX');
$routes->post('get-sub-category-details', 'Admin\SubCategoryController::getSubCategoryDetailsAJAX');
$routes->post('update-sub-category', 'Admin\SubCategoryController::updateSubCategoryAJAX');
$routes->post('delete-sub-category', 'Admin\SubCategoryController::deleteSubCategoryAJAX');

// Product APIs
$routes->post('add-product', 'Admin\ProductController::addProductAJAX');
$routes->get('fetch-products', 'Admin\ProductController::getAllProductsAJAX');
$routes->post('get-product-details', 'Admin\ProductController::getProductDetailsAJAX');
$routes->post('update-product', 'Admin\ProductController::updateProductAJAX');
$routes->post('delete-product', 'Admin\ProductController::deleteProductAJAX');

// Wishlist APIs
$routes->post('add-wishlist', 'Admin\WishlistController::addWishlistAJAX');
$routes->get('fetch-wishlists', 'Admin\WishlistController::getAllWishlistsAJAX');
$routes->post('delete-wishlist', 'Admin\WishlistController::deleteWishlistAJAX');

// Cart APIs
$routes->get('fetch-carts', 'Admin\CartController::getAllCartsAJAX');
$routes->post('delete-cart', 'Admin\CartController::deleteCartAJAX');
