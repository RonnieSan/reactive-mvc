<?php
// REACTIVE FRAMEWORK
// Application Framework

namespace Reactive;

class App extends \Slim\Slim
{
	
	public function __construct($settings) {
		parent::__construct($settings);

		// ROUTES
		// Include the list of manually set routes
		require $settings['app.folder'] . '/routes.php';

		// HOOKS
		// Include a list of custom hooks
		require $settings['app.folder'] . '/hooks.php';

		// Register the autoloader
		$this->register_autoloader();

		// Define the application root
		define('APP_ROOT', ROOT . DIRECTORY_SEPARATOR . $settings['app.folder']);

		// Set the 404 page
		$this->notFound(function() use ($settings) {
			$this->render($settings['404.template']);
		});
	}

	// --------------------------------------------------
	// SLUGIFY A STRING/ARRAY DIFFERENT WAYS
	// You may pass in a string or an array of strings

	static function slugify($input, $filters = array('lowercase', 'dashes'), $returnString = FALSE) {

		if (!is_array($input)) {
			$returnString = TRUE;
			$input = array($input);
		}

		$input = array_map(function($segment) use ($filters) {

			// Replace underscores with dashes
			if (in_array('dashes', $filters)) {
				$segment = str_replace('_', '-', $segment);
			}

			// Split camelCase into words
			$words = preg_split('/(?=[A-Z])/', $segment);

			// Remove empty segments
			$words = array_filter($words, function($value) { return !empty($value); });

			// Join segments with dashes
			if (in_array('dashes', $filters)) {
				$segment = implode('-', $words);
			}

			// Join everything with underscores
			if (in_array('underscores', $filters)) {
				$segment = str_replace('-', '_', $segment);
			}

			// Collapse all word delimiters
			if (in_array('collapse', $filters)) {
				$segment = str_replace(array('-', '_'), '', $segment);
			}

			// Make everything lowercase
			if (in_array('lowercase', $filters)) {
				$segment = strtolower($segment);
			}

			// Replace double dashes
			$segment = str_replace('--', '-', $segment);

			return $segment;

		}, $input);

		if ($returnString) {
			return $input[0];
		}

		return $input;

	}

	// END SLUGIFY A STRING/ARRAY DIFFERENT WAYS
	// --------------------------------------------------


	// --------------------------------------------------
	// FORMAT AS A VALID NAMESPACE
	
	static function namespacify($value) {
		return str_replace(' ', '_', ucwords(str_replace('-', ' ', $value)));
	}
	
	// END FORMAT AS A VALID NAMESPACE
	// --------------------------------------------------


	// --------------------------------------------------
	// AUTOLOADER
	public static function reactive_autoload($className) {

		// *** Comments assume \Libraries\ClassName is being auto-loaded ***

		// Get an instance of the app object
		$app = \Slim\Slim::getInstance();

		// Trim the opening slash from the namespaced class that was passed in
		$class    = ltrim($className, '\\');
		$segments = explode('\\', $class);

		// Set the last segment as the class name
		$className = array_pop($segments);

		
		// --------------------------------------------------
		// TRY VARIATIONS OF NAMING CONVENTIONS

		// This section allows you to use different naming conventions for your file structure
		// i.e. /Libraries/SomeClass, /Libraries/Some_Class, etc.

		$basePaths[]   = $app->config('app.folder');
		$basePaths     = array_merge($basePaths, $app->config('app.parents'));
		$basePaths[]   = 'core';

		$baseFolders   = array('', 'Libraries' . DIRECTORY_SEPARATOR, 'libraries' . DIRECTORY_SEPARATOR);
		
		// Unchanged
		$directories[] = implode(DIRECTORY_SEPARATOR, $segments);
		$filenames[]   = $className;

		// Underscores
		$directories[] = implode(DIRECTORY_SEPARATOR, self::slugify($segments, array('underscores')));
		$filenames[]   = self::slugify($className, array('underscores'));

		// Underscores and lowercase
		$directories[] = implode(DIRECTORY_SEPARATOR, self::slugify($segments, array('underscores', 'lowercase')));
		$filenames[]   = self::slugify($className, array('underscores', 'lowercase'));

		// Dashes
		$directories[] = implode(DIRECTORY_SEPARATOR, self::slugify($segments, array('dashes')));
		$filenames[]   = self::slugify($className, array('dashes'));

		// Dashes and lowercase
		$directories[] = implode(DIRECTORY_SEPARATOR, self::slugify($segments, array('dashes', 'lowercase')));
		$filenames[]   = self::slugify($className, array('dashes', 'lowercase'));

		// Collapsed
		$directories[] = implode(DIRECTORY_SEPARATOR, self::slugify($segments, array('collapse')));
		$filenames[]   = self::slugify($className, array('collapse'));

		// Collapsed and lowercase
		$directories[] = implode(DIRECTORY_SEPARATOR, self::slugify($segments, array('collapse', 'lowercase')));
		$filenames[]   = self::slugify($className, array('collapse', 'lowercase'));

		// Build the test cases
		foreach ($basePaths as $basePath) {
			foreach ($baseFolders as $baseFolder) {
				foreach ($directories as $directory) {
					foreach ($filenames as $filename) {
						$testCases[] = $basePath . DIRECTORY_SEPARATOR . $baseFolder . $directory . DIRECTORY_SEPARATOR . $filename . '.php';
					}
				}
			}
		}
		$testCases = array_unique($testCases);

		// Check if any of the test cases exist
		foreach ($testCases as $key => $testCase) {
			if (file_exists($testCase)) {
				require_once $testCase;
				return TRUE;
			}
		}
		
		// END TRY VARIATIONS OF NAMING CONVENTIONS
		// --------------------------------------------------

		return FALSE;

	}

	
	// Register the Reactive autoloader autoloader
	public function register_autoloader() {
		spl_autoload_register(__NAMESPACE__ . "\\App::reactive_autoload");
	}
	// END AUTOLOADER
	// --------------------------------------------------

