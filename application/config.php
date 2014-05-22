<?php
// --------------------------------------------------
// APPLICATION-SPECIFIC CONFIG
// Set application-specific settings here
// Call with: $this->app->config('settingName')
// --------------------------------------------------
 
$config = array(

	'app.name'       => 'My Application',                // The application name
	'app.folder'     => basename(__DIR__),               // A root folder for all auto routes
	'app.rooturi'    => '',                              // The root URI folder for the app
	'app.parents'    => array(),                         // An array of app folders that the autoloader falls back on
	'app.scripts'    => basename(__DIR__) . '/scripts',  // The folder containing the client-side framework scripts
	'templates.path' => basename(__DIR__) . '/views',    // What folder has all the views

	// Databases
	'db.default' => array(
			'host'        => 'localhost',                // Host address for the database
			'type'        => 'mysql',                    // Type of database you're using (for PDO library)
			'database'    => 'myDatabase',               // Name of the database
			'username'    => 'myDatabaseUser',           // Username for conencting to the database
			'password'    => 'myPassword'                // Password for connecting to the database
		)

);

$config = array_merge($commonConfig, $config);

return $config;