<?php
 
$config = array(

	'name'           => basename(__DIR__),            // The application name
	'view'           => new \Reactive\View(),         // Use the Reactive view class

	'class.root'     => 'Root',                       // What controller class will be used for the root
	'templates.path' => basename(__DIR__) . '/views', // What folder has all the views
	'404.template'   => '404.php',                    // Path to the view to use as a 404 page

	// Databases
	'db.default' => array(
			'host'        => 'localhost',             // Host address for the database
			'type'        => 'mysql',                 // Type of database you're using (for PDO library)
			'database'    => 'dbName',                // Name of the database
			'username'    => 'username',              // Username for conencting to the database
			'password'    => 'password'               // Password for connecting to the database
		)

);
 
return $config;