<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Default route - Landing page
$routes->get('/', 'Landing::index');

// Auth routes
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/attempt', 'Auth::attempt');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/register', 'Auth::register');
$routes->get('/auth/logout', 'Auth::logout');

// Customer routes - requires login
$routes->group('customer', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Customer::dashboard');
    $routes->get('order/new', 'Customer::newOrder');
    $routes->post('order/create', 'Customer::createOrder');
    $routes->get('orders', 'Customer::orders');
    $routes->get('orders/(:num)', 'Customer::orderDetail/$1');
    $routes->post('orders/(:num)/cancel', 'Customer::cancelOrder/$1');
    $routes->get('profile', 'Customer::profile');
    $routes->post('profile/update', 'Customer::updateProfile');
});

// Admin routes - requires login + admin role
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('orders', 'Admin::orders');
    $routes->get('orders/(:num)', 'Admin::orderDetail/$1');
    $routes->post('orders/(:num)/status', 'Admin::updateStatus/$1');
    $routes->get('services', 'Admin::services');
    $routes->get('services/create', 'Admin::createService');
    $routes->post('services/create', 'Admin::createService');
    $routes->get('services/(:num)/edit', 'Admin::editService/$1');
    $routes->post('services/(:num)/edit', 'Admin::editService/$1');
    $routes->post('services/(:num)/delete', 'Admin::deleteService/$1');
    $routes->get('customers', 'Admin::customers');
    $routes->get('customers/(:num)', 'Admin::customerDetail/$1');
    $routes->get('reports', 'Admin::reports');

    // Promotions
    $routes->get('promotions', 'AdminPromotions::index');
    $routes->get('promotions/create', 'AdminPromotions::create');
    $routes->post('promotions/create', 'AdminPromotions::create');
    $routes->get('promotions/(:num)/edit', 'AdminPromotions::edit/$1');
    $routes->post('promotions/(:num)/edit', 'AdminPromotions::edit/$1');
    $routes->post('promotions/(:num)/delete', 'AdminPromotions::delete/$1');

    // FAQs
    $routes->get('faqs', 'AdminFaqs::index');
    $routes->get('faqs/create', 'AdminFaqs::create');
    $routes->post('faqs/create', 'AdminFaqs::create');
    $routes->get('faqs/(:num)/edit', 'AdminFaqs::edit/$1');
    $routes->post('faqs/(:num)/edit', 'AdminFaqs::edit/$1');
    $routes->post('faqs/(:num)/delete', 'AdminFaqs::delete/$1');

    // Settings
    $routes->get('settings', 'AdminSettings::index');
    $routes->post('settings', 'AdminSettings::update');

    // Payments
    $routes->get('orders/(:num)/payment', 'AdminPayment::showPayment/$1');
    $routes->post('orders/(:num)/payment', 'AdminPayment::processPayment/$1');
});
