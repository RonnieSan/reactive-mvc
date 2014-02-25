<?php
// --------------------------------------------------
// REACTIVE MVC
// ©2014 by Reactive Apps
// 
// Use this index file if you have different apps
// that are located in different sub-domains
// 
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
require 'Reactive/helpers.php';

// -- BASE CONTROLLER CLASSES --
// You should extend all your controllers from the
// controllers in this file.  You can also add your
// own base controllers to this file.
require 'Reactive/controller.php';

// -- COMMON CONFIG SETTINGS --
// Include a config file that will be merged with
// your app-specific configs.
require 'config.php';

// END REACTIVE MVC CORE FILES
// --------------------------------------------------


// --------------------------------------------------
// APPLICATION-SPECIFIC CONFIGS
// Require ALL your application configs here

require 'application1/config.php';
require 'application2/config.php';

// END APPLICATION-SPECIFIC CONFIGS
// --------------------------------------------------


// --------------------------------------------------
// INSTANTIATE YOUR APPLICATIONS
// Create an apps array and pass each of your app
// configs into new apps in the array

$app1 = new \Reactive\App($appConfig1);
$app2 = new \Reactive\App($appConfig2);

// END INSTANTIATE YOUR APPLICATIONS
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
define('ENVIRONMENT', 'development');

// END CONSTANTS
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
// RUN APPS (SUB-DOMAINS)
// Use this code block if you are separating your
// apps based on sub-domains

// Doing it this way, you can use sub-domains that include a .
// i.e. site.stage.local.whatever
if (strpos($_SERVER['HTTP_HOST'], 'someSubDomain') === 0) {

	// Run the Reactive Routing Engine
	$app1->generate_routes($app1);
	$app1->run();

}

if (strpos($_SERVER['HTTP_HOST'], 'someOtherSubDomain') === 0) {

	// Run the Reactive Routing Engine
	$app2->generate_routes($app2);
	$app2->run();

}

// END RUN APPS (SUB-FOLDERS)
// --------------------------------------------------