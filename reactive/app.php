<?php
namespace Reactive;

Class App extends \Slim\Slim
{

	// We need to make the view class public
        public $view;
        public $api;
        public $session;
        
        public function __construct($settings) {
		parent::__construct($settings);
        
            //bootstrap the API
            $this->api = \Integration\Api::bootstrap(\Icm_Config::fromIni('config/api_config.ini'));
            $this->session = new \Icm_Session_Namespace($settings['session_namespace']);
        }

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

		// What is the class and function we should try to call
		if (count($uriValues) > 0) {
			$class     = str_replace(' ', '_', ucwords(str_replace('-', ' ', $uriValues[0])));
			$function  = isset($uriValues[1]) ? str_replace('-', '_', $uriValues[1]) : 'index';
		} else {
			$class    = '';
			$function = '';
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
			$this->create_route($route, $nsClass, $function, $method);
		}

		// Try the root controller
		else {

			$nsClass = '\\Controllers\\Root';

			$function = isset($uriValues[0]) ? $uriValues[0] : 'index';

			// Check if we should be calling the index function
			if ($function === 'index') {
				$route = "/";
			} else {
				$route    = "/{$uriValues[0]}(/)(:args+)";
			}

			// Create the route
			$this->create_route($route, $nsClass, $function, $method);

		}

	}

	// Create a route by passing in variables
	public function create_route($route, $class, $function, $method) {
		$app = $this;

		// Check for a method specific route
		if (is_callable($class . "::{$function}_{$method}")) {
			$this->{$method}($route, function($args = NULL) use ($app, $class, $function, $method) {
				$controller = new $class($app);
				call_func(array($controller, "{$function}_{$method}"), $args);
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

}