	// --------------------------------------------------
	// ROUTING FUNCTIONS
	// Generate an auto-route based on the URI
	public function generate_routes() {

		// Get the request object and method
		$request       = $this->request();
		$requestMethod = strtolower($request->getMethod());

		// Get the URI
		$uri     = $request->getResourceUri();
		$rootURI = $this->config('app.rooturi');

		// Check if the first segment matches the app.rooturi in the config
		if (!empty($rootURI) && strpos($uri, $rootURI) == 0) {
			$uri = substr($uri, strlen($rootURI));
		}

		// Explode the URI into segments
		$uriSegments = explode('/', $uri);
		$uriSegments = array_filter($uriSegments, function($value) { return $value != ''; });
		$uriSegments = array_values($uriSegments);

		// Create an array of properly formatted namespaces
		// Capitalize each word and replace dashes with underscores
		$nsUriSegments = array('Controllers');
		foreach ($uriSegments as $segment) {
			$nsUriSegments[] = self::namespacify($segment);
		}

		// URI Segments will be popped off the array and stored as params
		$params   = array();
		$nsParams = array();

		// Get the name of the default class from the config file
		$defaultClass = $this->config('class.default');

		// Loop through the URI segments looking for a valid route
		while ($nsUriSegments) {

			// Check for a root class and index method
			// \Path\To\Folder\Root::index()
			$namespace = '\\' . implode('\\', $nsUriSegments) . '\\';
			if ($this->_create_route_if_method_exists($namespace . $defaultClass, 'index', $params, $uriSegments, $requestMethod)) {
				break;
			}

			if (count($uriSegments)) {
				// Check for a class name and index method
				// \Path\To\Folder::index()
				$namespace = '\\' . implode('\\', array_slice($nsUriSegments, 0, -1)) . '\\';
				if ($this->_create_route_if_method_exists($namespace . end($nsUriSegments), 'index', $params, $uriSegments, $requestMethod)) {
					break;
				}

				// Check for a root class and method name
				// \Path\To\Root::folder()
				$namespace = '\\' . implode('\\', array_slice($nsUriSegments, 0, -1)) . '\\';
				if ($this->_create_route_if_method_exists($namespace . $defaultClass, strtolower(end($nsUriSegments)), $params, $uriSegments, $requestMethod)) {
					break;
				}
			}

			if (count($uriSegments) > 1) {
				// Check for a class name and method name
				// \Path\To::folder()
				$namespace = '\\' . implode('\\', array_slice($nsUriSegments, 0, -2)) . '\\';
				if ($this->_create_route_if_method_exists($namespace . $nsUriSegments[count($nsUriSegments) - 2], strtolower(end($nsUriSegments)), $params, $uriSegments, $requestMethod)) {
					break;
				}
			}

			// Pop off the last URI segment and store it as a param
			array_unshift($params, array_pop($uriSegments));

			// Pop off the last namespaced URI segment to keep the two arrays synched
			array_unshift($nsParams, array_pop($nsUriSegments));

		}
	}

	// Check if a method exists and if the number of params matches
	private function _create_route_if_method_exists($class, $function, $params, $uriSegments, $method) {

		// Check if the first argument is a callable method
		if (method_exists($class, $function)) {

			// Check if the method accepts parameters
			$reflection        = new \ReflectionMethod($class, $function);
			$numberOfParams    = $reflection->getNumberOfParameters();
			$numberOfReqParams = $reflection->getNumberOfRequiredParameters();

			$route = '/' . $this->config('app.rooturi') . '/' . implode('/', $uriSegments) . '(/)';
			$route = str_replace('//', '/', $route);

			$paramCount = count($params);

			// Check if it has the number of required params
			if ($numberOfParams > 0) {
				if ($paramCount >= $numberOfReqParams && $paramCount <= $numberOfParams) {
					
					// Add arguments to the route
					$route .= '(:args+)';

					// Create the route
					$this->_create_route($route, $class, $function, $method);
					return TRUE;
				}
			}

			elseif ($paramCount <= 0) {
				// Create the route
				$this->_create_route($route, $class, $function, $method);
				return TRUE;
			}
		}

		return FALSE;
	}

	// Create a route by passing in variables
	private function _create_route($route, $class, $function, $method) {
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
				})->via('GET', 'POST', 'PUT', 'PATCH', 'DELETE');
			}

		}
	}

	// END ROUTING FUNCTIONS
	// --------------------------------------------------


	// --------------------------------------------------
	// LOADER FUNCTIONS

	// Load a helper file
	public function load_helpers($fileName) {

		$basePaths[] = $this->config('app.folder');
		$basePaths   = array_merge($basePaths, $this->config('app.parents'));
		$basePaths[] = 'core';

		$directories = array('helpers', 'Helpers');

		foreach ($basePaths as $basePath) {
			foreach ($directories as $directory) {
				$filePath = $basePath . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $fileName . '.php';
				if (is_file($filePath)) {
					require_once $filePath;
				}
			}
		}
	}

	// END LOADER FUNCTIONS
	// --------------------------------------------------


	// --------------------------------------------------
	// RENDER METHOD
	// We have to override Slim's render so we can pass
	// some config settings into the view

	public function render($template, $data = array(), $status = null) {
		if (!is_null($status)) {
			$this->response->status($status);
		}
		$this->view->scriptFolder = $this->config('app.scripts');
		$this->view->setTemplatesDirectory($this->config('templates.path'));
		$this->view->appendData($data);
		$this->view->display($template);
	}
	
	// END RENDER METHOD
	// --------------------------------------------------

}
