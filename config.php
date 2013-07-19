<?php
 
$config = array(
	
	'view'           => new \Reactive\View(),  // Use the Reactive view class

	'class.root'     => 'Root',                // What controller class will be used for the root
	'templates.path' => 'views',               // What folder has all the views
	'404.template'   => '404.php',             // Path to the view to use as a 404 page

	'db.type'        => 'mysql',               // Type of database you're using (for PDO library)
	'db.host'        => 'localhost',           // Host address for the database
	'db.database'    => 'retail_dev',          // Name of the database
	'db.username'    => 'retail_user',         // Username for conencting to the database
	'db.password'    => 'password1'            // Password for connecting to the database

);
 
return $config;