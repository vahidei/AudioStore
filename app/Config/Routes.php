<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$sec = ADMIN_SEC;
// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/file/(:any)', 'Home::$1');
$routes->get('lang/{locale}', 'Language::index');
$routes->get($sec.'/admin', '\App\Modules\Admin\Controllers\Admin::index');
$routes->get($sec.'/admin/category', '\App\Modules\Admin\Controllers\Category::index');
$routes->get($sec.'/admin/track', '\App\Modules\Admin\Controllers\Track::index');
$routes->get($sec.'/admin/package', '\App\Modules\Admin\Controllers\Package::index');
$routes->get($sec.'/admin/discounts', '\App\Modules\Admin\Controllers\Discount::index');
$routes->get($sec.'/admin/user', '\App\Modules\Admin\Controllers\User::index');
$routes->get($sec.'/admin/statistics', '\App\Modules\Admin\Controllers\Statistics::index');
$routes->get($sec.'/admin/settings', '\App\Modules\Admin\Controllers\Settings::index');
$routes->get($sec.'/admin/discount/(:any)', '\App\Modules\Admin\Controllers\Discount::$1');
$routes->post($sec.'/admin/discount/(:any)', '\App\Modules\Admin\Controllers\Discount::$1');
$routes->get($sec.'/admin/category/(:any)', '\App\Modules\Admin\Controllers\Category::$1');
$routes->post($sec.'/admin/category/(:any)', '\App\Modules\Admin\Controllers\Category::$1');
$routes->get($sec.'/admin/track/(:any)', '\App\Modules\Admin\Controllers\Track::$1');
$routes->post($sec.'/admin/track/(:any)', '\App\Modules\Admin\Controllers\Track::$1');
$routes->get($sec.'/admin/package/(:any)', '\App\Modules\Admin\Controllers\Package::$1');
$routes->post($sec.'/admin/package/(:any)', '\App\Modules\Admin\Controllers\Package::$1');
$routes->get($sec.'/admin/user/(:any)', '\App\Modules\Admin\Controllers\User::$1');
$routes->get($sec.'/admin/(:any)', '\App\Modules\Admin\Controllers\Admin::$1');
$routes->post($sec.'/admin/(:any)', '\App\Modules\Admin\Controllers\Admin::$1');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
