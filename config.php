<?php
 
$config = array(

	'view'           => new \Reactive\View(),         // Use the Reactive view class

	'class.root'     => 'Root',                       // What controller class will be used for the root
	'404.template'   => '404.php',                    // Path to the view to use as a 404 page

);
 
return $config;