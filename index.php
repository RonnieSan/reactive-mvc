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

// ------------------------------
// APPLICATION CONFIGS

$appConfig1 = array(

	'name'           => 'application',                // The application name
	'route.folder'   => '',                           // The default folder for the app
	'templates.path' => 'application/views', // What folder has all the views

	// Databases
	'db.default' => array(
			'host'        => 'localhost',             // Host address for the database
			'type'        => 'mysql',                 // Type of database you're using (for PDO library)
			'database'    => 'dbName',                // Name of the database
			'username'    => 'username',              // Username for conencting to the database
			'password'    => 'password'               // Password for connecting to the database
		)

);

$appConfig1 = array_merge($config, $appConfig1);


$appConfig2 = array(

	'name'           => 'admin',                      // The application name
	'route.folder'   => 'admin',                           // The default folder for the app
	'templates.path' => 'admin/views', // What folder has all the views

	// Databases
	'db.default' => array(
			'host'        => 'localhost',             // Host address for the database
			'type'        => 'mysql',                 // Type of database you're using (for PDO library)
			'database'    => 'dbName',                // Name of the database
			'username'    => 'username',              // Username for conencting to the database
			'password'    => 'password'               // Password for connecting to the database
		)

);

$appConfig2 = array_merge($config, $appConfig2);

// END APPLICATION CONFIGS
// ------------------------------

// Instantiate new apps
$apps   = array();
$apps[] = new \Reactive\App($appConfig1);
$apps[] = new \Reactive\App($appConfig2);

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
// RUN APPS

foreach ($apps as $app) {

	if (!empty($app->config('route.folder'))) {

		if (strpos($_SERVER['REQUEST_URI'], $app->config('route.folder')) === 1) {

			$app->generate_routes($app);
			$app->run();

		}
	} else {

		$app->generate_routes($app);
		$app->run();
		
	}

}

// END RUN APPS
// ------------------------------