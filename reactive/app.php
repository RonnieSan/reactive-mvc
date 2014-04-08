<?php
// REACTIVE FRAMEWORK
// Application Framework

namespace Reactive;

Class App extends \Slim\Slim
{

	public $db;
	
	public function __construct($settings) {
		parent::__construct($settings);

		// ROUTES
		// Include the list of manually set routes
		require $settings['name'] . '/routes.php';

		// HOOKS
		// Include a list of custom hooks
		require $settings['name'] . '/hooks.php';

		// Register the autoloader
		$this->register_autoloader();

		// Connect to the database
		// $this->db = new \Libraries\Database();
		// $this->db->connect();

		// Set the 404 page
		$this->notFound(function() {
			$this->render($this->config('404.template'));
		});
	}

	// ------------------------------
	// AUTOLOADER
	public static function autoload($className)
	{

		$app = \Slim\Slim::getInstance();

		// Get the base directory
		$baseDir = __DIR__;
		if (substr($baseDir, -strlen(__NAMESPACE__)) === __NAMESPACE__) {
			$baseDir = substr($baseDir, 0, -strlen(__NAMESPACE__));
		}

		$className = ltrim($className, '\\');

		// Get the unaltered path to the file
		$namespace = '';
		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$filePath  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
		}

		$filePathArray = explode(DIRECTORY_SEPARATOR, $filePath);
		$filePath      = $app->config('name') . DIRECTORY_SEPARATOR;

		// Check for an existing file path
		foreach ($filePathArray as $segment) {

			// Check if this segment is a directory
			if (is_dir($baseDir . $filePath . $segment)) {
				$filePath .= $segment . DIRECTORY_SEPARATOR;
				continue;
			}

			// Try the segment in lowercase with dashes
			if (is_dir($baseDir . $filePath . str_replace('_', '-', strtolower($segment)))) {
				$filePath .= str_replace('_', '-', strtolower($segment)) . DIRECTORY_SEPARATOR;
				continue;
			}

		}

		// Check if the unaltered file exists
		if (file_exists($filePath . $className . '.php')) {
			require_once $filePath . $className . '.php';
			return;
		}

		// Check if the lowercase file with dashes exists
		if (file_exists($filePath . str_replace('_', '-', strtolower($className)) . '.php')) {
			require_once $filePath . str_replace('_', '-', strtolower($className)) . '.php';
			return;
		}

	}

	
	// Register Slim's PSR-0 autoloader
	public function register_autoloader() {
		spl_autoload_register(__NAMESPACE__ . "\\App::autoload");
	}
	// END AUTOLOADER
	// ------------------------------

	// ------------------------------
	// ROUTING FUNCTIONS
	// Generate an auto-route based on the URI
	public function generate_routes() {

		// Get the request object and method
		$request   = $this->request();
		$method    = strtolower($request->getMethod());

		// Break up the URI
		$uri       = $request->getResourceUri();
		$uriValues = explode('/', $uri);
		$uriValues = array_filter($uriValues, function($value) {return $value != '';});
		$uriValues = array_values($uriValues);

		// Check if the first segment matches the app.rootpath in the config
		if (count($uriValues) > 0 && $uriValues[0] == $this->config('app.rootpath')) {
			array_shift($uriValues);
		}

		// Create an array of properly formatted namespaces
		$namespacedURI = array();

		foreach ($uriValues as $value) {
			$namespacedURI[] = str_replace(' ', '_', ucwords(str_replace('-', ' ', $value)));
		}

		// Use the root class unless something else matches
		$paramCount = 0;
		$function   = 'index';
		$argString  = '';

		// No URI was passed
		if (count($namespacedURI) === 0) {
			// There's no URI, try the root class
			if (class_exists('\\Controllers\\' . $this->config('class.root'))) {
				$class = '\\Controllers\\' . $this->config('class.root');

				// Check if the root class exists with a named function
				$this->_create_route_if_method_exists($class, 'index', 0, array(), $namespacedURI, $method);
			}
		}

		// Parse through the URI
		else {
			while (count($namespacedURI) > 0) {

				// Create a class namespace from the URL segments
				$namespacedClass = implode('\\', $namespacedURI);

				// Check if the folder exists with a root class
				if (class_exists('\\Controllers\\' . $namespacedClass . '\\' . $this->config('class.root'))) {
					$class = '\\Controllers\\' . $namespacedClass . '\\' . $this->config('class.root');

					// Check if the root class exists with a named function
					// We're checking if the index function exists in the first pass
					if ($this->_create_route_if_method_exists($class, $function, $paramCount, $uriValues, $namespacedURI, $method)) {
						break;
					}
				}

				// Check if the class exists with an index or named function
				if (class_exists('\\Controllers\\' . $namespacedClass)) {
					$class = '\\Controllers\\' . $namespacedClass;

					// Check if the class exists with a named function
					if ($this->_create_route_if_method_exists($class, $function, $paramCount, $uriValues, $namespacedURI, $method)) {
						break;
					}

					// Check if the class exists with an index function
					if ($this->_create_route_if_method_exists($class, 'index', $paramCount + 1, array_slice($uriValues, 0, count($namespacedURI)), $namespacedURI, $method)) {
						break;
					}
				}

				// Add one to the param count after the first iteration
				if ($function !== 'index') {
					$paramCount++;
				}

				// Remove the last item in the array and use it as the function name in the next run
				$function = strtolower(array_pop($namespacedURI));
			}

			// Nothing matched, try the Root class with parameters
			if (class_exists('\\Controllers\\' . $this->config('class.root'))) {
				$class = '\\Controllers\\' . $this->config('class.root');

				$this->_create_route_if_method_exists($class, $function, $paramCount, $uriValues, $namespacedURI, $method);
				$this->_create_route_if_method_exists($class, 'index', $paramCount + 1, array_slice($uriValues, 0, count($namespacedURI)), $namespacedURI, $method);
			}
		}
	}

	// Check if a method exists and if the number of params matches
	private function _create_route_if_method_exists($class, $function, $paramCount, $uriValues, $namespacedURI, $method) {

		// Check if the first argument is a callable method
		if (method_exists($class, $function)) {

			// Check if the method accepts parameters
			$reflection        = new \ReflectionMethod($class, $function);
			$numberOfParams    = $reflection->getNumberOfParameters();
			$numberOfReqParams = $reflection->getNumberOfRequiredParameters();

			$routeArray = array_slice($uriValues, 0, count($namespacedURI) + 1);
			$route = '/' . $this->config('app.rootpath') . '/' . implode('/', $routeArray) . '(/)';
			$route = str_replace('//', '/', $route);

			// Check if it has the number of required params
			if ($numberOfParams > 0) {
				if ($paramCount >= $numberOfReqParams && $paramCount <= $numberOfParams) {
					
					// Add arguments to the route
					$route .= '(:args+)';

					// Create the route
					$this->create_route($route, $class, $function, $method);
					return TRUE;
				}
			} elseif ($paramCount <= 0) {
				// Create the route
				$this->create_route($route, $class, $function, $method);
				return TRUE;
			}
		}

		return FALSE;
	}

	// Create a route by passing in variables
	public function create_route($route, $class, $function, $method) {
		$app = $this;

		// Check for a method specific route
		if (is_callable($class . "::{$function}__{$method}")) {
			$this->{$method}($route, function($args = NULL) use ($app, $class, $function, $method) {
				$controller = new $class($app);
				call_func(array($controller, "{$function}__{$method}"), $args); // Uses a double underscore
			});
		}

		// Check for a catch-all route
		else {

			if (is_callable($class . "::{$function}")) {
				$this->map($route, function($args = NULL) use ($app, $class, $function) {
					$controller = new $class($app);
					call_func(array($controller, $function), $args);
				})->via('GET', 'POST', 'PUT', 'DELETE');
			}

		}
	}
	// END ROUTING FUNCTIONS
	// ------------------------------


	// ------------------------------
	// LOADER FUNCTIONS

	// Load a helper file
	public function load_helper($fileName) {

		if (file_exists(ROOT . '/' . $this->config['name'] . "/helpers/{$fileName}.php")) {
			include(ROOT . '/' . $this->config['name'] . "/helpers/{$fileName}.php");
		}

	}

	// END LOADER FUNCTIONS
	// ------------------------------

}
