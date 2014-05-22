<?php
// --------------------------------------------------
// COMMON CONFIG SETTINGS
// If your apps will have common config settings,
// add them here.  When you setup your config files,
// you will merge them with this one.
// --------------------------------------------------

$commonConfig = array(

	'view'           => new \Reactive\View(),         // Use the Reactive view class
	'class.default'  => 'Root',                       // What controller class will be used for the root directory
	'404.template'   => '404.php',                    // Path to the view to use as a 404 page

);
 
return $commonConfig;