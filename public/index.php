<?php 

use core\Router;

/**
 * Composer
 */
require '../vendor/autoload.php';

/**
 * Root Path
 */
define('ROOT_PATH', 'http://localhost/emps');

/**
 * Start Sessions
 */
session_start();

/**
 * Turn on output buffering
 */
ob_start();

/**
 * Twig
 */
// Twig_Autoloader::register();

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('core\Error::errorHandler');
set_exception_handler('core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'admin']);


$router->dispatch($_SERVER['QUERY_STRING']);
