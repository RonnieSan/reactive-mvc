<?php
$start = microtime();

// --------------------------------------------------
// REACTIVE MVC
// ©2014 by Reactive Apps
// --------------------------------------------------


// --------------------------------------------------
// SESSION STUFF
// 
// If you're using anything that requires a session
// you should start a session here.

session_cache_limiter(false);
session_start();

// END SESSION STUFF
// --------------------------------------------------


// --------------------------------------------------
// SLIM FRAMEWORK
// This is where the Slim Framework files get called
// and the autoloader is registered

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

// END SLIM FRAMEWORK
// --------------------------------------------------


// --------------------------------------------------
// REACTIVE MVC CORE FILES
// Include important reactive core files

// -- HELPER FUNCTIONS --
// Include the helper functions
// Place all your helper functions in this file
require 'Reactive/Helpers.php';

// -- COMMON CONFIG SETTINGS --
// Include a config file that will be merged with
// your app-specific configs.
require 'config.php';

// END REACTIVE MVC CORE FILES
// --------------------------------------------------


// --------------------------------------------------
// APPLICATION-SPECIFIC CONFIGS
// Require ALL your application configs here

require 'application/config.php';

// END APPLICATION-SPECIFIC CONFIGS
// --------------------------------------------------


// --------------------------------------------------
// CONSTANTS
// Set your application constants here

// -- ROOT DIRECTORY --
// Good to have when working with file paths
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

// -- ENVIRONMENT --
// Change the way stuff works based on the current
// environment you're working in
$environment = d($_SERVER['environment'], 'development');
define('ENVIRONMENT', $environment);

// END CONSTANTS
// --------------------------------------------------


// --------------------------------------------------
// INSTANTIATE YOUR APPLICATIONS
// Create an apps array and pass each of your app
// configs into new apps in the array

$app = new \Reactive\App($config);

// END INSTANTIATE YOUR APPLICATIONS
// --------------------------------------------------


// --------------------------------------------------
// MIDDLEWARE
// Add middleware for all apps here

// $app->add(new \SomeMiddleWare());

// END MIDDLEWARE
// --------------------------------------------------


// --------------------------------------------------
// COMMON HOOKS
// Add hooks that apply to all your apps here
// App-specific hook are added in the App class

// $app->hook('the.hook.name', function () use ($app) {
//     // Do something
// });

// END COMMON HOOKS
// --------------------------------------------------


// --------------------------------------------------
// RUN APPS
// Use this code block if you are separating your

// Run the Reactive Routing Engine
$app->generate_routes($app);
$app->run();

// END RUN APPS
// --------------------------------------------------

var_dump(microtime() - $start);