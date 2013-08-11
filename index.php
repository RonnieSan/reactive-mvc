<?php
// Start a session
session_cache_limiter(false);
session_start();

// Require the Slim Framework
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

// Include important reactive core files
require 'reactive/helpers.php';
require 'reactive/controller.php';
require 'config.php';

// Instantiate a new app and register the Reactive autoloader
$app = new \Reactive\App($config);
$app->register_autoloader();


// ------------------------------
// APP CONSTANTS

define('ROOT', $_SERVER['DOCUMENT_ROOT']);

// END APP CONSTANTS
// ------------------------------


// ------------------------------
// MIDDLEWARE
// Add middleware to the application

// $app->add(new \SomeMiddleWare());

// END MIDDLEWARE
// ------------------------------


// ------------------------------
// HOOKS
// Add your hooks here

// $app->hook('the.hook.name', function () use ($app) {
//     // Do something
// });

// END HOOKS
// ------------------------------


// ------------------------------
// ROUTES

// Manual Routes
// Include the list of manually set routes
require 'routes.php';

// Dynamic Routes
// Auto-generate routes based on the URI
$app->generate_routes($app);

// END ROUTES
// ------------------------------


// Run the application
$app->run();