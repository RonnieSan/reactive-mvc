<?php
// REACTIVE FRAMEWORK
// Application Framework

namespace Reactive;

Class App extends \Slim\Slim
{

	public $db;
	public $view; // We need to make the view class public
	
	public function __construct($settings) {
		parent::__construct($settings);

		// Connect to the database
		$this->db = new \Libraries\Database();
		$this->db->connect();

		// Set the 404 page
		$this->notFound(function() {
			$this->render($this->config('404.template'));
		});
	}

	// ------------------------------
	// AUTOLOADER
	public static function autoload($className)
	{

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
		$filePath      = '';

		// Check for an existing file path
		foreach ($filePathArray as $segment) {

			// Check if this segment is a directory
			if (is_dir($baseDir . $filePath . $segment)) {
				$filePath .= $segment . DIRECTORY_SEPARATOR;
				continue;
			}

			// Try the segment with dashes
			if (is_dir($baseDir . $filePath . str_replace('_', '-', $segment))) {
				$filePath .= str_replace('_', '-', $segment) . DIRECTORY_SEPARATOR;
				continue;
			}

			// Try the segment in lowercase
			if (is_dir($baseDir . $filePath . strtolower($segment))) {
				$filePath .= strtolower($segment) . DIRECTORY_SEPARATOR;
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

		// Check if the dashed file exists
		if (file_exists($filePath . str_replace('_', '-', $className) . '.php')) {
			require_once $filePath . str_replace('_', '-', $className) . '.php';
			return;
		}

		// Check if the lowercase file exists
		if (file_exists($filePath . strtolower($className) . '.php')) {
			require_once $filePath . strtolower($className) . '.php';
			return;
		}

		// Check if the lowercase file with dashes exists
		if (file_exists($filePath . str_replace('_', '-', strtolower($className)) . '.php')) {
			require_once $filePath . str_replace('_', '-', strtolower($className)) . '.php';
			return;
		}

	}

	
	// Register Slim's PSR-0 autoloader
	public function register_autoloader()
	{
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

		// Create an array of properly formatted namespaces
		$namespacedURI = array();

		foreach ($uriValues as $value) {
			$namespacedURI[] = str_replace(' ', '_', ucwords(str_replace('-', ' ', $value)));
		}

		// Use the root class unless something else matches
		$class      = '\\Controllers\\' . $this->config('class.root');
		$function   = 'index';
		$paramCount = 0;
		$argString  = '';

		// Reduce the URI array
		while (count($namespacedURI) > 0) {

			// Check if the class exists
			$namespacedClass = implode('\\', $namespacedURI);

			if (class_exists('\\Controllers\\' . $namespacedClass)) {
				$class = '\\Controllers\\' . $namespacedClass;
				if ($this->_check_method($class, $function, $paramCount, $uriValues, $namespacedURI, $method)) {
					break;
				}
			}

			if (class_exists('\\Controllers\\' . $namespacedClass . '\\Root')) {
				$class = '\\Controllers\\' . $namespacedClass . '\\Root';
				if ($this->_check_method($class, $function, $paramCount, $uriValues, $namespacedURI, $method)) {
					break;
				}
			}

			// Remove the last item in the array and add it to the args array
			$function = array_pop($namespacedURI);
			$paramCount++;

		}

	}

	// Check if a method exists and if the number of params matches
	private function _check_method($class, $function, $paramCount, $uriValues, $namespacedURI, $method) {
		// Check if the first argument is a callable method
		if (method_exists($class, $function)) {
			
			// Check if the method accepts parameters
			$reflection        = new \ReflectionMethod($class, $function);
			$numberOfParams    = $reflection->getNumberOfParameters();
			$numberOfReqParams = $reflection->getNumberOfRequiredParameters();
			$paramCount--;

			$routeArray = array_slice($uriValues, 0, count($namespacedURI) + 1);
			$route = '/' . implode('/', $routeArray) . '(/)';

			// Check if there are params
			if ($numberOfParams > 0) {
				
				// Check if it has the number of required params
				if ($paramCount >= $numberOfReqParams && $paramCount <= $numberOfParams) {

					// Create the route
					$route .= '(:args+)';
					$this->create_route($route, $class, $function, $method);
					return TRUE;

				}

			} else {

				// Create the route
				$this->create_route($route, $class, $function, $method);
				return TRUE;

			}
		}
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

		if (file_exists(ROOT . "/helpers/{$fileName}.php")) {
			include(ROOT . "/helpers/{$fileName}.php");
		}

	}

	// END LOADER FUNCTIONS
	// ------------------------------

}
