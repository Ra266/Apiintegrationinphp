<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('userlist', 'Auth::index');
$routes->post('insertuser', "Auth::createuser");
$routes->post('createuser', "Auth::create");
$routes->get('singleuser/(:num)', "Auth::showSingleuserList/$1");
$routes->get("showupdateuser/(:num)", "Auth::updateuserlist/$1");
$routes->post('updateuser', 'Auth::updatedatastore');
$routes->delete('deleteuser/(:num)', "Auth::deletesinglelist/$1");
