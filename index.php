<?php
// Require the Slim Framework
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

// Include important reactive core files
require 'reactive/helpers.php';
require 'reactive/controller.php';
require 'settings.php';

// Instantiate a new app
$app = new \Reactive\App($settings);

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
require_once 'routes.php';

// Dynamic Routes
// Auto-generate routes based on the URI
$app->generate_routes($app);

// END ROUTES
// ------------------------------


// Run the application
$app->run();