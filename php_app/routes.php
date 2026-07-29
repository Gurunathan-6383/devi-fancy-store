<?php
use App\Core\Response;
use App\Core\Request;
use App\Middleware\AuthMiddleware;
use App\Middleware\CustomerAuthMiddleware;

$router->setNotFoundHandler(function () {
    if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
        Response::json(['success' => false, 'message' => 'Not Found'], 404);
    }
    http_response_code(404);
    echo view('layouts.main', view('pages.404'), ['title' => 'Page Not Found']);
});

// Health check
$router->get('/api/health', function () {
    Response::json(['success' => true, 'message' => 'Devi Fancy Store API is running']);
});

// Auth routes (admin)
$router->post('/api/auth/login', 'AuthController@login');
$router->get('/api/auth/verify', 'AuthController@verify', [AuthMiddleware::class]);

// Customer auth routes
$router->post('/api/customer/signup', 'CustomerAuthController@signup');
$router->post('/api/customer/login', 'CustomerAuthController@login');
$router->get('/api/customer/verify', 'CustomerAuthController@verify', [CustomerAuthMiddleware::class]);

// Category routes
$router->get('/api/categories/active', 'CategoryController@getActive');
$router->get('/api/categories', 'CategoryController@getAll', [AuthMiddleware::class]);
$router->get('/api/categories/{id}', 'CategoryController@getById');
$router->post('/api/categories', 'CategoryController@create', [AuthMiddleware::class]);
$router->put('/api/categories/{id}', 'CategoryController@update', [AuthMiddleware::class]);
$router->delete('/api/categories/{id}', 'CategoryController@delete', [AuthMiddleware::class]);
$router->patch('/api/categories/{id}/toggle-visibility', 'CategoryController@toggleVisibility', [AuthMiddleware::class]);

// Product routes
$router->get('/api/products/active', 'ProductController@getActive');
$router->get('/api/products/featured', 'ProductController@getFeatured');
$router->get('/api/products/search', 'ProductController@search');
$router->get('/api/products', 'ProductController@getAll', [AuthMiddleware::class]);
$router->get('/api/products/slug/{slug}', 'ProductController@getBySlug');
$router->get('/api/products/{id}', 'ProductController@getById');
$router->post('/api/products', 'ProductController@create', [AuthMiddleware::class]);
$router->put('/api/products/{id}', 'ProductController@update', [AuthMiddleware::class]);
$router->delete('/api/products/{id}', 'ProductController@delete', [AuthMiddleware::class]);

// Catalogue routes
$router->get('/api/catalogues/published', 'CatalogueController@getPublished');
$router->get('/api/catalogues', 'CatalogueController@getAll', [AuthMiddleware::class]);
$router->get('/api/catalogues/slug/{slug}', 'CatalogueController@getBySlug');
$router->get('/api/catalogues/{id}', 'CatalogueController@getById');
$router->get('/api/catalogues/{id}/products', 'CatalogueController@getProducts');
$router->post('/api/catalogues', 'CatalogueController@create', [AuthMiddleware::class]);
$router->put('/api/catalogues/{id}', 'CatalogueController@update', [AuthMiddleware::class]);
$router->delete('/api/catalogues/{id}', 'CatalogueController@delete', [AuthMiddleware::class]);
$router->patch('/api/catalogues/{id}/toggle-publish', 'CatalogueController@togglePublish', [AuthMiddleware::class]);
$router->post('/api/catalogues/{id}/products', 'CatalogueController@addProduct', [AuthMiddleware::class]);
$router->delete('/api/catalogues/{id}/products/{productId}', 'CatalogueController@removeProduct', [AuthMiddleware::class]);

// Cart routes
$router->post('/api/cart/add', 'CartController@add');
$router->post('/api/cart/update', 'CartController@update');
$router->post('/api/cart/remove', 'CartController@remove');

// Order routes
$router->post('/api/orders', 'OrderController@placeOrder');
$router->get('/api/orders', 'OrderController@getAllOrders', [AuthMiddleware::class]);

