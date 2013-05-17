<?php
namespace Reactive;

Class Router
{
	protected $request;
	protected $routes;
	protected $settings;
 
	public function __construct() {
		$env = \Slim\Environment::getInstance();
		$this->request = new \Slim\Http\Request($env);
		$this->routes  = array();

		$settings = require("settings.php");
        $this->settings = $settings;
	}

	// Add routes to an associative array - Class:Function@Method
	public function addRoutes($routes) {
		foreach ($routes as $route => $path) {

			// Set the default method
			$method = 'any';

			// Override the default method
			if (strpos($path, '@') !== false) {
				list($path, $method) = explode('@', $path);
			}
			
			// Retrieve the callback
			$function = $this->processCallback($path);
			$r = new \Slim\Route($route, $function);
			$r->setHttpMethods(strtoupper($method));

			// Add the route to the routes array
			array_push($this->routes, $r);
		}
	}

	// Process the callback function
	protected function processCallback($path) {

		$class = 'Root';

		if (strpos($path, ':') !== false) {
			list($class, $path) = explode(':', $path);
		}

		$function = ($path != '') ? $path : 'index';

		$func = function() use ($class, $function) {
			$class = '\\Controllers\\' . $class;
			$class = new $class();

			$args = func_get_args();

			call_func(array($class, $function), $args);

		};

		return $func;
	}

	// Run the route
	public function run() {

		$matched = FALSE;
		$uri     = $this->request->getResourceUri();
		$method  = $this->request->getMethod();
		$args    = array();

		// Match manually set routes first
		foreach ($this->routes as $i => $route) {
			if ($route->matches($uri)) {
				if ($route->supportsHttpMethod($method) || $route->supportsHttpMethod("ANY")) {

					$func = $route->getCallable();
					$args = array_values($route->getParams());

					// Call the route function
					call_func($func, $args);

					// The route matched, don't show a 404 error
					$matched = TRUE;
				}
			}
		}

		// Then try to match an auto route
		if (!$matched) {

			// Break the URI into an array
			$uriValues = explode('/', $uri);
			$uriValues = array_filter($uriValues, function($value) {
							 return ($value != '');
						 });
			$uriValues = array_values($uriValues);

			// Get the class, function, and arguments
			if (count($uriValues) > 0) {
				
				$class     = $uriValues[0];
				$function  = isset($uriValues[1]) ?: '';
				$args      = array_slice($uriValues, 2);
				$path      = "{$class}:{$function}";
				$method    = strtolower($method);

			} else {
				
				// Get the path for the home page
				if (isset($this->settings['home.path'])) {
					$path = $this->settings['home.path'];
				} else {
					$path = 'Root:index';
				}

				$pathValues = explode(':', $path);
				$class      = $pathValues[0];
				$function   = $pathValues[1];

			}

			$namespace = "\\Controllers\\" . ucwords($class);

			if (class_exists($namespace)) {
				
				// Check if a function matches the specific method
				if (is_callable($namespace . "::{$function}_{$method}")) {
					$r = new \Slim\Route($uri, $this->processCallback("{$path}_{$method}"));
				}

				// Check if a function matches ANY method
				elseif (is_callable($namespace . "::{$function}")) {
					$r = new \Slim\Route($uri, $this->processCallback($path));
				}

			} else {

				// Try the Root controller
				// Check if a function matches the specific method
				if (is_callable("\\Controllers\\Root::{$class}_{$method}")) {
					$r = new \Slim\Route($uri, $this->processCallback("{$class}_{$method}"));
				}

				// Check if a function matches ANY method
				elseif (is_callable("\\Controllers\\Root::{$class}")) {
					$r = new \Slim\Route($uri, $this->processCallback($class));
				}

			}

			// No matching route was found or created, it's probably a 404 error
			if (!isset($r) || !is_object($r)) {

				if (isset($this->settings['error.404.path'])) {
					$path = $this->settings['error.404.path'];
				}

				$r = new \Slim\Route($uri, $this->processCallback($path));

			}

			// Call the auto route callback
			$func = $r->getCallable();
			call_func($func, $args);

		}

	}

}