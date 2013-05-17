<?php

require 'slim/Slim.php';
require 'reactive/autoload.php';
require 'helpers/development.php';

$app = new \Reactive\Router;

$routes = array(
	'/test/:one' => 'Something:awesome@get'
);
 
$app->addRoutes($routes);
 
$app->run();