<?php
// REACTIVE FRAMEWORK
// Core Functions


// ------------------------------
// ROUTING FUNCTIONS
// Generate an auto-route based on the URI
function generate_routes($app) {

	// Get the request object and method
	$request   = $app->request();
	$method    = strtolower($request->getMethod());

	// Break up the URI
	$uri       = $request->getResourceUri();
	$uriValues = explode('/', $uri);
	$uriValues = array_filter($uriValues, function($value) {return $value != '';});
	$uriValues = array_values($uriValues);

	// What is the class and function we should try to call
	if (count($uriValues) > 0) {
		$class     = str_replace(' ', '_', ucwords(str_replace('-', ' ', $uriValues[0])));
		$function  = isset($uriValues[1]) ? str_replace('-', '_', $uriValues[1]) : 'index';
	} else {
		$class    = '';
		$function = 'index';
	}

	// Check if the class exists
	$nsClass = '\\Controllers\\' . $class;
	if (class_exists($nsClass)) {

		// Check if we should be calling the index function
		if ($function === 'index') {
			$route = "/{$uriValues[0]}(/)";
		} else {
			$route = "/{$uriValues[0]}/{$uriValues[1]}(/)(:args+)";
		}

		// Create the route
		create_route($app, $route, $nsClass, $function, $method);
	}

	// Try the root controller
	else {

		$nsClass = '\\Controllers\\Root';

		// Check if we should be calling the index function
		if ($function === 'index') {
			$route = "/";
		} else {
			$route = "/{$uriValues[0]}(/)(:args+)";
		}

		// Create the route
		create_route($app, $route, $nsClass, $function, $method);

	}

}

// Create a route by passing in variables
function create_route($app, $route, $class, $function, $method) {
	// Check for a method specific route
	if (is_callable($class . "::{$function}_{$method}")) {
		$app->{$method}($route, function($args = NULL) use ($app, $class, $function, $method) {
			$controller = new $class($app);
			call_func(array($controller, "{$function}_{$method}"), $args);
		});
	}

	// Check for a catch-all route
	else {

		if (is_callable($class . "::{$function}")) {
			$app->map($route, function($args = NULL) use ($app, $class, $function) {
				$controller = new $class($app);
				call_func(array($controller, $function), $args);
			})->via('GET', 'POST', 'PUT', 'DELETE');
		}

	}
}
// END ROUTING FUNCTIONS
// ------------------------------


// ------------------------------
// MISC FUNCTIONS

// Call an arbitrary function and pass variables to it
// This is needed because call_user_func_array is terribly slow

function call_func($function, $args = array()) {

	// Count the number of arguments passed in
	$argCount = count($args);

	// If an array was passed in...
	if (is_array($function)) {

		$class  = $function[0];
		$method = $function[1];

		// call_user_func_array is slow, this is slightly faster
		switch ($argCount) {

			case 0: 
				$class->{$method}();
				break;

			case 1:
				$class->{$method}($args[0]);
				break;

			case 2:
				$class->{$method}($args[0], $args[1]);
				break;

			case 3:
				$class->{$method}($args[0], $args[1], $args[2]);
				break;

			case 4:
				$class->{$method}($args[0], $args[1], $args[2], $args[3]);
				break;

			case 5:
				$class->{$method}($args[0], $args[1], $args[2], $args[3], $args[4]);
				break;

			default:
				call_user_func_array(array($class->method), $args);
				break;

		}

	} else {
	
		// call_user_func_array is slow, this is slightly faster
		switch ($argCount) {

			case 0: 
				$function();
				break;

			case 1:
				$function($args[0]);
				break;

			case 2:
				$function($args[0], $args[1]);
				break;

			case 3:
				$function($args[0], $args[1], $args[2]);
				break;

			case 4:
				$function($args[0], $args[1], $args[2], $args[3]);
				break;

			case 5:
				$function($args[0], $args[1], $args[2], $args[3], $args[4]);
				break;

			default:
				call_user_func_array($function, $args);
				break;

		}

	}

}

// END MISC FUNCTIONS
// ------------------------------