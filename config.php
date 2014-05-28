<?php
// --------------------------------------------------
// COMMON CONFIG SETTINGS
// If your apps will have common config settings,
// add them here.  When you setup your config files,
// you will merge them with this one.
// --------------------------------------------------

$commonConfig = array(

	'view'           => new \Reactive\View(),         // Use the Reactive view class
	'default_class'  => 'Root',                       // What controller class will be used for the root directory
	'404_template'   => '404.php',                    // Path to the view to use as a 404 page

);
 
return $commonConfig;