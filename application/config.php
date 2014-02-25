<?php
// --------------------------------------------------
// APPLICATION-SPECIFIC CONFIG
// Set application-specific settings here
// Call with: $this->app->config('settingName')
// --------------------------------------------------
 
$appConfig1 = array(

	'name'           => basename(__DIR__),            // The application name
	'app.rootpath'   => '',                           // A root folder for all auto routes
	'templates.path' => basename(__DIR__) . '/views', // What folder has all the views

	// Databases
	'db.default' => array(
			'host'        => 'localhost',             // Host address for the database
			'type'        => 'mysql',                 // Type of database you're using (for PDO library)
			'database'    => 'reactive_apps',         // Name of the database
			'username'    => 'reactive_apps',         // Username for conencting to the database
			'password'    => 'password1'              // Password for connecting to the database
		)

);

$appConfig1 = array_merge($commonConfig, $appConfig1);

return $appConfig1;