// Settings routes
$router->get('/api/settings/public', 'SettingsController@getPublic');
$router->get('/api/settings', 'SettingsController@getAll', [AuthMiddleware::class]);
$router->put('/api/settings', 'SettingsController@update', [AuthMiddleware::class]);

// Wishlist routes
$router->get('/api/wishlist', 'WishlistController@getAll', [CustomerAuthMiddleware::class]);
$router->get('/api/wishlist/ids', 'WishlistController@getIds', [CustomerAuthMiddleware::class]);
$router->post('/api/wishlist/toggle', 'WishlistController@toggle', [CustomerAuthMiddleware::class]);

// Review routes
$router->get('/api/reviews/product/{productId}', 'ReviewController@getByProduct');
$router->post('/api/reviews', 'ReviewController@create', [CustomerAuthMiddleware::class]);

// Announcement routes
$router->get('/api/announcements/active', 'AnnouncementController@getActive');
$router->get('/api/announcements', 'AnnouncementController@getAll', [AuthMiddleware::class]);
$router->get('/api/announcements/{id}', 'AnnouncementController@getById');
$router->post('/api/announcements', 'AnnouncementController@create', [AuthMiddleware::class]);
$router->put('/api/announcements/{id}', 'AnnouncementController@update', [AuthMiddleware::class]);
$router->delete('/api/announcements/{id}', 'AnnouncementController@delete', [AuthMiddleware::class]);
$router->patch('/api/announcements/{id}/toggle-status', 'AnnouncementController@toggleStatus', [AuthMiddleware::class]);

// Content page routes
$router->get('/api/content-pages/public/{slug}', 'ContentPageController@getBySlug');
$router->get('/api/content-pages', 'ContentPageController@getAll', [AuthMiddleware::class]);
$router->get('/api/content-pages/{id}', 'ContentPageController@getById', [AuthMiddleware::class]);
$router->post('/api/content-pages', 'ContentPageController@create', [AuthMiddleware::class]);
$router->put('/api/content-pages/{id}', 'ContentPageController@update', [AuthMiddleware::class]);
$router->delete('/api/content-pages/{id}', 'ContentPageController@delete', [AuthMiddleware::class]);

// ============ SSR Page Routes ============

// Auth pages
$router->get('/admin/login', 'HomeController@adminLogin');
$router->get('/login', 'HomeController@login');
$router->get('/signup', 'HomeController@signup');

// Admin pages (protected)
$router->group('/admin', [AuthMiddleware::class], function ($router) {
    $router->get('', 'HomeController@dashboard');
    $router->get('/categories', 'HomeController@manageCategories');
    $router->get('/products', 'HomeController@manageProducts');
    $router->get('/catalogues', 'HomeController@manageCatalogues');
    $router->get('/orders', 'HomeController@manageOrders');
    $router->get('/customers', 'HomeController@manageCustomers');
    $router->get('/announcements', 'HomeController@manageAnnouncements');
    $router->get('/content-pages', 'HomeController@manageContentPages');
    $router->get('/settings', 'HomeController@adminSettings');
});

// Public pages
$router->get('/', 'HomeController@home');
$router->get('/categories', 'HomeController@categories');
$router->get('/categories/{slug}', 'HomeController@products');
$router->get('/catalogues', 'HomeController@catalogues');
$router->get('/catalogues/{slug}', 'HomeController@catalogueDetail');
$router->get('/products/{slug}', 'HomeController@productDetails');
$router->get('/search', 'HomeController@searchResults');
$router->get('/products', 'HomeController@products');
$router->get('/cart', 'HomeController@cart');
$router->get('/checkout', 'HomeController@checkout');
$router->get('/wishlist', 'HomeController@wishlist');
$router->get('/page/contact-us', 'HomeController@contactUs');
$router->get('/page/about-us', 'HomeController@aboutUs');
$router->get('/page/{slug}', 'HomeController@contentPage');

// Admin logout
$router->get('/admin/logout', function() {
    session_destroy();
    Response::redirect('/admin/login');
});

// Customer logout
$router->get('/logout', function() {
    unset($_SESSION['customer_user']);
    Response::redirect('/');
});
